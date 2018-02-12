# Data Integrator

Data integration between Pervasive and MySQL databases is handled by multiple systems.

Each of these systems is used based on where source of truth for specific data is, which systems need to have access to that data and performance/technical limitations involved.

## Pervasive to MySQL update
Pervasive is source of truth for 
1. Divisions
2. BudgetNeighborhoods
3. Communities
4. SpecLevels
5. AttachedBuildingCatalog
6. SiteholdReasons
7. Users
Site Manager needs access to this data but none of these entities are often changed.

Special Data Integrator software has been developed by Fischer Homes and is running on a special server (AWS instance ID i-080d0e8e) by cron jobs.
To manage this code you need to log into the server
ssh -i "FDI.pem" ubuntu@54.88.162.22
Code is in /var/jobs/data-integrator, uses FHomes/dataintegrator repository live branch.
To run this code you must use fischer user as it has all environment variables needed to access Pervasive database.
To execute update_mysql_fischer_api.php script you need to execute (bash command)
sh /var/jobs/data-integrator/src/runcron.sh update_mysql_fischer_api

To deploy code for DI
    ssh -i "FDI.pem" ubuntu@54.88.162.22
    cd /var/jobs/data-integrator
    git pull

    cd composer

    php ./vendor/propel/propel/bin/propel.php reverse fischer_api --schema-name fischer_api.schema --namespace fischer_api

    vi generated-reversed-database/fischer_api.schema.xml

remove namespace attribute from database node and save the file

    php ./vendor/propel/propel/bin/propel.php model:build
    php ./vendor/propel/propel/bin/propel.php config:convert
    php composer.phar dump-autoload

    sudo su fischer

    cd ../src/

to test run
    sh ./runcron.sh pervasive-to-mysql-daily