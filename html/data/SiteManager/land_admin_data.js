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
    createModalUpdateSite();
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
    
    $( "#update-legalSection-mortgage-form" ).on( "submit", function( event ) {
        event.preventDefault();
        legalSectionMortgageUpdate = {};
        legalSectionMortgageCreate = {};
        // do App2Api translation
        $('.update-legalSection').each( function(){
            let element = $(this).attr('id').split("-");
            if (typeof legalSectionMortgageUpdate[element[1]] === 'undefined')
                legalSectionMortgageUpdate[element[1]] = {};
            legalSectionMortgageUpdate[element[1]][element[2]] = $(this).val();
        });
        updateLegalSectionMortgage(legalSectionMortgageUpdate);

        $('.create-legalSectionMortgage').each( function(){
            let element = $(this).attr('id').split("-");
            if (typeof legalSectionMortgageCreate[element[1]] === 'undefined') {
                legalSectionMortgageCreate[element[1]] = {};
            }
            legalSectionMortgageCreate[element[1]][element[2]] = $(this).val();
            legalSectionMortgageCreate[element[1]].legalSectionId = $('#currentLegalSection-id').val();
        });
        createLegalSectionMortgage(legalSectionMortgageCreate);

        deleteLegalSectionMortgage(legalSectionMortgagesDelete);

        reloadActiveTab();

        $("#updateLegalSectionModal").modal('hide');
    });

    // Site validation script
    $("#update-site-form").validate({
        // Rules for form validation
        rules : {
            name : {
                required : true,
            },
            siteNumber : {
                required : true,
                siteNumberValidator : true,
            },  
            jobNumber : {
                required : true,
                jobNumberValidator : true,
            },                 
            vendorId : {
                maxlength: 5
            }
        },

        // Messages for form validation
        messages : {
            name : {
                required : "Please enter Site Code"
            }
        },

        // Do not change code below
        errorPlacement : function(error, element) {
            error.insertAfter(element.parent());
        },
        submitHandler: function(form) {
            site = {};
            // do App2Api translation
            $("#update-site-form input, #update-site-form select, #update-site-form textarea").each( function(){
                site[$(this).attr("name")] = $(this).val();
                if ($(this).val() === 'true') {
                    site[$(this).attr("name")] = true;
                } else if ($(this).val() === 'false') {
                    site[$(this).attr("name")] = false;
                }
            });

            updateSite(site);
            reloadActiveTab();
        }
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
            error = false;
            sitesToCreate = {};
            if ($('#addSite-siteNumberMiddleThreeEnd').val() === "") {
                $('#addSite-siteNumberMiddleThreeEnd').val($('#addSite-siteNumberMiddleThreeStart').val());
            }
            for (i = $('#addSite-siteNumberMiddleThreeStart').val(); i <= $('#addSite-siteNumberMiddleThreeEnd').val(); i++) {
                sitesToCreate[i] = {};
                sitesToCreate[i].communityId = $('#addSite-communityId').val();
                sitesToCreate[i].fischerSectionId = $('#addSite-fischerSectionId').val();
                sitesToCreate[i].legalSectionId = $('#addSite-legalSectionId').val();
                sitesToCreate[i].siteNumber = community.code + $('#addSite-siteNumberFirstTwo').val() + zeroFill(i) + $('#addSite-siteNumberLastFour').val();
                sitesToCreate[i].jobNumber = community.code + $('#addSite-siteNumberFirstTwo').val() + zeroFill(i) + $('#addSite-siteNumberLastFour').val();
                $.each(communitySites, function (key, val) {
                    if (val.siteNumber === sitesToCreate[i].siteNumber) {
                        if (!error) {
                            $.bigBox({
                                title : "Error: Site Number " + sitesToCreate[i].siteNumber + " already exists",
                                content : "No sites were created. Please review Site Numbers you are trying to create and try again.",
                                color : "#EA5939",
                                icon : "fa fa-exclamation fadeInLeft animated"
                            });
                        }
                        error = true;
                    }
                })
            }
            if (!error) {
                createSites(sitesToCreate);
            }
            reloadActiveTab();

            $("#addSiteModal").modal("hide");
        }
    });
    $("#addSite-communityId").on("change", function() {
        populateSiteModalAdd($(this).val());
    });
    $("#addSiteRange").on('click', function (e) {
        e.preventDefault();
        $(".addSiteRange").show();
        return false;
    });
    $("#addSite-siteNumberFirstTwo").on("change", function () {
        $("#addSite-siteNumberFirstTwoTo").removeAttr("disabled");
        $("#addSite-siteNumberFirstTwoTo").val($(this).val());
        $("#addSite-siteNumberFirstTwoTo").attr("disabled", "disabled");
    });
    $("#addSite-siteNumberLastFour").on("change", function () {
        $("#addSite-siteNumberLastFourTo").removeAttr("disabled");
        $("#addSite-siteNumberLastFourTo").val($(this).val());
        $("#addSite-siteNumberLastFourTo").attr("disabled", "disabled");
    });
});

