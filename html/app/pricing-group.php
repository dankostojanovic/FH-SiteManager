<?php

require_once("../init.php");
checkLogin();

require_once("inc/init.php");

?>

<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/pikaday/pikaday.css">
<script data-jsfiddle="common" src="dist/pikaday/pikaday.js"></script>
<script data-jsfiddle="common" src="dist/moment/moment.js"></script>
<script data-jsfiddle="common" src="dist/zeroclipboard/ZeroClipboard.js"></script>
<script data-jsfiddle="common" src="dist/numbro/numbro.js"></script>
<script data-jsfiddle="common" src="dist/numbro/languages.js"></script>
<script type="text/javascript" src="js/component_js/global.js"></script>

<style type= "text/css">
	tr {
		transition: background-color .25s;
	}	
</style>

<h1>Pricing Group</h1>

<!-- Update Pricing Group Modal -->
<div class="modal fade" id="updatePricingGroupModal" tabindex="-1" role="dialog" aria-labelledby="updatePricingGroupModalLabel" aria-hidden="true"><!-- style="display: none;" -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    X
                </button>
                <h4 class="modal-title">Fischer Pricing Group</h4>
            </div>

            <div class="modal-body no-padding">
                <form action="" id="update-pricingGroup-form" class="smart-form" novalidate="novalidate">
                    <input type="hidden" name="id" id="currentPricingGroup-id">
                    <fieldset>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Pricing Group ID</label>
                                <div class="col col-4">
                                    <label class="input"> 
                                        <input type="text" name="pricingGroupId" id="currentFischerPricing-id" style= 'background-color: #A9A9A9' disabled="disabled">
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Name</label>
                                <div class="col col-4">
                                    <label class="select">
                                        <input type= "text" name="pricingGroupName" id="currentFischerPricing-name">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                    </fieldset>

                    <footer>
                        <button type="submit" class="btn btn-primary">Submit</button>                      
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->


<!-- START DATA TABLE -->
<div class= "container" id= "pricingGroupContainer">
	<button class= 'btn btn-success' id= 'createForm'>Create</button>
	<div id= 'totalRecords'></div>
	<table id="datatable_tabletools" class="table table-striped table-bordered table-hover dataTable no-footer" width="50%" role="grid" aria-describedby="datatable_tabletools_info" style="width: 50%;">
		<thead>
			<tr role="row">
				<th data-class="expand" class="expand sorting" tabindex="0" aria-controls="datatable_tabletools" rowspan="1" colspan="1" aria-label="Action" style="width: 79px;">Action</th>
				<th data-hide="phone" class="sorting_asc" tabindex="0" aria-controls="datatable_tabletools" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: activate to sort column descending" style="width: 31px;">ID</th>
				<th data-class="expand" class="expand sorting" tabindex="0" aria-controls="datatable_tabletools" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 79px;">Name</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<!-- END DATA TABLE -->

