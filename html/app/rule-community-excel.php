<?php
require_once("../init.php");
checkLogin();
require_once("inc/init.php");
$columns = \rule\Map\CommunityTableMap::getTableMap()->getColumns();
?>
<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/handsontable.css">
<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/pikaday/pikaday.css">
<script data-jsfiddle="common" src="dist/pikaday/pikaday.js"></script>
<script data-jsfiddle="common" src="dist/moment/moment.js"></script>
<script data-jsfiddle="common" src="dist/zeroclipboard/ZeroClipboard.js"></script>
<script data-jsfiddle="common" src="dist/numbro/numbro.js"></script>
<script data-jsfiddle="common" src="dist/numbro/languages.js"></script>
<script data-jsfiddle="common" src="dist/handsontable.js"></script>

<p>
	<button name="load">Load</button>
	<button name="save">Save</button>
	<button name="reset">Reset</button>
	<label><input type="checkbox" name="autosave" checked="checked" autocomplete="off"> Autosave</label>
	<input id="search_field" type="search" placeholder="Search">
</p>

<div id="exampleConsole" class="console">Click "Load" to load data from server</div>

<!-- <div id="example1"></div> -->
<div id="example1" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 700px; overflow: hidden; width: 1500px;" data-originalstyle="height: 700px; overflow: hidden; width: 1500px;"></div>

<!-- <p>
	<button name="dump" data-dump="#example1" data-instance="hot" title="Prints current data source to Firebug/Chrome Dev Tools">
	  Dump data to console
	</button>
</p> -->

<script type="text/javascript">
	var
		$container = $("#example1"),
		$console = $("#exampleConsole"),
		$parent = $container.parent(),
		autosaveNotification,
		searchFiled = document.getElementById('search_field'),
		hot;

        hot = new Handsontable($container[0], {
			columnSorting: true,
			startRows: 20,
			startCols: 12,
			rowHeaders: true,
			// colHeaders: true,
			// colHeaders: ['Id','Code','Name','Is_Active','Id_Division','When_Created','When_Updated','When_Inactive','Created_By','Updated_By','Inactive_By','Active_Estimating','Active_Marketing','Active_Land_Mgmt','Product_Type','Plot_Plan_Schd_Dur','Plot_Plan_Recd_Dur','Devp_Rvw_Subd_Dur','Devp_Rvw_Recd_Dur','Zone_Subm_Dur','Zone_Recd_Dur','Bldg_Perm_Subm_Dur','Bldg_Perm_Recd_Dur','File_To_Estimating_Dur','Days_To_Start_Dur','Truss_Subm_Dur','Truss_Recd_Dur','County_Subm_Dur','County_Recd_Dur'],
			// columns: [
			//   {},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{}
			// ],
			colHeaders:[
				<?php 
				foreach ($columns as $key => $c) {
					echo "'".$c->getName()."',";
				}
				?>
			],
			columns:[
				<?php 
				foreach ($columns as $key => $c) {
					echo "{data:'".$c->getPhpName()."'},";
				}
				?>
			],
			/*columns: [
				{data:'Id'},
				{data:'Code'},
				{data:'Name'},
				{data:'IsActive'},
				{data:'IdDivision'},
				{data:'WhenCreated'},
				{data:'WhenUpdated'},
				{data:'WhenInactive'},
				{data:'CreatedBy'},
				{data:'UpdatedBy'},
				{data:'InactiveBy'},
				{data:'ActiveEstimating'},
				{data:'ActiveMarketing'},
				{data:'ActiveLandMgmt'},
				{data:'ProductType'},
				{data:'PlotPlanSchdDur'},
				{data:'PlotPlanRecdDur'},
				{data:'DevpRvwSubdDur'},
				{data:'DevpRvwRecdDur'},
				{data:'ZoneSubmDur'},
				{data:'ZoneRecdDur'},
				{data:'BldgPermSubmDur'},
				{data:'BldgPermRecdDur'},
				{data:'FileToEstimatingDur'},
				{data:'DaysToStartDur'},
				{data:'TrussSubmDur'},
				{data:'TrussRecdDur'},
				{data:'CountySubmDur'},
				{data:'CountyRecdDur'}
			],*/
			minSpareCols: 0,
			minSpareRows: 1,
			contextMenu: true,
			// fixedRowsTop: 1,
			fixedColumnsLeft: 3,
			search: true,
			// observeChanges: true,
        	afterChange: function (change, source) {
	            var data;

	            if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
	              return;
	            }

	            $.each(change, function(index, val) {
	            	// instead of giving me the column I need the record id
	            	var tmp = hot.getDataAtRow(val[0]);  // gets me the row data
	            	change[index][0] = tmp[0]; // sets the change id to the row id on the record.
	            });

	            clearTimeout(autosaveNotification);
				$.ajax({
					url: '/rule/save/community/',
					headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'},
					dataType: 'json',
					type: 'POST',
					data: {changes: change}, // contains changed cells' data
					success: function () {
						$console.text('Autosaved (' + change.length + ' cell' + (change.length > 1 ? 's' : '') + ')');
						autosaveNotification = setTimeout(function () {
							$console.text('Changes will be autosaved');
						}, 1000);
					}
				});
        	}
        });

		$parent.find('button[name=load]').click(function () {
			$.ajax({
				url: '/rule/community/?page=1&rows=600',
				headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
				dataType: 'json',
				type: 'GET',
				success: function (res) {
					// console.log(res.rows);
					hot.loadData(res.rows);
				}
			});
		}).click(); // execute immediately

		$parent.find('button[name=save]').click(function () {
			$.ajax({
				url: '/rule/save/community/test/',
				data: {data: hot.getData()}, // returns all cells' data
				dataType: 'json',
				type: 'POST',
				success: function (res) {
					if (res.result === 'ok') {
						$console.text('Data saved');
					} else {
						$console.text('Save error');
					}
				},
				error: function () {
					$console.text('Save error');
				}
			});
		});

		/*$parent.find('button[name=reset]').click(function () {
		  $.ajax({
		    url: 'php/reset.php',
		    success: function () {
		      $parent.find('button[name=load]').click();
		    },
		    error: function () {
		      $console.text('Data reset failed');
		    }
		  });
		});*/

		$parent.find('input[name=autosave]').click(function () {
			if ($(this).is(':checked')) {
				$console.text('Changes will be autosaved');
			} else {
				$console.text('Changes will not be autosaved');
			}
		});

		function onlyExactMatch(queryStr, value) {
			return queryStr.toString() === value.toString();
		}

		Handsontable.Dom.addEvent(searchFiled, 'keyup', function (event) {
			var queryResult = hot.search.query(this.value);

			// console.log(queryResult);
			hot.render();
		});
</script>