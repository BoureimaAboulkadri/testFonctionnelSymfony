composer install
yarn install
yarn encore production 

## reconfig .env : doctrine/doctrine-bundle
php bin/console doctrine:database:create
php bin/console doctrine:schema:create

## creation des fixtures
composer require --dev orm-fixtures
composer require --dev doctrine/doctrine-fixtures-bundle
composer require --dev fakerphp/faker

## creation des class fixtures
php bin/console make:fixtures
    -> UserFixtures
 
php bin/console make:fixtures
    -> ArticleFixtures

## chargement des fixtures
php bin/console doctrine:fixtures:load  
    -> yes


## Create database for env testing
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
php bin/console --env=test doctrine:fixtures:load


## install Panther
composer require --dev symfony/panther