var sitesToAdd = [];
var sitesBasicFilter = {
    tables : [
        'Community',
        'FischerSection',
        'LegalSection',
        'SpecLevel',
        'PricingGroup'
    ],
    fields : {
        'Site' : [],
        'FischerSection': [],
        'LegalSection': [],
        'SpecLevel': [],
        'Community': [],
        'PricingGroup': []
    },
    filters : {
        'Site' : {},
        'FischerSection': {},
        'LegalSection': {},
        'SpecLevel': {},
        'Community': {},
        'PricingGroup': {}
    },
    pagination : {
        'OrderByAsc' : sortSitesAsc,
        'PerPage' : sitesPerPage,
        'Page' : sitesPage,
        'OrderBy' : app2ApiMap.site[sortSitesBy]
    }
}

$(document).ready(function() {
    activateCustomView();
    createModalUpdateLegalSection();
    createModalUpdateFischerSection();
    createModalAddSite();

    // Legal Section validation script
    $("#update-legalSection-form").validate({
        // Rules for form validation
        rules : {
            name : {
                required : true,
            },
        },

        // Messages for form validation
        messages : {
            name : {
                required : "Please enter Legal Section Code",
            },
        },

        // Do not change code below
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    $( "#update-legalSection-form" ).on( "submit", function( event ) {
        event.preventDefault();
        legalSection = {};
        // do App2Api translation
        $("#update-legalSection-form input, #update-legalSection-form select, #update-legalSection-form textarea").each( function(){
            legalSection[$(this).attr("name")] = $(this).val();
        });
        // if id set do PATCH
        if (legalSection["id"] > 0) {
            updateLegalSection(legalSection);
            reloadActiveTab();
        } else {
            // else do POST
            createLegalSection(legalSection);
            reloadActiveTab();
        }

        $("#updateLegalSectionModal").modal("hide");
    });

    // FischerSection validation script
    $("#update-fischerSection-form").validate({
        // Rules for form validation
        rules : {
            name : {
                required : true
            }
        },
        // Messages for form validation
        messages : {
            name : {
                required : "Please enter Fischer Section ID"
            }
        },

        // Do not change code below
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        },
        submitHandler: function(form) {
            error = false;
            fischerSection = {};
            // do App2Api translation
            $("#update-fischerSection-form input, #update-fischerSection-form select, #update-fischerSection-form textarea").each( function(){
                fischerSection[$(this).attr("name")] = $(this).val();
            });
            $.each(communityFischerSections, function (key, val) {
                if (val.name === fischerSection.name && fischerSection.id != val.id) {
                    $.bigBox({
                        title : "Error: Fischer Section ID " + fischerSection.name + " already exists",
                        content : "No Fischer Section ID was created. Please review Fischer Section ID you are trying to create and try again.",
                        color : "#EA5939",
                        icon : "fa fa-exclamation fadeInLeft animated"
                    });
                    error = true;
                }
            })
            if(!error) {
                // if id set do PATCH
                if (fischerSection["id"] > 0) {
                    updateFischerSection(fischerSection);
                } else {
                    // else do POST
                    createFischerSection(fischerSection);
                }
            }
            reloadActiveTab();

            $("#updateFischerSectionModal").modal("hide");
        }
    });

    $('#currentFischerSection-communityId').on('change', function() {
        communityId = $(this).val();
        populateFischerSectionModal(0);
    });

    jQuery.validator.addMethod("siteNumberValidator", function(value) {
        return /^[A-Z0-9]{3}\d{9}$/.test( value );
    }, 'Please enter a valid Site Number value.');
    jQuery.validator.addMethod("jobNumberValidator", function(value) {
        return /^[A-Z0-9]{3}\d{9}$/.test( value );
    }, 'Please enter a valid Job Number value.');

    $( function() {
        $( "#currentSite-purchaseDate" ).datepicker();
    } );

    $(document).on("click", ".submitCreateSites", function(e) {
        e.preventDefault();

        $("#add-site-form").submit();
    });
        
    // Site validation script
    $("#add-site-form").validate({
        // Rules for form validation
        rules : {
            communityId : {
                required : true,
            },
            fischerSectionId : {
                required : true,
            },
            legalSectionId : {
                required : true
            },
            siteNumberFirstTwo : {
                required : true,
                minlength: 2,
                maxlength: 2,
                digits: true
            },
            siteNumberMiddleThreeStart : {
                required : true,
                minlength: 3,
                maxlength: 3,
                digits: true
            },
            siteNumberMiddleThreeEnd : {
                maxlength: 3,
                digits: true
            },
            siteNumberLastFour : {
                required : true,
                minlength: 4,
                maxlength: 4
            }
        },

        // Do not change code below
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        },
        submitHandler: function(form) {
            // Default
            error = false;
            var tempCommunityId = $('#addSite-communityId').val();
            var tempFischerSectionId = $('#addSite-fischerSectionId').val();
            var tempLegalSectionId = $('#addSite-legalSectionId').val();
            var count = 0;

            sitesToCreate = {};

            // Multiple site numbers so you need too loop and calculate.
            $.each(sitesToAdd, function(key, val) {
                // Check if there is a range.
                if(val.range > 0) {
                    if(val.range < val.second) {
                        if (!error) {
                            $.bigBox({
                                title : "Error: Site Number Range is Invalid",
                                content : "No sites were created. Please review Site Numbers you are trying to create and try again.",
                                color : "#EA5939",
                                icon : "fa fa-exclamation fadeInLeft animated"
                            });
                        }
                        error = true;
                    }

                    // Create setup loop.
                    for(var i = 0; i <= (val.range - val.second); i++) {
                        sitesToCreate[count] = {};
                        sitesToCreate[count].communityId = tempCommunityId;
                        sitesToCreate[count].fischerSectionId = tempFischerSectionId;
                        sitesToCreate[count].legalSectionId = tempLegalSectionId;
                        sitesToCreate[count].siteNumber = val.communityCode + val.first + zeroFill((parseInt(val.second, 10) + count).toString()) + val.third;
                        sitesToCreate[count].jobNumber = val.communityCode + val.first + zeroFill((parseInt(val.second, 10) + count).toString()) + val.third;
                        
                        // Check if it exists already. (Add next);
                        $.each(communitySites, function (csey, csval) {
                            if (csval.siteNumber === sitesToCreate[count].siteNumber) {
                                val.dupe = true;

                                // Set error == true so we dont add.
                                if(!error) {
                                    error = true;
                                }
                            }
                        });

                        count++;
                    }
                }
                else {
                    sitesToCreate[count] = {};
                    sitesToCreate[count].communityId = tempCommunityId;
                    sitesToCreate[count].fischerSectionId = tempFischerSectionId;
                    sitesToCreate[count].legalSectionId = tempLegalSectionId;
                    sitesToCreate[count].siteNumber = val.communityCode + val.first + zeroFill(parseInt(val.second, 10).toString()) + val.third;
                    sitesToCreate[count].jobNumber = val.communityCode + val.first + zeroFill(parseInt(val.second, 10).toString()) + val.third;

                    // Check if it exists already. (Add next);
                    $.each(communitySites, function (cskey, csval) {
                        if (csval.siteNumber === sitesToCreate[count].siteNumber) {
                            val.dupe = true;

                            // Set error == true so we dont add.
                            if(!error) {
                                error = true;
                            }
                        }
                    });

                    count++;
                }
            });

            if(!error) {
                createSites(sitesToCreate);

                reloadActiveTab();

                $("#add-site-form-list").addClass("hidden");
                $("#add-site-form-list").html("");
                $("#addSiteModal").modal("hide");
            }
            else {
                var stringToShow = "There were matches while trying to add.";

                alert(stringToShow);
            }
        }
    });

    $(document).on("change", "#addSite-communityId", function() {
        populateSiteModalAdd($(this).val());
    });

    // Add range
    $(document).on("click", ".btn-add-range", function(e) {
        e.preventDefault();

        // Get the row id for obj manipulation.
        var rowId = $(this).closest("div.row").attr("id").replace(/[^0-9\.]/g, '');
        
        // Change the obj.
        sitesToAdd[rowId].range = 0;

        // Render the updated html.
        renderSiteNumbers(sitesToAdd);
    });

    // Delete range
    $(document).on("click", ".btn-remove-range", function() {
        // Get the row id for obj manipulation.
        var rowId = $(this).closest("div.row").attr("id").replace(/[^0-9\.]/g, '');

        // Change the obj.
        sitesToAdd[rowId].range = null;

        // Render the updated html.
        renderSiteNumbers(sitesToAdd);
        renderSiteDisplayList(sitesToAdd);
    });

    // Update site number
    $(document).on("change", "#sites-section div.row input", function() {
        // Get the row id for obj manipulation.
        var rowId = $(this).closest("div.row").attr("id").replace(/[^0-9\.]/g, '');
        var elementId = $(this).attr("id");
        // get the element name.
        var elementName = $(this).attr("name");
        var shouldRender = false;

        switch(elementName) {
            case "siteNumbercommunityCode":
                var tempCode = $(this).val();

                $.each(sitesToAdd, function(key, val) {
                    sitesToAdd[key].communityCode = tempCode;
                });
                shouldRenderList = true;
                break;
            case "siteNumberFirstTwo":
                var tempCode = $(this).val();

                $.each(sitesToAdd, function(key, val) {
                    sitesToAdd[key].first = tempCode;
                });
                shouldRenderList = true;
                break;
            case "siteNumberMiddleThreeStart":
                sitesToAdd[rowId].second = $(this).val();
                shouldRenderList = true;
                break;
            case "siteNumberLastFour":
                sitesToAdd[rowId].third = $(this).val();
                shouldRenderList = true;
                break;
            case "siteNumberMiddleThreeEnd":
                sitesToAdd[rowId].range = $(this).val();
                shouldRenderList = true;
                break;
        }

        // Render the updated html.
        if(sitesToAdd[rowId].range < 1 || sitesToAdd === null) {
            renderSiteNumbers(sitesToAdd);

            // Refocus last element.
            $("#" + elementId).parent().next().focus();
        }

        if(shouldRenderList) {
            renderSiteDisplayList(sitesToAdd);
        }
    });

    // Add new site 
    $(document).on("click", "#addNewSite", function() {
        // Create temp array for adding new site.
        if(sitesToAdd[0] && sitesToAdd[0].communityCode) {
            var temp = { 
                    communityCode: sitesToAdd[0].communityCode,
                    first: sitesToAdd[0].first, 
                    second: null, 
                    third: sitesToAdd[0].third,
                    range: null,
                    dupe: false,
                }

            sitesToAdd.push(temp);

            renderSiteNumbers(sitesToAdd);
            renderSiteDisplayList(sitesToAdd);
        }
    });

    // Remove secondary sites
    $(document).on("click", ".btn-remove-site", function(e) {
        e.preventDefault();

        // Get the row id for obj manipulation.
        var rowId = $(this).closest("div.row").attr("id").replace(/[^0-9\.]/g, '');

        sitesToAdd.splice(rowId, 1);

        renderSiteNumbers(sitesToAdd);
        renderSiteDisplayList(sitesToAdd);
    });

    // When closing create sites modal clear the information.
    $(document).on("click", ".cancelCreateSites", function(e) {
        e.preventDefault();

        $("#sites-section").html('');
        $("#addSite-communityId").prop("disabled", false);
        $("#form-div").removeClass("show-border-right");
        $("#add-site-form-list").hide();
        $("#add-site-form-list").html("");
    });

    $(document).on("click", ".cancel-form", function(e) {
        e.preventDefault();

        holderModal.close();
    });

    $(document).on("click", ".submit-form", function(e) {
        e.preventDefault();
        
        // Grab all forms being submitted.
        var formsSubmitted = $(this).closest(".container-fluid").parent().find("form");

        // For each of them send them to validation.
        $.each(formsSubmitted, function() {
            // Switch on id.
            var formId = $(this).attr("id");

            $("#" + formId).submit();
        } );

        //holderModal.close();
    });

    $(document).on("submit", "#update-site-form", function(e) {
        e.preventDefault();

        var site = {};
        var limitations = {};
        var site_id = null;

        // Loop through every input in the form and submit it.
        $("#update-site-form input, #update-site-form select, #update-site-form textarea").each( function(){
            var attrName = $(this).attr("name");
            var attrValue = $(this).val();
            var attrId = $(this).attr("id");

            if(attrId == "currentSiteUpdate-id") {
                site_id = attrValue;
            }

            if(attrId == "currentSiteUpdate-limitation") {
                limitations["limitations"] = attrValue;
            }
            else{
                site[app2ApiMap.site[attrName]] = attrValue;

                if(attrValue === 'true') {
                    site[app2ApiMap.site[attrName]] = true;
                } 
                else if(attrValue === 'false') {
                    site[app2ApiMap.site[attrName]] = false;
                }
            }
        });

        updateSite(site);
        updateSiteLimitations(site_id, limitations["limitations"]);

        holderModal.close();
    });

    $(document).on("submit", "#update-site-limitations-form", function(e) {
        e.preventDefault();

        var limitations = {};

        // Create function to delete all limitations on current site. then update the ones selected.
        $("#update-site-limitations-form select, #update-site-limitations-form input").each(function() {
            var attrName = $(this).attr("name");
            var attrValue = $(this).val();

            if(attrName == "id") {
                limitations[app2ApiMap.site[attrName]] = attrValue;
            }
            else {
                limitations["limitations"] = attrValue;
            }
        });
        // Delete all limitations.
        updateSiteLimitations(limitations['SiteId'], limitations["limitations"]);
    });
});
// End Document Ready

