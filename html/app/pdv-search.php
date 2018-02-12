<?php 
require_once("../init.php");
checkLogin();
require_once("inc/init.php");
?>
<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-search"></i> 
				Pervasive 
			<span>>  
				Schema Search
			</span>
		</h1>
	</div>
	<!-- end col -->
	
	<!-- right side of the page with the sparkline graphs -->
	<!-- col -->
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		
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
			<div class="jarviswidget" id="wid-id-0">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-search"></i> </span>
					<h2>Pervasive Database Schema Search</h2>				
					
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
						<!-- Realtor.id, Realtor.agentNumber, Realtor.idBroker, Realtor.idPerson, Name, Person.email, Person.phone, Broker.idOrganization, Organization.name As \"Org Name\", Organization.streetAddress, Organization.city, Organization.idState, Organization.postalCode, Organization.phone -->
						<table id="dt_pdv" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th class="hasinput" style="width:17%">
										<input type="text" class="form-control" placeholder="Database Name" />
									</th>
									<th class="hasinput" style="width:17%">
										<input type="text" class="form-control" placeholder="Table Name" />
									</th>
									<th class="hasinput" style="width:17%">
										<input type="text" class="form-control" placeholder="Column Id" />
									</th>
									<th class="hasinput" style="width:17%">
										<input type="text" class="form-control" placeholder="Column Name" />
									</th>
									<th class="hasinput" style="width:17%">
										<input type="text" class="form-control" placeholder="Column Type" />
									</th>
									<th class="hasinput" style="width:17%">
										<input type="text" class="form-control" placeholder="Column Size" />
									</th>
								</tr>
								<tr>
									<th data-hide="expand">Database Name</th>
									<th data-hide="">Table Name</th>
									<th data-hide="">Column ID</th>
									<th data-hide="">Column Name</th>
									<th data-hide="">Column Type</th>
									<th data-class="">Column Size</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>

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

	<!-- row -->

	<div class="row">

		<!-- a blank row to get started -->
		<div class="col-sm-6">
			<!-- your contents here -->
		</div>
			
	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">

	/* // DOM Position key index //
		
			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing 
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class
			
			Also see: http://legacy.datatables.net/usage/features
		*/

	var apiCallData = new Array();

	function makeCallData(){
		// reset call data to have the api token.  Probably needs to be a Golbal variable and function.
		apiCallData['Authorization'] = "1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f";
		// console.log(apiCallData);
	}
	
	var pagefunction = function() {
		// clears the variable if left blank
		// $('#forms').dataTable();

		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480,
		};

		makeCallData();

		dt1 = $('#dt_pdv').DataTable({
			"processing": true,
        	"serverSide": true,
			"ajax": {
				"url":"/pdv/getDTdata/",
				"type": 'POST',
				"headers": apiCallData
			},
			// "ajax" : "/di/function/getJobs/",
			// "order": [[ 7, "asc" ]],
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs' l C T r>>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			// "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
			// 		"t"+
			// 		"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
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
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_pdv'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

		$("div.toolbar").html('<div class="text-right"></div>');
	    	   
	    // Apply the filter
	    $("#dt_pdv thead th input[type=text]").on( 'keyup change', function () {
	        dt1
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
	    } );

	};
	
	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});

</script>