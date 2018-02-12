<?php
//initilize the page
// require_once("inc/init.php");
// echo __DIR__;
require_once(__DIR__."/lib/config.php");

//require UI configuration (nav, ribbon, etc.)
require_once(__DIR__."/inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Vision E Forms";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"extr-page", "class"=>"animated fadeInDown");
include("inc/header.php");


?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
<header id="header">
	<!--<span id="logo"></span>-->

	<div id="logo-group">
		<span id="logo"> <img src="../img/slim-logo.png" alt="Vision E Forms"> </span>

		<!-- END AJAX-DROPDOWN -->
	</div>

	<!-- <span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">Need an account?</span> <a href="/register.php" class="btn btn-danger">Creat account</a> </span> -->

</header>
<style>
.rightSize li {
	/*width: <?php //echo round(100 / (count($questions)+1),2); ?>%;*/
	width: <?php echo round(100 / 7,2); ?>%;
}
.myRadio
{
	float: left;
	position: relative;
    margin-right: 30px;
    margin-bottom: 4px;
    padding-left: 25px;
    line-height: 25px;
    color: #404040;
    cursor: pointer;
    font-size: 13px;
    margin-top: 0;
}
</style>
<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div id="content" class="container">
	<article class="col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-deletebutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-check"></i> </span>
					<h2><?=$u->getCompany();?> Form</h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">

						<div class="row">
							<form id="wizard-1" class="" novalidate="novalidate">
								<div id="bootstrap-wizard-1" class="col-sm-12">
									<div class="form-bootstrapWizard rightSize">
										<ul class="bootstrapWizard form-wizard">
											<li class="active" data-target="#step1">
												<a href="#tab1" data-toggle="tab"> <span class="step">1</span> <span class="title hidden-xs">Contact information</span> </a>
											</li>
											<!-- <li data-target="#step2">
												<a href="#tab2" data-toggle="tab"> <span class="step">2</span> <span class="title">Select A Location</span> </a>
											</li> -->
											<li data-target="#step3">
												<a href="#tab3" data-toggle="tab"> <span class="step">2</span> <span class="title hidden-xs">Medical Insurance</span> </a>
											</li>
											<li data-target="#step4">
												<a href="#tab4" data-toggle="tab"> <span class="step">3</span> <span class="title hidden-xs">Life Style</span> </a>
											</li>
											<li data-target="#step5">
												<a href="#tab5" data-toggle="tab"> <span class="step">4</span> <span class="title hidden-xs">Contacts</span> </a>
											</li>
											<li data-target="#step6">
												<a href="#tab6" data-toggle="tab"> <span class="step">5</span> <span class="title hidden-xs">Optical Preferences</span> </a>
											</li>
											<li data-target="#step7">
												<a href="#tab7" data-toggle="tab"> <span class="step">6</span> <span class="title hidden-xs">Confirmation</span> </a>
											</li>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="tab-content">
										<div class="tab-pane active" id="tab1">
											<br>
											<h3><strong>Step 1 </strong> - Contact Information</h3>
											<input type="hidden" name="userId" id="userId" value="<?=$u->getId();?>">
											<input type="hidden" name="formId" id="formId" value="">
											<input type="hidden" name="hCode" id="hCode" value="null">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="First Name" type="text" name="fname" id="fname">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Last Name" type="text" name="lname" id="lname">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="date of birth" type="text" name="birth_date" id="birth_date">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-plus-square fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Last four social securty" type="text" name="sos" id="sos">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="email@address.com" type="text" name="email" id="email">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Confirm Email" type="text" name="email2" id="email2">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-phone fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" data-mask="+(999) 999-9999" data-mask-placeholder= "X" placeholder="Best Phone" type="text" name="wphone" id="wphone">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-phone fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" data-mask="+(999) 999-9999" data-mask-placeholder= "X" placeholder="Confirm Phone" type="text" name="hphone" id="hphone">
														</div>
													</div>
												</div>
											</div>
											<h3><strong>Validate Phone Number</strong> - Choose how to validate your number</h3>
											<div class="row">
												<div class="col-sm-6 col-sm-offset-3">
													<div class="inline-group myRadio">
														<label class="radio myRadio radio-inline">
															<input type="radio" name="val-type" value="SMS">
															<i></i>SMS (text)</label>
														<label class="radio myRadio radio-inline">
															<input type="radio" name="val-type" value="Voice">
															<i></i>Voice</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6 col-sm-offset-3">
													<a href="javascript:valPhone();" class="btn btn-lg btn-primary">Send Validation Now</a><br>
												</div>
											</div>
											<div class="row"><br /></div>
											<div class="row">
												<div class="col-sm-6 col-sm-offset-3">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-check-square fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Validation Code" type="text" name="vCode" id="vCode">
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- <div class="tab-pane" id="tab2">
											<br>
											<h3><strong>Step 2</strong> - Select A Location</h3>
											<p>This pannel needs to be built.</p>
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-flag fa-lg fa-fw"></i></span>
															<select name="country" class="form-control input-lg">
																<option value="" selected="selected">Select Country</option>
																<option value="United States">United States</option>
																<option value="United Kingdom">United Kingdom</option>
																<option value="Afghanistan">Afghanistan</option>
																<option value="Albania">Albania</option>
																<option value="Algeria">Algeria</option>
																<option value="American Samoa">American Samoa</option>
																<option value="Andorra">Andorra</option>
																<option value="Angola">Angola</option>
																<option value="Anguilla">Anguilla</option>
																<option value="Antarctica">Antarctica</option>
																<option value="Antigua and Barbuda">Antigua and Barbuda</option>
																<option value="Argentina">Argentina</option>
																<option value="Armenia">Armenia</option>
																<option value="Aruba">Aruba</option>
																<option value="Australia">Australia</option>
																<option value="Austria">Austria</option>
																<option value="Azerbaijan">Azerbaijan</option>
																<option value="Bahamas">Bahamas</option>
																<option value="Bahrain">Bahrain</option>
																<option value="Bangladesh">Bangladesh</option>
																<option value="Barbados">Barbados</option>
																<option value="Belarus">Belarus</option>
																<option value="Belgium">Belgium</option>
																<option value="Belize">Belize</option>
																<option value="Benin">Benin</option>
																<option value="Bermuda">Bermuda</option>
																<option value="Bhutan">Bhutan</option>
																<option value="Bolivia">Bolivia</option>
																<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
																<option value="Botswana">Botswana</option>
																<option value="Bouvet Island">Bouvet Island</option>
																<option value="Brazil">Brazil</option>
																<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
																<option value="Brunei Darussalam">Brunei Darussalam</option>
																<option value="Bulgaria">Bulgaria</option>
																<option value="Burkina Faso">Burkina Faso</option>
																<option value="Burundi">Burundi</option>
																<option value="Cambodia">Cambodia</option>
																<option value="Cameroon">Cameroon</option>
																<option value="Canada">Canada</option>
																<option value="Cape Verde">Cape Verde</option>
																<option value="Cayman Islands">Cayman Islands</option>
																<option value="Central African Republic">Central African Republic</option>
																<option value="Chad">Chad</option>
																<option value="Chile">Chile</option>
																<option value="China">China</option>
																<option value="Christmas Island">Christmas Island</option>
																<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
																<option value="Colombia">Colombia</option>
																<option value="Comoros">Comoros</option>
																<option value="Congo">Congo</option>
																<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
																<option value="Cook Islands">Cook Islands</option>
																<option value="Costa Rica">Costa Rica</option>
																<option value="Cote D'ivoire">Cote D'ivoire</option>
																<option value="Croatia">Croatia</option>
																<option value="Cuba">Cuba</option>
																<option value="Cyprus">Cyprus</option>
																<option value="Czech Republic">Czech Republic</option>
																<option value="Denmark">Denmark</option>
																<option value="Djibouti">Djibouti</option>
																<option value="Dominica">Dominica</option>
																<option value="Dominican Republic">Dominican Republic</option>
																<option value="Ecuador">Ecuador</option>
																<option value="Egypt">Egypt</option>
																<option value="El Salvador">El Salvador</option>
																<option value="Equatorial Guinea">Equatorial Guinea</option>
																<option value="Eritrea">Eritrea</option>
																<option value="Estonia">Estonia</option>
																<option value="Ethiopia">Ethiopia</option>
																<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
																<option value="Faroe Islands">Faroe Islands</option>
																<option value="Fiji">Fiji</option>
																<option value="Finland">Finland</option>
																<option value="France">France</option>
																<option value="French Guiana">French Guiana</option>
																<option value="French Polynesia">French Polynesia</option>
																<option value="French Southern Territories">French Southern Territories</option>
																<option value="Gabon">Gabon</option>
																<option value="Gambia">Gambia</option>
																<option value="Georgia">Georgia</option>
																<option value="Germany">Germany</option>
																<option value="Ghana">Ghana</option>
																<option value="Gibraltar">Gibraltar</option>
																<option value="Greece">Greece</option>
																<option value="Greenland">Greenland</option>
																<option value="Grenada">Grenada</option>
																<option value="Guadeloupe">Guadeloupe</option>
																<option value="Guam">Guam</option>
																<option value="Guatemala">Guatemala</option>
																<option value="Guinea">Guinea</option>
																<option value="Guinea-bissau">Guinea-bissau</option>
																<option value="Guyana">Guyana</option>
																<option value="Haiti">Haiti</option>
																<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
																<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
																<option value="Honduras">Honduras</option>
																<option value="Hong Kong">Hong Kong</option>
																<option value="Hungary">Hungary</option>
																<option value="Iceland">Iceland</option>
																<option value="India">India</option>
																<option value="Indonesia">Indonesia</option>
																<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
																<option value="Iraq">Iraq</option>
																<option value="Ireland">Ireland</option>
																<option value="Israel">Israel</option>
																<option value="Italy">Italy</option>
																<option value="Jamaica">Jamaica</option>
																<option value="Japan">Japan</option>
																<option value="Jordan">Jordan</option>
																<option value="Kazakhstan">Kazakhstan</option>
																<option value="Kenya">Kenya</option>
																<option value="Kiribati">Kiribati</option>
																<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
																<option value="Korea, Republic of">Korea, Republic of</option>
																<option value="Kuwait">Kuwait</option>
																<option value="Kyrgyzstan">Kyrgyzstan</option>
																<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
																<option value="Latvia">Latvia</option>
																<option value="Lebanon">Lebanon</option>
																<option value="Lesotho">Lesotho</option>
																<option value="Liberia">Liberia</option>
																<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
																<option value="Liechtenstein">Liechtenstein</option>
																<option value="Lithuania">Lithuania</option>
																<option value="Luxembourg">Luxembourg</option>
																<option value="Macao">Macao</option>
																<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
																<option value="Madagascar">Madagascar</option>
																<option value="Malawi">Malawi</option>
																<option value="Malaysia">Malaysia</option>
																<option value="Maldives">Maldives</option>
																<option value="Mali">Mali</option>
																<option value="Malta">Malta</option>
																<option value="Marshall Islands">Marshall Islands</option>
																<option value="Martinique">Martinique</option>
																<option value="Mauritania">Mauritania</option>
																<option value="Mauritius">Mauritius</option>
																<option value="Mayotte">Mayotte</option>
																<option value="Mexico">Mexico</option>
																<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
																<option value="Moldova, Republic of">Moldova, Republic of</option>
																<option value="Monaco">Monaco</option>
																<option value="Mongolia">Mongolia</option>
																<option value="Montserrat">Montserrat</option>
																<option value="Morocco">Morocco</option>
																<option value="Mozambique">Mozambique</option>
																<option value="Myanmar">Myanmar</option>
																<option value="Namibia">Namibia</option>
																<option value="Nauru">Nauru</option>
																<option value="Nepal">Nepal</option>
																<option value="Netherlands">Netherlands</option>
																<option value="Netherlands Antilles">Netherlands Antilles</option>
																<option value="New Caledonia">New Caledonia</option>
																<option value="New Zealand">New Zealand</option>
																<option value="Nicaragua">Nicaragua</option>
																<option value="Niger">Niger</option>
																<option value="Nigeria">Nigeria</option>
																<option value="Niue">Niue</option>
																<option value="Norfolk Island">Norfolk Island</option>
																<option value="Northern Mariana Islands">Northern Mariana Islands</option>
																<option value="Norway">Norway</option>
																<option value="Oman">Oman</option>
																<option value="Pakistan">Pakistan</option>
																<option value="Palau">Palau</option>
																<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
																<option value="Panama">Panama</option>
																<option value="Papua New Guinea">Papua New Guinea</option>
																<option value="Paraguay">Paraguay</option>
																<option value="Peru">Peru</option>
																<option value="Philippines">Philippines</option>
																<option value="Pitcairn">Pitcairn</option>
																<option value="Poland">Poland</option>
																<option value="Portugal">Portugal</option>
																<option value="Puerto Rico">Puerto Rico</option>
																<option value="Qatar">Qatar</option>
																<option value="Reunion">Reunion</option>
																<option value="Romania">Romania</option>
																<option value="Russian Federation">Russian Federation</option>
																<option value="Rwanda">Rwanda</option>
																<option value="Saint Helena">Saint Helena</option>
																<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
																<option value="Saint Lucia">Saint Lucia</option>
																<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
																<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
																<option value="Samoa">Samoa</option>
																<option value="San Marino">San Marino</option>
																<option value="Sao Tome and Principe">Sao Tome and Principe</option>
																<option value="Saudi Arabia">Saudi Arabia</option>
																<option value="Senegal">Senegal</option>
																<option value="Serbia and Montenegro">Serbia and Montenegro</option>
																<option value="Seychelles">Seychelles</option>
																<option value="Sierra Leone">Sierra Leone</option>
																<option value="Singapore">Singapore</option>
																<option value="Slovakia">Slovakia</option>
																<option value="Slovenia">Slovenia</option>
																<option value="Solomon Islands">Solomon Islands</option>
																<option value="Somalia">Somalia</option>
																<option value="South Africa">South Africa</option>
																<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
																<option value="Spain">Spain</option>
																<option value="Sri Lanka">Sri Lanka</option>
																<option value="Sudan">Sudan</option>
																<option value="Suriname">Suriname</option>
																<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
																<option value="Swaziland">Swaziland</option>
																<option value="Sweden">Sweden</option>
																<option value="Switzerland">Switzerland</option>
																<option value="Syrian Arab Republic">Syrian Arab Republic</option>
																<option value="Taiwan, Province of China">Taiwan, Province of China</option>
																<option value="Tajikistan">Tajikistan</option>
																<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
																<option value="Thailand">Thailand</option>
																<option value="Timor-leste">Timor-leste</option>
																<option value="Togo">Togo</option>
																<option value="Tokelau">Tokelau</option>
																<option value="Tonga">Tonga</option>
																<option value="Trinidad and Tobago">Trinidad and Tobago</option>
																<option value="Tunisia">Tunisia</option>
																<option value="Turkey">Turkey</option>
																<option value="Turkmenistan">Turkmenistan</option>
																<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
																<option value="Tuvalu">Tuvalu</option>
																<option value="Uganda">Uganda</option>
																<option value="Ukraine">Ukraine</option>
																<option value="United Arab Emirates">United Arab Emirates</option>
																<option value="United Kingdom">United Kingdom</option>
																<option value="United States">United States</option>
																<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
																<option value="Uruguay">Uruguay</option>
																<option value="Uzbekistan">Uzbekistan</option>
																<option value="Vanuatu">Vanuatu</option>
																<option value="Venezuela">Venezuela</option>
																<option value="Viet Nam">Viet Nam</option>
																<option value="Virgin Islands, British">Virgin Islands, British</option>
																<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
																<option value="Wallis and Futuna">Wallis and Futuna</option>
																<option value="Western Sahara">Western Sahara</option>
																<option value="Yemen">Yemen</option>
																<option value="Zambia">Zambia</option>
																<option value="Zimbabwe">Zimbabwe</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-map-marker fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="city">
																<option value="" selected="selected">Select City</option>
																<option>Amsterdam</option>
																<option>Atlanta</option>
																<option>Baltimore</option>
																<option>Boston</option>
																<option>Buenos Aires</option>
																<option>Calgary</option>
																<option>Chicago</option>
																<option>Denver</option>
																<option>Dubai</option>
																<option>Frankfurt</option>
																<option>Hong Kong</option>
																<option>Honolulu</option>
																<option>Houston</option>
																<option>Kuala Lumpur</option>
																<option>London</option>
																<option>Los Angeles</option>
																<option>Melbourne</option>
																<option>Mexico City</option>
																<option>Miami</option>
																<option>Minneapolis</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope-o fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Postal Code" type="text" name="postal" id="postal">
														</div>
													</div>
												</div>
											</div>
										</div> -->
										<div class="tab-pane" id="tab3">
											<br>
											<h3><strong>Step 2</strong> - Medical Insurance Information</h3>
											<h3>Major Medical Insurance</h3>
											<div class="row">
												<div class="col-sm-12">
													<h2><strong>Do you have Medical Insurance?</strong></h2>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="med_insu" value="yes">
													<i></i>Yes</label>
												</div>
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="med_insu" value="no">
													<i></i>No</label>
												</div>
											</div>
											<div class="row" hidden="" id="holder1">
												<div class="col-sm-6 ">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Policyholder's Name" type="text" name="ph1_name" id="ph1_name">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="date of birth" type="text" name="ph1_bd" id="ph1_bd">
														</div>
													</div>
												</div>
											</div>
											<div class="row" hidden="" id="holder2">
												<div class="col-sm-6 ">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-building fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Company" type="text" name="ph1_company" id="ph1_company">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-plus fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Policy Number" type="text" name="ph1_num" id="ph1_num">
														</div>
													</div>
												</div>
											</div>
											<h3>Vision Insurance</h3>
											<div class="row">
												<div class="col-sm-12">
													<h2><strong>Do you have Vision Insurance?</strong></h2>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="vis_insu" value="yes">
													<i></i>Yes</label>
												</div>
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="vis_insu" value="no">
													<i></i>No</label>
												</div>
											</div>
											<div class="row" hidden="" id="holder3">
												<div class="col-sm-6 ">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Policyholder's Name" type="text" name="ph2_name" id="ph2_name">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="date of birth" type="text" name="ph2_bd" id="ph2_bd">
														</div>
													</div>
												</div>
											</div>
											<div class="row" hidden="" id="holder4">
												<div class="col-sm-6 ">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-building fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Company" type="text" name="ph2_company" id="ph2_company">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-plus fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Policy Number" type="text" name="ph2_num" id="ph2_num">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab4">
											<br>
											<h3><strong>Step 3</strong> - Life Style</h3>
											<div class="row">
												<div class="col-sm-12">
													<h2><strong>Please check all that apply!</strong></h2>
													<label class="radio myRadio radio-inline">
													<input type="radio" name="patient_type" value="existing">
													<i></i>Existing Patient</label>
													<label class="radio myRadio radio-inline">
													<input type="radio" name="patient_type" value="new">
													<i></i>New Patient</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="have_sunglasses" >
													<i></i>I have quality sunglasses</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="rx_sunglasses">
													<i></i>I have Rx sunglasses</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="primary_eyeglasses">
													<i></i>I have a primary set of eyeglasses</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="backup_eyeglasses">
													<i></i>I have backup glasses</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<h2><strong>Today I am in need of:</strong></h2>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="need_glasses" >
													<i></i>Eyeglasses</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="need_backup_glasses">
													<i></i>Backup Eyeglasses</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="need_sunglasses">
													<i></i>Sunglasses</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="need_rx_sunglasses">
													<i></i>RX Sunglasses</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="need_reading_glasses">
													<i></i>Reading Glasses</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="need_computer_glasses">
													<i></i>Computer Glasses</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="need_contacts">
													<i></i>Contact Lenses</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<h2><strong>Please check all activities you are involved in:</strong></h2>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="cycle" >
													<i></i>Bicycle / Motorcycle</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="water">
													<i></i>Boating / Jet Ski / Kayak</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="golf">
													<i></i>Golf</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="swimming">
													<i></i>Swimming</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="sports" >
													<i></i>Softball / Soccer / Organized sports</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="water">
													<i></i>Running</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="hunting">
													<i></i>Hunting / Shooting</label>
												</div>
												<div class="col-sm-3">
													<label class="checkbox myRadio check-inline">
													<input type="checkbox" name="gaming">
													<i></i>Digital Gaming</label>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab5">
											<br>
											<div class="row">
												<div class="col-sm-12">
													<!-- <h2><strong>Contact Lens usage:</strong></h2> -->
													<h3><strong>Step 4</strong> - Contact Lens Usage</h3>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="contact_usage" value="hard">
													<i></i>Hard</label>
												</div>
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="contact_usage" value="soft_yearly">
													<i></i>Soft Yearly</label>
												</div>
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="contact_usage" value="soft_quarterly">
													<i></i>Soft Quarterly</label>
												</div>
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="contact_usage" value="soft_2_week">
													<i></i>Soft 2 Week</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="contact_usage" value="soft_daily">
													<i></i>Soft Daily</label>
												</div>
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="contact_usage" value="toric">
													<i></i>Toric</label>
												</div>
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="contact_usage" value="biofocal">
													<i></i>Bifocal</label>
												</div>
												<div class="col-sm-3">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="contact_usage" value="tinted">
													<i></i>Tinted</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<h2><strong>I wear my contacts?</strong></h2>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-2">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="wear_contacts" value="0 %">
													<i></i>0 %</label>
												</div>
												<div class="col-sm-2">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="wear_contacts" value="< 50 %">
													<i></i>< 50 %</label>
												</div>
												<div class="col-sm-2">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="wear_contacts" value="50 %">
													<i></i>50 %</label>
												</div>
												<div class="col-sm-2">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="wear_contacts" value="75 %">
													<i></i>75 %</label>
												</div>
												<div class="col-sm-2">
													<label class="radio myRadio radio-inline">
													<input type="radio" name="wear_contacts" value="90 % +">
													<i></i>90 % +</label>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<h2><strong>How many hours a day do you wear contacts?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-clock-o fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Hours a day?" type="text" name="hours_a_day" id="hours_a_day">
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<h2><strong>Sleep in your contacts?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-eye-slash fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="sleep_in_contacts">
																<option value="" selected="selected">Select option</option>
																<option value="Yes">Yes</option>
																<option value="No">No</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab6">
											<br>
											<!-- <h1>Eyeglasses and Lens Preferences</h1> -->
											<h3><strong>Step 5</strong> - Eyeglasses and Lens Preferences</h3>
											<div class="row">
												<div class="col-sm-6">
													<h2><strong>I prefer the Brand Name Eyeglasses?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-eye fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="brand_name">
																<option value="" selected="selected">Select option</option>
																<option value="A Lot">A Lot</option>
																<option value="A Little">A Little</option>
																<option value="No Preference">No Preference</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<h2><strong>I prefer these types of eyeglasses?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-eye fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="type_glasses">
																<option value="" selected="selected">Select option</option>
																<option value="Plastic">Plastic</option>
																<option value="Metal">Metal</option>
																<option value="Semi-Rimless">Semi-Rimless</option>
																<option value="Drill Mount Rimless">Drill Mount Rimless</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<h2><strong>Today I would like to see an eyeglass style:</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-check-square-o fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="eyeglass_style">
																<option value="" selected="selected">Select option</option>
																<option value="Trendy">Trendy and New</option>
																<option value="Similar">Similar to what I already have</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<h2><strong>I prefer the thin and lightweight lenses?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-check-square-o fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="thin_lenses">
																<option value="" selected="selected">Select option</option>
																<option value="Yes">Yes</option>
																<option value="No">No</option>
																<option value="Tell me more">Tell me more</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<h2><strong>I prefer the Anti-Reflective (A/R) Coating on my lenses?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-check-square-o fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="ar">
																<option value="" selected="selected">Select option</option>
																<option value="Yes">Yes</option>
																<option value="No">No</option>
																<option value="Tell me more">Tell me more</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<h2><strong>I prefer photo chromatic / transitions lenses?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-check-square-o fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="transition_lenses">
																<option value="" selected="selected">Select option</option>
																<option value="Yes">Yes</option>
																<option value="No">No</option>
																<option value="Tell me more">Tell me more</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<h2><strong>Tell me about the new high definition - digital Lenses?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-check-square-o fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="hd_lenses">
																<option value="" selected="selected">Select option</option>
																<option value="Yes">Yes</option>
																<option value="No">No</option>
																<option value="Tell me more">Tell me more</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<h2><strong>I have shopped on-line for glasses?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-check-square-o fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="online_shopping">
																<option value="" selected="selected">Select option</option>
																<option value="Yes">Yes</option>
																<option value="No">No</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<h2><strong>Are you interested in LASIK or refractive surgery?</strong></h2>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-eye fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="lasik">
																<option value="" selected="selected">Select option</option>
																<option value="Yes">Yes</option>
																<option value="No">No</option>
																<option value="Tell me more">Tell me more</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab7">
											<br>
											<h3><strong>Step 6</strong> - Save Form</h3>
											<br>
											<h1 class="text-center text-success"><strong><i class="fa fa-check fa-lg"></i> Complete</strong></h1>
											<h4 class="text-center">Click next to finish</h4>
											<br>
											<br>
										</div>

										<div class="form-actions">
											<div class="row">
												<div class="col-sm-12">
													<ul class="pager wizard no-margin">
														<!--<li class="previous first disabled">
														<a href="javascript:void(0);" class="btn btn-lg btn-default"> First </a>
														</li>-->
														<li class="previous disabled">
															<a href="javascript:void(0);" class="btn btn-lg btn-default"> Previous </a>
														</li>
														<!--<li class="next last">
														<a href="javascript:void(0);" class="btn btn-lg btn-primary"> Last </a>
														</li>-->
														<li class="next">
															<a href="javascript:void(0);" class="btn btn-lg txt-color-darken"> Next </a>
														</li>
													</ul>
												</div>
											</div>
										</div>

									</div>
								</div>
							</form>
						</div>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
	</div>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php
	//include required scripts
	include(__DIR__."/inc/scripts.php");
