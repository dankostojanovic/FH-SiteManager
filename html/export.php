<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'init.php';

// phpinfo();

/*$serverName = "10.70.12.25";
$connectionOptions = array(
	"Database" => "KovaFischer",
	"Uid" => "fischerreader",
	"PWD" => "tVZSTVKgd8uSFDYirvsU"
);
//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);
//Select Query
$tsql= "SELECT @@Version as SQL_VERSION";
//Executes the query
$getResults= sqlsrv_query($conn, $tsql);
//Error handling
if ($getResults == FALSE)
	die(FormatErrors(sqlsrv_errors()));
?>
<h1> Results : </h1>
<?php
while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
	echo ($row['SQL_VERSION']);
	echo ("<br/>");
}
	sqlsrv_free_stmt($getResults);
function FormatErrors( $errors )
{
	// Display errors.
	echo "Error information: <br/>";
	foreach ( $errors as $error )
	{
		echo "SQLSTATE: ".$error['SQLSTATE']."<br/>";
		echo "Code: ".$error['code']."<br/>";
		echo "Message: ".$error['message']."<br/>";
	}
} */

// $db = new PDO("sqlsrv:Server=10.70.12.25;Database=KovaFischer", "fischerreader", "tVZSTVKgd8uSFDYirvsU");
// $db = new PDO("dblib:host=10.70.12.25;dbname=KovaFischerTest", "fischerreader", "tVZSTVKgd8uSFDYirvsU");
//  tsql -H <hostname/ip_address> -p <port> -U <username> -P <password> -D <chosen_database>
//  tsql -H 10.70.12.25 -U fischerreader -P tVZSTVKgd8uSFDYirvsU -D KovaFischerTest
/*try {
	// $hostname = "10.70.12.25";
	// $port = 10060;
	// $dbname = "KovaFischer";
	// $username = "fischerreader";
	// $pw = "tVZSTVKgd8uSFDYirvsU";
	// $dbh = new PDO ("dblib:host=$hostname:$port;dbname=$dbname","$username","$pw");
	// $db = new PDO("sqlsrv:Server=10.70.12.25;Database=KovaFischer", "fischerreader", "tVZSTVKgd8uSFDYirvsU");
	$db = new PDO("dblib:host=10.70.12.25;dbname=KovaFischer", "fischerreader", "tVZSTVKgd8uSFDYirvsU");	
} catch (PDOException $e) {
	echo "Failed to get DB handle: " . $e->getMessage() . "\n";
	exit;
}*/

// $sql = 'SHOW Tables;';
// $data = fetch($db, $sql);

/* testing sapphire endpoints */
// $api = new \apidb\SapphireApi(1);
// print_array($api);


/*$community = new \rule\Community(1);
// print_array($community);
$sections = $community->getCommunitySections();
foreach ($sections as $key => $s) {
	// print_array($s);
	// print_array($s->getCommunitySites());
	print_array($s->countCommunitySites());
}*/

// $site = new \rule\CommunitySite(3705);
// $incl = $site->getCommunitySiteInclFeatures();
// print_array($incl->toJson());
// $incl = new \rule\CommunitySite(3705)->getCommunitySiteInclFeatures();
// print_array($incl);

// echo "I am here!";
/*use Propel\Runtime\Propel;
$conn = Propel::getConnection('crm');


$sql = "SHOW TABLES";

$tables = fetch($conn, $sql);

// print_array($tables);
foreach ($tables as $key => $table) {
	$tableSQL = 'DESCRIBE '.$table['Tables_in_fischerhomes'];
	// print_array($table);
	// echo $tableSQL."<br/>";
	$tableInfo = fetch($conn, $tableSQL);
	// print_array($tableInfo);
	echo $table['Tables_in_fischerhomes']."<br/>";
	echo "Field,Type,Null,Key,Default,Extra<br/>";
	foreach ($tableInfo as $i => $info) {
		echo $info['Field'].",\"".$info['Type']."\",".$info['Null'].",".$info['Key'].",".$info['Default'].",".$info['Extra']."<br />";
	}
	echo "<br />";
}*/

/*Pervasive down here*/

function describe($conn=null, $table=null){
	// Enumerate columns in table, even if empty.
	// Warning: This may not work in pervasive.
	// http://stackoverflow.com/a/15671366/5114
	// $rs = $conn->query("SELECT * FROM $table LIMIT 0");
	$rs = $conn->query(" SELECT TOP 1 * FROM $table");
	for ($i = 0; $i < $rs->columnCount(); $i++) {
		$col = $rs->getColumnMeta($i);
		$columns[] = $col['name'];
	}
	return $columns;
}

// $conn = new PDO('odbc:live-pervasive', '', '');

// $dbs = getAllDatabases($conn);
// print_array($dbs);

/*foreach ($dbs as $key => $db) {
	// $db = "FISCHER MANAGEMENT";
	echo $db."<br />";
	// $newSQL = 'SELECT XF.XF$Name, XE.* from "'.$db.'".X$File xf inner join "'.$db.'".x$field xe on xe.xe$file = xf.xf$id;';
	$newSQL = 'SELECT DISTINCT XF.XF$Name as "table" from "'.$db.'".X$File xf';
	// echo $newSQL."<br />";
	$data = fetch($conn, $newSQL);
	print_array($data);
}*/

	// $columns = describe($conn, '"FISCHER MANAGEMENT".Community');
	/*$sql = 'SELECT TOP 1 * from "FISCHER MANAGEMENT".Job_Information';
	$columns = fetch($conn, $sql);
	print_array($columns);
	foreach ($columns[0] as $column => $data) {
		echo $column."<br />";
	}*/

