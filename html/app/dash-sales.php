<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once("../init.php");
checkLogin();
require_once("inc/init.php");

// $divisions = \crm\DivisionQuery::Create()->orderByName('ASC')->find();
// print_array($divisions->toArray());
?>
<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-cogs"></i> 
				Sales
			<span>>  
				Dashboard
			</span>
		</h1>
	</div>
	<!-- end col -->
	
	<!-- right side of the page with the sparkline graphs -->
	<!-- col -->
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<!-- sparks -->
		<!-- <ul id="sparks">
			<li class="sparks-info">
				<h5> My Income <span class="txt-color-blue">$47,171</span></h5>
				<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
					1300, 1877, 2500, 2577, 2000, 2100, 3000, 2700, 3631, 2471, 2700, 3631, 2471
				</div>
			</li>
			<li class="sparks-info">
				<h5> Site Traffic <span class="txt-color-purple"><i class="fa fa-arrow-circle-up" data-rel="bootstrap-tooltip" title="Increased"></i>&nbsp;45%</span></h5>
				<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
					110,150,300,130,400,240,220,310,220,300, 270, 210
				</div>
			</li>
			<li class="sparks-info">
				<h5> Site Orders <span class="txt-color-greenDark"><i class="fa fa-shopping-cart"></i>&nbsp;2447</span></h5>
				<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
					110,150,300,130,400,240,220,310,220,300, 270, 210
				</div>
			</li>
		</ul> -->
		<!-- end sparks -->
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
		<article class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-0">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
					<h2>Filters </h2>				
					
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
						<form action="#" id="filters" name="filters" class="smart-form" novalidate="novalidate">
							<fieldset>
								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" onchange="javascript:formHasChanged()" name="startdate" id="startdate" placeholder="Start Date" value="<?php echo date('Y-m-d', strtotime('-1 months'));?>">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" onchange="javascript:formHasChanged()" name="finishdate" id="finishdate" placeholder="End Date" value="<?php echo date('Y-m-d');?>">
										</label>
									</section>
								</div>
							</fieldset>
							<fieldset>
								<div class="row">
									<label class="label col col-2">Division</label>
									<section class="col col-10">
										<!-- <label class="select"> -->
											<!-- <select name="type" id="type" onchange="#" > -->
											<select multiple style="width:100%" onchange="javascript:formHasChanged()" class="select2" id="division" name="division">
												<!-- <option value="-1">All</option> -->
												<?php 
													// foreach ($divisions as $key => $d) {
													// 	echo "<option value='".$d->getId()."' >".$d->getName()."</option>";
													// }
												?>
											</select> <!-- <i></i> </label> -->
									</section>
								</div>
								<!-- <div class="row">
									<label class="label col col-2">Name</label>
									<section class="col col-10">
										<label class="select">
											<select name="name" id="name" onchange="javascript:formChanged()" >
												<option value="">All</option>
											</select> <i></i> </label>
									</section>
								</div> -->
							</fieldset>
						</form>
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
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-1">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-graph"></i> </span>
					<h2> </h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">
						<ul id="myTab1" class="nav nav-tabs bordered">
							<li class="active">
								<a href="#s1" data-toggle="tab">Sales Summary</a>
							</li>
							<li>
								<a href="#s2" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Budget Revenue </a>
							</li>
							<li>
								<a href="#s3" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Goal Revenue </a>
							</li>
							<li>
								<a href="#s4" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Net Revenue </a>
							</li>
							
						</ul>

						<div id="myTabContent1" class="tab-content padding-10">
							<div class="tab-pane fade in active" id="s1">
								<div id="summary" class="chart no-padding"></div>
							</div>
							<div class="tab-pane fade" id="s2">
								<div id="budget_revenue" class="chart no-padding"></div>
							</div>
							<div class="tab-pane fade" id="s3">
								
							</div>
							<div class="tab-pane fade" id="s4">
								
							</div>
						</div>
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
		</article>
	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">

	var graphData01 = [];

	pageSetUp();
	
	// pagefunction
	
	var pagefunction = function(data = null) {
		$('#summary').html('');

		if(data){
			console.log(data);
			Morris.Bar({
				element : 'summary',
				data : data,
				xkey : 'Period',
				ykeys : [ 'BudgetSales', 'GoalSales', 'NetSales' ],
				labels : [ 'BudgetSales', 'GoalSales', 'NetSales' ],
				pointSize : 4,
				hideHover : 'auto'
			});
		}
	};


	var pagefunction2 = function(data = null) {
		if(data){
			var options = {
				xaxis : {
					mode : "time",
					tickLength : 5
				},
				series : {
					lines : {
						show : true,
						lineWidth : 1,
						fill : true,
						fillColor : {
							colors : [{
								opacity : 0.1
							}, {
								opacity : 0.15
							}]
						}
					},
                   //points: { show: true },
					shadowSize : 0
				},
				selection : {
					mode : "NetRevenue"
				},
				grid : {
					hoverable : true,
					clickable : true,
					tickColor : 'blue',
					borderWidth : 0,
					borderColor : 'blue',
				},
				tooltip : true,
				tooltipOpts : {
					content : "Your sales for <b>%x</b> was <span>$%y</span>",
					dateFormat : "%y-%0m-%0d",
					defaultTheme : false
				},
				colors : [$chrt_second],
		
			};
		
			plot_1 = $.plot($("#budget_revenue"), [data], options);
		}
	};

	loadScript("js/plugin/flot/jquery.flot.cust.min.js", function(){
		loadScript("js/plugin/flot/jquery.flot.resize.min.js", function(){
			loadScript("js/plugin/flot/jquery.flot.fillbetween.min.js", function(){
				loadScript("js/plugin/flot/jquery.flot.orderBar.min.js", function(){
					loadScript("js/plugin/flot/jquery.flot.pie.min.js", function(){
						loadScript("js/plugin/flot/jquery.flot.time.min.js", function(){
							loadScript("js/plugin/flot/jquery.flot.tooltip.min.js", pagefunction2);
						});
					});
				});
			});
		});
	});
	
	// end pagefunction
	
	// run pagefunction
	// pagefunction();
	loadScript("js/plugin/morris/raphael.min.js", function(){
		loadScript("js/plugin/morris/morris.min.js", pagefunction);
	});

	var pagefunction3 = function(){
		$('#startdate').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#finishdate').datepicker('option', 'minDate', selectedDate);
			}
		});
		
		$('#finishdate').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#startdate').datepicker('option', 'maxDate', selectedDate);
			}
		});
	}

	loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction3);


	function getDivisions(){
		$.ajax({
			url: '/bi/sales/distinct/',
			type: 'GET',
			dataType: 'JSON',
			headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'}
		})
		.done(function(divisions) {
			// console.log("success");
			// console.log(divisions);
			$('#division').empty();
			// $('').append();
			$.each(divisions, function(index, val) {
				$('#division').append("<option value='"+val.Division+"'>"+val.DivisionName+"</option>");
			});
		})
		.fail(function() {
			// console.log("error");
		})
		.always(function() {
			// console.log("complete");
		});

	}

	function formHasChanged(){
		graphData01 = getGraphData();
	}

	function formatFilters(){
		filter = {filter:{}};
		division = [];
		if($('#division').val()){
			$.each($('#division').val(), function(index, val) {
				division.push( { 0: val, 1:' = ' });
			});
			filter.filter.Division = division;
		}
		period = [];
		period.push({min: $('#startdate').val(), max: $('#finishdate').val()});
		filter.filter.Period = period;
		// filter.filter.Period = ['min': $('startdate').val(), 'max': $('finishdate').val()];

		return filter;
	}

	function getGraphData(){
		var newData = Array();
		// var filters = $('#filters').serializeObject();
		var filters = {};
		filters = formatFilters();
		console.log(filters);
		$.ajax({
			url: '/bi/sales/',
			type: 'GET',
			dataType: 'JSON',
			headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
			data: filters,
		})
		.done(function(data) {
			// console.log("success");
			console.log(data);
			pagefunction(data);
			// newData = data;
			// console.log(newData);
		})
		.fail(function() {
			// console.log("error");
		})
		.always(function() {
			// console.log("complete");
		});

		return newData;
	}

	$(document).ready(function() {
		getDivisions();
		formHasChanged();
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
</script>
