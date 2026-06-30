# Claude Code Custom Commands

Vlastní příkazy (slash commands) pro Claude Code, optimalizované pro projekt **iOrdinace** (custom PHP MVC framework).

## Dostupné příkazy

### `/jw-commit` — Commit Workflow
Vytvoří commit s souvisejícími změnami podle best practices.

- Zkontroluje git status a diff
- Přidá pouze související soubory (atomic commits)
- Vytvoří commit s popisnou zprávou

**Použití:** Po dokončení feature/úpravy, před push.

---

### `/jw-status` — Project Status Check
Zkontroluje kompletní stav projektu.

- Git status (změny, commits)
- Docker status (běžící kontejnery, logy)
- PHP error log
- DB připojení

**Použití:** Před commitem nebo deploymentem.

---

### `/jw-review` — Code Review
Detailní review všech změn před commitem.

- Přehled změn (`git diff`)
- Checklist kvality a bezpečnosti
- Interactive staging (`git add -p`)
- Commit message guidelines

**Použití:** Před každým commitem (best practice).

---

### `/jw-fix` — Quick Fix/Hotfix Workflow
Rychlý workflow pro opravu chyb.

- Identifikace problému (error log, PHP syntax)
- Fix a otestování
- Commit s správným formátem
- Emergency hotfix postup

**Použití:** Při opravě bugů a urgentních hotfixů.

---

### `/jw-deploy` — Deployment Workflow
Připraví změny pro deployment.

- Commit + push workflow
- Instrukce pro produkční deployment
- Checklist a rollback postup

**Použití:** Před deploymentem na produkci.

---

### `/jw-db` — Database Operations
Správa databáze a zálohování.

- Backup/restore (`mysqldump`)
- Database info a SQL dotazy
- Troubleshooting
- Credentials: host=`database`, user=`root`, pass=`root`, db=`iordinace`
- Adminer: http://localhost:8080

**Použití:** Před velkými změnami (backup!), pro správu DB.

---

### `/jw-test` — Testing & QA
Testing a quality assurance.

- PHP syntax check
- HTTP testy (curl)
- Error log analýza
- Security check
- Pre-deployment checklist

**Použití:** Před každým deploymentem.

---

### `/jw-seo` — SEO Audit
Komplexní SEO audit webu.

- HTML analýza (meta tagy, nadpisy, alt texty)
- Technické SEO (robots.txt, sitemap)
- Lighthouse audit (výkon + SEO)

**Použití:** Pro pravidelné SEO kontroly.

---

### `/jw-chrome-test` — Lighthouse Audit
Lighthouse audit výkonu a SEO všech stránek webu.

- Spustí audity paralelně pro všechny stránky z navigace
- Core Web Vitals, Performance, SEO skóre
- Konkrétní doporučení pro opravu

**Použití:** `/jw-chrome-test` nebo `/jw-chrome-test http://localhost/kontakt`

---

## Typický workflow

### Vývoj nové feature

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
/jw-deploy     # Deployment na produkci
```

### Oprava chyby

```
/jw-fix        # Fix workflow
↓
/jw-test       # Otestovat opravu
↓
/jw-commit     # Commit fix
↓
/jw-deploy     # Urgentní deployment
```

## Git Commit Message Prefixy

- `Feature:` — nová funkcionalita
- `Fix:` — oprava chyby
- `Config:` — změny v konfiguraci
- `Docs:` — dokumentace
- `Refactor:` — refactoring kódu
- `Style:` — CSS/design změny
- `Chore:` — build, dependencies

## Docker příkazy

```bash
docker compose up -d          # Start containers
docker compose down           # Stop containers
docker compose logs -f app    # Sleduj logy
docker compose exec app bash  # Shell do containeru
```

## Databáze

```bash
# Adminer GUI
http://localhost:8080

# CLI
docker compose exec database mysql -uroot -proot iordinace

# Backup
docker compose exec database mysqldump -uroot -proot iordinace | gzip > backups/backup-$(date +%Y-%m-%d).sql.gz
```

---

**Projekt:** iOrdinace — PHP MVC (bez frameworku)
**Autor:** Claude Code + JW