// }

/*This starts the DATABASE import from pervasive*/
/*$dbs = fetch($conn, 'SELECT Datasource FROM INFOSYS.Datasources');
// print_array($dbs);
foreach ($dbs as $i => $db) {
	// print_array($db);
	$apidb = new \apidb\PervasiveDatabase();
	$apidb->setName($db['Datasource']);
	$apidb->save();
}*/
/*Ends DATAbase import*/

/*Starts the tables import for the datbases*/
// $databases = \apidb\PervasiveDatabaseQuery::Create()->find();
/*$databases = \apidb\PervasiveDatabaseQuery::Create()->filterById(25)->find();
print_array($databases->toArray());
foreach ($databases as $i => $d) {
	// $d->insertColumns($conn);
	// echo json_encode( $d->toArray() );
	$newSQL = 'SELECT DISTINCT XF.XF$Name as "table" from "'.$d->getName().'".X$File xf';
	// echo $newSQL."<br />";
	$data = fetch($conn, $newSQL);
	// print_array($data);
	foreach ($data as $count => $t) {
		$nTable = new \apidb\PervasiveTable();
		$nTable->setIdDatabase($d->getId());
		$nTable->setTableName($t['table']);
		$nTable->save();
	}
}*/
/*ends table import for the datbases*/

/*starts the Columns import for the tables of a database*/
// $tables = \apidb\PervasiveTableQuery::Create()->find();
// $tables = \apidb\PervasiveTableQuery::Create()->filterByIdTable(1)->find();
/*$tables = \apidb\PervasiveTableQuery::Create()->filterByIdDatabase(28)->find();
// print_array($tables->toArray());
foreach ($tables as $key => $table) {
	print_array($table->toArray());
	$database = \apidb\PervasiveDatabaseQuery::Create()->filterById($table->getIdDatabase())->findone();
	// print_array($database->toArray());
	$newSQL = 'SELECT XF.XF$Name, XE.* from "'.$database->getName().'".X$File xf inner join "'.$database->getName().'".x$field xe on xe.xe$file = xf.xf$id where XF$Name = \''.$table->getTableName().'\';';
	// echo $newSQL."<br />";
	$data = fetch($conn, $newSQL);
	print_array($data);
	// [Xf$Name] => ACL[Xe$Id] => 60441[Xe$File] => 1599[Xe$Name] => XACL2 [Xe$DataType] => 255 [Xe$Offset] => 2[Xe$Size] => 0 [Xe$Dec] => 0 [Xe$Flags] => 0
	foreach ($data as $n => $info) {
		$column = new \apidb\PervasiveColumns();
		$column->setIdDatabase($table->getIdDatabase());
		$column->setIdTable($table->getIdTable());
		$column->setName($info['Xe$Name']);
		$column->setType($info['Xe$DataType']);
		$column->setSize($info['Xe$Size']);
		$column->setUpdatedDate(Date('Y-m-d H:i:s'));
		$column->save();
	}
}*/
/*end columns update*/
// echo date('Y-m-d', strtotime("last sunday"));

// $conn = null;


/*Starting the sql migration here*/

/*$dataTypes = \apidb\PervasiveDataTypesQuery::Create()->find();
foreach ($dataTypes as $key => $dt) {
	// print_array($dt);
	$data[$dt->getId()]['pervasive'] = $dt->getPervasiveData();
	$data[$dt->getId()]['mysql'] = $dt->getMysqlData();
}
// print_array($dataTypes);
// print_array($data);

$tables = \apidb\PervasiveTableQuery::create()->filterByIdDatabase(3)->find();
// $tables = \apidb\PervasiveTableQuery::Create()->filterByIdDatabase(3)->limit(1)->find();
// $tables = \apidb\PervasiveTableQuery::Create()->filterByIdDatabase(3)->where('id_table = 761 or id_table = 776 or id_table = 771 or id_table = 782')->find();

foreach ($tables as $key => $table) {
	echo "table: ". $table->getTableName()."<br />";
	// print_array($table->toArray());
	$sql = "CREATE TABLE `".trim($table->getTableName(),' ')."` (";
	$columns = \apidb\PervasiveColumnsQuery::Create()->filterByIdTable($table->getIdTable())->where('`type` != 255')->find();

	foreach ($columns as $i => $col) {
		// print_array($col->toArray());

		switch ($col->getType()) {
			case 3:
				$sql .= "`".trim($col->getName(),' ')."` ".$data[$col->getType()]['mysql']." ";
				break;
			case 4:
				$sql .= "`".trim($col->getName(),' ')."` ".$data[$col->getType()]['mysql']." ";
				break;
			case 20:
				$sql .= "`".trim($col->getName(),' ')."` ".$data[$col->getType()]['mysql']." ";
				break;
			case 30:
				$sql .= "`".trim($col->getName(),' ')."` ".$data[$col->getType()]['mysql']." ";
				break;
			default:
				$sql .= "`".trim($col->getName(),' ')."` ".$data[$col->getType()]['mysql']."(".$col->getSize().")";
				break;
		}

		// $sql .= "`".trim($col->getName(),' ')."` ".$data[$col->getType()]['mysql']."(".$col->getSize().")";
		if( $i != (count($columns)-1) ){
			$sql .= ',';	
		}
	}
	$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	echo $sql ."<br/>";
	echo "--------------------------------------------------- <br />";
}*/

