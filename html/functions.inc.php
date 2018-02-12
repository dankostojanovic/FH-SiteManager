<?php

function print_array($object){
	echo '<pre style="text-align: left;">'; print_r($object); echo '</pre>';
};

function random_password( $length = 8 ) {
    // $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
};

function checkLogin(){
	if(empty($_SESSION['userId'])){
		// header( 'Location: http://www.visioneforms.com/');
		// header( 'Location: https://w0lf.ddns.net/login.php');
		//header('Location: http://'.$_SERVER['HTTP_HOST'].'/login.php');
		$_SESSION['userId'] = 2;
	}
};

function sendEmail($email = null, $message = null, $html = null)
{
	if(!empty($email) && !empty($message) && !empty($html))
	{
		// require '../PHPMailerAutoload.php';
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host = "smtp-relay.sendinblue.com";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 587;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = "ty@tysoft.me";
		//Password to use for SMTP authentication
		$mail->Password = "m4rCTbf723LsMRQh";
		//Set who the message is to be sent from
		$mail->setFrom('thudson@fischerhomes.com', 'Ty Hudson (Automated)');
		//Set an alternative reply-to address
		$mail->addReplyTo('thudson@fischerhomes.com', 'Ty Hudson');
		//Set who the message is to be sent to
		$mail->addAddress($email, 'Automated Email - FHDev.tech');
		//Set the subject line
		$mail->Subject = $message;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		// $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
		// $mail->msgHTML(file_get_contents('/var/www/html/ajax/email/email-list.html'), dirname(__FILE__));
		$mail->msgHTML($html);
		//Replace the plain text body with one created manually
		$mail->AltBody = $message;
		//Attach an image file
		// $mail->addAttachment('images/phpmailer_mini.png');
		//send the message, check for errors
		if (!$mail->send()) {
		    echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		    echo "Message sent!<br />";
		}
	}
};

function html_table($data = array())
{
    $rows = array();
    foreach ($data as $row) {
        $cells = array();
        foreach ($row as $cell) {
            $cells[] = "<td>{$cell}</td>";
        }
        $rows[] = "<tr>" . implode('', $cells) . "</tr>";
    }
    return "<table class='hci-table'>" . implode('', $rows) . "</table>";
}

function checkauth($headers = null, $log = null){
    return 1;
	// note User-Agent = browser being used by request.
	if(!empty($headers['Authorization'])){
		// look auth up, is it valid?
		$token = new \apidb\Token();
		$token->fillByToken($headers['Authorization']);
		// print_array($token);
		if($token->isValid() == 1){
			if(!empty($log) ){
				$log->setTokenId($token->getId());
				$log->setAccepted(1);
				$log->save();
			}
			return 1;
		}else{
			return 0;
		}
		// return $token->isValid();
	}else{
		return 0;
	}
};

/*function checkAuthLogging($headers = null){
	if(!empty($headers['auth'])){
 		// look auth up, is it valid?
 		$token = new \apidb\Token();
 		$token->fillByToken($headers['auth']);
 		// return $token->isValid();
 		if($token->isValid() == 1){
 			$log = new \apidb\Request();
 			$log->setTokenId($token->getId());
 			$log->setHeader(json_encode($headers));
 			$log->setStartDatetime(date('Y-m-d H:i:s'));
 			$log->setStopDatetime(date('Y-m-d H:i:s'));
 			$log->setAccepted(1);
 			$log->setRoute();
			$log->setRequestIp(getUserIP());
 		}else{
 			return 0;
 		}
 	}else{
 		return 0;
 	}
 }*/

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
};

function getDatabase($conn = null, $job_number = null){
	if(!empty($conn) && !empty($job_number)){
		$sql = 'SELECT Acl.SiteNumber, S1.Purchaser, D1."DataSourceName" FROM "OHCND01"."ACL" Acl inner join  "OHCND01"."Communitysite" S1 on S1.Site_Number = Acl.SiteNumber inner join "OHCND01"."DivisionList" D1 on D1."Division" = Acl.Division and D1."CompanyShortName" = S1."Purchaser"  Where Acl.SiteNumber = \''.$job_number.'\';';
		/*$stmt = $conn->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetchall();*/
		$stmt = $conn->prepare($sql);
		if (!$stmt) {
    		// echo "\nPDO::errorInfo():\n";
    		print_r($conn->errorInfo());
		}else{
			$stmt->execute();
			$row = $stmt->fetchall();
		}
		// print_array($row);
 		// return $row;
 		/*if( !empty($row[0]['Purchaser']) && $GLOBALS['dbid'] == 2){
 			return $row[0]['Purchaser'];
 		}else if(!empty($row[0]['DataSourceName']) && $GLOBALS['dbid'] == 1){
 			return $row[0]['DataSourceName'];
 		}else{
 			return null;
 		}*/
		if(!empty($row[0]['DataSourceName'])){
			return $row[0]['DataSourceName'];
		}else{
			return null;
		}

	}else{
		return null;
	}
};