function updateSite(site) {
    let jsonData = [];
    let jsonRow = {};

    $.each(site, function(key, value) {
        jsonRow[key] = value;
    });
    delete jsonRow['undefined'];
    jsonData.push(jsonRow);

    $.ajax({
        url: '/slimapi/v1/sites',
        type: 'PATCH',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function() {
        return site;
    })
    .fail(function(request, status, error) {

        $.bigBox({
            title : "Update Site failed",
            content : request.responseText,
            color : "#EA5939",
            timeout: 8000,
            icon : "fa fa-exclamation fadeInLeft animated"
        });

        console.log('Site ' + site['SiteId'] + ' update failed.');
        return false;
    });
}

/*
    Needs to be used in promises so you can update the limitations after
    this is completed.
*/
function updateSiteLimitations(siteId, limitationsArray) {
    var siteLimitationsFilter = {
        per_page : -1,
        page : 1,
    }

    var newSiteTestArray = [];
    var newLimitations = [];
    var results = {};
    var html = "";
    var title = "Deleted";
    var type = "warning";

    // Fetch current site limitations.
    $.when(
        getSiteLimitations(siteId, siteLimitationsFilter)
    ).then(function(siteLimitations) {
        if(siteLimitations.rows !== "undefined" && siteLimitations.rows.length > 0) {
            $.each(siteLimitations.rows, function(key, val) {
                if(limitationsArray == null || limitationsArray.indexOf(val.LimitationId.toString()) === -1) {
                    // Deleted the record.
                    $.ajax({
                        url: "/slimapi/v1/site/limitations/" + val.RefLimitationsId,
                        type: "DELETE",
                        success: function(data, textStatus, xhr) {
                            if(xhr.status == 204) {
                                html += "Deleted Limitation: " + val.Code + "\n";
                            } else {
                                html += "Deleted Failed Limitation: " + val.Code + "\n";
                            }
                        }
                    });
                }
                else{
                    newSiteTestArray.push(val.LimitationId.toString());
                }
            });

            if(newSiteTestArray.length > 0) {
                $.each(limitationsArray, function(key, val) {
                    if(newSiteTestArray.indexOf(val) === -1) {
                        // Add to ref limitation sites.
                        newLimitations.push(val);
                    }
                });
            }
            else {
                newLimitations = limitationsArray;
            }
        }
        else {
            newLimitations = limitationsArray;
        }

        if(newLimitations !== null && newLimitations.length > 0) {
            var ajaxObj = {
                community : null,
                fischerSection : null,
                legalSection : null,
                limitations : newLimitations
            };

            $.ajax({
                url: "/slimapi/v1/sites/" + siteId + "/limitations",
                type: "POST",
                dataType: 'json',
                contentType: "application/json; charset=utf-8",
                data : JSON.stringify(ajaxObj),
                success: function(data, textStatus, xhr) {
                    // Check what status was sent.
                    if(xhr.status == 200 || xhr.status == 202) {
                        var title = "Success!";
                        var type = "success";
                        html += "Successfully added all limitations!";
                    }
                    else {
                        var title = "Something went wrong!";
                        var type = "error";
                        html += "Invalid data was set to create please review your selections and try again.";
                    }

                    swal({
                        title: title,
                        type: type,
                        html: html
                    });
                }
            });
        }
    });
}

function getCreateSiteLimitationsForm() {
    // Fix Make this a promise for call backs.
    holderModal = new FloatingModal({
        content: "app/forms/set_limitations_form.php",
        contentIsFile: true,
        contentTitle: "Create Site Limitations",
    });

    $("body").addClass("noscroll");

    holderModal.open();
}

function getEditSitesForm(id) {
    if(id > 0){
        var limitationsFilter = {
            per_page: -1
        };

        var siteLimitationsFilter = {
            per_page : -1,
            page : 1,
        }

        // Pull the data and then show the form.
        $.when(
            // Create the floating modal
            holderModal = createFloatingModal("Site: <span class='siteNumber'>Test</span>", "app/forms/site_design_edit_site.php", true, '50%')
        ).then(function() {
            // hit the api for site values.
            var legalSectionFilter = {
                per_page: -1
            };

            $.when(
                getSite(id)
            ).then(function() {
                $.when(
                    // Assign the values to the modal here.
                    getFischerSectionsByCommunityID(site.communityId),
                    getLegalSectionsByCommunityID(site.communityId, legalSectionFilter),
                    getSiteLimitations(site.id, siteLimitationsFilter),
                    getLimitations(limitationsFilter),
                ).always(function(fischerSections, legalSections, siteLimitations, limitations) {
                    $('.siteNumber').text(site.jobNumber);

                    $.each(Object.keys(app2ApiMap.site), function(index, field) {
                        if(field == "id") {
                            $("#currentSiteLimitationUpdate-" + field).val('');
                        }

                        $("#currentSiteUpdate-" + field).val('');
                    });

                    // populate communities options with current cummunity selected
                    $('#currentSiteUpdate-communityId').empty();
                    $.each(communitiesFiltered, function(val, community) {
                        $('#currentSiteUpdate-communityId').append( $('<option></option>').val(community.id).html(community.code + ' ' + community.name) );
                    });

                    // populate Fischer Section options with current Fischer Section selected
                    $('#currentSiteUpdate-fischerSectionId').empty();
                    $.each(fischerSections[0].CommunitySections, function(val, fischerSection) {
                        $('#currentSiteUpdate-fischerSectionId').append( $('<option></option>').val(fischerSection.SectionId).html(fischerSection.SectionName) );
                    });

                    // populate Legal Section options with current Legal Section selected
                    $('#currentSiteUpdate-legalSectionId').empty();
                    $.each(legalSections[0].rows, function(val, legalSection) {
                        $('#currentSiteUpdate-legalSectionId').append( $('<option></option>').val(legalSection.LegalSectionId).html(legalSection.LegalSectionName) );
                    });

                    // populate Spec Level options
                    $('#currentSiteUpdate-specLevelId').empty();
                    $.each(specLevels, function(val, specLevel) {
                        $('#currentSiteUpdate-specLevelId').append( $('<option></option>').val(specLevel.id).html(specLevel.name) );
                    });

                    // populate Spec Level options
                    $('#currentSiteUpdate-pricingGroupId').empty();
                    $.each(prependNullObject(pricingGroups), function(val, pricingGroup) {
                        $('#currentSiteUpdate-pricingGroupId').append( $('<option></option>').val(pricingGroup.id).html(pricingGroup.name) );
                    });

                    $("#currentSiteUpdate-limitation").empty();
                    renderLimitationSectionSelections(limitations[0], "currentSiteUpdate-limitation", siteLimitations[0].rows);
                }).done(function() {
                    $.each(Object.keys(app2ApiMap.site), function(index, field) {
                        if(field == "id") {
                            $("#currentSiteLimitationUpdate-" + field).val(site[field]);
                        }

                        $("#currentSiteUpdate-" + field).val(site[field]);

                        if(field == 'purchaseDate' && site[field]) {
                            $("#currentSiteUpdate-" + field).val(moment(site[field]).format('MM/DD/YYYY'));
                        }
                    });

                    $("#currentSiteUpdate-communityId option[value='"+site.communityId+"']").prop('selected', true);
                    $("#currentSiteUpdate-fischerSectionId option[value='"+site.fischerSectionId+"']").prop('selected', true);
                    $("#currentSiteUpdate-legalSectionId option[value='"+site.legalSectionId+"']").prop('selected', true);
                    $("#currentSiteUpdate-specLevelId option[value='"+site.specLevelId+"']").prop('selected', true);
                    $("#currentSiteUpdate-pricingGroupId option[value='"+site.pricingGroupId+"']").prop('selected', true);
                    $("#currentSiteUpdate-availableFlag option[value='"+site.availableFlag+"']").prop('selected', true);
                    $("#currentSiteUpdate-availableFlag").css('cursor', 'auto');
                    $("#currentSiteUpdate-availableFlag").prop('disabled', false);

                    if (!site.isAvailableFlagEditable) {
                        $("#currentSiteUpdate-availableFlag").prop('disabled', 'disabled');
                        $("#currentSiteUpdate-availableFlag").css('cursor', 'not-allowed');
                    }

                    $(".attached").show();
                    if(site.buildingType == '' || site.buildingType == null) {
                        $(".attached").hide();
                    }

                    // Mortgage Ammendment data
                    $('#currentSiteUpdate-mortgageInformation').empty();
                    $.each(siteMortgageAmmendments, function(val, mortgageAmmendment) {
                        if(mortgageAmmendment.requestDate) {
                            requestDate = new Date(Date.parse(mortgageAmmendment.requestDate));
                            requestDate.setSeconds(requestDate.getSeconds() + 18000);
                            requestDateString = requestDate.toDateString();
                        } 
                        else {
                            releaseDateString = '';
                        }

                        if(mortgageAmmendment.receivedDate) {
                            receivedDate = new Date(Date.parse(mortgageAmmendment.receivedDate));
                            receivedDate.setSeconds(receivedDate.getSeconds() + 18000);
                            receivedDateString = receivedDate.toDateString();
                        } 
                        else {
                            receivedDateString = '';
                        }

                        if(mortgageAmmendment.recordedDate) {
                            recordedDate = new Date(Date.parse(mortgageAmmendment.recordedDate));
                            recordedDate.setSeconds(recordedDate.getSeconds() + 18000);
                            recordedDateString = recordedDate.toDateString();
                        } 
                        else {
                            recordedDateString = '';
                        }

                        if(mortgageAmmendment.releaseDate) {
                            releaseDate = new Date(Date.parse(mortgageAmmendment.releaseDate));
                            releaseDate.setSeconds(releaseDate.getSeconds() + 18000);
                            releaseDateString = releaseDate.toDateString();
                        } 
                        else {
                            releaseDateString = '';
                        }

                        $('#currentSiteUpdate-mortgageInformation').append( $('<div class="well col col-6"></div>').html('<div class="row"><div class="col col-4">FH Mortgage Amend #: </div><div class="col col-8">' + mortgageAmmendment.ammendmentNumber + '</div></div>\
                            <div class="row"><div class="col col-4">Request Date:</div><div class="col col-8">' + releaseDateString + '</div></div>\
                            <div class="row"><div class="col col-4">Received Date:</div><div class="col col-8">' + receivedDateString + '</div></div>\
                            <div class="row"><div class="col col-4">Recorded Date:</div><div class="col col-8">' + recordedDateString + '</div></div>\
                            <div class="row"><div class="col col-4">Release Date:</div><div class="col col-8">' + releaseDateString + '</div></div>\
                            <div class="row"><div class="col col-4">Lender:</div><div class="col col-8">' + mortgageAmmendment.lender + '</div></div>\
                            '));
                    });

                    $("#currentSiteUpdate-siteNumber").prop("disabled", true);
                    $("#currentSiteUpdate-jobNumber").prop("disabled", true);

                    $(".selectpicker").selectpicker('refresh');

                    $("body").addClass("noscroll");

                    holderModal.open();
                });
            });
        });
    }
}

function createCustomCommunityHot() {
    return hotCommunity = new Handsontable($communityContainer[0], {
        allowRemoveRow: false,
        allowRemovecolumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
        autoColumnSize: false,
        autoRowSize: false,
        columnSorting: true,
        contextMenu: false,
        data: [],
        fillHandle: false,
        fixedColumnsLeft: 5,
        hiddenColumns: {
            columns: [0],
            indicators: false
        },
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 5,
        colWidths: [80,80,115,250,200],
        colHeaders: ["Id", "Division", "Community Code", "Community Name", "Budget Neighborhood"],
        columns: [
            {"data": "id", "type": "numeric", "readOnly": true},
            {"data": "DivisionCode", "type": "text", "readOnly": true},
            {"data": "code", "type": "text", "readOnly": true},
            {"data": "name", "type": "text", "readOnly": true},
            {"data": "BudgetneighborhoodName", "type": "text", "readOnly": true}
        ],
    });
}

function createCustomFischerSectionHot() {
    return hotFischerSection = new Handsontable($fischerSectionContainer[0], {
        allowRemoveRow: true,
        allowRemovecolumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
        autoRowSize: false,
        autoColumnSize: false,
        columnSorting: true,
        contextMenu: true,
        data: [],
        fixedColumnsLeft: 5,
        hiddenColumns: {
            columns: [0],
            indicators: false
        },
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 5,
        wordWrap: false,
        colWidths: [80,80,115,100,160],
        colHeaders: [
            "F. Section Id", "Action", "<span title='No sort'>Community Code", "Section Name", "<span title='No sort'>Spec Level</sort>"
            ],
        columns: [
            {"data": "id", "type": "numeric", "readOnly": true},
            {"data": "action", type: "text"},
            {"data": "CommunityCode", "type": "text", "readOnly": true},
            {"data": "name", "type": "text"},
            {
                "data": "SpeclevelName",
                "type": "handsontable",
                "handsontable": {
                    "colHeaders": ["Spec Level Id", "Spec Level Descr"],
                    "autoColumnSize": true,
                    data: specLevels,
                    getValue: function() {
                        var selection = this.getSelected();
                        // Set selectedSpecLevelId to be used in creates/updates
                        selectedSpecLevelId = this.getSourceDataAtRow(selection[0]).id;

                        // Return name to be displayed
                        return this.getSourceDataAtRow(selection[0]).name;
                    }
                }
            }
        ],
        beforeOnCellMouseDown: function (event, coords) {
            if (!$('#chFischerSectionSave').is(':checked') && coords.row > -1 && this.colToProp(coords.col) === 'action') {
                fischerSection = this.getDataAtRow(coords.row);
                fischerSectionModalFischerSectionId = fischerSection[0];
                populateFischerSectionModal(fischerSectionModalFischerSectionId);
                $("#updateFischerSectionModal").modal('show');
            }

            return;
        },
        afterChange: function (change, source) {
            if (source === 'loadData') {
                // console.log('Loading data.');
                return;
            }
            if (!$('#chFischerSectionSave').is(':checked')) {
                $.bigBox({
                    title : "Save on Change is not on",
                    content : "Please select Save on Change before updating or adding data.",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                return;
            }
            jsonData = [];
            $.each(change, function(index, val) {
                jsonData[index] = {};
                var row = hotFischerSection.getDataAtRow(val[0]);
                // Special handling for belongsTo relation
                if (change[index][1] === 'SpeclevelName') {
                    change[index][1] = 'SpecLevelId';
                    change[index][3] = selectedSpecLevelId;
                } else {
                    change[index][1] = app2ApiMap.fischerSection[change[index][1]];
                }
                change[index][0] = row[0];
                jsonData[index][app2ApiMap.fischerSection['id']] = row[0];
                jsonData[index][change[index][1]] = change[index][3];
            });
            $.ajax({
                url: '/slimapi/v1/fischerSections',
                type: 'PATCH',
                processData: false,
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(jsonData)
            })
            .done(function() {
                reloadActiveTab();
            })
            .fail(function() {
                console.log('Insert/Update failed.');
                reloadActiveTab();
            });
        },
        beforeRemoveRow: function(index, amount) {
            if(amount > 1) {
                $.bigBox({
                    title : "Method not supported",
                    content : "Please delete one record at a time",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                reloadActiveTab();
                return;
            }
            if (!$('#chFischerSectionSave').is(':checked')) {
                $.bigBox({
                    title : "Save on Change is not on",
                    content : "Please select Save on Change before deleting data.",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                reloadActiveTab();
                return;
            }
            removeId = hotFischerSection.getDataAtCell(index, 0);
            $.ajax({
                url: '/slimapi/v1/fischerSections/' + removeId,
                type: 'DELETE',
                dataType: 'json'
            })
            .done(function() {
                reloadActiveTab();
            })
            .fail(function() {
                console.log('Fischer Section delete failed.');
                reloadActiveTab();
            });
        },
        beforeColumnSort: function(column) {
            if (sortFischerSectionsBy === hotFischerSection.colToProp(column)) {
                if (sortFischerSectionsAsc === 'asc') {
                    sortFischerSectionsAsc = 'desc';
                } else {
                    sortFischerSectionsAsc = 'asc';
                }
            } else {
                sortFischerSectionsAsc = 'asc';
            }
            sortFischerSectionsBy = hotFischerSection.colToProp(column);
            reloadActiveTab();
            return false;
        }
    });
}

function createCustomLegalSectionHot() {
    return hotLegalSection = new Handsontable($legalSectionContainer[0], {
        allowRemoveRow: true,
        allowRemovecolumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
        autoRowSize: false,
        autoColumnSize: false,
        columnSorting: true,
        contextMenu: true,
        data: [],
        fixedColumnsLeft: 3,
        hiddenColumns: {
            columns: [0],
            indicators: false
        },
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 20,
        wordWrap: false,
        colWidths: [
            80,130,115,125,140,130,145,150,150,150,150,150,150,165,170,140,145,150,150,150,
            80,155,150,150,140,150,160,150,165,150,160,130,130,140,140,140,150,120,120,130,165,110,120,
            130,130,120,140,140,160,140,160,150,80,80,130,130,110,120,150,120
            ,130,130,120,115,140,145,145,190,60,190,80
        ],
        colHeaders: [
            'L. Section Record Id', 'Action', '<span title="No sort">Community Code</sort>', 'Legal Section Code', 'Section Phase Name',
            'Zoning Jurisdiction','Zoning Jurisdiction2','Zoning Classification','Zoning Classification2','Front Yard Minimum','Front Yard Minimum2',
            'Side Yard Minimum','Side Yard Minimum2','Combined Side Minimum','Combined Side Minimum2','Rear Yard Minimum','Rear Yard Minimum2',
            'Sideon Corner Lot Min','Sideon Corner Lot Min2','Prior Deed Reference','Comments',
            'Min Lot Size Front Entry','Min Lot Size Side Entry',
            'Min Lot Size Rear Entry','Tfg Zoning Jurisdictio','Tfg Zoning Classificat','Tfg Front Yard Minimum','Tfg Side Yard Minimum',
            'Tfg Combined Side Minim','Tfg Rear Yard Minimum','Tfg Sideon Corner Minim','Width At Setback','Width At Setback2','Tfg Width At Setback',
            'Side Next To Street1','Side Next To Street2','Tfg Side Next To Street','Min Sq Ft Per Lot','Min Sq Ft Ranch',
            'Min Sq Ft 2 Story','Min Sq Ft2 Story1 Master','Base Section','Base Section Old','Min Sq Ft Per Lot2','Tfg Zoning Juris2','Tfg Zoning Class2',
            'Tfg Front Yard Min 2','Tfg Side Yard Min2','Tfg Combined Side Min2','Tfg Rear Yard Min2',
            'Frg Side On Corner Min2','Tfg Width At Setback2','Developer','Engineer','Zone1 Spec Level Id',
            'Zone2 Spec Level Id','Max Sq Ft Ranch','Ranch Comments','Two Story Comments','Max Sq Ft 2Story'
            ,'Plat Recording Book','Plat Recording Date',
            'Recorded Name','Legal Description','Covenant Restriction','Covenant Restriction2','Covenant Restriction3',
            'Last Modified','User Id','Created Date','Created By',
            ],
        columns: [
            {data: 'id', type: 'numeric', readOnly: true},
            {data: 'action', type: 'text'},
            {data: 'CommunityCode', type: 'text', readOnly: true},
            {data: 'name', type: 'text'},
            {data: 'sectionPhaseName', type: 'text'},
            {data: 'zoningJurisdiction', type: 'text'},
            {data: 'zoningJurisdiction2', type: 'text'},
            {data: 'zoningClassification', type: 'text'},
            {data: 'zoningClassification2', type: 'text'},
            {data: 'frontYardMinimum', type: 'text'},
            {data: 'frontYardMinimum2', type: 'text'},
            {data: 'sideYardMinimum', type: 'text'},
            {data: 'sideYardMinimum2', type: 'text'},
            {data: 'combinedSideMinimum', type: 'text'},
            {data: 'combinedSideMinimum2', type: 'text'},
            {data: 'rearYardMinimum', type: 'text'},
            {data: 'rearYardMinimum2', type: 'text'},
            {data: 'sideonCornerLotMin', type: 'text'},
            {data: 'sideonCornerLotMin2', type: 'text'},
            {data: 'priorDeedReference', type: 'text'},
            {data: 'comments', type: 'text'},
            {data: 'minLotSizeFrontEntry', type: 'text'},
            {data: 'minLotSizeSideEntry', type: 'text'},
            {data: 'minLotSizeRearEntry', type: 'text'},
            {data: 'tfgZoningJurisdictio', type: 'text'},
            {data: 'tfgZoningClassificat', type: 'text'},
            {data: 'tfgFrontYardMinimum', type: 'text'},
            {data: 'tfgSideYardMinimum', type: 'text'},
            {data: 'tfgCombinedSideMinim', type: 'text'},
            {data: 'tfgRearYardMinimum', type: 'text'},
            {data: 'tfgSideonCornerMinim', type: 'text'},
            {data: 'widthAtSetback', type: 'text'},
            {data: 'widthAtSetback2', type: 'text'},
            {data: 'tfgWidthAtSetback', type: 'text'},
            {data: 'sideNextToStreet1', type: 'text'},
            {data: 'sideNextToStreet2', type: 'text'},
            {data: 'tfgSideNextToStreet', type: 'text'},
            {data: 'minSqFtPerLot', type: 'text'},
            {data: 'minSqFtRanch', type: 'text'},
            {data: 'minSqFt2Story', type: 'text'},
            {data: 'minSqFt2Story1Master', type: 'text'},
            {data: 'baseSection', type: 'text'},
            {data: 'baseSectionOld', type: 'text'},
            {data: 'minSqFtPerLot2', type: 'text'},
            {data: 'tfgZoningJuris2', type: 'text'},
            {data: 'tfgZoningClass2', type: 'text'},
            {data: 'tfgFrontYardMin2', type: 'text'},
            {data: 'tfgSideYardMin2', type: 'text'},
            {data: 'tfgCombinedSideMin2', type: 'text'},
            {data: 'tfgRearYardMin2', type: 'text'},
            {data: 'tfgSideOnCornerMin2', type: 'text'},
            {data: 'tfgWidthAtSetback2', type: 'text'},
            {data: 'developer', type: 'text'},
            {data: 'engineer', type: 'text'},
            {data: 'zone1SpecLevelId', type: 'text'},
            {data: 'zone2SpecLevelId', type: 'text'},
            {data: 'maxSqFtRanch', type: 'text'},
            {data: 'ranchComments', type: 'text'},
            {data: 'twoStoryComments', type: 'text'},
            {data: 'maxSqFt2Story',type: 'text'},
            {data: 'platRecordingBook', type: 'text'},
            {data: 'platRecordingDate', type: 'text'},
            {data: 'recordedName', type: 'text'},
            {data: 'legalDescription', type: 'text'},
            {data: 'covenantRestriction', type: 'text'},
            {data: 'covenantRestriction2', type: 'text'},
            {data: 'covenantRestriction3', type: 'text'},
            {data: 'lastModifiedDate', type: 'date', "readOnly": true},
            {data: 'lastModifiedBy', type: 'text', "readOnly": true},
            {data: 'createdDate', type: 'date', "readOnly": true},
            {data: 'createdBy', type: 'text', "readOnly": true}
        ],
        beforeOnCellMouseDown: function (event, coords) {
            if (!$('#chLegalSectionSave').is(':checked') && coords.row > -1 && this.colToProp(coords.col) === 'name') {
                legalSection = this.getDataAtRow(coords.row);
                legalSectionId = legalSection[0];
                populateLegalSectionDropdown();
            } else if (!$('#chLegalSectionSave').is(':checked') && coords.row > -1 && this.colToProp(coords.col) === 'action') {
                legalSection = this.getDataAtRow(coords.row);
                legalSectionModalLegalSectionId = legalSection[0];
                populateLegalSectionModal(legalSectionModalLegalSectionId);
                $("#updateLegalSectionModal").modal('show');
            }

            return;
        },
        afterChange: function (change, source) {
            if (source === 'loadData') {
                // console.log('Loading data.');
                return;
            }
            if (!$('#chLegalSectionSave').is(':checked')) {
                $.bigBox({
                    title : "Save on Change is not on",
                    content : "Please select Save on Change before updating or adding data.",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                return;
            }
            jsonData = [];
            $.each(change, function(index, val) {
                jsonData[index] = {};
                var row = hotLegalSection.getDataAtRow(val[0]);
                change[index][1] = app2ApiMap.legalSection[change[index][1]];
                change[index][0] = row[0];
                jsonData[index][app2ApiMap.legalSection['id']] = row[0];
                jsonData[index][change[index][1]] = change[index][3];
            });

            $.ajax({
                url: '/slimapi/v1/legalSections',
                type: 'PATCH',
                processData: false,
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(jsonData)
            })
            .fail(function() {
                console.log('Insert/Update failed.');
                reloadActiveTab();
            });
        },
        beforeRemoveRow: function(index, amount) {
            if(amount > 1) {
                $.bigBox({
                    title : "Method not supported",
                    content : "Please delete one record at a time",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                reloadActiveTab();
                return;
            }
            if (!$('#chLegalSectionSave').is(':checked')) {
                $.bigBox({
                    title : "Save on Change is not on",
                    content : "Please select Save on Change before deleting data.",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                reloadActiveTab();
                return;
            }
            removeId = hotLegalSection.getDataAtCell(index, 0);
            $.ajax({
                url: '/slimapi/v1/legalSections/' + removeId,
                type: 'DELETE',
                dataType: 'json'
            })
            .done(function() {
                reloadActiveTab();
            })
            .fail(function() {
                console.log('Legal Section delete failed.');
                reloadActiveTab();
            });
        },
        beforeColumnSort: function(column) {
            if (sortLegalSectionsBy === hotLegalSection.colToProp(column)) {
                if (sortLegalSectionsAsc === 'asc') {
                    sortLegalSectionsAsc = 'desc';
                } else {
                    sortLegalSectionsAsc = 'asc';
                }
            } else {
                sortLegalSectionsAsc = 'asc';
            }
            sortLegalSectionsBy = hotLegalSection.colToProp(column);
            reloadActiveTab();
            return false;
        }
    });
}

function createCustomSiteHot() {
    return hotSite = new Handsontable($siteContainer[0], {
        allowRemoveRow: true,
        allowRemovecolumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
        autoRowSize: false,
        autoColumnSize: false,
        columnSorting: true,
        contextMenu: false,
        data: [],
        fixedColumnsLeft: 7,
        hiddenColumns: {
            columns: [0,8,10],
            indicators: false
        },
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 20,
        wordWrap: false,
        colWidths: [
            80,50,50,50,110,
            110,100,100,60,70,
            110,220,220,90,160,
            180,180,50,110,50,
            110,100,110,90,65,
            120,70,70,80,120,
            90,100,80,130,150,
            120,120,120,90,90,
            120,110,90,70,130,
            110,70,110,70,90,
            80,120,120,120,120,
            65,75,120,65,120,
            120,120,120,210
        ],
        colHeaders: [
            'Site Id', 'Action', '<span title="No sort">Comm</sort>', 'Site', 'Site Number',
            'Job Number', '<span title="No sort">Fischer Section</sort>', '<span title="No sort">Legal Section</sort>', 'Hold', 'Available',
            'Available Editable', 'General Notes', 'Construction Notes', 'Site Premium', 'Extra Construction Cost',
            'Address1', 'Address2', 'Width', 'Buildable Width', 'Depth',
            'Buildable Depth', 'Front Setback', 'Garage Location', 'Garage Entry', 'Rear Exit',
            'Foundation Type', 'TOF/TOS', 'Sea Level', 'A/B Curb', 'Above/Below Elev', 
            'Sewer Tap', 'Sanitary Invert', 'Water Tap', 'Utility Easement', 'Sanitary Easement', 
            'Storm Easement', 'Water Easement', 'Other Easement', 'Drain Pattern', 'Catch Basin', 
            'Sanitary Manhole', 'Storm Manhole', 'Fire Hydrant', 'Sidewalk', 'Secondary Pedestal', 
            'Transformers', 'SiteRating', 'GC Job Number', 'Purchaser', 'Site Cost',
            'Vendor ID', 'View Premium', 'Garage Premium', 'Walkout Premium', '<span title="No sort">Specificaton Level</sort>',
            'Parcel Id', 'PIDN Type', 'Legal Lot Number', 'Group Id', 'Building Number',
            'Released Date', 'Purchase Date', 'Check Number', 'Fire Suppression Room Address'
        ],
        columns: [
            {data: 'id', type: 'numeric', readOnly: true},
            {data: 'action', type: 'text'},
            {data: 'CommunityCode', type: 'text', readOnly: true},
            {data: 'site', type: 'text', readOnly: true},
            {data: 'siteNumber', type: 'text', validator: siteNumberValidator, allowInvalid: false},
            {data: 'jobNumber', type: 'text', validator: jobNumberValidator, allowInvalid: false},
            {
                data: 'FischerSectionName',
                type: 'handsontable',
                handsontable: {
                    colHeaders: ['Fischer Section Id', 'Fischer Section Name'],
                    autoColumnSize: true,
                    data: communityFischerSections,
                    getValue: function() {
                        var selection = this.getSelected();
                        // Set selectedFischerSectionId to be used in creates/updates
                        selectedFischerSectionId = this.getSourceDataAtRow(selection[0]).id;
                        // Return name to be displayed
                        return this.getSourceDataAtRow(selection[0]).name;
                    }
                }
            },
            {
                data: 'LegalSectionName',
                type: 'handsontable',
                handsontable: {
                    colHeaders: ['Legal Section Id', 'Legal Section Name'],
                    autoColumnSize: true,
                    data: communityLegalSections,
                    getValue: function() {
                        var selection = this.getSelected();
                        // Set selectedLegalSectionId to be used in creates/updates
                        selectedLegalSectionId = this.getSourceDataAtRow(selection[0]).id;
                        // Return name to be displayed
                        return this.getSourceDataAtRow(selection[0]).name;
                    }
                }
            },
            {
                data: 'HoldName',
                type: 'handsontable',
                handsontable: {
                    colHeaders: ['Hold Id', 'Hold Name'],
                    autoColumnSize: true,
                    data: siteHolds,
                    getValue: function() {
                        var selection = this.getSelected();
                        // Set selectedSiteHoldId to be used in creates/updates
                        //FIXME use map
                        selectedSiteHoldId = this.getSourceDataAtRow(selection[0]).code;

                        // Return name to be displayed
                        return this.getSourceDataAtRow(selection[0]).description;
                    }
                }
            },
            {data: 'availableFlag', type: 'checkbox', checkedTemplate: true, uncheckedTemplate: false},
            {data: 'isAvailableFlagEditable', type: 'text', readOnly: true},
            {data: 'generalNotes', type: 'text'},
            {data: 'constructionNotes', type: 'text'},
            {data: 'sitePremium', type: 'numeric', format: '$0,0.00', language: 'en-US'},
            {data: 'estConstCost', type: 'numeric', format: '$0,0.00', language: 'en-US'},
            {data: 'address1', type: 'text'},
            {data: 'address2', type: 'text'},
            {data: 'width', type: 'text'},
            {data: 'buildableWidth', type: 'text'},
            {data: 'depth', type: 'text'},
            {data: 'buildableDepth', type: 'text'},
            {data: 'setback', type: 'text'},
            {data: 'garageLocation', type: 'text'},
            {data: 'garageEntry', type: 'text'},
            {data: 'rearExit', type: 'text'},
            {data: 'basementDescription', type: 'text'},
            {data: 'tosOrF', type: 'text'},
            {data: 'topOf', type: 'text'},
            {data: 'aOrBCurb', type: 'text'},
            {data: 'aboveCurb', type: 'text'},
            {data: 'sewerTap', type: 'text'},
            {data: 'sanitaryInvert', type: 'text'},
            {data: 'waterTap', type: 'text'},
            {data: 'utilityEasement', type: 'text'},
            {data: 'sanitaryEasement', type: 'text'},
            {data: 'stormEasement', type: 'text'},
            {data: 'waterEasement', type: 'text'},
            {data: 'otherEasement', type: 'text'},
            {data: 'drainagePattern', type: 'text'},
            {data: 'catchBasin', type: 'text'},
            {data: 'sanitaryManhole', type: 'text'},
            {data: 'stormManhole', type: 'text'},
            {data: 'fireHydrant', type: 'text'},
            {data: 'sidewalks', type: 'text'},
            {data: 'secondaryPedestal', type: 'text'},
            {data: 'transformers', type: 'text'},
            {data: 'siteRating', type: 'text'},
            {data: 'gcJobNumber', type: 'text'},
            {data: 'purchaser', type: 'text'},
            {data: 'siteCost', type: 'numeric', format: '$0,0.00', language: 'en-US'},
            {data: 'vendorId', type: 'text'},
            {data: 'viewPremium', type: 'text'},
            {data: 'garagePremium', type: 'text'},
            {data: 'walkOutPremium', type: 'text'},
            {
                data: 'SpeclevelName',
                type: 'handsontable',
                handsontable: {
                    colHeaders: ['Speclevelid', 'Specleveldescr'],
                    autoColumnSize: true,
                    data: specLevels,
                    getValue: function() {
                        var selection = this.getSelected();
                        // Set selectedSpecLevelId to be used in creates/updates
                        //FIXME use map
                        selectedSpecLevelId = this.getSourceDataAtRow(selection[0]).id;

                        // Return name to be displayed
                        return this.getSourceDataAtRow(selection[0]).name;
                    }
                }
            },
            {data: 'parcelId', type: 'text'},
            {data: 'pidnType', type: 'text'},
            {data: 'legalLotNumber', type: 'text'},
            {data: 'groupId', type: 'text'},
            {data: 'buildingNumber', type: 'text'},
            {data: 'releasedForSale', type: 'text'},
            {data: 'purchaseDate', type: 'text'},
            {data: 'checkNumber', type: 'text'},
            {data: 'fireSuppressRoomAddress', type: 'text'}
        ],
        beforeOnCellMouseDown: function (event, coords) {
            if (!$('#chSiteSave').is(':checked') && coords.row > -1 && this.colToProp(coords.col) === 'action') {
                site = this.getDataAtRow(coords.row);
                siteModalSiteId = site[0];
                getEditSitesForm(siteModalSiteId);
            }

            return;
        },
        beforePaste: function(data, coords) {
            // TODO: Determine if column is numeric
            for(var row = 0; row < data.length; row++) { // Row
                for(var col = 0; col < data[row].length; col++) { // Column
                    var y = row + coords[0].startRow;
                    var x = col + coords[0].startCol;

                    // Remove dollar signs before paste
                    if(hotSite.getDataType(y, x) === 'numeric') {
                        data[row][col] = removeNonNumeric(data[row][col]);
                    }
                }
            }
        },
        afterChange: function (change, source) {
            if (source === 'loadData') {
                // console.log('Loading data.');
                return;
            }
            if (!$('#chSiteSave').is(':checked')) {
                $.bigBox({
                    title : "Save on Change is not on",
                    content : "Please select Save on Change before updating or adding data.",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                return;
            }
            jsonData = [];
            $.each(change, function(index, val) {
                jsonData[index] = {};
                var row = hotSite.getDataAtRow(val[0]);
                // Special handling for belongsTo relation
                if (change[index][1] === 'FischerSectionName') {
                    change[index][1] = app2ApiMap.site['fischerSectionId'];
                    change[index][3] = selectedFischerSectionId;
                } else if (change[index][1] === 'LegalSectionName') {
                    change[index][1] = app2ApiMap.site['legalSectionId'];
                    change[index][3] = selectedLegalSectionId;
                } else if (change[index][1] === 'SpeclevelName') {
                    change[index][1] = 'SpecLevelId';
                    change[index][3] = selectedSpecLevelId;
                } else {
                    // Get and Post API endpoints require different field names for relational data
                    change[index][1] = app2ApiMap.site[change[index][1]].replace('community_site_inventory.','');
                }
                change[index][0] = row[0];
                jsonData[index][app2ApiMap.site['id']] = row[0];
                jsonData[index][change[index][1]] = change[index][3];
            });
            $.ajax({
                url: '/slimapi/v1/sites',
                type: 'PATCH',
                processData: false,
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(jsonData),
                beforeSend: function(){
                    $('.sapphire_loader').show();
                    $('.sapphire_fade').show();
                },
                complete: function(){
                     $('.sapphire_loader').hide();
                     $('.sapphire_fade').hide();
                }
            })
            .fail(function(request, status, error) {
                errorObject = JSON.parse(request.responseText);
                errorSites = errorObject.errors;
                $.each(errorSites, function(index, val) {
                    $.bigBox({
                        title : errorObject.message,
                        content : 'Site ' + val.resource + ' could not be updated.',
                        color : "#EA5939",
                        timeout: 8000,
                        icon : "fa fa-exclamation fadeInLeft animated"
                    });
                })
                console.log('Insert/Update failed.');
                reloadActiveTab();
            });
        },
        beforeRemoveRow: function(index, amount) {
            if(amount > 1) {
                $.bigBox({
                    title : "Method not supported",
                    content : "Please delete one record at a time",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                reloadActiveTab();
                return;
            }
            if (!$('#chSiteSave').is(':checked')) {
                $.bigBox({
                    title : "Save on Change is not on",
                    content : "Please select Save on Change before deleting data.",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                reloadActiveTab();
                return;
            }
            removeId = hotSite.getDataAtCell(index, 0);
            $.ajax({
                url: '/slimapi/v1/sites/' + removeId,
                type: 'DELETE',
                dataType: 'json'
            })
            .done(function() {
                reloadActiveTab();
            })
            .fail(function() {
                console.log('Site delete failed.');
                reloadActiveTab();
            });
        },
        beforeColumnSort: function(column) {
            if (sortSitesBy === hotSite.colToProp(column)) {
                if (sortSitesAsc === 'asc') {
                    sortSitesAsc = 'desc';
                } else {
                    sortSitesAsc = 'asc';
                }
            } else {
                sortSitesAsc = 'asc';
            }
            sortSitesBy = hotSite.colToProp(column);
            reloadActiveTab();
            return false;
        }
    });
}

function activateCustomView() { 
    $('#filterFischerSectionName').hide();

    $('#filterSiteJobNumber').hide();
}

function createModalUpdateLegalSection() {
    $('#updateLegalSectionModal').html("");
    $('#updateLegalSectionModal').append('<div class="modal-dialog modal-lg">\
        <div class="modal-content">\
            <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">\
                    ×\
                </button>\
                <h4 class="modal-title">Legal Section</h4>\
            </div>\
\
            <div class="modal-body no-padding">\
                <form action="" id="update-legalSection-form" class="smart-form" novalidate="novalidate">\
                    <input type="hidden" name="id" id="currentLegalSection-id">\
                    <fieldset>\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Community</label>\
                                <div class="col col-4">\
                                    <label class="select"> <i class="icon-append fa fa-book"></i>\
                                        <select name="communityId" id="currentLegalSection-communityId">\
                                        </select>\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Legal Section Code</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-book"></i>\
                                        <input type="text" name="name" id="currentLegalSection-name">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-6 text-center"><h5>Fischer</h5></label>\
                                <label class="label col col-6 text-center"><h5>Legal</h5></label>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-12"><h6>Setbacks</h6></label>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">TFG Front Yard Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-book"></i>\
                                        <input type="text" name="tfgFrontYardMinimum" id="currentLegalSection-tfgFrontYardMinimum">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Front Yard Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-book"></i>\
                                        <input type="text" name="frontYardMinimum" id="currentLegalSection-frontYardMinimum">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">TFG Side Yard Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="tfgSideYardMinimum" id="currentLegalSection-tfgSideYardMinimum">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Side Yard Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-asterisk"></i>\
                                        <input type="text" name="sideYardMinimum" id="currentLegalSection-sideYardMinimum">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">TFG Combined Side Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="tfgCombinedSideMinim" id="currentLegalSection-tfgCombinedSideMinim">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Combined Side Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="combinedSideMinimum" id="currentLegalSection-combinedSideMinimum">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">TFG Rear Yard Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="tfgRearYardMinimum" id="currentLegalSection-tfgRearYardMinimum">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Rear Yard Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="rearYardMinimum" id="currentLegalSection-rearYardMinimum">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">TFG Side on Corner Min</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="tfgSideonCornerMinim" id="currentLegalSection-tfgSideonCornerMinim">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Side on Corner</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="sideonCornerLotMin" id="currentLegalSection-sideonCornerLotMin">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">TFG Width at Setback</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="tfgWidthAtSetback" id="currentLegalSection-tfgWidthAtSetback">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Width at Setback</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="widthAtSetback" id="currentLegalSection-widthAtSetback">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-12"><h6>Zoning</h6></label>\
                            </div>\
                        <section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">TFG Zoning Juristiction</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="tfgZoningJurisdictio" id="currentLegalSection-tfgZoningJurisdictio">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Zoning Juristiction</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="zoningJurisdiction" id="currentLegalSection-zoningJurisdiction">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">TFG Zoning Classification</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="tfgZoningClassificat" id="currentLegalSection-tfgZoningClassificat">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Zoning Classification</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="zoningClassification" id="currentLegalSection-zoningClassification">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-8"> </label>\
                                <label class="label col col-2"> Min Sq. Ft.</label>\
                                <label class="label col col-2"> Max Sq. Ft.</label>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2"> </label>\
                                <div class="col col-4">\
                                </div>\
                                <label class="label col col-2">Per Lot</label>\
                                <div class="col col-2">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="minSqFtPerLot" id="currentLegalSection-minSqFtPerLot">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2"> </label>\
                                <div class="col col-4">\
                                </div>\
                                <label class="label col col-2">Ranch</label>\
                                <div class="col col-2">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="minSqFtRanch" id="currentLegalSection-minSqFtRanch">\
                                    </label>\
                                </div>\
                                <div class="col col-2">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="maxSqFtRanch" id="currentLegalSection-maxSqFtRanch">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2"> </label>\
                                <div class="col col-4">\
                                </div>\
                                <label class="label col col-2">2 Story</label>\
                                <div class="col col-2">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="minSqFt2Story" id="currentLegalSection-minSqFt2Story">\
                                    </label>\
                                </div>\
                                <div class="col col-2">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="maxSqFt2Story" id="currentLegalSection-maxSqFt2Story">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2"> </label>\
                                <div class="col col-4">\
                                </div>\
                                <label class="label col col-2">2 Story - Master</label>\
                                <div class="col col-2">\
                                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                        <input type="text" name="minSqFt2Story1Master" id="currentLegalSection-minSqFt2Story1Master">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                    </fieldset>\
\
                    <footer>\
                        <button type="submit" class="btn btn-primary">\
                            Submit\
                        </button>\
                        <button type="button" class="btn btn-default" data-dismiss="modal">\
                            Cancel\
                        </button>\
                    </footer>\
                </form>\
            </div>\
        </div>\
    </div>');
}

function createModalUpdateFischerSection() {
    $('#updateFischerSectionModal').html("");
    $('#updateFischerSectionModal').append('<div class="modal-dialog modal-lg">\
        <div class="modal-content">\
            <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">\
                    ×\
                </button>\
                <h4 class="modal-title">Fischer Section</h4>\
            </div>\
\
            <div class="modal-body no-padding">\
                <form action="" id="update-fischerSection-form" class="smart-form" novalidate="novalidate">\
                    <input type="hidden" name="id" id="currentFischerSection-id">\
                    <fieldset>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Community</label>\
                                <div class="col col-4">\
                                    <label class="select"> <i class="icon-append"></i>\
                                        <select name="communityId" id="currentFischerSection-communityId">\
                                        </select>\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Fischer Section ID</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-sitemap"></i>\
                                        <input type="text" name="name" id="currentFischerSection-name">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Spec Level</label>\
                                <div class="col col-4">\
                                    <label class="select"> <i class="icon-append"></i>\
                                        <select name="specLevelId" id="currentFischerSection-specLevelId">\
                                        </select>\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                    </fieldset>\
\
                    <footer>\
                        <button type="submit" class="btn btn-primary">\
                            Submit\
                        </button>\
                        <button type="button" class="btn btn-default" data-dismiss="modal">\
                            Cancel\
                        </button>\
                    </footer>\
                </form>\
            </div>\
        </div>\
    </div>');
}

function createModalAddSite() {
    $("#addSiteModal").html("");
    $("#addSiteModal").append('<div id="sites-add-modal" class="modal-dialog modal-lg">\
        <div class="modal-content">\
            <div class="modal-header">\
                <button type="button" class="close cancelCreateSites" data-dismiss="modal" aria-hidden="true">\
                    ×\
                </button>\
                <h4 class="modal-title">Create Sites</h4>\
            </div>\
\
            <div class="modal-body no-padding">\
                <div id="form-div" class="col col-md-12">\
                    <form action="" id="add-site-form" class="smart-form" novalidate="novalidate">\
                        <fieldset class="col col-md-12">\
    \
                            <section>\
                                <div class="row">\
                                    <label class="label col col-2">Community</label>\
                                    <div class="col col-4">\
                                        <label class="select"> <i class="icon-append fa fa-sitemap"></i>\
                                            <select name="communityId" id="addSite-communityId">\
                                            </select>\
                                        </label>\
                                    </div>\
                                </div>\
                            </section>\
    \
                            <section>\
                                <div class="row">\
                                    <label class="label col col-2">Fischer Section</label>\
                                    <div class="col col-4">\
                                        <label class="select"> <i class="icon-append"></i>\
                                            <select name="fischerSectionId" id="addSite-fischerSectionId">\
                                            </select>\
                                        </label>\
                                    </div>\
                                </div>\
                            </section>\
    \
                            <section>\
                                <div class="row">\
                                    <label class="label col col-2">Legal Section</label>\
                                    <div class="col col-4">\
                                        <label class="select"> <i class="icon-append"></i>\
                                            <select name="legalSectionId" id="addSite-legalSectionId">\
                                            </select>\
                                        </label>\
                                    </div>\
                                </div>\
                            </section>\
    \
                            <section id="sites-section">\
                            </section>\
    \
                            <section>\
                                <div class="row">\
                                    <div class="col col-2">&nbsp;\
                                    </div>\
                                    <label class="label col col-4"><a id="addNewSite" >Add New Site Number</a></label>\
                                </div>\
                            </section>\
    \
                        </fieldset>\
                    </form>\
                </div>\
                <div id="add-site-form-list" class="col-md-2 hidden"></div>\
                <div class="clearfix"></div>\
            </div>\
            <div class="modal-footer">\
                <button type="submit" name="Submit" class="btn btn-primary hidden submitCreateSites">\
                    Submit\
                </button>\
                <button type="button" name="Cancel" class="btn btn-default cancelCreateSites" data-dismiss="modal">\
                    Cancel\
                </button>\
            </div>\
        </div>\
    </div>\
    ');
}

/*
    New render function for create sites form.
*/
function renderSiteNumbers(siteNumbersArray) {
    // Return HTML
    var html = "";

    $.each(siteNumbersArray, function (key, val) {
        // Defaults
        var showDeleteRange = false;
        var rowPadding = "";
        var marginPadding = "";
        var disabledCommonElements = "";

        // Validation
        if(val.communityCode === undefined || val.communityCode === null) {
            val.communityCode = "";
        }

        if(val.first === undefined || val.first === null) {
            val.first = "";
        }

        if(val.second === undefined || val.second === null) {
            val.second = "";
        }

        if(val.third === undefined || val.third === null) {
            val.third = "";
        }

        html += "<div id='site" + key + "' class='row'>";
            // If key = 0 then its the first row.
            if(key === 0) {
                html += "<label class='label col col-2'>Site Number</label>";
            }
            else {
                disabledCommonElements = "disabled";
                rowPadding = "style='padding-top: 7px;'";
                marginPadding = "style='margin-top: 7px;'";
                
                html += "<label class='label col col-2'>";

                // For format reason this will look better.
                if(key > 0) {
                    html += "<button class='btn btn-danger btn-smart-form btn-remove-site'>Remove Site</button>";
                }
                
                html += "</label>";
            }

            html += "<div class='col col-4' " + rowPadding + ">";
                html += "<label class='input col col-3' style='padding-left: 0px'>";
                    html += "<input type='text' name='siteNumbercommunityCode' id='addSite-siteNumbercommunityCode" + key + "' value='" + val.communityCode + "' disabled>";
                html += "</label>";
                html += "<label class='input col col-3' style='padding-left: 0px'>";
                    html += "<input type='text' name='siteNumberFirstTwo' id='addSite-siteNumberFirstTwo" + key + "' placeholder='##' maxlength='2' value='" + val.first + "' " + disabledCommonElements + " required>";
                html += "</label>";
                html += "<label class='input col col-3' style='padding-left: 0px'>";
                    html += "<input type='text' name='siteNumberMiddleThreeStart' id='addSite-siteNumberMiddleThreeStart" + key + "' placeholder='###' maxlength='3' value='" + val.second + "' required>";
                html += "</label>";
                html += "<label class='input col col-3' style='padding-left: 0px'>";
                    html += "<input type='text' name='siteNumberLastFour' id='addSite-siteNumberLastFour" + key + "' placeholder='####' maxlength='4' value='" + val.third + "' required>";
                html += "</label>";
            html += "</div>";

            if(val.range === null) {
                html += "<button class='btn btn-default btn-smart-form btn-add-range' " + marginPadding + ">Add Range</button>";
            }

            if(val.range !== null) {
                if(val.range === 0) {
                    val.range = "";
                }

                showDeleteRange = true;

                html += "<label class='label col col-1 addSiteRange text-center' " + marginPadding + ">To</label>";

                html += "<div class='col col-4 addSiteRange' " + rowPadding + ">";
                    html += "<label class='input col col-3' style='padding-left: 0px'>";
                        html += "<input type='text' name='siteNumbercommunityCode' id='addSite-siteNumbercommunityCodeTo" + key + "' value='" + val.communityCode + "' disabled>";
                    html += "</label>";
                    html += "<label class='input col col-3' style='padding-left: 0px'>";
                        html += "<input type='text' name='siteNumberFirstTwo' id='addSite-siteNumberFirstTwoTo" + key + "' placeholder='##' maxlength='2' value='" + val.first + "' required disabled>";
                    html += "</label>";
                    html += "<label class='input col col-3' style='padding-left: 0px'>";
                        html += "<input type='text' name='siteNumberMiddleThreeEnd' id='addSite-siteNumberMiddleThreeEnd" + key + "' placeholder='###' maxlength='3' value='" + val.range + "' required>";
                    html += "</label>";
                    html += "<label class='input col col-3' style='padding-left: 0px'>";
                        html += "<input type='text' name='siteNumberLastFour' id='addSite-siteNumberLastFourTo" + key + "' placeholder='####' maxlength='4' value='" + val.third + "' required disabled>";
                    html += "</label>";
                html += "</div>";
            }

            if(showDeleteRange) {
                html += "<a class='btn btn-default btn-smart-form btn-remove-range' " + marginPadding + ">X</a>";
            }

        html += "</div>";
    });

    $("#sites-section").html(html);
}

function renderSiteDisplayList(siteNumbersArray) {
    // Default variables.
    var html = "<ul class='create-site-list'>";
    var showSubmit = false;
    var hasDupe = false;
    // Holder for testing if site will exist after create.
    var arrayCreateSiteNumber = [];

    // Create the list.
    $.each(siteNumbersArray, function (key, val) {
        // Validation
        if(val.communityCode === undefined || val.communityCode === null) {
            val.communityCode = "";
        }

        if(val.first === undefined || val.first === null) {
            val.first = "";
        }

        if(val.second === undefined || val.second === null) {
            val.second = "";
        }

        if(val.third === undefined || val.third === null) {
            val.third = "";
        }

        if(val.second !== "") {
            // Check if there is a range.
            if(val.range > 0) {
                for(var i = 0; i <= (val.range - val.second); i++) {
                    // setup sitenumber
                    var tempSiteNumber = val.communityCode + val.first + zeroFill(parseInt(val.second) + i).toString() + val.third;
                    var dupeCSSClass = "";

                    // Check if length of tempSiteNumber is equal to correct sitenumber
                    if(tempSiteNumber.length === 12) {
                        showSubmit = true;
                        var checkDupe = checkSiteMatch(tempSiteNumber, arrayCreateSiteNumber);
                        // Push after check so we dont fail on current.
                        arrayCreateSiteNumber.push(tempSiteNumber);

                        if(checkDupe) {
                            dupeCSSClass = "duplicate-site";
                            val.dupe = true;
                            hasDupe = true;
                        }

                        // Render the html.
                        html += "<li class='" + dupeCSSClass + "'>" + val.communityCode + "  " + val.first + "  " + zeroFill(parseInt(val.second) + i).toString() + "  " + val.third + "</li>";
                    }
                }
            }
            else {
                // setup sitenumber
                var tempSiteNumber = val.communityCode + val.first + zeroFill(parseInt(val.second, 10).toString()) + val.third;
                var dupeCSSClass = "";

                // Check if length of tempSiteNumber is equal to correct sitenumber
                if(tempSiteNumber.length === 12) {
                    showSubmit = true;
                    var checkDupe = checkSiteMatch(tempSiteNumber, arrayCreateSiteNumber);
                    // Push after check so we dont fail on current.
                    arrayCreateSiteNumber.push(tempSiteNumber);

                    if(checkDupe) {
                        dupeCSSClass = "duplicate-site";
                        val.dupe = true;
                        hasDupe = true;
                    }

                    // Render the html.
                    html += "<li class='" + dupeCSSClass + "'>" + val.communityCode + "  " + val.first + "  " + zeroFill(parseInt(val.second, 10).toString()) + "  " + val.third + "</li>";
                }
            }
        }
    });

    html += "</ul>";

    if(showSubmit) {
        $("#form-div").addClass("col-md-10").addClass("show-border-right").removeClass("col-md-12");

        $("#add-site-form-list").height($("#form-div").height());
        $("#add-site-form-list").html(html);
        $("#add-site-form-list").removeClass("hidden");

        if(!hasDupe) {
            $(".submitCreateSites").removeClass("hidden");
        }
        else if(!$(".submitCreateSites").hasClass("hidden")) {
            $(".submitCreateSites").addClass("hidden");
        }
    }
    else if($("#add-site-form").closest("div").hasClass("show-border-right")) {
        $("#form-div").removeClass("show-border-right");
        $("#form-div").addClass("col-md-12").removeClass("col-md-10");

        $("#add-site-form-list").addClass("hidden");
        $("#add-site-form-list").html("");
    }
}

function checkSiteMatch(siteToMatch, currentCreateSitesArray) {
    // Variable to return.
    var isDupe = false;

    $.each(communitySites, function (key, val) {
        if (val.siteNumber === siteToMatch) {
            isDupe = true;
        }
    });

    $.each(currentCreateSitesArray, function(key, val) {
        if(val === siteToMatch) {
            isDupe = true;
        }
    });

    return isDupe;
}
