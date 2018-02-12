<?php
// "my shit!" - Ty Hudson
// FIXME
require_once('init.php');
//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Login";

/* ---------------- END PHP Custom Scripts ------------- */

$error = "";

// Active Domain configuration
// FIXME Move this to environment file outside of git
$ldapHost = "172.16.6.69";
$ldapPort = 389;
$ldapDomain = 'FischerHomes';
$ldapBaseDn = 'DC=FischerHomes, DC=local';
$ldapFjdGroup = 'OU=FJD_Fischer_Group';

if(!empty($_POST))
{
	// print_array($_POST);
	if(empty($_POST['name']) && !empty($_POST['username']) && !empty($_POST['password']))
	{
		$user = \UsersQuery::Create()->filterByActiveDirectoryUsername($_POST['username'])->findone();
        
	// Special handling for nidin
        // Should NOT be pushed to production
        if($_POST['username'] == 'dev-nvinayakan') {
            // set session
            $_SESSION['userId'] = $user->getId();
            $_SESSION['fischer_username'] = $user->getFischerUsername();
            header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/');
            exit();
        }

		if(!empty($user)){
			// try authentication with ldap
			$ldapconn = ldap_connect($ldapHost, $ldapPort) or die("Could not connect to authentication server. Please contact IS");

			ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
			ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

			// clean post before sending to ldap
			$ldapbind = ldap_bind($ldapconn, $_POST['username'].'@'.$ldapDomain, $_POST['password']);

			if($ldapbind){
				// set session
				$_SESSION['userId'] = $user->getId();
				$_SESSION['fischer_username'] = $user->getFischerUsername();
				header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/');
				exit();
			}else
			{
				$error = "User name or password is incorrect.  Try again.";
			}
		}else{
			$error = "User name not found.  Please contact an administrator";
		}
	}else
	{
		$error = "Bots are not allowed to use this site! Sorry.";
	}
}

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"extr-page", "class"=>"animated fadeInDown");

require_once("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
<header id="header">
	<!--<span id="logo"></span>-->

	<div id="logo-group">
		<!-- <span id="logo"> <img src="../img/slim-logo.png" alt="Vision E Forms"> </span> -->

		<!-- END AJAX-DROPDOWN -->
	</div>

	<!-- <span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">Need an account?</span> <a href="/register.php" class="btn btn-danger">Creat account</a> </span> -->

</header>

<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div id="content" class="container">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
				<h1 class="txt-color-red login-header-big">Fischer Homes web solution</h1>
				<div class="hero">

					<div class="pull-left login-desc-box-l">
						<h4 class="paragraph-header"></h4>
						<div class="login-app-icons">
							<!-- <a href="javascript:void(0);" class="btn btn-danger btn-sm">Frontend Template</a>
							<a href="javascript:void(0);" class="btn btn-danger btn-sm">Find out more</a> -->
						</div>
					</div>

					<!-- <img src="<?php //echo ASSETS_URL; ?>/img/demo/iphoneblank.png" class="pull-right display-image" alt="" style="width:210px"> -->

				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<h5 class="about-heading"> </h5>
						<p>

						</p>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<h5 class="about-heading"> </h5>
						<p>

						</p>
					</div>
				</div>

			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
				<div class="well no-padding">
					<form action="#" method="POST" id="login-form" class="smart-form client-form">
						<input type="hidden" name="name" id="name" value="">
						<header>
							Sign In
						</header>

						<fieldset>

							<section>
								<label class="label">Username</label>
								<label class="input"> <i class="icon-append fa fa-user"></i>
									<input type="username" name="username">
									<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter user name</b></label>
							</section>

							<section>
								<label class="label">Password</label>
								<label class="input"> <i class="icon-append fa fa-lock"></i>
									<input type="password" name="password">
									<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
								<div class="note">
									<!-- <a href="/forgotpassword.php">Forgot password?</a> -->
								</div>
							</section>

							<!-- <section>
								<label class="checkbox">
									<input type="checkbox" name="remember" checked="">
									<i></i>Stay signed in</label>
							</section> -->
						</fieldset>
						<footer>
							<?=$error;?>
							<button type="submit" class="btn btn-primary">
								Sign in
							</button>
						</footer>
					</form>

				</div>

			</div>
		</div>
	</div>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php
	//include required scripts
	include("inc/scripts.php");
?>

<!-- PAGE RELATED PLUGIN(S)
<script src="..."></script>-->

<script type="text/javascript">
	runAllForms();

	$(function() {
		// Validation
		$("#login-form").validate({
			// Rules for form validation
			rules : {
				username : {
					required : true,
					// email : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 40
				}
			},

			// Messages for form validation
			messages : {
				username : {
					required : 'Please enter your User name',
					// email : 'Please enter a VALID email address'
				},
				password : {
					required : 'Please enter your password'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	});
</script>
