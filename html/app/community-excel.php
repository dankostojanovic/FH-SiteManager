<?php
require_once("../init.php");
checkLogin();
require_once("inc/init.php");
$columns = \test\Map\CommunityTableMap::getTableMap()->getColumns();
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
</p>

<div id="exampleConsole" class="console">Click "Load" to load data from server</div>

<div id="example1"></div>

<p>
	<button name="dump" data-dump="#example1" data-instance="hot" title="Prints current data source to Firebug/Chrome Dev Tools">
	  Dump data to console
	</button>
</p>

<script type="text/javascript">
	var
		$container = $("#example1"),
		$console = $("#exampleConsole"),
		$parent = $container.parent(),
		autosaveNotification,
		hot;

        hot = new Handsontable($container[0], {
          columnSorting: true,
          startRows: 20,
          startCols: 12,
          rowHeaders: true,
          // colHeaders: ['CommunityOld','Division','City','County','State','SchoolDistrict','DateRecordModified','TimeRecordModified','UserModified','Community','CommunityName','TaxRate'],
          // columns: [
          //   {},{},{},{},{},{},{},{},{},{},{},{}
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
          minSpareCols: 0,
          minSpareRows: 1,
          contextMenu: true,
          afterChange: function (change, source) {
            var data;

            if (source === 'loadData' || !$parent.find('input[name=autosave]').is(':checked')) {
              return;
            }
            data = change[0];

            // transform sorted row to original row
            data[0] = hot.sortIndex[data[0]] ? hot.sortIndex[data[0]][0] : data[0];

            clearTimeout(autosaveNotification);
            $.ajax({
              url: 'php/save.php',
              headers:{nolog:'1'},
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
		    url: '/api/v1/communities/?page=1&rows=600',
		    headers: {Authorization:'1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581f', nolog:'1'},
		    dataType: 'json',
		    type: 'GET',
		    success: function (res) {
		      // var data = [], row;

		      // for (var i = 0, ilen = res.rows.length; i < ilen; i++) {
		      //   row = [];
		      //   row[0] = res.rows[i].CommunityOld;
		      //   row[1] = res.rows[i].Division;
		      //   row[2] = res.rows[i].City;
		      //   row[3] = res.rows[i].County;
		      //   row[4] = res.rows[i].State;
		      //   row[5] = res.rows[i].SchoolDistrict;
		      //   row[6] = res.rows[i].DateRecordModified;
		      //   row[7] = res.rows[i].TimeRecordModified;
		      //   row[8] = res.rows[i].UserModified;
		      //   row[9] = res.rows[i].Community;
		      //   row[10] = res.rows[i].CommunityName;
		      //   row[11] = res.rows[i].TaxRate;
		        
		      //   data[i] = row;
		      //   // data[res.rows[i].Community] = row;
		      // }
		      // $console.text('Data loaded');
		      // console.log(data);
		      // hot.loadData(data);
		      // hot.loadData(res.data);
		      hot.loadData(res.rows);
		    }
		  });
		}).click(); // execute immediately

		/*$parent.find('button[name=save]').click(function () {
		  $.ajax({
		    url: 'php/save.php',
		    data: {data: hot.getData()}, // returns all cells' data
		    dataType: 'json',
		    type: 'POST',
		    success: function (res) {
		      if (res.result === 'ok') {
		        $console.text('Data saved');
		      }
		      else {
		        $console.text('Save error');
		      }
		    },
		    error: function () {
		      $console.text('Save error');
		    }
		  });
		});*/

		$parent.find('button[name=reset]').click(function () {
		  $.ajax({
		    url: 'php/reset.php',
		    success: function () {
		      $parent.find('button[name=load]').click();
		    },
		    error: function () {
		      $console.text('Data reset failed');
		    }
		  });
		});

		$parent.find('input[name=autosave]').click(function () {
		  if ($(this).is(':checked')) {
		    $console.text('Changes will be autosaved');
		  }
		  else {
		    $console.text('Changes will not be autosaved');
		  }
		});
</script>