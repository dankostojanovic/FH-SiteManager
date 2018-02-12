<?php 
use Propel\Runtime\Propel;
require_once('dataTables.php');
$request_getDataTableData = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		// DB table to use
		// $table = 'request';
		// date_default_timezone_set('America/New_York');
		$table = 'dt_request';
		 
		// Table's primary key
		$primaryKey = 'id';
		
		/*can't get this to work but would be dynamic way to say what domain this is.*/
		/*$domains = \apidb\DomainsQuery::create()->find()->toArray();
		$domain = array();
		foreach ($domains as $key => $d) {
			$domain[$d['Id']] = $d['Name'];
		}*/

		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes  
		// https://datatables.net/examples/data_sources/server_side.html
		$columns = array(
		    array( 'db' => 'id', 'dt' => 0, 'formatter' => function($d, $row){
		    	if(!empty($row['data_before']) && !empty($row['data_after'])){
		    		// return "<a href='javascript:compareData(".$d.")' class='btn btn-primary' >".$d."</a>";
		    		return "<div class='btn-group'>
								<button class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
									".$d."<span class='caret'></span>
								</button>
								<ul class='dropdown-menu'>
									<li>
										<a href='javascript:compareData(".$d.");'>Compare Data</a>
									</li>
									<li>
										<a href='javascript:compareTable(".$d.");'>Compare Table</a>
									</li>
									<li>
										<a href='javascript:tarinaTable(".$d.");'>Tarina Table</a>
									</li>
								</ul>
							</div>";
		    	}else{
		    		return $d;
		    	}
		    } ),
		    array( 'db' => 'token_id',  'dt' => 1 ),
		    array( 'db' => 'header',   'dt' => 2, 'formatter' => function($d, $row){
		    	if(!empty($d)){
		    		return "<a href='javascript:viewHeaders(".$row['id'].")' class='btn btn-default' >View Headers</a>";
		    	}else{
		    		return $d;
		    	}
		    } ),
		    array( 'db' => 'domain',     'dt' => 3),
		    array( 'db' => 'request_variables', 'dt' => 4, 'formatter' => function($d, $row){
		    	// if(!empty($d)){
		    	if( $d != '[]'){
		    		return "<a href='javascript:viewVariables(".$row['id'].")' class='btn btn-info' >View Variables</a>";
		    	}else{
		    		return $d;
		    	}
		    } ),
		    array( 'db' => 'data_before',  'dt' => 5 , 'formatter' => function($d, $row){
		    	if(!empty($d)){
		    		return "<a href='javascript:viewDataBefore(".$row['id'].")' class='btn btn-warning' >View Before</a>";
		    	}else{
		    		return $d;
		    	}
		    } ),
		    array( 'db' => 'data_after',   'dt' => 6 , 'formatter' => function($d, $row){
		    	if(!empty($d)){
		    		return "<a href='javascript:viewDataAfter(".$row['id'].")' class='btn btn-success' >View After</a>";
		    	}else{
		    		return $d;
		    	}
		    } ),
		    array( 'db' => 'route',     'dt' => 7 ),
		    array( 'db' => 'request_ip', 'dt' => 8 ),
		    /*array( 'db' => 'start_datetime',  'dt' => 9 ),
		    array( 'db' => 'stop_datetime',   'dt' => 10 ),
		    array( 'db' => 'start',     'dt' => 11 ),
		    array( 'db' => 'stop',     'dt' => 12 ),*/
		    array( 'db'=> 'start_datetime', 'dt' => 9, 'formatter' => function($d, $row){
		    	// return date('Y-m-d', strtotime($row['start_datetime']) );
		    	return date('Y-m-d', strtotime($d) );
		    }),
		    array( 'db'=> 'start_datetime', 'dt' => 10, 'formatter' => function($d, $row){
		    	// need to get this coverted over to eastern standard time.
				// $date = new DateTime($d, new DateTimeZone('America/New_York'));
				// return $date->format('h:i:s A');
				// return $date->format('h:i:s A');
		    	return date('h:i:s A', strtotime($d) );
		    	// return date('h:i:s A', $date->format('U') );
		    	// return $d;
		    }),
		    array( 'db' => "total time", 'dt' => 11, 'formatter' => function($d, $row){
		    	if( $d > 0 ){
		    		return $d;
		    	}else{
		    		return 0;
		    	}
		    } ),
		    array( 'db' => 'accepted',     'dt' => 12 )
		);
		 
		$conn = Propel::getConnection('apidb');
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		// print_array($_POST);
		// print_array($conn);
		echo json_encode(
		    SSP::simple( $_POST, $conn, $table, $primaryKey, $columns )
		);
	}
};


