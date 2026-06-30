Správa databáze a zálohování - interaktivní menu.

**NOTE:** Tento příkaz je generický. Pro specifický projekt uprav:
- Database credentials (aktuálně: server=database, user=root, pass=root, db=drupal)
- Cesty k backups/ adresáři (bude vytvořen automaticky pokud neexistuje)
- Commerce SQL dotazy (pokud nepoužíváš Drupal Commerce)

**IMPORTANT:** Hned na začátku použij AskUserQuestion tool a nabídni uživateli tyto možnosti:

1. **Backup databáze**
   - Vytvoř novou zálohu aktuální databáze
   - Uloží se do složky backups/ s timestampem

2. **Restore databáze**
   - Obnov databázi ze zálohy
   - Zobrazí seznam dostupných backupů k výběru
   - ⚠️ POZOR: Přepíše aktuální databázi!

3. **Seznam backupů**
   - Zobraz všechny dostupné zálohy
   - Zobrazí datum vytvoření a velikost souboru

4. **Database Info**
   - Zobraz velikost databáze a počet tabulek
   - Zobraz 10 největších tabulek
   - Zobraz konfiguraci databáze

5. **Cleanup & Optimalizace**
   - Smaž staré watchdog záznamy (30+ dní)
   - Optimalizuj cache tabulky
   - Smaž staré revisions

6. **Užitečné SQL dotazy**
   - Počet objednávek podle stavu
   - Seznam aktivních uživatelů
   - Produkty bez stock level
   - Vlastní SQL dotaz

7. **Synchronizace**
   - Připrav instrukce pro Stage → Local sync
   - Připrav instrukce pro Production → Local sync (s anonymizací)

8. **Troubleshooting**
   - Test databázového připojení
   - Zkontroluj error log
   - Oprav poškozené tabulky

9. **Config Management**
   - Export konfigurace (drush cex)
   - Import konfigurace (drush cim)
   - Zkontroluj config rozdíly

10. **Kompletní dokumentace**
   - Zobraz celou dokumentaci níže

Po výběru proveď odpovídající akci. Pokud uživatel vybere "Kompletní dokumentace", zobraz mu níže uvedenou referenční dokumentaci.

---

## Database Operations - Referenční dokumentace

### 1. Backup databáze

**Vytvoř backup lokální databáze:**
```bash
# Pomocí drush (doporučeno) — POZOR: --result-file musí být absolutní cesta!
vendor/bin/drush sql:dump --gzip --result-file=/var/www/html/backups/local-backup-$(date +%Y-%m-%d-%H%M%S).sql
```

**Poznámka k SSL:** MariaDB container používá self-signed certifikát. Aby drush sql:dump fungoval,
musí existovat soubor `drush/drush.yml` s konfigurací `extra-dump: '--skip-ssl'`.
Tento soubor je součástí projektu — pokud chybí, vytvoř ho:
```yaml
# drush/drush.yml
options:
  uri: 'http://localhost'
command:
  sql:
    dump:
      options:
        extra-dump: '--skip-ssl'
```

**Poznámka:** Deploy skripty (pokud existují) mohou vytvářet automatické backupy před deploymentem.

### 2. Restore databáze

**Z komprimovaného backupu:**
```bash
# Pomocí drush (doporučeno)
gunzip < backups/local-backup-2025-11-07-140000.sql.gz | vendor/bin/drush sql:cli
```

**Z nekomprimovaného backupu:**
```bash
vendor/bin/drush sql:cli < backups/backup.sql
```

**⚠️ POZOR:** Restore přepíše celou databázi!

**Po restore VŽDY spusť:**
```bash
vendor/bin/drush cr        # Vyčisti cache
vendor/bin/drush updb -y   # Update databáze
vendor/bin/drush cim -y    # Synchronizuj config (pokud potřeba)
```

### 3. Seznam backupů

**Zobraz všechny backupy:**
```bash
# Zobraz seznam se stářím a velikostí
ls -lh backups/*.sql* | awk '{print $9, $6, $7, $8, $5}'
```

**Nebo podrobněji s datem:**
```bash
# Zobraz backupy seřazené podle data
ls -lht backups/*.sql* | head -20
```

**Najdi nejnovější backup:**
```bash
ls -t backups/*.sql.gz | head -1
```

### 4. Synchronizace databáze

