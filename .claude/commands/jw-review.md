Proveď detailní review všech změn před commitem:

## 1. Zobraz přehled změn

```bash
git status
git diff --stat
git diff --shortstat
```

## 2. Review změněných souborů

**Zobraz všechny změny:**
```bash
git diff
```

**Zobraz změny po jednotlivých souborech:**
```bash
git diff <cesta/k/souboru>
```

## 3. Review checklist

### Kód (PHP, JS, CSS)
- [ ] Žádné `var_dump()`, `print_r()`, `console.log()` v kódu
- [ ] Žádné zakomentované bloky kódu
- [ ] Dodrženy coding standards (odsazení, mezery)
- [ ] Smysluplné názvy proměnných a funkcí

### Bezpečnost
- [ ] Žádné hesla, API keys, tokeny v kódu
- [ ] Žádné production credentials
- [ ] `.env` soubory v `.gitignore`
- [ ] SQL dotazy nepoužívají nezabezpečené string concatenation s uživatelským vstupem
- [ ] Uživatelský vstup je escapován (`htmlspecialchars`)

### Git
- [ ] Žádné `vendor/` nebo `node_modules/` změny
- [ ] Žádné IDE soubory (.idea/, .vscode/)
- [ ] Žádné backup soubory (*.bak, *~)
- [ ] `.npm-cache/`, `.composer-cache/` jsou v `.gitignore`

## 4. Interactive Review

**Pro interaktivní přidávání změn po částech:**
```bash
git add -p
```

Odpovědi:
- `y` — přidej tuto změnu
- `n` — nepřidávej
- `s` — rozděl na menší části
- `q` — ukonči

## 5. Review staged changes

```bash
git diff --staged
git diff --staged --stat
git diff --staged --name-only
```

## 6. Commit message

**Struktura:**
```
Prefix: Stručný popis (max 72 znaků)

Detailnější popis co a proč (pokud potřeba):
- Bullet point 1
- Bullet point 2

Co-Authored-By: Claude Sonnet 4.6 <noreply@anthropic.com>
```

**Prefixy:**
- `Feature:` — nová funkcionalita
- `Fix:` — oprava chyby
- `Config:` — změny v konfiguraci/nastavení
- `Docs:` — dokumentace
- `Refactor:` — refactoring kódu
- `Style:` — CSS/design změny
- `Chore:` — build, dependencies

## 7. Final Check

```bash
git status
git diff --staged --stat
git log --oneline -5
```

**Pokud vše OK → Vytvoř commit!**

## Příklady dobrých commit messages

```
✅ Feature: Přidání formuláře pro kontakt
✅ Fix: Oprava zobrazení aktualit bez obrázku
✅ Config: Docker prostředí s vlastním Dockerfile
✅ Style: Responzivní úprava navigace pro mobil
```

## Špatné commit messages

```
❌ fixed stuff
❌ WIP
❌ update
❌ changes
```

## Git aliasy (volitelné)

```ini
[alias]
    st = status
    df = diff
    dfs = diff --staged
    lg = log --oneline --graph --decorate
```
