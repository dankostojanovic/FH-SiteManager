<?php
/*
 *
 * Used to pull all users from LDAP into MySQL database
 *
 * Basic sequence with LDAP is connect, bind, search, interpret search
 * result, close connection
 *
 */

// Bring in Propel
require_once '/var/www/fischer_api/vendor/autoload.php';
require_once '/var/www/fischer_api/generated-conf/config.php';
require_once '/var/www/html/functions.inc.php';

// Define LDAP connection
$ldapHost = "172.16.6.69";
$ldapPort = 389;
$ldapDomain = 'FischerHomes';
$ldapBaseDn = 'DC=FischerHomes, DC=local';

// Connect to LDAP
$ds=ldap_connect($ldapHost, $ldapPort);

ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

if ($ds) {
    // Bind
    // Use real user credentials with permissions to read OU=TFG_USER_ACCOUNTS
    $r=ldap_bind($ds, 'dstojanovic@'.$ldapDomain, "real_passowrd");

    // Search OU=TFG_USER_ACCOUNTS for relevant data
    $sr=ldap_search($ds, "OU=TFG_USER_ACCOUNTS,DC=FischerHomes,DC=local", "CN=*");
    $info = ldap_get_entries($ds, $sr);

    // Loop through result set
    for ($i=0; $i<$info["count"]; $i++) {
        if (isset($info[$i]["mail"][0]) && $info[$i]["mail"][0] != '') {
            $user = \UsersQuery::create()->filterByEmail($info[$i]["mail"][0])->findone();
            if (!$user) {
                $user = new \Users();
                $user->setByName('Email', $info[$i]["mail"][0]);
                $user->setFischerUsername($info[$i]["samaccountname"][0]);
                $user->setActiveDirectoryUsername($info[$i]["samaccountname"][0]);
                $user->setCreated('now');
            } else {
                $user->setByName('Email', $info[$i]["mail"][0]);
                $user->setFischerUsername($user->getFischerUsername());
                $user->setActiveDirectoryUsername($info[$i]["samaccountname"][0]);
            }
            try {
                $user->setLastUpdated('now');
                $user->save();
                echo "Username is: " . $info[$i]["samaccountname"][0] . "<br />";
                echo "Email is: " . $info[$i]["mail"][0] . "<br /><hr />";

            } catch (Exception $ex) {
                var_dump($ex);
                var_dump($info[$i]["mail"][0]);
            }
        }
    }

     // Close connection
    ldap_close($ds);

} else {
   echo "<h4>Unable to connect to LDAP server</h4>";
}
