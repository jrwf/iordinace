# Claude Code Custom Commands

Tento adresář obsahuje vlastní příkazy (slash commands) pro Claude Code, které zjednodušují práci s Drupal projektem.

## 🔧 Konfigurace pro tvůj projekt

**DŮLEŽITÉ:** Tyto příkazy jsou generické a lze je použít na jakémkoliv Drupal projektu. Před použitím zkontroluj a uprav:

1. **Theme název** - Aktuálně nastaveno: `woodyt`
   - Zjisti aktivní theme: `vendor/bin/drush config:get system.theme default`
   - Uprav v souborech: `jw-fix.md`, `jw-review.md`, `jw-test.md`

2. **Database credentials** - Aktuálně nastaveno: `database`/`root`/`root`/`drupal`
   - Zkontroluj v `web/sites/default/settings.php`
   - Uprav v souboru: `jw-db.md`

3. **Deploy workflow** - Pokud používáš pouze lokální Docker:
   - Ignoruj sekce o STAGE/PRODUCTION v `jw-deploy.md`
   - Zaměř se na lokální workflow (sekce 1)

4. **Backups directory** - Vytvoří se automaticky jako `backups/` v project root

## Dostupné příkazy

### 📝 `/jw-commit` - Commit Workflow
Vytvoří commit s souvisejícími změnami podle best practices.

**Co dělá:**
- Vyčistí Drupal cache
- Exportuje konfiguraci
- Zkontroluje git status
- Přidá pouze související soubory
- Vytvoří commit s popisnou zprávou

**Kdy použít:**
- Po dokončení feature/úpravy
- Před push do remote repozitáře
- Pro atomic commits (jedna funkcionalita = jeden commit)

**Příklad použití:**
```
/jw-commit
```

---

### 🚀 `/jw-deploy` - Deployment Workflow
Připraví změny pro deployment podle three-tier workflow (LOCAL → STAGE → PRODUCTION).

**Co dělá:**
- Připraví změny na lokálním prostředí
- Poskytne instrukce pro STAGE deployment
- Poskytne instrukce pro PRODUCTION deployment
- Obsahuje checklist a rollback postup

**Kdy použít:**
- Před deploymentem na STAGE/PRODUCTION
- Pro přehled deployment procesu
- Kontrola před push

**Příklad použití:**
```
/jw-deploy
```

---

### 🔍 `/jw-status` - Project Status Check
Zkontroluje kompletní stav projektu a připravenost na commit/deployment.

**Co dělá:**
- Git status (změny, commits, atd.)
- Drupal config status
- Database updates status
- Module status (včetně dev modulů)
- Error log check
- Composer security audit

**Kdy použít:**
- Před každým commitem
- Před deploymentem
- Pro quick check stavu projektu
- Při troubleshootingu problémů

**Příklad použití:**
```
/jw-status
```

---

### 👀 `/jw-review` - Code Review
Proveď detailní review všech změn před commitem.

**Co dělá:**
- Zobrazí přehled všech změn
- Review po jednotlivých souborech
- Zkontroluje config soubory
- Code quality checklist
- Interactive staging (`git add -p`)
- Commit message guidelines

**Kdy použít:**
- Před každým commitem (best practice!)
- Pro kontrolu kvality kódu
- Před code review s týmem
- Pro identifikaci nežádoucích změn

**Příklad použití:**
```
/jw-review
```

---

### 🔧 `/jw-fix` - Quick Fix/Hotfix Workflow
Rychlý workflow pro opravu chyb (bugfix/hotfix).

**Co dělá:**
- Pomůže identifikovat problém
- Poskytne workflow pro fix
- Testování opravy
- Commit fix s správným formátem
- Emergency hotfix postup
- Rollback instrukce

**Kdy použít:**
- Při opravě bugů
- Pro urgentní hotfixy
- Po objevení chyby v logách
- Při production issues

**Příklad použití:**
```
/jw-fix
```

---

### 💾 `/jw-db` - Database Operations
Správa databáze a zálohování.

**Co dělá:**
- Backup/restore databáze
- Synchronizace DB (STAGE → LOCAL)
- Anonymizace produkční DB
- Database cleanup a optimalizace
- SQL query examples
- Emergency recovery

