Správa databáze a zálohování - interaktivní menu.

**Credentials:** host=`database`, user=`root`, password=`root`, db=`iordinace`
**Adminer:** http://localhost:8080

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

4. **Database Info**
   - Velikost databáze, počet tabulek, největší tabulky

5. **Vlastní SQL dotaz**
   - Spusť libovolný SQL dotaz

6. **Troubleshooting**
   - Test připojení, oprav poškozené tabulky

Po výběru proveď odpovídající akci.

---

## Database Operations - Referenční dokumentace

### 1. Backup databáze

```bash
mkdir -p backups
docker compose exec database mysqldump -uroot -proot iordinace \
  | gzip > backups/backup-$(date +%Y-%m-%d-%H%M%S).sql.gz
```

### 2. Restore databáze

```bash
# Z komprimovaného backupu
gunzip < backups/backup-YYYY-MM-DD-HHMMSS.sql.gz \
  | docker compose exec -T database mysql -uroot -proot iordinace
```

⚠️ **POZOR:** Restore přepíše celou databázi!

### 3. Seznam backupů

```bash
ls -lht backups/*.sql.gz | head -20
```

### 4. Databázové info

**Připoj se k databázi:**
```bash
docker compose exec database mysql -uroot -proot iordinace
```

**Zobraz tabulky:**
```bash
docker compose exec database mysql -uroot -proot -e "SHOW TABLES" iordinace
```

**Velikost databáze:**
```sql
SELECT
  table_name AS 'Tabulka',
  ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Velikost (MB)'
FROM information_schema.TABLES
WHERE table_schema = 'iordinace'
ORDER BY (data_length + index_length) DESC;
```

**Spusť jako příkaz:**
```bash
docker compose exec database mysql -uroot -proot -e "
SELECT table_name, ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM information_schema.TABLES WHERE table_schema = 'iordinace'
ORDER BY size_mb DESC;" iordinace
```

### 5. Vlastní SQL dotaz

```bash
docker compose exec database mysql -uroot -proot -e "SELECT * FROM aktuality LIMIT 5" iordinace
```

### 6. Troubleshooting

**Test připojení:**
```bash
docker compose exec database mysql -uroot -proot -e "SELECT 1" iordinace
```

**Restart databáze:**
```bash
docker compose restart database
```

**Zkontroluj logy databáze:**
```bash
docker compose logs database
```

**Oprav poškozené tabulky:**
```bash
docker compose exec database mysqlcheck -uroot -proot --auto-repair iordinace
```

### 7. Automatické zálohy

**Cron job na serveru:**
```bash
# Backup každý den ve 2:00
0 2 * * * cd /var/www/html && mysqldump -h database -uroot -proot iordinace | gzip > /var/www/html/backups/auto-backup-$(date +\%Y-\%m-\%d).sql.gz && find /var/www/html/backups/ -name "auto-backup-*.sql.gz" -mtime +7 -delete
```

### Užitečné SQL dotazy pro iOrdinace

```sql
-- Aktuality (hlavní tabulka)
SELECT idaktualita, nadpis, zobrazit, ts FROM aktuality ORDER BY orders;

-- Počet aktualit podle viditelnosti
SELECT zobrazit, COUNT(*) FROM aktuality GROUP BY zobrazit;

-- Posledních 10 aktualit
SELECT idaktualita, nadpis, ts FROM aktuality ORDER BY ts DESC LIMIT 10;
```

## Checklist pro database operations

**Před restore:**
- [ ] Vytvořen backup aktuální databáze
- [ ] Dostatek místa na disku

**Po restore:**
- [ ] Test připojení aplikace: http://localhost/
- [ ] Zkontroluj error log
