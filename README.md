# symfony01
FOR LOCAL:
1) clone repo
2) config .env with your DATABASE_URL, APP_ENV and APP_SECRET to connect to database
3) run composer install in terminal for composer dependencies
4) run npm install in terminal for npm dependencies
5) run composer require symfony/webpack-encore-bundle in terminal for the webpack dependencies
6) run composer require symfony/ux-live-component in terminal for the live components of SymfonyUX
7) run php bin/console doctrine:database:create in terminal to create the database locally
8) run php bin/console doctrine:schema:update --force in terminal to update all the schemas defined in entitys
9) run symfony server:start and npm run watch for the live components of SymfonyUX

for any errors, verify:
- versions of PHP and Symfony (PHP 8.1 or +, SYMFONY 6)
- WAMP or XAMPP running on background (must be)
- setting enviroment variables for symfony or composer in windows/macOs
- having the symfony.exe that is required for the enviroment variable 