function updateSite(site) {
    let jsonData = [];
    let jsonRow = {};

    $.each(site, function(key, value) {
        jsonRow[app2ApiMap.site[key]] = value;
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
            {data: 'action', type: 'text'},
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
            .fail(function() {
                console.log('Update failed.');
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
        fixedColumnsLeft: 4,
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
            ,130,130,120,115,140,145,145,90,60,90,80
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
            {data: 'lastModified', type: 'text'},
            {data: 'userId', type: 'text'},
            {data: 'createdDate', type: 'text'},
            {data: 'createdBy', type: 'text'}
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
                console.log('Update failed.');
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
        fixedColumnsLeft: 11,
        hiddenColumns: {
            columns: [0, 11],
            indicators: false
        },
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 20,
        viewportRowRenderingOffsetNumber: 50,
        wordWrap: false,
        colWidths: [
            /*  0 */ 50,
            /*  1 */ 50,
            /*  2 */ 50,
            /*  3 */ 50,
            /*  4 */ 105,
            /*  5 */ 105,
            /*  6 */ 100,
            /*  7 */ 90,
            /*  8 */ 100,
            /*  9 */ 100,
            /* 10 */ 70,
            /* 11 */ 120,
            /* 12 */ 100,
            /* 39 */ 100,
            /* 13 */ 90,
            /* 14 */ 90,
            /* 15 */ 150,
            /* 16 */ 140,
            /* 17 */ 120,
            /* 18 */ 120,
            /* 19 */ 120,
            /* 20 */ 75,
            /* 21 */ 70,
            /* 22 */ 70,
            /* 23 */ 90,
            /* 24 */ 130,
            /* 25 */ 100,
            /* 26 */ 130,
            /* 27 */ 120,
            /* 28 */ 120,
            /* 29 */ 120,
            /* 30 */ 110,
            /* 31 */ 110,
            /* 32 */ 90,
            /* 33 */ 110,
            /* 34 */ 90,
            /* 35 */ 110,
            /* 36 */ 90,
            /* 37 */ 110,
            /* 38 */ 90,
        ],
        colHeaders: [
            /*  0 */ 'Site Id',
            /*  1 */ 'Action',
            /*  2 */ 'Comm',
            /*  3 */ 'Site',
            /*  4 */ 'Site Number',
            /*  5 */ 'Job Number',
            /*  6 */ 'Fischer Section',
            /*  7 */ 'Legal Section',
            /*  8 */'Purchase Date',
            /*  9 */'Check Number',
            /* 10 */ 'Available',
            /* 11 */ 'Available Editable',
            /* 12 */ 'Pricing Group',
            /* 39 */ 'LOPTRecordID',
            /* 13 */ 'Site Cost',
            /* 14 */ 'Site Premium',
            /* 15 */ 'Extra Construction Cost',
            /* 16 */ 'Deposit Credit Taken',
            /* 17 */ 'Address1',
            /* 18 */ 'Address2',
            /* 19 */ 'Parcel ID',
            /* 20 */ 'PIDN Type',
            /* 21 */ 'Group #',
            /* 22 */ 'Vendor ID',
            /* 23 */ 'Purchaser',
            /* 24 */ 'Site Map Override',
            /* 25 */ 'Recorded Lot #',
            /* 26 */ 'Building Number',
            /* 27 */ 'View Premium',
            /* 28 */ 'Garage Premium',
            /* 29 */ 'WalkOut Premium',
            /* 30 */ 'Released Date',
            /* 31 */ 'Fee 1',
            /* 32 */ 'Fee1AppSca',
            /* 33 */ 'Fee 2',
            /* 34 */ 'Fee2AppSca',
            /* 35 */ 'Fee 3',
            /* 36 */ 'Fee3AppSca',
            /* 37 */ 'Fee 4',
            /* 38 */ 'Fee4AppSca',
        ],
        columns: [
            /*  0 */ {data: 'id', type: 'numeric', readOnly: true},
            /*  1 */ {data: 'action', type: 'text'},
            /*  2 */ {data: 'CommunityCode', type: 'text', readOnly: true},
            /*  3 */ {data: 'site', type: 'text', readOnly: true},
            /*  4 */ {data: 'siteNumber', type: 'text', validator: siteNumberValidator, allowInvalid: false},
            /*  5 */ {data: 'jobNumber', type: 'text', validator: jobNumberValidator, allowInvalid: false},
            /*  6 */ {
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
            /*  7 */ {
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
            /*  8 */ {data: 'purchaseDate', type: 'text'},
            /*  9 */ {data: 'checkNumber', type: 'text'},
            /* 10 */ {data: 'availableFlag', type: 'checkbox', checkedTemplate: true, uncheckedTemplate: false},
            /* 11 */ {data: 'isAvailableFlagEditable', type: 'text', readOnly: true},
            /* 12 */ {data: 'pricingGroupId', type: 'numeric'},
            /* 39 */ {data: 'loptRecordId', type: 'text'},
            /* 13 */ {data: 'siteCost', type: 'numeric', format: '$0,0.00', language: 'en-US'},
            /* 14 */ {data: 'sitePremium', type: 'numeric', format: '$0,0.00', language: 'en-US'},
            /* 15 */ {data: 'estConstCost', type: 'numeric', format: '$0,0.00', language: 'en-US'},
            /* 16 */ {data: 'credit', type: 'numeric', format: '$0,0.00', language: 'en-US'},
            /* 17 */ {data: 'address1', type: 'text'},
            /* 18 */ {data: 'address2', type: 'text'},
            /* 19 */ {data: 'parcelId', type: 'text'},
            /* 20 */ {data: 'pidnType', type: 'text'},
            /* 21 */ {data: 'groupId', type: 'text'},
            /* 22 */ {data: 'vendorId', type: 'text'},
            /* 23 */ {data: 'purchaser', type: 'text'},
            /* 24 */ {data: 'lotNumber', type: 'text'},
            /* 25 */ {data: 'legalLotNumber', type: 'text'},
            /* 26 */ {data: 'buildingNumber', type: 'text'},
            /* 27 */ {data: 'viewPremium', type: 'text'},
            /* 28 */ {data: 'garagePremium', type: 'text'},
            /* 29 */ {data: 'walkOutPremium', type: 'text'},
            /* 30 */ {data: 'releasedForSale', type: 'text'},
            /* 31 */ {data: 'fee1', type: 'text'},
            /* 32 */ {data: 'fee1AppSca', type: 'checkbox', checkedTemplate: 1, uncheckedTemplate: 0},
            /* 33 */ {data: 'fee2', type: 'text'},
            /* 34 */ {data: 'fee2AppSca', type: 'checkbox', checkedTemplate: 1, uncheckedTemplate: 0},
            /* 35 */ {data: 'fee3', type: 'text'},
            /* 36 */ {data: 'fee3AppSca', type: 'checkbox', checkedTemplate: 1, uncheckedTemplate: 0},
            /* 37 */ {data: 'fee4', type: 'text'},
            /* 38 */ {data: 'fee4AppSca', type: 'checkbox', checkedTemplate: 1, uncheckedTemplate: 0},
        ],
        beforeOnCellMouseDown: function (event, coords) {
            if (!$('#chSiteSave').is(':checked') && coords.row > -1 && this.colToProp(coords.col) === 'action') {
                site = this.getDataAtRow(coords.row);
                siteModalSiteId = site[0];
                populateSiteModal(siteModalSiteId);
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
                } else if (change[index][1] === 'PricingGroupName') {
                    change[index][1] = 'PricingGroupId';
                    change[index][3] = selectedPricingGroupId;
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
                if(request.responseText !== "") {
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
                    });
                } else {
                    $.bigBox({
                        title : "Error loading data (" + error.updateSite + ")",
                        content : "Please reload the page. If the problem persists please contact IS.",
                        color : "#EA5939",
                        icon : "fa fa-exclamation fadeInLeft animated"
                    });
                }

                $('.sapphire_loader').hide();
                $('.sapphire_fade').hide();

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
    $('.filterLegalSections').hide();
}

function createModalUpdateLegalSection() {
    $('#updateLegalSectionModal').html("");
    $('#updateLegalSectionModal').append(' <div class="modal-dialog modal-lg">\
        <div class="modal-content">\
            <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">\
                    ×\
                </button>\
                <h4 class="modal-title">Legal Section</h4>\
            </div>\
\
            <div class="modal-body no-padding">\
                <ul id="legalSectionUpdateModalTab" class="nav nav-tabs bordered">\
                    <li class="active">\
                        <a href="#legalSectionUpdateModalTab1" data-toggle="tab" aria-expanded="true">General </a>\
                    </li>\
                    <li class="">\
                        <a href="#legalSectionUpdateModalTab2" data-toggle="tab" aria-expanded="false">Mortgages </a>\
                    </li>\
                </ul>\
                <div id="legalSectionUpdateModalTabContent" class="tab-content padding-10">\
                    <div id="legalSectionUpdateModalTab1" class="tab-pane fade active in">\
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
                                        <label class="label col col-2">Recorded Name</label>\
                                        <div class="col col-4">\
                                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                                <input type="text" name="recordedName" id="currentLegalSection-recordedName">\
                                            </label>\
                                        </div>\
                                        <label class="label col col-2">Section Phase Name</label>\
                                        <div class="col col-4">\
                                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                                <input type="text" name="sectionPhaseName" id="currentLegalSection-sectionPhaseName">\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">Plat Recording Information</label>\
                                        <div class="col col-4">\
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                                <input type="text" name="platRecordingBook" id="currentLegalSection-platRecordingBook">\
                                            </label>\
                                        </div>\
                                        <label class="label col col-2">Plat Recording Date</label>\
                                        <div class="col col-4">\
                                            <label class="input"> <i class="icon-append fa fa-asterisk"></i>\
                                                <input type="text" name="platRecordingDate" id="currentLegalSection-platRecordingDate">\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">County</label>\
                                        <div class="col col-4">\
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                                <input type="text" name="county" id="currentLegalSection-CommunityCity2Name" disabled="disabled">\
                                            </label>\
                                        </div>\
                                        <label class="label col col-2">City</label>\
                                        <div class="col col-4">\
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                                <input type="text" name="city" id="currentLegalSection-CommunityCity1Name" disabled="disabled">\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">City Of Recordation</label>\
                                        <div class="col col-4">\
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                                <input type="text" name="cityOfRecordation" id="currentLegalSection-CommunityCityOfClerk" disabled="disabled">\
                                            </label>\
                                        </div>\
                                        <label class="label col col-2">Base Section</label>\
                                        <div class="col col-4">\
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                                <input type="text" name="baseSection" id="currentLegalSection-baseSection">\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">Legal Description</label>\
                                        <div class="col col-10">\
                                            <label class="textarea textarea-resizable">\
                                                <textarea rows="3" class="custom-scroll" name="legalDescription" id="currentLegalSection-legalDescription"></textarea>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">Covenant Restriction</label>\
                                        <div class="col col-10">\
                                            <label class="textarea textarea-resizable">\
                                                <textarea rows="3" class="custom-scroll" name="covenantRestriction" id="currentLegalSection-covenantRestriction"></textarea>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">Covenant Restriction 2</label>\
                                        <div class="col col-10">\
                                            <label class="textarea textarea-resizable">\
                                                <textarea rows="3" class="custom-scroll" name="covenantRestriction2" id="currentLegalSection-covenantRestriction2"></textarea>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">Covenant Restriction 3</label>\
                                        <div class="col col-10">\
                                            <label class="textarea textarea-resizable">\
                                                <textarea rows="3" class="custom-scroll" name="covenantRestriction3" id="currentLegalSection-covenantRestriction3"></textarea>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">Prior Deed Reference</label>\
                                        <div class="col col-10">\
                                            <label class="textarea textarea-resizable">\
                                                <textarea rows="3" class="custom-scroll" name="priorDeedReference" id="currentLegalSection-priorDeedReference"></textarea>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">Comments</label>\
                                        <div class="col col-10">\
                                            <label class="textarea textarea-resizable">\
                                                <textarea rows="3" class="custom-scroll" name="comments" id="currentLegalSection-comments"></textarea>\
                                            </label>\
                                        </div>\
                                    </div>\
                                </section>\
        \
                                <section>\
                                    <div class="row">\
                                        <label class="label col col-2">Mortgage Information</label>\
                                        <div class="col col-10">\
                                            <div id="currentLegalSection-mortgageInformation">\
        \
                                            </div>\
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
                    <div id="legalSectionUpdateModalTab2" class="tab-pane fade">\
                        <form action="" id="update-legalSection-mortgage-form" class="smart-form" novalidate="novalidate">\
                            <input type="hidden" name="id" id="currentLegalSection-id">\
                            <fieldset id="currentLegalSection-mortgageInformationUpdate">\
\
                            </fieldset>\
                            <fieldset id="currentLegalSection-mortgageInformationCreate">\
                            </fieldset>\
\
                            <footer>\
                                <button type="submit" class="btn btn-primary">\
                                    Submit\
                                </button>\
                                <button type="button" class="btn btn-default" data-dismiss="modal">\
                                    Cancel\
                                </button>\
                                <button type="button" id="addMortgage" class="btn btn-default">\
                                    Add Mortgage\
                                </button>\
                            </footer>\
                        </form>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>');
}

function createModalUpdateSite() {
    $('#updateSiteModalLandAdmin').html("");
    $('#updateSiteModalLandAdmin').append('<div id="updateSiteModal" class="modal-dialog modal-lg">\
        <div class="modal-content">\
            <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">\
                    ×\
                </button>\
                <h4 class="modal-title">Site: <span class="siteNumber"></h4>\
            </div>\
\
            <div class="modal-body no-padding">\
                <form action="" id="update-site-form" class="smart-form" novalidate="novalidate">\
                    <input type="hidden" name="id" id="currentSite-id">\
                    <fieldset>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Purchaser</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-user"></i>\
                                        <input type="text" name="purchaser" id="currentSite-purchaser">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Vendor ID</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-user"></i>\
                                        <input type="text" name="vendorId" id="currentSite-vendorId">\
                                    </label>\
                                </div>\
                                    <label class="label col col-2">Pricing Group</label>\
                                    <div class="col col-4">\
                                        <label class="select"> <i class="icon-append"></i>\
                                            <select name="pricingGroupId" id="currentSite-pricingGroupId">\
                                            </select>\
                                        </label>\
                                    </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Purchase Date</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-calendar"></i>\
                                        <input type="text" name="purchaseDate" id="currentSite-purchaseDate">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Released Date</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-calendar"></i>\
                                        <input type="text" name="releaseDate" id="currentSite-releasedForSale">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Check #</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="checkNumber" id="currentSite-checkNumber">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">LOPT Record ID</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="loptRecordId" id="currentSite-loptRecordId">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Address</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-address-card-o"></i>\
                                        <input type="text" name="address1" id="currentSite-address1">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Address 2</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-address-card-o"></i>\
                                        <input type="text" name="address2" id="currentSite-address2">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Parcel ID</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="parcelId" id="currentSite-parcelId">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">PIDN Type</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="pidnType" id="currentSite-pidnType">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Group #</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="groupId" id="currentSite-groupId">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Building Number</label>\
                                <div class="col col-4">\
                                    <label class="input" i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="buildingNumber" id="currentSite-buildingNumber">\
                                    </lable>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Site Cost</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-dollar"></i>\
                                        <input type="text" name="siteCost" id="currentSite-siteCost">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Site Premium</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-dollar"></i>\
                                        <input type="text" name="sitePremium" id="currentSite-sitePremium">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Site Map Override</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-dollar"></i>\
                                        <input type="text" name="lotNumber" id="currentSite-lotNumber">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Recorded Lot #</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-dollar"></i>\
                                        <input type="text" name="legalLotNumber" id="currentSite-legalLotNumber">\
                                    </label>\
                                <div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Est. Construction Cost</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-dollar"></i>\
                                        <input type="text" name="estConstCost" id="currentSite-estConstCost">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Deposit Credit Taken</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="credit" id="currentSite-credit">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section class="attached">\
                            <div class="row">\
                                <label class="label col col-2">View Premium</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-dollar"></i>\
                                        <input type="text" name="viewPremium" id="currentSite-viewPremium">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Garage Premium</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-dollar"></i>\
                                        <input type="text" name="garagePremium" id="currentSite-garagePremium">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
                        <section class="attached">\
                            <div class="row">\
                                <label class="label col col-2">WalkOut Premium</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-dollar"></i>\
                                        <input type="text" name="walkOutPremium" id="currentSite-walkOutPremium">\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\<label class="label col col-2">Mortgage Ammendments</label>\
                                <div class="col col-10">\
                                    <div id="currentSite-mortgageInformation">\
\
                                    </div>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Fee 1</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="fee1" id="currentSite-fee1">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Applies to site ajd. cost?</label>\
                                <div class="col col-4">\
                                    <label class="select"> <i class="icon-append"></i>\
                                        <select name="fee1AppSca" id="currentSite-fee1AppSca">\
                                            <option value="1">Yes</option>\
                                            <option value="0">No</option>\
                                        </select>\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Fee 2</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="fee2" id="currentSite-fee2">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Applies to site ajd. cost?</label>\
                                <div class="col col-4">\
                                    <label class="select"> <i class="icon-append"></i>\
                                        <select name="fee2AppSca" id="currentSite-fee2AppSca">\
                                            <option value="1">Yes</option>\
                                            <option value="0">No</option>\
                                        </select>\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Fee 3</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="fee3" id="currentSite-fee3">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Applies to site ajd. cost?</label>\
                                <div class="col col-4">\
                                    <label class="select"> <i class="icon-append"></i>\
                                        <select name="fee3AppSca" id="currentSite-fee3AppSca">\
                                            <option value="1">Yes</option>\
                                            <option value="0">No</option>\
                                        </select>\
                                    </label>\
                                </div>\
                            </div>\
                        </section>\
\
                        <section>\
                            <div class="row">\
                                <label class="label col col-2">Fee 4</label>\
                                <div class="col col-4">\
                                    <label class="input"> <i class="icon-append fa fa-money"></i>\
                                        <input type="text" name="fee4" id="currentSite-fee4">\
                                    </label>\
                                </div>\
                                <label class="label col col-2">Applies to site ajd. cost?</label>\
                                <div class="col col-4">\
                                    <label class="select"> <i class="icon-append"></i>\
                                        <select name="fee4AppSca" id="currentSite-fee4AppSca">\
                                            <option value="1">Yes</option>\
                                            <option value="0">No</option>\
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
    $("#addSiteModal").append('<div class="modal-dialog modal-lg">\
        <div class="modal-content">\
            <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">\
                    ×\
                </button>\
                <h4 class="modal-title">Create Sites</h4>\
            </div>\
\
            <div class="modal-body no-padding">\
                <form action="" id="add-site-form" class="smart-form" novalidate="novalidate">\
                    <footer>\
                        <button type="button" name="Cancel" class="btn btn-default" data-dismiss="modal">\
                            Cancel\
                        </button>\
                    </footer>\
                </form>\
            </div>\
        </div>\
    </div>\
    ');
}