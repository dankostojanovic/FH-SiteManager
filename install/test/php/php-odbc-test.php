<?php

/*
 *	Test of php odbc connection to pervasive
 *
 * */

// load config, get odbc config
// $config = json_decode(file_get_contents('../../src/config.json'),true);
// $odbc = $config['odbc'];

// use values from config to connect (not a traditional connection 
// string)
// We can't use a connection string, just the name of a dsn
// all connection settings should be in odbc.ini except login creds
// $db_conn = odbc_connect($odbc['dsn'], $odbc['uid'], $odbc['pwd']);
$db_conn = odbc_connect('odbc:Pervasive', "", "");

$query_string = 'select * from "OHCND01".po_header where po_no=?;';
$res = odbc_prepare($db_conn, $query_string);

if(!$res) die("could not prepare statement ".$query_string);

$parameters = array(203163);

echo "\n";
echo "\n";
if(odbc_execute($res, $parameters)) {
    $row = odbc_fetch_array($res);
	echo json_encode($row);
} else {
	echo odbc_errormsg($res);
}
echo "\n";
echo "\n";
