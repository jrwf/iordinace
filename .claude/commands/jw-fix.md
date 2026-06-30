Rychlý workflow pro opravu chyby (bugfix/hotfix):

## Quick Fix Workflow

### 1. Identifikuj problém

**Zkontroluj PHP error log:**
```bash
docker compose exec app tail -n 30 /var/log/apache2/error.log
```

**Zkontroluj PHP syntaxi konkrétního souboru:**
```bash
php -l <soubor.php>
```

**Zkontroluj všechny PHP soubory v projektu:**
```bash
find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \; 2>&1 | grep -v "No syntax errors"
```

### 2. Vytvoř opravu

**Uprav kód v:**
- `controller/` — kontrolery
- `model/` — modely
- `view/` — šablony (.phtml)
- `index.php`, `config.php` — entry point, konfigurace

### 3. Otestuj opravu

**Zkontroluj syntaxi opraveného souboru:**
```bash
php -l <soubor.php>
```

**Otestuj v prohlížeči:**
```bash
curl -sI http://localhost/
```

**Zkontroluj error log znovu:**
```bash
docker compose exec app tail -n 10 /var/log/apache2/error.log
```

### 4. Commit fix

**Zkontroluj změny:**
```bash
git status
git diff
```

**Přidej pouze soubory související s fixem:**
```bash
git add <soubory>
```

**Vytvoř commit:**
```bash
git commit -m "$(cat <<'EOF'
Fix: <Stručný popis problému>

- Co bylo špatně
- Jak bylo opraveno
- Kde se chyba projevovala

Co-Authored-By: Claude Sonnet 4.6 <noreply@anthropic.com>
EOF
)"
```

### 5. Deploy fix

```bash
git push origin master
# SSH na server a git pull
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

### SQL Fix

**Problém:** Chybějící escapování
```php
// ❌ Špatně
$sql = "SELECT * FROM aktuality WHERE id = " . $_GET['id'];

// ✅ Opraveno
$id = (int) $_GET['id'];
$sql = "SELECT * FROM aktuality WHERE id = " . $id;
```

### CSS Fix

**Problém:** Rozbitý layout na mobilu
```css
/* ❌ Špatně */
.container { width: 1200px; }

/* ✅ Opraveno */
.container { max-width: 1200px; width: 100%; }
```

## Emergency Hotfix

```bash
# 1. Vytvoř hotfix branch (volitelně)
git checkout -b hotfix/critical-bug

# 2. Oprav chybu + otestuj

# 3. Commit & Push
git add <files>
git commit -m "Fix: Kritická chyba"
git push origin hotfix/critical-bug

# 4. Merge do master
git checkout master
git merge hotfix/critical-bug
git push origin master
```

## Rollback při neúspěchu

```bash
git log --oneline -5
git reset --hard HEAD~1
```

## Užitečné příkazy pro debugging

```bash
# PHP syntax check
php -l <soubor.php>

# Tail error log
docker compose exec app tail -f /var/log/apache2/error.log

# Test HTTP odpovědi
curl -sI http://localhost/

# Test konkrétní stránky
curl -s http://localhost/home | head -20
```
