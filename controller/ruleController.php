<?php
use Propel\Runtime\Propel;
use Propel\Runtime\Propel\ActiveQuery;

$ruleSaveCommunity = function($log = null){
	// print_array($_REQUEST);
	if( checkauth(apache_request_headers(), $log) == 1 ){
		// print_array($_REQUEST['changes']);
		foreach ($_REQUEST['changes'] as $key => $change) {
			// print_array($change);
			$id = $change[0];
			$pos = $change[1];
			$com = new \rule\Community($id);
			// print_array($com);
			if(!empty($com)){
				// print_array($com);
				// $com->setByPosition($pos,$change[3]);
				$com->setByName($pos,$change[3]);
				$com->save();
			}else{
				// insert!
				$new = new \rule\Community();
				// $new->setByPosition($pos,$change[3]);
				$new->setByName($pos,$change[3]);
				$new->save();
			}
		}
	}
};


$ruleGetCommunity = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$page = 1;
		$limit = 10;
		$filter = array(); $select = array(); $divisionFilters = array(); $bdgtFilters = array();
		if(!empty($_GET['page']) && !empty($_GET['rows'])){
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
		}
		/* get filters if passed */
		if( !empty($_GET['filter']) ){
			$filter = $_GET['filter'];
		}

		if(!empty($_GET['divisionFilters'])){
			$divisionFilters = $_GET['divisionFilters'];
		}

		if(!empty($_GET['bdgtFilters'])){
			$bdgtFilters = $_GET['bdgtFilters'];
		}

		if(!empty($_GET['select'])){
			$select = $_GET['select'];
		}

		$orderBy = array('Code', 'ASC');
		if(!empty($_GET['orderBy'])){
			$orderBy = $_GET['orderBy'];
		}

		if(!empty($_GET['select'])){
			$communities = \CommunityQuery::Create()->
			Select($select)->
			join('Division')->
			join('BdgtNeighborhood')->
			useDivisionQuery()->
			filterByArray($divisionFilters)->
			endUse()->
			useBdgtNeighborhoodQuery()->
			filterByArray($bdgtFilters)->
			endUse()->
			filterByArray($filter)->
			orderBy($orderBy[0], $orderBy[1])->
			paginate($page, $limit);
		}else{
			$communities = \CommunityQuery::Create()->
			filterByArray($filter)->
			// join('Community.Division')->
			// joinBdgtNeighborhood()->
			orderBy($orderBy[0], $orderBy[1])->
			paginate($page, $limit);
		}

		// doesn't work  Danko has a better solution to put in all Query objects for this.
		// $communities = \rule\CommunityQuery::Create()->Select($select)->filterByArray($filter)->paginate($page, $limit);

		$response = new stdClass();
		$response->page = $communities->getPage();
		$response->totalPages = $communities->getLastPage(); // total number of pages
		$response->totalRecords = $communities->getNbResults(); // total number of records
		$response->rows = $communities->toArray();
		/*foreach ($communities as $key => $com) {
			$response->rows[$key] = $com->toArray();
			$response->rows[$key]['Division'] = $com->getDivision()->getDivision();
			$response->rows[$key]['DivisionName'] = $com->getDivision()->getDivisionName();
			$response->rows[$key]['BudgetName'] = $com->getBudgetName();
		}*/
		echo json_encode($response);
	}
};

$ruleGetCommunitySections = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		if(!empty($_GET['comId'])){
			$data = array();
            $community = \CommunityQuery::Create()->findPK($_GET['comId']);
			$sections =  $community->getCommunitySections();
			foreach ($sections as $key => $sec) {
				if($sec->getIsDeleted() != 1){
					$tmp = $sec->toArray();
					$tmp['Count'] = $sec->countCommunitySites();
					$data['CommunitySections'][] = $tmp;
				}
			}
			echo json_encode($data);
		}
	}
};

$ruleGetCommunitySectionLegals = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		if(!empty($_GET['comId'])){
			$data = array();
            $community = \CommunityQuery::Create()->findPK($_GET['comId']);
			$sections =  $community->getCommunitySectionLegals();
			foreach ($sections as $key => $sec) {
				if($sec->getIsDeleted() != 1){
					$tmp = $sec->toArray();
					$tmp['Count'] = $sec->countCommunitySites();
					$data['CommunitySectionLegals'][] = $tmp;
				}
			}
			echo json_encode($data);
		}
	}
};