function getPurchaser($conn = null, $job_number = null){
	if(!empty($conn) && !empty($job_number)){
		$sql = 'SELECT Acl.SiteNumber, S1.Purchaser, D1."DataSourceName" FROM "OHCND01"."ACL" Acl inner join  "OHCND01"."Communitysite" S1 on S1.Site_Number = Acl.SiteNumber inner join "OHCND01"."DivisionList" D1 on D1."Division" = Acl.Division and D1."CompanyShortName" = S1."Purchaser"  Where Acl.SiteNumber = \''.$job_number.'\' and "Acl"."AclLock" = 1;';
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetchall();
		// print_array($row);
		// return $row;
		if( !empty($row[0]['Purchaser']) ){
			return $row[0]['Purchaser'];
		}
		/*if(!empty($row[0]['DataSourceName'])){
 			return $row[0]['DataSourceName'];
 		}*/
	}else{
		return null;
	}
};

function getAllDatabases($conn = null){
	$query = "
		SELECT Datasource FROM
		INFOSYS.Datasources
		WHERE DatasourceType='O';
	";
	if(!empty($conn)){
		$rows = fetch($conn, $query);
		foreach($rows as $row){
			$c = $row['Datasource'];
			if($c){
				$companies[] = $c;
			}
		}
		return $companies;
	}

};

function fetch($conn = null,$query,$params=array()){
	//return array of rows
	$rows = array();
	if(!empty($conn)){
		try {
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare($query);
			$stmt->execute($params);
			if(!$stmt){
				print_array($conn->errorInfo());
			}
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $ex) {
			return 'Failed to fetch: '.$ex->getMessage();
			// return 0;
			// return json_encode(array());
		}
	}
	return $rows;
};

function checkACLLock($conn = null, $ControlCode = null, $SiteNumber = null, $db = null){
	// probably don't even need to do this
};

