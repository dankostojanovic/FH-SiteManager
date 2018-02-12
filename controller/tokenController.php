<?php 
// https://github.com/dannyvankooten/AltoRouter/issues/57  <<< helped me set this up with altorouter
$getAllTokens = function($log = null) {
    if( checkauth(apache_request_headers()) == 1 ){
		// echo "this is secure!";
		$tokens = \apidb\TokenQuery::create()->find();
		
		$data = array();
		foreach ($tokens as $key => $t) {
			// $data[$key][] = $t->getId();
			$data[$key][] = "<a href='javascript:getInfoToken(\"".$t->getId()."\")' class='btn btn-primary'>Update</a>";
			$data[$key][] = $t->getUserID();
			$data[$key][] = $t->getAccessName();
			$data[$key][] = $t->getName();
			$data[$key][] = $t->getShortDesc();
			$data[$key][] = $t->getCreatedDate('Y-m-d h:i:s A');
			$data[$key][] = $t->getLastUpdated('Y-m-d h:i:s A');
			$data[$key][] = $t->getExpiration('Y-m-d h:i:s A');
			$data[$key][] = $t->isValid();
		}
		$tmp['data'] = $data;
		echo json_encode($tmp);
		// had to do this because of datatables expectations
	}
};

$getAllAccess = function($args = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$access = \apidb\AccessQuery::create()->find();
		echo $access->toJson();
	}
};

$getTokenById = function($log = null) {
	if( checkauth(apache_request_headers(), $log) == 1 ){
		if(!empty($_REQUEST['Id'])){
			$token = new \apidb\Token($_REQUEST['Id']);
			// echo $token->toJson();
			$json = $token->toArray();
			unset($json['Token']);
			$json['ExpirationDate'] = $token->getExpiration('Y-m-d');
			$json['ExpirationTime'] = $token->getExpiration('h:i:s A');
			echo json_encode($json);
		}
	}
};

?>