$ruleGetCommunitySites = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$data = array();

		$filter = array();
		if( !empty($_GET['filter']) ){
			$filter = $_GET['filter'];
		}
		$sites = \CommunitySiteQuery::Create()->filterByArray($filter)->find();

		foreach ($sites as $key => $s) {
			// $tmp = $s->toArray();
			$tmp['SiteId'] = $s->getSiteId();
			$tmp['SiteNumber'] = $s->getSiteNumber();
			$tmp['Purchaser'] = $s->getPurchaser();
			$tmp['cIncl'] = $s->countCommunitySiteInclFeatures();
			$tmp['cPlanAvail'] = $s->countCommunitySitePlanAvailabilities();
			$tmp['cBasePlan'] = $s->countCommunitySitePlanBaseInvests();
			$data['CommunitySites'][] = $tmp;
		}
		echo json_encode($data);
	}
};

$ruleGetInclFeatures = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		if(!empty($_GET['siteId'])){
			$site = new \rule\CommunitySite($_GET['siteId']);
			$incl = $site->getCommunitySiteInclFeatures();
			echo $incl->toJson();
		}
	}
};

$ruleGetSitePA = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		if(!empty($_GET['siteId'])){
			$site = new \rule\CommunitySite($_GET['siteId']);
			$pa = $site->getCommunitySitePlanAvailabilities();
			echo $pa->toJson();
		}
	}
};

$ruleGetSitePBI = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		if(!empty($_GET['siteId'])){
			$site = new \rule\CommunitySite($_GET['siteId']);
			$pbi = $site->getCommunitySitePlanBaseInvests();
			echo $pbi->toJson();
		}
	}
};

$ruleGetComBasePlanInvest = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		if(!empty($_GET['comId'])){
			$com = new \rule\Community($_GET['comId']);
			$bpi = $com->getCommunityPlanBaseInvestments();
			echo $bpi->toJson();
		}
	}
};

$ruleSaveCommunitySection = function($log = null){
	// print_array($_REQUEST);
	if( checkauth(apache_request_headers(), $log) == 1 ){
//		 print_array($_REQUEST['changes']);
//         die();
		foreach ($_REQUEST['changes'] as $key => $change) {
			// print_array($change);
			$fischerUsername = ""; $tmp = "";
			if(!empty($_SESSION['fischer_username'])){
				$fischerUsername = $_SESSION['fischer_username'];
			}
			$id = $change[0];
			$pos = $change[1];

            // Update
			if($id != -1){
                try {
                    $com = new \CommunitySection($id);
                    $tmp = $com->getSectionName();
                    $com->setLastModified('now');
                    if(!empty($_SESSION['userId'])){
                        $com->setUserId($_SESSION['userId']);
                    }
                    $com->setByName($pos,$change[3]);
                    $com->save();

                } catch (Exception $exc) {
                    // TODO add logging or Sentry
//                    echo $exc->getTraceAsString();
                    echo "Failed!";
                }
                // FIXME
                try {
                    $data = json_encode(["id"=>$id, $pos=>$change[3]]);
                    $ss = new \SystemSync();
                    $ss->setSystem('fischer_api'); // CONST?
                    $ss->setApplication('site-manager'); // get from headers? from headers to some php var?
                    $ss->setModel('CommunitySection');
                    $ss->setAction('update');
                    $ss->setData($data);
                    $ss->setCreatedDate(date("Y-m-d H:i:s"));
                    $ss->save();
                } catch (Exception $ex) {
                    echo "Failed to sync!";
                }
//				$com->updateOne2One($tmp, $fischerUsername);
			} else {
				// Insert!
				$new = new \CommunitySection();
				$new->setLastModified('now');
				$new->setCreatedDate('now');
				if(!empty($_SESSION['userId'])){
					$new->setUserId($_SESSION['userId']);
					$new->setCreatedBy($_SESSION['userId']);
				}
				$new->setCommunityId($change[5]);
				$new->setByName($pos,$change[3]);
				$new->save();
			}
			// call data-sync-down on this table.
		}
	}
};