// $dbh = new PDO("pgsql:dbname=esritest;host=10.1.11.176", 'postgresu', 'PostMe4!!'); 

// $sql = "SELECT * FROM  ";

// use Propel\Runtime\Propel;

// $conn = Propel::getConnection('rule');
// $conn = Propel::getConnection('postsql');
// $pervasive = new PDO('odbc:live-Pervasive', '', '');
// $pervasive = new PDO('odbc:Pervasive', '', '');

/*$coms = \rule\CommunityQuery::create()->find()->toArray();
// print_array($coms);
foreach ($coms as $key => $com) {
	$ruleComs[$com['Code']] = $com; 
}*/
// print_array($ruleComs['TA0']);

// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."Community"';
// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."CommunitySSware"';
// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."CommunitySection"';
// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."CommunitySectionLega"';
// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."CommunityInsm"';
// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."CommunitySecondary"';
// $sql = 'SELECT TOP 10 OFFSET 10 * FROM "FISCHER MANAGEMENT"."CommunitySite"';
// $sql = 'SELECT TOP 10 * FROM "FISCHER MANAGEMENT"."ACL"';
// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."Site_Inventory"';
// $sql = 'SELECT TOP 10 * FROM "FISCHER MANAGEMENT"."PlanBaseInvestment" Order by PlanBaseInvRecordId ASC';
// $sql = 'SELECT * FROM "FISCHER MANAGEMENT"."PlanBaseInvestment" Order by PlanBaseInvRecordId ASC';
// $data = fetch($pervasive, $sql);
// print_array($data);

// foreach ($data as $key => $d) {
// 	// print_array($d);
// 	// echo $key."\n\r";
// 	$new = new \test\Planbaseinvestment();
// 	$new->fromArray($d);
// 	$new->save();
// }

// $sql = 'SELECT * from "Community" join "CommunitySSware" on "Community"."Community" = "CommunitySSware"."Community" join "CommunityInsm" on "Community"."Community" = "CommunityInsm"."Community" join "CommunitySecondary" on "Community"."Community" = "CommunitySecondary"."Community";';
/*$data = fetch($pervasive, $sql);

echo count($data);
// add records in from site_inventory
foreach ($data as $key => $inv) {
	// print_array($inv);
	$si = new \test\SiteInventory();
	// $si->fromArray($inv);
	$i = 0;
	foreach ($inv as $c => $v) {
		// echo $c." ".$v." <br />";
		$si->setByPosition($i, $v);
		$i++;
	}
	// for ($i = 0; $i < count($inv); $i++) { 
	// 	// echo "<br />";
	// 	$si->setByPosition($i, $inv[$i] );
	// }
	$si->save();
	// echo "<br />";
}*/

// foreach ($data[0] as $key => $value) {
// 	echo '"'.$key.'",';
// }

/*foreach ($data as $key => $d) {
	// print_array($d);
	$ruleSection = new \rule\CommunitySection();
	// $ruleSection->fromArray($d); // doesn't work
	if(!empty($ruleComs[$d['Community']]['Id'])){
		$ruleSection->setCommunityId($ruleComs[$d['Community']]['Id']);
	}else{
		$ruleSection->setCommunityId(0);
	}
	
	$ruleSection->setSectionName($d['SectionID']);
	$ruleSection->setFMinLotSizeFrontEntr($d['FMinLotSizeFrontEntr']);
	$ruleSection->setFMinLotSizeSideEntry($d['FMinLotSizeSideEntry']);
	$ruleSection->setFMinLotSizeRearEntry($d['FMinLotSizeRearEntry']);
	$ruleSection->setFComments($d['FComments']);
	$ruleSection->setFTfgZoningJurisDicti($d['FTFGZoningJurisdicti']);
	$ruleSection->setFTfgZoningClassifica($d['FTFGZoningClassifica']);
	$ruleSection->setFTfgFrontYardMin($d['FTFGFrontYardMinimum']);
	$ruleSection->setFTfgSideyardminimum($d['FTFGSideYardMinimum']);
	$ruleSection->setFTfgCombinedsidemini($d['FTFGCombinedSideMini']);
	$ruleSection->setFTfgRearyardminimum($d['FTFGRearYardMinimum']);
	$ruleSection->setFTfgSideoncornermini($d['FTFGSideonCornerMini']);
	$ruleSection->setFWidthAtSetback1($d['FWidthAtSetback1']);
	$ruleSection->setFWidthAtSetback2($d['FWidthAtSetback2']);
	$ruleSection->setFTfgWidthAtSetback($d['FTFGWidthAtSetback']);
	$ruleSection->setFSideNextToStreet1($d['FSideNextToStreet1']);
	$ruleSection->setFSideNextToStreet2($d['FSideNextToStreet2']);
	$ruleSection->setFTfgSideNextToStreet($d['FTFGSideNextToStreet']);
	$ruleSection->setFMinSqftPerLot($d['FMinSQFTPerLot']);
	$ruleSection->setFMinSqFtRanch($d['FMinSqFtRANCH']);
	$ruleSection->setFMinSqFt2Story($d['FMinSqFt2STORY']);
	$ruleSection->setFMinSqFt2Story1Maste($d['FMinSqFt2STORY1MASTE']);
	$ruleSection->setSpecLevelId($d['SpecLevelID']);
	$ruleSection->setLastModified('now');
	$ruleSection->setUserId(1);
	$ruleSection->setCreatedDate('now');
	$ruleSection->setCreatedBy(1);
	$ruleSection->save();
}*/

