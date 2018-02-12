<?php
require_once('init.php');
unset($_SESSION['userId']);
// header( 'Location: http://w0lf.ddns.net/');

if($_GET['access_denied'] == 1) {
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/access_denied.php');
} else {
	header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/');
}
?>