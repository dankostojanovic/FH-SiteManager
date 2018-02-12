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
			<i class="fa-fw fa fa-check"></i> 
				API
			<span>>  
				Testing
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

<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-deletebutton="false">
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
					<h2>Test API </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">

						<div class="row">
							<form id="wizard-1" novalidate="novalidate">
								<div id="bootstrap-wizard-1" class="col-sm-12">
									<div class="form-bootstrapWizard">
										<ul class="bootstrapWizard form-wizard">
											<li class="active" data-target="#step1">
												<a href="#tab1" data-toggle="tab"> <span class="step">1</span> <span class="title">Select Test</span> </a>
											</li>
											<li data-target="#step2">
												<a href="#tab2" data-toggle="tab"> <span class="step">2</span> <span class="title">Test Specific data</span> </a>
											</li>
											<li data-target="#step3">
												<a href="#tab3" data-toggle="tab"> <span class="step">3</span> <span class="title">Confirm and Run</span> </a>
											</li>
											<li data-target="#step4">
												<a href="#tab4" data-toggle="tab"> <span class="step">4</span> <span class="title">View Results</span> </a>
											</li>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="tab-content">
										<div class="tab-pane active" id="tab1">
											<br>
											<h3><strong>Step 1 </strong> - Select Test</h3>

											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-map-marker fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="apiCall" id="apiCall">
																<option value="" selected="selected">Select API Test</option>
																<option value="updateFromCPA">UpdateFromCPA</option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div id="databaseGroup" class="input-group">
															<span class="input-group-addon"><i class="fa fa-database fa-lg fa-fw"></i></span>
															<select name="database" id="database" class="form-control input-lg">
																<option value="" selected="selected">Select Database</option>
																<option value="1" >Live Pervasive</option>
																<option value="2" >Staging Pervasive</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<div id="recordsGroup" class="input-group">
															<span class="input-group-addon"><i class="fa fa-sort-numeric-desc fa-lg fa-fw"></i></span>
															<select name="records" id="records" class="form-control input-lg ">
																<option value="" selected="selected">Select # of Records</option>
																<?php 
																$i = 5;
																while ($i < 200) {
																	echo "<option value='$i'>$i</option>";
																	$i+=5;
																}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div id="domainGroup" class="input-group">
															<span class="input-group-addon"><i class="fa fa-sitemap fa-lg fa-fw"></i></span>
															<select name="domain" id="domain" class="form-control input-lg">
																<option value="" selected="selected">Select Domain</option>
																<option value="1" >apollo.fischermgmt.com</option>
																<option value="2" >w0lf.ddns.net</option>
															</select>
														</div>
													</div>
												</div>
											</div>

										</div>
										<div class="tab-pane" id="tab2">
											<br>
											<h3><strong>Step 2</strong> - Test Specific data</h3>
											<div id="tab2-content"></div>
										</div>
										<div class="tab-pane" id="tab3">
											<br>
											<h3><strong>Step 3</strong> - Confirm and Run</h3>
											<div class="row">
												<div class="col-md-4 col-md-offset-5">
													<a href="javascript:runIt();" class="btn btn-lg btn-danger" >Run it!</a>
													<a href="javascript:clearIt();" class="btn btn-lg btn-success" >Clear Table!</a>
												</div>
											</div>
											<br>
											<div class="table-responsive">
												<table id="run-table" class="table table-bordered table-striped table-hover">
													<thead>
														<tr>
															<th>ACL </th>
															<th>Control Code</th>
															<th>Site Number</th>
															<th>Time (MS)</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
														
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane" id="tab4">
											<br>
											<h3><strong>Step 4</strong> - View Results</h3>
											<br>
											<h1 class="text-center text-success"><strong><i class="fa fa-check fa-lg"></i> Complete</strong></h1>
											<h4 class="text-center">Click next to finish</h4>
											<br>
											<br>
										</div>

										<div class="form-actions">
											<div class="row">
												<div class="col-sm-12">
													<ul class="pager wizard no-margin">
														<!--<li class="previous first disabled">
														<a href="javascript:void(0);" class="btn btn-lg btn-default"> First </a>
														</li>-->
														<li class="previous disabled">
															<a href="javascript:void(0);" class="btn btn-lg btn-default"> Previous </a>
														</li>
														<!--<li class="next last">
														<a href="javascript:void(0);" class="btn btn-lg btn-primary"> Last </a>
														</li>-->
														<li class="next">
															<a href="javascript:void(0);" class="btn btn-lg txt-color-darken"> Next </a>
														</li>
													</ul>
												</div>
											</div>
										</div>

									</div>
								</div>
							</form>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="modal-content">
			<div class="modal-header" id="modal-header">
				<!-- <a href="javascript:onPrevPage();" class="btn btn-danger">Previous Page</a>
				<a href="javascript:onNextPage();" class="btn btn-primary">Next Page</a> -->
			</div>
			<div class="modal-body" id="modal-body">
				<!-- <canvas id="the-canvas" class="img-responsive"></canvas> -->
				<div class="tabs-left">
					<ul class="nav nav-tabs tabs-left" id="demo-pill-nav">
						<li class="active">
							<a href="#tab-r1" data-toggle="tab">ACL PDF </a>
						</li>
						<li>
							<a href="#tab-r2" data-toggle="tab">ACL Data</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-r1">
							<canvas id="the-canvas" class="img-responsive"></canvas>
						</div>
						<div class="tab-pane" id="tab-r2">
							
						</div>
					</div>
				</div>
			</div>
			<div id="modal-bot"></div>
			<div class="modal-footer" id="modal-footer">
				<!-- Curent Page<div id="page_num"></div>
				Total Pages<div id="page_count"></div> -->
			    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="remote-content">
			<div class="modal-header" id="remote-header">Header here</div>
			<div class="modal-body" id="remote-body">
				<p>Modal Body here!</p>
			</div>
			<div class="modal-footer" id="remote-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="js/pdf.js"></script>
