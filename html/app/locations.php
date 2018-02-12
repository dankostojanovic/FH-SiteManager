<?php 
require_once("../../init.php");
checkLogin();
require_once("inc/init.php");

if(isset($user))
{
	// print_array($user);
}else
{
	$user = new Users($_SESSION['userId']);
	// print_array($user);
}
$locations = $user->getLocations();
// print_array($locations);
?>
<!-- row -->
<div class="row">
	
	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-globe"></i> 
				Locations
			<span>>  
				View / Edit
			</span>
		</h1>
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
		<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-0">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-globe"></i> </span>
					<h2>View Locations </h2>			
					
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
						
						<table id="locationTable" class="table table-striped table-bordered table-hover" width="100%">
							<thead>
								<tr>
									<th>Edit</th>
									<th>Name</th>
									<th>Address</th>
									<th>City</th>
									<th>State</th>
									<th>Zip</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								foreach ($locations as $key => $l) {
									echo "<tr>
											<td><a href='javascript:loadLocation(".$l->getId().")' class='btn btn-primary'>Edit</a></td>
											<td>".$l->getName()."</td>
											<td>".$l->getAddress()."</td>
											<td>".$l->getCity()."</td>
											<td>".$l->getState()."</td>
											<td>".$l->getZip()."</td>
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

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-2">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-globe"></i> </span>
					<h2>Edit Location </h2>		
					
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
						
						<form action="" id="locationForm" class="smart-form" novalidate="novalidate">
							<header>
								Add / Edit Location
							</header>

							<fieldset>
								<input type="hidden" name="user_id" id="user_id" value="<?=$user->getId();?>">
								<input type="hidden" name="id" id="id" value="">
								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-globe"></i>
											<input type="text" name="name" id="name" placeholder="Name">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-10">
										<label class="input"> <i class="icon-append fa fa-map-marker"></i>
											<input type="text" name="address" id="address" placeholder="address">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-4">
										<label class="input"> <i class="icon-append fa fa-map-marker"></i>
											<input type="text" name="city" id="city" placeholder="city">
										</label>
									</section>
									<section class="col col-4">
										<label class="select">
											<select name="state" id="state">
												<option value="0" selected="" disabled="">Select State</option>
												<option value="AL">Alabama</option>
												<option value="AK">Alaska</option>
												<option value="AZ">Arizona</option>
												<option value="AR">Arkansas</option>
												<option value="CA">California</option>
												<option value="CO">Colorado</option>
												<option value="CT">Connecticut</option>
												<option value="DE">Delaware</option>
												<option value="DC">District Of Columbia</option>
												<option value="FL">Florida</option>
												<option value="GA">Georgia</option>
												<option value="HI">Hawaii</option>
												<option value="ID">Idaho</option>
												<option value="IL">Illinois</option>
												<option value="IN">Indiana</option>
												<option value="IA">Iowa</option>
												<option value="KS">Kansas</option>
												<option value="KY">Kentucky</option>
												<option value="LA">Louisiana</option>
												<option value="ME">Maine</option>
												<option value="MD">Maryland</option>
												<option value="MA">Massachusetts</option>
												<option value="MI">Michigan</option>
												<option value="MN">Minnesota</option>
												<option value="MS">Mississippi</option>
												<option value="MO">Missouri</option>
												<option value="MT">Montana</option>
												<option value="NE">Nebraska</option>
												<option value="NV">Nevada</option>
												<option value="NH">New Hampshire</option>
												<option value="NJ">New Jersey</option>
												<option value="NM">New Mexico</option>
												<option value="NY">New York</option>
												<option value="NC">North Carolina</option>
												<option value="ND">North Dakota</option>
												<option value="OH">Ohio</option>
												<option value="OK">Oklahoma</option>
												<option value="OR">Oregon</option>
												<option value="PA">Pennsylvania</option>
												<option value="RI">Rhode Island</option>
												<option value="SC">South Carolina</option>
												<option value="SD">South Dakota</option>
												<option value="TN">Tennessee</option>
												<option value="TX">Texas</option>
												<option value="UT">Utah</option>
												<option value="VT">Vermont</option>
												<option value="VA">Virginia</option>
												<option value="WA">Washington</option>
												<option value="WV">West Virginia</option>
												<option value="WI">Wisconsin</option>
												<option value="WY">Wyoming</option>
											</select> <i></i> </label>
									</section>
									<section class="col col-4">
										<label class="input"> <i class="icon-append fa fa-map-marker"></i>
											<input type="text" name="zip" id="zip" placeholder="zip">
										</label>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary">
									Add / Edit
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

		
		
			
	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">
	pageSetUp();
	
	var pagefunction = function() {
		$('#locationTable').dataTable();

		var $locationForm = $("#locationForm").validate({
			// Rules for form validation
			rules : {
				name : {
					required : true
				},
				address : {
					required : true,
				},
				city : {
					required : true
				},
				state : {
					required : true
				},
				zip : {
					required : true,
					digits : true
				}
			},

			// Messages for form validation
			messages : {
				name : {
					required : 'Please enter your name of location'
				},
				address : {
					required : 'Please enter your address'
				},
				city : {
					required : 'Please enter your city'
				},
				state : {
					required : 'Please select state'
				},
				zip : {
					required : 'Please enter your zip code.'
				}
			},

			submitHandler : function(form) {
				// alert('submit');
				info = $('#locationForm').serializeObject();
    			// console.log(info);
    			$.post('/function/location/', info, function(data, textStatus, xhr) {
    				// window.location.reload(true);
    			}, "json").done(function(){
    				// alert('right before reload');
    				// window.location.reload(true);
    			});

    			// location.reload();
			},

			success : function(){
				location.reload();
			},
			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	};
	

	// pagefunction();
	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js",function(){
						loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);
					});
				});
			});
		});
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

	function loadLocation(id)
	{
		$.post('/function/loadLocation/', {id:id}, function(data, textStatus, xhr) {
			// console.log(data);
			$('#id').val(data.id);
			$('#name').val(data.name);
			$('#address').val(data.address);
			$('#city').val(data.city);
			$('#state').val(data.state);
			$('#zip').val(data.zip);
		}, "json");
	}
	
</script>
