# symfony01
FOR LOCAL:
1) clone repo
5) config .env with your DATABASE_URL, APP_ENV and APP_SECRET to connect to database
2) run composer install in terminal for composer dependencies
3) run npm install in terminal for npm dependencies
4) run php bin/console doctrine:database:create in terminal to create the database locally
5) run php bin/console doctrine:schema:update --force in terminal to update all the schemas defined in entitys
6) run symfony server:start and npm run watch for the live components of SymfonyUX

for any errors, verify:
- versions of PHP and Symfony (PHP>7, SYMFONY 6)
- WAMP or XAMPP running on background (must be)
- setting enviroment variables for symfony or composer in windows/macOs
- having the symfony.exe that is required for the enviroment variable 
