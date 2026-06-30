Připrav změny pro deployment.

**UPOZORNĚNÍ:** Tento příkaz připravuje změny pro deployment. Samotný push do remote repozitáře proveď manuálně!

## Deployment Workflow

### 1. LOCAL (Docker) - Příprava změn

1. **Zkontroluj git status**
   ```bash
   git status
   git diff --stat
   ```
   Ověř, že všechny změny jsou záměrné.

2. **Vytvoř commit**
   - Zavolej `/jw-commit` pro vytvoření commitu

3. **Push do remote repozitáře**
   ```bash
   git push origin master
   ```

### 2. PRODUCTION Server - Deployment

**Po push do remote repozitáře:**

```bash
# SSH na PRODUCTION server
ssh user@prod-server
cd /path/to/project

# Pull změny
git pull origin master

# Restart apache (pokud potřeba)
sudo systemctl restart apache2
# nebo
sudo service apache2 restart
```

**Pokud máš deploy script:**
```bash
./deploy.sh
```

### 3. Ověření po deployi

```bash
# Test HTTP odpovědi
curl -sI https://tvoje-domena.cz/

# Zkontroluj error log
tail -n 20 /var/log/apache2/error.log
```

## Checklist před deploymentem

- [ ] Všechny změny commitnuty (`git status` čistý)
- [ ] Push do remote repozitáře dokončen
- [ ] Změny otestovány lokálně v Dockeru
- [ ] Žádné PHP chyby v error logu
- [ ] Web funguje: http://localhost

## Rollback

Pokud něco selže na PRODUCTION:

```bash
# Vrať git na předchozí verzi
git log --oneline -5
git reset --hard HEAD~1

# Zkontroluj funkčnost
curl -sI https://tvoje-domena.cz/
```
