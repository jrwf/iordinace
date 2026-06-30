Rychlý workflow pro opravu chyby (bugfix/hotfix):

**NOTE:** Tento příkaz je generický. Pro specifický projekt uprav:
- Theme název v sekci 2 (aktuálně používá se: `woodyt`)
- Cesty k custom modulům (pokud máš jiné)
- Error log cesty (podle tvého prostředí)

## Quick Fix Workflow

Pro rychlé opravy chyb bez velkých změn v konfiguraci.

### 1. Identifikuj problém

**Zkontroluj error log:**
```bash
vendor/bin/drush wd-show --severity=Error --count=20
```

**Nebo v prohlížeči:**
- `/admin/reports/dblog`

**Zkontroluj PHP errors:**
```bash
tail -f /var/log/apache2/error.log
# Nebo kde máš error log
```

### 2. Vytvoř opravu

**Uprav kód v:**
- `web/modules/custom/` - custom moduly
- `web/themes/custom/woodyt/` - theme (nebo jiný aktivní theme)
- Nebo config v `config/sync/`

### 3. Otestuj opravu

**Vyčisti cache:**
```bash
vendor/bin/drush cr
```

**Otestuj v prohlížeči:**
- Zkontroluj, že chyba je opravena
- Zkontroluj, že nic jiného se nerozbilo

**Zkontroluj error log znovu:**
```bash
vendor/bin/drush wd-show --severity=Error --count=10
```

### 4. Připrav commit

**Pokud jsi měnil config v UI:**
```bash
vendor/bin/drush cex -y
```

**Zkontroluj změny:**
```bash
git status
git diff
```

### 5. Commit fix

**Přidej změněné soubory:**
```bash
# Zjisti, co se změnilo
git status

# Přidej pouze soubory související s fixem
git add <soubory>
```

**Vytvoř commit:**
```bash
git commit -m "$(cat <<'EOF'
Fix: <Stručný popis problému>

<Detailnější popis:>
- Co bylo špatně
- Jak to bylo opraveno
- Kde se chyba projevovala

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
EOF
)"
```

### 6. Deploy fix

**Pro urgentní hotfix:**

1. **Push do repozitáře:**
   ```bash
   git push origin master
   ```

2. **Deploy na server (pokud máš remote prostředí):**
   ```bash
   # SSH na server a pull změny
   ssh user@your-server
   cd /path/to/project
   git pull origin master
   vendor/bin/drush cr
   vendor/bin/drush updb -y
   vendor/bin/drush cim -y  # pokud jsou config změny
   ```

3. **Pro lokální Docker prostředí:**
   ```bash
   # Změny jsou už v kódu, jen vyčisti cache
   vendor/bin/drush cr
   ```

## Příklady fixů

### PHP Error Fix

**Problém:** Undefined array key
```php
// ❌ Špatně
$value = $array['key'];

// ✅ Opraveno
$value = $array['key'] ?? 'default';
```

**Commit:**
```
Fix: Undefined array key 'field_name' v ShippingPriceProcessor

- Přidána kontrola existence klíče před přístupem
- Nastavena default hodnota pokud klíč neexistuje
- Chyba se projevovala při checkout bez shipping method
```

### Twig Error Fix

**Problém:** Accessing null value
```twig
{# ❌ Špatně #}
{{ node.field_image.entity.uri.value }}

{# ✅ Opraveno #}
{% if node.field_image.entity %}
  {{ node.field_image.entity.uri.value }}
{% endif %}
```

**Commit:**
```
Fix: Null pointer exception v product-teaser.html.twig

- Přidána kontrola existence entity před přístupem
- Zabraňuje chybě při produktech bez obrázku
```

### CSS Fix

**Problém:** Rozbitý layout na mobile
```css
/* ❌ Špatně */
.container {
  width: 1200px;
}

/* ✅ Opraveno */
.container {
  max-width: 1200px;
  width: 100%;
}
```

**Commit:**
```
Fix: Rozbitý layout košíku na mobilních zařízeních

- Změna width na max-width pro responsivitu
- Container nyní správně reaguje na menší displeje
- Testováno na viewport 320px - 768px
```

### Config Fix

**Problém:** Špatně nastavený View filter
```bash
# Uprav v UI: /admin/structure/views/view/stock_overview
# Export config
vendor/bin/drush cex -y
```

**Commit:**
```
Fix: Stock overview zobrazoval i vypnuté produkty

- Přidán filter na published status
- View nyní zobrazuje pouze aktivní produkty
- Config: views.view.stock_overview.yml
```

## Emergency Hotfix

**Pro kritické chyby na produkci:**

### 1. Vytvoř hotfix branch (volitelně)
```bash
git checkout -b hotfix/critical-bug
```

### 2. Oprav chybu
```bash
# Uprav kód
# Test
vendor/bin/drush cr
```

### 3. Commit & Push
```bash
git add <files>
git commit -m "Fix: Kritická chyba v checkout procesu"
git push origin hotfix/critical-bug
```

### 4. Deploy na PRODUCTION (pokud urgentní)
```bash
# STAGE test
ssh stage-server "./deploy-stage.sh"

# PRODUCTION deploy
ssh prod-server "./deploy-prod.sh"
```

### 5. Merge do master
```bash
git checkout master
git merge hotfix/critical-bug
git push origin master
```

## Checklist před fix deploymentem

- [ ] Chyba identifikována a reprodukovatelná
- [ ] Oprava provedena a otestována lokálně
- [ ] Cache vyčištěna (`drush cr`)
- [ ] Error log zkontrolován (chyba zmizela)
- [ ] Git commit vytvořen s popisem
- [ ] Push do repozitáře
- [ ] STAGE deployment + test
- [ ] PRODUCTION deployment (pokud urgentní)

## Rollback při neúspěchu

**Pokud fix nefunguje:**

```bash
# 1. Rollback git
git log --oneline -5
git reset --hard HEAD~1  # vrať o 1 commit zpět

# 2. Obnov database (pokud byly DB změny a máš backup)
# Najdi poslední backup:
ls -lht backups/*.sql.gz | head -5

# Restore z backupu:
gunzip < backups/[název-backupu].sql.gz | vendor/bin/drush sql:cli

# 3. Vyčisti cache
vendor/bin/drush cr

# 4. Zkontroluj, že web funguje
vendor/bin/drush status
```

## Užitečné příkazy pro debugging

```bash
# Tail error log
tail -f /var/log/apache2/error.log

# Watchdog v reálném čase
vendor/bin/drush wd-tail

# PHP syntax check
php -l <soubor.php>

# Zkontroluj modul dependencies
vendor/bin/drush pm:list --type=module | grep -i <module_name>

# Config diff
vendor/bin/drush config:diff

# Clear specific cache
vendor/bin/drush cc <cache_type>
```
