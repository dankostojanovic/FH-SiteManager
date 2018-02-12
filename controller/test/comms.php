<?php 
if( checkauth(apache_request_headers()) == 1 ){
	// echo "token is good!";
	$communities = \crm\CommunityQuery::Create()->find();
	// $json = $communities->toArray();
	// echo json_encode($json);
	echo $communities->toJson();
}

?>