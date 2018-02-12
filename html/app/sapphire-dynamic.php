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
			<i class="fa-fw fa fa-home"></i> 
				Fischer Homes
			<span>>  
				API End Points and Data
			</span>
		</h1>
	</div>
	<!-- end col -->
	
	<!-- right side of the page with the sparkline graphs -->
	<!-- col -->
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<!-- sparks -->
		<ul id="sparks">
			
		</ul>
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
		<article class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0">
				<header>
					<span class="widget-icon"> <i class="fa fa-address-book"></i> </span>
					<h2>Select Sources</h2>				
					
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
						<fieldset>
							<div class="row">	
								<div class="col-sm-4">
									<div class="icon-addon addon-lg">
										<select class='form-control input-lg' id='targetTab' name='targetTab'>
											<option value='0'>Select Target</option>
											<option value="hr1">Data View 1</option>
											<option value="hr2">Data View 2</option>
											<option value="hr3">Data View 3</option>
											<option value="hr4">Data View 4</option>
										</select>
										<label for="targetTab" class="fa fa-object-group" rel="tooltip" title="targetTab"></label>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="icon-addon addon-lg">
										<select class='form-control input-lg' id='source' name='source'>
											<option value='0'>Select Source</option>
											<option value="1">Pervasive API</option>
											<option value="2">Sapphire API</option>
											<!-- <option value="3">Land Ops - Rule</option> -->
											<!-- <option value="4">CRM </option> -->
											<!-- <option value="5">Lasso </option> -->
											<!-- <option value="6">Arch GIS </option> -->
										</select>
										<label for="source" class="fa fa-object-group" rel="tooltip" title="Source"></label>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="icon-addon addon-lg">
										<select class='form-control input-lg' id='objects' name='objects'>
											<option value='0'>Select End Point</option>
										</select>
										<label for="objects" class="fa fa-object-group" rel="tooltip" title="Objects"></label>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<!-- <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1">
				<header>
					<span class="widget-icon"> <i class="fa fa-pencil"></i> </span>
					<h2>Source Summary</h2>
					
				</header>
				<div>
					<div class="jarviswidget-editbox">
						<input class="form-control" type="text">
					</div>
					<div class="widget-body" id="summary-holder">

					</div>
					
				</div>
				
			</div> -->

		</article>
		
	</div>

	<!-- end row -->

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			
			<!-- Widget ID (each widget will need unique ID) jarviswidget-color-darken -->
			<div class="jarviswidget" id="wid-id-2">
				<header>
					<!-- <span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Data and Maps </h2>	 -->			
					<ul class="nav nav-tabs pull-left in">

						<li class="active">
							<a data-toggle="tab" href="#hr1"> <i class="fa fa-lg fa-table"></i> <span class="hidden-mobile hidden-tablet hr1Header"> Data View 1</span> </a>
						</li>

						<li>
							<a data-toggle="tab" href="#hr2"> <i class="fa fa-lg fa-table"></i> <span class="hidden-mobile hidden-tablet hr2Header"> Data View 2</span> </a>
						</li>

						<li>
							<a data-toggle="tab" href="#hr3"> <i class="fa fa-lg fa-table"></i> <span class="hidden-mobile hidden-tablet hr3Header"> Data View 3</span> </a>
						</li>

						<li>
							<a data-toggle="tab" href="#hr4"> <i class="fa fa-lg fa-table"></i> <span class="hidden-mobile hidden-tablet hr4Header"> Data View 4</span> </a>
						</li>

						<li>
							<a data-toggle="tab" href="#hr5"> <i class="fa fa-lg fa-street-view"></i> <span class="hidden-mobile hidden-tablet"> Maps </span> </a>
						</li>

						<li>
							<a data-toggle="tab" href="#hr6"> <i class="fa fa-lg fa-area-chart"></i> <span class="hidden-mobile hidden-tablet"> BI Graphs  </span> </a>
						</li>

						<li>
							<a data-toggle="tab" href="#hr7"> <i class="fa fa-lg fa-history"></i> <span class="hidden-mobile hidden-tablet"> History  </span> </a>
						</li>

					</ul>
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
						<div class="tab-content">
							<div class="tab-pane active" id="hr1"></div>
							<div class="tab-pane" id="hr2"></div>
							<div class="tab-pane" id="hr3"></div>
							<div class="tab-pane" id="hr4"></div>
							<div class="tab-pane" id="hr5">
								<div class="row">
									<article class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										<div id="hr1Title"></div>
										<div id="hr1PK"></div>
										<div class="icon-addon addon-lg">
											<select id="hr1Cols" class='form-control input-lg' id='hr1Cols' name='hr1Cols'>
												<option value='0'>Select Column Name</option>
											</select>
											<!-- <label for="hr1Cols" class="fa fa-object-group" rel="tooltip" title="hr1Cols"></label> -->
										</div>
									</article>
									<article class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										<div id="hr2Title"></div>
										<div id="hr2PK"></div>
										<!-- <div id="hr2Cols"></div> -->
										<div class="icon-addon addon-lg">
											<select class='form-control input-lg' id='hr2Cols' name='hr2Cols'>
												<option value='0'>Select Column Name</option>
											</select>
											<!-- <label for="hr1Cols" class="fa fa-object-group" rel="tooltip" title="hr1Cols"></label> -->
										</div>
									</article>
									<article class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										<div id="hr3Title"></div>
										<div id="hr3PK"></div>
										<!-- <div id="hr3Cols"></div> -->
										<div class="icon-addon addon-lg">
											<select class='form-control input-lg' id='hr3Cols' name='hr3Cols'>
												<option value='0'>Select Column Name</option>
											</select>
											<!-- <label for="hr1Cols" class="fa fa-object-group" rel="tooltip" title="hr1Cols"></label> -->
										</div>
									</article>
									<article class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
										<div id="hr4Title"></div>
										<div id="hr4PK"></div>
										<!-- <div id="hr4Cols"></div> -->
										<div class="icon-addon addon-lg">
											<select class='form-control input-lg' id='hr4Cols' name='hr4Cols'>
												<option value='0'>Select Column Name</option>
											</select>
											<!-- <label for="hr1Cols" class="fa fa-object-group" rel="tooltip" title="hr1Cols"></label> -->
										</div>
									</article>
								</div>
								<div class="row">
									
								</div>
								<div class="row">
									<table id="" class="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox">
										<thead>
											<tr>
												<th>map_id</th>
												<th>source_id</th>
												<th>source_name</th>
												<th>source_type</th>
												<th>source_size</th>
												<th>destination_id</th>
												<th>destination_name</th>
												<th>destination_type</th>
												<th>destination_size</th>
												<th>created_date</th>
												<th>updated_date</th>
												<th>deleted</th>
												<th>user_id</th>
												<th>update_user_id</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane" id="hr6"></div>
							<div class="tab-pane" id="hr7"></div>
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

