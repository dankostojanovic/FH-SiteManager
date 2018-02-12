<?php 
use Propel\Runtime\Propel;
checkLogin();
$where = "WHERE date(start_date) between '".$_REQUEST['startdate']."'and '".$_REQUEST['finishdate']."'";
if(!empty($_REQUEST['name'])){
	$where.= " and `name` = '".$_REQUEST['name']."'";
}

if(!empty($_REQUEST['type'])){
	$where .= " and `type` = '".$_REQUEST['type']."'";
}

$sql = "SELECT date(start_date) as 'date', name, type, SEC_TO_TIME(SUM(TIME_TO_SEC(stop_date) - TIME_TO_SEC(start_date))) AS timediff, SUM(TIME_TO_SEC(stop_date) - TIME_TO_SEC(start_date)) as 'seconds' FROM jobs $where group by name, date(start_date) order by date(start_date) desc, name asc";
$conn = Propel::getConnection('logs');
$data = array();
// {"period": "2012-10-01", "licensed": 3407, "sorned": 660},
foreach($conn->query($sql) as $row){
	// print_array($row);
	// $data[$row['date']][$row['name']] = $row['timediff'];
	$data[$row[0]][$row[1]] = $row[4];
}
// print_array($data);
$format = array();
foreach ($data as $key => $d) {
	$tmp = array();
	$tmp['period'] = $key;
	
	foreach ($d as $i => $t) {
		$tmp[$i] = $t;
	}
	// $format[] = json_encode($tmp);
	$format[] = $tmp;
}
// print_array($format);
echo json_encode($format);
?>