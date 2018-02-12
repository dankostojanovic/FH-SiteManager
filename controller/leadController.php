<?php 
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;

$lead_filterByControlCode = function($ControlCode = null, $log = null){
	if( !empty($ControlCode) && checkauth(apache_request_headers(), $log) == 1 ){
		echo \crm\LeadQuery::create()->filterByControlCode($ControlCode)->findone()->toJson();
	}
};

/**
	* @SWG\Get(
	*     path="/lead/filterbyControlCode/{ControlCode}/",
	*     summary="Returns all lead information.",
	*     description="Returns all lead information ",
	*     operationId="lead_filterByControlCode",
	*	  tags={"Lead"},
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
	*         description="Control Code ",
	*         in="path",
	*         name="ControlCode",
	*         required=true,
	*         type="string",
	*         format="string"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Tokens Response",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/Lead")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/Lead"
	*         )
	*     )
	* )
*/

$lead_getAll = function($log = null){
	// Use Criteria;
	// $c = new Criteria();

	if( checkauth(apache_request_headers(), $log) == 1 ){
		// $leads = \crm\LeadQuery::Create()->filterByControlCode(ModelCriteria::ISNOTNULL)->find();
		// $leads = \crm\LeadQuery::Create()->Where('controlCode is not NULL')->limit(20)->find();
		$leads = \crm\LeadQuery::Create()->Where('controlCode is not NULL')->paginate();
		// $leads = \crm\LeadQuery::Create()->filterByControlCode(Criteria::ISNOTNULL)->paginate();
		print_array($leads);
		$data = array();
		$data['page'] = $leads->getPage();
		$data['count'] = $leads->count();
		$data['maxPerPage'] = $leads->getMaxPerPage();
		// $data['lastPage'] = $lead->getMaxPage();
		// $data['total'] = $leads->getTotal();
		foreach ($leads as $key => $l) {
			// print_array($l->toArray());
			$data['object'][] = $l->toArray();
		}
		echo json_encode($data);
		// echo $leads->toArray();
		if(!empty($leads)){
			// echo $leads->toJson();
			// echo json_encode($leads->toArray());
		}
		// echo $leads->count();
		/*$conn = Propel::getConnection('crm');
		$sql = "SELECT * from Lead where controlCode is Not null limit 10;";
		$data = fetch($conn, $sql);
		echo json_encode($data);*/
	}
};


$lead_HSE_CCSaleInfo = function($ControlCode = null, $log = null){
	if( !empty($ControlCode) && checkauth(apache_request_headers(), $log) == 1 ){
		$data = array();
		$lead = \crm\LeadQuery::create()->filterByControlCode($ControlCode)->findone();
		if(!empty($lead)){
			// get lender info
			$lender = $lead->getLenderInfo();
			if(!empty($lender)){
				$data['lender'] = $lender->toArray();
			}
			// get loan officer info
			$loanOfficers = $lead->getLoanOfficers();
			if(!empty($loanOfficers)){
				$data['loanOfficers'] = $loanOfficers->toArray();
			}
			// get realtor info
			$realtor = $lead->getRealtor();
			if(!empty($realtor)){
				$data['realtor'] = $realtor->toArray();
				if(!empty( $realtor->getIdperson()) ){
					$realtorPerson = new \crm\Person($realtor->getIdperson());
					if(!empty($realtorPerson)){
						$data['realtorPerson'] = $realtorPerson->toArray();
					}
				}
				// get broker info
				// get realtor broker organization info
				if(!empty($realtor->getIdbroker())){
					$broker = new \crm\Broker($realtor->getIdbroker());
					if(!empty($broker)){
						$data['broker'] = $broker->toArray();
					}
				}
			}
			$organization = $lead->getRealitorBrokerOrgInfo();
			if(!empty($organization)){
				$data['realtorOrg'] = $organization->toArray();
 			}
		}
		echo json_encode($data);
	}
};
?>