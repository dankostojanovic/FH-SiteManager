<?php
require_once("../init.php");
checkLogin();
require_once("inc/init.php");
$columns = \rule\Map\CommunitySiteTableMap::getTableMap()->getColumns();
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
// echo "<option value='-1'>Select Community</option>";
foreach ($coms as $key => $com) {
	echo "<option value=\"".$com->getId()."\">".$com->getCode()." - ".$com->getName()."</option>";
}
echo "</select>  ";
echo "<select id='secId' name='secId'><option value='0'>Select Section</option></select>";
echo"</p>";
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
			fixedColumnsLeft: 4,
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
					url: '/rule/save/communitySite/',
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
				url: '/rule/communitySite/?page=1&rows=500',
				headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
				dataType: 'json',
				type: 'GET',
				data: {comId:$('#communityId').val(), secId: $('#secId').val()},
				success: function (res) {
					// console.log(res.rows);
					hot.loadData(res.rows);
				}
			});
		}); // execute immediately

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
			getSectionOptions();
			hot.clear();
			$('#load').click();
		});

		$('#secId').change(function(event) {
			hot.clear();
			$('#load').click();
		});

		function getSectionOptions(){
			$.ajax({
				url: '/rule/community/getSections/',
				type: 'GET',
				dataType: 'json',
				data: { comId : $('#communityId').val()},
				headers:{nolog:'1', Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f'}
			})
			.done(function(data) {
				console.log("success");
				console.log(data.CommunitySections);
				// $('#secId').find('option').remove().end().append('<option value="0">Select Section</option>').val(0);
				// $('#secId').clear();
				$('#secId').empty();
				$('#secId').append('<option value="0">Select Section</option>');
				$.each(data.CommunitySections, function(index, val) {
					// $('#secId').append($('<option value"'+val.SectionId+'">'+val.SectionName+'</option>').val(val).html(text));
					$('#secId').append('<option value="'+val.SectionId+'">'+val.SectionName+'</option>');
				});
				$('#secId').val(0);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		}

		$(document).ready(function() {
			getSectionOptions();
			$('#load').click();
		});
		
</script>