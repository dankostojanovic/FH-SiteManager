<?php 
$job_information_upsert = function($log = null){

};

$jobInformation_getInfo = function($cc = null, $log = null){
	$data = array();
	if( !empty($cc) && checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$db = getDatabase($conn, $cc);
		if(!empty($db)){
			$sql = 'SELECT * FROM "'.$db.'"."Job_Information" Where Job_Number = :job_number';
			$data = fetch($conn, $sql,  array('job_number' => $cc ) );
			if(!empty($data[0])){
				echo json_encode($data[0]);
			}
		}
		$conn = null;
	}else{
		echo json_encode($data);
	}
};

/**
	* @SWG\Get(
	*     path="/jobInformation/getInfo/{cc}/",
	*     summary="Returns Job_Information.",
	*     description="Returns Job Information based off control code or Job Number",
	*     operationId="jobInformation_getInfo",
	*	  tags={"Job_Information"},
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         name="Authorization",
	*         in="header",
	*         description="pass the auth token in the header.",
	*         required=true,
	*         type="string",
	*         @SWG\Items(type="string"),
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Parameter(
	*         description="Control Code or Job Number",
	*         in="path",
	*         name="cc",
	*         required=true,
	*         type="string",
	*         format="string"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Tokens Response",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/Job_Information")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/Job_Information"
	*         )
	*     )
	* )
*/

$jobInformation_getAllDatabases = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		// return getDatabase($conn);
		echo json_encode( getAllDatabases($conn) );
	}
};

/**
	* @SWG\Get(
	*     path="/jobInformation/getdbs/",
	*     summary="Returns All Databases.",
	*     description="Returns All Databases.",
	*     operationId="jobInformation_getAllDatabases",
	*	  tags={"Job_Information"},
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         name="Authorization",
	*         in="header",
	*         description="pass the auth token in the header.",
	*         required=true,
	*         type="string",
	*         @SWG\Items(type="string"),
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Tokens Response",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/Job_Information")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/Job_Information"
	*         )
	*     )
	* )
*/

$jobInformation_getDatabase = function($cc = null, $log = null){
	$data = array();
	if( !empty($cc) && checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		// echo $GLOBALS['dn'];
		$db = getDatabase($conn, $cc);
		echo $db;
		$conn = null;
	}else{
		echo 0;
	}
};

/**
	* @SWG\Get(
	*     path="/jobInformation/getdb/{cc}/",
	*     summary="Returns database.",
	*     description="Returns Database based off control code or Job Number",
	*     operationId="jobInformation_getDatabase",
	*	  tags={"Job_Information"},
	*     produces={"string"},
	*     @SWG\Parameter(
	*         name="Authorization",
	*         in="header",
	*         description="pass the auth token in the header.",
	*         required=true,
	*         type="string",
	*         @SWG\Items(type="string"),
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Parameter(
	*         description="Control Code or Job Number",
	*         in="path",
	*         name="cc",
	*         required=true,
	*         type="string",
	*         format="string"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Gives Database location",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/Job_Information")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="0",
	*         @SWG\Schema(
	*             ref="#/definitions/Job_Information"
	*         )
	*     )
	* )
*/

$jobInformation_getPurchaser = function($cc = null, $log = null){
	$data = array();
	if( !empty($cc) && checkauth(apache_request_headers(), $log) == 1 ){
		$conn = new PDO($GLOBALS['dn'], '', '');
		$purchaser = getPurchaser($conn, $cc);
		echo $purchaser;
		$conn = null;
	}else{
		echo 0;
	}
};

/**
	* @SWG\Get(
	*     path="/jobInformation/getPurchaser/{cc}/",
	*     summary="Returns Purchaser.",
	*     description="Returns Purchaser based off control code or Job Number",
	*     operationId="jobInformation_getPurchaser",
	*	  tags={"Job_Information"},
	*     produces={"string"},
	*     @SWG\Parameter(
	*         name="Authorization",
	*         in="header",
	*         description="pass the auth token in the header.",
	*         required=true,
	*         type="string",
	*         @SWG\Items(type="string"),
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Parameter(
	*         description="Control Code or Job Number",
	*         in="path",
	*         name="cc",
	*         required=true,
	*         type="string",
	*         format="string"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Gives Database location",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/Job_Information")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="0",
	*         @SWG\Schema(
	*             ref="#/definitions/Job_Information"
	*         )
	*     )
	* )
*/