// echo json_encode($data);

/*$fp = fopen('test.csv', 'w');

foreach ($data as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);*/

// echo count($data);
// print_array($data);
// foreach ($data as $key => $d) {
// 	$community = new \test\Community();
// 	$community->fromArray($d);
// 	$community->save();
// 	echo $d['Community_Name']."<br />";
// }
/*foreach ($data as $key => $d) {
	$community = new \test\Communityssware();
	$community->fromArray($d);
	$community->save();
	// echo $d['Community_Name']."<br />";
}*/

/*foreach ($data as $key => $d) {
	$section = new \test\Communitysection();
	$section->fromArray($d);
	$section->save();
}*/

/*foreach ($data as $key => $d) {
	// $section = new \test\Communitysectionlega();
	// $section = new \test\Communitysite();
	// $section = new \test\ACL
	$section->fromArray($d);
	$section->save();
}*/

/*foreach ($data as $key => $d) {
	$section = new \test\Communityinsm();
	// $section = new \test\Communitysite();
	// $section = new \test\ACL
	$section->fromArray($d);
	$section->save();
}*/

/*foreach ($data as $key => $d) {
	$section = new \test\Communitysecondary();
	// $section = new \test\Communitysite();
	// $section = new \test\ACL
	$section->fromArray($d);
	$section->save();
}*/

// foreach ($data as $key => $d) {
// 	$acl = new \test\Acl();
// 	$acl->fromArray($d);
// 	$acl->save();
// }

?>

<?php 
/*start of moving records over to rule*/

// use Propel\Runtime\Propel;

// $conn = Propel::getConnection('rule');
// $pervasive = new PDO('odbc:live-Pervasive', '', '');

// $coms = \rule\CommunityQuery::create()->find();
// $coms = \rule\CommunityQuery::create()->filterByCode('AE0')->find()->toArray();
// $coms = \rule\CommunityQuery::create()->filterByCode('AE0')->find();
// print_array($coms);
// foreach ($coms as $key => $com) {
// 	$ruleComs[$com['Code']] = $com;
// }

// print_array($ruleComs);

/*foreach ($coms as $key => $com) {
	$sections = \rule\CommunitySectionQuery::Create()->filterByCommunityId($com->getId())->find();
	// print_array( $sections->toArray() );

	foreach ($sections as $countSec => $section) {
		echo "Com Code: ".$com->getCode()." Section: ".$section->getSectionName()." ID: ".$section->getSectionId()." <br />";
		$testSites = \test\CommunitySiteQuery::Create()->filterByCommunity( $com->getCode() )->filterBySectionId( $section->getSectionName() )->find()->toArray();
		// print_array($testSites->toArray());
		foreach ($testSites as $countSite => $site) {
			// print_array($site);
			$n = new \rule\CommunitySite();
			$n->fromArray($site);
			$n->setCommunityId($com->getId());
			$n->setSectionId($section->getSectionId());
			$n->save();
		}
	}
	// $testSites = \test\CommunitySiteQuery::Create()->filterByCommunity($com->getCode())->find();
}*/

// $testSites = \test\CommunitySiteQuery::Create()->filterByCommunity('AE0')->find();
// $tmp = $testSites->toArray();

// print_array( $tmp );

// $community = new \rule\Community();
// $columns = $community->getTableMap()->getColumns();

// print_array($community->getDatabaseMap());
// $comMap = new rule\Map\CommunityTableMap();
// print_array($comMap->getTableMap()->toArray());
// $comMap = \rule\Map\CommunityTableMap::getTableMap();
// $columns = \rule\Map\CommunityTableMap::getTableMap()->getColumns();
// $columns = \rule\Map\CommunityTableMap::getTableMap()->getColumns();
// // print_array( $comMap );

// // $columns = $comMap->getColumns();
// // print_array($columns);
// foreach ($columns as $key => $c) {
// 	// print_array($c);
// 	echo $c->getName()." ".$c->getPhpName()."<br />";
// }
?>

<?php 
/*Starts moving the sites sub tables in.*/

// $coms = \rule\CommunityQuery::create()->find();
// $coms = \rule\CommunityQuery::create()->filterByCode('AE0')->find()->toArray();
// $coms = \rule\CommunityQuery::create()->filterByCode('AE0')->find();

/*foreach ($coms as $key => $c) {
	// print_array($c->getCommunitySites()->toArray());
	$sites = $c->getCommunitySites();
	if(!empty($sites)){
		foreach ($sites as $countSites => $site) {
			// echo "SiteId: ".$site->getSiteId()." SiteNumber: ".$site->getSiteNumber()."<br />";
			$holds = \test\SiteHoldQuery::create()->filterBySiteNumber($site->getSiteNumber())->find();
			// $holds = \test\SiteHoldQuery::create()->where('Site_Number = "'.$site->getSiteNumber().'"')->find();
			if(!empty($holds)){
				foreach ($holds as $countHolds => $hold) {
					// insert into new schema
					echo $site->getSiteNumber();
					print_array($hold->toArray());
					$csh = new \rule\CommunitySiteHold();
					$csh->fromArray($hold->toArray());
					$csh->setSiteId($site->getSiteId());
					$csh->setHoldCodeId($hold->getHoldCode());
					$csh->setCreatedDate($hold->getDateCreated());
					$csh->setModifiedDate($hold->getLastDateModified());
					$csh->save();
				}
			}
		}
	}
}*/

