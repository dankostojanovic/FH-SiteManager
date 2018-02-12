<?php
require_once("../init.php");
checkLogin();
require_once("inc/init.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$comCol = \rule\Map\CommunityTableMap::getTableMap()->getColumns();
$sectionLegaCol = rule\Map\CommunitySectionLegalTableMap::getTableMap()->getColumns();
$sectionCol = rule\Map\CommunitySectionTableMap::getTableMap()->getColumns();
$siteCol = \rule\Map\CommunitySiteTableMap::getTableMap()->getColumns();
$inclCol = \rule\Map\CommunitySiteInclFeatureTableMap::getTableMap()->getColumns();
$paCol = \rule\Map\CommunitySitePlanAvailabilityTableMap::getTableMap()->getColumns();
$pbiCol = \rule\Map\CommunitySitePlanBaseInvestTableMap::getTableMap()->getColumns();
$cpbiCol = \rule\Map\CommunityPlanBaseInvestmentTableMap::getTableMap()->getColumns();
// foreach ($paCol as $key => $c) {
// 	echo $key."<br />";
// }
?>
<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/handsontable.css">
<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/pikaday/pikaday.css">
<script data-jsfiddle="common" src="dist/pikaday/pikaday.js"></script>
<script data-jsfiddle="common" src="dist/moment/moment.js"></script>
<script data-jsfiddle="common" src="dist/zeroclipboard/ZeroClipboard.js"></script>
<script data-jsfiddle="common" src="dist/numbro/numbro.js"></script>
<script data-jsfiddle="common" src="dist/numbro/languages.js"></script>
<script data-jsfiddle="common" src="dist/handsontable.js"></script>

<?php 
// $coms = \rule\CommunityQuery::create()->filterByActive(1)->find();
$coms = \rule\CommunityQuery::create()->find();
?>

<!-- <div class="row">
	<div class="col-sm-4">
		<i class="fa fa-fw fa-lg fa-users"></i>
	</div>
	<div class="col-sm-4">
		<i class="fa fa-fw fa-lg fa-sitemap"></i>
		<i class="fa fa-fw fa-lg fa-map-pin"></i>
	</div>
	<div class="col-sm-4">
		<i class="fa fa-fw fa-lg fa-home"></i>
	</div>
</div> -->
<div class="row">
	<div class="col-sm-3">
		<div class="icon-addon addon-lg">
			<select class='form-control input-lg' id='communityId' name='communityId'>
			<?php 
				foreach ($coms as $key => $com) {
					// echo "<option value=\"".$com->getId()."\">".$com->getCode()." - ".$com->getName()."</option>";
					// echo "<option value=\"".$com->getId()."\">".$com->getCode()." - ".$com->getName()." Sec:".$com->countCommunitySections()." Sites:".$com->countCommunitySites()."</option>";
					echo "<option value=\"".$com->getId()."\">".$com->getCode()." Sections:".$com->countCommunitySections()." Legal:".$com->countCommunitySectionLegals()." PBI:".$com->countCommunityPlanBaseInvestments()." Sites:".$com->countCommunitySites()."</option>";
				}
			?>
			</select>
			<!-- <label for="communityId" class="fa fa-users" rel="tooltip" title="Community"></label> -->
			<label for="communityId" class="fa fa-sitemap" rel="tooltip" title="Community"></label>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="icon-addon addon-lg">
			<select class='form-control input-lg' id='secId' name='secId'><option value='0'>Select Section</option></select>
			<label for="secId" class="fa fa-map-pin" rel="tooltip" title="Sections"></label>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="icon-addon addon-lg">
			<select class='form-control input-lg' id='secLegaId' name='secLegaId'><option value='0'>Select Legal Section</option></select>
			<label for="secId" class="fa fa-map-pin" rel="tooltip" title="Sections"></label>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="icon-addon addon-lg">
			<select class='form-control input-lg' id='siteId' name='siteId'><option value='0'>Select Site</option></select>
			<label for="siteId" class="fa fa-home" rel="tooltip" title="Site"></label>
		</div>
	</div>
</div>
<br />
<ul id="myTab1" class="nav nav-tabs bordered">
	<li class="active">
		<a href="#s1" data-toggle="tab"> <i class="fa fa-fw fa-lg fa-users"></i> Community </a>
	</li>
	<li>
		<a href="#s2" data-toggle="tab"><i class="fa fa-fw fa-lg fa-sitemap"></i> Sections</a>
	</li>
	<li>
		<a href="#s8" data-toggle="tab"><i class="fa fa-fw fa-lg fa-sitemap"></i> Legal Sections</a>
	</li>
	<li>
		<a href="#s3" data-toggle="tab"><i class="fa fa-fw fa-lg fa-sitemap"></i> Plan Base Investments</a>
	</li>
	<!-- <li class="dropdown">
		<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-fw fa-lg fa-home"></i> Sites <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li>
				<a href="#s4" data-toggle="tab">Sites</a>
			</li>
			<li>
				<a href="#s5" data-toggle="tab">Site Incl Feature</a>
			</li>
			<li>
				<a href="#s6" data-toggle="tab">Site Plan Availablity</a>
			</li>
			<li>
				<a href="#s7" data-toggle="tab">Site Plan Base Invest</a>
			</li>
		</ul>
	</li> -->
	<li>
		<a href="#s4" data-toggle="tab"><i class="fa fa-fw fa-lg fa-map-pin"></i> Sites</a>
	</li>
	<li>
		<a href="#s5" data-toggle="tab"><i class="fa fa-fw fa-lg fa-home"></i> Site Incl Feature</a>
	</li>
	<li>
		<a href="#s6" data-toggle="tab"><i class="fa fa-fw fa-lg fa-home"></i> Site Plan Availablity</a>
	</li>
	<li>
		<a href="#s7" data-toggle="tab"><i class="fa fa-fw fa-lg fa-home"></i> Site Plan Base Invest</a>
	</li>
	<li class="pull-right">
		<a href="javascript:void(0);">
			<div class="sparkline txt-color-pinkDark text-align-right" data-sparkline-height="18px" data-sparkline-width="90px" data-sparkline-barwidth="7">
			<!-- could put summery here! -->
			</div> 
		</a>
	</li>
</ul>

<div id="myTabContent1" class="tab-content padding-10">
	<div class="tab-pane fade in active" id="s1">
		<!-- <h3>Community</h3> -->
		<button id="btnComLoad" class="btn btn-success">Load</button>
		Save on Change
		<span class="onoffswitch"> 
			<input type="checkbox" name="chComSave" id="chComSave" class="onoffswitch-checkbox" rel="tooltip" data-placement="left" data-original-title="Check Yes to make changes real time">
			<label class="onoffswitch-label" for="chComSave">
				<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
				<span class="onoffswitch-switch"></span> 
			</label> 
		</span>
		<div id="communityConsole" class="console">Click "Load" to load data from server</div>
		<div class="row">
			<div class="col-sm-2">
				<label class="input"> <!-- <i class="icon-append fa fa-users"></i> -->
					<input class="form-control" type="text" name="comName" id="comName" placeholder="Community Name">
				</label>
			</div>
			<div class="col-sm-2">
				<label class="input"> <!-- <i class="icon-append fa fa-pin"></i> -->
					<input class="form-control" type="text" name="comCity" id="comCity" placeholder="Community City">
				</label>
			</div>
			<div class="col-sm-2">
				<label class="input"> <!-- <i class="icon-append fa fa-pin"></i> -->
					<input class="form-control" type="text" name="comState" id="comState" placeholder="Community State">
				</label>
			</div>
		</div>
		<div id="hot-com" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: hidden; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s2">
		<!-- <h3>Community Sections</h3> -->
		<button id="btnSecLoad" class="btn btn-success">Load</button>
		Save on Change
		<span class="onoffswitch"> 
			<input type="checkbox" name="chSecSave" id="chSecSave" class="onoffswitch-checkbox">
			<label class="onoffswitch-label" for="chSecSave">
				<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
				<span class="onoffswitch-switch"></span> 
			</label> 
		</span>
		<div id="sectionConsole" class="console">Click "Load" to load data from server</div>
		<div class="row">
			<div class="col-sm-2">
				<label class="input"> <!-- <i class="icon-append fa fa-users"></i> -->
					<input class="form-control" type="text" name="secName" id="secName" placeholder="Section Name">
				</label>
			</div>
		</div>
		<div id="hot-section" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: hidden; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s8">
		<!-- <h3>Community Sections</h3> -->
		<button id="btnSecLegaLoad" class="btn btn-success">Load</button>
		Save on Change
		<span class="onoffswitch"> 
			<input type="checkbox" name="chSecLegaSave" id="chSecLegaSave" class="onoffswitch-checkbox">
			<label class="onoffswitch-label" for="chSecLegaSave">
				<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
				<span class="onoffswitch-switch"></span> 
			</label> 
		</span>
		<div id="sectionLegaConsole" class="console">Click "Load" to load data from server</div>
		<div class="row">
			<div class="col-sm-2">
				<label class="input"> <!-- <i class="icon-append fa fa-users"></i> -->
					<input class="form-control" type="text" name="legaSecName" id="legaSecName" placeholder="Legal Section Name">
				</label>
			</div>
		</div>
		<div id="hot-sectionLega" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: hidden; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s3">
		<button id="btnCpbiLoad" class="btn btn-success">Load</button>
		Save on Change
		<span class="onoffswitch"> 
			<input type="checkbox" name="chCpbiSave" id="chCpbiSave" class="onoffswitch-checkbox">
			<label class="onoffswitch-label" for="chCpbiSave">
				<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
				<span class="onoffswitch-switch"></span> 
			</label> 
		</span>
		<div id="cpbiConsole" class="console">Click "Load" to load data from server</div>
		<div id="hot-cpbi" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: hidden; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s4">
		<button id="btnSiteLoad" class="btn btn-success">Load</button>
		Save on Change
		<span class="onoffswitch"> 
			<input type="checkbox" name="chSiteSave" id="chSiteSave" class="onoffswitch-checkbox">
			<label class="onoffswitch-label" for="chSiteSave">
				<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
				<span class="onoffswitch-switch"></span> 
			</label> 
		</span>
		<div id="siteConsole" class="console">Click "Load" to load data from server</div>
		<div class="row">
			<div class="col-sm-2">
				<label class="input"> <!-- <i class="icon-append fa fa-users"></i> -->
					<input class="form-control" type="text" name="siteNumber" id="siteNumber" placeholder="Site Number">
				</label>
			</div>
			<div class="col-sm-2">
				<label class="input"> <!-- <i class="icon-append fa fa-users"></i> -->
					<input class="form-control" type="text" name="purchaser" id="purchaser" placeholder="Purchaser">
				</label>
			</div>
		</div>
		<div id="hot-site" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: hidden; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s5">
		<button id="btnInclLoad" class="btn btn-success">Load</button>
		Save on Change
		<span class="onoffswitch"> 
			<input type="checkbox" name="chInclSave" id="chInclSave" class="onoffswitch-checkbox">
			<label class="onoffswitch-label" for="chInclSave">
				<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
				<span class="onoffswitch-switch"></span> 
			</label> 
		</span>
		<div id="inclConsole" class="console">Click "Load" to load data from server</div>
		<div id="hot-incl" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: hidden; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s6">
		<button id="btnPaLoad" class="btn btn-success">Load</button>
		Save on Change
		<span class="onoffswitch"> 
			<input type="checkbox" name="chPaSave" id="chPaSave" class="onoffswitch-checkbox">
			<label class="onoffswitch-label" for="chPaSave">
				<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
				<span class="onoffswitch-switch"></span> 
			</label>
		</span>
		<div id="paConsole" class="console">Click "Load" to load data from server</div>
		<div id="hot-pa" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: hidden; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s7">
		<button id="btnPbiLoad" class="btn btn-success">Load</button>
		Save on Change
		<span class="onoffswitch"> 
			<input type="checkbox" name="chPbiSave" id="chPbiSave" class="onoffswitch-checkbox">
			<label class="onoffswitch-label" for="chPbiSave">
				<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
				<span class="onoffswitch-switch"></span> 
			</label>
		</span>
		<div id="pbiConsole" class="console">Click "Load" to load data from server</div>
		<div id="hot-pbi" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: hidden; width: 100%;"></div>
	</div>
</div>


<script type="text/javascript">
	// var ComData = [
	// 		{id: '1', code: '4B0', name: 'FOUR BRIDGES-CHERRY LAUREL', active:'0', divisionId:'7'},
	// 		{id: '2', code: 'AA0', name: 'ADENA AT MIAMI BLUFFS', active:'0', divisionId:'4'},
	// 	];
	// var ComData = [
	// 	{Id: '1', Code: '4B0', Name: 'FOUR BRIDGES-CHERRY LAUREL', Active:'0', DivisionId:'7'},
	// 	{Id: '2', Code: 'AA0', Name: 'ADENA AT MIAMI BLUFFS', Active:'0', DivisionId:'4'},
	// ];
	var ComData = [];
	var SectionData = [];
	var LegalSectionData = [];

	function getSectionOptions(){
		$.ajax({
			url: '/rule/community/getSections/',
			type: 'GET',
			dataType: 'json',
			data: { comId : $('#communityId').val(), secId: $('#secId').val()},
			headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'}
		})
		.done(function(data) {
			console.log("success");
			console.log(data.CommunitySections);
			// $('#secId').find('option').remove().end().append('<option value="0">Select Section</option>').val(0);
			// $('#secId').clear();
			$('#secId').empty();
			$('#secId').append('<option value="0">Select Section</option>');
			$.each(data.CommunitySections, function(index, val) {
				// $('#secId').append($('<option value"'+val.SectionId+'">'+val.SectionName+'</option>').val(val).html(text));
				$('#secId').append('<option value="'+val.SectionId+'">'+val.SectionName+' Sites:'+val.Count+'</option>');
			});
			$('#secId').val(0);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	function getSectionLegaOptions(){
		$.ajax({
			url: '/rule/community/getSectionLegals/',
			type: 'GET',
			dataType: 'json',
			data: { comId : $('#communityId').val()},
			headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'}
		})
		.done(function(data) {
			console.log("success");
			console.log(data.CommunitySections);
			// $('#secId').find('option').remove().end().append('<option value="0">Select Section</option>').val(0);
			// $('#secId').clear();
			$('#secLegaId').empty();
			$('#secLegaId').append('<option value="0">Select Legal Section</option>');
			$.each(data.CommunitySectionLegals, function(index, val) {
				$('#secLegaId').append('<option value="'+val.LegalSectionId+'">'+val.LegalSectionName+' Sites:'+val.Count+'</option>');
			});
			$('#secLegaId').val(0);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	function getSiteOptions(){
		filter = {filter:{}};
    	if($('#communityId').val() != 0){
    		filter.filter.communityId = {0:$('#communityId').val(), 1:" = "};
    	}
    	if($('#secId').val() != 0 ){
    		filter.filter.sectionId = {0:$('#secId').val(), 1:" = "};
    	}
    	if($('#secLegaId').val() != 0){
    		filter.filter.legalSectionId = {0:$('#secLegaId').val(), 1:" = "};
    	}
		$.ajax({
			url: '/rule/community/getSites/',
			type: 'GET',
			dataType: 'json',
			// data: { comId : $('#communityId').val(), secId: $('#secId').val(), secLegaId: $('#secLegaId').val()},
			data: filter,
			headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'}
		})
		.done(function(data) {
			console.log("success");
			console.log(data.CommunitySites);
			// $('#secId').find('option').remove().end().append('<option value="0">Select Section</option>').val(0);
			// $('#secId').clear();
			$('#siteId').empty();
			$('#siteId').append('<option value="0">Select Site</option>');
			$.each(data.CommunitySites, function(index, val) {
				// $('#secId').append($('<option value"'+val.SectionId+'">'+val.SectionName+'</option>').val(val).html(text));
				$('#siteId').append('<option value="'+val.SiteId+'">['+val.SiteNumber+'] '+val.cIncl+' '+val.cPlanAvail+' '+val.cBasePlan+' ('+val.Purchaser+')</option>');
			});
			$('#siteId').val(0);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	$('#communityId').change(function(event) {
		getSectionOptions();
		getSiteOptions();
		getSectionLegaOptions();
		$('#btnSecLoad').click();
		$('#btnSecLegaLoad').click();
		$('#btnSiteLoad').click();
		$('#btnCpbiLoad').click();
	});

	$('#secId').change(function(event) {
		getSiteOptions();
		$('#btnSecLoad').click();
		$('#btnSiteLoad').click();
	});

	$('#secLegaId').change(function(event) {
		getSiteOptions();
		$('#btnSecLegaLoad').click();
		$('#btnSiteLoad').click();
	});

	$('#siteId').change(function(event) {
		$('#btnInclLoad').click();
		$('#btnPaLoad').click();
		$('#btnPbiLoad').click();
	});

		/*Start Community Grid*/
		var
			$container = $("#hot-com"),
			$console = $("#communityConsole"),
			// $parent = $container.parent(),
			autosaveNotification,
			// searchFiled = document.getElementById('search_field'),
			hotcom;

	        hotcom = new Handsontable($container[0], {
				columnSorting: true,
				startRows: 20,
				startCols: 12,
				rowHeaders: true,
				colHeaders:[
					<?php 
					foreach ($comCol as $key => $c) {
						echo "'".$c->getName()."',";
					}
					?>
				],
				columns:[
					<?php 
					foreach ($comCol as $key => $c) {
						if($key == 'ID'){
							echo "{data:'".$c->getPhpName()."', editor:false },";
						}else{
							echo "{data:'".$c->getPhpName()."'},";
						}
					}
					?>
				],
				
				minSpareCols: 0,
				minSpareRows: 1,
				contextMenu: true,
				// fixedRowsTop: 1,
				fixedColumnsLeft: 3,
				search: true,
				// manualColumnMove: true,
    //             manualRowMove: true,
    //             manualColumnResize: true,
    //             manualRowResize: true,
				// observeChanges: true,
	        	afterChange: function (change, source) {
		            var data;

		            // if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
		            if (source === 'loadData' || !$('#chComSave').is(':checked') ) {
		    			// $.bigBox({
						// 	title : "You're not editing anything!",
						// 	content : "Please check the box!",
						// 	color : "#EA5939",
						// 	timeout: 3000,
						// 	icon : "fa fa-exclamation fadeInLeft animated",
						// });
		              return;
		            }

		            $.each(change, function(index, val) {
		            	// instead of giving me the column I need the record id
		            	var tmp = hotcom.getDataAtRow(val[0]);  // gets me the row data
		            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
		            });

		            clearTimeout(autosaveNotification);
					$.ajax({
						url: '/rule/save/community/',
						headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
						dataType: 'json',
						type: 'POST',
						data: {changes: change}, // contains changed cells' data
						success: function () {
							$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
							autosaveNotification = setTimeout(function () {
								$console.text('Changes will be autosaved');
							}, 1000);
						}
					});
	        	}
	        });

	        $('#btnComLoad').click(function(event) {
	        	filter = {filter:{}};
	        	if($('#comName').val()){
	        		filter.filter.Name = {0:$('#comName').val(), 1:" rlike "};
	        	}
	        	if($('#comState').val()){
	        		filter.filter.State = {0:$('#comState').val(), 1:" = "};
	        	}
	        	if($('#comCity').val()){
	        		filter.filter.City = {0:$('#comCity').val(), 1:" rlike "};
	        	}
	        	$.ajax({
					url: '/rule/community/?page=1&rows=600',
					headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
					dataType: 'json',
					// data: {filter:{"Name":{0:"creek",1:" rlike "}}},
					data: filter,
					type: 'GET',
					success: function (res) {
						// console.log(res.rows);
						hotcom.loadData(res.rows);
						// ComData = res.rows;
						ComData = [];
						$.each(res.rows, function(index, val) {
							// console.log(val.Id,val.Code,val.Name,val.Active,val.DivisionId);
							ComData.push( { Id:val.Id, Code:val.Code, Name:val.Name, Active:val.Active, DivisionId:val.DivisionId } );
						});
					}
				});
	        });
    	/*Ends Community*/

		/*Starts Section*/
		var
			$container2 = $("#hot-section"),
			$console2 = $("#sectionConsole"),
			// $parent = $container.parent(),
			autosaveNotification,
			// searchFiled = document.getElementById('search_field'),
			hotsec;

			function hotsecCreate(){
				// console.log(ComData);
		        hotsec = new Handsontable($container2[0], {
					columnSorting: true,
					startRows: 20,
					startCols: 12,
					rowHeaders: true,
					colHeaders:[
						<?php 
						foreach ($sectionCol as $key => $c) {
							echo "'".$c->getName()."',";
						}
						?>
					],
					columns:[
						<?php 
						foreach ($sectionCol as $key => $c) {
							if($key == 'SECTION_ID'){
								echo "{data:'".$c->getPhpName()."', editor:false },";
							}else if ($key == 'COMMUNITY_ID'){
								echo "{
										data:'".$c->getPhpName()."',
										type: 'handsontable',
										handsontable: {
											colHeaders: ['Id', 'Code', 'Name', 'Active', 'Division Id'],
											autoColumnSize: true,
											data: ComData,
											getValue: function() {
												var selection = this.getSelected();

												// Get always manufacture name of clicked row
												return this.getSourceDataAtRow(selection[0]).Id;
											},
										},
									},";
							}else{
								echo "{data:'".$c->getPhpName()."'},";	
							}
						}
						?>
					],
					
					minSpareCols: 0,
					minSpareRows: 1,
					contextMenu: true,
					// fixedRowsTop: 1,
					fixedColumnsLeft: 3,
					search: true,
					// observeChanges: true,
		        	afterChange: function (change, source) {
			            var data;

			            // if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
			            if (source === 'loadData' || !$('#chSecSave').is(':checked') ) {
			            	/*$.bigBox({
								title : "AutoSave Not Checked!",
								content : "Please check YES if you want to change data!",
								// color : "#EA5939",
								color : "blue",
								timeout: 3000,
								icon : "fa fa-exclamation fadeInLeft animated",
							});*/
			            	return;
			            }

			            $.each(change, function(index, val) {
			            	// instead of giving me the column I need the record id
			            	var tmp = hotsec.getDataAtRow(val[0]);  // gets me the row data
			            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
			            	if(!tmp[0]){
			            		// console.log('id = zero');
			            		// change[index]['communityId'] = $('#communityId').val();
			            		// change[index]['sectionId'] = $('#secId').val();
			            		change[index][0] = -1;
			            		if($('#communityId').val() != 0){
			            			change[index][5] = $('#communityId').val();	
			            		}else{
			            			// notify user to select community.
			            			$.bigBox({
										title : "Select Community!",
										content : "Please select Section!",
										color : "#EA5939",
										timeout: 3000,
										icon : "fa fa-exclamation fadeInLeft animated",
									});
			            		}
			            	}
			            });

			            clearTimeout(autosaveNotification);
						$.ajax({
							url: '/rule/save/communitySection/',
							headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
							dataType: 'json',
							type: 'POST',
							data: {changes: change}, // contains changed cells' data
							success: function () {
								$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
								autosaveNotification = setTimeout(function () {
									$console.text('Changes will be autosaved');
								}, 1000);
							}
						});
		        	}
		        });
	    	}

	    	hotsecCreate();

	        $('#btnSecLoad').click(function(event) {
	        	filter = {filter:{}};
	        	filter.filter.CommunityId = { 0:$('#communityId').val(), 1:' = '};
	        	if($('#secName').val()){
	        		filter.filter.SectionName = { 0:$('#secName').val(), 1:' rlike '}
	        	}
	        	hotsec.destroy();
	        	hotsecCreate();
	        	$.ajax({
					url: '/rule/communitySection/?page=1&rows=600',
					headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
					dataType: 'json',
					type: 'GET',
					// data: {comId:$('#communityId').val()},
					// data: {
					// 	filter: {
					// 		"CommunityId": { 0:$('#communityId').val(), 1:' = '}
					// 	}
					// },
					data: filter,
					success: function (res) {
						// console.log(res.rows);
						if(res.rows){
							hotsec.loadData(res.rows);
							SectionData = [];
							$.each(res.rows, function(index, val) {
								SectionData.push( { Id:val.SectionId, Name:val.SectionName } );
							});
						}else {
							$('#chSecSave').attr('checked', false);
							SectionData = [];
							hotsec.clear();
						}
					}
				});
	        });
    	/*Ends Section*/

    	/*Starts Legal Section*/
		var
			$container8 = $("#hot-sectionLega"),
			$console8 = $("#sectionLegaConsole"),
			// $parent = $container.parent(),
			autosaveNotification,
			// searchFiled = document.getElementById('search_field'),
			hotsecLega;

			function hotsecLegaCreate(){
		        hotsecLega = new Handsontable($container8[0], {
					columnSorting: true,
					startRows: 20,
					startCols: 12,
					rowHeaders: true,
					colHeaders:[
						<?php 
						foreach ($sectionLegaCol as $key => $c) {
							echo "'".$c->getName()."',";
						}
						?>
					],
					columns:[
						<?php 
						foreach ($sectionLegaCol as $key => $c) {
							if($key == 'LEGAL_SECTION_ID'){
								echo "{data:'".$c->getPhpName()."', editor:false },";
							}else if ($key == 'COMMUNITY_ID'){
								echo "{
										data:'".$c->getPhpName()."',
										type: 'handsontable',
										handsontable: {
											colHeaders: ['Id', 'Code', 'Name', 'Active', 'Division Id'],
											autoColumnSize: true,
											data: ComData,
											getValue: function() {
												var selection = this.getSelected();

												// Get always manufacture name of clicked row
												return this.getSourceDataAtRow(selection[0]).Id;
											},
										},
									},";
							}else{
								echo "{data:'".$c->getPhpName()."'},";	
							}
						}
						?>
					],
					
					minSpareCols: 0,
					minSpareRows: 1,
					contextMenu: true,
					// fixedRowsTop: 1,
					fixedColumnsLeft: 3,
					search: true,
					// observeChanges: true,
		        	afterChange: function (change, source) {
			            var data;

			            // if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
			            if (source === 'loadData' || !$('#chSecLegaSave').is(':checked') ) {
			            	/*$.bigBox({
								title : "AutoSave Not Checked!",
								content : "Please check YES if you want to change data!",
								// color : "#EA5939",
								color : "blue",
								timeout: 3000,
								icon : "fa fa-exclamation fadeInLeft animated",
							});*/
			            	return;
			            }

			            $.each(change, function(index, val) {
			            	// instead of giving me the column I need the record id
			            	var tmp = hotsecLega.getDataAtRow(val[0]);  // gets me the row data
			            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
			            	if(!tmp[0]){
			            		// console.log('id = zero');
			            		// change[index]['communityId'] = $('#communityId').val();
			            		// change[index]['sectionId'] = $('#secId').val();
			            		change[index][0] = -1;
			            		if($('#communityId').val() != 0){
			            			change[index][5] = $('#communityId').val();	
			            		}else{
			            			// notify user to select community.
			            			$.bigBox({
										title : "Select Community!",
										content : "Please select Section!",
										color : "#EA5939",
										timeout: 3000,
										icon : "fa fa-exclamation fadeInLeft animated",
									});
			            		}
			            	}
			            });

			            clearTimeout(autosaveNotification);
						$.ajax({
							url: '/rule/save/communitySectionLega/',
							headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
							dataType: 'json',
							type: 'POST',
							data: {changes: change}, // contains changed cells' data
							success: function () {
								$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
								autosaveNotification = setTimeout(function () {
									$console.text('Changes will be autosaved');
								}, 1000);
							}
						});
		        	}
		        });
	    	}

	    	hotsecLegaCreate();

	        $('#btnSecLegaLoad').click(function(event) {
	        	filter = {filter:{}};
	        	filter.filter.CommunityId = { 0:$('#communityId').val(), 1:' = '};
	        	if($('#legaSecName').val()){
	        		filter.filter.LegalSectionName = { 0:$('#legaSecName').val(), 1:' rlike '}
	        	}
	        	hotsecLega.destroy();
	        	hotsecLegaCreate();
	        	$.ajax({
					url: '/rule/communitySectionLega/?page=1&rows=600',
					headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
					dataType: 'json',
					type: 'GET',
					// data: {comId:$('#communityId').val()},
					data: filter,
					success: function (res) {
						// console.log(res.rows);
						if(res.rows){
							hotsecLega.loadData(res.rows);
							LegalSectionData = [];
							$.each(res.rows, function(index, val) {
								LegalSectionData.push( { Id:val.LegalSectionId, Name:val.LegalSectionName } );
							});
						}else {
							$('#chSecLegaSave').attr('checked', false);
							hotsecLega.clear();
							LegalSectionData = [];
						}
					}
				});
	        });
    	/*Ends Legal Section*/

        /*Starts Site*/
    	var
			$container3 = $("#hot-site"),
			$console3 = $("#siteConsole"),
			// $parent = $container.parent(),
			autosaveNotification,
			// searchFiled = document.getElementById('search_field'),
			hotsite;

			function hotsiteCreate(){
		        hotsite = new Handsontable($container3[0], {
					columnSorting: true,
					startRows: 20,
					startCols: 12,
					rowHeaders: true,
					colHeaders:[
						<?php 
						foreach ($siteCol as $key => $c) {
							echo "'".$c->getName()."',";
						}
						?>
					],
					columns:[
						<?php 
						foreach ($siteCol as $key => $c) {
							if($key == 'SITE_ID'){
								echo "{data:'".$c->getPhpName()."', editor:false },";
							}else if ($key == 'COMMUNITY_ID'){
								echo "{
										data:'".$c->getPhpName()."',
										type: 'handsontable',
										handsontable: {
											colHeaders: ['Id', 'Code', 'Name', 'Active', 'Division Id'],
											autoColumnSize: true,
											data: ComData,
											getValue: function() {
												var selection = this.getSelected();

												// Get always manufacture name of clicked row
												return this.getSourceDataAtRow(selection[0]).Id;
											},
										},
									},";
							}else if ($key == 'SECTION_ID'){
								echo "{
										data:'".$c->getPhpName()."',
										type: 'handsontable',
										handsontable: {
											colHeaders: ['Id', 'Name'],
											autoColumnSize: true,
											data: SectionData,
											getValue: function() {
												var selection = this.getSelected();

												// Get always manufacture name of clicked row
												return this.getSourceDataAtRow(selection[0]).Id;
											},
										},
									},";
							}else if ($key == 'LEGAL_SECTION_ID'){
								echo "{
										data:'".$c->getPhpName()."',
										type: 'handsontable',
										handsontable: {
											colHeaders: ['Id', 'Name'],
											autoColumnSize: true,
											data: LegalSectionData,
											getValue: function() {
												var selection = this.getSelected();

												// Get always manufacture name of clicked row
												return this.getSourceDataAtRow(selection[0]).Id;
											},
										},
									},";
							}else{
								echo "{data:'".$c->getPhpName()."'},";	
							}
						}
						?>
					],
					
					minSpareCols: 0,
					minSpareRows: 1,
					contextMenu: true,
					// fixedRowsTop: 1,
					fixedColumnsLeft: 3,
					search: true,
					dropdownMenu: true,
					filters: true,
					// observeChanges: true,
		        	afterChange: function (change, source) {
			            var data;

			            // if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
			            if (source === 'loadData' || !$('#chSiteSave').is(':checked') ) {
			              return;
			            }

			            $.each(change, function(index, val) {
			            	// instead of giving me the column I need the record id
			            	var tmp = hotsite.getDataAtRow(val[0]);  // gets me the row data
			            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
			            	if(!tmp[0]){
			            		// console.log('id = zero');
			            		// change[index]['communityId'] = $('#communityId').val();
			            		// change[index]['sectionId'] = $('#secId').val();
			            		change[index][0] = -1;
			            		if($('#communityId').val() != 0){
			            			change[index][5] = $('#communityId').val();	
			            		}else{
			            			// notify user to select community.
			            			// return;
			            		}

			            		if($('#secId').val() != 0){
			            			change[index][6] = $('#secId').val();
			            		}else{
			            			// notify user to select Section
			            			$.bigBox({
										title : "Select Section!",
										content : "Please select Section!",
										color : "#EA5939",
										timeout: 3000,
										icon : "fa fa-exclamation fadeInLeft animated",
									});
									return;
			            		}

			            		if($('#secLegaId').val() != 0){
			            			change[index][7] = $('#secLegaId').val();
			            		}else{
			            			// notify user to select Section
			            			$.bigBox({
										title : "Select Legal Section!",
										content : "Please select Legal Section!",
										color : "#EA5939",
										timeout: 3000,
										icon : "fa fa-exclamation fadeInLeft animated",
									});
									return;
			            		}
			            		// change[index][7] = hotsite.getDataAtRow(0);
			            	}
			            	// console.log(change);
			            });

			            clearTimeout(autosaveNotification);
						console.log(change);
						$.ajax({
							url: '/rule/save/communitySite/',
							headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
							dataType: 'json',
							type: 'POST',
							data: {changes: change}, // contains changed cells' data
							success: function () {
								$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
								autosaveNotification = setTimeout(function () {
									$console.text('Changes will be autosaved');
								}, 1000);
							}
						});
		        	}
		        });
		    }

		    hotsiteCreate();

	        $('#btnSiteLoad').click(function(event) {
	        	filter = {filter:{}};
	        	filter.filter.CommunityId = { 0:$('#communityId').val(), 1:" = "};
	        	if($('#secId').val() != 0){
	        		filter.filter.SectionId = { 0:$('#secId').val(), 1:" = "}
	        	}
	        	if($('#secLegaId').val() != 0 ){
	        		filter.filter.LegalSectionId = { 0:$('#secLegaId').val(), 1:" = "}
	        	}
	        	if($('#siteNumber').val()){
	        		filter.filter.SiteNumber = { 0:$('#siteNumber').val(), 1:" rlike "}
	        	}
	        	if($('#purchaser').val()){
	        		filter.filter.Purchaser = { 0:$('#purchaser').val(), 1:" rlike "}
	        	}
	        	hotsite.destroy();
	        	hotsiteCreate();
	        	$.ajax({
					url: '/rule/communitySite/?page=1&rows=600',
					headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
					dataType: 'json',
					type: 'GET',
					// data: {comId:$('#communityId').val(), secId: $('#secId').val(), secLegaId: $('#secLegaId').val()},
					data: filter,
					success: function (res) {
						// console.log(res.rows);
						if(res.rows){
							hotsite.loadData(res.rows);
						}else{
							$('#chSiteSave').attr('checked', false);
							hotsite.clear();
						}
					}
				});
	        });
        /*Ends Site*/

        /*Start Site Incl Features*/
        var
			$container4 = $("#hot-incl"),
			$console4 = $("#inclConsole"),
			// $parent = $container.parent(),
			autosaveNotification,
			// searchFiled = document.getElementById('search_field'),
			hotincl;

	        hotincl = new Handsontable($container4[0], {
				columnSorting: true,
				startRows: 20,
				startCols: 12,
				rowHeaders: true,
				colHeaders:[
					<?php 
					foreach ($inclCol as $key => $c) {
						echo "'".$c->getName()."',";
					}
					?>
				],
				columns:[
					<?php 
					foreach ($inclCol as $key => $c) {
						if($key == 'SITE_INCL_ID'){
							echo "{data:'".$c->getPhpName()."', editor:false },";
						}else{
							echo "{data:'".$c->getPhpName()."'},";	
						}
					}
					?>
				],
				
				minSpareCols: 0,
				minSpareRows: 1,
				contextMenu: true,
				// fixedRowsTop: 1,
				fixedColumnsLeft: 3,
				search: true,
				dropdownMenu: true,
				filters: true,
				// observeChanges: true,
	        	afterChange: function (change, source) {
		            var data;

		            // if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
		            if (source === 'loadData' || !$('#chInclSave').is(':checked') ) {
		              return;
		            }

		            $.each(change, function(index, val) {
		            	// instead of giving me the column I need the record id
		            	var tmp = hotincl.getDataAtRow(val[0]);  // gets me the row data
		            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
		            	if(!tmp[0]){
		            		change[index][0] = -1;
		            		change[index][5] = $('#siteId').val();
		            	}
		            	// console.log(change);
		            });

		            clearTimeout(autosaveNotification);
					console.log(change);
					$.ajax({
						url: '/rule/save/communitySiteInclFeatures/',
						headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
						dataType: 'json',
						type: 'POST',
						data: {changes: change}, // contains changed cells' data
						success: function () {
							$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
							autosaveNotification = setTimeout(function () {
								$console.text('Changes will be autosaved');
							}, 1000);
						}
					});
	        	}
	        });

	        $('#btnInclLoad').click(function(event) {
	        	$.ajax({
					url: '/rule/communitySiteInclFeatures/',
					headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
					dataType: 'json',
					type: 'GET',
					data: {siteId:$('#siteId').val()},
					success: function (res) {
						// console.log(res.CommunitySiteInclFeatures);
						if(res.CommunitySiteInclFeatures){
							hotincl.loadData(res.CommunitySiteInclFeatures);
							// hotincl.loadData(res);
						}else{
							alert("uncheck should happen");
							$('#chInclSave').attr('checked', false);
							hotincl.clear();
						}
					}
				});
	        });
        /*End Site Incl Features*/

        /*Start Plan Availability */
        var
			$container5 = $("#hot-pa"),
			$console5 = $("#paConsole"),
			// $parent = $container.parent(),
			autosaveNotification,
			// searchFiled = document.getElementById('search_field'),
			hotpa;

	        hotpa = new Handsontable($container5[0], {
				columnSorting: true,
				startRows: 20,
				startCols: 12,
				rowHeaders: true,
				colHeaders:[
					<?php 
					foreach ($paCol as $key => $c) {
						echo "'".$c->getName()."',";
					}
					?>
				],
				columns:[
					<?php 
					foreach ($paCol as $key => $c) {
						if($key == 'SITE_PLAN_AV_ID'){
							echo "{data:'".$c->getPhpName()."', editor:false },";
						}else{
							echo "{data:'".$c->getPhpName()."'},";	
						}
					}
					?>
				],
				
				minSpareCols: 0,
				minSpareRows: 1,
				contextMenu: true,
				// fixedRowsTop: 1,
				fixedColumnsLeft: 3,
				search: true,
				dropdownMenu: true,
				filters: true,
				// observeChanges: true,
	        	afterChange: function (change, source) {
		            var data;

		            // if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
		            if (source === 'loadData' || !$('#chPaSave').is(':checked') ) {
		              return;
		            }

		            $.each(change, function(index, val) {
		            	// instead of giving me the column I need the record id
		            	var tmp = hotpa.getDataAtRow(val[0]);  // gets me the row data
		            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
		            	if(!tmp[0]){
		            		change[index][0] = -1;
		            		change[index][5] = $('#siteId').val();
		            	}
		            	// console.log(change);
		            });

		            clearTimeout(autosaveNotification);
					console.log(change);
					$.ajax({
						url: '/rule/save/communitySitePlanAvailability/',
						headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
						dataType: 'json',
						type: 'POST',
						data: {changes: change}, // contains changed cells' data
						success: function () {
							$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
							autosaveNotification = setTimeout(function () {
								$console.text('Changes will be autosaved');
							}, 1000);
						}
					});
	        	}
	        });

	        $('#btnPaLoad').click(function(event) {
	        	$.ajax({
					url: '/rule/communitySitePlanAvailability/',
					headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
					dataType: 'json',
					type: 'GET',
					data: {siteId:$('#siteId').val()},
					success: function (res) {
						// console.log(res.CommunitySitePlanAvailabilities);
						if(res.CommunitySitePlanAvailabilities){
							hotpa.loadData(res.CommunitySitePlanAvailabilities);
							// hotincl.loadData(res);
						}else{
							alert("uncheck should happen");
							$('#chPaSave').attr('checked', false);
							hotpa.clear();
						}
					}
				});
	        });
	    /*End Plan Availability */

	    /* Start Site Base Investment */
	    var
			$container6 = $("#hot-pbi"),
			$console6 = $("#pbiConsole"),
			// $parent = $container.parent(),
			autosaveNotification,
			// searchFiled = document.getElementById('search_field'),
			hotpbi;

	        hotpbi = new Handsontable($container6[0], {
				columnSorting: true,
				startRows: 20,
				startCols: 12,
				rowHeaders: true,
				colHeaders:[
					<?php 
					foreach ($pbiCol as $key => $c) {
						echo "'".$c->getName()."',";
					}
					?>
				],
				columns:[
					<?php 
					foreach ($pbiCol as $key => $c) {
						if($key == 'SPBI_ID'){
							echo "{data:'".$c->getPhpName()."', editor:false },";
						}else{
							echo "{data:'".$c->getPhpName()."'},";
						}
					}
					?>
				],
				
				minSpareCols: 0,
				minSpareRows: 1,
				contextMenu: true,
				// fixedRowsTop: 1,
				fixedColumnsLeft: 3,
				search: true,
				dropdownMenu: true,
				filters: true,
				// observeChanges: true,
	        	afterChange: function (change, source) {
		            var data;

		            // if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
		            if (source === 'loadData' || !$('#chPbiSave').is(':checked') ) {
		              return;
		            }

		            $.each(change, function(index, val) {
		            	// instead of giving me the column I need the record id
		            	var tmp = hotpbi.getDataAtRow(val[0]);  // gets me the row data
		            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
		            	if(!tmp[0]){
		            		change[index][0] = -1;
		            		change[index][5] = $('#siteId').val();
		            	}
		            	// console.log(change);
		            });

		            clearTimeout(autosaveNotification);
					console.log(change);
					$.ajax({
						url: '/rule/save/communitySitePlanBaseInvest/',
						headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
						dataType: 'json',
						type: 'POST',
						data: {changes: change}, // contains changed cells' data
						success: function () {
							$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
							autosaveNotification = setTimeout(function () {
								$console.text('Changes will be autosaved');
							}, 1000);
						}
					});
	        	}
	        });

	        $('#btnPbiLoad').click(function(event) {
	        	$.ajax({
					url: '/rule/communitySitePlanBaseInvest/',
					headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
					dataType: 'json',
					type: 'GET',
					data: {siteId:$('#siteId').val()},
					success: function (res) {
						// console.log(res.CommunitySitePlanBaseInvests);
						if(res.CommunitySitePlanBaseInvests){
							hotpbi.loadData(res.CommunitySitePlanBaseInvests);
							// hotincl.loadData(res);
						}else{
							alert("uncheck should happen");
							$('#chPbiSave').attr('checked', false);
							hotpbi.clear();
						}
					}
				});
	        });

	    /*Start Community Plan Base Investments Grid*/
	    var
			$container7 = $("#hot-cpbi"),
			$console7 = $("#cpbiConsole"),
			// $parent = $container.parent(),
			autosaveNotification,
			// searchFiled = document.getElementById('search_field'),
			hotcpbi;

			function hotcpbiCreate(){
		        hotcpbi = new Handsontable($container7[0], {
					columnSorting: true,
					startRows: 20,
					startCols: 12,
					rowHeaders: true,
					colHeaders:[
						<?php 
						foreach ($cpbiCol as $key => $c) {
							echo "'".$c->getName()."',";
						}
						?>
					],
					columns:[
						<?php 
						foreach ($cpbiCol as $key => $c) {
							if($key == 'PLAN_BASE_INV_ID'){
								echo "{data:'".$c->getPhpName()."', editor:false },";
							}else if ($key == 'COMMUNITY_ID'){
								echo "{
										data:'".$c->getPhpName()."',
										type: 'handsontable',
										handsontable: {
											colHeaders: ['Id', 'Code', 'Name', 'Active', 'Division Id'],
											autoColumnSize: true,
											data: ComData,
											getValue: function() {
												var selection = this.getSelected();

												// Get always manufacture name of clicked row
												return this.getSourceDataAtRow(selection[0]).Id;
											},
										},
									},";
							}else if ($key == 'SECTION_ID'){
								echo "{
										data:'".$c->getPhpName()."',
										type: 'handsontable',
										handsontable: {
											colHeaders: ['Id', 'Name'],
											autoColumnSize: true,
											data: SectionData,
											getValue: function() {
												var selection = this.getSelected();

												// Get always manufacture name of clicked row
												return this.getSourceDataAtRow(selection[0]).Id;
											},
										},
									},";
							}else{
								echo "{data:'".$c->getPhpName()."'},";	
							}
						}
						?>
					],
					
					minSpareCols: 0,
					minSpareRows: 1,
					contextMenu: true,
					// fixedRowsTop: 1,
					fixedColumnsLeft: 3,
					search: true,
					dropdownMenu: true,
					filters: true,
					// observeChanges: true,
		        	afterChange: function (change, source) {
			            var data;

			            // if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
			            if (source === 'loadData' || !$('#chCpbiSave').is(':checked') ) {
			              return;
			            }

			            $.each(change, function(index, val) {
			            	// instead of giving me the column I need the record id
			            	var tmp = hotcpbi.getDataAtRow(val[0]);  // gets me the row data
			            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
			            	if(!tmp[0]){
			            		change[index][0] = -1;
			            		change[index][5] = $('#communityId').val();
			            		change[index][6] = $('#secId').val();
			            	}
			            	// console.log(change);
			            });

			            clearTimeout(autosaveNotification);
						console.log(change);
						$.ajax({
							url: '/rule/save/communityPlanBaseInvestment/',
							headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
							dataType: 'json',
							type: 'POST',
							data: {changes: change}, // contains changed cells' data
							success: function () {
								$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
								autosaveNotification = setTimeout(function () {
									$console.text('Changes will be autosaved');
								}, 1000);
							}
						});
		        	}
		        });
		    }

		    hotcpbiCreate();

	        $('#btnCpbiLoad').click(function(event) {
	        	hotcpbi.destroy();
	        	hotcpbiCreate();
	        	$.ajax({
					url: '/rule/communityPlanBaseInvestments/',
					headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
					dataType: 'json',
					type: 'GET',
					data: {comId:$('#communityId').val()},
					success: function (res) {
						// console.log(res.CommunitySitePlanBaseInvests);
						if(res.CommunityPlanBaseInvestments){
							hotcpbi.loadData(res.CommunityPlanBaseInvestments);
							// hotincl.loadData(res);
						}else{
							// alert("uncheck should happen");
							$('#chCpbiSave').attr('checked', false);
							hotcpbi.clear();
						}
					}
				});
	        });


        $(document).ready(function() {
        	$('#btnComLoad').click();
        	$('#btnSecLoad').click();
        	$('#btnSecLegaLoad').click();
        	$('#btnSiteLoad').click();

        	getSiteOptions();
        	getSectionOptions();
        	getSectionLegaOptions();

        	$('#comName').on('keyup', function(event) {
				event.preventDefault();
				$('#btnComLoad').click();
			});

        	$('#comCity').on('keyup', function(event) {
				event.preventDefault();
				$('#btnComLoad').click();
			});

        	$('#comState').on('keyup', function(event) {
				event.preventDefault();
				$('#btnComLoad').click();
			});

			$('#secName').on('keyup', function(event) {
				event.preventDefault();
				$('#btnSecLoad').click();
			});

			$('#legaSecName').on('keyup', function(event) {
				event.preventDefault();
				$('#btnSecLegaLoad').click();
			});

			$('#siteNumber').on('keyup', function(event) {
				event.preventDefault();
				$('#btnSiteLoad').click();
			});

			$('#purchaser').on('keyup', function(event) {
				event.preventDefault();
				$('#btnSiteLoad').click();
			});
        });
</script>