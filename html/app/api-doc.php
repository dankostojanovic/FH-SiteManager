<?php 
require_once("../init.php");
// checkLogin();
// require_once("inc/init.php");

// $swagger = \Swagger\scan('/var/www');
$swagger = \Swagger\scan('/var/www/controller');

// $swagger = \Swagger\scan('/var/www/composer/ORM');
header('Content-Type: application/json');
echo $swagger;
// print_array($swagger);
?>