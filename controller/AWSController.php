<?php 
$AWS_Testing = function ($log = null){
	// print_array(getenv(1));
	// AKIAIYZW3G2M3HTK4BZA:eOorqae6sCdO35zrRmVofuIUWMvFXi3iS4u8vIIA
	if(  checkauth(apache_request_headers(), $log) == 1 ){
		// Use the us-west-2 region and latest version of each client.
		$sharedConfig = [
		    'version' => 'latest',
    		'region'  => 'us-east-1',
    		'credentials' => [
        		'key'    => 'AKIAIYZW3G2M3HTK4BZA',
        		'secret' => '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',
    		],
		];

		// Create an SDK class used to share configuration across clients.
		$sdk = new Aws\Sdk($sharedConfig);

		$s3 = $sdk->createS3();
		$result = $s3->listBuckets();

		// $buckets = $result->toArray();

		/*foreach ($result['Buckets'] as $bucket) {
		    echo $bucket['Name'] . "<br />";
		}*/
		// echo json_encode($result['Buckets']);
		// echo json_encode($result);
		// print_array($result);
	}
};


$AWS_GetACLFile = function($acl=null, $log = null){
	if( !empty($acl) && checkauth(apache_request_headers(), $log) == 1 ){
		// $sdk = new Aws\Sdk($sharedConfig);
		// $s3 = $sdk->createS3();
		// 90126   90126_ACL.pdf
		// echo $acl;
		// $path = "/media/acl-pdfs/".$acl."_ACL.pdf";
		// $pdf = file_get_contents($path, FILE_USE_INCLUDE_PATH);
		// echo $pdf;
		// $dir = scandir('/media/acl-pdfs/');
		// print_array($dir);
		// print_array($pdf);
		// echo json_encode($dir);
		// header('Content-type: application/pdf');
  		// header('Content-Disposition: inline; filename="' . $filename . '"');
  		// header('Content-Transfer-Encoding: binary');
  		// header('Accept-Ranges: bytes');
  		// echo $pdf;
	}
};

?>