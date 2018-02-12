<?php
require_once("../init.php");
checkLogin();
require_once("inc/init.php");
$columns = \rule\Map\CommunitySectionTableMap::getTableMap()->getColumns();
?>
<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/handsontable.css">
<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/pikaday/pikaday.css">
<script data-jsfiddle="common" src="dist/pikaday/pikaday.js"></script>
<script data-jsfiddle="common" src="dist/moment/moment.js"></script>
<script data-jsfiddle="common" src="dist/zeroclipboard/ZeroClipboard.js"></script>
<script data-jsfiddle="common" src="dist/numbro/numbro.js"></script>
<script data-jsfiddle="common" src="dist/numbro/languages.js"></script>
<script data-jsfiddle="common" src="dist/handsontable.js"></script>

<?php 
$coms = \rule\CommunityQuery::create()->find();
echo "<p><select id='communityId' name='communityId'>";
foreach ($coms as $key => $com) {
	echo "<option value=\"".$com->getId()."\">".$com->getCode()." - ".$com->getName()."</option>";
}
echo "</select></p>";
?>
<p>
	<button id="load" name="load">Load</button>
	<button name="save">Save</button>
	<button name="reset">Reset</button>
	<!-- <label><input type="checkbox" name="autosave" checked="checked" autocomplete="off"> Autosave</label> -->
	<label><input type="checkbox" name="autosave" autocomplete="off"> Autosave</label>
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
			// colHeaders: ['section_id','community_id','section_name','f_min_lot_size_front_entr','f_min_lot_size_side_entry','f_min_lot_size_rear_entry','f_comments','f_tfg_zoning_juris_dicti','f_tfg_zoning_classifica','f_tfg_front_yard_min','f_tfg_SideYardMinimum','f_tfg_CombinedSideMini','f_tfg_RearYardMinimum','f_tfg_SideonCornerMini','f_width_at_setback1','f_width_at_setback2','f_tfg_width_at_setback','f_side_next_to_street1','f_side_next_to_street2','f_tfg_side_next_to_street','f_min_sqft_per_lot','f_min_sq_ft_ranch','f_min_sq_ft2_story','f_min_sq_ft2_story1_maste','spec_level_id','last_modified','user_id','created_date','created_by'],
			/*columns: [
				{data:'SectionId'},
				{data:'CommunityId'},
				{data:'SectionName'},
				{data:'FMinLotSizeFrontEntr'},
				{data:'FMinLotSizeSideEntry'},
				{data:'FMinLotSizeRearEntry'},
				{data:'FComments'},
				{data:'FTfgZoningJurisDicti'},
				{data:'FTfgZoningClassifica'},
				{data:'FTfgFrontYardMin'},
				{data:'FTfgSideyardminimum'},
				{data:'FTfgCombinedsidemini'},
				{data:'FTfgRearyardminimum'},
				{data:'FTfgSideoncornermini'},
				{data:'FWidthAtSetback1'},
				{data:'FWidthAtSetback2'},
				{data:'FTfgWidthAtSetback'},
				{data:'FSideNextToStreet1'},
				{data:'FSideNextToStreet2'},
				{data:'FTfgSideNextToStreet'},
				{data:'FMinSqftPerLot'},
				{data:'FMinSqFtRanch'},
				{data:'FMinSqFt2Story'},
				{data:'FMinSqFt2Story1Maste'},
				{data:'SpecLevelId'},
				{data:'LastModified'},
				{data:'UserId'},
				{data:'CreatedDate'},
				{data:'CreatedBy'}
			],*/
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
			// rows:[
			// 	{data:'Id'}
			// ],
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
					url: '/rule/save/communitySection/',
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
			// hot.clear();
			$.ajax({
				url: '/rule/communitySection/?page=1&rows=600',
				headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
				dataType: 'json',
				type: 'GET',
				data: {comId:$('#communityId').val()},
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

		$('#communityId').change(function(event) {
			hot.clear();
			$('#load').click();
		});
</script>