?>

<!-- PAGE RELATED PLUGIN(S)
<script src="..."></script>-->

<script type="text/javascript">
	pageSetUp();

	// PAGE RELATED SCRIPTS

	// pagefunction

	var pagefunction = function() {

		// load bootstrap wizard

		loadScript("../admin/js/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js", runBootstrapWizard);

		//Bootstrap Wizard Validations

		function runBootstrapWizard() {

			var $validator = $("#wizard-1").validate({

				rules : {
					email : {
						required : true,
						email : "Your email address must be in the format of name@domain.com"
					},
					email2 : {
						required : true,
						email : "Your email address must be in the format of name@domain.com",
						equalTo : "#email"
					},
					fname : {
						required : true
					},
					lname : {
						required : true
					},
					hphone : {
						required : true,
						minlength : 10,
						equalTo: '#wphone'
					},
					wphone : {
						required : true,
						minlength : 10,
						// equalTo : '#hphone'
					},
					vCode : {
						required :true,
						equalTo : '#hCode'
					},
					birth_date : {
						required : true
					},
					sos : {
						required : true,
						digits :true,
						minlength : 4
					},
					patient_type : {
						required : true
					},
					wear_contacts : {
						required : true
					},
					contact_usage : {
						required : true
					},
					vis_insu : {
						required : true
					},
					med_insu : {
						required : true
					}
				},

				messages : {
					fname : "Please specify your First name",
					lname : "Please specify your Last name",
					email : {
						required : "We need your email address to contact you",
						email : "Your email address must be in the format of name@domain.com"
					},
					email : {
						required : "We need your email address to contact you",
						email : "Your email address must be in the format of name@domain.com",
						equalTo: "Please give us the same email address"
					},
					vCode : {
						required : "We need to validate your Phone Number.",
						equalTo: "Please give us the same code sent to your phone"
					},
				},

				highlight : function(element) {
					$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
				},
				unhighlight : function(element) {
					$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
				},
				errorElement : 'span',
				errorClass : 'help-block',
				errorPlacement : function(error, element) {
					if (element.parent('.input-group').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
			});

			$('#bootstrap-wizard-1').bootstrapWizard({

				'tabClass' : 'form-wizard',
				'onNext' : function(tab, navigation, index) {
					var info = $('#wizard-1').serializeObject();
					console.log(info);
					var $valid = $("#wizard-1").valid();
					if (!$valid) {
						$validator.focusInvalid();
						return false;
					} else {
						$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
						$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
					}
				}
			});

		};

	};

	// end pagefunction

	// Load bootstrap wizard dependency then run pagefunction
	pagefunction();

	function valPhone()
	{
		var number = $('#hphone').val();
		var number2 = $('#wphone').val();
		var info = $('#wizard-1').serializeObject();
		// alert($('input[name="val-type"]:checked'));
		$('input[name="val-type"]:checked').each(function() {
		   // console.log(this.value);
		   // alert(this.value);
			if(this.value == "Voice"){
				alert("voice functionality has not been created");
			}

			if(this.value == "SMS"){
				if(number && number2 && number == number2){
					// sd=parseInt(number);
					var sd = number.replace(/[^0-9]/g, '');
//					$.post('/sendSMS/'+sd, info ,function(data, textStatus, xhr) {
//						// console.log(data);
//						$('#hCode').val(data.hCode);
//						$('#formId').val(data.formId);
//					},'json');
				}else{
					alert('Numbers are not filled out or the same.');
				}
			}
		});
	}

	$.fn.serializeObject = function()
	{
	    var o = {};
	    var a = this.serializeArray();
	    $.each(a, function() {
	        if (o[this.name] !== undefined) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    return o;
	};

	function getParameterByName(name, url) {
	    if (!url) url = window.location.href;
	    name = name.replace(/[\[\]]/g, "\\$&");
	    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	        results = regex.exec(url);
	    if (!results) return null;
	    if (!results[2]) return '';
	    return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

	$(document).ready(function() {
		$('#birth_date').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "1900:2016",
		});
		$('#ph1_bd').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "1900:2016",
		});
		$('#ph2_bd').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "1900:2016",
		});

		$('input[name=med_insu]').change(function () {
	        if ($(this).val() == 'yes') {
	            $('#holder1').show();
	            $('#holder2').show();
	        } else {
	            $('#holder1').hide();
	            $('#holder2').hide();
	        }
	        // alert($(this).val());
	    }).trigger('change');

	    $('input[name=vis_insu]').change(function () {
	        if ($(this).val() == 'yes') {
	            $('#holder3').show();
	            $('#holder4').show();
	        } else {
	            $('#holder3').hide();
	            $('#holder4').hide();
	        }
	        // alert($(this).val());
	    }).trigger('change');

	    var info = getParameterByName('info');
	    if(info)
	    {
	    	// alert(info);
	    	$.post('/function/getFormData/', {info:info}, function(data, textStatus, xhr) {
	    		console.log(data);
	    	});
	    }
	});
</script>

<?php
	//include footer
	include(__DIR__."/inc/google-analytics.php");
?>