<?php 
// echo "<h1>User test page!</h1>";
$user = new \logs\Users(1);
// $user = new \logs\Users();
// echo $user->create("thudson@fischerhomes.com", "testing", "Billy", "Bob", "SuperAdmin");
// echo $user->create("dstojanovic@fischerhomes.com", "test123", "Danko", "Stojanovic", "SuperAdmin");
// $user->updatePassword('');
// echo $user->create("jcann@fischerhomes.com", "fhomes#123", "John", "Cann", "SuperAdmin");
// echo $user->create("jwetzel@fischerhomes.com", "changeme#123", "Jen", "Wetzel", "LandOpts");
// echo $user->create("tgoldsberry@fischerhomes.com", "fischerhomes#123", "Tarina", "Goldsberry", "Tarina");
// echo "<br />Done!";
// print_array($user);
$crmid = $user->findCRMUserId();
// echo "crm user id = $crmid";
// testing under here tokens
// $token = bin2hex(random_bytes(32));
// echo $token;

$token = new \apidb\Token();
// $token->fillByToken("1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f");
// $token->create($crmid, "Max Test APP", "This is a token to us in the console C++ APP.", 1);
// $token->create($crmid, "Website - Will", "API for Will and the other developer for new website.", 1);
// $token->create($crmid, "Matt Williams", "Token for testing toSapphire", 1);
// $token->create($crmid, "Sapphire Testing Token", "Token for testing toSapphire", 1);
// $token->create($crmid, "Bob Bruegge", "For Bob Bruegge", 1);

// print_array($token);
echo "made token";

/*$now = strtotime(date('Y-m-d H:i:s'));
echo $now ."<br />";

echo strtotime($token->getExpiration('Y-m-d H:i:s'));*/
?>