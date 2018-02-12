# Data Migration

Data migration from Pervasive and MySQL database is handled by uncommitted version of Data Integrator software.

It exists and is executed only on Fischer laptop (asset number 2476).

## Usage
To gain access to this code use user ty
terminal:
    cd /home/ty/sites/data-integrator
    vagrant up
    vagrant ssh
    cd /vagrant
    git checkout migrate 
    cd /src
    sudo su fischer
    sh ./runcron.sh pervasive-to-fischer_v3-sites - or whichever file needs to be executed

To migrate all data a number of scripts should be ran:
1. update-mysql-fischer_api - one time and moves Community, Division, BudgetNeighborhood, SpecLevel, AttachedBuildingCatalog and SiteHoldReason entities.
2. pervasive-to-fischer_api-fischer_sections
3. pervasive-to-fischer_api-legal_sections
4. pervasive-to-fischer_api-sites
5. pervasive-to-fischer_api-site_inventories
6. pervasive-to-fischer_api-site_addls
7. pervasive-to-fischer_api-site_notes
8. pervasive-to-fischer_api-sitehold_archives
9. pervasive-to-fischer_api-users
10. pervasive-to-fischer_api-siteholds
11. pervasive-to-fischer_api-vendors
