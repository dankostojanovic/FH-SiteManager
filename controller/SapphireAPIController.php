<?php 

$sapphireAPIListCommunities = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://fischer.kovasolutions.com/KovaFischerTestingWebConfigurator/api/v3/Community/ExportList?auth=425240ac28d8656bc8e1eb9f2a35f149",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"postman-token: 61c1861a-f213-0e73-5f2a-4fe5ca726494"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  	// echo $response;
			// had to add this for dataTables data format.
			// $info['data'] = json_decode($response, 1);
			$info = json_decode($response, 1);
			$data['data'] = array();
			foreach ($info as $key => $v) {
				// print_array($v);
				$data['data'][] = $v;
			}
			echo json_encode($data);
		}
	}else{
		http_response_code(403);
	}
};

$sapphireAPIGetCommunityByCode = function($code = null, $log = null){

	if( checkauth(apache_request_headers(), $log) == 1 && !empty($code) ){

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://fischer.kovasolutions.com/KovaFischerTestingWebConfigurator/api/v4/Community/ExportList?auth=425240ac28d8656bc8e1eb9f2a35f149&%24filter=CommunityID%20eq%20'$code'",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"postman-token: 21111225-aaff-4afb-d3e7-3af809df1000"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}

	}

};

$sapphireAPIListBUnits = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://fischer.kovasolutions.com/KovaFischerTestingWebConfigurator/api/v4/BUnit/ExportList?auth=425240ac28d8656bc8e1eb9f2a35f149",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"postman-token: 61c1861a-f213-0e73-5f2a-4fe5ca726494"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  	// echo $response;
			// had to add this for dataTables data format.
			// $info['data'] = json_decode($response, 1);
			$info = json_decode($response, 1);
			$data['data'] = array();
			foreach ($info as $key => $v) {
				// print_array($v);
				$data['data'][] = $v;
			}
			echo json_encode($data);
		}
	}else{
		http_response_code(403);
	}
};

/* Clearly only works on saphire right now. */
$sapphireAPIGetDynamic = function($version = null, $modalName = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($version)  && !empty($modalName)){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://fischer.kovasolutions.com/KovaFischerTestingWebConfigurator/api/$version/$modalName/ExportList?auth=425240ac28d8656bc8e1eb9f2a35f149",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  	// echo $response;
			// had to add this for dataTables data format.
			// $info['data'] = json_decode($response, 1);
			$info = json_decode($response, 1);
			$data['data'] = array();
			
			if(!empty($info[0])){
				$data['map'] = array2column($info[0]);
			}

			foreach ($info as $key => $v) {
				// print_array($v);
				$data['data'][] = $v;
			}
			echo json_encode($data);
		}
	}else{
		http_response_code(403);
	}
};

$sapphireGetEndpointsList = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1){
		if(!empty($_GET['sourceId'])){
			$apis = \apidb\SapphireApiQuery::Create()->filterBySourceId($_GET['sourceId'])->filterByExportList(1)->OrderBy('ModalName','ASC')->find();
		}else{
			$apis = \apidb\SapphireApiQuery::Create()->filterByExportList(1)->OrderBy('ModalName','ASC')->find();
		}
		echo $apis->toJson();
	}
};

$sapphireGetEndpointById = function($id = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($id) ){
		// $apis = \apidb\SapphireApiQuery::Create()->filterBySourceId($id)->filterByExportList(1)->OrderBy('ModalName','ASC')->find();
		$apis = \apidb\SapphireApiQuery::Create()->findPK($id);
		echo $apis->toJson();
	}
};

$sapphireAPIGetDynamic2 = function($id = null , $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($id) ){

		$api = new \apidb\SapphireApi($id);
		$version = $api->getVersion();
		$modalName = $api->getModalName();
		$sourceId = $api->getSourceId();

		$curl = curl_init();

		/* this will probably be an object $api->getData(); */
		/* actually all of these things will probably end up living on this object but it need to be renamed */
		if($sourceId == 1){
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://apps.fischermgmt.com/API/$modalName/",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"authorization: 91c547f8b9334f5d0ad799c4cfb06f8f",
					"cache-control: no-cache",
				),
			));
		}else if($sourceId == 2){
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://fischer.kovasolutions.com/KovaFischerTestingWebConfigurator/api/$version/$modalName/ExportList?auth=425240ac28d8656bc8e1eb9f2a35f149",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
				),
			));
		}
		

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			// had to do this for Data Tables.  Note: this is really a middle layer
			$info = json_decode($response, 1);
			if(!empty($info[0])){
				$data['dataTablesDef'] = array2column($info[0]);
			}

			$data['sourceInfo'] = $api->toArray();

			$data['data'] = $info;
			echo json_encode($data);
		}
	}else{
		http_response_code(403);
	}
};

$genericPOST = function($version = null, $modalName = null, $log = null){
	if( checkauth(apache_request_headers(), $log) == 1 && !empty($version)  && !empty($modalName)){
		if(!empty($log)){
			$array = array('version' => $version , 'modalName' => $modalName );
			$log->setDataBefore(json_encode($array) );
			$log->save();
			$log->logSapphireData(json_encode($_POST));
		}
	}
};

$genericPOSTById = function($id = null, $log = null){
	if(checkauth(apache_request_headers(), $log) == 1 && !empty($id)){
		if(!empty($log)){
			$array = array('id' => $id );
			$log->setDataBefore(json_encode($array));
			$log->save();
			$log->logSapphireData(json_encode($_POST));
		}
	}
};