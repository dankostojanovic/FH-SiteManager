<?php 

$powerBiSalesData = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		/* 
		// old version of the call.
		// $data = \crm\DashcompanyoverviewQuery::Create()->orderBy('divisionCode ASC, salesMonth ASC')->find();
		$data = \crm\DashcompanyoverviewQuery::Create()->orderByDivisioncode('ASC')->orderBySalesmonth('ASC')->find();
		// echo $data->toJson();
		$tmp = array();
		foreach ($data as $key => $d) {
			$t = array();
			// $t['Id'] = $d->getId();
			$t['divisionCode'] = $d->getDivisioncode();
			$t['budgetSales'] = $d->getBudgetsales();
			$t['netSales'] = $d->getNetsales();
			$t['goalSales'] = $d->getGoalsales();
			$t['saleMonth'] = $d->getSalesmonth('Y-m-d');
			$tmp[] = $t;
		}
		echo json_encode($tmp);
		*/  

		$filters = array();
		if(!empty($_GET['filter'])){
			$filters = $_GET['filter'];
		}

		/* Filters with date min max */
		// $filters['Period'] = array('2016-01-01'=>'between');
		// $filters['Period'] = array('min'=>'2016-01-01', 'max' => '2016-12-01');
		// $filters['Period'] = array(0 =>['2016-01-01','min'], 1 => ['2016-12-01', 'max']);
		// $filters['Period'] = array(0 =>['2016-01-01','between'], 1 => ['2016-12-01', 'between']);
		// $filters['Period'][] = array("min" => $searchDate." 00:00:00", "max" => $searchDate." 23:59:59");
		// $filters['Period'][] = array("min" => "2016-01-01", "max" => "2016-02-01");

		if( !empty($filters['Division']) && count($filters['Division']) > 1){
			$data = \rule\SalesDashboardQuery::Create();
			$tmp = array();
			foreach ($filters['Division'] as $key => $value) {
				$data->condition('t'.$key, 'sales_dashboard.division = ?', $value[0]);
				$tmp[] = 't'.$key;
			}
			$newFilters = $filters;
			unset($newFilters['Division']);
			$data->combine($tmp, 'or')->filterByArray($newFilters)->orderByPeriod('ASC')->find();
		}else{
			$data = \rule\SalesDashboardQuery::Create()->filterByArray($filters)->orderByPeriod('ASC')->find();
		}

		$newData = array();
		foreach ($data as $key => $v) {
			$newData[$key] = $v->toArray();
			$newData[$key]['Period'] = $v->getPeriod('Y-m-d');
		}
		echo json_encode($newData);
	}
};


$salesDash = function($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		
	}
};

$salesDashGetDistinct = function ($log = null){
	if( checkauth(apache_request_headers(), $log) == 1 ){
		$select = array('Division', 'DivisionName');
		$data = \rule\SalesDashboardQuery::Create()->select($select)->distinct('Division')->orderBy('DivisionName', 'ASC')->find();
		echo json_encode($data->toArray());
	}
};

?>