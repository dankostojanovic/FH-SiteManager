<?php 
$sapphireGetVendor = function($vendorId = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($vendorId) ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		// $conn = new PDO('odbc:Pervasive');
		$vendors = array();

		if( !empty($vendorId) ){
			// $params['vendor_id'] = $_GET['vendor_id']; 
			$sql = 'SELECT "vendor_id", "Name", "tax_rate", "address_1", "city", "state", "zip", "mgmt_contact", "mgmt_contact_phone", "VendorType" FROM "FISCHER MANAGEMENT"."Vendor" WHERE "vendor_id" = :vendor_id ';
			$vendors = fetch($conn, $sql, array('vendor_id' => $vendorId ));
		}
		/*else {
			$sql = 'SELECT Top 100 "vendor_id", "Name", "tax_rate", "address_1", "city", "state", "zip", "mgmt_contact", "mgmt_contact_phone", "VendorType" FROM "FISCHER MANAGEMENT"."Vendor" Order By "VendorRecordId" DESC';
			$vendors = fetch($conn, $sql);
		}*/

		echo json_encode($vendors);
	}
};

$sapphirePostVendor = function($vendorId = null,$log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($vendorId)){
		$conn = new PDO($GLOBALS['dn'], '', '');
		if( !empty($_POST['rows']) ){
			$rows = $_POST['rows'];
			$rows['vendor_id'] = $vendorId;
			$affected = upsert($conn, '"Fischer Management"."Vendor"', "vendor_id", $rows);
			// echo $affected;
			if($affected == 1){
				http_response_code(200);
			}else{
				http_response_code(400);
			}
		}
	}else{
		http_response_code(403);
	}
};

$sapphireGetActivity = function($activity = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($activity)){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$records = array();

		$sql = 'SELECT activity, DisplayName, description, group_id FROM "FISCHER MANAGEMENT"."activity" WHERE "activity" = :activity ';
		$records = fetch($conn, $sql, array('activity' => $activity ));

		echo json_encode($records);
	}
};

$sapphirePostActivity = function ($activity = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($activity)){
		$conn = new PDO($GLOBALS['dn'], '', '');
		if( !empty($_POST['rows']) ){
			$rows = $_POST['rows'];
			$rows['activity'] = $activity;
			$affected = upsert($conn, '"Fischer Management"."activity"', "activity", $rows);
			if($affected == 1){
				http_response_code(200);
			}else{
				http_response_code(400);
			}
		}
	}else{
		http_response_code(403);
	}
};

$sapphireGetVendorType = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$vendorType = array();
		$sql = 'SELECT VendorType, Description FROM "FISCHER MANAGEMENT"."VendorType" Order By "VendorType" ASC';
		$vendorType = fetch($conn, $sql);
		echo json_encode($vendorType);
	}else{
		http_response_code(403);
	}
};

$sapphireGetGroups = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$vendorType = array();
		$sql = 'SELECT group_id, Description FROM "FISCHER MANAGEMENT"."groups" Order By "group_id" ASC';
		$vendorType = fetch($conn, $sql);
		echo json_encode($vendorType);
	}else{
		http_response_code(403);
	}
};
?>