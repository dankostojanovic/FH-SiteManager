<?php 

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
// echo "Made it here to ODBC LAND!";
/*$PVSW_ROOT = "/usr/local/psql";
putenv("PVSW_ROOT=/usr/local/psql");
putenv("PATH=/usr/local/psql/bin:/bin:/usr/bin");
putenv("LD_LIBRARY_PATH=/lib/x86_64-linux-gnu:/usr/lib/x86_64-linux-gnu:$PVSW_ROOT/lib:$PVSW_ROOT/lib64:$PVSW_ROOT/bin:/usr/lib");

putenv("MANPATH=$PVSW_ROOT/man:$PVSW_ROOT/man");
putenv("BREQ=$PVSW_ROOT/lib");
putenv("LD_BIND_NOW=1");

putenv("ODBCSYSINI=/etc");
putenv("ODBCINI=/etc/odbc.ini");*/

// exec ("sudo ../install/pervasive/bashrc");

// exec ("sudo su psql");
// echo `whoami`;

// var_dump( posix_getpwuid('1002'));

// $groups = posix_getgroups();
// print_array(posix_getgroups());

// foreach ($groups as $key => $g) {
// 	print_array(posix_getgid());
// }
// echo $_ENV['DIR']

// include_once '../install/pervasive/bashrc';
// print_array($_ENV);

// exec(" Defaults:www-data !requiretty");
// exec("www-data ALL=(psql) NOPASSWD: /usr/local/psql");

$dsn ="odbc:Pervasive";
// $dsn ="Pervasive";

/*try {
    $dbh = new PDO($dsn, '', '');
} 
catch(PDOException $e) {
    echo $e->getMessage();
}*/

// print_array($dbh);
// var_dump($dbh);

// $sql = "select * from \"FISCHER MANAGEMENT\".\"AlertType\";";
// $sql = "select * from \"AlertType\"";

/*try{
    // $dbh = new pdo( 'odbc:pervasive;dbname="FISCHER MANAGEMENT"',
    $dbh = new pdo( 'odbc:pervasive',
                    '',
                    '',
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    print_array($dbh);

    // die(json_encode(array('outcome' => true)));
}
catch(PDOException $ex){
    die(json_encode(array('outcome' => false, 'message' => 'Unable to connect '. $ex->getMessage() ) ) );
}*/

/*function fetch($dbh,$query,$params=array()){
	//return array of rows
	$rows = array();
	try {
		$stmt = $dbh->prepare($query);
		$stmt->execute($params);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch(PDOException $ex) {
		// error('Failed to fetch: '.$ex->getMessage());
		echo 'Failed to fetch: '.$ex->getMessage();
	}
	return $rows;
}*/

// $dbh = new pdo( 'odbc:pervasive', '', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
// $dbh = new pdo( 'odbc:pervasive');

// $sql = 'SHOW TABLES';
// if($dbh->is_connected())
// {
    // $query = $dbh->query($sql);
    // return $query->fetchAll(PDO::FETCH_COLUMN);
// }

	/*$query = "
		SELECT Datasource FROM
		INFOSYS.Datasources
		WHERE DatasourceType='O';
	";*/

	// $dbh = new PDO($dsn, '', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$dbh = new PDO($dsn, '', '');
	// $query = 'SELECT * FROM "INFOSYS"."Fischer_App" ;';
	// $query = "SELECT * FROM \"FISCHER MANAGEMENT\".\"AlertType\" where AlertType = 'WTF';";
	// $query = "SELECT * FROM \"FISCHER MANAGEMENT\".\"AlertType\";";
	// $query = 'Call "INFOSYS"."WU_getBaseFeatures";';
	// $query = "Call WU_getPlanAvailability";
	

	/*2.97 seconds even worse*/
	/*$stmt = $dbh->prepare($query);
	$stmt->execute();
	$row = $stmt->fetchall();

	// print_array($row);
	echo json_encode($row);*/
	// echo json_encode($row);

	// foreach ($row as $key => $value) {
	// 	print_array($value);
	// }

	/*2.91 seconds this way */
	/*$stmt = $dbh->prepare($query);
	$rows = fetch($dbh,$query); 
	print_array($rows);*/

	// print_array($rows);
	/*foreach($rows as $row){
		$c = $row['Datasource'];
		debug("Database: $c");
		if($c){
			$companies[] = $c;
		}
	}*/

	// $stmt = $dbh->query($query);
	/*$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($result as $key => $value) {
		print_array($value);
	}*/

	/*while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
   		print_array($row);
   		// var_dump($row);
	}*/

// start odbc connect
// $connection_string = "Driver={Pervasive ODBC Interface}";
/*$connection_string = "Pervasive";

$connection = odbc_connect($connection_string, null, null);

print_array($connection);

$sql = 'SELECT * FROM "INFOSYS"."Fischer_App"';
$rs = odbc_exec($connection, $sql);
// var_dump($rs);
foreach ($rs as $key => $r) {
	print_array($r);
}*/

/*
echo shell_exec('isql -v Pervasive');
echo "<br />";
echo shell_exec('isql -v Pervasive << END SELECT * FROM "INFOSYS"."Fischer_App" go');
*/
?>