$ruleGetCommunitySection = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$page = 1;
		$limit = 10;
		// $comId = 1;
		$filter = array(); $specLevelFilter = array();
		if(!empty($_GET['page']) && !empty($_GET['rows'])){
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
		}
		if(!empty($_GET['filter'])){
			$filter = $_GET['filter'];
		}

		if(!empty($_GET['specLevelFilter'])){
			$specLevelFilter = $_GET['specLevelFilter'];
		}

		$orderBy = array('SectionId', 'ASC');
		if(!empty($_GET['orderBy'])){
			$orderBy = $_GET['orderBy'];
		}

		if(!empty($_GET['select'])){
			$commSec = \CommunitySectionQuery::Create()->
			Select($_GET['select'])->
			join('SpecLevels')->
			useSpecLevelsQuery()->
			filterByArray($specLevelFilter)->
			endUse()->
			join('Community')->
			filterByArray($filter)->
			orderBy($orderBy[0], $orderBy[1])->
			paginate($page, $limit);
		}else{
			$commSec = \CommunitySectionQuery::Create()->filterByArray($filter)->orderBy($orderBy[0], $orderBy[1])->paginate($page, $limit);
		}
		// $commSec = \rule\CommunitySectionQuery::create()->filterByCommunityId($comId)->paginate($page, $limit);


		$response = new stdClass();
		$response->page = $commSec->getPage();
		$response->totalPages = $commSec->getLastPage(); // total number of pages

		$response->totalRecords = $commSec->getNbResults();
		$response->rows = $commSec->toArray();
		// foreach ($commSec as $key => $cs) {
			// $response->rows[$key] = $cs->toArray();
			// $response->rows[$key]['CommunityCode'] = $cs->getCommunity()->getCode();
			// $response->rows[$key]['CommunityName'] = $cs->getCommunity()->getName();
		// }
		echo json_encode($response);
	}
};

$ruleSaveCommunitySectionLega = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		foreach ($_REQUEST['changes'] as $key => $change) {
			// print_array($change);
			$id = $change[0];
			$pos = $change[1];
			// print_array($com);
			if($id != -1){
                $com = \CommunitySectionLegalQuery::Create()->findPK($id);
				$com->setLastModified('now');
				if(!empty($_SESSION['userId'])){
					$com->setUserId($_SESSION['userId']);
				}
				$com->setByName($pos,$change[3]);
				$com->save();
			}else{
				// insert!
				$new = \CommunitySectionLegalQuery::Create();
				$new->setLastModified('now');
				$new->setCreatedDate('now');
				if(!empty($_SESSION['userId'])){
					$new->setUserId($_SESSION['userId']);
					$new->setCreatedBy($_SESSION['userId']);
				}
				// $new->setByPosition($pos,$change[3]);
				$new->setCommunityId($change[5]);
				$new->setByName($pos,$change[3]);
				$new->save();
			}
		}
	}
};


$ruleGetCommunitySectionLega = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$page = 1;
		$limit = 10;
		$comId = 1;
		$filter = array(); $comFilter = array();
		if(!empty($_GET['page']) && !empty($_GET['rows'])){
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
		}

		if(!empty($_GET['filter'])){
			$filter = $_GET['filter'];
		}

		$orderBy = array('LegalSectionId', 'ASC');
		if(!empty($_GET['orderBy'])){
			$orderBy = $_GET['orderBy'];
		}

		if(!empty($_GET['select'])){
			$commSec = \CommunitySectionLegalQuery::Create()->
			Select($_GET['select'])->
			join('Community')->
			useCommunityQuery()->
			filterByArray($comFilter)->
			endUse()->
			filterByArray($filter)->
			orderBy($orderBy[0], $orderBy[1])->
			paginate($page, $limit);
		}else{
			$commSec = \CommunitySectionLegalQuery::Create()->
			filterByArray($filter)->
			orderBy($orderBy[0], $orderBy[1])->
			paginate($page, $limit);
		}

		$response = new stdClass();
		$response->page = $commSec->getPage();
		$response->totalPages = $commSec->getLastPage(); // total number of pages

		$response->totalRecords = $commSec->getNbResults();
		$response->rows = $commSec->toArray();
		// foreach ($commSec as $key => $cs) {
		// 	$response->rows[$key] = $cs->toArray();
		// 	$response->rows[$key]['CommunityCode'] = $cs->getCommunity()->getCode();
		// 	$response->rows[$key]['CommunityName'] = $cs->getCommunity()->getName();
		// }
		echo json_encode($response);
	}
};

$ruleSaveCommunitySite = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		foreach ($_REQUEST['changes'] as $key => $change) {
			$id = $change[0];
			$pos = $change[1];

			// if(!empty($site)){
			if($id != -1){
				// print_array($com);
				$site = new \CommunitySite($id);
				$site->upsertSiteInventory();

				if($pos == 'SiteCost' || $pos == 'VendorId'){
					$siteInv = $site->getCommunitySiteInventory();
					$siteInv->setByName($pos,$change[3]);
					$siteInv->save();
				}else{
					$site->setByName($pos,$change[3]);
					$site->save2();
				}

			}else{
				// insert!
				$new = new \CommunitySite();
				// $new->setByPosition($pos,$change[3]);
				$new->setCommunityId($change[5]);
				$new->setSectionId($change[6]);
				$new->setLegalSectionId($change[7]);
				$new->save2();
				if($pos == 'SiteCost' || $pos == 'VendorId'){
					$siteInv = $site->getCommunitySiteInventory();
					$siteInv->setByName($pos,$change[3]);
					$siteInv->save();
				}else{
					$new->setByName($pos,$change[3]);
					$new->save();
				}
				// print_array($change);
			}
		}
	}
};