/*$testholds = \test\SiteHoldQuery::Create()->find();

foreach ($testholds as $cth => $hold) {
	print_array($hold->toArray());
}*/

/*$inclFet = \test\SiteInclFeatureQuery::create()->find();
// print_array($inclFet->toArray());
foreach ($inclFet as $key => $fet) {
	$site = \rule\CommunitySiteQuery::create()->filterBySiteNumber($fet->getSiteNumber())->findone();
	if(!empty($site)){
		// print_array($site->toArray());
		$new = new \rule\CommunitySiteInclFeature();
		$tmp = $fet->toArray();
		unset($tmp['SiteInclId']);
		$new->fromArray($tmp);
		$new->setSiteId($site->getSiteId());
		$new->setDescription($tmp['Description1']);
		$new->save();
	}
}*/


// $addl = \test\SiteAddlQuery::create()->limit(10)->find();
/*$addl = \test\SiteAddlQuery::create()->find();
// print_array($addl->toArray());

foreach ($addl as $key => $al) {
	// echo $al->getSiteNumber()."<br />";
	$site = \rule\CommunitySiteQuery::Create()->filterBySiteNumber($al->getSiteNumber())->findone();
	if(!empty($site)){
		// print_array($site->toArray());
		echo $al->getSiteNumber()."  ".$site->getSiteId()."<br />";
		$tmp = $al->toArray();
		unset($tmp['SiteNumber']);
		unset($tmp['JobNumber']);
		// print_array($tmp);
		$site->fromArray($tmp);
		$site->save();
	}
}*/


// $community = new \rule\Community(438);
// echo $community->getCommunitySites()->toJson();

// phpinfo();

/*Start Plan Availability Migrate */

// $pas = \test\SitePlanAvailabilityQuery::Create()->limit(10)->find();
/*use Propel\Runtime\Propel;
$conn = Propel::getConnection('test');
// $sql = "SELECT * from Site_Plan_Availability limit 10";
$sql = "SELECT * from Site_Plan_Availability";

$pas = fetch($conn, $sql);

foreach ($pas as $key => $pa) {
	// Site_Number
	// print_array($pa);
	$site = \rule\CommunitySiteQuery::Create()->filterBySiteNumber($pa['Site_Number'])->findone();
	if(!empty($site)){
		// print_array($pa);
		// unset($pa['Site_Plan_Av_Record_Id']);
		// unset($pa['Site_Number']);
		// unset($pa['Created_By']);
		// unset($pa['Modified_By']);
		// $pa['Site_Id'] = $site->getSiteId();
		// print_array($pa);
		$new = new \rule\CommunitySitePlanAvailability();
		// $new->fromArray($pa);
		$new->setSiteId($site->getSiteId());
		$new->setPlanMaster($pa['Plan_Master']);
		$new->setGarageLocation($pa['Garage_Location']);
		$new->setGarageEntry($pa['Garage_Entry']);
		$new->setRearExit($pa['Rear_Exit']);
		$new->setDateCreated($pa['Date_Created']);
		$new->setLastModifiedDate($pa['Last_Modified_Date']);
		$new->save();
		echo $pa['Site_Number']."\n\r";
	}
	// echo $pa->getSiteNumber()." ".$pa->getPlanMaster()."<br />";
}*/
/*End MIGRATE*/

/* Migrate BASE Plan Investment */

// $test = \test\SitePlanBaseInvestQuery::Create()->limit(10)->find();
/*$test = \test\SitePlanBaseInvestQuery::Create()->find();
foreach ($test as $key => $base) {
	// print_array($base->toArray());
	echo $base->getSiteNumber()."\n\r";
	$tmp = $base->toArray();
	unset($tmp['SitPlnBasInvRecordId']);
	unset($tmp['SiteNumber']);
	$site = \rule\CommunitySiteQuery::Create()->filterBySiteNumber($base->getSiteNumber())->findone();
	if(!empty($site)){
		$tmp['SiteId'] = $site->getSiteId();
		$new = new \rule\CommunitySitePlanBaseInvest();
		$new->fromArray($tmp);
		$new->save();
	}
}*/

/* Testing Postgre SQL Connections for Christorpher */
/*
$db = new PDO('pgsql:dbname=agsbase host=2539Win7', 'sde', 'GisMe32!');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->beginTransaction();
$stmt = $db->prepare("select oid from BLOBS where ident = ?");
$stmt->execute(array($some_id));
$stmt->bindColumn('oid', $oid, PDO::PARAM_STR);
$stmt->fetch(PDO::FETCH_BOUND);
$stream = $db->pgsqlLOBOpen($oid, 'r');
header("Content-type: application/octet-stream");
fpassthru($stream);

*/

/* MIGRATION Community Plan Base Investment */