<script type= "text/javascript" language= "javascript">

    $(document).ready(function() {
        loadTable();

        //Show form for new record POST
        $('#createForm').click(function(){
            showForm(null, null);
        });

        //Hides form
        $('#updatePricingGroupModal button.close').click(function(){
            $('#updatePricingGroupModal').slideUp('fast');
        });

        //Display the Pricing Group For
        function showForm(id, name){
            $('#updatePricingGroupModal').modal('show');
            $('#currentFischerPricing-id').val(id);
            $('#currentFischerPricing-name').val(name);
        }

        //Load and insert data into table
        function loadTable(){
            $.ajax({
                url: '/slimapi/v1/pricingGroups?per_page=1000',
                type: 'GET',
                dataType: 'JSON',
                error: function(){
                    $('.container').append("<p>An Error Has Occurred!</p>");
                },
                success: function(data){
                    var items= [];
                    for (var i = 0; i < data.rows.length; i++) {
                        items.push(
                               "<tr role='row'><td id= "+ data.rows[i].PricingGroupId +">" +
                                    "<button class='btn btn-xs btn-default edit' data-original-title='Edit Row'><i class='fa fa-pencil'></i></button>" +
                                    "<button class='btn btn-xs btn-default delete' data-original-title='Cancel'><i class='fa fa-times'></i></button>" +
                            "</td><td class= 'pricing-id'>" +
                            data.rows[i].PricingGroupId
                            + "</td><td name= '"+ data.rows[i].Name +"'>" +
                            data.rows[i].Name
                            + "</td></tr>"
                        );
                    }

                    $('#totalRecords').empty().append('Total Records: ' + data.rows.length);
                    //Remove existing elements in case the table 
                    //is being reloaded after data change.
                    //Add the data to the empty tbody
                    $('tbody').empty().append(items);

                    $('.edit').click(function(){
                        var id= $(this).parent().attr('id');
                        var name= $(this).parent().next().next().attr('name');
                        showForm(id, name);
                    });

                    //Delete record
                    $('.delete').click(function(){
                        var id= $(this).parent().attr('id');
                        var remove= confirm('Are you sure you want to delete this record? ID: ' + id);
                        if(remove){

                            deleteRecord(id);
                        }
                    });
                }
            });
        }

        function deleteRecord(id){
            //DELETE Method
            $.ajax({
                url: '/slimapi/v1/pricingGroups/' + id,
                type: 'DELETE',
                processData: false,
                contentType: "application/json; charset=utf-8",
                //data: JSON.stringify(pricingData),
                success: function(){
                    $('#updatePricingGroupModal').slideUp('fast');
                    loadTable();
                    
                    //Success Animations
                    //Set Timeout to ensure the css will alter 
                    //after the new reload of the table.
                    
                    $('#pricingGroupContainer').prepend("<h3 style= 'color: #008000'>Successfully Updated!</h3>");
                    $.when($('#pricingGroupContainer h3').hide().delay(500).slideDown().delay(2000).slideUp()).done(function(){
                        $('#pricingGroupContainer h3').remove();
                    });
                }
            }).fail(function(){
                console.log('Delete failed.');
                reloadActiveTab();
            });
        }
        
        //Form Submission
        $('#updatePricingGroupModal').submit(function(){
            event.preventDefault();
            var pricingData= [
                {
                    "PricingGroupId": $('#currentFischerPricing-id').val(),
                    "Name": $('#currentFischerPricing-name').val()
                }
            ];

            //Check if group id exists
            loadTable();
            var idArray= [];
            $('.pricing-id').each(function(){
                idArray.push($(this).html());
            });

            //If the id exists do a PATCH
            //else do a POST
            if($.inArray(pricingData[0].PricingGroupId, idArray)!= -1){           

                //PATCH Method
                $.ajax({
                    url: '/slimapi/v1/pricingGroups',
                    type: 'PATCH',
                    processData: false,
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify(pricingData),
                    success: function(){
                        $('#updatePricingGroupModal').slideUp('fast');
                        loadTable();
                        $('#updatePricingGroupModal').modal('hide');
                        var currentID= $('#currentFischerPricing-id').val();
                        //Success Animations
                        //Set Timeout is used because there needs
                        //to be delay to ensure the css will alter 
                        //the new reload of the table.
                        window.setTimeout(function(){
                            $('#pricingGroupContainer #'+currentID).closest('tr').css('background-color', '#90EE90')
                        }, 500);
                        $('#pricingGroupContainer').prepend("<h3 style= 'color: #008000'>Successfully Updated!</h3>");
                        $.when($('#pricingGroupContainer h3').hide().delay(500).slideDown().delay(2000).slideUp()).done(function(){
                            $('#pricingGroupContainer h3').remove();
                        });
                    }
                }).fail(function() {
                    console.log('Update failed.');
                    reloadActiveTab();
                });
            }
            else{
                //Use POST
                //Set id to null because it should auto increment
                pricingData[0].PricingGroupId= null;

                //POST Method
                $.ajax({
                    url: '/slimapi/v1/pricingGroups',
                    type: 'POST',
                    processData: false,
                    contentType: "application/json; charset=utf-8",
                    data: JSON.stringify(pricingData),
                    success: function(){
                        $('#updatePricingGroupModal').slideUp('fast');
                        loadTable();
                        $('#updatePricingGroupModal').modal('hide');
                        var currentID= $('#currentFischerPricing-id').val();

                        //Success Animations
                        //Set Timeout to ensure the css will alter 
                        //after the new reload of the table.
                        window.setTimeout(function(){
                            //Without the filter this could highlight multiple rows
                            //of the table becuase contains() is a sub string match
                            $('td:contains(' + pricingData[0].Name  + ')').filter(function(){
                                return $(this).text() == pricingData[0].Name;
                            }).closest('tr').css('background-color', '#90EE90');
                        }, 500);
                        $('#pricingGroupContainer').prepend("<h3 style= 'color: #008000'>Successfully Updated!</h3>");
                        $.when($('#pricingGroupContainer h3').hide().delay(500).slideDown().delay(2000).slideUp()).done(function(){
                            $('#pricingGroupContainer h3').remove();
                        });
                    }
                }).fail(function() {
                    console.log('Create failed.');
                    reloadActiveTab();
                });
            }
        });   
    });

</script>