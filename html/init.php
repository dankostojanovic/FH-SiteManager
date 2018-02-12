<?php
// phpinfo();
session_start();
// date_default_timezone_set('America/Indiana/Indianapolis');
// data_default_timezone_set('UTC');

// setup the autoloading
require_once '/var/www/fischer_api/vendor/autoload.php';

// setup Propel
require_once '/var/www/fischer_api/generated-conf/config.php';

include_once 'functions.inc.php';
