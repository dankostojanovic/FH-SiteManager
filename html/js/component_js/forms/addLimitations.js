$(document).ready(function() {
	$('.selectpicker').selectpicker({
		style: 'btn-default',
		width: '100%'
	});

	// Handle all changes to the community selection.
    $(document).on("changed.bs.select", "#limitations-community", function(e) {
    	var sitesFilter = sitesBasicFilter;

        sitesFilter.pagination['PerPage'] = -1;

        if($("#limitations-community").val() > 0) {
            sitesFilter.filters['Site'][app2ApiMap.site.communityId] = {"value":$("#limitations-community").val(), "operator":" = "};
        }

        if($("#limitations-fischer").val() > 0) {
            delete sitesFilter.filters['Site'][app2ApiMap.site.fischerSectionId];
        }

        if($("#limitations-legal").val() > 0) {
            delete sitesFilter.filters['Site'][app2ApiMap.site.legalSectionId];
        }

        var limitationsFilter = {
	        per_page: -1
	    };

        var legalSectionFilter = {
            per_page: -1
        };

    	$.when(
    		getFischerSectionsByCommunityID($(this).val()),
    		getLegalSectionsByCommunityID($(this).val(), legalSectionFilter),
    		getSites(sitesFilter),
    		getLimitations(limitationsFilter),
    	)
    	.then(function(fischerSections, legalSections, sitesSections, limitations) {
    		renderFischerSectionSelections(fischerSections[0], "limitations-fischer", fischerSectionId);
    		renderLegalSectionSelections(legalSections[0], "limitations-legal", legalSectionId);
    		renderSiteSectionSelections(sitesSections[0], "site-select");
    		renderLimitationSectionSelections(limitations[0], "limitation-select");
    	})
    	.done(function(fischerSections, legalSections) {
    		$('.selectpicker').selectpicker('refresh');
    	})
    	.fail(function() {
    		console.log("Loading other limitations form failed.");
    	});
    });

    $(document).on("changed.bs.select", "#limitations-fischer", function(e) {
    	var sitesFilter = sitesBasicFilter;

        sitesFilter.pagination['PerPage'] = 10000;

        if($("#limitations-fischer").val() > 0) {
            sitesFilter.filters['Site'][app2ApiMap.site.fischerSectionId] = {"value":$("#limitations-fischer").val(), "operator":" = "};
        } else {
            delete sitesFilter.filters['Site'][app2ApiMap.site.fischerSectionId];
        }

    	$.when(
    		getSites(sitesFilter),
    	)
    	.then(function(sitesSections) {
    		renderSiteSectionSelections(sitesSections, "site-select");
    	})
    	.done(function(fischerSections, legalSections) {
    			$('.selectpicker').selectpicker('refresh');
    	})
    	.fail(function() {
    		console.log("Loading other limitations form failed.");
    	});
    });

    $(document).on("changed.bs.select", "#limitations-legal", function(e) {
    	var sitesFilter = sitesBasicFilter;

        sitesFilter.pagination['PerPage'] = 10000;

        if($("#limitations-legal").val() > 0) {
            sitesFilter.filters['Site'][app2ApiMap.site.legalSectionId] = {"value":$("#limitations-legal").val(), "operator":" = "};
        } else {
            delete sitesFilter.filters['Site'][app2ApiMap.site.legalSectionId];
        }

    	$.when(
    		getSites(sitesFilter),
    	)
    	.then(function(sitesSections) {
    		renderSiteSectionSelections(sitesSections, "site-select");
    	})
    	.done(function(fischerSections, legalSections) {
    		$('.selectpicker').selectpicker('refresh');
    	})
    	.fail(function() {
    		console.log("Loading other limitations form failed.");
    	});
    });

    $(document).on("click", ".submit-right-form", function(e) {
        e.preventDefault();
        var error = [];
        error['error'] = false;
        error['message'] = [];
        var fischerSection = null;
        var legalSection = null;

        if($("#limitations-community").val() < 1) {
            if(!error['error']) {
                error['error'] = true;
            }

            error['message'].push("Invalid community");
        }

        if($("#site-select").val() == null || $("#site-select").val().length < 1) {
            if(!error['error']) {
                error['error'] = true;
            }

            error['message'].push("You must select at least one site");
        }

        if($("#limitation-select").val() == null || $("#limitation-select").val().length < 1) {
            if(!error['error']) {
                error['error'] = true;
            }

            error['message'].push("You must select at least one limitation");
        }

        if(error['error']) {
            // clear errors section and display the errors.
            $(".errors-box").html('');
            $(".errors-box").append("<h3 class='error-title'>Please fix these errors</h3>");

            // Loop through the errors and display.
            error['message'].forEach(function(message, index) {
                $(".errors-box").append("<p>" + message + "</p>");
            });

            $(".errors-box").show();
        }
        else {
            $(".errors-box").hide();
            $(".errors-box").html('');

            // Set nulls
            if(!$("#limitations-fischer").val() == null && $("#limitations-fischer").val() > 0) {
                fischerSection = $("#limitations-fischer").val();
            }

            if(!$("#limitations-legal").val() == null && $("#limitations-legal").val() > 0) {
                legalSection = $("#limitations-legal").val();
            }

            var ajaxObj = {
                community : $("#limitations-community").val(),
                fischerSection : fischerSection,
                legalSection : legalSection,
                limitations : $("#limitation-select").val()
            };
            var promises = [];

            // Make initial pop up showing what will be added.
            var selectedSites = {};
            var selectedLimitations = {};
            var htmlSitesTable = "<table class='table table-bordered table-striped'><thead><tr><th>Sites</th></tr></thead><tbody>";
            var htmlLimitationsTable = "<table class='table table-bordered table-striped'><thead><tr><th>Limitations</th></tr></thead><tbody>";

            $.each($("#site-select option:selected"), function() {
            	selectedSites[$(this).val()] = $(this).text();
            	htmlSitesTable += "<tr><td>" + $(this).text() + "</td></tr>";
            });

            $.each($("#limitation-select option:selected"), function() {
            	selectedLimitations[$(this).val()] = $(this).attr("title");
            	htmlLimitationsTable += "<tr><td>" + $(this).attr("title") + "</td></tr>";
            });

            htmlSitesTable += "</tbody></table>";
            htmlLimitationsTable += "</tbody></table>";
            var type = "success";

            var objKeys = Object.keys(selectedSites);

            swal({
                title: "Are you sure you want to add these limitations to the following " + objKeys.length + " sites?",
                type: "info",
                html: "<div class='col-md-6 swal-table'>" + htmlSitesTable + "</div><div class='col-md-6 swal-table'>" + htmlLimitationsTable + "</div>",
                showCancelButton: true,
                confirmButtonText: "Yes these are correct!",
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        $.each($("#site-select option:selected"), function() {
                            if($(this).val() !== null || $(this).val() !== "undefined") {
                                var request = $.ajax({
                                        url: "/slimapi/v1/sites/" + $(this).val() + "/limitations",
                                        type: "POST",
                                        contentType: "application/json; charset=utf-8",
                                        data : JSON.stringify(ajaxObj),
                                        success: function(data, textStatus, xhr) {

                                        }
                                    });

                                promises.push(request);
                            }
                        });

                        $.when.apply(null, promises).done(function() {
                            var htmlReturn = "";
                            var htmlCreated = "";
                            var htmlDuplicates = "";
                            var htmlFailed = "";

                            $.each(promises, function(key, value) {
                                if(typeof(value.responseJSON.rows.created) !== "undefined") {
                                    $.each(value.responseJSON.rows.created, function(key2, val2) {
                                        htmlCreated += "<tr><td>" + selectedSites[val2.SiteId] + "</td><td>" + selectedLimitations[val2.LimitationId] + "</td></tr>";
                                    });
                                }

                                if(typeof(value.responseJSON.rows.duplicates) !== "undefined") {
                                    $.each(value.responseJSON.rows.duplicates, function(key3, val3) {
                                        htmlDuplicates += "<tr><td>" + selectedSites[val3.SiteId] + "</td><td>" + selectedLimitations[val3.LimitationId] + "</td></tr>";
                                    });
                                }

                                if(typeof(value.responseJSON.rows.failed) !== "undefined") {
                                    $.each(value.responseJSON.rows.failed, function(key4, val4) {
                                        htmlReturn += "<tr><td>" + selectedSites[val4.SiteId] + "</td><td>" + selectedLimitations[val4.LimitationId] + "</td></tr>";
                                    });
                                }
                            });

                            if(htmlCreated !== "") {
                                htmlReturn += "<h3>Created</h3>";
                                htmlReturn += "<table class='table table-bordered table-striped'>";
                                htmlReturn += "<thead><tr><th>Site</th><th>Limitation</th></tr></thead><tbody>";

                                htmlReturn += htmlCreated;

                                htmlReturn += "</tbody></table>";
                            }

                            if(htmlDuplicates !== "") {
                                type = "warning";

                                htmlReturn += "<h3>Duplicates</h3>";
                                htmlReturn += "<table class='table table-bordered table-striped'>";
                                htmlReturn += "<thead><tr><th>Site</th><th>Limitation</th></tr></thead><tbody>";

                                htmlReturn += htmlDuplicates;

                                htmlReturn += "</tbody></table>";
                            }

                            if(htmlFailed !== "") {
                                type = "error";
                                
                                htmlReturn += "<h3>Failed</h3>";
                                htmlReturn += "<table class='table table-bordered table-striped'>";
                                htmlReturn += "<thead><tr><th>Site</th><th>Limitation</th></tr></thead><tbody>";

                                htmlReturn += htmlFailed;

                                htmlReturn += "</tbody></table>";
                            }

                            swal({
                                title: "Results of save!",
                                type: type,
                                html: htmlReturn,
                                showCancelButton: true,
                                cancelButtonText: "No, I'm done",
                                confirmButtonText: "Add another limitation"
                            }).then((result) => {
                                if(!result.value) {
                                    // Close the form.
                                    holderModal.close();
                                }
                            });
                        });
                    });
                }
            });
        }
    });

    onLoadFunction(communitiesFiltered, $("#communityId").val(), $("#fischerSectionId").val(), $("#legalSectionId").val());
});

