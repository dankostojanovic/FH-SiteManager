<?php 
use Propel\Runtime\Propel;
checkLogin();
$sql = "select distinct name from jobs";
$conn = Propel::getConnection('logs');
$data = array();
foreach($conn->query($sql) as $row){
	// print_array($row);
	$data[] = $row[0];
}
sort($data);
echo json_encode($data);
?>