<?php
require_once 'init.php';

$_SESSION['userId'] = 2;

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

include 'home.php';
?>