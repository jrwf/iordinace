# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**iOrdinace** — website for a pediatrician's office (MUDr. Přecechtělová, Medipraktik Zlín s.r.o.). Custom PHP MVC framework, no Composer dependencies. UI and code comments are in Czech.

## Docker Environment

```bash
# Start all services (app on :80, MySQL on :3306, Adminer on :8080)
docker compose up -d

# Rebuild after Dockerfile changes
docker compose up -d --build

# Tail app logs
docker compose logs -f app

# Shell into app container
docker compose exec app bash
```

The `.env` file sets `UID`, `GID`, and `ENVIRONMENT`. The `ENVIRONMENT` value (`local`) is the key used to look up DB credentials in `config.php`.

There are no tests and no build/lint steps.

## MVC Architecture

**Entry point**: `index.php` — starts session, registers a custom autoloader (`autoloadFunkce`), creates `SmerovacKontroler`, and calls `vypisPohled()`.

**Autoloader convention**:
- Class names ending in `Kontroler` → loaded from `controller/ClassName.php`
- Everything else → loaded from `model/ClassName.php`

**Routing** (`controller/SmerovacKontroler.php`):
- URL first segment is converted from kebab-case to PascalCase + `Kontroler` suffix
- `/home` → `HomeKontroler`, `/preventivni-prohlidky` → `PreventivniProhlidkyKontroler`
- If `controller/XKontroler.php` doesn't exist, redirects to `/chyba`
- Empty or `/dist` path redirects to `/home`
- The router wraps the matched controller's view inside `view/rozlozeni.phtml` (the main layout)

**Controllers** (`controller/Kontroler.php`):
- All extend abstract `Kontroler`
- Must implement `zpracuj($parametry)`: set `$this->hlavicka` (title/keywords/description), populate `$this->data[]` (variables extracted into the view), and set `$this->pohled` (view filename without `.phtml`)
- `presmeruj($url)` redirects; `overUzivatele()` checks login session

**Views** (`view/*.phtml`):
- `rozlozeni.phtml` is the shared layout; it includes the header, nav, slideshow/foto header, contact block, and footer, then calls `$this->kontroler->vypisPohled()` to embed the page-specific view
- Variables from `$this->data` are extracted (with `htmlspecialchars` applied) into the view scope

**Models** (`model/`):
- `DatabaseConnection` reads `ENVIRONMENT` env var, looks up credentials in `config.php`, connects via `mysqli`
- `aktuality` handles news/posts CRUD — each method instantiates its own `DatabaseConnection` via `getSpojeni()` (no shared connection object)
- `Login` handles session-based auth with hardcoded credentials (username/password stored in plaintext in the file)
- `Main` provides `bodyClass()` — maps URL paths to CSS class names

## Adding a New Page

1. Create `controller/MyPageKontroler.php` extending `Kontroler`, implement `zpracuj()`
2. Create `view/my-page.phtml` for the page content
3. The page is immediately accessible at `/my-page` — no routing config needed

## Database

MySQL 8.0.32. Credentials for local dev: host `database`, db `iordinace`, user `root`, password `root`.

Queries use raw `mysqli` strings — no ORM or prepared statements. The `aktuality` table has columns: `idaktualita`, `orders`, `nadpis`, `perex`, `obsah`, `zobrazit`, `ts`, `created`.

Adminer (DB GUI) is available at `http://localhost:8080` when Docker is running.
