Zkontroluj kompletní stav projektu a připravenost na commit/deployment:

## 1. Git Status

**Zkontroluj stav repozitáře:**
```bash
git status
git log --oneline -5
git diff --stat
```

**Co hledat:**
- Uncommitted changes (červené soubory)
- Staged changes (zelené soubory)
- Untracked files (nové soubory)
- Commits ahead of origin (kolik commitů čeká na push)

## 2. Drupal Configuration Status

**Zkontroluj config změny:**
```bash
vendor/bin/drush config:status
# Alias: vendor/bin/drush cst
```

**Možné výsledky:**
- `Only in DB` - Config v databázi, není v souborech → **Potřeba export (`drush cex`)**
- `Only in sync` - Config v souborech, není v DB → **Potřeba import (`drush cim`)**
- `Different` - Config se liší → **Zkontroluj rozdíly**
- `Identical` - Vše v pořádku ✅

## 3. Database Updates Status

**Zkontroluj pending database updates:**
```bash
vendor/bin/drush updatedb:status
# Alias: vendor/bin/drush updbst
```

**Pokud jsou pending updates:**
```bash
vendor/bin/drush updb -y
vendor/bin/drush cr
vendor/bin/drush cex -y
```

## 4. Module Status

**Zkontroluj aktivní moduly:**
```bash
vendor/bin/drush pm:list --type=module --status=enabled
```

**Zkontroluj, zda nejsou dev moduly na produkci:**
```bash
vendor/bin/drush pm:list --status=enabled | grep -E "(devel|webprofiler|kint)"
```

**Pokud jsou dev moduly na produkci → PROBLÉM! ⚠️**

## 5. Cache Status

**Vyčisti cache (vždy před exportem config):**
```bash
vendor/bin/drush cr
```

## 6. Composer Status

**Zkontroluj, zda jsou všechny dependencies aktuální:**
```bash
composer validate
composer outdated --direct
```

**Zkontroluj security issues:**
```bash
composer audit
```

## 7. Error Log

**Zkontroluj poslední chyby v Drupal logu:**
```bash
vendor/bin/drush watchdog:show --severity=Error --count=10
# Alias: vendor/bin/drush wd-show
```

**Nebo v prohlížeči:**
- `/admin/reports/dblog`

## 8. System Status

**Zkontroluj celkový stav systému:**
```bash
vendor/bin/drush status
```

**Důležité parametry:**
- Drupal version
- Database status
- PHP version
- Files directory permissions
- Settings file

## 9. Připravenost na Commit

**✅ Ready pro commit pokud:**
- [ ] `git status` - jasné co se commituje
- [ ] `drush cst` - config synchronized (Identical)
- [ ] `drush updbst` - no pending updates
- [ ] `drush wd-show` - žádné kritické chyby
- [ ] Cache vyčištěna (`drush cr`)
- [ ] Config exportována (`drush cex`)

**Pokud ANO → Zavolej `/jw-commit`**

## 10. Připravenost na Deployment

**✅ Ready pro deployment pokud:**
- [ ] Všechny změny commitnuty
- [ ] Push do remote repozitáře dokončen
- [ ] CI/CD testy prošly (GitHub Actions)
- [ ] Změny otestovány lokálně
- [ ] Config změny jsou správné

**Pokud ANO → Zavolej `/jw-deploy`**

## Quick Commands

```bash
# Kompletní kontrola před commitem
vendor/bin/drush cr && vendor/bin/drush cex -y && git status

# Kompletní kontrola konfigurace
vendor/bin/drush cst && vendor/bin/drush updbst

# Zkontroluj všechny logy
vendor/bin/drush wd-show --severity=Error --count=20

# Zkontroluj git changes s detaily
git status && git diff --stat && git log --oneline -5
```

## Časté problémy a řešení

**"Only in DB" config:**
→ `vendor/bin/drush cex -y`

**"Only in sync" config:**
→ `vendor/bin/drush cim -y` (POZOR: importuje config z souborů!)

**Pending database updates:**
→ `vendor/bin/drush updb -y`

**Uncommitted changes:**
→ Zkontroluj `git diff`, pak `/jw-commit`

**Cache problémy:**
→ `vendor/bin/drush cr`

**Permission issues:**
→ `chmod -R 755 web/sites/default/files`
