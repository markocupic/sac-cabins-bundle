:: Run phpstan inside your IDE e.g. PhpStorm (Windows only)
:: Install inside PhpStorm the  "Batch Script Support" plugin
cd..
cd..
cd..
cd..
cd..
cd..
php -d memory_limit=-1 vendor/bin/phpstan analyse vendor/markocupic/sac-cabins-bundle/src vendor/markocupic/sac-cabins-bundle/tests