/*$comArray = array();
$coms = \rule\CommunityQuery::Create()->find();
foreach ($coms as $key => $com) {
	$comArray[$com->getCode()] = $com->toArray();
	// $comArray[$com->getCode()]['Sections'] = $com->getCommunitySections()->toArray();
	$sections = $com->getCommunitySections();
	foreach ($sections as $i => $sec) {
		$comArray[$com->getCode()]['sections'][$sec->getSectionName()] = $sec->toArray();
	}
}
// print_array($comArray);

// $tests = \test\PlanbaseinvestmentQuery::create()->orderBy('Community')->limit(30)->find();
$tests = \test\PlanbaseinvestmentQuery::create()->orderBy('Community')->find();
foreach ($tests as $key => $t) {
	// print_array($t);
	$tmp = $t->toArray();
	unset($tmp['PlanBaseInvRecordId']);
	// $tmp['PlanBaseInvRecordId'] = -1;
	$tmp['Community'] = $comArray[$t->getCommunity()]['Id'];
	$tmp['SectionId'] = $comArray[$t->getCommunity()]['sections'][$t->getSectionId()]['SectionId'];
	// print_array($tmp);
	$new = new \rule\CommunityPlanBaseInvestment();
	$new->fromArray($tmp);
	$new->save();
	echo $key."\n\r";
}*/


// $com = new \rule\Community(1);
// print_array($com->getCommunityPlanBaseInvestments()->toArray());
// print_array($com->getCommunityPlanBaseInvestments()->toJson());

// $data = \test\CommunitysectionlegaQuery::create()->limit(1)->find()->toArray();
// print_array($data);


/*Start of division realignment*/


/*
$div = \crm\AlertemailrecordQuery::Create()->filterByDivisionId(4)->find(); // id 4 is actually division 5
foreach ($div as $key => $alert) {
	$new = new \crm\Alertemailrecord();
	$new->setAlertType($alert->getAlertType());
	$new->setDivisionId(11); // id 11 is actually division 15
	$new->setRecipientEmail($alert->getRecipientEmail());
	$new->setRecipientName($alert->getRecipientName());
	$new->setUserId($alert->getUserId());
	$new->setLastModified('now');
	$new->save();
	echo $new->getId()."< br />";
	print_array($new->toArray());
}

$div = \crm\AlertemailrecordQuery::Create()->filterByDivisionId(7)->find(); // id 7 is actually division 7
foreach ($div as $key => $alert) {
	$new = new \crm\Alertemailrecord();
	$new->setAlertType($alert->getAlertType());
	$new->setDivisionId(12); // id 12 is division 17
	$new->setRecipientEmail($alert->getRecipientEmail());
	$new->setRecipientName($alert->getRecipientName());
	$new->setUserId($alert->getUserId());
	$new->setLastModified('now');
	$new->save();
	echo $new->getId()."< br />";
}
*/
/*end realign */

/*Sales dash fun!*/

// $div = new \crm\Division(4);
// // print_array($div->getNetsalesreportsJoinStaff()->toArray());
// foreach ($div->getNetsalesreportsJoinStaff() as $key => $sales) {
// 	print_array($sales);
// }

/* end sales dash fun for now*/

/* Start Division Realignment */

// get michelle's list 
// loop through it
// look up employee number on staff to find person 
// if we find person we have the email
// logic for old division look up that email and that division duplicate them to new divisions

