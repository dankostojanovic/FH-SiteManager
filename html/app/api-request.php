<?php 
require_once("../init.php");
checkLogin();
require_once("inc/init.php");
?>
<style type="text/css" media="screen">
	body .modal-admin {
    	/* new custom width */
    	width: 1600px;
    	margin-auto;
    	/* must be half of the width, minus scrollbar on the left (30px) */
    	/*margin-left: -280px;*/
	}	
</style>
<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-rocket"></i> 
				API
			<span>>  
				Requests
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
			<div class="jarviswidget" id="wid-id-0"  data-widget-editbutton="false">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-rocket"></i> </span>
					<h2>API Request Data</h2>				
					
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
						<table id="dt_request" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th></th>
									<!-- <th class="hasinput" style="width:11%">
										<input type="text" class="form-control" placeholder="Request Id" />
									</th> -->
									<th class="hasinput" style="width:11%">
										<select >
											<option value="">All</option>
											<option value="0">unaccepted</option>
											<?php 
												$tokens = \apidb\TokenQuery::Create()->find();
												foreach ($tokens as $key => $token) {
													echo "<option value=\"".$token->getId()."\">".$token->getName()."</option>";
												}
											?>
										</select>
										<!-- <input type="text" class="form-control" placeholder="Token Id" /> -->
									</th>
									<th class="hasinput" style="width:11%">
										<input type="text" class="form-control" placeholder="Header" />
									</th>
									<th class="hasinput" style="width:11%">
										<input type="text" class="form-control" placeholder="Domain" />
									</th>
									<th class="hasinput" style="width:11%">
										<input type="text" class="form-control" placeholder="Request Variables" />
									</th>
									<th class="hasinput" style="width:11%">
										<input type="text" class="form-control" placeholder="Data Before" />
									</th>
									<th class="hasinput" style="width:11%">
										<input type="text" class="form-control" placeholder="Data After" />
									</th>
									<th class="hasinput" style="width:11%">
										<input type="text" class="form-control" placeholder="Route" />
									</th>
									<th class="hasinput" style="width:11%">
										<input type="text" class="form-control" placeholder="Request IP" />
									</th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<th data-hide="phone,tablet">ID</th>
									<th data-hide="phone,tablet">token_id</th>
									<th data-hide="phone,tablet">header</th>
									<th data-hide="phone,tablet">domain</th>
									<th data-class="phone,tablet">request_variables</th>
									<th data-hide="phone">data_before</th>
									<th data-hide="phone,tablet">data_after</th>
									<th data-hide="expand">Route</th>
									<th data-hide="phone">Requesting IP</th>
									<!-- <th data-hide="phone">State Date</th>
									<th data-hide="phone">Stop Date</th>
									<th data-hide="phone,tablet">Start</th>
									<th data-hide="phone">Stop</th> -->
									<th data-hide="phone,tablet">Date</th>
									<th data-hide="phone,tablet">Time</th>
									<th data-hide="phone,tablet">Response Time</th>
									<th data-hide="phone,tablet">Accepted</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
							<tfoot>
								<tr>
									<th >ID</th>
									<th >token_id</th>
									<th >header</th>
									<th >domain</th>
									<th >request_variables</th>
									<th >data_before</th>
									<th >data_after</th>
									<th >Route</th>
									<th >Requesting IP</th>
									<th >Date</th>
									<th >Time</th>
									<th >Response Time</th>
									<th >Accepted</th>
								</tr>
							</tfoot>
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

