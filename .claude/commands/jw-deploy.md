Připrav změny pro deployment podle three-tier workflow:

**NOTE:** Tento příkaz je generický workflow. Pro specifický projekt uprav:
- Názvy prostředí (LOCAL/STAGE/PRODUCTION nebo jiné)
- SSH přístupy a cesty k projektům
- Deploy scripty (pokud existují)
- Pokud používáš pouze lokální Docker, zaměř se na sekci 1

**UPOZORNĚNÍ:** Tento příkaz připravuje změny pro deployment. Samotný push do remote repozitáře proveď manuálně!

## Deployment Workflow

### 1. LOCAL (Docker) - Příprava změn

**Aktuální krok - spustíš lokálně:**

1. **Vyčisti cache**
   - `vendor/bin/drush cr`

2. **Zkontroluj pending database updates**
   - `vendor/bin/drush updatedb:status`
   - Pokud jsou pending updates, spusť: `vendor/bin/drush updb -y`

3. **Vyexportuj konfiguraci**
   - `vendor/bin/drush cex -y`
   - Zkontroluj, které config soubory byly změněny

4. **Zkontroluj změny**
   - `git status`
   - `git diff config/sync/`
   - Ověř, že všechny config změny jsou zamýšlené

5. **Vytvoř commit**
   - Zavolej `/jw-commit` pro vytvoření commitu
   - NEBO použij PhpStorm pro commit + push

### 2. STAGE Server - Testovací deployment (volitelné)

**Po push do remote repozitáře (pokud máš STAGE prostředí):**

```bash
# SSH na STAGE server
ssh user@stage-server
cd /path/to/project

# Pokud máš deploy script:
./deploy-stage-local.sh

# NEBO manuální deployment:
vendor/bin/drush state:set system.maintenance_mode 1 --input-format=integer
vendor/bin/drush sql:dump --gzip --result-file=backups/stage-backup-$(date +%Y%m%d-%H%M%S).sql
git pull origin master
composer install  # WITH dev dependencies pro STAGE
vendor/bin/drush updb -y
vendor/bin/drush cim -y
vendor/bin/drush cr
vendor/bin/drush state:set system.maintenance_mode 0 --input-format=integer
```

**Testování na STAGE:**
- Zkontroluj funkčnost všech změn
- Ověř, že konfigurace byla správně importována
- Otestuj všechny nové funkcionality
- Zkontroluj logy: `/admin/reports/dblog`

### 3. PRODUCTION Server - Produkční deployment (volitelné)

**POUZE pokud vše funguje na STAGE (nebo lokálně otestováno)!**

```bash
# SSH na PRODUCTION server
ssh user@prod-server
cd /path/to/project

# Pokud máš deploy script:
./deploy-prod.sh

# NEBO manuální deployment (OPATRNĚ!):
vendor/bin/drush state:set system.maintenance_mode 1 --input-format=integer
vendor/bin/drush sql:dump --gzip --result-file=backups/prod-backup-$(date +%Y%m%d-%H%M%S).sql
git pull origin master
composer install --no-dev  # NO dev dependencies na PRODUCTION!
vendor/bin/drush updb -y
vendor/bin/drush cim -y
vendor/bin/drush cr
# Vypni dev moduly (devel, webprofiler, kint)
vendor/bin/drush pmu devel webprofiler kint -y
vendor/bin/drush state:set system.maintenance_mode 0 --input-format=integer
```

## Checklist před deploymentem

**LOCAL:**
- [ ] Všechny změny commitnuty
- [ ] Config exportován (`drush cex`)
- [ ] Database updates aplikovány (`drush updb`)
- [ ] Cache vyčištěna (`drush cr`)
- [ ] Změny pushnuty do remote repozitáře

**STAGE:**
- [ ] Deploy script úspěšně dokončen
- [ ] Web funguje bez chyb
- [ ] Všechny nové funkcionality otestovány
- [ ] Config správně importována
- [ ] Žádné chyby v logu

**PRODUCTION:**
- [ ] STAGE deployment úspěšný
- [ ] Všechny testy prošly
- [ ] Backup vytvořen před deploymentem
- [ ] Dev moduly budou vypnuty (kromě symfony_mailer, backup_migrate)

## Důležité poznámky

⚠️ **NIKDY nepřeskakuj STAGE deployment!**
⚠️ **VŽDY testuj na STAGE před PRODUCTION!**
⚠️ **Database backups jsou v `backups/` directory**
⚠️ **Git authentication: použij SSH klíče (doporučeno) nebo HTTPS**

## Rollback

Pokud něco selže na PRODUCTION:

```bash
# Obnov database z backupu
gunzip -c backups/prod-backup-YYYY-MM-DD-HHMMSS.sql.gz | mysql -u user -p database_name

# Vrať git na předchozí verzi
git reset --hard HEAD~1

# Znovu spusť deployment
./deploy-prod.sh
```

## CI/CD Pipeline (volitelné)

**Pokud máš nastavené GitHub Actions nebo jiné CI/CD:**
1. **CI Tests** - Spustí se automaticky při push/PR
2. **Deploy to STAGE** - Spustí se automaticky při push do master
3. **Deploy to PRODUCTION** - POUZE manuálně (requires confirmation)

Pro lokální Docker vývoj většinou není CI/CD potřeba.