/*
    Name: getFischerSectionsByCommunityID
    @param: INT communityID
    Return: JSON Obj
*/
function getFischerSectionsByCommunityID(communityID) {
    return $.ajax({
        url: '/slimapi/v1/communities/' + communityID + '/fischerSections',
        type: "GET",
        success: function(data) {
            // Left success here for testing purposes
        },
        error: function(data) {
        	console.log("Error: 0002 - Failed to retrieve fischer sections by community id.");
        }
    });
}

/*
    Name: getLegalSectionsByCommunityID
    @param: INT communityID
    Return: JSON Obj
*/
function getLegalSectionsByCommunityID(communityID, filters = []) {
    return $.ajax({
        url: '/slimapi/v1/communities/' + communityID + '/legalSections',
        type: "GET",
        data: filters,
        success: function(data) {
            // Left success here for testing purposes
        },
        error: function(data) {
        	console.log("Error: 0003 - Failed to retrieve legal sections by community id.");
        }
    });
}

/*
	Name: renderLimitationsCommunitySelections
	@param: Obj - communityList
	Return: N/A
*/
function renderCommunitySelections(communityList, elementID) {
	$("#" + elementID).html('');
	$("#" + elementID).html('<option value="">Nothing Selected</option>');

	// Set the communities and have one selected.
    $.each(communityList, function(key, val) {
        var selected = "";
        if(!val.isDeleted) {
            $("#" + elementID).append("<option value='" + val.id + "'>" + val.code + " - " + val.name + "</option>");
        }
    });
}