<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="modal-content">
			<div class="modal-header" id="modal-header">Header here</div>
			<div class="modal-body" id="modal-body">
				<p>Modal Body here!</p>
			</div>
			<div class="modal-footer" id="modal-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="compareModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="compare-content">
			<div class="modal-header" id="compare-header">Compare Table</div>
			<div class="modal-body" id="compare-body">
				<table id="dt_compare" class="table table-striped table-bordered table-hover" width="100%">
					<thead>
						<tr>
							<th class="hasinput" style="width:25%">
								<input type="text" class="form-control" placeholder="Column" />
							</th>
							<th class="hasinput" style="width:25%">
								<input type="text" class="form-control" placeholder="Before" />
							</th>
							<th class="hasinput" style="width:25%">
								<input type="text" class="form-control" placeholder="After" />
							</th>
							<th class="hasinput" style="width:25%">
								<input type="text" class="form-control" placeholder="Changed" />
							</th>
						</tr>
						<tr>
							<th >Column</th>
							<th >Before</th>
							<th >After</th>
							<th >Changed</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th >Column</th>
							<th >Before</th>
							<th >After</th>
							<th >Changed</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer" id="compare-footer">
			    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			    <button type="button" class="btn btn-danger" >Revert Data</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="tarinaModal" tabindex="-2" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-admin">
		<div class="modal-content" id="tarina-content">
			<div class="modal-header" id="tarina-header">Compare Table</div>
			<div class="modal-body" id="tarina-body">
				<table id="dt_tarina" class="table table-striped table-bordered table-hover" width="100%">
					<thead>
						<tr>
							<th class="hasinput" style="width:14%">
								<input type="text" class="form-control" placeholder="Column" />
							</th>
							<th class="hasinput" style="width:14%">
								<input type="text" class="form-control" placeholder="Before" />
							</th>
							<th class="hasinput" style="width:14%">
								<input type="text" class="form-control" placeholder="After" />
							</th>
							<th class="hasinput" style="width:14%">
								<input type="text" class="form-control" placeholder="Changed" />
							</th>
							<th class="hasinput" style="width:14%">
								<input type="text" class="form-control" placeholder="Now" />
							</th>
							<th class="hasinput" style="width:14%">
								<input type="text" class="form-control" placeholder="Different" />
							</th>
							<th class="hasinput" style="width:14%">
								<input type="text" class="form-control" placeholder="Ticket Column" />
							</th>
						</tr>
						<tr>
							<th >Column</th>
							<th >Before</th>
							<th >After</th>
							<th >Changed</th>
							<th >Now</th>
							<th >Different</th>
							<th >Ticket Column</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th >Column</th>
							<th >Before</th>
							<th >After</th>
							<th >Changed</th>
							<th >Now</th>
							<th >Different</th>
							<th >Ticket Column</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer" id="tarina-footer">
			    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	pageSetUp();
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
	var dt2 = '';
	var apiCallData = new Array();

	function makeCallData(){
		// reset call data to have the api token.  Probably needs to be a Golbal variable and function.
		apiCallData['Authorization'] = "1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f";
		apiCallData['nolog'] = 1;
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

		dt2 = $('#dt_compare').DataTable();
		dt3 = $('#dt_tarina').DataTable();

		dt1 = $('#dt_request').DataTable({
			"processing": true,
        	"serverSide": true,
			"ajax": {
				"url":"/api/request/getDataTableData/",
				// "url":"/bypass.php",
				"type": 'POST',
				"headers": apiCallData
			},
			"order": [[ 0, "desc" ]],
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
	        /*this actually doesn't work but its a good start*/
	        /*initComplete: function () {
	            this.api().columns().every( function () {
	                var column = this;
	                var select = $('<select><option value=""></option></select>')
	                    .appendTo( $(column.footer()).empty() )
	                    .on( 'change', function () {
	                        var val = $.fn.dataTable.util.escapeRegex(
	                            $(this).val()
	                        );
	 
	                        column
	                            .search( val ? '^'+val+'$' : '', true, false )
	                            .draw();
	                    } );
	 
	                column.data().unique().sort().each( function ( d, j ) {
	                    select.append( '<option value="'+d+'">'+d+'</option>' )
	                } );
	            } );
	        },*/
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_request'), breakpointDefinition);
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
	    // $("#dt_request thead th input[type=text]").on( 'keyup change', function () {
	    $("#dt_request thead th :input").on( 'keyup change', function () {
	        dt1
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
	    } );

	    // $('select').on('change', function() {
	    /*$('#dt_request thead th input[type=select]').on('change', function() {
  			// alert( this.value ); // or $(this).val()
  			// alert($(this).parent());
  			// console.log($(this).parent().parent());
  			dt1
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
		});*/

		// Apply the filter
	    $("#dt_compare thead th input[type=text]").on( 'keyup change', function () {
	    // $("#dt_compare thead th :input").on( 'keyup change', function () {
	        dt2
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
	    } );

	    $("#dt_tarina thead th input[type=text]").on( 'keyup change', function () {
	    // $("#dt_compare thead th :input").on( 'keyup change', function () {
	        dt3
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

	var pagefunction2 = function() {

		var updateOutput = function(e) {
			var list = e.length ? e : $(e.target), output = list.data('output');
			if (window.JSON) {
				output.val(window.JSON.stringify(list.nestable('serialize')));
				//, null, 2));
			} else {
				output.val('JSON browser support required for this demo.');
			}
		};

		// activate Nestable for list 1
		/*$('#nestable').nestable({
			group : 1
		}).on('change', updateOutput);

		// activate Nestable for list 2
		$('#nestable2').nestable({
			group : 1
		}).on('change', updateOutput);

		// output initial serialised data
		updateOutput($('#nestable').data('output', $('#nestable-output')));
		updateOutput($('#nestable2').data('output', $('#nestable2-output')));*/

		/*$('#nestable-menu').on('click', function(e) {
			alert("did this happen?");
			var target = $(e.target), action = target.data('action');
			if (action === 'expand-all') {
				$('.dd').nestable('expandAll');
			}
			if (action === 'collapse-all') {
				$('.dd').nestable('collapseAll');
			}
		});*/

		// $('#nestable3').nestable();
		
	};
	
	// end pagefunction
	
	// load nestable.min.js then run pagefunction
	loadScript("js/plugin/jquery-nestable/jquery.nestable.min.js", pagefunction2);

	/*I got this buildItem function that I modified from http://jsfiddle.net/burakoztirpan/53WSc/*/
	function buildItem(key, item) {

		var html = "<li class='dd-item' data-id='" + key + "' id='" + key + "'>";
		html += "<div class='dd-handle'>" + key + "</div>";
		html += "<ol class='dd-list'>"+item+"</ol>";
		html += "</li>";

		return html;
	}

	function buildItemOrg(key, item) {

	    var html = "<li class='dd-item' data-id='" + key + "' id='" + key + "'>";
	    html += "<div class='dd-handle'>" + key + "</div>";
	    // console.log(item);
	    // if (item) {
	        html += "<ol class='dd-list'>";
	        $.each(item, function (index, sub) {
	            // html += buildItemOrg(index,sub);
	            html+= "<li class=\"dd-item\" data-id=\""+index+sub+"\"><div class=\"dd-handle\">"+index+": "+sub+"</div></li>";
	        });
	        html += "</ol>";
	    // }
	    html += "</li>";
	    return html;
	}


	function makeModal(data, item){
		if(data){
			$('#modal-header').html('<h1>Id:'+data.Id+'  Route:'+data.Route+'</h1>');
			$('#modal-body').html("<div id='nestable-menu'><button type='button' class='btn btn-success' data-action='expand-all'>Expand All</button><button type='button' class='btn btn-danger' data-action='collapse-all'>Collapse All</button></div>");
			$('#modal-body').append("<div id='nestable'><ul class='dd-list'></ul></div>");
			$.each(JSON.parse(item), function (index, item) {
				$('#nestable ul').append(buildItem(index, item));
			});
			setUpBTNS();
			$('#nestable').nestable();
			$('#remoteModal').modal('show');
		}
	}

	function makeModalDiff(data, item){
		if(data){
			data.diff = item;
			$('#modal-header').html('<h1>Id:'+data.Id+'  Route:'+data.Route+'</h1>');
			$('#modal-body').html("<div id='nestable-menu'><button type='button' class='btn btn-success' data-action='expand-all'>Expand All</button><button type='button' class='btn btn-danger' data-action='collapse-all'>Collapse All</button></div>");
			$('#modal-body').append("<div id='nestable'><ul class='dd-list'></ul></div>");
			$.each(item, function (index, item) {
				// $('#nestable ul').append(buildItem(index, item));
				$('#nestable ul').append(buildItemOrg(index, item));
			});
			setUpBTNS();
			$('#nestable').nestable();
			$('#remoteModal').modal('show');
		}
	}

	function viewHeaders(id){
		// $('#remoteModal').modal('show');
		if(id){
			makeCallData();
			$.ajax({
				url: '/api/request/'+id+'/',
				type: 'GET',
				dataType: 'json',
				headers: apiCallData
				// data: {param1: 'value1'},
			})
			.done(function(data) {
				// console.log("success");
				console.log(data);
				/*$('#modal-header').html('<h1>Id:'+data.Id+'  Route:'+data.Route+'</h1>');
				$('#modal-body').html("<div id='nestable'><ul class='dd-list'></ul></div>");
				$.each(JSON.parse(data.Header), function (index, item) {
    				$('#nestable ul').append(buildItem(index, item));
    			});

				$('#nestable').nestable();
				$('#remoteModal').modal('show');*/
				makeModal(data, data.Header);
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		}
	}

	function viewVariables(id){
		// $('#remoteModal').modal('show');
		if(id){
			makeCallData();
			$.ajax({
				url: '/api/request/'+id+'/',
				type: 'GET',
				dataType: 'json',
				headers: apiCallData
				// data: {param1: 'value1'},
			})
			.done(function(data) {
				// console.log("success");
				console.log(data);
				makeModal(data, data.RequestVariables);
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		}
	}

	function viewDataAfter(id){
		// $('#remoteModal').modal('show');
		if(id){
			makeCallData();
			$.ajax({
				url: '/api/request/'+id+'/',
				type: 'GET',
				dataType: 'json',
				headers: apiCallData
				// data: {param1: 'value1'},
			})
			.done(function(data) {
				// console.log("success");
				console.log(data);
				makeModal(data, data.DataAfter);
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		}
	}

	function viewDataBefore(id){
		// $('#remoteModal').modal('show');
		if(id){
			makeCallData();
			$.ajax({
				url: '/api/request/'+id+'/',
				type: 'GET',
				dataType: 'json',
				headers: apiCallData
				// data: {param1: 'value1'},
			})
			.done(function(data) {
				// console.log("success");
				console.log(data);
				makeModal(data, data.DataBefore);
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		}
	}

	function setUpBTNS(){
		$('#nestable-menu').on('click', function(e) {
			// alert("did this happen?");
			var target = $(e.target), action = target.data('action');
			if (action === 'expand-all') {
				// $('.dd').nestable('expandAll');
				$('#nestable').nestable('expandAll');
			}
			if (action === 'collapse-all') {
				// $('.dd').nestable('collapseAll');
				$('#nestable').nestable('collapseAll');
			}
		});
	}

	function compareData(id){
		if(id){
			makeCallData();
			$.ajax({
				url: '/api/request/'+id+'/',
				type: 'GET',
				dataType: 'json',
				headers: apiCallData
				// data: {param1: 'value1'},
			})
			.done(function(data) {
				// console.log("success");
				// console.log(data);
				var difference = [];
				if(data.DataBefore && data.DataAfter){
					// console.log(data.DataBefore);
					// console.log(data.DataAfter);
					var obj1 = jQuery.parseJSON( data.DataBefore);
					var obj2 = jQuery.parseJSON( data.DataAfter);
					// difference = getDifferences(data.DataBefore, data.DataAfter);
					difference = getDifferences(obj1, obj2);
					console.log(difference);
					console.log(difference.toString());
					makeModalDiff(data, difference);
					// testDiff = findCommonProps(obj1,obj2);
					// console.log(testDiff);
				}
				// makeModal(data, data.DataBefore);
				// console.log(difference);
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		}
	}

	/*Object.extend(Object, {
	   deepEquals: function(o1, o2) {
	     var k1 = Object.keys(o1).sort();
	     var k2 = Object.keys(o2).sort();
	     if (k1.length != k2.length) return false;
	     return k1.zip(k2, function(keyPair) {
	       if(typeof o1[keyPair[0]] == typeof o2[keyPair[1]] == "object"){
	         return deepEquals(o1[keyPair[0]], o2[keyPair[1]])
	       } else {
	         return o1[keyPair[0]] == o2[keyPair[1]];
	       }
	     }).all();
	   }
	});*/

	function getDifferences(oldObj, newObj) {
		var diff = {};
		// var diff = new Array();
		for (var k in oldObj) {
			if (!(k in newObj)){
				// diff[k] = undefined;  // property gone so explicitly set it undefined
			}
			else if (oldObj[k] !== newObj[k]){
				// diff[k] = newObj[k];  // property in both but has changed
				/*diff[k]['new'] = newObj[k];
				diff[k]['old'] = oldObj[k];*/
				// diff[k] = array('new' = newObj[k], 'old' = oldObj[k]);
				// diff[k] = [['new', newObj[k]],['old', oldObj[k]]]; //works but I want it to be different
				// diff[k] = ['new', newObj[k],'old', oldObj[k]];
				diff[k] = { "new":newObj[k], "old":oldObj[k]};
			}
		}
		for (k in newObj) {
			if (!(k in oldObj)){
				diff[k]['new'] = newObj[k]; // property is new
			}
		}
		return diff;
	}

	function findCommonProps(obj1, obj2) {
	    var map1 = {}, map2 = {};
	    var commonProps = [];

	    function isArray(item) {
	        return Object.prototype.toString.call(item) === "[object Array]";
	    }

	    function getProps(item, map) {
	        if (typeof item === "object") {
	            if (isArray(item)) {
	                // iterate through all array elements
	                for (var i = 0; i < item.length; i++) {
	                    getProps(item[i], map);
	                }
	            } else {
	                for (var prop in item) {
	                    map[prop] = true;
	                    // recursively get any nested props
	                    // if this turns out to be an object or array
	                    getProps(item[prop], map);
	                }
	            }
	        }
	    }

	    // get all properties in obj1 into a map
	    getProps(obj1, map1);
	    getProps(obj2, map2);
	    for (var prop in map1) {
	        if (prop in map2) {
	            commonProps.push(prop);
	        }
	    }
	    return commonProps;
	}


	function compareTable(id){
		if(id){
			dt2.destroy();
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;
			
			var breakpointDefinition = {
				tablet : 1024,
				phone : 480,
			};
			makeCallData();
			// alert("before");
			dt2 = $('#dt_compare').DataTable({
				"processing": false,
	        	"serverSide": false,
				"ajax": {
					"url":"/api/request/getDataTableCompare/"+id+"/",
					"type": 'POST',
					"headers": apiCallData
				},
				"order": [[ 0, "asc" ]],
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs' l T r>>"+
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
		        /*this actually doesn't work but its a good start*/
		        /*initComplete: function () {
		            this.api().columns().every( function () {
		                var column = this;
		                var select = $('<select><option value=""></option></select>')
		                    .appendTo( $(column.footer()).empty() )
		                    .on( 'change', function () {
		                        var val = $.fn.dataTable.util.escapeRegex(
		                            $(this).val()
		                        );
		 
		                        column
		                            .search( val ? '^'+val+'$' : '', true, false )
		                            .draw();
		                    } );
		 
		                column.data().unique().sort().each( function ( d, j ) {
		                    select.append( '<option value="'+d+'">'+d+'</option>' )
		                } );
		            } );
		        },*/
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_dt_basic) {
						responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_compare'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_dt_basic.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_dt_basic.respond();
				}
			});

			$('#compareModal').modal('show');
		}
	}

	function tarinaTable(id){
		if(id){
			dt3.destroy();
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;
			
			var breakpointDefinition = {
				tablet : 1024,
				phone : 480,
			};
			makeCallData();
			// alert("before");
			dt3 = $('#dt_tarina').DataTable({
				"processing": false,
	        	"serverSide": false,
				"ajax": {
					"url":"/api/request/getTarinaCompare/"+id+"/",
					"type": 'POST',
					"headers": apiCallData
				},
				"order": [[ 0, "asc" ]],
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs' l T r>>"+
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
		        /*this actually doesn't work but its a good start*/
		        /*initComplete: function () {
		            this.api().columns().every( function () {
		                var column = this;
		                var select = $('<select><option value=""></option></select>')
		                    .appendTo( $(column.footer()).empty() )
		                    .on( 'change', function () {
		                        var val = $.fn.dataTable.util.escapeRegex(
		                            $(this).val()
		                        );
		 
		                        column
		                            .search( val ? '^'+val+'$' : '', true, false )
		                            .draw();
		                    } );
		 
		                column.data().unique().sort().each( function ( d, j ) {
		                    select.append( '<option value="'+d+'">'+d+'</option>' )
		                } );
		            } );
		        },*/
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_dt_basic) {
						responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_tarina'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_dt_basic.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_dt_basic.respond();
				}
			});

			$('#tarinaModal').modal('show');
		}
	}
</script>