$ruleGetCommunitySite = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$page = 1;
		$limit = 10;
		// $comId = 0; $secId = 0; $secLegaId = 0;
		$filter = array(); $filter2 = array(); $specLevelFilter = array();
		if(!empty($_GET['page']) && !empty($_GET['rows'])){
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
		}

		if(!empty($_GET['filter'])){
			$filter = $_GET['filter'];
		}

		if(!empty($_GET['filter2'])){
			$filter2 = $_GET['filter2'];
		}

		if(!empty($_GET['specLevelFilter'])){
			$specLevelFilter = $_GET['specLevelFilter'];
		}

		$orderBy = array('SiteId', 'ASC');
		if(!empty($_GET['orderBy'])){
			$orderBy = $_GET['orderBy'];
		}

		if(!empty($_GET['select'])){
			// $commSites = \rule\CommunitySiteQuery::Create()->Select($_GET['select'])->joinWithCommunitySiteInventory()->joinWithCommunitySection()->filterByArray($filter)->orderBy($orderBy[0], $orderBy[1])->paginate($page, $limit);
			$commSites = \CommunitySiteQuery::Create()->
				Select($_GET['select'])->
				join('CommunitySection')->
				join('Community')->
				join('CommunitySiteInventory')->
				join('CommunitySectionLegal')->
				join('SpecLevels')->
				useCommunitySiteInventoryQuery()->
				filterByArray($filter2)->
				endUse()->
				useSpecLevelsQuery()->
				filterByArray($specLevelFilter)->
				endUse()->
				filterByArray($filter)->
				orderBy($orderBy[0], $orderBy[1])->
//            toString();
//    var_dump($commSites);
				paginate($page, $limit);
		}else{
			$commSites = \CommunitySiteQuery::Create()->
				 joinWith('CommunitySection')->
				 joinWith('Community')->
				 joinWith('CommunitySiteInventory')->
				 joinWith('CommunitySectionLegal')->
				filterByArray($filter)->
				orderBy($orderBy[0], $orderBy[1])->
				paginate($page, $limit);
		}

		// $commSites = \rule\CommunitySiteQuery::Create()->join('CommunitySiteInventory')->with('CommunitySiteInventory')->limit(10)->find();

		$response = new stdClass();
		$response->page = $commSites->getPage();
		$response->totalPages = $commSites->getLastPage(); // total number of pages
		$response->totalRecords = $commSites->getNbResults();
		$response->rows = $commSites->toArray();
		/*foreach ($commSites as $key => $site) {
			// print_array($site);
			// $response->rows[$key] = $site->toArray();
			$response->rows[$key] = $site->toArray() + $site->getCommunitySiteInventory()->toArray();
			// $response->row[$key]['CommunitySiteInventory'] = $site->getCommunitySiteInventory()->toArray();
			$response->rows[$key]['CommunityName'] = $site->getCommunity()->getName();
			$response->rows[$key]['CommunityCode'] = $site->getCommunity()->getCode();
			$response->rows[$key]['FischerSectionName'] = $site->getCommunitySection()->getSectionName();
			$response->rows[$key]['LegalSectionName'] = $site->getCommunitySectionLegal()->getLegalSectionName();
		}*/
		echo json_encode($response);
	}
};

$ruleSaveInclFeatures = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		foreach ($_REQUEST['changes'] as $key => $change) {
			$id = $change[0];
			$pos = $change[1];
			if($id != -1){
				// find and update
				$incl = new \rule\CommunitySiteInclFeature($id);
				$incl->setByName($pos,$change[3]);
				$incl->save();
			}else{
				// insert!
				$new = new \rule\CommunitySiteInclFeature();
				$new->setSiteId($change[5]);
				$new->setByName($pos,$change[3]);
				$new->save();
			}
		}
	}
};

$ruleSaveSitePA = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		foreach ($_REQUEST['changes'] as $key => $change) {
			$id = $change[0];
			$pos = $change[1];
			if($id != -1){
				// find and update
				$incl = new \rule\CommunitySitePlanAvailability($id);
				$incl->setByName($pos,$change[3]);
				$incl->save();
			}else{
				// insert!
				$new = new \rule\CommunitySitePlanAvailability();
				$new->setSiteId($change[5]);
				$new->setByName($pos,$change[3]);
				$new->save();
			}
		}
	}
};