**Stage → Local (pokud máš remote STAGE server):**
```bash
# 1. Vytvoř backup na STAGE serveru
ssh user@stage-server "cd /path/to/project && vendor/bin/drush sql:dump --gzip --result-file=/tmp/stage-db.sql"

# 2. Stáhni backup
scp user@stage-server:/tmp/stage-db.sql.gz backups/

# 3. Restore lokálně
gunzip < backups/stage-db.sql.gz | vendor/bin/drush sql:cli

# 4. Vyčisti cache a update databázi
vendor/bin/drush cr
vendor/bin/drush updb -y
vendor/bin/drush cim -y
```

**Production → Local (OPATRNĚ - pokud máš remote PRODUCTION server):**
```bash
# ⚠️ POUZE pro debugging produkčních problémů!
# ⚠️ OBSAHUJE citlivá data zákazníků!

# 1. Backup z produkce
ssh user@prod-server "cd /path/to/project && vendor/bin/drush sql:dump --gzip --result-file=/tmp/prod-db.sql"

# 2. Stáhni
scp user@prod-server:/tmp/prod-db.sql.gz backups/prod-snapshot-$(date +%Y-%m-%d).sql.gz

# 3. Restore
gunzip < backups/prod-snapshot-*.sql.gz | vendor/bin/drush sql:cli

# 4. DŮLEŽITÉ: ANONYMIZUJ DATA! (viz sekce níže)
vendor/bin/drush cr
```

### 5. Anonymizace produkční databáze

**Pokud potřebuješ produkční data pro testing:**

```sql
-- Připoj se k databázi
vendor/bin/drush sql:cli

-- Anonymizuj uživatelská data
UPDATE users_field_data SET mail = CONCAT('user', uid, '@example.com'), name = CONCAT('user', uid) WHERE uid > 0;

-- Anonymizuj objednávky (adresy, emails)
UPDATE commerce_order__billing_profile SET billing_profile_target_id = NULL;
UPDATE commerce_order SET mail = CONCAT('order', order_id, '@example.com');

-- Smaž session tokens
TRUNCATE TABLE sessions;

exit;
```

**Resetuj hesla uživatelů:**
```bash
# Nastav heslo "admin" pro všechny uživatele
vendor/bin/drush user:password admin admin
vendor/bin/drush user:password user1 admin
# Nebo hromadně přes SQL (bezpečnější přes drush):
# Pro všechny uživatele použij stejné heslo
```

**Vyčisti cache po anonymizaci:**
```bash
vendor/bin/drush cr
```

### 6. Database Info

**Zobraz info o databázi:**
```bash
vendor/bin/drush sql:conf
# Alias: vendor/bin/drush sqlc
```

**Připoj se k databázi:**
```bash
vendor/bin/drush sql:cli
# Alias: vendor/bin/drush sqlc
```

**Spusť SQL query:**
```bash
vendor/bin/drush sql:query "SELECT * FROM users_field_data LIMIT 5"
# Alias: vendor/bin/drush sqlq
```

**Velikost databáze:**
```bash
vendor/bin/drush sql:query "SELECT
  table_schema AS 'Database',
  ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.TABLES
WHERE table_schema = 'drupal'
GROUP BY table_schema"
```

### 7. Cleanup databáze

**Smaž staré watchdog záznamy:**
```bash
vendor/bin/drush wd:delete all
# Nebo jen staré záznamy
vendor/bin/drush sql:query "DELETE FROM watchdog WHERE timestamp < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))"
```

**Smaž staré revisions:**
```bash
# POZOR: Toto smaže historii!
vendor/bin/drush sql:query "DELETE FROM node_revision WHERE vid NOT IN (SELECT vid FROM node_field_data)"
```

**Optimalizuj tabulky:**
```bash
vendor/bin/drush sql:query "OPTIMIZE TABLE watchdog, cache_bootstrap, cache_config, cache_data, cache_default, cache_entity"
```

### 8. Database Troubleshooting

**Zkontroluj připojení:**
```bash
vendor/bin/drush sql:query "SELECT 1"
```

**Zkontroluj tabulky:**
```bash
vendor/bin/drush sql:query "SHOW TABLES"
```

**Zkontroluj character set:**
```bash
vendor/bin/drush sql:query "SHOW VARIABLES LIKE 'character_set%'"
```

**Oprav poškozené tabulky:**
```bash
vendor/bin/drush sql:query "REPAIR TABLE <table_name>"
```

### 9. Import/Export Config

**Export aktuální konfigurace:**
```bash
vendor/bin/drush cex -y
```

**Import konfigurace ze souborů:**
```bash
vendor/bin/drush cim -y
```

