<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once("../init.php");
checkLogin();
require_once("inc/init.php"); 

if(!empty($_GET['jobsId'])){
	$link = '';
	$logs = \logs\LogsQuery::create()->filterByJobsId($_GET['jobsId'])->find();
	$jobs = new \logs\Jobs($_GET['jobsId']);
	if( !empty( $jobs->getType() ) ){
		if($jobs->getType() == 1){
			$link = 'https://fnet.fischermgmt.com/CRM/lead/';
		}else{
			$link = 'https://staging.fischermgmt.com/CRM/lead/';
		}
	}
}

$names = array('440' => array() , '449' => array(), '451' => array());
foreach ($names as $key => $name) {
	$u = new \crm\User($key);
	$info = $u->getPersonInfo();
	$names[$key] = $info;
}
// print_array($names);
?>
<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-area-chart"></i> 
				Lasso2crm
			<span>>  
				Drill Down
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
			<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
				<header>
					<span class="widget-icon"> <i class="fa fa-area-chart"></i> </span>
					<h2>Job Summary </h2>		
					
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
						
						<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
							<thead>			                
								<tr>
									<th data-hide="phone,tablet">ID</th>
									<th data-class="expand"></i> Name</th>
									<th data-hide="phone"></i> Description</th>
									<th> Changes</th>
									<th data-hide="phone,tablet">User</th>
									<th data-hide="phone,tablet"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i> Date</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach ($logs as $key => $l) {
									echo 	"<tr>
												<td>".$l->getId()."</td>
												<td><a target='_blank' href='$link".$l->getName()."'>".$l->getName()."</a></td>
												<td>".$l->getDescription()."</td>
												<td>".$l->getChanges()."</td>
												<td>".$names[$l->getCrmUserId()]['Namefirst']." ".$names[$l->getCrmUserId()]['Namelast']."</td>
												<td>".$l->getDatetime('Y-m-d H:i:s')."</td>
											</tr>";
								}
								?>
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
		<div class="col-sm-12">
			<!-- your contents here -->
		</div>
			
	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">
	

	pageSetUp();
	
	
	
	// pagefunction
	
	var pagefunction = function() {
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#dt_basic').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"order": [[ 3, "desc" ]],
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
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