function gatherAllNewData($conn = null, $job_number = null, $control_code = null, $db = null, $acl_info = null){
	if( !empty($conn) && !empty($job_number) && !empty($control_code) ){
		$job_info = array();
		$lead = \crm\LeadQuery::create()->filterByControlcode($control_code)->findone();
		// echo "Lead</br>";
		// print_array($lead->toArray());
		// print_array($lead);
		$job_info['Job_Number'] = $job_number;
		$job_info['Site_Number'] = $job_number;
		$job_info['JobNumberNew'] = substr_replace($job_number, '/', 5, 0);
		$job_info['JobNumberNew'] = substr_replace($job_info['JobNumberNew'], '/', 9, 0);
		$job_info['SiteNumberNew'] = substr_replace($job_number, '/', 5, 0);
		$job_info['SiteNumberNew'] = substr_replace($job_info['SiteNumberNew'], '/', 9, 0);
		$job_info['Control_Code'] = $control_code;
		$job_info['LastModified'] = date('Y-m-d',strtotime('now'));

		if( !empty( $lead->getId() ) ){
			!empty($lead->getWhencreated('Y-m-d')) ? $job_info['CreatedDate'] = $lead->getWhencreated('Y-m-d') : false;
			// !empty($lead->getWhenupdated('Y-m-d')) ? $job_info['LastModified'] = $lead->getWhenupdated('Y-m-d') : false; // make this today
			!empty($lead->getCreatorUsername()) ? $job_info['CreatedBy'] = $lead->getCreatorUsername() : false;
			!empty($lead->getStaffEmployeeNumber()) ? $job_info['Sales_Counselor'] = $lead->getStaffEmployeeNumber() : false;
			// print_array($lead->getCreatorUserInfo());
			// echo "getting employeeNumber after this<br /> ".$lead->getStaffEmployeeNumber();
			// print_array($lead->getStaffEmployeeNumber());

			$prospect = \crm\ProspectQuery::create()->filterByIdlead($lead->getId())->findone();
			// echo "Prospect</br>";
			// print_array($prospect->toArray());
			// $customer = \crm\CustomerQuery::create()->filterByIdlead($lead->getId())->findone();  // had to get all the customers info
			// print_array($customer->toArray());
			$customer = $lead->getCustomers();
			// echo "Customer</br>";
			// have to loop through customers to get person info
			foreach ($customer as $key => $c) {
				// print_array($c);
				// echo "Person <br/>";
				$person = new \crm\Person($c->getIdperson());
				// print_array($person->toArray());
				// if key = 0 = primary; if key = 1 $secondary
				if($key == 0){
					!empty($person->getNamefirst()) ? $job_info['His_Name'] = strtoupper($person->getNamefirst()): false;
					!empty($person->getNamelast()) ? $job_info['HisLastName'] = strtoupper($person->getNamelast()): false;
					!empty($person->getPhone()) ? $job_info['His_Work_Phone'] = $person->getPhone(): false;
					!empty($person->getPhoneext()) ? $job_info['HisWorkExtension'] = $person->getPhoneext(): false;
					!empty($person->getPhonemobile()) ? $job_info['HisCellPhone'] = $person->getPhonemobile(): false;
					!empty($person->getStreetaddress()) ? $job_info['CustomerCurrentAddre'] = strtoupper($person->getStreetaddress()): false;
					!empty($person->getCity()) ? $job_info['CustomerCurrentCity'] = $person->getCity(): false;
					// !empty($person->getIdstate()) ? $job_info['CustomerCurrentState'] = $person->getIdstate(): false;
					!empty($person->getStateLetters()) ? $job_info['CustomerCurrentState'] = $person->getStateLetters(): false;
					!empty($person->getPostalcode()) ? $job_info['CustomerCurrentZip'] = $person->getPostalcode(): false;
					!empty($person->getEmail()) ? $job_info['PrimaryEmailAddress'] = strtoupper($person->getEmail()): false;
				}else{
					!empty($person->getNamefirst()) ? $job_info['Her_Name'] = strtoupper($person->getNamefirst()): false;
					!empty($person->getNamelast()) ? $job_info['HerLastName'] = strtoupper($person->getNamelast()): false;
					!empty($person->getPhone()) ? $job_info['Her_Work_Phone'] = $person->getPhone(): false;
					!empty($person->getPhoneext()) ? $job_info['HerWorkExtension'] = $person->getPhoneext(): false;
					!empty($person->getEmail()) ? $job_info['AltEmailAddress'] = strtoupper($person->getEmail()) : false;
				}
			}

			// formate ACL info here after we know we have all other data.
			// condition ? /* short code */ : /* short code */;
			if(!empty($acl_info)){
				// check this garageEntry
				// !empty($acl_info['GarageEntry'])  ? $job_info['Garage_Side'] = $acl_info['GarageEntry'] . $acl_info['GarageLocation'] : false;
				if( !empty($acl_info['GarageEntry']) && !empty($acl_info['GarageLocation']) ){
					$job_info['Garage_Side'] = $acl_info['GarageLocation'] . $acl_info['GarageEntry'];
				}
				!empty($acl_info['Address']) ? $job_info['Address'] = $acl_info['Address'] : false;
				!empty($acl_info['City']) ? $job_info['City'] = $acl_info['City']: false;
				!empty($acl_info['State']) ? $job_info['State'] = $acl_info['State']: false;
				!empty($acl_info['Zip']) ? $job_info['Zip'] = $acl_info['Zip']: false;
				// ACL Agreement_Written want to use signed date not date created.
				// !empty($acl_info['Agreement_Written']) ? $job_info['Agreement_Written'] = $acl_info['Agreement_Written']: false;
				!empty($acl_info['Total']) ? $job_info['Agreement_Amount'] = $acl_info['Total']: false;
				// !empty($acl_info['GarageEntry']) ? $job_info['GarageEntry'] = $acl_info['GarageEntry']: false;
				// !empty($acl_info['RearExit']) ? $job_info['RearExitFeature'] = $acl_info['RearExit']: false;
				!empty($acl_info['RearExit']) ? $job_info['RearEntry'] = $acl_info['RearExit']: false;
				!empty($acl_info['ACLSequence']) ? $job_info['ACLSequenceID'] = $acl_info['ACLSequence']: false;
				!empty($acl_info['Division']) ? $job_info['Division'] = $acl_info['Division']: false;
				!empty($acl_info['Plan_Name']) ? $job_info['Home_Type'] = $acl_info['Plan_Name']: false;
				// !empty($acl_info['Section_Name']) ? $job_info['Project'] = $acl_info['Section_Name']: false;
				// !empty($acl_info['Copied_From_Market']) ? $job_info['Home_Status'] = "M": $job_info['Home_Status'] = "C" ;
				// !empty($acl_info['Copied_From_Market']) ? $job_info['Home_Status'] = "M": $job_info['Home_Status'] = "C" ;
				// if it exist leave it alone else make it u
				// $job_info['Home_Status'] = "U";  // this is now done on the controller that calls this function
			}

			// print_array($prospect);
			/*$fin = $prospect->getFinancings();
			// print_array($fin);
			foreach ($fin as $key => $f) {
				print_array($f->toArray());
				print_array($f->getLoanofficer());
				$loanOfficer = $f->getLoanofficer();
				$LOInfo = $loanOfficer->getPerson();
				print_array($LOInfo->toArray());

			}*/

			/*Loan officer info filled in here*/
			$loanOfficer = $lead->getLoanOfficers();
			if(!empty($loanOfficer)){
				if(!empty($loanOfficer->getNamefirst()) && !empty($loanOfficer->getNamelast()) ){
					$job_info['LenderContact'] = strtoupper($loanOfficer->getNamefirst() . " " . $loanOfficer->getNamelast());
				}
				!empty($loanOfficer->getEmail()) ? $job_info['LenderEmail'] = strtoupper($loanOfficer->getEmail()) : false;
				!empty($loanOfficer->getPhone()) ? $job_info['LenderPhone'] = $loanOfficer->getPhone() : false;
				!empty($loanOfficer->getPhoneext()) ? $job_info['LenderPhoneExt'] = $loanOfficer->getPhoneext() : false;
				!empty($loanOfficer->getFax()) ? $job_info['LenderFax'] = $loanOfficer->getFax() : false;
			}

			/*get name of Primary Lender*/
			$lender = $lead->getLenderInfo();
			if(!empty($lender)){
				!empty($lender->getName()) ? $job_info['Lender'] = strtoupper($lender->getName()) : false;
			}

			// go out and get Realtor info
			$realtor = $lead->getRealtor();
			// print_array($realtor);
			if(!empty($realtor)){
				!empty($realtor->getAgentnumber()) ? $job_info['AgentNumber'] = $realtor->getAgentnumber() : false;
				if( !empty($realtor->getIdbroker()) ){
					$broker = new \crm\Broker($realtor->getIdbroker());
					!empty($broker->getBrokernumber()) ? $job_info['BrokerNumber'] = $broker->getBrokernumber() : false;
				}
			}

			// get realtor org info
			$orgInfo = $lead->getRealitorBrokerOrgInfo();
			// print_array($orgInfo);
			if( !empty($orgInfo ) ){
				// $job_info['BrokerNumber'] = $orgInfo->getPhone();
			}
			if( !empty($orgInfo ) ){
				// $job_info['Broker'] = $orgInfo->getName();
			}
		}
		return $job_info;
	}
};

