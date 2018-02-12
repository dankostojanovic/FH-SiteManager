<?php 
if(!empty($id)){
	$user = new \logs\Users($id);
	echo $user->findCRMUserId();
}else{
	echo "0";
}

?>