<script type="text/javascript" src="js/plugin/jquery-nestable/jquery.nestable.min.js"></script>
<script type="text/javascript">
	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();

	var acls = null;

	var pdfDoc = null,
		pageNum = 1,
		pageRendering = false,
		pageNumPending = null,
		scale = 1.5,
		canvas = document.getElementById('the-canvas'),
		ctx = canvas.getContext('2d');
	/*loadScript("js/pdf.js");
	loadScript("js/pdf.worker.js");
	loadScript("js/web/viewer.js");
	loadScript("js/web/compatibility.js");
	loadScript("js/web/debugger.js");
	loadScript("js/web/l10n.js");*/
	// loadScript("js/pdfobject.js");

	// PAGE RELATED SCRIPTS

	// pagefunction

	var pagefunction = function() {

		// load bootstrap wizard
		
		loadScript("js/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js", runBootstrapWizard);

		//Bootstrap Wizard Validations

		function runBootstrapWizard() {

			var $validator = $("#wizard-1").validate({

				rules : {
					apiCall : {
						required : true
					},
					database : {
						required : true
					},
					records : {
						required : true
					},
					domain : {
						required : true
					},
					toTest : {
						required : true
					}
				},

				messages : {
					apiCall : {
						required : "Please Select an API Call to test"
					},
					database : {
						required : "Please Select a Database"
					}
				},

				highlight : function(element) {
					$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
				},
				unhighlight : function(element) {
					$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
				},
				errorElement : 'span',
				errorClass : 'help-block',
				errorPlacement : function(error, element) {
					if (element.parent('.input-group').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
			});

			$('#bootstrap-wizard-1').bootstrapWizard({

				'tabClass' : 'form-wizard',
				'onNext' : function(tab, navigation, index) {
					var $valid = $("#wizard-1").valid();
					if (!$valid) {
						$validator.focusInvalid();
						return false;
					} else {
						$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
						$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
					}
				}
			});

		};

		// load fuelux wizard
		
		/*loadScript("js/plugin/fuelux/wizard/wizard.min.js", fueluxWizard);
		
		function fueluxWizard() {

			var wizard = $('.wizard').wizard();

			wizard.on('finished', function(e, data) {
				//$("#fuelux-wizard").submit();
				//console.log("submitted!");
				$.smallBox({
					title : "Congratulations! Your form was submitted",
					content : "<i class='fa fa-clock-o'></i><i>1 seconds ago...</i>",
					color : "#5F895F",
					iconSmall : "fa fa-check bounce animated",
					timeout : 4000
				});

			});

		};*/

	};

	// end pagefunction
	
	// Load bootstrap wizard dependency then run pagefunction
	pagefunction();

	$(document).ready(function() {
		PDFJS.workerSrc = "js/pdf.worker.js";
		resetTab1();

		$('#apiCall').on('change', function(event) {
			event.preventDefault();
			if($(this).val() == ''){
				resetTab1();
			}else{
				$('#databaseGroup').removeClass('hidden');
			}
		});

		$('#database').on('change',  function(event) {
			event.preventDefault();
			/* Act on the event */
			// alert($(this).val());
			if($(this).val() != ''){
				// $('#domainGroup').removeClass('hidden');
				$('#recordsGroup').removeClass('hidden');
			}else{
				$('#domainGroup').addClass('hidden');
				$('#recordsGroup').addClass('hidden');
			}

			if($("#apiCall").val() === 'updateFromCPA'){
				/*$.ajax({
					url: '/api/ACL/testUpdateFromCPA/',
					type: 'GET',
					dataType: 'json',
					data: {param1: 'value1'},
				});*/
			}
		});

		$('#records').on('change', function(event) {
			event.preventDefault();
			if($(this).val() != ''){
				$('#domainGroup').removeClass('hidden');
				$('#tab2-content').html('');
				$.post('php/updateFromCPA.php', {database: $('#database').val(), records:$(this).val() }, function(data, textStatus, xhr) {
					/*optional stuff to do after success */
					$('#tab2-content').html(data);
				});
			}else{
				$('#domainGroup').addClass('hidden');
			}
		});

		// loadScript('js/pdf.js');

	}); /*end document ready*/

	function resetTab1(){
		$('#apiCall').val('');
		$('#database').val('');
		$('#records').val('');
		$('#domain').val('');
		$('#databaseGroup').addClass('hidden');
		$('#recordsGroup').addClass('hidden');
		$('#domainGroup').addClass('hidden');
		$('#tab2-content').html('');
	}

	function getAclPdf(aclNum, id){
		if(aclNum){
			pageNum = 1;
			PDFJS.getDocument({
				url: '/api/ACL/'+aclNum+'/',
				httpHeaders:{
					Authorization: '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f',
					nolog:'1'
				}
			})
			.then(function (pdfDoc_) {
				pdfDoc = pdfDoc_;
				// Initial/first page rendering
				// document.getElementById('page_count').textContent = pdfDoc.numPages;
				renderPage(pageNum);
				if(acls[id]){
					makeTab(acls[id]);
				}else{
					$("#tab-r2").html("");
				}
			});

		}
	}



	function convertDataURIToBinary(dataURI) {
		var BASE64_MARKER = ';base64,';
		var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
		var base64 = dataURI.substring(base64Index);
		var raw = window.atob(base64);
		var rawLength = raw.length;
		var array = new Uint8Array(new ArrayBuffer(rawLength));

		for(var i = 0; i < rawLength; i++) {
			array[i] = raw.charCodeAt(i);
		}
		return array;
	}

	function uint8ToString(buf) {
	    var i, length, out = '';
	    for (i = 0, length = buf.length; i < length; i += 1) {
	        out += String.fromCharCode(buf[i]);
	    }
	    return out;
	}

	function renderPage(num) {
		pageRendering = true;
		pageNum = num;
		// Using promise to fetch the page
		pdfDoc.getPage(num).then(function(page) {
			var viewport = page.getViewport(scale);
			canvas.height = viewport.height;
			canvas.width = viewport.width;

			// Render PDF page into canvas context
			var renderContext = {
				canvasContext: ctx,
				viewport: viewport
			};
			var renderTask = page.render(renderContext);

			// Wait for rendering to finish
			renderTask.promise.then(function () {
				pageRendering = false;
				if (pageNumPending !== null) {
					// New page rendering is pending
					renderPage(pageNumPending);
					pageNumPending = null;
				}
			});
		});

		// Update page counters
		// document.getElementById('page_num').textContent = pageNum;
		$('#myModal').modal('show');
		buildPagination(pdfDoc.numPages,num);
	}

	function queueRenderPage(num) {
		if (pageRendering) {
			pageNumPending = num;
		} else {
			renderPage(num);
		}
	}

	function onPrevPage() {
		if (pageNum <= 1) {
			return;
		}
		pageNum--;
		queueRenderPage(pageNum);
  	}
  	// document.getElementById('prev').addEventListener('click', onPrevPage);

	function onNextPage() {
		if (pageNum >= pdfDoc.numPages) {
			return;
		}
		pageNum++;
		queueRenderPage(pageNum);
	}

	function buildPagination(numPages, num){
		$.post('php/pdf-page-menu.php', {total:numPages, currentPage:num}, function(data, textStatus, xhr) {
			$('#modal-header').html(data);
			$('#modal-bot').html(data);
		});
	}

	function makeModal2(item){
		if(item){
			$('#remote-header').html('<h1>ACL:'+item.ACLSequence+' ControlCode:'+item.ControlCode+' SiteNumber:'+item.SiteNumber+' </h1>');
			$('#remote-body').html("<div id='nestable-menu2'><button type='button' class='btn btn-default' data-action='expand-all'>Expand All</button><button type='button' class='btn btn-default' data-action='collapse-all'>Collapse All</button></div>");
			$('#remote-body').append("<div id='nestable2'><ul class='dd-list'></ul></div>");
			$.each(item, function (index, i) {
				$('#nestable2 ul').append(buildItem2(index, i));
			});
			setUpBTNS3();
			$('#nestable2').nestable();
			// $('#nestable2').nestable('collapseAll');
			$('#remoteModal').modal('show');
		}
	}
	/* this one is for the tabs */
	function setUpBTNS2(){
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
	/* this one is for stand alone*/
	function setUpBTNS3(){
		$('#nestable-menu2').on('click', function(e) {
			// alert("did this happen?");
			var target = $(e.target), action = target.data('action');
			if (action === 'expand-all') {
				// $('.dd').nestable('expandAll');
				$('#nestable2').nestable('expandAll');
			}
			if (action === 'collapse-all') {
				// $('.dd').nestable('collapseAll');
				$('#nestable2').nestable('collapseAll');
			}
		});
	}

	function buildItem2(key, item) {

		var html = "<li class='dd-item' data-id='" + key + "' id='" + key + "'>";
		html += "<div class='dd-handle'>" + key + "</div>";
		html += "<ol class='dd-list'>"+item+"</ol>";
		html += "</li>";

		return html;
	}

	function makeTab(item){
		if(item){
			$('#tab-r2').html("<div id='nestable-menu'><button type='button' class='btn btn-default' data-action='expand-all'>Expand All</button><button type='button' class='btn btn-default' data-action='collapse-all'>Collapse All</button></div>");
			$('#tab-r2').append("<div id='nestable'><ul class='dd-list'></ul></div>");
			$.each(item, function (index, i) {
				$('#nestable ul').append(buildItem2(index, i));
			});
			setUpBTNS2();
			$('#nestable').nestable();
			// $('#nestable').nestable('collapseAll');
		}
	}

	function runIt(){
		
		$("input[name='toTest[]']").each( function (){
			// console.log(this);
			if(this.checked){
				var id = this.value;
				var postData = {ControlCode:acls[this.value]['ControlCode'],SiteNumber:acls[this.value]['SiteNumber']};
				var text = $("#domain option[value='"+$('#domain').val()+"']").text();
				var url = 'https://'+text+'/api/jobInformation/updateFromCPA/';
				var d = new Date();
				var start = d.getMilliseconds();
				var stop = d.getMilliseconds();
				var tmp = {status:'',time:0,start:start,stop:stop};
				$.ajax({
					url: url,
					type: 'POST',
					headers: {Authorization: '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
					data: postData,
				})
				.done(function() {
					tmp.status = 'Success';
				})
				.fail(function() {
					tmp.status = 'Failed';
				})
				.always(function() {
					var f = new Date();
					stop = f.getMilliseconds();
					tmp.stop = stop;
					tmp.time = stop - start;
					console.log(tmp);
					$.extend( acls[id], tmp);
					console.log(acls[id]);
					addToTable(acls[id]);
				});
			}
		});
	}

	function addToTable(item){
		if(item){
			$('#run-table tr:last').after('<tr><td>'+item.ACLSequence+'</td><td>'+item.ControlCode+'</td><td>'+item.SiteNumber+'</td><td>'+item.time+'</td><td>'+item.status+'</td></tr>');
		}
	}

	function clearIt(){
		$('#run-table tr').not(function(){ return !!$(this).has('th').length; }).remove();
	}
</script>