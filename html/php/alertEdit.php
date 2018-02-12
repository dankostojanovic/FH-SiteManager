<?php 
include_once '../init.php';
checkLogin();
$alert = null;
if(!empty($_REQUEST['id'])){
	
	// print_array($alert);
	if(!empty($_REQUEST['oper']) && $_REQUEST['oper'] == 'edit'){
		// echo "edit!";
		$alert = \crm\AlerttypeQuery::create()->findpk($_REQUEST['id']);
		if(!empty($alert)){
			$alert->setDescription($_REQUEST['Description']);
			$alert->setSubject($_REQUEST['Subject']);
			$alert->setMessage($_REQUEST['Message']);
			$alert->setTemplateCode($_REQUEST['TemplateCode']);
			$alert->setLastModified(date('Y-m-d H:i:s'));
			$alert->save();
		}
	}else if($_REQUEST['oper'] == 'add'){
		// print_array($_REQUEST);
		$alert = new \crm\Alerttype;
		$alert->setAlertType($_REQUEST['AlertType']);
		$alert->setDescription($_REQUEST['Description']);
		$alert->setSubject($_REQUEST['Subject']);
		$alert->setMessage($_REQUEST['Message']);
		$alert->setTemplateCode($_REQUEST['TemplateCode']);
		$alert->setLastModified(date('Y-m-d H:i:s'));
		$alert->save();

	}else if($_REQUEST['oper'] == 'del'){
		// print_array($_REQUEST);
		$alert = \crm\AlerttypeQuery::create()->findpk($_REQUEST['id']);
		if(!empty($alert)){
			$alert->delete();
		}
	}
}



?>