<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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
			<i class="fa-fw fa fa-cogs"></i> 
				Dashboard
			<span>>  
				Integrator 
			</span>
		</h1>
	</div>
	<!-- end col -->
	
	<!-- right side of the page with the sparkline graphs -->
	<!-- col -->
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<!-- Type
		<select name="type" id="type" onchange="">
			<option value="">All</option>
			<option value="1">Production</option>
			<option value="2">Staging</option>
		</select>
		&nbsp;|&nbsp;
		Name
		<select name="name" id="name" onchange="">
			<option value="">All</option>
		</select>
		<br /> -->
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

	<div class="row">
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
					<h2>Jobs Filters </h2>
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
						<form action="#" id="filters" class="smart-form" novalidate="novalidate">
							<fieldset>
								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" onchange="javascript:formChanged()"  name="startdate" id="startdate" placeholder="Start Date" value="<?php echo date('Y-m-d', strtotime('-30 days'));?>">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" onchange="javascript:formChanged()"  name="finishdate" id="finishdate" placeholder="End Date" value="<?php echo date('Y-m-d');?>">
										</label>
									</section>
								</div>
							</fieldset>
							<fieldset>
								<div class="row">
									<label class="label col col-2">Type</label>
									<section class="col col-10">
										<label class="select">
											<select name="type" id="type" onchange="javascript:formChanged()" >
												<option value="">All</option>
												<option value="1">Production</option>
												<option value="2">Staging</option>
											</select> <i></i> </label>
									</section>
								</div>
								<div class="row">
									<label class="label col col-2">Name</label>
									<section class="col col-10">
										<label class="select">
											<select name="name" id="name" onchange="javascript:formChanged()" >
												<option value="">All</option>
											</select> <i></i> </label>
									</section>
								</div>
							</fieldset>
						</form>
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
			</div>
		</article>
	</div>

	<div class="row">
		
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget night-sky" id="wid-id-2" data-widget-editbutton="false">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
					<h2>Jobs Stats Chart</h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<div id="jobs-stats" class="chart has-legend"></div>
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->
		
	</div>

	<!-- row -->
	<div class="row">
		
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
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
					<h2>Jobs Details </h2>				
					
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
						<table id="di_jobs" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th data-hide="phone,tablet" >Job Id</th>
									<th data-class="expand"> <i class="fa fa-fw fa-cog text-muted hidden-md hidden-sm hidden-xs"></i>Name</th>
									<th>Type</th>
									<th>Date</th>
									<th data-hide="phone,tablet, pc">Start Datetime</th>
									<th data-hide="phone,tablet, pc">End Datetime</th>
									<th data-hide="phone,tablet">Description</th>
									<th>Duration</th>
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

</section>
<!-- end widget grid -->

<script type="text/javascript">

	var dt1;
	var chart1;

	pageSetUp();
	
	// pagefunction
	
	var pagefunction = function() {
		// clears the variable if left blank
		// $('#forms').dataTable();

		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		dt1 = $('#di_jobs').DataTable({
			"ajax": {
				"url":"/di/function/getJobs/",
				"type": 'POST',
				"data" : {
					'startdate' : $('#startdate').val(),
					'finishdate' : $('#finishdate').val(),
					'name': $('#name').val(),
					'type': $('#type').val()
				},
			},
			// "ajax" : "/di/function/getJobs/",
			"order": [[ 0, "desc" ]],
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l C T>r>"+
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
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#di_jobs'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

	};
	
	// end pagefunction
	
	// run pagefunction
	// pagefunction();

	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});

	var pagefunction2 = function(){

		/* chart colors default */
		var $chrt_border_color = "#efefef";
		var $chrt_grid_color = "#DDD"
		var $chrt_main = "#E24913";			/* red       */
		var $chrt_second = "#6595b4";		/* blue      */
		var $chrt_third = "#FF9F01";		/* orange    */
		var $chrt_fourth = "#7e9d3a";		/* green     */
		var $chrt_fifth = "#BD362F";		/* dark red  */
		var $chrt_mono = "#000";

		// takes the filters form and makes it into an object to send to the post
		filters = $('#filters').serializeObject();


		if ($('#jobs-stats').length){ 
			$.post('/di/function/dashGraphData/', filters, function(data, textStatus, xhr) {
				// day_data = data;
				console.log(data);  // this is all the data
				// console.log(data[0]); // this is the 1st object or 1st dates data
				var keyLables = Array();
				$.each(data, function(index, val) {
					$.each(val, function(col_name, info) {
						// keyLables.push(col_name);
						if($.inArray(col_name, keyLables) === -1 && col_name != 'period') keyLables.push(col_name);
					});
				});
				//  we have to do the above to make sure that we have the unique data columns that are in the data for the yKeys and lables for the graph.
				// keyLables.splice('period');
				console.log(keyLables);

				chart1 = Morris.Line({
					element: 'jobs-stats',
					data: data,
					xkey: 'period',
					// ykeys: ['Lasso', 'Lasso2crm', 'Drip', 'Community', 'Sales Performance', 'AWS PCC Prospect'],
					// labels: ['Lasso', 'Lasso2crm', 'Drip', 'Community', 'Sales Performance', 'AWS PCC Prospect']
					ykeys: keyLables,
					labels: keyLables
				});
			}, "json");
		}
	}

	loadScript("js/plugin/morris/raphael.min.js", function(){
		loadScript("js/plugin/morris/morris.min.js", pagefunction2);
	});

	var pagefunction3 = function(){
		$('#startdate').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#finishdate').datepicker('option', 'minDate', selectedDate);
				formChanged();
			}
		});
		
		$('#finishdate').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#startdate').datepicker('option', 'maxDate', selectedDate);
				formChanged();
			}
		});
	}

	loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction3);

	$(document).ready(function() {
		getJobNames();
	});
	
	function getJobNames()
	{
		$.post('/di/function/distName/', function(data, textStatus, xhr) {
			console.log(data);
			$.each(data, function(key, value) {   
    			// $('#name').append($("<option></option>").attr("value",key).text(value)); 
    			$('#name').append($("<option></option>").attr("value",value).text(value)); 
			});
		}, "json");
	}

	function formChanged(){
		// alert("Something changed!");
		dt1.destroy();
		// chart1 = "";
		$('#jobs-stats').html('');
		pagefunction();
		pagefunction2();
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
</script>
