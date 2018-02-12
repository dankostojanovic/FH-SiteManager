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
			<i class="fa-fw fa fa-tachometer"></i>
				Land Ops
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
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-0">
				<header>
					<span class="widget-icon"> <i class="fa fa-tachometer"></i> </span>
					<h2>Summary </h2>

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

						<div id="normal-bar-graph" class="chart no-padding"></div>

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
		/*
		Morris.Bar({
			element: 'normal-bar-graph',
			data: [
				{x: '2011 Q1', y: 3, z: 2, a: 3},
				{x: '2011 Q2', y: 2, z: null, a: 1},
				{x: '2011 Q3', y: 0, z: 2, a: 4},
				{x: '2011 Q4', y: 2, z: 4, a: 3}
			],
			xkey: 'x',
			ykeys: ['y', 'z', 'a'],
			labels: ['Y', 'Z', 'A']
		});
		*/
		$.ajax({
			url: '/rule/dash/sum/',
			type: 'POST',
			dataType: 'json',
			headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
			// data: {param1: 'value1'},
		})
		.done(function(info) {
			console.log("success");
				Morris.Bar({
				element: 'normal-bar-graph',
				data: info,
				xkey: 'Name',
				ykeys: ['Sections', 'Sites'],
				labels: ['Sections', 'Sites']
			});
		})
		.fail(function() {
			console.log("error");
			$('#normal-bar-graph').html("Error!");
		})
		.always(function() {
			console.log("complete");
		});

	};

	// end pagefunction

	// run pagefunction
	loadScript("js/plugin/morris/raphael.min.js", function(){
		loadScript("js/plugin/morris/morris.min.js", pagefunction);
	});

</script>
