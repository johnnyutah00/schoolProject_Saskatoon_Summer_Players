#!/bin/bash
#
# This script will create an SQLite test database

#deletes existing database/migrations

cd /
cd d:
cd prj4.ssp/ssp_project/var

rm ssp.db

cd ../src/Migrations

rm Version*

#creates database and the needed migrations

cd /
cd d:
cd prj4.ssp/ssp_project/

sed -i 's#test#dev#' .env

cd config/packages/dev/

sed -i 's#mysql://adminer:TurnKey15@192.168.5.128:3306/ssp.db#sqlite:///%kernel.project_dir%/var/ssp.db#g' doctrine.yaml

cd ../../../

php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

cd config/packages/dev

sed -i 's#sqlite:///%kernel.project_dir%/var/ssp.db#mysql://adminer:TurnKey15@192.168.5.128:3306/ssp.db#g' doctrine.yaml

cd ../../../




