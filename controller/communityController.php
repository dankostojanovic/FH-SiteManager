<?php 

$community_getAll = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$communities = \crm\CommunityQuery::create()->find();
		echo $communities->toJson();
	}
};

/**
	* @SWG\Get(
	*     path="/community/",
	*     summary="Returns all community base objects.",
	*     description="Returns all communities from the Fischer Homes CRM.",
	*     operationId="community_getAll",
	*	  tags={"Community"},
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
	*             @SWG\Items(ref="#/definitions/community")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/community"
	*         )
	*     )
	* )
*/

$community_filterByCode = function ($code = null, $log = null){
	if(empty($code)){
		echo "code is empty or null";
	}else{
		if( checkauth(apache_request_headers(), $log) == 1 ){
			$community = \crm\CommunityQuery::create()->filterByCode($code)->findone();
			if( !empty($community) ) {
				echo $community->toJson();
			}
		}
	}
};

/**
	* @SWG\Get(
	*     path="/community/filterByCode/{code}/",
	*     summary="Returns Community by code",
	*     description="Returns a single Community",
	*     operationId="community_filterByCode",
	*     tags={"Community"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         description="Community Code of community to return",
	*         in="path",
	*         name="code",
	*         required=true,
	*         type="string",
	*         format="string"
	*     ),
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
	*         description="successful operation",
	*         @SWG\Schema(ref="#/definitions/community")
	*     ),
	*     @SWG\Response(
	*         response="400",
	*         description="Invalid Code supplied"
	*     )
	* )
*/

$pervasiveSP_getBaseFeatures = function ($log = null) {
	/* WU_getBaseFeatures - Base Features */
	if( checkauth( apache_request_headers(), $log) == 1 ) {
		$conn = new PDO($GLOBALS['dn'], '', '');
		// $sql = 'CALL WU_getBaseFeatures WHERE "PlanMaster" = \'F741\'';
		$sql = 'CALL WU_getBaseFeatures';
		/*$stmt = $conn->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetchall();*/
		$row = fetch($conn,$sql);
		// echo "in here and printing row after this line <br />";
		// print_array($row);
		if(!empty($row)){
			echo json_encode($row);
		}
	}
};

/**
	* @SWG\Get(
	*     path="/community/getBaseFeatures/",
	*     summary="Returns Community Base Features",
	*     description="Returns a json of Community's base features.",
	*     operationId="pervasiveSP_getBaseFeatures",
	*     tags={"Community"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
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
	*         description="successful operation Json of base features",
	*         @SWG\Schema(ref="#/definitions/community")
	*     ),
	*     @SWG\Response(
	*         response="400",
	*         description="Invalid Code supplied"
	*     )
	* )
*/


$pervasiveSP_getPlanAvailability = function ($log = null) {
	/*WU_getPlanAvailability - Homes available in each community and their base price in that community*/
	if( checkauth( apache_request_headers(), $log) == 1 ) {
		$conn = new PDO($GLOBALS['dn'], '', '');
		$sql = 'CALL WU_getPlanAvailability';
		/*$stmt = $conn->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetchall();*/
		$row = fetch($conn,$sql);
		// echo "in here and printing row after this line <br />";
		// print_array($row);
		if(!empty($row)){
			echo json_encode($row);
		}
	}
};

/**
	* @SWG\Get(
	*     path="/community/getPlanAvailability/",
	*     summary="Returns Homes available in each community and their base price in that community",
	*     description="Returns a json of Homes available in each community and their base price in that community.",
	*     operationId="pervasiveSP_getPlanAvailability",
	*     tags={"Community"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
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
	*         description="successful operation Json of base features",
	*         @SWG\Schema(ref="#/definitions/community")
	*     ),
	*     @SWG\Response(
	*         response="200",
	*         description="No Data Returned"
	*     )
	* )
*/

$pervasiveSP_getMarketHomes = function ($log = null) {
	/*WU_getPlanAvailability - Homes available in each community and their base price in that community*/
	if( checkauth( apache_request_headers(), $log) == 1 ) {
		$conn = new PDO($GLOBALS['dn'], '', '');
		// $conn = new PDO('odbc:live-Pervasive', '', '');
		$sql = 'CALL WU_getMarketHomes';
		$row = fetch($conn, $sql);
		$tmp = utf8_converter($row);
		echo json_encode($tmp);
	}
};

/**
	* @SWG\Get(
	*     path="/community/getMarketHomes/",
	*     summary="Returns Market Homes",
	*     description="Returns market homes for sale.",
	*     operationId="pervasiveSP_getMarketHomes",
	*     tags={"Community"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
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
	*         description="successful operation Json of base features",
	*         @SWG\Schema(ref="#/definitions/community")
	*     ),
	*     @SWG\Response(
	*         response="200",
	*         description="No Data Returned"
	*     )
	* )
*/

