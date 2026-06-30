Proveď CSS testování tématu. Téma: **$ARGUMENTS**

Pokud není téma zadáno (prázdné $ARGUMENTS), zeptej se uživatele: „Které téma chceš testovat? (např. woodyt)"

---

## Příprava

Ověř, že téma existuje:
```bash
ls web/themes/custom/$ARGUMENTS/
```

Pokud adresář neexistuje, informuj uživatele a skonči.

Zjisti dostupné CSS soubory:
```bash
find web/themes/custom/$ARGUMENTS/css -name "*.css" 2>/dev/null | head -20
```

---

## 1. Stylelint — linting CSS souborů

### 1a. Ověř přítomnost Stylelint

```bash
ls web/themes/custom/$ARGUMENTS/node_modules/.bin/stylelint 2>/dev/null || echo "chybí"
```

**Pokud chybí:** Zkontroluj, zda má téma `package.json` se skriptem `lint:css`:
```bash
cat web/themes/custom/$ARGUMENTS/package.json
```

- Pokud `lint:css` skript **existuje**, spusť `npm install` a pokračuj.
- Pokud skript **neexistuje**, přidej Stylelint do tématu:
  1. Přidej do `package.json` scripts: `"lint:css": "stylelint 'css/**/*.css'"` a `"lint:css:fix": "stylelint 'css/**/*.css' --fix"`
  2. Přidej do devDependencies: `"stylelint": "^16.0.0"`
  3. Zkopíruj `.stylelintrc.json` z woodyt (sdílená konfigurace):
     ```bash
     cp web/themes/custom/woodyt/.stylelintrc.json web/themes/custom/$ARGUMENTS/.stylelintrc.json
     ```
  4. Nainstaluj:
     ```bash
     cd web/themes/custom/$ARGUMENTS && npm install
     ```

### 1b. Spusť linting

```bash
cd web/themes/custom/$ARGUMENTS && npm run lint:css 2>&1
```

**Vyhodnoť výsledek:**
- **0 chyb** → pokračuj
- **Chyby nalezeny** → zobraz přehled. Zeptej se uživatele, zda auto-opravit (`npm run lint:css:fix`).

---

## 2. Jest — PostCSS build pipeline

### Podmínka: spusť POUZE pokud téma používá PostCSS

Zjisti, zda téma má PostCSS build systém:
```bash
ls web/themes/custom/$ARGUMENTS/postcss.config.js 2>/dev/null || \
ls web/themes/custom/$ARGUMENTS/src/css/ 2>/dev/null || \
echo "žádný PostCSS"
```

**Pokud téma NEMÁ PostCSS** (žádný `postcss.config.js` ani `src/css/`):
→ Přeskoč tento krok. Zaznamenej do souhrnu: `PostCSS Jest — ⬜ N/A (téma nepoužívá PostCSS build)`.

**Pokud téma MÁ PostCSS**, ověř existenci CSS testů:
```bash
ls web/themes/custom/$ARGUMENTS/tests/css/ 2>/dev/null || echo "žádné CSS testy"
```

- Pokud testy **neexistují**: informuj uživatele, že by je bylo vhodné vytvořit.
- Pokud testy **existují**, spusť:
  ```bash
  cd web/themes/custom/$ARGUMENTS && npm test -- --testPathPattern="tests/css" --no-coverage 2>&1
  ```

---

## 3. PHP Unit testy — preprocessing hooky

### Podmínka: spusť POUZE pokud téma má `.theme` soubor s vlastními hooky

Zkontroluj obsah `.theme` souboru:
```bash
grep -c "function " web/themes/custom/$ARGUMENTS/$ARGUMENTS.theme 2>/dev/null || echo "0"
```

**Pokud téma NEMÁ vlastní PHP funkce** (výsledek je 0 nebo soubor neexistuje):
→ Přeskoč. Zaznamenej: `PHP Unit — ⬜ N/A (téma nemá vlastní preprocessing hooky)`.

**Pokud má hooky**, zkontroluj existenci testů:
```bash
find web/themes/custom/$ARGUMENTS/tests/src/Unit -name "*.php" 2>/dev/null || echo "žádné"
```

- Pokud testy **neexistují**: navrhni jejich vytvoření.
- Pokud **existují**, spusť:
  ```bash
  php vendor/bin/phpunit \
    --bootstrap web/core/tests/bootstrap.php \
    web/themes/custom/$ARGUMENTS/tests/src/Unit/ \
    --no-coverage 2>&1
  ```

---

## 4. BrowserTestBase — funkcionální testy renderování

### Podmínka: vždy relevantní, ale testy musí být napsány per-téma

> BrowserTestBase testuje DOM strukturu specifickou pro dané téma —
> přítomnost konkrétních elementů, CSS tříd, regionů a HTML atributů.
> Testy se **nesdílejí** mezi tématy — každé téma musí mít vlastní.

Zkontroluj existenci funkcionálních testů:
```bash
find web/themes/custom/$ARGUMENTS/tests/src/Functional -name "*.php" 2>/dev/null || echo "žádné"
```

**Pokud testy NEEXISTUJÍ:**
Informuj uživatele: „Téma nemá BrowserTestBase testy. Chceš, abych vytvořil `ThemeRenderTest.php` pro téma $ARGUMENTS?"

Pokud ano, vytvoř test podle následující šablony (uprav selektory podle skutečné struktury tématu):
- Namespace: `Drupal\Tests\$ARGUMENTS\Functional`
- Soubor: `web/themes/custom/$ARGUMENTS/tests/src/Functional/ThemeRenderTest.php`
- `$defaultTheme = '$ARGUMENTS'`
- Testy ověřující: HTTP 200, aktivní téma v body class, viewport meta, skip-to-content, hlavní regiony (header, main, footer), CSS/JS linky

**Pokud testy EXISTUJÍ**, spusť:
```bash
SIMPLETEST_DB="mysql://root:root@database/drupal" \
SIMPLETEST_BASE_URL="http://localhost" \
php vendor/bin/phpunit \
  --bootstrap web/core/tests/bootstrap.php \
  web/themes/custom/$ARGUMENTS/tests/src/Functional/ \
  --no-coverage 2>&1
```

> BrowserTestBase vytvoří dočasnou DB a nainstaluje Drupal s tématem.
> Testy trvají déle (30–120 s).

---

## 5. Souhrn výsledků

Na konci zobraz tabulku. Použij ⬜ pro přeskočené kroky (N/A):

| Test | Výsledek | Poznámka |
|------|----------|----------|
| Stylelint | ✅/❌ | X chyb v Y souborech |
| PostCSS Jest | ✅/❌/⬜ | X/Y testů nebo N/A |
| PHP Unit | ✅/❌/⬜ | X/Y testů nebo N/A |
| BrowserTestBase | ✅/❌/⬜ | X/Y testů nebo chybí |

Pokud něco selhalo nebo chybí, navrhni konkrétní kroky k nápravě.

---

## Použití

```
/jw-test-css woodyt
/jw-test-css allent
/jw-test-css patek
```
