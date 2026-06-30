Proveď testování Drupal tématu (PHP hooky, JavaScript). Téma: **$ARGUMENTS**

Pokud není téma zadáno (prázdné $ARGUMENTS), zeptej se uživatele: „Které téma chceš testovat? (např. woodyt)"

> Tento příkaz pokrývá PHP a JS vrstvu tématu.
> CSS testování řeší samostatně `/jw-test-css`.
> BrowserTestBase testování řeší samostatně `/jw-test-browser`.

---

## Příprava

Ověř, že téma existuje:
```bash
ls web/themes/custom/$ARGUMENTS/
```

Pokud adresář neexistuje, informuj uživatele a skonči.

Zjisti základní strukturu tématu:
```bash
find web/themes/custom/$ARGUMENTS -maxdepth 2 -name "*.php" -o -name "*.theme" -o -name "*.js" | grep -v node_modules | sort
```

---

## 1. PHP syntax check — soubor .theme

### Podmínka: spusť POUZE pokud téma má `.theme` soubor

```bash
ls web/themes/custom/$ARGUMENTS/$ARGUMENTS.theme 2>/dev/null || echo "žádný .theme soubor"
```

**Pokud `.theme` soubor NEEXISTUJE:**
→ Přeskoč kroky 1–3. Zaznamenej: `PHP — ⬜ N/A (téma nemá .theme soubor)`.

**Pokud existuje**, zkontroluj syntaxi:
```bash
php -l web/themes/custom/$ARGUMENTS/$ARGUMENTS.theme
```

Pokud syntax check selže, zastav a informuj uživatele o chybě před pokračováním.

---

## 2. PHP Unit testy — preprocessing hooky

### Podmínka: spusť POUZE pokud `.theme` obsahuje vlastní funkce

Zjisti, kolik funkcí téma definuje:
```bash
grep -c "^function " web/themes/custom/$ARGUMENTS/$ARGUMENTS.theme 2>/dev/null || echo "0"
```

Zobraz přehled funkcí:
```bash
grep "^function " web/themes/custom/$ARGUMENTS/$ARGUMENTS.theme 2>/dev/null
```

**Pokud téma NEMÁ vlastní funkce (0):**
→ Přeskoč. Zaznamenej: `PHP Unit — ⬜ N/A (žádné vlastní hooky)`.

**Pokud funkce existují**, zkontroluj přítomnost unit testů:
```bash
find web/themes/custom/$ARGUMENTS/tests/src/Unit -name "*.php" 2>/dev/null || echo "žádné"
```

- **Testy neexistují** → Informuj uživatele:
  „Téma má tyto hooky bez testů: [seznam funkcí]. Chceš, abych vytvořil unit testy?"

  Pokud ano, vytvoř `tests/src/Unit/PreprocessTest.php`:
  - Namespace: `Drupal\Tests\$ARGUMENTS\Unit`
  - `require_once` souboru `.theme` v `setUp()`
  - Test pro každou funkci která manipuluje pole (nevyžaduje Drupal služby)
  - Funkce volající `\Drupal::service()` testuj jako Kernel test

- **Testy existují** → Spusť:
  ```bash
  php vendor/bin/phpunit \
    --bootstrap web/core/tests/bootstrap.php \
    web/themes/custom/$ARGUMENTS/tests/src/Unit/ \
    --no-coverage --testdox 2>&1
  ```

---

## 3. Kernel testy — hooky vyžadující Drupal služby

### Podmínka: spusť POUZE pokud existují Kernel testy

```bash
find web/themes/custom/$ARGUMENTS/tests/src/Kernel -name "*.php" 2>/dev/null || echo "žádné"
```

**Pokud Kernel testy neexistují:**
→ Přeskoč. Zaznamenej: `Kernel — ⬜ N/A`.

**Pokud existují**, spusť:
```bash
SIMPLETEST_DB="mysql://root:root@database/drupal" \
php vendor/bin/phpunit \
  --bootstrap web/core/tests/bootstrap.php \
  web/themes/custom/$ARGUMENTS/tests/src/Kernel/ \
  --no-coverage --testdox 2>&1
```

---

## 4. Jest — JavaScript chování

### Podmínka: spusť POUZE pokud téma má vlastní JavaScript soubory

Zkontroluj přítomnost JS souborů:
```bash
find web/themes/custom/$ARGUMENTS/js -name "*.js" 2>/dev/null | grep -v node_modules || echo "žádné JS soubory"
```

**Pokud téma NEMÁ vlastní JavaScript:**
→ Přeskoč. Zaznamenej: `Jest — ⬜ N/A (téma nemá JavaScript)`.

**Pokud JS existuje**, zobraz nalezené soubory a zkontroluj Jest testy:
```bash
find web/themes/custom/$ARGUMENTS/tests/js -name "*.test.js" 2>/dev/null || echo "žádné Jest testy"
```

- **Jest testy neexistují** → Informuj uživatele:
  „Téma má tyto JS soubory bez testů: [seznam]. Chceš, abych vytvořil Jest testy?"

  Pokud ano:
  1. Přidej do `package.json` tématu: Jest + jest-environment-jsdom + testovací script
  2. Vytvoř `tests/js/<název>.test.js` pro každý JS soubor
  3. Mockuj `Drupal` a `once` globály, testuj konkrétní chování (klikací události, toggle tříd, DOM manipulace)

- **Jest testy existují** → Ověř, že Jest je nainstalovaný:
  ```bash
  ls web/themes/custom/$ARGUMENTS/node_modules/.bin/jest 2>/dev/null || echo "chybí"
  ```

  Pokud chybí: `cd web/themes/custom/$ARGUMENTS && npm install`

  Spusť:
  ```bash
  cd web/themes/custom/$ARGUMENTS && npm test -- --testPathPattern="tests/js" --no-coverage 2>&1
  ```

---

## 5. Souhrn výsledků

Na konci zobraz tabulku. Použij ⬜ pro N/A, ⚠️ pro "testy chybí ale měly by existovat":

| Test | Výsledek | Poznámka |
|------|----------|----------|
| PHP syntax | ✅/❌/⬜ | OK nebo chyba na řádku X |
| PHP Unit | ✅/❌/⬜/⚠️ | X/Y testů nebo N/A nebo chybí |
| Kernel | ✅/❌/⬜ | X/Y testů nebo N/A |
| Jest JS | ✅/❌/⬜/⚠️ | X/Y testů nebo N/A nebo chybí |

Pokud je u nějakého kroku ⚠️, navrhni konkrétní kroky k vytvoření chybějících testů.

---

## Diagnostika selhání

- **PHP syntax error** → oprav chybu v `.theme` souboru na uvedeném řádku
- **Unit test selže na `\Drupal::`** → přesuň test do Kernel testu
- **Jest — `Drupal is not defined`** → přidej mock `global.Drupal = { behaviors: {} }` před `require()`

---

## Použití

```
/jw-test-thema woodyt
/jw-test-thema allent
/jw-test-thema patek
```