function getTarinaColumns(){
	// $a = array();
	$a = array('Job_Number', 'Site_Number', 'JobNumberNew', 'SiteNumberNew', 'Control_Code', 'CreatedDate', 'LastModified', 'CreatedBy', 'Sales_Counselor', 'His_Name', 'HisLastName', 'His_Work_Phone', 'HisWorkExtension', 'HisCellPhone', 'CustomerCurrentAddre', 'CustomerCurrentCity', 'CustomerCurrentState', 'CustomerCurrentZip', 'PrimaryEmailAddress', 'Her_Name', 'HerLastName', 'Her_Work_Phone', 'HerWorkExtension', 'AltEmailAddress', 'Garage_Side', 'GarageLocation', 'GarageEntry', 'Address', 'City', 'State', 'Zip', 'Agreement_Written', 'Agreement_Amount', 'RearExitFeature', 'RearEntry', 'ACLSequenceID', 'Division', 'Home_Type', 'Project', 'Home_Status', 'LenderContact', 'LenderEmail', 'LenderPhone', 'LenderPhoneExt', 'LenderFax', 'Lender', 'AgentNumber', 'BrokerNumber', 'Broker');
	return $a;
}

function emailAlertQue($AlertType = null, $subject = null, $body01 = null){
	if(!empty($AlertType) && !empty($subject) && !empty($body01) ){
		// $sql = 'INSERT ';
	}
}