/*
use Propel\Runtime\Propel;
$conn = Propel::getConnection('crm');

$changes = \test\DivsionChangesQuery::Create()->find();
$total = $changes->count();
$found = 0; $emailFound = 0; $emailAlertsFound = 0;
echo "<table border='1'><tr><td>Name</td><td>Old Divsion</td><td>New Divison</td><td>found?</td><td>Employee Number</td><td>Email</td><td>Job Description</td><td>Email Alert Count</td><td>Distinct Count</td></tr>";
foreach ($changes as $key => $c) {
	// print_array($d->toArray());
	$data = null;
	echo "<tr>";
	echo "<td>".$c->getFullName()."</td><td>".$c->getOldDivision()."</td><td>".$c->getNewDivision()."</td>";
	$staff = \crm\StaffQuery::Create()->filterByEmployeeNumber($c->getVendorId())->findone();
	if(!empty($staff)){
		// print_array($staff);
		// echo $staff->getIdperson()."<br />";
		$person = $staff->getPerson();
		$alerts = \crm\AlertemailrecordQuery::Create()->filterByRecipientEmail($person->getEmail())->find();
		if($alerts->count() != 0){
			$emailAlertsFound++;
			$sql = "SELECT Distinct division_id, count(*) as 'total' from AlertEmailRecord where recipient_email = '".$person->getEmail()."' group by division_id";
			$data = fetch($conn, $sql);
			foreach ($alerts as $na => $alert) {
				if($alert->getDivisionId() == 3 || $alert->getDivisionId() == 8){
					switch ($c->getNewDivision()) {
						case '5-15':  
							// 5 = id 4  15 = id 11	
							$new = new \crm\Alertemailrecord();
							$new->setAlertType($alert->getAlertType());
							$new->setDivisionId(4); // id 12 is division 17
							$new->setRecipientEmail($alert->getRecipientEmail());
							$new->setRecipientName($alert->getRecipientName());
							$new->setUserId($alert->getUserId());
							$new->setLastModified('now');
							$new->save();
							$new2 = new \crm\Alertemailrecord();
							$new2->setAlertType($alert->getAlertType());
							$new2->setDivisionId(11); // id 12 is division 17
							$new2->setRecipientEmail($alert->getRecipientEmail());
							$new2->setRecipientName($alert->getRecipientName());
							$new2->setUserId($alert->getUserId());
							$new2->setLastModified('now');
							$new2->save();
							break;
						case '7-17':
							// 7 = id 7  17 = id 12
							$new = new \crm\Alertemailrecord();
							$new->setAlertType($alert->getAlertType());
							$new->setDivisionId(7); // id 12 is division 17
							$new->setRecipientEmail($alert->getRecipientEmail());
							$new->setRecipientName($alert->getRecipientName());
							$new->setUserId($alert->getUserId());
							$new->setLastModified('now');
							$new->save();
							$new2 = new \crm\Alertemailrecord();
							$new2->setAlertType($alert->getAlertType());
							$new2->setDivisionId(12); // id 12 is division 17
							$new2->setRecipientEmail($alert->getRecipientEmail());
							$new2->setRecipientName($alert->getRecipientName());
							$new2->setUserId($alert->getUserId());
							$new2->setLastModified('now');
							$new2->save();
							break;
						case '5-15-7-17':
							$new = new \crm\Alertemailrecord();
							$new->setAlertType($alert->getAlertType());
							$new->setDivisionId(4); // id 12 is division 17
							$new->setRecipientEmail($alert->getRecipientEmail());
							$new->setRecipientName($alert->getRecipientName());
							$new->setUserId($alert->getUserId());
							$new->setLastModified('now');
							$new->save();
							$new2 = new \crm\Alertemailrecord();
							$new2->setAlertType($alert->getAlertType());
							$new2->setDivisionId(11); // id 12 is division 17
							$new2->setRecipientEmail($alert->getRecipientEmail());
							$new2->setRecipientName($alert->getRecipientName());
							$new2->setUserId($alert->getUserId());
							$new2->setLastModified('now');
							$new2->save();
							$new3 = new \crm\Alertemailrecord();
							$new3->setAlertType($alert->getAlertType());
							$new3->setDivisionId(7); // id 12 is division 17
							$new3->setRecipientEmail($alert->getRecipientEmail());
							$new3->setRecipientName($alert->getRecipientName());
							$new3->setUserId($alert->getUserId());
							$new3->setLastModified('now');
							$new3->save();
							$new4 = new \crm\Alertemailrecord();
							$new4->setAlertType($alert->getAlertType());
							$new4->setDivisionId(12); // id 12 is division 17
							$new4->setRecipientEmail($alert->getRecipientEmail());
							$new4->setRecipientName($alert->getRecipientName());
							$new4->setUserId($alert->getUserId());
							$new4->setLastModified('now');
							$new4->save();
							break;
						default:
							# code...
							break;
					}
				}
			}
		}
		echo "<td>1</td><td>".$staff->getEmployeenumber()."</td><td>".$person->getEmail()."</td><td>".$staff->getFunctionaljobdescription()."</td><td>".$alerts->count()."</td>";
		if(!empty($data)){
			echo "<td>".json_encode($data)."</td>";
		}else{
			echo "<td></td>";
		}
		$found++;
	}else{
		echo "<td>0</td><td></td><td></td><td></td><td></td><td></td>";
	}
	echo "</tr>";
}
echo "</table>";
$percent = number_format( $found / $total, 4);
$percent2 = number_format( $emailAlertsFound / $total, 4);
echo "Total:".$total." Found:".$found." Percent found:".$percent." Have Email Alerts:".$emailAlertsFound." Percent:".$percent2;
*/
?>

<?php 
/*$lead = \crm\LeadQuery::Create()->filterByControlcode(70039614)->findone();
// print_array($lead->toArray());
echo $lead->getStaffEmployeeNumber();*/


/* ME Testing UpdateFromCPA code! */
/*$job_info = array();
$lead = new \crm\Lead(80592);
$customer = $lead->getCustomers();
// echo strtoupper($lead->getPropertytype());
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

print_array($job_info);*/

/*Tables and objects*/

// basicly we want to select all tables on a database and then loop throught the tables and call the propel object.

/*function getPhpName($obj = null){
	if(!empty($obj)){
		$tmp = $obj[0]->attributes();
		foreach ($tmp as $key => $value) {
			if($key == "phpName"){
				return $value;
			}
		}
		return null;
	}
}

$xml = simplexml_load_file('/var/www/composer/generated-reversed-database/test.schema.xml');

use Propel\Runtime\Propel;
$conn = Propel::getConnection('test');

$sql = "SHOW TABLES";

$tables = fetch($conn, $sql);

foreach ($tables as $key => $t) {
	$tmp = $xml->xpath("table[@name='".$t['Tables_in_test']."']");
	$phpName = getPhpName($tmp);
	echo $phpName ."<br />"; 
	if(!empty($phpName)){
		$reflect  = new ReflectionClass("\\test\\".$phpName);
		$instance = $reflect->newInstanceArgs(array('__construct'));
		$instance = $reflect->newInstanceArgs();
		// print_array($instance);
	}
}*/

/*End Table*/

/*Quick migration over to rule*/
/*$coms = \rule\CommunityQuery::Create()->find();

foreach ($coms as $key => $com) {
	$one2one = \one2one\CommunityQuery::Create()->filterByCommunity($com->getCode())->findone();
	if(!empty($one2one)){
		$com->setCity($one2one->getCity());
		$com->setCounty($one2one->getCounty());
		$com->setState($one2one->getState());
		$com->setTaxRate($one2one->getTaxRate());
		$com->setSchoolDistrict($one2one->getSchooldistrict());
		$com->setWhenUpdated('now');
		$com->save();
	}
}*/

