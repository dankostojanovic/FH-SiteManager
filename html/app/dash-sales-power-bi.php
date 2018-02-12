<?php 
require_once("../init.php");
checkLogin();
require_once("inc/init.php");

?>

<!-- two -->
<!-- https://app.powerbi.com/view?r=eyJrIjoiMGVjNGM5M2ItNjJkZC00NDM4LWE5NTAtNWZkN2M2YzVlNTgwIiwidCI6ImFhZjlkNzMzLWJmNjgtNGVjOS1iYzM1LTNhZjg0ZDU0Mjk0YSIsImMiOjN9 -->

<!-- <iframe width="800" height="600" src="https://app.powerbi.com/view?r=eyJrIjoiYjA0ZWZjYzEtMmNjMS00M2EyLTk3OTctMzQ4ZGVlZDU3OWFiIiwidCI6ImFhZjlkNzMzLWJmNjgtNGVjOS1iYzM1LTNhZjg0ZDU0Mjk0YSIsImMiOjN9"></iframe> -->
<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-home"></i> 
				Power Bi
			<span>>  
				Demo
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
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-0">
				<header>
					<span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
					<h2> </h2>				
					
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
						
						<iframe width="100%" height="600" src="https://app.powerbi.com/view?r=eyJrIjoiYjA0ZWZjYzEtMmNjMS00M2EyLTk3OTctMzQ4ZGVlZDU3OWFiIiwidCI6ImFhZjlkNzMzLWJmNjgtNGVjOS1iYzM1LTNhZjg0ZDU0Mjk0YSIsImMiOjN9"></iframe>

					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->

		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-1">
				<header>
					<span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
					<h2> </h2>				
					
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
						
						<iframe width="100%" height="600" src="https://app.powerbi.com/view?r=eyJrIjoiMGVjNGM5M2ItNjJkZC00NDM4LWE5NTAtNWZkN2M2YzVlNTgwIiwidCI6ImFhZjlkNzMzLWJmNjgtNGVjOS1iYzM1LTNhZjg0ZDU0Mjk0YSIsImMiOjN9"></iframe>

					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->	
		</article>
		
	</div>

	<div class="row">
		
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-2">
				<header>
					<span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
					<h2> </h2>				
					
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
						
						<div id="reportHolder">
							
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

<script src="js/powerbi/powerbi.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

	pageSetUp();
	
	// pagefunction
	
	var pagefunction = function() {
		// clears the variable if left blank
	};
	
	// end pagefunction
	
	// run pagefunction
	pagefunction();

	$(document).ready(function() {
			
	});
	
</script>
