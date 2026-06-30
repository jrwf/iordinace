Proveď BrowserTestBase funkcionální testování tématu. Téma: **$ARGUMENTS**

Pokud není téma zadáno, zeptej se uživatele: „Které téma chceš testovat? (např. woodyt)"

BrowserTestBase testuje reálné renderování HTML stránek s aktivním tématem —
ověřuje strukturu DOM, přítomnost CSS tříd, správnost HTML atributů a HTTP odpovědí.
Na rozdíl od unit testů spouští kompletní Drupal stack.

---

## Příprava prostředí

Ověř, že téma existuje:
```bash
ls web/themes/custom/$ARGUMENTS/
```

Zkontroluj, zda existují funkcionální testy:
```bash
find web/themes/custom/$ARGUMENTS/tests/src/Functional/ -name "*.php" 2>/dev/null
```

Pokud žádné testy neexistují, nabídni jejich vytvoření:
> „Téma nemá BrowserTestBase testy. Chceš, abych je vytvořil?"
> Pokud ano, použij třídu `Drupal\Tests\{theme}\Functional\ThemeRenderTest`
> s namespace `Drupal\Tests\{theme}\Functional` a soubor ulož do
> `web/themes/custom/{theme}/tests/src/Functional/ThemeRenderTest.php`.

---

## Spuštění testů

### Základní spuštění (bez JS)

```bash
SIMPLETEST_DB="mysql://root:root@database/drupal" \
SIMPLETEST_BASE_URL="http://localhost" \
php vendor/bin/phpunit \
  --bootstrap web/core/tests/bootstrap.php \
  web/themes/custom/$ARGUMENTS/tests/src/Functional/ \
  --no-coverage \
  --testdox \
  2>&1
```

### Filtrování konkrétního testu

```bash
SIMPLETEST_DB="mysql://root:root@database/drupal" \
SIMPLETEST_BASE_URL="http://localhost" \
php vendor/bin/phpunit \
  --bootstrap web/core/tests/bootstrap.php \
  web/themes/custom/$ARGUMENTS/tests/src/Functional/ \
  --filter "testFrontPageRenders" \
  --no-coverage 2>&1
```

### S JavaScript (potřeba ChromeDriver)

> JavaScript testy (`WebDriverTestBase`) potřebují běžící ChromeDriver na portu 9515.
> Pokud ChromeDriver není dostupný, přeskoč JS testy.

```bash
SIMPLETEST_DB="mysql://root:root@database/drupal" \
SIMPLETEST_BASE_URL="http://localhost" \
MINK_DRIVER_ARGS_WEBDRIVER='["chrome", {"browserName":"chrome","goog:chromeOptions":{"args":["--disable-gpu","--headless","--no-sandbox"]}}, "http://localhost:9515"]' \
php vendor/bin/phpunit \
  --bootstrap web/core/tests/bootstrap.php \
  web/themes/custom/$ARGUMENTS/tests/src/FunctionalJavascript/ \
  --no-coverage 2>&1
```

---

## Co testy ověřují

Správně napsaný `ThemeRenderTest` pro téma by měl pokrývat:

| Test | Co ověřuje |
|------|-----------|
| `testFrontPageRenders` | HTTP 200, základní HTML struktura |
| `testThemeIsActive` | Správné téma je aktivní (`<body class="...">`) |
| `testBurgerMenuPresent` | Element `.burger` existuje v DOM |
| `testMobileMenuRegion` | `.region-mobil-menu` je v DOM |
| `testCssIsLinked` | `<link>` na theme CSS soubory |
| `testJsIsLinked` | `<script>` na theme JS soubory |
| `testArticleNodeRenders` | Article node se zobrazí s tématem |
| `testResponsiveMetaTag` | `<meta name="viewport">` je přítomný |
| `testSkipToContent` | Skip-to-content odkaz pro přístupnost |
| `testPreprocessNodeDate` | Article má `date` proměnnou z preprocessu |

---

## Diagnostika selhání

Pokud test selže:

1. **HTTP 500** → zkontroluj Drupal watchdog:
   ```bash
   vendor/bin/drush wd-show --severity=Error --count=5
   ```

2. **Element nenalezen** → zkontroluj, zda twig template existuje a je správně strukturovaný

3. **Téma se nenačítá** → ověř `web/themes/custom/$ARGUMENTS/$ARGUMENTS.info.yml`

4. **DB chyba** → ověř DB připojení:
   ```bash
   vendor/bin/drush status
   ```

---

## Použití

```
/jw-test-browser woodyt
/jw-test-browser allent
```
