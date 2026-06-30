Zkontroluj kompletní stav projektu a připravenost na commit/deployment:

## 1. Git Status

**Zkontroluj stav repozitáře:**
```bash
git status
git log --oneline -5
git diff --stat
```

**Co hledat:**
- Uncommitted changes (červené soubory)
- Staged changes (zelené soubory)
- Untracked files (nové soubory)
- Commits ahead of origin

## 2. Docker Status

**Zkontroluj běžící kontejnery:**
```bash
docker compose ps
```

**Zkontroluj logy aplikace:**
```bash
docker compose logs --tail=20 app
```

## 3. PHP Error Log

**Zkontroluj chyby Apache:**
```bash
docker compose exec app tail -n 30 /var/log/apache2/error.log
```

**Nebo přímo v prohlížeči** — zapni zobrazování chyb v `config.php` (pouze lokálně).

## 4. Databáze

**Test připojení:**
```bash
docker compose exec database mysql -uroot -proot -e "SELECT 1" iordinace
```

**Zkontroluj tabulky:**
```bash
docker compose exec database mysql -uroot -proot -e "SHOW TABLES" iordinace
```

## 5. Composer / Dependencies

**Zkontroluj security issues:**
```bash
composer audit
```

## 6. Připravenost na Commit

**✅ Ready pro commit pokud:**
- [ ] `git status` — jasné co se commituje
- [ ] Žádné PHP chyby v error logu
- [ ] Web funguje v prohlížeči (http://localhost)
- [ ] Žádné citlivé údaje (hesla, API klíče) v kódu

**Pokud ANO → Zavolej `/jw-commit`**

## 7. Připravenost na Deployment

**✅ Ready pro deployment pokud:**
- [ ] Všechny změny commitnuty
- [ ] Push do remote repozitáře dokončen
- [ ] Změny otestovány lokálně

**Pokud ANO → Zavolej `/jw-deploy`**

## Quick Commands

```bash
# Git přehled
git status && git diff --stat && git log --oneline -5

# Docker stav
docker compose ps && docker compose logs --tail=10 app

# Test webu
curl -sI http://localhost/
```
