<?php 
use Propel\Runtime\Propel;
/*correct way it should be done.*/
$testGetCommunities = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$page = 1;
		$limit = 10;
		if(!empty($_GET['page']) && !empty($_GET['rows'])){
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
		}

		$communities = \test\CommunityQuery::create()->paginate($page, $maxPerPage = $limit);
		// $communities = \test\CommunityQuery::create()->find();
		$responce = new stdClass();
		$responce->page = $communities->getPage();
		$responce->total = $communities->getLastPage(); // total number of pages
		// $responce->records = $alerts->count();
		$responce->records = $communities->getNbResults();
		// print_array($communities->toArray());
		// $responce = 
		foreach ($communities as $key => $com) {
			$tmp = $com->toArray();
			// $tmp['LastModified'] = $com->getLastModified('Y-m-d H:i:s'); 
			$responce->rows[$key]['id'] = $com->getCommunity();
			$responce->rows[$key]['cell'] = $tmp;
		}
		echo json_encode($responce);
		// print_array($communities);
	}
};

/*Rigged up version that Cando wants*/
$testGetCommunities2 = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$page = 1;
		$limit = 10;
		if(!empty($_GET['page']) && !empty($_GET['rows'])){
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
		}

		$communities = \test\CommunityQuery::create()->paginate($page, $maxPerPage = $limit);
		// $communities = \test\CommunityQuery::create()->find();
		$responce = new stdClass();
		$responce->page = $communities->getPage();
		$responce->total = $communities->getLastPage(); // total number of pages
		// $responce->records = $alerts->count();
		$responce->records = $communities->getNbResults();
		// print_array($communities->toArray());
		foreach ($communities as $key => $com) {
			$tmp = $com->toArray();
			// unset($tmp['Community_Name']);
			// $tmp['CommunityName'] = $com->getCommunity_Name();
			// unset($tmp['Tax_Rate']);
			// $tmp['TaxRate'] = $com->getTax_Rate();
			// $tmp['DateRecordModified'] = $com->getDateRecordModified('Y-m-d');
			// $tmp['TimeRecordModified'] = $com->getTimeRecordModified('H:i:s');
			// unset($tmp['CommunityFiller']);
			$responce->rows[$key] = $tmp;
		}
		echo json_encode($responce);
	}
};

$testGetCommunities3 = function($log = null){
	$communities = \test\CommunityQuery::create()->find();
	echo $communities->toJson();
};

$testGetSites = function($code = null, $log = null){
	// echo "here!";
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($code)){
		$page = 1;
		$limit = 10;
		if(!empty($_GET['page']) && !empty($_GET['rows'])){
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
		}

		$sites = \test\CommunitySiteQuery::Create()->filterByCommunity($code)->paginate($page, $limit);
		$responce = new stdClass();
		$responce->page = $sites->getPage();
		$responce->total = $sites->getLastPage(); // total number of pages
		$responce->records = $sites->getNbResults();

		foreach ($sites as $key => $s) {
			$tmp = $s->toArray();
			$responce->rows[$key]['id'] = $s->getSiteNumber();
			$responce->rows[$key]['cell'] = $tmp;
		}
		echo json_encode($responce);
	}
};

$testSaveSite = function ($sn = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($sn)){
		$site = \test\CommunitySiteQuery::Create()->filterBySiteNumber($sn)->findOne();
		// print_array($site->toArray());
		// parse_str( file_get_contents("php://input"), $post_vars);
		// print_array($post_vars);
		// var_dump($post_vars);
		if(!empty($site) && !empty($_REQUEST['site'])){
			$tmp = json_decode($_REQUEST['site'], true);
			// print_array($tmp);
			// unset($tmp['Commsiterecordid']);
			$site->fromArray($tmp);
			// $site->fromObject($tmp); // this doesn't exist.
			$site->save();
		}else{
			// header("HTTP/1.0 404 Not Found");
			header("HTTP/1.0 409 Conflict");
		}
		
	}else{
		header("HTTP/1.0 404 Not Found");
	}
};

$testGetPurchasers = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = Propel::getConnection('test');
		$sql = 'SELECT DISTINCT purchaser FROM community_site ORDER BY purchaser ASC';
		$data = fetch($conn, $sql);
		// print_array($data);
		unset($data[0]); // removing null
		echo json_encode($data);
	}else{
		header("HTTP/1.0 404 Not Found");
	}
};

$testGetLegelSections = function ($code = null,$log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($code)){
		$lega = \test\CommunitysectionlegaQuery::Create()->filterByCommunity($code)->find();
		echo json_encode($lega->toArray());
	}else{
		header("HTTP/1.0 404 Not Found");
	}
};

$testGetFischerSections = function ($code = null,$log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($code)){
		$section = \test\CommunitysectionQuery::Create()->filterByCommunity($code)->find();
		echo json_encode($section->toArray());
	}else{
		header("HTTP/1.0 404 Not Found");
	}
};

$testSaveSection = function ($id = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($id)){
		$sec = \test\CommunitysectionQuery::Create()->filterByComsectionrecordid($id)->findOne();
		
		if(!empty($sec) && !empty($_REQUEST['section'])){
			$tmp = json_decode($_REQUEST['section'], true);
			$sec->fromArray($tmp);
			$sec->save();
		}else{
			// header("HTTP/1.0 404 Not Found");
			header("HTTP/1.0 409 Conflict");
		}
		
	}else{
		header("HTTP/1.0 404 Not Found");
	}
};

$testSaveCommunity = function ($code = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($code)){
		$comm = \test\CommunityQuery::Create()->filterByCommunity($code)->findOne();
		
		if(!empty($comm) && !empty($_REQUEST['community'])){
			$tmp = json_decode($_REQUEST['community'], true);
			$comm->fromArray($tmp);
			$comm->save();
		}else{
			// header("HTTP/1.0 404 Not Found");
			header("HTTP/1.0 409 Conflict");
		}
		
	}else{
		header("HTTP/1.0 404 Not Found");
	}
};
?>