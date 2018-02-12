<?php 
// will change checklogin to check auth and we will need an auth token passed back
checkLogin();
/*if(empty($_REQUEST['type']) ){
	$jobs = \logs\JobsQuery::create()->find();
}else{
	$jobs = \logs\JobsQuery::create()->filterByType($_REQUEST['type'])->find();
}*/

if(!empty($_REQUEST['type']) && !empty($_REQUEST['name'])){
	$jobs = \logs\JobsQuery::create()->filterByType($_REQUEST['type'])->filterByName($_REQUEST['name'])->filterByStartDate(array("min" => $_REQUEST['startdate']." 00:00:00", "max" => $_REQUEST['finishdate']." 23:59:59"))->find();
}else if(!empty($_REQUEST['type']) && empty($_REQUEST['name']) ){
	$jobs = \logs\JobsQuery::create()->filterByType($_REQUEST['type'])->filterByStartDate(array("min" => $_REQUEST['startdate']." 00:00:00", "max" => $_REQUEST['finishdate']." 23:59:59"))->find();
}else if(empty($_REQUEST['type']) && !empty($_REQUEST['name']) ){
	$jobs = \logs\JobsQuery::create()->filterByName($_REQUEST['name'])->filterByStartDate(array("min" => $_REQUEST['startdate']." 00:00:00", "max" => $_REQUEST['finishdate']." 23:59:59"))->find();
}else{
	$jobs = \logs\JobsQuery::create()->filterByStartDate(array("min" => $_REQUEST['startdate']." 00:00:00", "max" => $_REQUEST['finishdate']." 23:59:59"))->find();
}

// echo $jobs->toJson();
// print_array($jobs->toJson());
$data = array();
foreach ($jobs as $key => $j) {
	// $data[$key][] = $j->getId();
	/*if($j->getName() == "Lasso2crm"){
		$data[$key][] = "<a href='#app/lasso2crm.php?jobsId=".$j->getId()."'>".$j->getId()."</a>";
	}else{
		$data[$key][] = $j->getId();
	}*/
	$data[$key][] = "<a href='#app/lasso2crm.php?jobsId=".$j->getId()."'>".$j->getId()."</a>";
	$data[$key][] = $j->getName();
	$data[$key][] = $j->getType();
	$data[$key][] = $j->getStartDate("Y-m-d");
	$data[$key][] = $j->getStartDate("Y-m-d H:i:s");
	$data[$key][] = $j->getStopDate("Y-m-d H:i:s");
	$data[$key][] = $j->getDescription();
	$datetime1 = new DateTime($j->getStartDate("Y-m-d H:i:s"));
	$datetime2 = new DateTime($j->getStopDate("Y-m-d H:i:s"));
	$interval = $datetime1->diff($datetime2);
	$data[$key][] = $interval->format("%H:%I:%S");
}
$tmp['data'] = $data;
echo json_encode($tmp);

?>