function quote($str){
	// Field quoting is weird in pervasive.
	$quote = '"';
	return $quote.$str.$quote;
}

function insert($conn = null,$table,$row){
	if(!empty($conn)){
		// iterate fields in row to get names and values
		$sql = insertQuery($table,$row);
		// print_array($sql);
		// print_array($row);
		$params = array_values($row);
		// print_array($params);
		$affected = execDB($conn, $sql,$params);
		return $affected;
	}else{
		return 0;
	}
};

/*
$table = "\"$company\".\"AlertType\"";
$field = 'AlertType'; \\primary
*/
function upsert($conn = null, $table,$primary,$row){
	if(!empty($conn)){
		$id = $row[$primary];
		$verb = "Updated";
		$affected = update($conn, $table,$primary,$row);
		// echo $affected;
		if($affected < 1){
			$verb = "Inserted";
			$affected = insert($conn, $table,$row);
		}
		// echo "$verb $id in $table: ".$affected."<br/>";
		return $affected;
	}else{
		return 0;
	}
};

/*
$table = "\"$company\".\"AlertType\"";
$field = 'AlertType'; \\primary
*/
function update($conn = null,$table,$primary,$row){
	if(!empty($conn)){
		// iterate fields in row, figure out field names and values
		$params = array();
		foreach($row as $key=>$value){
			if($primary != $key){
				$fields[] = quote($key)."=? ";
				// $fields[] = " ".$key."=? ";
				$params[] = $value;
			}
		}
		// last value is for primary key, which will be equal if row already
		// exists in table, else we won't update anything
		$params[] = $row[$primary];
		$sql = "UPDATE $table SET " .  implode(',',$fields) . " WHERE \"$primary\"=? ";
		// debug($sql);
		// echo $sql;
		// print_array($row);
		// print_array($params);
		// get number of affected rows to return
		$affected = execDB($conn, $sql, $params);
		if($affected>1){
			echo "Multiple rows affected with same primary field: $primary";
		}
		return $affected;
	}else{
		return 0;
	}
};

function insertQuery($table,$row){
	$fields = array();
	$qs = array();
	foreach(array_keys($row) as $key){
		// Field quoting is weird in pervasive.
		$fields[] = quote($key);
		$qs[] = '?';
	}
	$sql = "INSERT INTO $table ( ".
		implode(',',$fields) .
		") VALUES (".
		implode(',',$qs).
		");";
	return $sql;
};

function execDB($conn = null,$query,$params=array()){
	if(!empty($conn) ){
		//return count of rows affected
		// print_array($conn);
		$rows = 0;
		try {
			// print_array($query);
			// print_array($params);
			// $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare($query);
			$stmt->execute($params);
			$rows = $stmt->rowCount();
			if(!$stmt){
				print_r($conn->errorInfo());
			}
		} catch(PDOException $ex) {
			echo 'Failed to execute: '.$ex->getMessage();
		}

		return $rows;
	}else{
		return array();
	}
};

function insertEmailQueue($conn = null,$AlertType = null,$subject = null,$body01 = null){
	if(!empty($conn) && !empty($AlertType) && !empty($subject) && !empty($body01)){
		// $table = "\"Fischer Management\".\"EmailQueue\"";
		$table = "\"OHCND01\".\"EmailQueue\"";
		$row['AlertType'] = $AlertType;
		$row['Subject'] = $subject;
		$row['Body01'] = $body01;
		echo insert($conn,$table,$row);
	}
};

function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
        }
    });

    return $array;
};


/* Not sure that I even need this funciton just yet. */
/*function getArrayKeys($array = null){
	$new = array;
	if(!empty($array)){
		foreach ($array as $key => $value) {

		}
	}
	return $new;
};*/

function array2column($array = null){
	$new = "";
	$tmp = array();
	if(!empty($array)){
		// $new = "[";
		foreach ($array as $key => $value) {
			// $new .= "{data:'".$key."'},";
			$tmp[] = array('data' => $key );
		}
		// $new .= "]";
	}
	// return $new;
	// return json_encode($tmp); // do not have to encode here because the endpoint returns all of it json_encoded so it's done twice.
	return $tmp;
};
?>
