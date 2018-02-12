<?php
require_once("../init.php");
checkLogin();
require_once("inc/init.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-eye"></i> 
				Sapphire Community
			<span>>  
				Data View
			</span>
		</h1>
	</div>
	<!-- end col -->
	
	<!-- right side of the page with the sparkline graphs -->
	<!-- col -->
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<!-- <button id="btnHideColumns" name="btnHideColumns" onclick="hideMostColumns()" class="btn btn-success"> Hide Most Columns</button> -->
		<div class="btn-group">
			<button type="button" class="btn btn-success" onclick="javascript:hideMostColumns();">
				Hide Most Columns
			</button>
			<button type="button" class="btn btn-primary" onclick="javascript:showColumns();">
				Unhide all
			</button>
		</div>
	</div>
	<!-- end col -->
	
</div>
<!-- end row -->

<!--
	The ID "widget-grid" will start to initialize all widgets below 
	You do not need to use widgets if you dont want to. Simply remove 
	the <section></section> and you can use wells or panels instead 
	-->

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">
		
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0">

				<header>
					<span class="widget-icon"> <i class="fa fa-eye"></i> </span>
					<h2>Sapphire Communities View</h2>					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						<input class="form-control" type="text">	
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">

						<ul id="myTab1" class="nav nav-tabs bordered">
							<li>
								<a href="#s2" data-toggle="tab">Business Units (DT)</a>
							</li>
							<li class="active">
								<a href="#s1" data-toggle="tab">Community (DT)</a>
							</li>
							<!-- <li>
								<a href="#s2" data-toggle="tab">Excel Like</a>
							</li> -->
						</ul>

						<div id="myTabContent1" class="tab-content padding-10">
							<div class="tab-pane fade in active" id="s1">
							
								<table id="dt_community" class="table table-striped table-bordered display responsive nowrap" width="100%">
								    <thead>
										<tr>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="CommunityRID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="CommunityID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Name" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Description" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="BUnitID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="City" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="ZipCode" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="County" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="StateCode" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Status" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="FollowUpWorkflow" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="TaxPercentage" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="UsesPhases" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="IsMultiFamily" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="ModelList" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Props" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="AntiMonotonyRule" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="ShortID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="SalesOfficeState" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="SalesOfficeCounty" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="SalesOfficeCity" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="SalesOfficeZip" />
											</th>
										</tr>
								        <tr>
								            <th data-class="expand">CommunityRID</th>
								            <th>CommunityID</th>
								            <th>Name</th>
								            <th>Description</th>
								            <th data-hide="phone,tablet">BUnitID</th>
								            <th data-hide="phone,tablet">City</th>
								            <th data-hide="phone,tablet">ZipCode</th>
								            <th data-hide="phone,tablet">County</th>
								            <th data-hide="phone,tablet">StateCode</th>
								            <th data-hide="phone,tablet">Status</th>
								            <th data-hide="phone,tablet">FollowUpWorkflow</th>
								            <th data-hide="phone,tablet">TaxPercentage</th>
								            <th data-hide="phone,tablet">UsesPhases</th>
								            <th data-hide="phone,tablet">IsMultiFamily</th>
								            <th data-hide="phone,tablet">ModelList</th>
								            <th data-hide="phone,tablet">Props</th>
								            <th data-hide="phone,tablet">AntiMonotonyRule</th>
								            <th data-hide="phone,tablet">ShortID</th>
								            <th data-hide="phone,tablet">SalesOfficeState</th>
								            <th data-hide="phone,tablet">SalesOfficeCounty</th>
								            <th data-hide="phone,tablet">SalesOfficeCity</th>
								            <th data-hide="phone,tablet">SalesOfficeZip</th>
								        </tr>
								    </thead>

								    <tbody>
								        
								    </tbody>

								</table>

							</div>
							<div class="tab-pane fade" id="s2">
								<table id="dt_bunit" class="table table-striped table-bordered display responsive nowrap" width="100%">
								    <thead>
										<tr>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="BUnitFullID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="BUnitID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Name" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Description" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Parent" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="LegalName" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="StreetAddress" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="City" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="ZipCode" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="StateCode" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Country" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="PhoneMain" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="EnabledInWeb" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="WebGroupName" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="ExternalWebID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="GeoLocLat" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="GeoLocLng" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="BUnitLevelID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="BUnitNumber" />
											</th>
										</tr>
								        <tr>
								            <th data-class="expand">BUnitFullID</th>
								            <th>BUnitID</th>
								            <th>Name</th>
								            <th>Description</th>
								            <th data-hide="phone,tablet">Parent</th>
								            <th data-hide="phone,tablet">LegalName</th>
								            <th data-hide="phone,tablet">StreetAddress</th>
								            <th data-hide="phone,tablet">City</th>
								            <th data-hide="phone,tablet">ZipCode</th>
								            <th data-hide="phone,tablet">StateCode</th>
								            <th data-hide="phone,tablet">Country</th>
								            <th data-hide="phone,tablet">PhoneMain</th>
								            <th data-hide="phone,tablet">EnabledInWeb</th>
								            <th data-hide="phone,tablet">WebGroupName</th>
								            <th data-hide="phone,tablet">ExternalWebID</th>
								            <th data-hide="phone,tablet">GeoLocLat</th>
								            <th data-hide="phone,tablet">GeoLocLng</th>
								            <th data-hide="phone,tablet">BUnitLevelID</th>
								            <th data-hide="phone,tablet">BUnitNumber</th>
								        </tr>
								    </thead>

								    <tbody>
								        
								    </tbody>

								</table>
							</div>
						</div>

					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->
		
	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-admin">
		<div class="modal-content" id="modal-content">
			<div class="modal-header" id="modal-header">Edit Community</div>
			<div class="modal-body" id="modal-body">
				<form action="#" id="edit-community" class="smart-form">
					<!-- <header>
						something here!
					</header> -->

					<fieldset>
						<div class="row">
							<section class="col col-3">
								<label class="input"> AntiMonotonyRule <!-- <i class="icon-append fa fa-user"></i> -->
									<input type="text" name="AntiMonotonyRule" id="AntiMonotonyRule" placeholder="AntiMonotonyRule">
								</label>
							</section>
							<section class="col col-3">
								<label class="input"> BUnitID<!-- <i class="icon-append fa fa-envelope-o"></i> -->
									<input type="text" name="BUnitID" id="BUnitID" placeholder="BUnitID">
								</label>
							</section>
							<section class="col col-3">
								<label class="input"> City
									<input type="text" name="City" id="City" placeholder="City">
								</label>
							</section>
							<section class="col col-3">	
								<input type="hidden" name="CommunityFullID" id="CommunityFullID">
								<!-- <label class="input"> CommunityFullID
									<input type="text" name="CommunityFullID" id="CommunityFullID" placeholder="CommunityFullID">
								</label> -->
							</section>
						</div>

						<div class="row">
							<section class="col col-3">
								<label class="input"> CommunityID
									<input type="text" name="CommunityID" id="CommunityID" placeholder="CommunityID">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> County
									<input type="text" name="County" id="County" placeholder="County">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> Description
									<input type="text" name="Description" id="Description" placeholder="Description">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> FollowUpWorkflow
									<input type="text" name="FollowUpWorkflow" id="FollowUpWorkflow" placeholder="FollowUpWorkflow">
								</label>
							</section>
						</div>

						<div class="row">
							<section class="col col-3">	
								<label class="input"> ModelList
									<input type="text" name="ModelList" id="ModelList" placeholder="ModelList">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> Name
									<input type="text" name="Name" id="Name" placeholder="Name">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> Props
									<input type="text" name="Props" id="Props" placeholder="Props">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> SalesOfficeCity
									<input type="text" name="SalesOfficeCity" id="SalesOfficeCity" placeholder="SalesOfficeCity">
								</label>
							</section>
						</div>
						<div class="row">
							<section class="col col-3">	
								<label class="input"> SalesOfficeCounty
									<input type="text" name="SalesOfficeCounty" id="SalesOfficeCounty" placeholder="SalesOfficeCounty">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> SalesOfficeEmail
									<input type="text" name="SalesOfficeEmail" id="SalesOfficeEmail" placeholder="SalesOfficeEmail">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> SalesOfficeFax
									<input type="text" name="SalesOfficeFax" id="SalesOfficeFax" placeholder="SalesOfficeFax">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> SalesOfficePhone1
									<input type="text" name="SalesOfficePhone1" id="SalesOfficePhone1" placeholder="SalesOfficePhone1">
								</label>
							</section> 
						</div>
						<div class="row">
							<section class="col col-3">	
								<label class="input"> SalesOfficePhone2
									<input type="text" name="SalesOfficePhone2" id="SalesOfficePhone2" placeholder="SalesOfficePhone2">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> SalesOfficeState
									<input type="text" name="SalesOfficeState" id="SalesOfficeState" placeholder="SalesOfficeState">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> SalesOfficeStreet
									<input type="text" name="SalesOfficeStreet" id="SalesOfficeStreet" placeholder="SalesOfficeStreet">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> SalesOfficeZip
									<input type="text" name="SalesOfficeZip" id="SalesOfficeZip" placeholder="SalesOfficeZip">
								</label>
							</section>
						</div>
						<div class="row">
							<section class="col col-3">	
								<label class="input"> ShortID
									<input type="text" name="ShortID" id="ShortID" placeholder="ShortID">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> StateCode
									<input type="text" name="StateCode" id="StateCode" placeholder="StateCode">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> Status
									<input type="text" name="Status" id="Status" placeholder="Status">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> TaxPercentage
									<input type="text" name="TaxPercentage" id="TaxPercentage" placeholder="TaxPercentage">
								</label>
							</section>
						</div>
						<div class="row">
							<section class="col col-3">	
								<label class="input"> UsesPhases
									<input type="text" name="UsesPhases" id="UsesPhases" placeholder="UsesPhases">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> UsesSections
									<input type="text" name="UsesSections" id="UsesSections" placeholder="UsesSections">
								</label>
							</section>
							<section class="col col-3">	
								<label class="input"> ZipCode
									<input type="text" name="ZipCode" id="ZipCode" placeholder="ZipCode">
								</label>
							</section>
						</div>
						<!-- <section>
							<label class="input"> 
								<input type="text" name="" id="" placeholder="">
							</label>
							<label class="input"> 
								<input type="text" name="" id="" placeholder="">
							</label>
							<label class="input"> 
								<input type="text" name="" id="" placeholder="">
							</label>
							<label class="input"> 
								<input type="text" name="" id="" placeholder="">
							</label>
						</section> -->

						<!-- <section>
							<label class="label"></label>
							<label class="textarea"> <i class="icon-append fa fa-comment"></i> 										
								<textarea rows="3" name="review" id="review" placeholder="Text of the review"></textarea> 
							</label>
						</section> -->
					</fieldset>
					<footer>
						<button type="submit" class="btn btn-success">
							Up Date
						</button>
					</footer>
				</form>
			</div>
			<div class="modal-footer" id="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	pageSetUp();

	var otable;
	var dtBunit;
	
	var pagefunction = function() {
		
		
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_fixed_column2 = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		/* Community Table  */
	    otable = $('#dt_community').DataTable({
	    	ajax: {
				url:"/sapi/listCommunities/",
				type: 'GET',
				dataType: 'JSON',
				headers: {
					Authorization : '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'
				}
			},
			columns: [
				{ data:'CommunityRID', class:'CommunityRID'},
				{ data:'CommunityID', class:'CommunityID'},
				{ data:'Name'},
				{ data:'Description'},
				{ data:'BUnitID' },
				{ data:'City' },
				{ data:'ZipCode' },
				{ data:'County' },
				{ data:'StateCode' },
				{ data:'Status' },
				{ data:'FollowUpWorkflow' },
				{ data:'TaxPercentage' },
				{ data:'UsesPhases' },
				{ data:'IsMultiFamily' },
				{ data:'ModelList' },
				{ data:'Props' },
				{ data:'AntiMonotonyRule' },
				{ data:'ShortID' },
				{ data:'SalesOfficeState' },
				{ data:'SalesOfficeCounty' },
				{ data:'SalesOfficeCity' },
				{ data:'SalesOfficeZip' }
        	],
			// "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
			// 		"t"+
			// 		"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs' l C T r>>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"oTableTools": {
	        	 "aButtons": [
	             "copy",
	             "csv",
	             "xls",
	                {
	                    "sExtends": "pdf",
	                    "sTitle": "FischerHomes_PDF",
	                    "sPdfMessage": "FischerHomes PDF Export",
	                    "sPdfSize": "letter"
	                },
	             	{
                    	"sExtends": "print",
                    	"sMessage": "Generated by FischerHomes <i>(press Esc to close)</i>"
                	}
	             ],
	            "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
	        },
			"autoWidth" : false,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_column) {
					responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#dt_community'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_fixed_column.respond();
			}
	    });
	    

		// $('#dt_community tbody').on( 'click', 'button', function () {  // would be for a button on the row.
		$('#dt_community tbody').on( 'click', 'tr',function () {
			// console.log($(this).find(".CommunityID").html());
			// var theCode = $(this).find(".CommunityID").html();
			getCommunityForm($(this).find(".CommunityID").html());
		} );

	    // custom toolbar
	    // $("div.toolbar").html('<div class="text-right"><img src="img/logo2.png" alt="Fischer Homes" style=" height:20px; margin-top: 1px; margin-right: 10px;"></div>');
	   	// table.columns( [ 0, 1, 2, 3 ] ).visible( false, false );
	   	// otable.columns([5,6,7,8,9,10]).visible(false,false,false,false,false,false);
	   	// otable.columns([5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21]).visible(false);
	    // Apply the filter
	    $("#dt_community thead th input[type=text]").on( 'keyup change', function () {
	        otable
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
	    } );
	    /* END Community Table */

	    /* B Unit Table  */
		dtBunit = $('#dt_bunit').DataTable({
			ajax: {
				url:"/sapi/listBUnits/",
				type: 'GET',
				dataType: 'JSON',
				headers: {
					Authorization : '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'
				}
			},
			columns: [
				{ data:'BUnitFullID', class:'BUnitFullID'},
				{ data:'BUnitID', class:'BUnitID'},
				{ data:'Name'},
				{ data:'Description'},
				{ data:'Parent' },
				{ data:'LegalName' },
				{ data:'StreetAddress' },
				{ data:'City' },
				{ data:'ZipCode' },
				{ data:'StateCode' },
				{ data:'Country' },
				{ data:'PhoneMain' },
				{ data:'EnabledInWeb' },
				{ data:'WebGroupName' },
				{ data:'ExternalWebID' },
				{ data:'GeoLocLat' },
				{ data:'GeoLocLng' },
				{ data:'BUnitLevelID' },
				{ data:'BUnitNumber' }
        	],
			// "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
			// 		"t"+
			// 		"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs' l C T r>>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"dtBunitTools": {
	        	 "aButtons": [
	             "copy",
	             "csv",
	             "xls",
	                {
	                    "sExtends": "pdf",
	                    "sTitle": "FischerHomes_PDF",
	                    "sPdfMessage": "FischerHomes PDF Export",
	                    "sPdfSize": "letter"
	                },
	             	{
                    	"sExtends": "print",
                    	"sMessage": "Generated by FischerHomes <i>(press Esc to close)</i>"
                	}
	             ],
	            "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
	        },
			"autoWidth" : false,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_datatable_fixed_column2) {
					responsiveHelper_datatable_fixed_column2 = new ResponsiveDatatablesHelper($('#dt_bunit'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_datatable_fixed_column2.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_datatable_fixed_column2.respond();
			}
	    });
	    

		// $('#dt_bunit tbody').on( 'click', 'button', function () {  // would be for a button on the row.
		// $('#dt_bunit tbody').on( 'click', 'tr',function () {
			// console.log($(this).find(".CommunityID").html());
			// var theCode = $(this).find(".CommunityID").html();
			// getCommunityForm($(this).find(".CommunityID").html());
		// } );

	    // custom toolbar
	    // $("div.toolbar").html('<div class="text-right"><img src="img/logo2.png" alt="Fischer Homes" style=" height:20px; margin-top: 1px; margin-right: 10px;"></div>');

	    // Apply the filter
		$("#dt_bunit thead th input[type=text]").on( 'keyup change', function () {
			dtBunit
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();
		} );	
	    /* END Community Table */
	};
	
	// end pagefunction
	
	// run pagefunction
	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});
	

	function hideMostColumns(){
		// console.log(otable);
		otable.columns([5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21]).visible(false);
		dtBunit.columns([5,6,7,8,9,10,11,12,13,14,15,16,17,18]).visible(false);
		// alert('after functions here!');
	}

	function showColumns(){
		// console.log(otable);
		otable.columns([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21]).visible(true);
		dtBunit.columns([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18]).visible(true);
		// alert('after functions here!');
	}

	function getTableData(){

		$.ajax({
			url: '/sapi/listCommunities/',
			type: 'GET',
			dataType: 'JSON',
			headers: {
				Authorization : '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'
			}
		})
		.done(function(data) {
			console.log("success getting the data");
			// console.log(data);
			tableData = data;
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

		return tableData;
	}

	/*$('#btnHideColumns').on('click', function(event) {
		event.preventDefault();
		alert('clicked');
		otable.columns([5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21]).visible(false);
		// had to do this this way because of hide on load makes searching not work.
	});*/

	function getCommunityForm(code = null){
		if(code){
			editCommunityForm.resetForm();
			$.ajax({
				url: '/sapi/community/'+code+'/',
				type: 'GET',
				dataType: 'JSON',
				headers: {
					Authorization : '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'
				}
			})
			.done(function(data) {
				console.log("success getting the data!");
				console.log(data[0]);
				$.each(data[0], function(name, val){
					var $el = $('[name="'+name+'"]'),
						type = $el.attr('type');

					switch(type){
						case 'checkbox':
							$el.attr('checked', 'checked');
							break;
						case 'radio':
							$el.filter('[value="'+val+'"]').attr('checked', 'checked');
							break;
						default:
							$el.val(val);
					}
				});
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
				$('#remoteModal').modal('show');
			});
		}
	}

	$(document).ready(function() {

	});

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

	var editCommunityForm

	var pagefunction2 = function() {

		jQuery.validator.addMethod("dollarsscents", function(value, element) {
        	return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
    	}, "You must include two decimal places");

		editCommunityForm = $("#edit-community").validate({
				// Rules for form validation
				rules : {
					CommunityID : {
						required: true,
						maxlength : 255
					},
					Name :{
						required : true,
						maxlength : 120
					},
					Description : {
						required: true,
						maxlength : 8000
					},
					AntiMonotonyRule : {
						maxlength: 8000
					},
					BUnitID : {
						required : true,
						maxlength : 255
					},
					City : {
						maxlength : 40
					},
					ZipCode : {
						maxlength: 10
					},
					County : {
						// required : false,
						maxlength: 40
					},
					StateCode : {
						// required : false,
						maxlength: 2
					},
					Status : {
						// required : false,
						maxlength: 16
					},
					FollowUpWorkflow : {
						// required : false,
						maxlength: 255
					},
					TaxPercentage : {
						number: true,
						dollarsscents:true
					},
					UsesPhases : {
						// required : false,
						maxlength: 40
					},
					IsMultiFamily : {
						// required : false,
						maxlength: 40
					},
					ModalList : {
						// required : false,
						maxlength: 40
					},
					Props : {
						// required : false,
						maxlength: 40
					},
					ShortID : {
						// required : false,
						maxlength: 255
					},
					SalesOfficeState : {
						// required : false,
						maxlength: 2
					},
					SalesOfficeCounty : {
						// required : false,
						maxlength: 40
					},
					SalesOfficeCity : {
						// required : false,
						maxlength: 40
					},
					SalesOfficeZip : {
						// required : false,
						maxlength: 10
					}
				},

				// Messages for form validation
				messages : {
					
				},

				// Ajax form submition
				submitHandler : function(form) {
					/*$(form).ajaxSubmit({
						success : function() {
							// $("#comment-form").addClass('submited');
						}
					});*/

				},

				// Do not change code below
				errorPlacement : function(error, element) {
					error.insertAfter(element.parent());
				}
			});
	};

	loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction2);
</script>
