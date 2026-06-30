Testing a quality assurance příkazy:

**NOTE:** Tento příkaz je generický. Pro specifický projekt uprav:
- Theme název (aktuálně používá se: `woodyt`)
- Cesty k custom modulům/themes podle tvé struktury
- Commerce sekce (pokud nepoužíváš Commerce)

## 1. Spusť testy

**PHP Unit testy (pokud existují):**
```bash
vendor/bin/phpunit web/modules/custom/
```

**Syntax check všech custom modulů:**
```bash
find web/modules/custom -name "*.php" -exec php -l {} \;
```

**Syntax check theme:**
```bash
find web/themes/custom/woodyt -name "*.php" -exec php -l {} \;
# Uprav cestu podle tvého aktivního theme
```

## 2. Code Quality

**PHPStan analýza (pokud je nainstalován):**
```bash
vendor/bin/phpstan analyse web/modules/custom/
```

**YAML validace:**
```bash
find web/modules/custom -name "*.yml" -exec php -r "yaml_parse_file('{}') or exit(1);" \;
```

**Twig syntax check:**
```bash
vendor/bin/drush twig:debug
```

## 3. Testování funkcionalit

**Test order workflow:**
1. Vytvoř test objednávku v košíku
2. Projdi checkout procesem
3. Zkontroluj stav objednávky v admin: `/admin/commerce/orders`
4. Zkontroluj email notifikace

**Test user registration:**
1. Vytvoř nový účet: `/user/register`
2. Zkontroluj email (pokud je nastaveno)
3. Ověř role v admin: `/admin/people`

**Test stock management:**
1. Uprav stock level produktu
2. Vytvoř objednávku
3. Zkontroluj, že stock level klesl

## 4. Performance Testing

**Cache performance:**
```bash
# Vyčisti cache
vendor/bin/drush cr

# Načti homepage (měřit čas)
time curl http://localhost/

# Načti znovu (mělo by být rychlejší - cache)
time curl http://localhost/
```

**Database query performance:**
```bash
# Zapni query logging
vendor/bin/drush sql:cli
SET GLOBAL general_log = 'ON';
SET GLOBAL log_output = 'TABLE';

# Proveď operaci na webu

# Zobraz slow queries
SELECT * FROM mysql.slow_log ORDER BY query_time DESC LIMIT 10;
```

## 5. Security Testing

**Check permissions:**
```bash
# Files directory
ls -la web/sites/default/files/

# Settings.php (should be 444)
ls -la web/sites/default/settings.php

# Config sync (should not be web accessible)
ls -la web/config/sync/
```

**Check for known vulnerabilities:**
```bash
composer audit
```

**Check for dev modules on production:**
```bash
vendor/bin/drush pm:list --status=enabled | grep -E "(devel|webprofiler|kint)"
```

## 6. Accessibility Testing

**Zkontroluj HTML validitu:**
- Použij: https://validator.w3.org/
- Nebo browser extension

**Zkontroluj kontrast barev:**
- Použij: https://webaim.org/resources/contrastchecker/
- Nebo browser DevTools

**Keyboard navigation:**
- Zkus navigovat celým webem pouze pomocí Tab/Enter/Escape

## 7. Browser Testing

**Test v různých prohlížečích:**
- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari (if available)
- [ ] Mobile browsers

**Responsive testing:**
- [ ] Mobile (320px - 767px)
- [ ] Tablet (768px - 991px)
- [ ] Desktop (992px+)

## 8. Error Testing

**Zkontroluj error handling:**

Test 404:
```bash
curl -I http://localhost/neexistujici-stranka
# Mělo by vrátit 404
```

Test 403:
```bash
curl -I http://localhost/admin
# Bez přihlášení mělo by vrátit 403/302
```

**Zkontroluj error log:**
```bash
vendor/bin/drush wd-show --severity=Error --count=20
```

## 9. Content Testing

**Test všechny content types:**
- [ ] Vytvoř nový content každého typu
- [ ] Uprav existující content
- [ ] Smaž content
- [ ] Zkontroluj zobrazení na frontendu

**Test všechny Views:**
- [ ] Otevři každý View v admin
- [ ] Zkontroluj preview
- [ ] Otestuj filtry a sortování
- [ ] Zkontroluj na frontendu

## 10. Commerce Testing

**Test checkout procesu:**
1. Přidej produkty do košíku
2. Zobraz košík: `/cart`
3. Checkout: `/checkout`
4. Vyplň údaje
5. Vyber shipping method
6. Vyber payment method
7. Dokončení objednávky
8. Zkontroluj stav: `/admin/commerce/orders`

**Test stock levels:**
1. Zobraz stock overview: `/admin/commerce/stock-overview`
2. Uprav stock level produktu
3. Vytvoř objednávku
4. Ověř, že stock klesl

**Test shipping methods:**
1. Zkontroluj dostupné metody: `/admin/commerce/config/shipping-methods`
2. Test různých kombinací (váha, cena, destinace)

## Quick Test Commands

```bash
# All-in-one test
vendor/bin/drush cr && \
vendor/bin/drush cst && \
vendor/bin/drush updbst && \
vendor/bin/drush wd-show --severity=Error --count=5

# Syntax check all PHP
find web/modules/custom web/themes/custom -name "*.php" -exec php -l {} \;

# Security check
composer audit && \
vendor/bin/drush pm:list --status=enabled | grep -E "(devel|webprofiler)"

# Performance test
time curl -s http://localhost/ > /dev/null
```

## Pre-deployment Test Checklist

- [ ] Cache vyčištěna
- [ ] Žádné PHP syntax errors
- [ ] Config synchronizována
- [ ] Žádné pending database updates
- [ ] Žádné error logs
- [ ] Všechny hlavní funkcionality otestovány
- [ ] Responsive design funguje
- [ ] Checkout proces funguje
- [ ] Stock management funguje
- [ ] Email notifikace fungují (pokud nastaveno)
- [ ] Security audit prošel (composer audit)
- [ ] Žádné dev moduly na production

Pokud všechny testy projdou ✅ → Ready pro deployment!