$ruleSaveSitePBI = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		foreach ($_REQUEST['changes'] as $key => $change) {
			$id = $change[0];
			$pos = $change[1];
			if($id != -1){
				// find and update
				$incl = new \rule\CommunitySitePlanBaseInvest($id);
				$incl->setByName($pos,$change[3]);
				$incl->save();
			}else{
				// insert!
				$new = new \rule\CommunitySitePlanBaseInvest();
				$new->setSiteId($change[5]);
				$new->setByName($pos,$change[3]);
				$new->save();
			}
		}
	}
};

$ruleSaveCommunityPBI = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		foreach ($_REQUEST['changes'] as $key => $change) {
			$id = $change[0];
			$pos = $change[1];
			if($id != -1){
				// find and update
				$incl = new \rule\CommunityPlanBaseInvestment($id);
				$incl->setByName($pos,$change[3]);
				$incl->save();
			}else{
				// insert!
				$new = new \rule\CommunityPlanBaseInvestment();
				$new->setCommunityId($change[5]);
				$new->setSiteId($change[6]);
				$new->setByName($pos,$change[3]);
				$new->save();
			}
		}
	}
};

$ruleGetSpecLevels = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$specLevels = \SpecLevelsQuery::Create()->Select(array('SpecLevelId', 'SpecLevelDescr'))->find();
		$data['rows'] = $specLevels->toArray();
		echo json_encode($data);
	}
};

$ruleGetBdgtNeighborhood = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$bdgt = \BdgtNeighborhoodQuery::Create()->Select(array('BdgtNeighRecordId', 'BdgtNeighName'))->orderBy('BdgtNeighName', 'ASC')->find();
		$data['rows'] = $bdgt->toArray();
		echo json_encode($data);
	}
};

/*Start for division community budget and region.*/
$ruleGetDivisions = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$divisions = \DivisionQuery::Create()->find();
		$data['rows'] = $divisions->toArray();
		echo json_encode($data);
	}
};

$ruleGetRegions = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$regions = \RegionQuery::Create()->find();
		$data['rows'] = $regions->toArray();
		echo json_encode($data);
	}
};
/*end budget and region*/
/* Dashboard Stuff after this */

$ruleDashSum = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$data = array();
		// $coms = \rule\CommunityQuery::Create()->filterByIsActive(1)->find();
		$coms = \CommunityQuery::Create()->filterByShowOnDash(1)->find();

		foreach ($coms as $key => $c) {
			$tmp['Name'] = $c->getName()." (".$c->getCode().")";
			$tmp['Sections'] = $c->getCommunitySections()->count();
			$tmp['Sites'] = $c->getCommunitySites()->count();
			$data[] = $tmp;
		}
		echo json_encode($data);
	}
};

$getVenderRecords = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$page = 1;
		$limit = 10;
		$filter = array(); $select = array();
		if(!empty($_GET['page']) && !empty($_GET['rows'])){
			$page = $_GET['page']; // get the requested page
			$limit = $_GET['rows']; // get how many rows we want to have into the grid
		}
		// $venders = \rule\VendorQuery::Create()->
	}
};

$ruleSaveVendor = function($log = null){
	// print_array($_REQUEST);
	if( checkauth(apache_request_headers(), $log) == 1 ){
		// print_array($_REQUEST['changes']);
		/*foreach ($_REQUEST['changes'] as $key => $change) {
			// print_array($change);
			$id = $change[0];
			$pos = $change[1];
			$com = new \rule\Community($id);
			// print_array($com);
			if(!empty($com)){
				// print_array($com);
				// $com->setByPosition($pos,$change[3]);
				$com->setByName($pos,$change[3]);
				$com->save();
			}else{
				// insert!
				$new = new \rule\Community();
				// $new->setByPosition($pos,$change[3]);
				$new->setByName($pos,$change[3]);
				$new->save();
			}
		}*/

	}
};


$sapphireEndPoint = function($object=null,$action=null,$log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		print_array($object);
		print_array($action);
		if(!empty($_REQUEST['id'])){
			echo $_REQUEST['id']."<br/>";
		}

		if(!empty($object)){
			$reflect  = new ReflectionClass("\\rule\\".$object);
			$reflect->loadByPK($_REQUEST['id']);
			$reflect->sapphire01();
		}
	}
}
?>