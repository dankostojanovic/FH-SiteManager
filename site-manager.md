# Site Manager

Site Manager uses [Smart Admin](https://wrapbootstrap.com/theme/smartadmin-responsive-webapp-WB0573SK0) and [Handsontable](https://handsontable.com/). Main goal was to allow users to manipulate data in Excel-like fashion.
It also uses modal for forms and views which do not naturally fir into a table view.


## Parts
Site Manager consists of:
1. [Smart Admin](https://wrapbootstrap.com/theme/smartadmin-responsive-webapp-WB0573SK0)
2. Alto Router API
3. Slim API
4. communities.php with [Handsontable](https://handsontable.com/) for front end


## Folders
Project consists of:
1. controller - functions to support AltoRouter
2. fischer_api - Propel, included packages and custom libraries
3. html - Smart Admin
4. install - ????


## Routes
Regular requests go through html/index.php.
If request URI matches any Alto router supported endpoint annonimous function is loaded from one of the controllers.
If request URI is / it is send to home.php. Feature module files are in app folder, e.g. Site Manager view is app/communities.php and corresponding URL is /#app/communities.php.

Slim API requests is redirected by .htaccess to api_index.php, e.g. https://sitemanager.fischermgmt.com/#app/communities.php.

login.php handles the login. It uses Fischer Homes LDAP to authorize users but MySQL users table for all user data and sessions for authorization.
API requests are authorized by header key, hard-coded to Authorization: 1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581d.


##Front-end
After loading html and js from communities.php, all communication with the server is handled by jquery ajax calls.