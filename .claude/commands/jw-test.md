Testing a quality assurance příkazy:

## 1. PHP Syntax Check

**Syntax check všech PHP souborů v projektu:**
```bash
find . -name "*.php" -not -path "./vendor/*" -not -path "./.npm-cache/*" -exec php -l {} \; 2>&1 | grep -v "No syntax errors"
```

**Syntax check konkrétního souboru:**
```bash
php -l controller/HomeKontroler.php
```

## 2. Error Log

**Zkontroluj chyby aplikace:**
```bash
docker compose exec app tail -n 50 /var/log/apache2/error.log
```

**Sleduj logy v reálném čase:**
```bash
docker compose logs -f app
```

## 3. HTTP Testy

**Test homepage:**
```bash
curl -sI http://localhost/
```

**Test konkrétních stránek:**
```bash
curl -sI http://localhost/home
curl -sI http://localhost/aktuality
curl -sI http://localhost/kontakt
```

**Test 404:**
```bash
curl -sI http://localhost/neexistujici-stranka
# Mělo by vrátit redirect na /chyba
```

## 4. Security Testing

**Zkontroluj permissions souborů:**
```bash
ls -la config.php
# Neměl by být world-readable
```

**Zkontroluj .gitignore:**
```bash
cat .gitignore | grep -E "(\.env|config\.local|credentials)"
```

## 5. Performance Testing

**Základní load time:**
```bash
time curl -s http://localhost/ > /dev/null
time curl -s http://localhost/ > /dev/null  # druhý request (cache)
```

**Database query:**
```bash
docker compose exec database mysql -uroot -proot -e "SHOW STATUS LIKE 'Slow_queries'" iordinace
```

## 6. Accessibility & Browser Testing

**Test v různých prohlížečích:**
- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Mobile (DevTools responsive mode)

**Responsive breakpoints:**
- [ ] Mobile (320px–767px)
- [ ] Tablet (768px–991px)
- [ ] Desktop (992px+)

## 7. Database Test

**Test připojení:**
```bash
docker compose exec database mysql -uroot -proot -e "SELECT COUNT(*) FROM aktuality" iordinace
```

## 8. Security Check

**Zkontroluj composer audit:**
```bash
composer audit
```

## Pre-deployment Checklist

- [ ] Žádné PHP syntax errors (`find . -name "*.php"...`)
- [ ] Žádné chyby v error logu
- [ ] Homepage vrací HTTP 200
- [ ] 404 stránka funguje
- [ ] Web funguje na mobilu (responsive)
- [ ] Žádné citlivé údaje v kódu
- [ ] Composer audit bez kritických chyb

Pokud všechny testy projdou ✅ → Ready pro deployment!