// $com = new \rule\CommunitySection(1);

// print_array($com->getCommunity()->toArray());

// $select = array('Id','Code', 'Name');
// $select = array();

// $coms = \rule\CommunityQuery::Create()->select($select)->find();
// $coms = \rule\CommunityQuery::Create()->find();
// print_array($coms->toArray());
// echo $coms->toJson();
// print_array($coms);
// $data = array();
// foreach ($coms as $key => $v) {
	// $data[$key] = $v->toArray();
	// $data[$key]['DivisionName'] = 
	// print_array($v->getDivision()->getDivisionName());
	// echo $v->getDivision()->getDivisionName();
	// echo $v->getDivision()->getDivision()."<br />";
// }

// $com = new \rule\Community(5);
// echo $com->getBudgetName();

// $sites = \rule\CommunitySiteQuery::Create()->limit(100)->find();
/*$sites = \rule\CommunitySiteQuery::Create()->find();

foreach ($sites as $key => $site) {
	$inv = \rule\CommunitySiteInventoryQuery::Create()->filterByJobNumber($site->getJobNumber())->filterBySiteNumber($site->getSiteNumber())->find();
	echo $key."<br />";
	if(!empty($inv)){
		foreach ($inv as $count => $v) {
			$v->setSiteId($site->getSiteId());
			$v->save();
		}
	}
}*/

// $orderBy = array('SiteId', 'ASC');

// $commSites = \rule\CommunitySiteQuery::Create()->Select(array('SiteId','CommunityId','SectionId'))->joinWithCommunitySiteInventory()->filterByArray( array('CommunityId' => [300,' = '] ))->orderBy($orderBy[0], $orderBy[1])->paginate(1,3);
// $commSites = \rule\CommunitySiteQuery::Create()->joinWithCommunitySiteInventory()->filterByArray( array('CommunityId' => [300,' = '] ))->orderBy($orderBy[0], $orderBy[1])->paginate(1,3);
// $commSites = \rule\CommunitySiteQuery::Create()->joinWithCommunitySiteInventory()->filterByCommunityId(2)->paginate(1,1);
// $commSites = \rule\CommunitySiteQuery::Create()->Select('CommunitySite.SiteId', 'CommunitySite.CommunityId')->joinCommunitySiteInventory()->with('CommunitySiteInventory')->filterByCommunityId(2)->find();

// echo $commSites;

// echo $commSites->toJson();
// echo json_encode($commSites->toArray());
// foreach ($commSites as $key => $site) {
	// print_array($site->toArray());
	// print_array($site->getCommunitySiteInventory()->toArray());
	// $tmp = $site->toArray() + $site->getCommunitySiteInventory()->toArray();
	// print_array($tmp);
// }

/*$holds = \one2one\SiteholdQuery::Create()->find();

foreach ($holds as $key => $hold) {
	$site = \rule\CommunitySiteQuery::Create()->filterBySiteNumber($hold->getSiteNumber())->findone();
	// echo  $hold->getSiteNumber().": ".$site->count()."<br />";
	// $site->setHoldCode($hold->getHoldCode());
	// $site->setHoldRequester($hold->getHoldRequester());
	// $site->setHoldDate($hold->getHoldDate());
	// $site->setHoldControlCode($hold->getHoldControlCode());
	// $site->setHoldDeposit($hold->getHoldDeposit());
	// $site->setHoldNotes($hold->getHoldNotes());
	// $site->save();
	print_array($site->toArray());
}*/

/*
$notMatched = 0; $siteError = 0; $totalPA = 0;
$select = array('CommSiteRecordID', 'Site_Number','GarageLocation','GarageEntry','RearExit');
// $sites = \one2one\CommunitysiteQuery::Create()->Select($select)->Distinct('SiteNumber')->where("GarageLocation != '' and GarageEntry != '' and RearExit != ''")->limit(30)->find();
$sites = \one2one\CommunitysiteQuery::Create()->Select($select)->Distinct('SiteNumber')->where("GarageLocation != '' and GarageEntry != '' and RearExit != ''")->find();
echo count($sites);
foreach ($sites as $key => $site) {
	$concat = $site['GarageLocation'].$site['GarageEntry'].$site['RearExit'];
	$planAvailability = \one2one\SiteplanavailabilityQuery::Create()->filterBySitenumber($site['Site_Number'])->find();

	$totalPA = $totalPA + count($planAvailability);
	$sites[$key]['error'] = 0;
	echo $site['Site_Number'].": ".$concat."<br />";

	foreach ($planAvailability as $i => $plan) {
		// print_array($plan->toArray());
		$t1 = $plan->getGaragelocation();
		$t2 = $plan->getGarageentry();
		$t3 = $plan->getRearexit();
		// $concat2 = $plan->getGaragelocation().$plan->getGarageentry().$plan->getRearexit();
		$concat2 = $t1.$t2.$t3;
		$tmp = 0;
		if($concat != $concat2){
			echo $plan->getPlanMaster()." ".$concat." does not match ".$concat2."<br />";
			$notMatched++;
			$sites[$key]['error'] = 1;
		}
	}
}

foreach ($sites as $key => $site) {
	if($site['error'] == 1){
		$siteError++;
	}
}

echo "Total Sites:".count($sites)." Error Sites:".$siteError." Total SitePlanAvailability Records:".$totalPA." notMatched SitePlanAvailability Records:".$notMatched; 
*/
?>