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
			<i class="fa-fw fa fa-rocket"></i> 
				API
			<span>>  
				Tokens
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

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">
		
		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-0">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-lock"></i> </span>
					<h2>Tokens </h2>				
					
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
						<a href="javascript:addToken(<?php echo $_SESSION['userId'] ?>)" class="btn btn-success" >Add Token</a><br />
						<table id="dt_tokens" class="table table-striped table-bordered table-hover" width="100%">
							<thead>			                
								<tr>
									<th data-hide="phone,tablet">Update</th>
									<th data-hide="phone,tablet">User</th>
									<th data-hide="phone,tablet">Access</th>
									<th data-class="expand">Name</th>
									<th data-hide="phone">Description</th>
									<th data-hide="phone,tablet">Created Date</th>
									<th data-hide="phone,tablet">Last Updated</th>
									<th data-hide="phone,tablet"><i class="fa fa-fw fa-exclamation-circle txt-color-blue hidden-md hidden-sm hidden-xs"></i> Expiration</th>
									<th>isValid?</th>
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

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-2">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-unlock"></i> </span>
					<h2>Update Token </h2>				
					
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
						
						<form action="" id="updateToken" class="smart-form" novalidate="novalidate">
							<header>
								Update Token
							</header>

							<fieldset>
								<input type="hidden" name="Id" id="Id" value="">
								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-rocket"></i>
											<input type="text" id="Name" name="Name" placeholder="Name">
										</label>
									</section>
								</div>
								<div class="row">
									
								</div>
								<section>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i> 										
										<textarea rows="5" id="ShortDesc" name="ShortDesc" placeholder="Short Discription goes here."></textarea> 
									</label>
								</section>
							</fieldset>

							<fieldset>
								<div class="row">
									<section class="col col-6">
										<label for="AccessId"> Access</label>
										<label class="select">
											<select name="AccessId" id="AccessId">
												<option value="">Select Access</option>
											</select> <i></i> </label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
											<input type="text" name="ExpirationDate" id="ExpirationDate" placeholder="Expiration date">
										</label>
									</section>
									<section class="col col-6">
										<!-- <input class="form-control" id="ExpirationTime" name="ExpirationTime" type="text" placeholder="Select time">
											<span class="input-group-addon"><i class="fa fa-clock-o"></i></span> -->
										<label class="input"> <i class="icon-append fa fa-clock-o"></i>
											<input type="text" id="ExpirationTime" name="ExpirationTime" placeholder="Expiration date">
										</label>
									</section>
								</div>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary">
									Update Token
								</button>
							</footer>
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
			desktop: 1333
		};

		makeCallData();

		dt1 = $('#dt_tokens').DataTable({
			"ajax": {
				"url":"/api/tokens/",
				"type": 'GET',
				"headers": apiCallData
			},
			// "ajax" : "/di/function/getJobs/",
			"order": [[ 7, "asc" ]],
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l C >r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			// "columnDefs": [
			// 	{ "visible": false, "targets": 1 },
			// 	{ "visible": false, "targets": 2 },
			// 	{ "visible": false, "targets": 5 },
			// 	{ "visible": false, "targets": 6 }
			// ],
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_tokens'), breakpointDefinition);
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
		var $updateToken = $("#updateToken").validate({
			// Rules for form validation
			rules : {
				Name : {
					required : true
				},
				ShortDesc : {
					required : true,
					minlength : 30
				},
				AccessId: {
					required : true
				},
				ExpirationDate: {
					required: true
				},
				ExpirationTime : {
					required: true
				}
			},

			// Messages for form validation
			messages : {
				Name : {
					required : 'Please enter Token name',
				},
				ShortDesc : {
					required : 'Please enter Token Description'
				},
				AccessId: {
					required : 'Please select an Access'
				},
				ExpirationDate: {
					required: 'Please select an expiration date'
				},
				ExpirationTime : {
					required: 'Please select an expiration time'
				}
			},

			// Ajax form submition
			submitHandler : function(form) {
				var formData = $('#updateToken').serializeObject();
				console.log(formData);
				$.ajax({
					url: '/path/to/file',
					type: 'POST',
					headers: apiCallData,
					// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
					data: formData,
				})
				.done(function(data) {
					console.log("success on update");
				})
				.fail(function() {
					console.log("error on update");

				});
				
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	}

	loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction2);

	loadScript("js/plugin/bootstrap-timepicker/bootstrap-timepicker.min.js", runTimePicker);
	
	function runTimePicker() {
	    $('#ExpirationTime').timepicker({
	    	minuteStep: 5,
	    	showSeconds: true
	    });
	}

	$("#ExpirationDate").datepicker({
		dateFormat : 'yy-mm-dd',
	    changeMonth: true,
	    numberOfMonths: 3,
	    prevText: '<i class="fa fa-chevron-left"></i>',
	    nextText: '<i class="fa fa-chevron-right"></i>',
	});

	function getAccess(){
		makeCallData();
		$.ajax({
			url: '/api/tokens/access/',
			type: 'GET',
			dataType: 'json',
			headers: apiCallData
		})
		.done(function(data, textStatus, xhr) {
			console.log("success");
			// console.log(data);
			$.each(data.Accesses, function(key, value) {   
				// console.log('value.Id:'+value.Id + '  value.Name:' +value.Name);
    			// $('#name').append($("<option></option>").attr("value",key).text(value)); 
    			$('#AccessId').append($("<option></option>").attr("value",value.Id).text(value.Name)); 
			});
		})
		.fail(function() {
			console.log("error");
		});	
	}

	function getInfoToken(Id){
		// alert(Id);
		$.ajax({
			url: '/api/tokens/getTokenById/',
			type: 'POST',
			dataType: 'json',
			data: {Id:Id},
			headers:apiCallData
		})
		.done(function(data) {
			console.log("success on getInfoToken");
			// console.log(data);
			runTimePicker();
			$('#Id').val(data.Id);
			$('#AccessId').val(data.AccessId);
			$('#Name').val(data.Name);
			$('#ShortDesc').val(data.ShortDesc);
			$('#ExpirationDate').val(data.ExpirationDate);
			$('#ExpirationTime').val(data.ExpirationTime);
		})
		.fail(function() {
			console.log("error on getInfoToken");
		});
		
	}

	$(document).ready(function() {
		getAccess();	
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
