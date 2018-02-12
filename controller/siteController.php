<?php 
$sites_getAllSites = function($log = null){
	$data = array();
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$sql = 'SELECT * FROM "OHCND01"."CommunitySite"';
		$data = fetch($conn, $sql);
		if(!empty($data)){
			echo json_encode($data);
		}
		$conn = null;
	}else{
		echo json_encode($data);
	}
};



$sites_getSitesBySection = function($section = null, $log = null){
	$data = array();
	if( !empty($section) && checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$sql = 'SELECT * FROM "OHCND01"."CommunitySite" WHERE SectionID = :section';
		$data = fetch($conn, $sql, array('section' => $section ));
		if(!empty($data)){
			echo json_encode($data);
		}
		$conn = null;
	}else{
		echo json_encode($data);
	}
};

$site_getDistinctSection = function($code = null, $log = null){
	$data = array();
	if( !empty($code) && checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$sql = 'SELECT Distinct "SectionID" FROM "OHCND01"."CommunitySite" Where "Community" = :code';
		$data = fetch($conn, $sql, array('code' => $code ) );
		if(!empty($data)){
			echo json_encode($data);
		}
		$conn = null;
	}else{
		echo json_encode($data);
	}
};

$site_getAllDistSection = function($log = null){
	$data = array();
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$sql = 'SELECT Distinct "SectionID" FROM "OHCND01"."CommunitySite"';
		$data = fetch($conn, $sql, array() );
		if(!empty($data)){
			echo json_encode($data);
		}
		$conn = null;
	}else{
		echo json_encode($data);
	}
};

?>