<script type="text/javascript">

	pageSetUp();

	var dtTable1 = undefined;
	var dtTable2 = undefined;
	var dtTable3 = undefined;
	var dtTable4 = undefined;
	var responsiveHelper1 = undefined;
	var responsiveHelper2 = undefined;
	var responsiveHelper3 = undefined;
	var responsiveHelper4 = undefined;

	var source1 = undefined;
	var source2 = undefined;
	var source3 = undefined;
	var source4 = undefined;

	var sCom1 = undefined;
	var sCom2 = undefined;
	var sCom3 = undefined;
	var sCom4 = undefined;

	// var dtData = [{"holder1":'value1',"holder2":'value2',"holder3":'value3',"holder4":'value4'}];
	// var columns = [{data:'holder1'},{data:'holder2'},{data:'holder3'},{data:'holder4'}];
	// var columnsValue = ['holder1','holder2','holder3','holder4'];
	// var columns = [];
	// var columnsValue = [];

	// makeThead(columnsValue);


	// var responsiveHelper_dt_holder = undefined;
	
	var breakpointDefinition = {
		tablet : 1024,
		phone : 480
	};
	
	// pageSetUp();

	var pagefunction = function() {
		// createDataTable(dtData,columns);
		
	    

		// $('#dt_holder tbody').on( 'click', 'button', function () {  // would be for a button on the row.
		// $('#dt_holder tbody').on( 'click', 'tr',function () {
			// console.log($(this).find(".CommunityID").html());
			// var theCode = $(this).find(".CommunityID").html();
			// getCommunityForm($(this).find(".CommunityID").html());
		// } );

		// custom toolbar
			// $("div.toolbar").html('<div class="text-right"><img src="img/logo2.png" alt="Fischer Homes" style=" height:20px; margin-top: 1px; margin-right: 10px;"></div>');
			// table.columns( [ 0, 1, 2, 3 ] ).visible( false, false );
			// otable.columns([5,6,7,8,9,10]).visible(false,false,false,false,false,false);
			// otable.columns([5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21]).visible(false);
		// Apply the filter
		// $("#dt_holder thead th input[type=text]").on( 'keyup change', function () {
		//     dtTable
		//         .column( $(this).parent().index()+':visible' )
		//         .search( this.value )
		//         .draw();
		// } );
	    /* END Community Table */
	};
	
	// end pagefunction
	
	// run pagefunction
	loadScript("js/plugin/datatables/jquery.dataTables.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.js", pagefunction)
				});
			});
		});
	});


	function makeThead(cols = null, theadId = null){
		if(cols && theadId){
			// $('#thead01').html("");
			$('#'+theadId).html("");
			var c = 0;
			var html = '<tr>';
			$.each(cols, function(index, val) {
				html += '<th class="hasinput" style="width:17%"><input type="text" class="form-control" placeholder="'+val+'" /></th>';
				// html += '<th class="hasinput"><input type="text" class="form-control" placeholder="'+val+'" /></th>';
			});
			html += '</tr><tr>';
			$.each(cols, function(index, val) {
				if(c == 0){
					html += '<th data-class="expand">'+val+'</th>';  // for responsive this is the expanding cell
				}else if(c != 0 && c < 4){
					html += '<th>'+val+'</th>';  // these will always be shown 
				}else{
					html += '<th data-hide="phone,tablet">'+val+'</th>' // this will be hidden automaticly on phone and tablet
				}
				c++;
			});
			html += '</tr>';
			// $('#thead01').html(html);
			$('#'+theadId).html(html);
			// pagefunction(); // can not be called here becuase it is not loaded in this don't really understand why.
		}
	}

	/* 
		might be able to get by without a dtTable.destroy() 
		tab id, 
		table id (holder),
			dt_holder1 = source 1
			dt_holder2 = source 2 
			etc...
		thead id, 
		record1 which is the data[0] passed through defineColumns().
	*/
	function resetDataTable(tabId = null, tableId = null, theadId = null, record1 = null){
		/*if(tabId == "hr1" && dtTable1){
			dtTable1.destroy();
		}else if(tabId == "hr2" && dtTable2){
			dtTable2.destroy();
		}else if(tabId == "hr3" && dtTable3){
			dtTable3.destroy();
		}else if(tabId == "hr4" && dtTable4){
			dtTable4.destroy();
		}*/			

		if(tabId && tableId && theadId && record1){
			$('#'+tabId).html("");
			var nHtml = '<table id="'+tableId+'" class="table table-striped table-bordered display responsive nowrap" width="100%"><thead id="'+theadId+'"></thead><tbody></tbody></table>';
			$('#'+tabId).html(nHtml);
		
			makeThead(record1, theadId);
		}
	}

	function updateObjectsSapphire(sourceId = 0){
		$.ajax({
			url: '/sapi/getEndPoints/',
			type: 'GET',
			dataType: 'json',
			data : {
				sourceId:sourceId,
			},
			headers: {
				Authorization : '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'
			}
		})
		.done(function(data) {
			console.log("success");
			// console.log('index:'+index+' val:'+val);
			$('#objects').empty();
			$('#objects').append('<option value="0">Select Object</option>');
			// $.each(data.SapphireApis, function(index, val) {
			$.each(data.SapphireApis, function(index, val) {
				$('#objects').append('<option value="'+val.Id+'">'+val.ModalName+' ('+val.Version+')</option>');
			});
			$('#objects').val(0);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	/*This is going to need to be flat column names*/
	function defineColumns(data){
		columnsValue = Array();
		if(data){
			// columnsValue = Array();
			$.each(data, function(index, val) {
				columnsValue.push(index);
			});
			// makeThead(columnsValue);
		}
		return columnsValue;
	}

	$('#source').change(function(event) {
		/* Act on the event */
		if($(this).val() != 0){
			updateObjectsSapphire($(this).val());
			// $('#objects').show();
		}else{
			$('#objects').empty();
			$('#objects').append('<option value="0">Select Object</option>');
			// resetDataTable();
		}
	});

	$('#objects').change(function(event) {
		var tmp = $(this).val();
		var tTab = $('#targetTab').val();
		var tableId = tTab+'dtTable';
		var theadId = tTab+'Thead';
		var sId = $('#source').val();

		if(tmp != 0 && $('#source').val() != 0 && tTab != 0){
			$.ajax({
				url: '/sapi/byId/'+tmp+'/',
				type: 'GET',
				dataType: 'JSON',
				headers: {
					Authorization : '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'
				}
			})
			.done(function(data) {
				// console.log("success");
				// dtData = data.data;
				// columns = data.map;

				if(data.data[0]){
					record1 = defineColumns(data.data[0]);
					resetDataTable(tTab, tableId, theadId, record1);
					// createDataTable();
					// createDataTable(data.data, data.dataTablesDef, tableId);
					// alert();
					/* this needs to be rewritten because it can be done on four lines */
					switch(tTab) {
						case "hr1":
							sCom1 = record1;
							source1 = data.sourceInfo;
							createDataTable(data.data, data.dataTablesDef, tableId, dtTable1, responsiveHelper1);
							$('.hr1Header').html(data.sourceInfo.ModalName+' '+data.sourceInfo.Id );
							$('#hr1Title').html(data.sourceInfo.ModalName+' '+data.sourceInfo.Id );
							$('#hr1PK').html(data.sourceInfo.PrimaryRecord);
							// $('#hr1Cols').html('<pre>'+record1+'</pre>');
							break;
						case "hr2":
							sCom2 = record1;
							source2 = data.sourceInfo;
							createDataTable(data.data, data.dataTablesDef, tableId, dtTable2, responsiveHelper2);
							$('.hr2Header').html(data.sourceInfo.ModalName+' '+data.sourceInfo.Id );
							$('#hr2Title').html(data.sourceInfo.ModalName+' '+data.sourceInfo.Id );
							$('#hr2PK').html(data.sourceInfo.PrimaryRecord);
							// $('#hr2Cols').html('<pre>'+record1+'</pre>');
							break;
						case "hr3":
							sCom3 = record1;
							source3 = data.sourceInfo;
							createDataTable(data.data, data.dataTablesDef, tableId, dtTable3, responsiveHelper4);
							$('.hr3Header').html(data.sourceInfo.ModalName+' '+data.sourceInfo.Id );
							$('#hr3Title').html(data.sourceInfo.ModalName+' '+data.sourceInfo.Id );
							$('#hr3PK').html(data.sourceInfo.PrimaryRecord);
							// $('#hr3Cols').html('<pre>'+record1+'</pre>');
							break;
						case "hr4":
							sCom4 = record1;
							source5 = data.sourceInfo;
							createDataTable(data.data, data.dataTablesDef, tableId, dtTable3, responsiveHelper4);
							$('.hr4Header').html(data.sourceInfo.ModalName+' '+data.sourceInfo.Id );
							$('#hr4Title').html(data.sourceInfo.ModalName+' '+data.sourceInfo.Id );
							$('#hr4PK').html(data.sourceInfo.PrimaryRecord);
							// $('#hr4Cols').html('<pre>'+record1+'</pre>');
							break;						
					}
				}
			}).then(function(){

			}).fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		}
	});

	/*$('#targetTab').change(function(event) {
		if($(this).val() == 1){
			updateObjectsSapphire();
			// $('#objects').show();
		}else{
			$('#objects').empty();
			$('#objects').append('<option value="0">Select Object</option>');
			// resetDataTable();
		}
	});*/


	$(document).ready(function() {
		// updateObjects();
	});


	function createDataTable(dtData, columnDefs, tableId, dt, rHelper){
	// function createDataTable(){
		
		// responsiveHelper_dt_holder = undefined;
		// responsiveHelper = undefined;
		
		// console.log(columnDefs);
		// console.log(dtData);

		dt = $('#'+tableId).DataTable({
			data : dtData,
			columns : columnDefs,
			// columnDefs:[
			// 	{
			// 		"targets": columnsValue,
			// 		"data": dtData
			// 	}
			// ],
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
			"autoWidth" : true,
			"preDrawCallback" : function() {
			// Initialize the responsive datatables helper once.
				// if (!responsiveHelper1) {
					rHelper = new ResponsiveDatatablesHelper($('#'+tableId), breakpointDefinition);
					// responsiveHelper1 = new ResponsiveDatatablesHelper($('#'+tableId), breakpointDefinition);
				// }
				/*if($('#targetTab').val() == 'hr1'){
					responsiveHelper1 = new ResponsiveDatatablesHelper($('#'+tableId), breakpointDefinition);
				}else if($('#targetTab').val() == 'hr2'){
					responsiveHelper2 = new ResponsiveDatatablesHelper($('#'+tableId), breakpointDefinition);
				}else if($('#targetTab').val() == 'hr3'){
					responsiveHelper3 = new ResponsiveDatatablesHelper($('#'+tableId), breakpointDefinition);
				}else if($('#targetTab').val() == 'hr4'){
					responsiveHelper4 = new ResponsiveDatatablesHelper($('#'+tableId), breakpointDefinition);
				}*/
			},
			"rowCallback" : function(nRow) {
				rHelper.createExpandIcon(nRow);
				// responsiveHelper1.createExpandIcon(nRow);
				/*if($('#targetTab').val() == 'hr1'){
					responsiveHelper1.createExpandIcon(nRow);
				}else if($('#targetTab').val() == 'hr2'){
					responsiveHelper2.createExpandIcon(nRow);
				}else if($('#targetTab').val() == 'hr3'){
					responsiveHelper3.createExpandIcon(nRow);
				}else if($('#targetTab').val() == 'hr4'){
					responsiveHelper4.createExpandIcon(nRow);
				}*/
			},
			"drawCallback" : function(oSettings) {
				rHelper.respond();
				// responsiveHelper1.respond();
				/*if($('#targetTab').val() == 'hr1'){
					responsiveHelper1.respond();
				}else if($('#targetTab').val() == 'hr2'){
					responsiveHelper2.respond();
				}else if($('#targetTab').val() == 'hr3'){
					responsiveHelper3.respond();
				}else if($('#targetTab').val() == 'hr4'){
					responsiveHelper4.respond();
				}*/
			}
		});

		$("#"+tableId+" thead th input[type=text]").on( 'keyup change', function () {
		    dt
		        .column( $(this).parent().index()+':visible' )
		        .search( this.value )
		        .draw();
		} );
	};
</script>
