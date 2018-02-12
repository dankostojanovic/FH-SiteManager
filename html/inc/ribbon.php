	<!-- RIBBON -->
	<div id="ribbon">

		<span class="ribbon-button-alignment"> 
			<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true" data-reset-msg="Would you like to RESET all your saved widgets and clear LocalStorage?"><i class="fa fa-refresh"></i></span> 
		</span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<!-- This is auto generated -->
		</ol>
		<!-- end breadcrumb -->

		<div id="envLabel" style="display: none;">
            Staging
        </div>

        <div class="pull-right">

			<!-- collapse menu button -->
			<div id="hide-menu" class="btn-header pull-right">
				<span> <a href="javascript:void(0);" title="Collapse Menu" data-action="toggleMenu"><i class="fa fa-reorder"></i></a> </span>
			</div>
			<!-- end collapse menu -->

			<!-- #MOBILE -->
			<!-- Top menu profile link : this shows only when top menu is active -->
			<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
				<li class="">
					<a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown">
						<!-- <img src="<?php //echo APP_URL; ?>/img/avatars/sunny.png" alt="" class="online" /> -->
					</a>
					<ul class="dropdown-menu pull-right">
						<li>
							<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="logout.php" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout" id="loginButtonMobile"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
						</li>
					</ul>
				</li>
			</ul>

			<!-- logout button -->
			<div id="logout" class="btn-header transparent pull-right">
				<span> <a href="<?php echo APP_URL; ?>/logout.php" id="loginButton" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
			</div>
			<!-- end logout button -->

			<!-- fullscreen button -->
			<div id="fullscreen" class="btn-header transparent pull-right">
				<span> <a href="javascript:void(0);" title="Full Screen" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i></a> </span>
			</div>
			<!-- end fullscreen button -->

		</div>
	</div>
	<!-- END RIBBON -->