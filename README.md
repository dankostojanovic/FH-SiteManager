# SiteManager
Site Manager is a web based system for management of Sites, Fischer Sections and Legal Sections.
Primary goal is to provide a user friendly solution based on modern technologies what will be easy to maintain, extend and scale.  

Site Manager uses [Smart Admin](https://wrapbootstrap.com/theme/smartadmin-responsive-webapp-WB0573SK0) and [Handsontable](https://handsontable.com/). Main goal was to allow users to manipulate data in Excel-like fashion.  

It also uses modal for special forms and views which don't naturally fit into a table view.  

All data is acquired through jquery ajax calls.

It is coupled with [Propel ORM](http://propelorm.org/) through API and user objects with minimal Authentication through hard-coded Authentication key.  

## Installation
Clone repository with 
* ```git clone https://github.com/FHomes/SiteManager.git SiteManager```
* ```cd SiteManager/```

Copy the information from ```propel.yml.example``` into a file called ```propel.yml```.
Copy the information from ```.env.example``` into a file called ```.env```
Then you can use the command:

* ```vagrant up```  

This will then begin the install of all the information that is needed to make Site Manager work.  
Once the install is finished, navigate to 127.0.0.1:8080.

## Parts:
Site Manager handsontable and html/js code
Alto router
Slim api
Propel ORM
Pervasive API component
Event Emitter component
LDAP
Data with Pervasive as source of truth
Database backusps


Pervasive database is still source of truth for
1. Divisions
2. BudgetNeighborhoods
3. Communities
4. SpecLevels
5. AttachedBuildingCatalog
6. SiteholdReasons
7. Users

## Systems used
Data updates pushed to Pervasive
1. Fischer Sections
2. Legal Sections
3. Sites
4. Legal Section Mortgages

Initial migration of data is handled by data integrator

## Changes to database
Run rfa script

## Servers
Site Manager development should be done locally, in a virtual machine.
Staging server is at http://sitemanager-staging.fischermgmt.com
Production server is at http://sitemanager.fischermgmt.com
Both use [letsencypt](https://letsencrypt.org/) service for ssl certificates.

Deployment is manual:
commit/push code
ssh into server
pull code
execute Propel "reverse engineer" procedure. There is a "rfa" shell script on both servers which does the same,
or read the [Docs](http://propelorm.org/documentation/cookbook/working-with-existing-databases.html).


## Contributing
1. Fork the repository
2. Create a feature branch: `git checkout -b my-new-feature`
3. Commit changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request
## Credits
Default routes (Alto Router API) by Ty Hudson
Smart Admin pages by Ty Hudson
Slim API pages by Danko Stojanovic
communities.php by Danko Stojanovic


## License
© 2017 Fischer Homes. All Rights Reserved
