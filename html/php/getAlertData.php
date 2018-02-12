<?php 
include_once '../init.php';
checkLogin();

/*$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database
$db = mysql_connect($dbhost, $dbuser, $dbpassword)
or die("Connection Error: " . mysql_error());

mysql_select_db($database) or die("Error conecting to db.");
$result = mysql_query("SELECT COUNT(*) AS count FROM invheader a, clients b WHERE a.client_id=b.client_id");
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
$SQL = "SELECT a.id, a.invdate, b.name, a.amount,a.tax,a.total,a.note FROM invheader a, clients b WHERE a.client_id=b.client_id ORDER BY $sidx $sord LIMIT $start , $limit";
$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
    $responce->rows[$i]['id']=$row[id];
    $responce->rows[$i]['cell']=array($row[id],$row[invdate],$row[name],$row[amount],$row[tax],$row[total],$row[note]);
    $i++;
}        
echo json_encode($responce);*/


// $alerts = \crm\AlerttypeQuery::create()->paginate( array('page' => 1, 'rowsPerPage'=> 10) )->find();
// $alerts = \crm\AlerttypeQuery::create()->find();
$page = 1;
$limit = 10;
if(!empty($_GET['page']) && !empty($_GET['rows'])){
	$page = $_GET['page']; // get the requested page
	$limit = $_GET['rows']; // get how many rows we want to have into the grid
}

$alerts = \crm\AlerttypeQuery::create()->paginate($page, $maxPerPage = $limit);
$responce->page = $alerts->getPage();
$responce->total = $alerts->getLastPage();
// $responce->records = $alerts->count();
$responce->records = $alerts->getNbResults();
// print_array($alerts->toArray());
// $responce = 
foreach ($alerts as $key => $alert) {
	$tmp = $alert->toArray();
	$tmp['LastModified'] = $alert->getLastModified('Y-m-d H:i:s'); 
	$responce->rows[$key]['id'] = $alert->getAlertType();
	$responce->rows[$key]['cell'] = $tmp;
}
echo json_encode($responce);
// print_array($alerts);
?>