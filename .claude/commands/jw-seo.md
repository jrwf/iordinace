---
description: Provede komplexní SEO audit webu hotelmalina.com
---

# SEO Audit - Hotel Malina

Proveď kompletní SEO audit webu podle checklistu v `docs/seo.md`.

## Co udělat:

### 1. Určit prostředí
Zjisti, jestli testuješ:
- **Localhost** (http://hotelmalina.loc) - základní SEO audit
- **Produkci** (https://hotelmalina.com) - kompletní audit včetně výkonu

### 2. Localhost audit (základní)

Zkontroluj:
1. **Drupal konfigurace**
   - Nainstalované SEO moduly (Metatag, Pathauto, Simple Sitemap)
   - Konfigurace meta tagů
   - URL aliasy
   - XML sitemap nastavení

2. **HTML analýza**
   - Načti homepage a důležité stránky
   - Zkontroluj meta tagy (title, description)
   - Ověř strukturu nadpisů (H1-H6)
   - Zkontroluj alt texty u obrázků
   - Canonical URLs

3. **Technické SEO**
   - robots.txt obsah
   - XML sitemap dostupnost
   - 404 stránka
   - Breadcrumbs

4. **Obsah**
   - Zkontroluj délku a kvalitu textu
   - Interní odkazy
   - Keywords použití

### 3. Produkční audit (kompletní)

Pokud testuju produkci, přidej:

1. **Výkon**
   - Google PageSpeed Insights analýza
   - Core Web Vitals
   - Načítací časy

2. **HTTPS a bezpečnost**
   - SSL certifikát
   - Security headers
   - Mixed content

3. **Google nástroje**
   - Search Console status (pokud máte přístup)
   - Mobile-friendly test
   - Rich results test

4. **Indexace**
   - Zkontroluj indexaci v Google (site: operátor)
   - robots.txt a sitemap.xml z internetu

### 4. Vytvořit report

Na konci vytvoř:
- Soubor `docs/seo-report-[DATUM].md` s výsledky
- Seznam nálezů (problémy a doporučení)
- Prioritizaci úkolů (kritické, důležité, nice-to-have)

## Použití:

Spusť tento command a upřesni:
1. Jakou URL mám testovat (localhost nebo produkce)
2. Jestli chci základní nebo podrobný report
3. Jestli se mám zaměřit na konkrétní oblasti

## Poznámky:

- Pro produkční test potřebuji fungující doménu
- Některé testy vyžadují externí nástroje (PageSpeed Insights)
- SEO audit může trvat 5-10 minut
- Report obsahuje akční body pro zlepšení
