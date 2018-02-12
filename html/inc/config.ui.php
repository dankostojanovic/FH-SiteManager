<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => APP_URL
);

/*navigation array config

ex:
"dashboard" => array(
	"title" => "Display Title",
	"url" => "http://yoururl.com",
	"url_target" => "_blank",
	"icon" => "fa-home",
	"label_htm" => "<span>Add your custom label/badge html here</span>",
	"sub" => array() //contains array of sub items with the same format as the parent
)

*/
if(!empty($_SESSION['userId'])){
    $user = \UsersQuery::Create()->findPK($_SESSION['userId']);
//	$user = new \Users($_SESSION['userId']);
	$access = $user->getAccess();
	if(!empty($access)){
		if($access == "SuperAdmin"){
			$page_nav = array(
			    "Dashboard" => array(
					"title" => "Dashboard",
					"url" => "app/dashboard.php",
					"icon" => "fa-home"
					// "sub" => array(
					// 	"Lasso2crm" => array(
					// 		"title" => "Lasso2crm Dashboard",
					// 		"url" => 'app/lasso2crm.php'
					// 	)
					// )
				),
				"SalesDash" => array(
					"title" => "Sales Dashboard",
					"url" => "#",
					"icon" => "fa-cogs",
					"sub" => array(
						"powerbi" => array(
							"title" => "Power Bi",
							"url" => 'app/dash-sales-power-bi.php'
						),
						"mine" => array(
							"title" => "My Version",
							"url" => 'app/dash-sales.php'
						)
					)
				),
				"Reports" => array(
					"title" => "Reports",
					// "url" => "app/locations.php",
					"icon" => "fa-area-chart",
					"sub" => array(
						"Lasso2crm" => array(
							"title" => "Lasso2crm Dashboard",
							"url" => 'app/lasso2crm.php'
						),
						"Division" => array(
							"title" => "Division",
							"url" => 'app/division.php'
						)
					)
				),
				"API" => array(
					"title" => "API",
					// "url" => "app/api.php",
					"icon" => "fa-rocket",
					"sub" => array(
						"Dashboard" => array(
							"title" => "API Dashboard",
							"url" => 'app/api.php'
						),
						"Documentation" => array(
							"title" => "API Documentation",
							// "url" => 'app/api-doc.php'
							"url" => 'doc/index.php',
							"url_target" => "_blank"
						),
						"Request" => array(
							"title" => "API Request",
							"url" => 'app/api-request.php'
						),
						"APITest" => array(
							"title" => "API Testing",
							"url" => 'app/api-testing.php'
						),
						"Tokens" => array(
							"title" => "API Tokens",
							"url" => 'app/api-tokens.php'
						),
						"PDV" => array(
							"title" => "PDV Search",
							"url" => 'app/pdv-search.php'
						)
					)
				),
				"Realtor" => array(
					"title" => "Realtor Data View",
					"url" => "app/realtor.php",
					"icon" => "fa-home"
				),
				"Alert_Type" => array(
					"title" => "Alert Type",
					"url" => "app/alertType.php",
					"icon" => "fa-exclamation"
				),
				"Land_Ops" => array(
					"title" => "Land Ops",
					"icon" => "fa-table",
					"sub" => array(
						"Home" => array(
							"title" => "Home",
							"url" => 'app/communities.php'
						),
						"Communities" => array(
							"title" => "Communities",
							"url" => 'app/community-excel.php'
						),
						"Sites" => array(
							"title" => "Sites",
							"url" => '#'
						)
					)
				),
				"Rule" => array(
					"title" => "Rule",
					"icon" => "fa-database",
					"sub" => array(
						"Communities" => array(
							"title" => "Community",
							"url" => 'app/rule-community-excel.php'
						),
						"Sections" => array(
							"title" => "Sections",
							"url" => 'app/rule-comm-section-excel.php'
						),
						"Sites" => array(
							"title" => "Sites",
							"url" => 'app/rule-sites-excel.php'
						),
						"Tabbed View" => array(
							"title" => "Tabbed View",
							"url" => 'app/rule-tabbed.php'
						),
						"Land Dashboard" => array(
							"title" => "Land Dashboard",
							"url" => 'app/land-dashboard.php'
						),
					)
				),
				"Sapphire" => array(
					"title" => "Sapphire System",
					"icon" => "fa-eye",
					"sub" => array(
						"Communities" => array(
							"title" => "Community",
							"url" => 'app/sapphire-community.php'
						),
						"dynamic" => array(
							"title" => "Dynamic View",
							"url" => 'app/sapphire-dynamic.php'
						)
					)
				)
			);
		}

		if($access == "Landops"){
			$page_nav = array(
				"LandOps Home" => array(
					"title" => "Land Ops Home",
					"url" => "app/communities.php",
					"icon" => "fa-home"
				),
				"Pricing Group" => array(
					"title" => "Pricing Group",
					"url" => "app/pricing-group.php",
					"icon" => "fa-table"
				),
				"Escalators" => array(
					"title" => "Escalators",
					"url" => "app/escalators.php",
					"icon" => "fa-line-chart"
				),
			);
		}
	}
}

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array('class' => 'minified'); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>