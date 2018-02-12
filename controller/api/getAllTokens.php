<?php 

if( checkauth(apache_request_headers()) == 1 ){
	// echo "this is secure!";
	$tokens = \apidb\TokenQuery::create()->find();
	
	$data = array();
	foreach ($tokens as $key => $t) {
		$data[$key][] = $t->getId();
		$data[$key][] = $t->getUserID();
		$data[$key][] = $t->getAccessId();
		$data[$key][] = $t->getName();
		$data[$key][] = $t->getShortDesc();
		$data[$key][] = $t->getCreatedDate('Y-m-d H:i:s');
		$data[$key][] = $t->getLastUpdated('Y-m-d H:i:s');
		$data[$key][] = $t->getExpiration('Y-m-d H:i:s');
		$data[$key][] = $t->isValid();
	}
	$tmp['data'] = $data;
	echo json_encode($tmp);
	// had to do this because of datatables expectations
}else{
	// echo "no auth no data!";
}

?>