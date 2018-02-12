<?php 

$selection_getAllData = function($log = null){
	if( checkauth( apache_request_headers(), $log) == 1 ) {
		$data = \crm\SelectionappointmentQuery::create()->find();
		// $data = \crm\SelectionappointmentQuery::create()->limit(100)->find();
		// $data = \crm\SelectionappointmentQuery::create()->filterById('873')->find();
		// var_dump($data);
		$tmp = array();
		foreach ($data as $key => $d) {
			$tmp['data'][$key] = $d->toArray();
			unset($tmp['data'][$key]['Comments']);
			unset($tmp['data'][$key]['Specialpricingnotes']);
			$tmp['data'][$key]['Scheduledatetime'] = $d->getScheduledatetime("Y-m-d H:i:s");
			$tmp['data'][$key]['Whencreated'] = $d->getWhencreated("Y-m-d H:i:s");
		}
		// print_array($tmp);
		echo json_encode($tmp);
	}
}; 

/**
	* @SWG\Get(
	*     path="/selectionsAppointment/",
	*     summary="Returns all Selections Appointments.",
	*     description="Returns all communities from the Fischer Homes API.",
	*     operationId="selection_getAllData",
	*	  tags={"SelectionsAppointment"},
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
	*             @SWG\Items(ref="#/definitions/SelectionsAppointment")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/SelectionsAppointment"
	*         )
	*     )
	* )
*/

$selection_getAptInfo = function($id = null, $log = null){
	// if id is not null  and check auth header.
	// find appointment by pk or id 
	// return apt info in json.
	/*if(!empty($id) && checkauth(apache_request_headers(), $log) == 1){
		$apt = new \crm\Selectionappointment($id);
		if(!empty($apt->getId())){
			// echo json_encode( $apt->toArray() );
			// echo $apt->getId();
			$data = $apt->toArray();
			unset($data['Comments']);
			unset($data['Specialpricingnotes']);
			$data['Scheduledatetime'] = $apt->getScheduledatetime("Y-m-d H:i:s");;
			$data['Whencreated'] = $apt->getWhencreated("Y-m-d H:i:s");
			echo json_encode($data);
		}
	}*/
};

/**
	* @SWG\Get(
	*     path="/selectionsAppointment/{Id}/",
	*     summary="Find Selections Appointment By ID",
	*     description="Returns a single Selections Appointment",
	*     operationId="selections_getAptInfo",
	*     tags={"SelectionsAppointment"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         description="ID of Selections Appointment to return",
	*         in="path",
	*         name="Id",
	*         required=true,
	*         type="integer",
	*         format="int64"
	*     ),
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
	*         description="successful operation",
	*         @SWG\Schema(ref="#/definitions/SelectionsAppointment")
	*     ),
	*     @SWG\Response(
	*         response="400",
	*         description="Invalid ID supplied"
	*     )
	* )
*/

$selection_updatePervasive = function($id = null, $log = null){

	// get apt by id 
	// check id appointment type and siteNumber and scheduleDateTime is valid.  {date = scheduleDateTime, time = scheduleDateTime}
	// release connection else error
	// if no error update pervasive query.

	// if 1,5,7 = FirstSelectVstSch else 2,3,4,6,8  
	// SignOffDateSch (2,3,4,8,8 we are updating this date); SelectionsDate (leave this a lone) ; SelectionsTime (leave this alone)
	// http://stackoverflow.com/questions/265073/php-background-processes -> lets the broswer go but keeps processing.
	if(!empty($id) && checkauth(apache_request_headers(), $log) == 1){
		$apt = new \crm\Selectionappointment($id);
		// print_array($apt);
		// $conn = new PDO($GLOBALS['dn'], '', '');
		if(!empty($apt->getId())){
			echo 1;
			$conn = new PDO("odbc:live-Pervasive", '', '');
			// $conn = new PDO($GLOBALS['dn'], '', '');
			$db = $apt->getPervasiveDB($conn);
			// print_array($db);
			if(!empty($db)){
				$data = $apt->getPervasiveData($conn, $db);
				// print_array($data);
				if(!empty($log)){
					$log->setDataBefore( json_encode($data) )->save();
				}
				
				$newData = $apt->updatePervasiveData($conn, $db);
				// $apt->updatePervasiveData($conn, $db);
				// $data = $apt->getPervasiveData($conn, $db);
				// print_array($newData);
				if(!empty($log)){
					// $log->setDataAfter( json_encode($data) )->save();
					$log->setDataAfter( json_encode($newData) )->save();
				}
			}
			$conn = null;
		}else{
			echo 0;
		}
	}

};

/**
	* @SWG\GET(
	*     path="/selectionsAppointment/update/{Id}/",
	*     summary="Find Selections Appointment By ID, Then finds database and data in pervasive and updates date.",
	*     description="Returns 1 if successful. Returns 0 if unsuccessful",
	*     operationId="selection_updatePervasive",
	*     tags={"SelectionsAppointment"},
	*     consumes={
	*         "application/json",
	*         "application/x-www-form-urlencoded"
	*     },
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         description="ID of Selections Appointment to return",
	*         in="path",
	*         name="Id",
	*         required=true,
	*         type="integer",
	*         format="int64"
	*     ),
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
	*         description="successful operation will return 1",
	*     ),
	*     @SWG\Response(
	*         response="400",
	*         description="Unsuccessful will return 0"
	*     )
	* )
*/

?>