$jobInformation_updateFromCPA = function($log = null){
	$data = array(); $jobFound = 0; $newInfo = array();
	/*print_array($_REQUEST);
	print_array($log);*/
	if( !empty($_REQUEST['ControlCode']) && !empty($_REQUEST['SiteNumber']) && checkauth(apache_request_headers(), $log) == 1 ){
		$cc = $_REQUEST['ControlCode'];
		$sn = $_REQUEST['SiteNumber'];
		$ACLSequence = '';
		$conn = new PDO($GLOBALS['dn'], '', '');
		$db = getDatabase($conn, $sn);
		// echo $db."<br />";
		if(!empty($db)){  //database not empty
			$sql = 'SELECT * FROM "'.$db.'"."Job_Information" WHERE Job_Number = :job_number';
			// echo $sql."<br />";
			$data = fetch($conn, $sql,  array('job_number' => $sn ) );
			// echo "Job_Information";
			// print_array($data);
			// was the job_information record found? 
			// if(!empty($data[0])){
				// $jobFound = 1;
				// echo json_encode($data[0]);
				// look up agreement written -- if no agreement written  
				// John told me to to use this query instead of the no lock 2017-01-03.
				// Added select * again but kept acllock = 1 2017-11-11
				// $query = 'SELECT ACLSequence, ControlCode, SiteNumber, ACLLock from "'.$db.'"."ACL" where ACLLock = 1 and ControlCode = :ControlCode and SiteNumber = :SiteNumber ;';
				$query = 'SELECT * FROM "'.$db.'"."ACL" WHERE ACLLock = 1 and ControlCode = :ControlCode and SiteNumber = :SiteNumber ;';
				$acl = fetch($conn, $query, array('ControlCode' => $cc , 'SiteNumber' => $sn ) );
				// echo "ACL";
				// print_array($acl);
				if(!empty($acl[0])){
					// print_array($data[0]);
					// print_array($acl[0]);
					$ACLSequence = $acl[0]['ACLSequence'];
					// $acl[0]['ACLLock'] = 1;  // this was just for forcing an email alert.
					if($acl[0]['ACLLock'] != 1){
						
						$subject = "ACL is not locked for ControlCode:".$cc." SiteNumber:".$sn."";
						$body01 = "ACL is not locked for ControlCode:".$cc." SiteNumber:".$sn." ACLSequence:".$ACLSequence;
						insertEmailQueue($conn,'API',$subject,$body01);
						
					}else{
						// put data together and update job info 
						// print_array($acl[0]);
						// print_array($data[0]);
						$newInfo = gatherAllNewData($conn, $sn, $cc, $db, $acl[0]);
						// print_array($newInfo);
						if(!empty($log)){
							if( !empty($data[0]) ){
								$log->setDataBefore(json_encode($data[0]));
								// if it exist leave it alone else make it u
								!empty($data[0]['Home_Status']) ? $newInfo['Home_Status'] = $data[0]['Home_Status'] : $newInfo['Home_Status'] = "U";
								// if data exists leave it alone
								!empty($data[0]['CreatedBy']) ? $newInfo['CreatedBy'] = $data[0]['CreatedBy'] : false;
								!empty($data[0]['CreatedDate']) ? $newInfo['CreatedDate'] = $data[0]['CreatedDate'] : false;
							}
							$log->setDataAfter(json_encode($newInfo));
							$log->save();
						}
						/*$newInfo['Job_Number'] = $sn;
						$newInfo['Site_Number'] = $sn;
						$newInfo['JobNumberNew'] = substr_replace($sn, '/', 5, 0);
						$newInfo['JobNumberNew'] = substr_replace($newInfo['JobNumberNew'], '/', 9, 0);
						$newInfo['SiteNumberNew'] = substr_replace($sn, '/', 5, 0);
						$newInfo['SiteNumberNew'] = substr_replace($newInfo['SiteNumberNew'], '/', 9, 0);*/
						// print_array($newInfo);
						if(
							// $data[0]['Home_Status'] == 'M' && empty($data[0]['Start_Date_Act']) && $data[0]['Division'] != "17" || 
							// $data[0]['Home_Status'] == 'M' && empty($data[0]['Start_Date_Act']) && $data[0]['Division'] != "15" || 
							$data[0]['Home_Status'] == 'H' && $data[0]['Division'] == "17" ||
							$data[0]['Home_Status'] == 'A' && $data[0]['Division'] == "17" ||
							$data[0]['Home_Status'] == 'H' && $data[0]['Division'] == "15" ||
							$data[0]['Home_Status'] == 'A' && $data[0]['Division'] == "15" ||
							$data[0]['Home_Status'] == 'M'){

							$subject = "No Action ControlCode:".$cc." SiteNumber:".$sn."";
							$body01 = "No Action for ControlCode:".$cc." SiteNumber:".$sn." Home_Status:".$data[0]['Home_Status']." Start_Date_Act:".$data[0]['Start_Date_Act']." Division:".$data[0]['Division']." Start_Date_Act:".$data[0]['Start_Date_Act'];
							insertEmailQueue($conn,'API',$subject,$body01);
							
						}else{
							$table = "\"$db\".\"Job_Information\"";
							// echo $table."<br/>";
							$affected = upsert($conn, $table,"Job_Number",$newInfo);
							// $affected = 1;
							// echo "<br/>affected:".$affected."<br/>";
							if($affected == 1){
								echo $affected;
							}else{
								// send an error email que.
								// echo $affected;
								$subject = "Upsert Failed for ControlCode:".$cc." SiteNumber:".$sn."";
								$body01 = "Upsert Failed for ControlCode:".$cc." SiteNumber:".$sn;
								insertEmailQueue($conn,'API',$subject,$body01);
							}
						}
						
					}
				}else{
					// no ACL data found.
					// echo "No ACL found for Job_Number:".$sn." ControlCode:".$cc;
					$subject = "No ACL found for ControlCode:".$cc." SiteNumber:".$sn."";
					$body01 = "No ACL found for ControlCode:".$cc." SiteNumber:".$sn;
					insertEmailQueue($conn,'API',$subject,$body01);
				}
			// }else{
				// no job info record found try to get info together and insert in correct database.
				// echo "No Job Information found for job_number:".$sn;
				// getAllData(SiteNumber, ControlCode, $jobFound)
				/*$subject = "No Job Information found for job_number:".$sn;
				$body01 = "No Job Information found for job_number:".$sn;
				insertEmailQueue($conn,'API',$subject,$body01);*/
			// }
		}else{
			// alert email sent out not able to find db on this control code and site number
			// echo "No Database Found for SiteNumber:".$sn;
			$subject = "No Database Found for SiteNumber:".$sn;
			$body01 = "No Database Found for SiteNumber:".$sn;
			insertEmailQueue($conn,'API',$subject,$body01);
		}

		$conn = null;
	}else{
		// echo json_encode($data);
		echo 0;
		// echo "No information found";
		// header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	}
};

$jobInformation_CPARevert = function($id =null, $log = null){
	if( !empty($id) && checkauth(apache_request_headers(), $log) == 1 ){
		// echo "reverting data!";
		// 1796
		$request = \apidb\RequestQuery::create()->filterById($id)->findone();
		// print_array($request);
		$oldData = json_decode($request->getDataBefore());
		// print_array($oldData);
		foreach ($oldData as $key => $value) {
			$tmp[$key] = $value;
		}
		// print_array($tmp);
		$conn = new PDO($GLOBALS['dn'], '', '');

		/*
		$table = "\"$company\".\"AlertType\"";
		$field = 'AlertType'; \\primary
		*/
		$db = getDatabase($conn, $oldData->Site_Number);
		// echo $db ."<br />";
		if(!empty($db)){
			$table = "\"$db\".\"Job_Information\"";
			echo update($conn, $table, 'Site_Number' ,$tmp);
		}
	}
};


$jobInformation_test = function($id = null, $log = null){
	if( !empty($id) && checkauth(apache_request_headers(), $log) == 1 ){
		$lead = new \crm\Lead($id);
		// print_array($lead->toArray());
		$prospects = $lead->getProspects();
		// print_array($prospects->toArray());
		foreach ($prospects as $count => $prospect) {
			$fin = $prospect->getFinancings();
			// print_array($fin);
			foreach ($fin as $key => $f) {
				// echo "Financing <br />";
				// print_array($f->toArray());
				// print_array($f->getLoanofficer());
				$loanOfficer = $f->getLoanofficer();
				$LOInfo = $loanOfficer->getPerson();
				// print_array($LOInfo->toArray());
				echo $LOInfo->toJson();
			}
		}
	}
};

$jobInformation_test2 = function($id = null, $log = null){
	if( !empty($id) && checkauth(apache_request_headers(), $log) == 1 ){
		$lead = new \crm\Lead($id);
		echo $lead->getLoanOfficers()->toJson();
	}
};

$jobInformation_testLender = function($id = null, $log = null){
	if( !empty($id) && checkauth(apache_request_headers(), $log) == 1 ){
		$lead = new \crm\Lead($id);
		if(!empty($lead->getControlcode())){
			$prospects = $lead->getProspects();
			foreach ($prospects as $count => $prospect) {
				$fin = $prospect->getFinancings();
				foreach ($fin as $key => $f) {
					$loanOfficer = $f->getLoanofficer();
					// print_array($loanOfficer);
					// $loanOfficer->getLenders();
					if(!empty($loanOfficer)){
						// print_array($loanOfficer->getLender());
						$lender = $loanOfficer->getLender();
						$org = $lender->getOrganization();
						// print_array($org->toArray());
						echo $org->toJson();
					}
				}
			}
		}
	}
};

$jobInformation_testLender2 = function($id = null, $log = null){
	if( !empty($id) && checkauth(apache_request_headers(), $log) == 1 ){
		$lead = new \crm\Lead($id);
		if(!empty($lead->getControlcode())){
			echo $lead->getLenderInfo()->toJson();
		}
	}
};

$jobInformation_testRealtor = function($id = null, $log = null){
	if( !empty($id) && checkauth(apache_request_headers(), $log) == 1 ){
		$lead = new \crm\Lead($id);
		if(!empty($lead->getControlcode())){
			// echo $lead->getRealtor()->toJson();
			/*$realtor = $lead->getRealtor();
			$broker = $realtor->getBroker();
			print_array($realtor->toArray());
			print_array($broker->toArray());
			$org = $broker->getOrganization();
			print_array($org->toArray());*/
			print_array($lead->toArray());
			// $orgInfo = $lead->getBroker();
			// $orgInfo = $lead->getRealtor()->getBroker()->getOrganization();
			// $orgInfo = $lead->getRealtor()->getBroker();
			$orgInfo = $lead->getRealtor();			
			print_array($orgInfo->toArray());
			$person = new \crm\Person($orgInfo->getIdperson());
			print_array($person->toArray());
			
			$broker = new \crm\Broker($orgInfo->getIdbroker());
			print_array($broker->toArray());
		}
	}
};

$jobInformation_CRMData = function($ControlCode = null, $log = null){
	if(!empty($ControlCode) && checkauth(apache_request_headers(), $log) == 1 ){
		
	}
};


$jobInformation_getdiff = function($log = null){
	// 6092, 6093, 6096
	$request = \apidb\RequestQuery::create()->filterById(6107)->findone();
	// print_array($request->toArray());

	$before = json_decode($request->getDataBefore(), true);
	// print_array($before);
	$after = json_decode($request->getDataAfter(), true);
	// print_array($after);
	// $compare = array_diff($before, $after);
	// print_array($compare);
	echo "Column, Before, After<br/>";
	$diff = array();
	foreach ($after as $key => $value) {
		$diff[$key]['after'] = $value;
		$diff[$key]['before'] = $before[$key];
		echo $key.",".$before[$key].",".$value."<br/>";
	}
	// print_array($diff);
}
?>