<?php 
/*function phpInfo(){
	echo "<h1>This is a test page</h1>";
	// phpinfo();
};

function userTest(){
	echo "<center>User test page!</center>";
	$user = new \logs\Users();
	// echo $user->create("thudson@fischerhomes.com", "testing", "Billy", "Bob", "SuperAdmin");
	// echo $user->create("dstojanovic@fischerhomes.com", "test123", "Danko", "Stojanovic", "SuperAdmin");
	print_array($user);
};*/

$ldapTest = function(){
	echo "<h1>Ldap Test Page!</h1>";
	// $ldaphost = "172.16.6.69";
	$ldapport = 389;
	// Connecting to LDAP
	// $ldapconn = ldap_connect($ldaphost, $ldapport)
	          // or die("Could not connect to $ldaphost");
	// print_array($ldapconn);

	$group = FALSE; $inclusive = FALSE;
	$ldap_host = "172.16.6.69";
	// $ldap_host = "local";
 
    // Active Directory DN
    $ldap_dn = "CN=Users,DC=ad,DC=local";
    // $ldap_dn = "CN=Users,DC=ad,DC=FischerHomes";
 
    // Domain, for purposes of constructing $user
    // $ldap_usr_dom = "@".$ldap_host;
    $ldap_usr_dom = "@local";
 
    // Active Directory user
    $user = "thudson";
    $password = "Iforgot16";
 
    // User attributes we want to keep
    // List of User Object properties:
    // http://www.dotnetactivedirectory.com/Understanding_LDAP_Active_Directory_User_Object_Properties.html
    $keep = array(
        "samaccountname",
        "distinguishedname"
    );
 
    // Connect to AD
    $ldap = ldap_connect($ldap_host,$ldapport) or die("Could not connect to LDAP");
    ldap_bind($ldap,$user.$ldap_usr_dom,$password) or die("Could not bind to LDAP");
    // ldap_bind($ldap,$user."@FischerHomes",$password) or die("Could not bind to LDAP");
 
 	// Begin building query
 	if($group) $query = "(&"; else $query = "";
 
 	$query .= "(&(objectClass=user)(objectCategory=person))";
 
    // Filter by memberOf, if group is set
    if(is_array($group)) {
    	// Looking for a members amongst multiple groups
    		if($inclusive) {
    			// Inclusive - get users that are in any of the groups
    			// Add OR operator
    			$query .= "(|";
    		} else {
				// Exclusive - only get users that are in all of the groups
				// Add AND operator
				$query .= "(&";
    		}
 
    		// Append each group
    		foreach($group as $g) $query .= "(memberOf=CN=$g,$ldap_dn)";
 
    		$query .= ")";
    } elseif($group) {
    	// Just looking for membership of one group
    	$query .= "(memberOf=CN=$group,$ldap_dn)";
    }
 
    // Close query
    if($group) $query .= ")"; else $query .= "";
 
	// Uncomment to output queries onto page for debugging
	// print_r($query);
 
 	var_dump($query);
    // Search AD
    $results = ldap_search($ldap,$ldap_dn,$query);
    var_dump($results);
    $entries = ldap_get_entries($ldap, $results);
 
    // Remove first entry (it's always blank)
    array_shift($entries);
 
    $output = array(); // Declare the output array
 
    $i = 0; // Counter
    // Build output array
    foreach($entries as $u) {
        foreach($keep as $x) {
        	// Check for attribute
    		if(isset($u[$x][0])) $attrval = $u[$x][0]; else $attrval = NULL;
 
        	// Append attribute to output array
        	$output[$i][$x] = $attrval;
        }
        $i++;
    }
	print_array($output);
};

?>