/*
    Name: renderLimitationsFischerSectionSelections
    @param: Obj fischerSections
    @param: String ElementID - Element to set selections too.
    @param: INTEGER selectedID - ID if fischer sections is selected.
    Return: N/A
*/
function renderFischerSectionSelections(fischerSections, elementID, selectedID = null) {
    $("#" + elementID).html('');
    $("#" + elementID).html('<option value="">Nothing Selected</option>');

    if(fischerSections.CommunitySections !== undefined && fischerSections.CommunitySections !== null) {
        $.each(fischerSections.CommunitySections, function(key, val) {
            var isSelected = "";

            if(Number(val.SectionId) === Number(selectedID)) {
                isSelected = "selected";
            }

            $("#" + elementID).append('<option title="' + val.SectionName + '" value="' + val.SectionId + '" ' + isSelected + '>' + val.SectionName + ' Sites:' + val.Count + '</option>');
        });
    }
}

/*
    Name: renderLegalSectionSelections
    @param: Obj legalSections
    @param: String ElementID - Element to set selections too.
    @param: INTEGER selectedID - ID if legal sections is selected.
    Return: N/A
*/
function renderLegalSectionSelections(legalSections, elementID, selectedID = null) {
    $("#" + elementID).html('');
    $("#" + elementID).html('<option value="">Nothing Selected</option>');

    if(legalSections.rows !== "undefined" && legalSections.rows !== null) {
        $.each(legalSections.rows, function(key, val) {
            var isSelected = "";

            if(Number(val.SectionId) === Number(selectedID)) {
                isSelected = "selected";
            }

            $("#" + elementID).append('<option title="' + val.LegalSectionName + '" value="' + val.LegalSectionId + '" ' + isSelected + '>' + val.LegalSectionName + ' Sites:' + val.Count + '</option>');
        });
    }
}

/*
    Name: renderLegalSectionSelections
    @param: Obj legalSections
    @param: String ElementID - Element to set selections too.
    @param: INTEGER selectedID - ID if legal sections is selected.
    Return: N/A
*/
function renderSiteSectionSelections(siteSections, elementID) {
	$("#" + elementID).html('');

    if(siteSections.rows !== undefined && siteSections.rows !== null) {
        $.each(siteSections.rows, function(key, val) {
            var isSelected = "";

            $("#" + elementID).append('<option value="' + val.SiteId + '">' + val.SiteNumber + '</option>');
        });
    }
}

function onLoadFunction(communities, communityID = null, fischerSectionId = null, legalSectionID = null) {
    renderCommunitySelections(communities, "limitations-community");

    $(".selectpicker").selectpicker("refresh");

    if(communityID > 0) { 
        $("#limitations-community").val($("#communityId").val()).change();
        $("#limitations-community").prop('disabled', true);

        // Set the fischer sections after filter.
        if(fischerSectionId > 0) {
            $("#limitations-fischer").val($("#fischerSectionId").val());
            $("#limitations-fischer").prop('disabled', true);
        }

        // Set the legal sections.
        if(legalSectionID > 0) {
            $("#limitations-legal").val($("#legalSectionId").val());
            $("#limitations-legal").prop('disabled', true);
        }
    }

    $(".selectpicker").selectpicker("refresh");
}