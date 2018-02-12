<?php
use Propel\Runtime\Propel;
require('dataTables.php');
/*get CRM Realtor Data*/ 
$realtor_getCrmData = function($log = null){
	if( checkauth(apache_request_headers()) == 1 ){
		// $realtors = \crm\RealtorQuery::create()->find();
		// echo $realtors->toJson();
		$sql = "SELECT Realtor.id, Realtor.agentNumber, Realtor.idBroker, Realtor.idPerson, CONCAT_WS(' ', Person.nameFirst, Person.nameLast) as 'Name', Person.email, Person.phone, Broker.idOrganization, Organization.name As \"Org Name\", Organization.streetAddress, Organization.city, Organization.idState, Organization.postalCode, Organization.phone FROM Realtor join Person on Realtor.idPerson = Person.id join Broker on Realtor.idBroker = Broker.id join Organization on Broker.idOrganization = Organization.id";

		$conn = Propel::getConnection('crm');
		$data = array();
		foreach($conn->query($sql) as $row){
			$data['data'][] = $row;
		}
		echo json_encode($data);
	}
};

$realtor_dtRealtor = function($log = null){
	if( checkauth(apache_request_headers()) == 1 ){
		// DB table to use
		$table = 'dt_realtor';
		 
		// Table's primary key
		$primaryKey = 'id';
		 
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes  
		// https://datatables.net/examples/data_sources/server_side.html
		$columns = array(
		    array( 'db' => 'id', 'dt' => 0 ),
		    array( 'db' => 'agentNumber',  'dt' => 1 ),
		    array( 'db' => 'idBroker',   'dt' => 2 ),
		    array( 'db' => 'idPerson',     'dt' => 3 ),
		    array( 'db' => 'Name', 'dt' => 4 ),
		    array( 'db' => 'email',  'dt' => 5 ),
		    array( 'db' => 'phone',   'dt' => 6 ),
		    array( 'db' => 'idOrganization',     'dt' => 7 ),
		    array( 'db' => 'Org Name', 'dt' => 8 ),
		    array( 'db' => 'streetAddress',  'dt' => 9 ),
		    array( 'db' => 'city',   'dt' => 10 ),
		    array( 'db' => 'idState',     'dt' => 11 ),
		    array( 'db' => 'postalCode',     'dt' => 12 ),
		    array( 'db' => 'Org Phone',     'dt' => 13 )
		);
		 
		$conn = Propel::getConnection('crm');
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		
		echo json_encode(
		    SSP::simple( $_POST, $conn, $table, $primaryKey, $columns )
		);
	}
};

/*This will not work on the Live server because dt_realtor view doesn't exist on the live server*/
$realtor_getInfo = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = Propel::getConnection('crm');
		$count = 0;
		/*starting filters here*/
		$sql = "SELECT * FROM dt_realtor ";
		$where = "WHERE "; $params = array();
		if( !empty($_REQUEST) ){
			// print_array($_REQUEST);
			$operator = "=";
			if(!empty($_REQUEST['operator']) ){
				$operator = $_REQUEST['operator'];
				unset($_REQUEST['operator']);
			}
			foreach ($_REQUEST as $key => $value) {
				$col = str_replace("_", " ", $key); // make colum name because post / get put _ in the place of a space.
				// echo $key ." ".$col."<br />";
				if($count != ( count($_REQUEST) -1) ){ // aka counter variable is not the last request item.
					$where .= ' `'.$col.'` '.$operator.' :'.$key.' AND';
				}else {
					$where .= ' `'.$col.'` '.$operator.' :'.$key;
				}
				$params[$key] = $value;
				$count++;
			}
			$sql .= $where;
		}
		// print_array($params);
		// echo $sql."<br />";
		$data = fetch($conn, $sql, $params);
		$conn = null;
		echo json_encode($data);
	}
};
/**
	* @SWG\Get(
	*     path="/realtor/",
	*     summary="Returns all realtor information.",
	*     description="Returns all realtor information ",
	*     operationId="realtor_getInfo",
	*	  tags={"Realtor"},
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         name="Authorization",
	*         in="header",
	*         description="pass the auth token in the header.",
	*         required=true,
	*         type="string",
	*         @SWG\Items(type="string"),
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Tokens Response",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/Realtor")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/Realtor"
	*         )
	*     )
	* )
*/

