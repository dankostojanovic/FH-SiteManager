<?php
require_once("../init.php");
checkLogin();
require_once("inc/init.php");
?>
<!-- row -->
<div class="row">

	<!-- col -->
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"> <i class="fa-fw fa fa-exclamation"></i> Alert Type <span>>
			View </span></h1>
	</div>
	<!-- end col -->

	<!-- right side of the page with the sparkline graphs -->
	<!-- col -->
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<!-- sparks -->
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
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<table id="jqgrid"></table>
			<div id="pjqgrid"></div>

			<br>
			<!-- <a href="javascript:void(0)" id="m1">Get Selected id's</a> -->
			<br>
			<!-- <a href="javascript:void(0)" id="m1s">Select(Unselect) row 13</a> -->

		</article>
		<!-- WIDGET END -->

	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

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

	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 *
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 *
	 */

	var pagefunction = function() {
		// loadScript("js/plugin/jqgrid/jquery.jqGrid.min.js", run_jqgrid_function);
		loadScript("js/plugin/jqgrid/jquery.jqGrid.js", run_jqgrid_function);

		function run_jqgrid_function() {

			/*$.post('php/getAlertData.php', function(data, textStatus, xhr) {				
				jqgrid_data = data;
			});*/

			jQuery("#jqgrid").jqGrid({
				// data : jqgrid_data,
				// datatype : "local",
				url: 'php/getAlertData.php',
				datatype: 'json',
				height : 'auto',
				colNames : ['Action','AlertType', 'Description', 'UserId', 'LastModified', 'Subject', 'Message', 'TemplateCode'],
				colModel : [
				{
					name:'Action',
					index:'Action'
				},
				{
					name : 'AlertType',
					index : 'AlertType',
					sortable : true,
					editable : true
				}, {
					name : 'Description',
					index : 'Description',
					sortable : true,
					editable: true
				}, {
					name : 'UserId',
					index : 'UserId',
					sortable : true,
					editable : false
				}, {
					name : 'LastModified',
					index : 'LastModified',
					sortable : true,
					editable : false
				}, {
					name : 'Subject',
					index : 'Subject',
					align : "right",
					sortable : true,
					editable : true
				}, {
					name : 'Message',
					index : 'Message',
					align : "right",
					sortable : true,
					editable : true
				},{
					name : 'TemplateCode',
					index : 'TemplateCode',
					sortable : true,
					editable : true
				}],
				rowNum : 10,
				rowList : [10, 20, 30, 40, 50, 100],
				pager : '#pjqgrid',
				// sortname : 'AlertType',
				toolbarfilter : true,
				viewrecords : true,
				sortorder : "asc",
				gridComplete : function() {
					var ids = jQuery("#jqgrid").jqGrid('getDataIDs');
					// var ids = jQuery("#jqgrid").jqGrid('AlertType');
					// var ids = jQuery("#jqgrid").jqGrid('getDataAlertType');
					console.log(ids);
					for (var i = 0; i < ids.length; i++) {
						var cl = ids[i];
						be = "<button class='btn btn-xs btn-default' data-original-title='Edit Row' onclick=\"jQuery('#jqgrid').editRow('" + cl + "');\"><i class='fa fa-pencil'></i></button>";
						se = "<button class='btn btn-xs btn-default' data-original-title='Save Row' onclick=\"jQuery('#jqgrid').saveRow('" + cl + "');\"><i class='fa fa-save'></i></button>";
						ca = "<button class='btn btn-xs btn-default' data-original-title='Cancel' onclick=\"jQuery('#jqgrid').restoreRow('" + cl + "');\"><i class='fa fa-times'></i></button>";
						// ce = "<button class='btn btn-xs btn-default' onclick=\"jQuery('#jqgrid').restoreRow('"+cl+"');\"><i class='fa fa-times'></i></button>";
						//jQuery("#jqgrid").jqGrid('setRowData',ids[i],{act:be+se+ce});
						jQuery("#jqgrid").jqGrid('setRowData', ids[i], {
							Action : be + se + ca
						});
					}
				},
				editurl : "php/alertEdit.php",
				caption : "Alert Types",
				multiselect : true,
				autowidth : true
			});

			// http://stackoverflow.com/questions/14317847/jqgrid-after-save-callback-function
			var myEditOptions = {
		        keys: true,
		        oneditfunc : function (rowid) {
		            // alert("row with rowid=" + rowid + " is editing.");
		            $.bigBox({
						title : "Editing Started!",
						content : "AlertType "+rowid +" is now being edited!",
						color : "#296191",
						//timeout: 6000,
						icon : "fa fa-pencil shake animated",
						// number : "1",
						timeout : 6000
					});
		        },
		        aftersavefunc : function (rowid, response, options) {
		            // alert("row with rowid=" + rowid + " is successfuly modified.");
		            $.bigBox({
						title : "Record Saved!",
						content : "AlertType "+rowid +" is now saved!",
						color : "#739E73",
						//timeout: 6000,
						icon : "fa fa-pencil shake animated",
						// number : "1",
						timeout : 6000
					});
		        },
		        /*errorfunc : function (rowid, response, options) {
		            // alert("row with rowid=" + rowid + " is successfuly modified.");
		            $.bigBox({
						title : "Unable to Save!",
						content : "AlertType "+rowid +" was not able to save!",
						color : "#C46A69",
						//timeout: 6000,
						icon : "fa fa-pencil shake animated",
						// number : "1",
						timeout : 6000
					});
		        }*/
		   //      afterrestorefunc: function (rowid, response, options) {
		   //          // alert("row with rowid=" + rowid + " is successfuly modified.");
		   //          $.bigBox({
					// 	title : "Recorded Restored!",
					// 	content : "AlertType "+rowid +" is now saved!",
					// 	color : "#739E73",
					// 	//timeout: 6000,
					// 	icon : "fa fa-pencil shake animated",
					// 	// number : "1",
					// 	timeout : 6000
					// });
		   //      }
		    };

			jQuery("#jqgrid").jqGrid('navGrid', "#pjqgrid", {
				edit : true,
				add : true,
				del : true,
				// editParams : myEditOptions
			});

			jQuery("#jqgrid").jqGrid('inlineNav', "#pjqgrid", {
				addParams: { 
			        // position: "afterSelected",
			        addRowParams: myEditOptions
			    },
			    // addedrow: "last",
			    editParams : myEditOptions
			});
			/* Add tooltips */
			$('.navtable .ui-pg-button').tooltip({
				container : 'body'
			});

			/*jQuery("#jqgrid").jqGrid('saveRow',rowid, 
			{ 
			    successfunc: function( response ) {
			        $.bigBox({
						title : "Record Saved!",
						content : "AlertType "+rowid +" is now saved!",
						color : "#739E73",
						//timeout: 6000,
						icon : "fa fa-pencil shake animated",
						// number : "1",
						timeout : 6000
					});
			    }
			});*/

			jQuery("#m1").click(function() {
				var s;
				s = jQuery("#jqgrid").jqGrid('getGridParam', 'selarrrow');
				alert(s);
			});
			jQuery("#m1s").click(function() {
				jQuery("#jqgrid").jqGrid('setSelection', "13");
			});

			// remove classes
			$(".ui-jqgrid").removeClass("ui-widget ui-widget-content");
			$(".ui-jqgrid-view").children().removeClass("ui-widget-header ui-state-default");
			$(".ui-jqgrid-labels, .ui-search-toolbar").children().removeClass("ui-state-default ui-th-column ui-th-ltr");
			$(".ui-jqgrid-pager").removeClass("ui-state-default");
			$(".ui-jqgrid").removeClass("ui-widget-content");

			// add classes
			$(".ui-jqgrid-htable").addClass("table table-bordered table-hover");
			$(".ui-jqgrid-btable").addClass("table table-bordered table-striped");

			$(".ui-pg-div").removeClass().addClass("btn btn-sm btn-primary");
			$(".ui-icon.ui-icon-plus").removeClass().addClass("fa fa-plus");
			$(".ui-icon.ui-icon-pencil").removeClass().addClass("fa fa-pencil");
			$(".ui-icon.ui-icon-trash").removeClass().addClass("fa fa-trash-o");
			$(".ui-icon.ui-icon-search").removeClass().addClass("fa fa-search");
			$(".ui-icon.ui-icon-refresh").removeClass().addClass("fa fa-refresh");
			$(".ui-icon.ui-icon-disk").removeClass().addClass("fa fa-save").parent(".btn-primary").removeClass("btn-primary").addClass("btn-success");
			$(".ui-icon.ui-icon-cancel").removeClass().addClass("fa fa-times").parent(".btn-primary").removeClass("btn-primary").addClass("btn-danger");

			$(".ui-icon.ui-icon-seek-prev").wrap("<div class='btn btn-sm btn-default'></div>");
			$(".ui-icon.ui-icon-seek-prev").removeClass().addClass("fa fa-backward");

			$(".ui-icon.ui-icon-seek-first").wrap("<div class='btn btn-sm btn-default'></div>");
			$(".ui-icon.ui-icon-seek-first").removeClass().addClass("fa fa-fast-backward");

			$(".ui-icon.ui-icon-seek-next").wrap("<div class='btn btn-sm btn-default'></div>");
			$(".ui-icon.ui-icon-seek-next").removeClass().addClass("fa fa-forward");

			$(".ui-icon.ui-icon-seek-end").wrap("<div class='btn btn-sm btn-default'></div>");
			$(".ui-icon.ui-icon-seek-end").removeClass().addClass("fa fa-fast-forward");

			// update buttons
			
			$(window).on('resize.jqGrid', function() {
				$("#jqgrid").jqGrid('setGridWidth', $("#content").width());
			});

		}// end function

	}
	loadScript("js/plugin/jqgrid/grid.locale-en.min.js", pagefunction);

</script>