$community_getSites_filterByCode = function ($code = null, $log = null){
	if(empty($code)){
		echo "code is empty or null";
	}else{
		if( checkauth(apache_request_headers(), $log) == 1 ){
			$conn = new PDO($GLOBALS['dn'], '', '');

			$sql = 'SELECT * FROM "OHCNA01"."CommunitySite" WHERE Community = :code ';
			$data = fetch($conn, $sql,  array('code' => $code ) );
			if(!empty($data)){
				echo json_encode($data);
			}
			$conn = null;
		}
	}
};

/**
	* @SWG\Get(
	*     path="/community/getSites/filterByCode/{code}/",
	*     summary="Returns Community sites by Community code",
	*     description="Returns Community sites by community code.",
	*     operationId="community_getSites_filterByCode",
	*     tags={"Community"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         description="Community Code of community to return",
	*         in="path",
	*         name="code",
	*         required=true,
	*         type="string",
	*         format="string"
	*     ),
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
	*         description="successful operation",
	*         @SWG\Schema(ref="#/definitions/sites")
	*     ),
	*     @SWG\Response(
	*         response="400",
	*         description="Invalid Code supplied"
	*     )
	* )
*/

$community_getSite_bySiteNum = function ($siteNum = null, $log = null){
	if(empty($siteNum)){
		echo "site number is null";
	}else{
		if( checkauth(apache_request_headers(), $log) == 1 ){
			$conn = new PDO($GLOBALS['dn'], '', '');

			$sql = 'SELECT * FROM "OHCNA01"."CommunitySite" WHERE Site_Number = :siteNum ';
			$data = fetch($conn, $sql,  array('siteNum' => $siteNum ) );
			if(!empty($data)){
				echo json_encode($data);
			}
			$conn = null;
		}
	}
};

/**
	* @SWG\Get(
	*     path="/community/getSite/filterBySiteNum/{siteNum}/",
	*     summary="Returns Community site by Site Number",
	*     description="Returns a single Community Site based on site number.",
	*     operationId="community_getSites_filterByCode",
	*     tags={"Community"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         description="Site Number to get that specific site info.",
	*         in="path",
	*         name="siteNum",
	*         required=true,
	*         type="string",
	*         format="string"
	*     ),
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
	*         description="successful operation",
	*         @SWG\Schema(ref="#/definitions/site")
	*     ),
	*     @SWG\Response(
	*         response="400",
	*         description="Invalid Code supplied"
	*     )
	* )
*/

$pervasiveSP_getActivePlans = function ($log = null) {
	/*get all active plans info*/
	if( checkauth( apache_request_headers(), $log) == 1 ) {
		$conn = new PDO($GLOBALS['dn'], '', '');
		$sql = file_get_contents('/var/www/controller/sql/getActivePlans.sql');
		$row = fetch($conn,$sql);
		if(!empty($row)){
			echo json_encode($row);
		}
	}
};

/**
	* @SWG\Get(
	*     path="/community/getActivePlans/",
	*     summary="Returns Active Plans",
	*     description="Returns a json of Active Plans.",
	*     operationId="pervasiveSP_getBaseFeatures",
	*     tags={"Community"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
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
	*         description="successful operation Json of base features",
	*         @SWG\Schema(ref="#/definitions/community")
	*     ),
	*     @SWG\Response(
	*         response="400",
	*         description="Invalid Code supplied"
	*     )
	* )
*/

$pervasiveSP_ActiveCommunity = function ($log = null) {
	/* this is for graphic language community (getActiveCommunities)*/
	if( checkauth( apache_request_headers(), $log) == 1 ) {
		$conn = new PDO($GLOBALS['dn'], '', '');
		$sql = 'SELECT Name,Community,City,County,State,RegionID FROM "FISCHER MANAGEMENT".CommunitySSware WHERE active = 1';
		$row = fetch($conn,$sql);
		if(!empty($row)){
			echo json_encode($row);
		}
		$conn = null;
	}
};

/**
	* @SWG\Get(
	*     path="/community/getActiveCommunities/",
	*     summary="Returns Active Communities for Graphic Languages",
	*     description="Returns a json of Active Plans.",
	*     operationId="pervasiveSP_ActiveCommunity",
	*     tags={"Community"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
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
	*         description="successful operation Json of active communities",
	*         @SWG\Schema(ref="#/definitions/community")
	*     ),
	*     @SWG\Response(
	*         response="400",
	*         description="Invalid Code supplied"
	*     )
	* )
*/
?>