$testRequest = function($log = null){
	$domains = \apidb\DomainsQuery::create()->find()->toArray();
	// print_array($domains);
	$tmp = array();
	foreach ($domains as $key => $domain) {
		$tmp[$domain['Id']] = $domain['Name'];
	}
	print_array($tmp);
};

$request_getInfo = function($id = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($id) ){
		$request = new \apidb\Request($id);
		echo $request->tojson();
	}
};


$request_getDataTableCompare = function($id = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($id) ){
		$request = new \apidb\Request($id);
		if( !empty($request->getDataBefore()) && !empty($request->getDataAfter())){
			$before = json_decode($request->getDataBefore(), true);
			$after = json_decode($request->getDataAfter(), true);
			// $before = json_decode($request->getDataBefore());
			// $after = json_decode($request->getDataAfter());
			$compare = array();
			foreach ($before as $key => $v) {
				/*$compare[$key]['column'] = $key;
				$compare[$key]['before'] = $v;
				$compare[$key]['after'] = $v;
				$compare[$key]['changed'] = 0;*/
				$compare[$key][0] = $key;
				$compare[$key][1] = $v;
				$compare[$key][2] = $v;
				$compare[$key][3] = 0;
			}
			foreach ($after as $key => $v) {
				/*$compare[$key]['after'] = $v;
				if($compare[$key]['before'] !== $v){
					$compare[$key]['changed'] = 1;
				}*/
				$compare[$key][2] = $v;
				if($compare[$key][1] !== $v){
					$compare[$key][3] = 1;
				}
			}
			$c = 0; $tmp = array();
			foreach ($compare as $key => $v) {
				// $tmp['data'][$c] = $v;
				$data[$c] = $v;
				// echo $key."  ".$c."<br />";
				// print_array($v);
				$c++;
			}

			$tmp['data'] = $data;
			echo json_encode($tmp);
		}else{
			header("HTTP/1.0 404 Not Found");
		}
	}else{
		header("HTTP/1.0 404 Not Found");
	}
};


$request_getTarinaCompare = function($id = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($id) ){
		$request = new \apidb\Request($id);
		// print_array($request->toArray());
		if( !empty($request->getDataBefore()) && !empty($request->getDataAfter())){
			$before = json_decode($request->getDataBefore(), true);
			$after = json_decode($request->getDataAfter(), true);
			$tarinaColumns = getTarinaColumns();
			// print_array($tarinaColumns);

			$pervasive = new PDO('odbc:live-Pervasive', '', '');
			$db = getDatabase($pervasive, $before['Job_Number']);
			// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."Job_Information" WHERE "Job_Number" = \''.$before['Job_Number'].'\'';
			$sql = 'SELECT * FROM "'.$db.'"."Job_Information" WHERE "Job_Number" = \''.$before['Job_Number'].'\'';
			// echo $sql;
			// $now = array();
			$now2 = fetch($pervasive, $sql);
			$now = utf8_converter($now2);
			// print_array($now[0]);
			// print_array($before);
			// print_array($after);

			$compare = array();
			foreach ($before as $key => $v) {
			/*
				$compare[$key]['column'] = $key;
				$compare[$key]['before'] = $v;
				$compare[$key]['after'] = $v;
				$compare[$key]['changed'] = 0;
			*/
				$compare[$key][0] = $key;
				$compare[$key][1] = $v;
				$compare[$key][2] = $v;
				$compare[$key][3] = 0;
				$compare[$key][4] = '';
				$compare[$key][5] = 0;
				$compare[$key][6] = 0;
			}
			foreach ($after as $key => $v) {
				/*$compare[$key]['after'] = $v;
				if($compare[$key]['before'] !== $v){
					$compare[$key]['changed'] = 1;
				}*/
				$compare[$key][2] = $v;
				if($compare[$key][1] !== $v){
					$compare[$key][3] = 1;
				}
			}

			if(!empty($now[0]) ){
				foreach ($now[0] as $key => $v) {
					// now
					$compare[$key][4] = $v;
					if($compare[$key][1] !== $v){
						// has changed since before
						$compare[$key][5] = 1;
					}
					// else{
					// 	$compare[$key][5] = 0;
					// }
				}
			}

			// make it so we can filter based on ticket
			foreach ($tarinaColumns as $key => $v) {
				if(!empty($compare[$v])){
					$compare[$v][6] = 1;	
				}
			}

			// make data into dataTable format
			$c = 0; $tmp = array();
			foreach ($compare as $key => $v) {
				// $tmp['data'][$c] = $v;
				$data[$c] = $v;
				// echo $key."  ".$c."<br />";
				// print_array($v);
				$c++;
			}

			$tmp['data'] = $data;
			echo json_encode($tmp);
		}else{
			header("HTTP/1.0 404 Not Found");
		}
	}else{
		header("HTTP/1.0 404 Not Found");
	}
};

?>