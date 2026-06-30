Proveď detailní review všech změn před commitem:

**NOTE:** Tento příkaz je generický. Pro specifický projekt uprav:
- Theme název (aktuálně používá se: `woodyt`)
- Cesty k custom modulům/themes podle tvé struktury

## 1. Zobraz přehled změn

**Git status:**
```bash
git status
```

**Statistika změn:**
```bash
git diff --stat
```

**Počet změněných řádků:**
```bash
git diff --shortstat
```

## 2. Review změněných souborů

**Zobraz všechny změny v detailu:**
```bash
git diff
```

**Zobraz změny po jednotlivých souborech:**

Pro každý změněný soubor z `git status`, zobraz:
```bash
git diff <cesta/k/souboru>
```

**Pro lepší čitelnost použij:**
```bash
git diff --color-words <cesta/k/souboru>
```

## 3. Review config souborů

**Zobraz všechny změněné config soubory:**
```bash
git diff config/sync/
```

**Zkontroluj důležité config změny:**

Vždy zkontroluj tyto config soubory, pokud byly změněny:
- `core.extension.yml` - nové/odebrané moduly
- `*.commerce_order_type.*.yml` - změny v typech objednávek
- `views.view.*.yml` - změny v Views
- `*.field.*.yml` - změny v polích entit
- `system.site.yml` - změny v site settings

**Pro detailní review config:**
```bash
# Zkontroluj, co se změnilo v konkrétním config
git diff config/sync/core.extension.yml

# Zkontroluj všechny nové config soubory
git status | grep "new file" | grep config/sync
```

## 4. Review kódu v custom modulech

**Zkontroluj změny v PHP souborech:**
```bash
git diff web/modules/custom/
```

**Zkontroluj změny v templates:**
```bash
git diff web/modules/custom/ -- "*.twig"
```

**Zkontroluj CSS změny:**
```bash
git diff web/themes/custom/woodyt/css/
# Nebo cesta k tvému aktivnímu theme
```

## 5. Review checklist

**Před commitem zkontroluj:**

### Kód (PHP, JS, CSS)
- [ ] Žádné `var_dump()`, `console.log()`, `dd()`, `kint()` v kódu
- [ ] Žádné zakomentované bloky kódu (cleanup)
- [ ] Dodrženy coding standards (odsazení, mezery)
- [ ] Žádné TODO/FIXME komentáře bez důvodu
- [ ] Funkce mají PHPDoc komentáře
- [ ] Konstanty a proměnné mají smysluplné názvy

### Konfigurace
- [ ] Config změny odpovídají provedeným úpravám
- [ ] Žádné nečekané config změny (UUID changes apod.)
- [ ] Dependencies jsou správně nastaveny
- [ ] Workflow a states jsou správně definovány

### CSS & Templates
- [ ] Dodrženy design guidelines z `CLAUDE.md`
- [ ] Použity CSS variables (ne hardcoded barvy)
- [ ] Responsive breakpoints implementovány
- [ ] BEM-like naming conventions
- [ ] Twig templates bez logic (pouze display)

### Bezpečnost
- [ ] Žádné hesla, API keys, tokeny v kódu
- [ ] Žádné production credentials
- [ ] `.env` soubory v `.gitignore`
- [ ] Správné escapování v Twig (`{{ var|escape }}`)

### Git
- [ ] Žádné `vendor/` nebo `node_modules/` změny
- [ ] Žádné compiled files (.class, .o, .pyc)
- [ ] Žádné IDE soubory (.idea/, .vscode/)
- [ ] Žádné backup soubory (*.bak, *~)

## 6. Interactive Review

**Pro interaktivní přidávání změn po částech:**
```bash
git add -p
```

Toto ti umožní:
- Projít každou změnu jednotlivě
- Vybrat, co chceš commitnout
- Rozdělit velké změny na atomic commits

**Odpovědi:**
- `y` - přidej tuto změnu (yes)
- `n` - nepřidávej (no)
- `s` - rozděl na menší části (split)
- `q` - ukonči (quit)
- `?` - nápověda

## 7. Review staged changes

**Po přidání souborů (`git add`) zkontroluj:**
```bash
git diff --staged
```

**Zkontroluj, co přesně bude commitnuto:**
```bash
git diff --staged --stat
git diff --staged --name-only
```

## 8. Review commit message

**Před vytvořením commitu si připrav zprávu:**

**Struktura:**
```
Prefix: Stručný popis (max 72 znaků)

Detailnější popis co a proč (pokud potřeba):
- Bullet point 1
- Bullet point 2

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
```

**Prefixy podle typu změny:**
- `Feature:` - nová funkcionalita
- `Fix:` - oprava chyby
- `Config:` - změny v konfiguraci
- `Docs:` - dokumentace
- `Refactor:` - refactoring kódu
- `Style:` - CSS/design změny
- `Chore:` - build, dependencies, atd.

## 9. Final Check

**Poslední kontrola před commitem:**

```bash
# 1. Status
git status

# 2. Staged changes
git diff --staged --stat

# 3. Config status
vendor/bin/drush cst

# 4. Pending updates
vendor/bin/drush updbst

# 5. Recent commits (pro styl zprávy)
git log --oneline -5
```

**Pokud vše OK ✅ → Vytvoř commit!**

## Příklady dobrých commit messages

Z vašeho repozitáře:

```
✅ Feature: Stylování stránky s objednávkami uživatele + zobecnění pravidel pro tabulky
✅ Docs: Přidání komplexních Design & Styling Guidelines do CLAUDE.md
✅ Feature: Přidání automatického přesměrování zákazníků po přihlášení a předvyplnění údajů
✅ Config: Přidání Claude Code konfigurace a portu 3100
```

## Příklady špatných commit messages

```
❌ fixed stuff
❌ WIP
❌ asdfasdf
❌ update
❌ changes
```

## Užitečné git aliasy

Přidej do `~/.gitconfig`:

```ini
[alias]
    st = status
    df = diff
    dfs = diff --staged
    dfstat = diff --stat
    lg = log --oneline --graph --decorate
    review = diff HEAD
```

Pak můžeš psát:
```bash
git st       # místo git status
git dfs      # místo git diff --staged
git lg       # pěkný log graph
```
