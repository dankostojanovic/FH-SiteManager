<?php 

$ACL_GetACLFile = function($acl=null, $log = null){
	if( !empty($acl) && checkauth(apache_request_headers(), $log) == 1 ){
		$path = "/media/acl-pdfs/".$acl."_ACL.pdf";
		$pdf = file_get_contents($path, FILE_USE_INCLUDE_PATH);
		if(!empty($pdf)){
			// header('Content-Disposition: inline; filename="' . $filename . '"');
  			// header('Content-Transfer-Encoding: binary');
  			// header('Accept-Ranges: bytes');
			header('Content-type: application/pdf');
			echo $pdf;
		}else{
			header("HTTP/1.0 404 Not Found");
		}
	}
};

$ACL_MoveData2Test = function($acl=null, $log=null){
	if( !empty($acl) && checkauth(apache_request_headers(), $log) == 1 ){
		// echo $acl;
		$live = new PDO('odbc:live-Pervasive', '', '');
		$test = new PDO('odbc:Pervasive', '', '');
		$sql = 'SELECT * FROM "Fischer Management"."ACL" WHERE "ACL"."ACLSequence" = :ACLSequence ';

		$liveACL = fetch($live,$sql,$params=array('ACLSequence'=>$acl));
		// print_array($liveACL[0]);

		if(!empty($liveACL[0])){
			$table = "\"Fischer Management\".\"ACL\"";
			echo upsert($test, $table,'ACLSequence',$liveACL[0]);
		}

		$sql2 = 'SELECT * FROM "Fischer Management"."ACLOptions" WHERE "ACLOptions"."ACLSequence" = :ACLSequence ';
		$liveACLOptions = fetch($live,$sql2,$params=array('ACLSequence'=>$acl));
		// print_array($liveACLOptions);

		if(!empty($liveACLOptions)){
			foreach ($liveACLOptions as $key => $option) {
				$table2 = "\"Fischer Management\".\"ACLOptions\"";
				upsert($test, $table2,'ACLOption_ID',$option);
			}
		}
		
	}
};

$ACL_MoveFiles = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		// Use the us-west-1 region and latest version of each client.
		$sharedConfig = [
		    'version' => 'latest',
    		'region'  => 'us-west-1',
    		'credentials' => [
        		'key'    => 'AKIAIYZW3G2M3HTK4BZA',
        		'secret' => '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',
    		],
		];

		// Create an SDK class used to share configuration across clients.
		$sdk = new Aws\Sdk($sharedConfig);

		$s3 = $sdk->createS3();
		// echo time()." =" .date('Y-m-d H:i:s')." <br />";
		$currentTime = time() - 720;
		// echo $currentTime . " = ".date('Y-m-d H:i:s', $currentTime);
		$dir = "/media/acl-pdfs";

		$pdfs = array();
		foreach (scandir($dir) as $node) {
    		$nodePath = $dir . DIRECTORY_SEPARATOR . $node;
    		if (is_dir($nodePath)) continue;
    		// if( is_dir($nodePath) && filemtime($nodePath) >= $currentTime){
    		if( filemtime($nodePath) >= $currentTime ){
    			$pdfs[$nodePath][0] = filemtime($nodePath);
    			// $pdfs[$nodePath][1] = date("Y-m-d H:i:s", filemtime($nodePath));	
    			// echo $node."<br />";
    			$s3->putObject(array(
				    'Bucket'     => "cando-pdfs",
				    'Key'        => "acl-pdf/".$node,
				    'SourceFile' => $nodePath
				));
				$pdfs[$nodePath][1] = "moved!";
				// echo $nodePath ." has been update to s3 <br />";
				if(!empty($log)){
					$log->setDataAfter(json_encode($pdfs));
					$log->save();
				}
    		}
		}
		echo json_encode($pdfs);
	}
};

$ACL_TestUpdateFromCPA = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$sql = 'SELECT TOP 10 * FROM "FISCHER MANAGEMENT"."ACL" WHERE ACLLock = 1 and ControlCode != 10000000 and ControlCode != 20000000 order  by DateCreated DESC;';
		$acls = fetch($conn, $sql);
		// print_array($acls);
		foreach ($acls as $key => $acl) {
			echo $acl['SiteNumber']." ".$acl['ControlCode']."<br />";
			// curl -X POST -H "Authorization: 1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f" -H "Cache-Control: no-cache" -H "Postman-Token: cd824fe6-b4cc-94ba-bbff-6e8389aa1bb4" -H "Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW" -F "ControlCode=70011555" -F "SiteNumber=ARC010810000" -F "ControlCode=70021285" -F "SiteNumber=BTP011290000" -F "ControlCode=70025967" -F "SiteNumber=BLD010100000" -F "ControlCode=70032510" -F "SiteNumber=BG0310040305" -F "ControlCode=70025866" -F "SiteNumber=LMT010210000" "https://w0lf.ddns.net/api/jobInformation/updateFromCPA/";
		}
	}
};

$HOA_MoveFiles = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		// Use the us-west-1 region and latest version of each client.
		$sharedConfig = [
		    'version' => 'latest',
    		'region'  => 'us-west-1',
    		'credentials' => [
        		'key'    => 'AKIAIYZW3G2M3HTK4BZA',
        		'secret' => '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',
    		],
		];

		// Create an SDK class used to share configuration across clients.
		$sdk = new Aws\Sdk($sharedConfig);

		$s3 = $sdk->createS3();
		// echo time()." =" .date('Y-m-d H:i:s')." <br />";
		// $currentTime = time() - 720;
		// echo $currentTime . " = ".date('Y-m-d H:i:s', $currentTime);
		$dir = "/media/associationFees";

		$pdfs = array();
		foreach (scandir($dir) as $node) {
    		$nodePath = $dir . DIRECTORY_SEPARATOR . $node;
    		if (is_dir($nodePath)) continue;
    		// if( is_dir($nodePath) && filemtime($nodePath) >= $currentTime){
    		// if( filemtime($nodePath) >= $currentTime ){
    			$pdfs[$nodePath][0] = filemtime($nodePath);
    			// $pdfs[$nodePath][1] = date("Y-m-d H:i:s", filemtime($nodePath));	
    			// echo $node."<br />";
    			$s3->putObject(array(
				    'Bucket'     => "cando-pdfs",
				    'Key'        => "AssociationFees/".$node,
				    'SourceFile' => $nodePath
				));
				$pdfs[$nodePath][1] = "moved!";
				// echo $nodePath ." has been update to s3 <br />";
				if(!empty($log)){
					$log->setDataAfter(json_encode($pdfs));
					$log->save();
				}
    		// }
		}
		echo json_encode($pdfs);
	}
};

?>