**Kdy použít:**
- Před velkými změnami (backup!)
- Pro sync DB ze STAGE/PRODUCTION
- Cleanup watchdog a cache
- Database troubleshooting

**Příklad použití:**
```
/jw-db
```

---

### ✅ `/jw-test` - Testing & QA
Testing a quality assurance příkazy.

**Co dělá:**
- PHP syntax check
- Code quality analýza
- Functionality testing checklist
- Performance testing
- Security testing
- Browser/responsive testing
- Pre-deployment checklist

**Kdy použít:**
- Před každým deploymentem
- Po větších změnách
- Pro QA před release
- Security audit

**Příklad použití:**
```
/jw-test
```

---

## Typický workflow

### 1. Vývoj nové feature

```
[Napsat kód]
↓
/jw-status     # Zkontrolovat stav
↓
/jw-test       # Otestovat změny
↓
/jw-review     # Review kódu
↓
/jw-commit     # Vytvořit commit
↓
/jw-deploy     # Deployment na STAGE/PROD
```

### 2. Oprava chyby

```
[Najít chybu v logu]
↓
/jw-fix        # Fix workflow
↓
/jw-test       # Otestovat opravu
↓
/jw-commit     # Commit fix
↓
/jw-deploy     # Urgentní deployment
```

### 3. Práce s databází

```
/jw-db         # Backup před změnami
↓
[Provést změny v DB]
↓
/jw-status     # Zkontroluj config changes
↓
/jw-commit     # Export a commit config
```

## Git Commit Message Prefixy

Používej tyto prefixy pro konzistentní commit messages:

- `Feature:` - Nová funkcionalita
- `Fix:` - Oprava chyby
- `Config:` - Změny v konfiguraci
- `Docs:` - Dokumentace
- `Refactor:` - Refactoring kódu
- `Style:` - CSS/design změny
- `Chore:` - Build, dependencies, atd.

**Příklad:**
```
Feature: Přidání českých překladů pro stavy objednávek

- Vytvořeno vlastní workflow sue_order_validation
- České názvy: Košík, Ke schválení, Dokončeno, Zrušeno
- Aktualizována dokumentace v commerce-description.md

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
```

## Drush Aliasy

Často používané Drush příkazy:

```bash
drush cr          # Cache rebuild
drush cex -y      # Config export
drush cim -y      # Config import
drush cst         # Config status
drush updb -y     # Database update
drush updbst      # Database update status
drush wd-show     # Watchdog show (error log)
drush pm:list     # Module list
drush sql:cli     # SQL CLI
drush sql:dump    # Database dump
```

## Tipy a triky

### Quick commands kombinace

**Před commitem:**
```bash
vendor/bin/drush cr && vendor/bin/drush cex -y && git status
```

**Status check:**
```bash
vendor/bin/drush cst && vendor/bin/drush updbst && vendor/bin/drush wd-show --count=5
```

**Security check:**
```bash
composer audit && vendor/bin/drush pm:list --status=enabled | grep -E "(devel|webprofiler)"
```

### Git pomocné příkazy

```bash
git status                  # Stav repozitáře
git diff                    # Změny v souborech
git diff --staged           # Staged změny
git log --oneline -5        # Posledních 5 commitů
git diff --stat             # Statistika změn
```

### Docker příkazy

```bash
docker-compose up -d        # Start containers
docker-compose down         # Stop containers
docker-compose logs -f app  # Sleduj logy
docker-compose exec app bash # Shell do containeru
```

## Troubleshooting

**Config sync problémy:**
```bash
vendor/bin/drush cim --partial -y
```

**Cache problémy:**
```bash
vendor/bin/drush cr
```

**Database problémy:**
```bash
vendor/bin/drush updb -y
vendor/bin/drush cr
```

**Permission problémy:**
```bash
chmod -R 755 web/sites/default/files
```

## Další zdroje

- **CLAUDE.md** - Kompletní dokumentace projektu
- **Claude Code Docs** - https://docs.claude.com/en/docs/claude-code

---

**Vytvořeno:** 2025-11-07
**Autor:** Claude Code + JW
**Verze:** 1.0
