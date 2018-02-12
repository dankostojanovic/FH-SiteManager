<?php 
use Propel\Runtime\Propel;
require_once('dataTables.php');


$getPDVData = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		// DB table to use
		$table = 'pdv';
		
		// Table's primary key
		$primaryKey = 'id_column';
		 
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes  
		// https://datatables.net/examples/data_sources/server_side.html
		$columns = array(
		    array( 'db' => 'dbName', 'dt' => 0 ),
		    array( 'db' => 'table_name',  'dt' => 1 ),
		    array( 'db' => 'id_column',   'dt' => 2 ),
		    array( 'db' => 'ColName',     'dt' => 3 ),
		    array( 'db' => 'type', 'dt' => 4 ),
		    array( 'db' => 'size',  'dt' => 5 )
		);
		 
		$conn = Propel::getConnection('apidb');
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		
		echo json_encode(
		    SSP::simple( $_POST, $conn, $table, $primaryKey, $columns )
		);
	}
};

$getDBdata = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = Propel::getConnection('apidb');
		$databases = \apidb\PervasiveDatabaseQuery::Create()->find();
		$data = array();

		$totalTableCount = 0; $totalColumnCount = 0; $totalDbCount = 0;

		if(!empty($databases)){
			foreach ($databases as $key => $db) {
				$data[$db->getId()]['Name'] = $db->getName();
				$data[$db->getId()]['TableCount'] = $db->getTableCount();
				// $data[$key]['ColumnCount'] = $db->getColumnCount(); // this function is way to slow.
				$totalTableCount += $data[$db->getId()]['TableCount'];
				$totalDbCount++;
			}
		}

		$sql = "SELECT id_database, count(*) as 'count' FROM pervasive_columns GROUP BY id_database";
		$queryData = fetch($conn, $sql);
		if(!empty($queryData)){
			foreach ($queryData as $key => $row) {
				$data[$row['id_database']]['ColumnCount'] = (int)$row['count'];
				$totalColumnCount += (int)$row['count'];
			}
		}

		$data['totals']['totalDbCount'] = $totalDbCount;
		$data['totals']['totalTableCount'] = $totalTableCount;
		$data['totals']['totalColumnCount'] = $totalColumnCount;
		
		echo json_encode($data);
	}
};

$getDBdata2 = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = Propel::getConnection('apidb');
		$data = array();
		$sql = "SELECT id_database, count(*) as 'count' FROM pervasive_columns GROUP BY id_database";
		$queryData = fetch($conn, $sql);
		if(!empty($queryData)){
			foreach ($queryData as $key => $row) {
				$data[$row['id_database']] = $row['count'];
			}
		}
		echo json_encode($data);
	}
};
?>