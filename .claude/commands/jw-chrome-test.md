---
description: Lighthouse audit výkonu a SEO všech stránek webu z navigace
---

# Lighthouse Audit — výkon a SEO

Proveď kompletní Lighthouse audit webu. Otestuj homepage a všechny stránky dostupné z navigace.

## Argumenty

`$ARGUMENTS` může obsahovat:
- URL webu (výchozí: `http://d11prod.loc`)
- Konkrétní stránku: `http://d11prod.loc/kontakt`
- Prázdné = audit celého webu z navigace

## Postup

### 1. Zjisti URL

Pokud `$ARGUMENTS` obsahuje URL, použij ji jako základ. Jinak použij `http://d11prod.loc`.

### 2. Získej stránky z navigace

```bash
curl -s http://d11prod.loc | grep -oP 'href="[^"]*"' | grep -v '#\|mailto\|javascript\|\.css\|\.js\|admin\|user\|logout' | sort -u
```

Pokud byl zadán argument s konkrétní stránkou, testuj pouze tu.

### 3. Spusť Lighthouse audity paralelně

Pro každou stránku z navigace (včetně homepage `/`):

```bash
npx lighthouse "URL_STRANKY" \
  --output=json \
  --output-path="/tmp/lh-SLUG.json" \
  --chrome-flags="--headless --no-sandbox --disable-setuid-sandbox --disable-dev-shm-usage" \
  --only-categories=performance,seo \
  --quiet &
```

Spusť všechny audity současně (`&` na pozadí) a počkej na dokončení (`wait`).

### 4. Zpracuj a zobraz výsledky

Z každého JSON reportu extrahuj:

**Skóre:**
- Performance (0–100)
- SEO (0–100)

**Core Web Vitals:**
- FCP — First Contentful Paint
- LCP — Largest Contentful Paint
- TBT — Total Blocking Time
- CLS — Cumulative Layout Shift
- TTI — Time to Interactive
- TTFB — Server Response Time (ms)

**Problémy:**
- Load opportunities (render-blocking, unused JS, server response time, HTTP/2)
- Diagnostics (cache policy, document.write, img bez width/height)
- SEO failures (chybějící meta description, title, robots)

### 5. Výstup — formát reportu

```
## Lighthouse Audit — [URL] — [DATUM]

### Souhrn
| Stránka | Perf | SEO | FCP | LCP | TBT | TTFB |
|---------|------|-----|-----|-----|-----|------|
| /       | 100  | 100 | ...  | ... | ... | ...  |
| /galerie| 58   | 92  | ...  | ... | ... | ...  |

### Detail problémů (jen stránky s Perf < 90 nebo SEO < 100)

#### /galerie (Perf: 58, SEO: 92)
- [FAIL] Popis problému: konkrétní hodnota
- [WARN] Popis problému: konkrétní hodnota
- [SEO] Chybějící meta description

### Doporučení
Seřaď problémy podle dopadu. Pro každý závažný problém (FAIL) navrhni konkrétní řešení v kontextu Drupalu 11.
```

### 6. Hodnocení výsledků

| Skóre | Hodnocení |
|-------|-----------|
| 90–100 | Výborné |
| 50–89  | Potřebuje zlepšení |
| 0–49   | Kritické |

### 7. Drupal-specifická doporučení

Pokud najdeš problémy, navrhni konkrétní Drupal řešení:

- **Vysoký TTFB** → zkontroluj Drupal cache (`drush cr`), Internal Page Cache, Dynamic Page Cache
- **Render-blocking CSS** → CSS aggregation v `admin/config/development/performance`
- **Unused JS** → JS aggregation, zkontroluj které moduly přidávají nevyužité knihovny
- **HTTP/2** → zapnout v Apache: `a2enmod http2`, přidat `Protocols h2 http/1.1` do VirtualHost
- **Chybí meta description** → nainstalovat/nakonfigurovat modul Metatag
- **Cache policy** → nastavit `max-age` hlavičky přes `.htaccess` nebo modul

## Poznámky

- Audity spouštěj paralelně pro úsporu času
- Pokud MCP Chrome DevTools nefunguje (Target closed), použij Lighthouse CLI přímo — viz `docs/chrome.md`
- Výsledky ukládej do `/tmp/lh-*.json` — zůstanou pro případné porovnání v rámci session
- Pro produkční audit změň URL na produkční doménu v argumentu
