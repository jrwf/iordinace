---
description: Lighthouse audit výkonu a SEO všech stránek webu z navigace
---

# Lighthouse Audit — výkon a SEO

Proveď kompletní Lighthouse audit webu. Otestuj homepage a všechny stránky dostupné z navigace.

## Argumenty

`$ARGUMENTS` může obsahovat:
- URL webu (výchozí: `http://localhost`)
- Konkrétní stránku: `http://localhost/kontakt`
- Prázdné = audit celého webu z navigace

## Postup

### 1. Zjisti URL

Pokud `$ARGUMENTS` obsahuje URL, použij ji jako základ. Jinak použij `http://localhost`.

### 2. Získej stránky z navigace

```bash
curl -s http://localhost/ | grep -oP 'href="[^"]*"' | grep -v '#\|mailto\|javascript\|\.css\|\.js' | sort -u
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
- TTFB — Server Response Time (ms)

**Problémy:**
- Load opportunities (render-blocking, unused JS, server response time)
- SEO failures (chybějící meta description, title, robots)

### 5. Výstup — formát reportu

```
## Lighthouse Audit — [URL] — [DATUM]

### Souhrn
| Stránka   | Perf | SEO | FCP | LCP | TBT | TTFB |
|-----------|------|-----|-----|-----|-----|------|
| /         | 100  | 100 | ... | ... | ... | ...  |
| /kontakt  | 85   | 95  | ... | ... | ... | ...  |

### Detail problémů (stránky s Perf < 90 nebo SEO < 100)

#### /kontakt (Perf: 85, SEO: 95)
- [FAIL] Popis problému: konkrétní hodnota
- [SEO] Chybějící meta description

### Doporučení
Seřaď problémy podle dopadu.
```

### 6. Hodnocení výsledků

| Skóre  | Hodnocení          |
|--------|--------------------|
| 90–100 | Výborné            |
| 50–89  | Potřebuje zlepšení |
| 0–49   | Kritické           |

### 7. Doporučení pro opravu

- **Vysoký TTFB** → zkontroluj PHP kód, DB dotazy, zapni output buffering
- **Render-blocking CSS** → přesuň CSS na konec, nebo použij `async`/`defer` pro JS
- **Unused JS** → odstraň nepoužívané skripty
- **HTTP/2** → zapnout v Apache: `a2enmod http2`
- **Chybí meta description** → přidej do kontrolerů `$this->hlavicka['description']`
- **Cache policy** → nastav `Cache-Control` hlavičky v `.htaccess`

## Poznámky

- Audity spouštěj paralelně pro úsporu času
- Výsledky ukládej do `/tmp/lh-*.json`
- Pro produkční audit změň URL na produkční doménu v argumentu
