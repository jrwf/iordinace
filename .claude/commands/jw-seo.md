---
description: Provede komplexní SEO audit webu
---

# SEO Audit

Proveď kompletní SEO audit webu podle checklistu.

## Co udělat:

### 1. Určit prostředí
Zjisti, jestli testuješ:
- **Localhost** (http://localhost) — základní technické SEO
- **Produkci** (https://hotelmalina.com nebo jiná doména) — kompletní audit

### 2. Localhost audit (základní)

1. **HTML analýza**
   - Načti homepage a důležité stránky přes `curl`
   - Zkontroluj meta tagy (title, description)
   - Ověř strukturu nadpisů (H1–H6)
   - Zkontroluj alt texty u obrázků
   - Canonical URLs

2. **Technické SEO**
   - robots.txt obsah: `curl http://localhost/robots.txt`
   - XML sitemap: `curl http://localhost/sitemap.xml`
   - 404 stránka
   - Breadcrumbs

3. **Obsah**
   - Délka a kvalita textu
   - Interní odkazy
   - Použití klíčových slov

### 3. Produkční audit (kompletní)

1. **Výkon (Lighthouse)**
   ```bash
   npx lighthouse https://hotelmalina.com \
     --output=json \
     --output-path=/tmp/lh-homepage.json \
     --chrome-flags="--headless --no-sandbox" \
     --only-categories=performance,seo
   ```

2. **HTTPS a bezpečnost**
   - SSL certifikát (platnost, redirect HTTP → HTTPS)
   - Security headers

3. **Indexace**
   - robots.txt a sitemap.xml z internetu
   - Mobile-friendly test

### 4. Vytvořit report

Na konci vytvoř:
- Soubor `docs/seo-report-[DATUM].md` s výsledky
- Seznam nálezů (problémy a doporučení)
- Prioritizaci (kritické, důležité, nice-to-have)

## Příkazy pro rychlý audit

```bash
# Meta tagy homepage
curl -s http://localhost/ | grep -E "<title>|<meta name"

# Kontrola H1
curl -s http://localhost/ | grep -oE "<h1[^>]*>[^<]*</h1>"

# HTTP status kódů
curl -sI http://localhost/
curl -sI http://localhost/neexistujici-stranka

# robots.txt
curl -s http://localhost/robots.txt
```

## Poznámky

- Pro produkční test potřebuješ fungující doménu
- SEO audit může trvat 5–10 minut
- Report obsahuje akční body pro zlepšení