/**
	* @SWG\Post(
	*     path="/realtor/",
	*     summary="Returns all realtor information.",
	*     description="Returns all realtor information ",
	*     operationId="realtor_getInfo",
	*	  tags={"Realtor"},
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         name="Authorization",
	*         in="header",
	*         description="pass the auth token in the header.",
	*         required=true,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Parameter(
	*         name="Operator",
	*         in="POST",
	*         description="defaults to = if nothing is passed.",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Parameter(
	*         name="id",
	*         in="POST",
	*         description="Filter by realtor ID",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="agentNumber",
	*         in="POST",
	*         description="Filter by realtor agentNumber",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="idBroker",
	*         in="POST",
	*         description="Filter by realtor idBroker",
	*         required=false,
	*         type="integer",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="idPerson",
	*         in="POST",
	*         description="Filter by realtor idPerson",
	*         required=false,
	*         type="integer",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="Name",
	*         in="POST",
	*         description="Filter by realtor Name",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="email",
	*         in="POST",
	*         description="Filter by realtor Email",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="phone",
	*         in="POST",
	*         description="Filter by realtor phone",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="idOrganization",
	*         in="POST",
	*         description="Filter by realtor idOrganization",
	*         required=false,
	*         type="integer",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="Org Name",
	*         in="POST",
	*         description="Filter by realtor Org Name",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="streetAddress",
	*         in="POST",
	*         description="Filter by realtor streetAddress",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="city",
	*         in="POST",
	*         description="Filter by realtor city",
	*         required=false,
	*         type="string",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="idState",
	*         in="POST",
	*         description="Filter by realtor idState",
	*         required=false,
	*         type="integer",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="postalCode",
	*         in="POST",
	*         description="Filter by realtor PostalCode",
	*         required=false,
	*         type="integer",
	*         collectionFormat="csv"
	*     ),
	*	  @SWG\Parameter(
	*         name="Org Phone",
	*         in="POST",
	*         description="Filter by realtor Orginzation Phone",
	*         required=false,
	*         type="integer",
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Tokens Response",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/Realtor")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/Realtor"
	*         )
	*     )
	* )
*/
$realtor_getInfo2 = function($id = null, $log = null){
	if(!empty($id) && checkauth(apache_request_headers(), $log) == 1 ){
		$conn = Propel::getConnection('crm');
		/*starting filters here*/
		$sql = "SELECT * FROM dt_realtor Where id = :id";
		$params = array('id' => $id);
		// print_array($params);
		// echo $sql."<br />";
		$data = fetch($conn, $sql, $params);
		$conn = null;
		echo json_encode($data);
	}
};

$realtor_byControlCode = function($ControlCode = null, $log = null){
	if( !empty($ControlCode) && checkauth(apache_request_headers(), $log) == 1 ){
		$lead = \crm\LeadQuery::create()->filterByControlCode($ControlCode)->findone();
		if( !empty( $lead ) ){
			$conn = Propel::getConnection('crm');
			$sql = "SELECT * FROM dt_realtor Where id = :id";
			$params = array('id' => $lead->getIdrealtor());
			$data = fetch($conn, $sql, $params);
			$conn = null;
			echo json_encode($data);
		}else{
			echo json_encode(array());
		}
	}
};

/**
	* @SWG\Get(
	*     path="/realtor/filterbyControlCode/{ControlCode}/",
	*     summary="Returns all realtor information.",
	*     description="Returns all realtor information ",
	*     operationId="realtor_getInfo",
	*	  tags={"Realtor"},
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         name="Authorization",
	*         in="header",
	*         description="pass the auth token in the header.",
	*         required=true,
	*         type="string",
	*         @SWG\Items(type="string"),
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Parameter(
	*         description="Control Code ",
	*         in="path",
	*         name="ControlCode",
	*         required=true,
	*         type="string",
	*         format="string"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Tokens Response",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/Realtor")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/Realtor"
	*         )
	*     )
	* )
*/
?>