**Partial import (přeskočí chybějící moduly):**
```bash
vendor/bin/drush cim --partial -y
```

**Zkontroluj rozdíly:**
```bash
vendor/bin/drush config:status
# Alias: vendor/bin/drush cst
```

### 10. Adminer (Database UI)

**Přístup k Adminer:**
- URL: `http://localhost:3000`
- Server: `database`
- Username: `root`
- Password: `root`
- Database: `drupal`

**Co můžeš dělat:**
- Prohlížet tabulky
- Spouštět SQL dotazy
- Exportovat/importovat data
- Upravovat strukturu tabulek

### 11. Automatické zálohy

**Cron job pro automatické backups (na serveru):**

```bash
# Edituj crontab
crontab -e

# Přidej řádek (backup každý den ve 2:00)
0 2 * * * cd /var/www/html && vendor/bin/drush sql:dump --gzip --result-file=/var/www/html/backups/auto-backup-$(date +\%Y-\%m-\%d).sql && find /var/www/html/backups/ -name "auto-backup-*.sql.gz" -mtime +7 -delete

# Vysvětlení:
# - Backup každý den ve 2:00 AM
# - Uloží jako backups/auto-backup-YYYY-MM-DD.sql.gz
# - Smaže backupy starší než 7 dní
```

## Checklist pro database operations

**Před restore:**
- [ ] Vytvořen backup aktuální databáze
- [ ] Zkontrolován source backup (není poškozený)
- [ ] Zavřeny všechny připojení k databázi
- [ ] Dostatek místa na disku

**Po restore:**
- [ ] Cache vyčištěna (`drush cr`)
- [ ] Database updates spuštěny (`drush updb -y`)
- [ ] Config synchronizována (`drush cim -y` nebo `drush cex -y`)
- [ ] Web funguje (otevři v prohlížeči)
- [ ] Zkontroluj error log (`drush wd-show`)

## Užitečné SQL dotazy

**Počet objednávek podle stavu:**
```sql
SELECT state, COUNT(*) as count
FROM commerce_order
GROUP BY state;
```

**Seznam aktivních uživatelů:**
```sql
SELECT uid, name, mail, created
FROM users_field_data
WHERE status = 1
ORDER BY created DESC
LIMIT 10;
```

**Produkty bez stock level:**
```sql
SELECT cpv.sku, cpv.title
FROM commerce_product_variation_field_data cpv
LEFT JOIN commerce_product_variation__field_stock_level stock ON cpv.variation_id = stock.entity_id
WHERE stock.field_stock_level_available IS NULL;
```

**Produkty s nízkým skladem (méně než 10 ks):**
```sql
SELECT
  cpv.sku,
  cpv.title,
  stock.field_stock_level_available as stock_quantity
FROM commerce_product_variation_field_data cpv
LEFT JOIN commerce_product_variation__field_stock_level stock
  ON cpv.variation_id = stock.entity_id
WHERE stock.field_stock_level_available < 10
  AND stock.field_stock_level_available IS NOT NULL
ORDER BY stock.field_stock_level_available ASC;
```

**Stock transakce pro konkrétní produkt (audit trail):**
```sql
SELECT
  cst.id,
  cst.entity_id,
  cst.qty as quantity_change,
  cst.transaction_time,
  cst.transaction_type_id,
  cpv.sku,
  cpv.title
FROM commerce_stock_transaction cst
LEFT JOIN commerce_product_variation_field_data cpv
  ON cst.entity_id = cpv.variation_id
WHERE cpv.sku = 'YOUR-SKU-HERE'
ORDER BY cst.transaction_time DESC
LIMIT 20;
```

**Velikost jednotlivých tabulek:**
```sql
SELECT
  table_name AS 'Table',
  ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.TABLES
WHERE table_schema = 'drupal'
ORDER BY (data_length + index_length) DESC
LIMIT 20;
```

## Emergency Database Recovery

**Pokud se databáze rozbije:**

1. **Zkontroluj log:**
   ```bash
   docker-compose logs database
   ```

2. **Restart database container:**
   ```bash
   docker-compose restart database
   ```

3. **Restore z posledního backupu:**
   ```bash
   gunzip < backups/[latest-backup].sql.gz | vendor/bin/drush sql:cli
   vendor/bin/drush cr
   ```

4. **Pokud backup chybí, zkus opravit tabulky:**
   ```bash
   vendor/bin/drush sql:cli
   SHOW TABLES;
   REPAIR TABLE [damaged_table];
   exit;
   ```
