<?php 
// setup the autoloading
// require_once '/var/www/composer/vendor/autoload.php';

// setup Propel
// require_once '/var/www/composer/generated-conf/config.php';

// echo "<h1>Ldap Test Page!</h1>";
// $ldapHost = "172.16.6.69";
// $ldapPort = 389;
// $ldapDomain = 'FischerHomes';
// $ldapBaseDn = 'DC=FischerHomes, DC=local';
// $ldapFjdGroup = 'OU=FJD_Fischer_Group';
// $ldapDomain = 'FischerHomes';
/*
	'ldap.server' => '172.16.6.69',
    'ldap.baseDn' => 'DC=FischerHomes,DC=local',
    'ldap.fjd-group' => 'OU=FJD_Fischer_Group',
    'ldap.groups-container' => '',
    'ldap.domain' => 'FischerHomes',
*/
$json = file_get_contents("/var/www/composer/ldapConfig.json");

$jsonIterator = new RecursiveIteratorIterator( new RecursiveArrayIterator(json_decode($json, TRUE)), RecursiveIteratorIterator::SELF_FIRST);

// print_array($jsonIterator);
foreach ($jsonIterator as $key => $value) {
	// echo $key."<br />";
}


function findGroups($ldapbind = null, $ldapFjdGroup = null, $ldapBaseDn = null)
{
	$search = ldap_search($ldapbind, $ldapFjdGroup . ',' . $ldapBaseDn, 'objectClass=group', ['cn']);
	$groups = [];
	if ($search)
	{
		$results = ldap_get_entries($ldapbind, $search);
		for ($i = 0; $i < $results['count']; $i++)
		{
			$groups[] = $results[$i]['cn'][0];
		}
	}
	
	return $groups;
}

function findUsers($ldapbind=null, $ldapBaseDn = null, $fjdGroups = null, $ldapFjdGroup = null)
{
	$results = [];
	$cookie = '';
	$pageSize = 10;
	// $fjdGroups = $this->findFJDGroups();
	

	do {
		ldap_control_paged_result($ldapbind , $pageSize, true, $cookie);
		$search = ldap_search($ldapbind , $ldapBaseDn, 'objectClass=user', ['ou', 'cn', 'sAMAccountName', 'memberOf', 'mail', 'employeeID', 'physicalDeliveryOfficeName', 'userAccountControl']);
		$results = array_merge($results, ldap_get_entries($ldapbind, $search));
		ldap_control_paged_result_response($ldapbind, $search, $cookie);
	} while ($cookie !== '' && $cookie !== null);

	// $search = ldap_search($ldapbind, $ldapBaseDn, 'objectClass=user', ['ou', 'cn', 'sAMAccountName', 'memberOf', 'mail', 'employeeID', 'physicalDeliveryOfficeName', 'userAccountControl']);
	// $results = array_merge($results, ldap_get_entries($this->link, $search));
	// $results = ldap_get_entries($ldapbind, $search);

	$users = [];
	for ($i = 0; isset($results[$i]); $i++)
	{
		$user = array();
		$user['username'] = $results[$i]['samaccountname'][0];
		$user['name'] = $results[$i]['cn'][0];
		$user['email'] = (isset($results[$i]['mail'])) ? $results[$i]['mail'][0] : '';
		$user['employeeId'] = isset($results[$i]['employeeID'][0]) ? $results[$i]['employeeID'][0] : '';
		$user['divisions'] = isset($results[$i]['physicaldeliveryofficename'][0]) ? $results[$i]['physicaldeliveryofficename'][0] : '';
		$user['isEnabled'] = (isset($results[$i]['useraccountcontrol'][0]) && $results[$i]['useraccountcontrol'][0] == 512) ? true : false;
		// $user['memberof'] = isset($results[$i]['memberof']) ? $results[$i]['memberof']: false;
 		if (isset($results[$i]['memberof']))
		{
			$user['memberof'] = $results[$i]['memberof'];
			// foreach ($results[$i]['memberof'] as $key => $v) {
			// 	print_array($v);
			// }	
		}

		$users[] = $user;
		
	}
	
	return $users;
}

function parseGroups($memberOf, $FJD = true, $fjdGroups, $ldapBaseDn, $ldapFjdGroup)
{
	$groups = [];
	// $groupContainer = $this->config->fjdGroupsContainer . ',' . $this->config->baseDn;
	$groupContainer =  $ldapFjdGroup . ',' . $ldapBaseDn;
	
	for ($i = 0; $i < $memberOf['count']; $i++)
	{
		if ($ldapFjdGroup == '' || substr($memberOf[$i], -strlen($groupContainer)) == $groupContainer)
		{
			$parts = explode(',', $memberOf[$i]);
			$group_cn_pair = explode('=', $parts[0]);
			$groups[] = $group_cn_pair[1];
		}
	}
	
	return ($FJD) ? array_intersect($groups, $fjdGroups) : $groups;
}

// $ldapconn = ldap_connect($ldapHost, $ldapPort) or die("Could not connect to $ldaphost");

// ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
// ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

/* put ldab passwords in config */
// $ldapbind = ldap_bind($ldapconn, '@FischerHomes', '');
// $ad_users = array();

// We have to set this option for the version of Active Directory we are using.
// ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
// ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.

// $usergroups = findGroups($ldapconn, $ldapFjdGroup, $ldapBaseDn);
// print_array($usergroups);

// if($ldapbind){
// $usergroups = array();
// $users = findUsers($ldapconn, $ldapBaseDn, $usergroups, $ldapFjdGroup);
// print_array($users);

foreach ($users as $key => $user) {
	if($user['isEnabled'] == 1){
		$found = 1; $memof = array(); $memofCount = 0;
		// echo strtolower($user['username'])."<br />";
		// print_array($user);
		if( !empty($user['memberof']) ){
			$memof = $user['memberof'];
			$memofCount = $user['memberof']['count'];
			unset($memof['count']);
		}
		// print_array($memof);
		$userObj = \logs\UsersQuery::Create()->filterByEmail($user['email'])->findone();
		if(empty($userObj)){
			$found = 0;
			$userObj = new \logs\Users();
			$userObj->setCreated('now');
		}
		$name_parts = explode(' ', $user['name']);
		$first_name = $name_parts[0];
		$last_name = $name_parts[sizeof($name_parts)-1];

		$userObj->setName($user['name']);
		$userObj->setFirstName($first_name);
		$userObj->setLastName($last_name);
		$userObj->setEmail($user['email']);
		$userObj->setFischerUsername($user['username']);
		$userObj->setEmployeeId($user['employeeId']);
		$userObj->setDivision($user['divisions']);
		$userObj->setGroups(json_encode($memof));
		$userObj->setGroupsCount($memofCount);
		$userObj->setIsEnabled($user['isEnabled']);
		$userObj->save();

		// echo "Username:".$user['name']." found?:".$found."<br />";
	}
}

?>