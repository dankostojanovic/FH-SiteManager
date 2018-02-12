<?php 
include_once '../init.php';
checkLogin();
$acls = array();
if(!empty($_POST['database']) && !empty($_POST['records'])){
	if($_POST['database'] == 1){
		$dns = 'odbc:live-pervasive';
	}else{
		$dns = 'odbc:pervasive';
	}
	$conn = new PDO($dns, '', '');
	$sql = 'SELECT TOP '.$_POST['records'].' * FROM "FISCHER MANAGEMENT"."ACL" WHERE ACLLock = 1 and ControlCode != 10000000 and ControlCode != 20000000 order  by DateCreated DESC;';
	$acls = fetch($conn, $sql);
	/*want to do a prepair but i cant on the top */
	/*$sql = 'SELECT TOP ? * FROM "FISCHER MANAGEMENT"."ACL" WHERE ACLLock = 1 and ControlCode != 10000000 and ControlCode != 20000000 order  by DateCreated DESC;';
	$acls = fetch($conn, $sql,  $prams = array( 'records' => $_POST['records'] ) );*/
}
// print_array($acls);
?>
<table id="dt_updateFromCPA" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr>
			<!-- <th class="hasinput" style="width:25%">
				<input type="text" class="form-control" placeholder="Column" />
			</th> -->
			<!-- <th><a href="javascript:checkAll();" class="btn btn-success">check All</a><a href="javascript:unCheckAll();" class="btn btn-default">uncheck All</a></th> -->
			<th>
				<div class="btn-group">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
						Check Box Actions <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li>
							<a href="javascript:checkAll();">Check All</a>
						</li>
						<li>
							<a href="javascript:unCheckAll();">Uncheck All</a>
						</li>
					</ul>
				</div>
			</th>
			<th class="hasinput" style="width:20%">
				<input type="text" class="form-control" placeholder="Control Code" />
			</th>
			<th class="hasinput" style="width:20%">
				<input type="text" class="form-control" placeholder="Site Number" />
			</th>
			<th class="hasinput" style="width:20%">
				<input type="text" class="form-control" placeholder="Division" />
			</th>
			<th class="hasinput" style="width:20%">
				<input type="text" class="form-control" placeholder="Agreement Written" />
			</th>
			<th class="hasinput" style="width:20%">
				<input type="text" class="form-control" placeholder="ACL Sequence" />
			</th>
		</tr>
		<tr>
			<th >Select</th>
			<th >Control Code</th>
			<th >Site Number</th>
			<th >Division</th>
			<th >Agreement Written</th>
			<th >View More</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		foreach ($acls as $key => $acl) {
			echo "<tr>
					<td><input type=\"checkbox\" name=\"toTest[]\" value=\"".$key."\">
					</td>
					<td>".$acl['ControlCode']."
					</td>
					<td>".$acl['SiteNumber']."
					</td>
					<td>".$acl['Division']."
					</td>
					<td>".$acl['Agreement_Written']."
					</td>
					<td>
						<div class='btn-group'>
							<button class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
								ACL Record Actions <span class='caret'></span>
							</button>
							<ul class='dropdown-menu'>
								<li>
									<a href='javascript:getAclPdf(".$acl['ACLSequence'].",".$key.");'>View ACL PDF</a>
								</li>
								<li>
									<a href='javascript:viewRecord(".$key.");'>View ACL Record</a>
								</li>
							</ul>
						</div>
					</td>
				</tr>";
		}
	?>
	</tbody>
	<tfoot>
		<tr>
			<th >Select</th>
			<th >Control Code</th>
			<th >Site Number</th>
			<th >Division</th>
			<th >Agreement Written</th>
			<th >View More</th>
		</tr>
	</tfoot>
</table>
<?php 
// print_array($acls);
?>

<script type="text/javascript">
	var cpa = '';
	var acls = <?php echo json_encode($acls);?>;
	console.log(acls);
	$(document).ready(function() {

		var pagefunction = function(){
			cpa = $('#dt_updateFromCPA').DataTable();
		}

		loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
					loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
						loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
					});
				});
			});
		});

		$("#dt_updateFromCPA thead th input[type=text]").on( 'keyup change', function () {
	        cpa
	            .column( $(this).parent().index()+':visible' )
	            .search( this.value )
	            .draw();
	    } );

		$('input[name="toTest[]"]').on('change', function(event) {
			event.preventDefault();
			/* Act on the event */
			// alert($(this).val()+' has changed');
		});
	});

	function checkAll(){
		// $('.toTest').prop('checked', true);
		$("input[name='toTest[]']").each( function (){
			$(this).prop('checked', true);
		});
	}

	function unCheckAll(){
		// $('.toTest').prop('checked', false);
		$("input[name='toTest[]']").each( function (){
			$(this).prop('checked', false);
		});
	}

	function viewRecord(id){
		if(acls[id]){
			makeModal2(acls[id]);
		}
	}
	</script>