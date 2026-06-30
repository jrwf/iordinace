Vytvoř commit s souvisejícími změnami podle následujícího workflow:

1. **Vyčisti Drupal cache**
   - Spusť: `vendor/bin/drush cr`
   - Ověř úspěch (zkontroluj výstup)

2. **Vyexportuj konfiguraci**
   - Spusť: `vendor/bin/drush cex -y`
   - Zkontroluj, jaké config soubory byly změněny

3. **Zkontroluj git status**
   - Spusť: `git status`
   - Spusť: `git diff` pro změněné soubory
   - Identifikuj, které soubory spolu tematicky souvisí

4. **Přidej pouze související soubory**
   - NIKDY nepoužívej `git add .` (přidá všechno!)
   - Použij: `git add <konkrétní-soubory>`
   - Přidávej pouze změny související s jednou funkcionalitou/úpravou

5. **Vytvoř commit**
   - Spusť `git log --oneline -5` pro zjištění stylu commit messages
   - Vytvoř commit s popisnou zprávou:
     - Začni prefixem: `Feature:`, `Fix:`, `Config:`, `Docs:`, `Refactor:`
     - Použij české znaky
     - Popis co a proč (ne jak)
   - Zahrň Claude Code footer (emoji robot + Co-Authored-By)

**Důležité:**
- Commituj pouze související změny (atomic commits)
- Nekombinuj různé funkcionality do jednoho commitu
- Vždy zkontroluj `git diff` před `git add`
- Config soubory (.yml) patří ke změnám, které je vytvořily
