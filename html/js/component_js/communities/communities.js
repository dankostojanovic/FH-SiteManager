/*
    Define variables
*/
var config;
var holderModal;
var numberSiteAttached = 0;
var communityId = 0,
    community,
    communities = [],
    communitiesPage = 1,
    communitiesPerPage = 5000,
    communitiesTotal = 1,
    communitiesTotalPages = 1,
    communitiesShowInactive = false,
    sortCommunitiesBy = 'name',
    sortCommunitiesAsc = 'asc',
    useCustomCommunityHot = false,
    customCommunityHotLoaded = false,
    communitiesFiltered = [],
    selectedModalCommunityId;
var specLevels = [],
    selectedSpecLevelId,
    specLevelsPage = 1,
    specLevelsPerPage = 50,
    specLevelsTotal = 1,
    specLevelsTotalPages = 1,
    sortSpecLevelsBy = 'name',
    sortSpecLevelsAsc = 'asc';
var divisionId = 0,
    divisions = [],
    divisionsPage = 1,
    divisionsPerPage = 50,
    divisionsTotal = 1,
    divisionsTotalPages = 1,
    sortDivisionsBy = 'name',
    sortDivisionsAsc = 'asc';
var companyId,
    companies = [],
    companiesPage = 1,
    companiesPerPage = 50,
    companiesTotal = 1,
    companiesTotalPages = 1,
    sortCompaniesBy = 'fullName',
    sortCompaniesAsc = 'asc';
var pricingGroupId,
    pricingGroups = [],
    pricingGroupsPage = 1,
    pricingGroupsPerPage = 50,
    pricingGroupsTotal = 1,
    pricingGroupsTotalPages = 1,
    sortPricingGroupsBy = 'PricingGroupId',
    sortPricingGroupsAsc = 'asc';
var attachedBuildingCatalogId,
    attachedBuildingCatalogs = [],
    attachedBuildingCatalogsPage = 1,
    attachedBuildingCatalogsPerPage = 1000,
    attachedBuildingCatalogsTotal = 1,
    attachedBuildingCatalogsTotalPages = 1,
    sortAttachedBuildingCatalogsBy = 'name',
    sortAttachedBuildingCatalogsAsc = 'asc',
    attachedBuildingCatalogBuildingTypes = [];;
var fischerSections = [],
    fischerSectionId,
    fischerSectionsPage = 1,
    fischerSectionsPerPage = 50,
    fischerSectionsTotal = 1,
    fischerSectionsTotalPages = 1,
    sortFischerSectionsBy = 'name',
    sortFischerSectionsAsc = 'asc',
    useCustomFischerSectionHot = false,
    customFischerSectionHotLoaded = false;
var communityFischerSections = [];
var legalSections = [],
    legalSectionId,
    legalSectionsPage = 1,
    legalSectionsPerPage = 50,
    legalSectionsTotal = 1,
    legalSectionsTotalPages = 1,
    sortLegalSectionsBy = 'name',
    sortLegalSectionsAsc = 'asc',
    useCustomLegalSectionHot = false,
    customLegalSectionHotLoaded = false
    legalSectionModalLegalSectionId = 0;
var communityLegalSections = [],
    legalSectionMortgages = []
    legalSectionMortgagesDelete = [];
var sites = [],
    sitesId,
    sitesPage = 1,
    sitesPerPage = 50,
    sitesTotal = 1,
    sitesTotalPages = 1,
    sortSitesBy = 'siteNumber',
    sortSitesAsc = 'asc',
    useCustomSiteHot = false,
    customSiteHotLoaded = false,
    filterSitesAvailable = -1,
    filterSitesPurchased = -1,
    communitySites = [];
var siteHolds = [],
    siteHold = {},
    siteHoldId,
    siteHoldsPage = 1,
    siteHoldsPerPage = 50,
    siteHoldsTotal = 1,
    siteHoldsTotalPages = 1,
    sortSiteHoldsBy = 'name',
    sortSiteHoldsAsc = 'asc',
    useCustomSiteHoldHot = false,
    customSiteHoldHotLoaded = false;
var siteHoldReasons = [],
    siteHoldReasonId,
    siteHoldReasonsPage = 1,
    siteHoldReasonsPerPage = 50,
    siteHoldReasonsTotal = 1,
    siteHoldReasonsTotalPages = 1,
    sortSiteHoldReasonsBy = 'description',
    sortSiteHoldReasonsAsc = 'asc';
var vendors = [],
    vendorsId,
    vendorsPage = 1,
    vendorsPerPage = 50,
    vendorsTotal = 1,
    vendorsTotalPages = 1,
    sortVendorsBy = 'vendorId',
    sortVendorsAsc = 'asc';
var activeTab = 'communities',
    showDeleted = 0,
    autosaveNotification,
    siteNumberValidator,
    jobNumberValidator;
var systemSync;
var error = {
        communityLoad: '0001',
        fischerSectionLoad: '0002',
        legalSectionLoad: '0003',
        siteLoad: '0004',
        siteHoldLoad: '0005',
        divisionLoad: '0006',
        specLevelLoad: '0007',
        communityLoadAll: '0008',
        legalSectionUpdate: '0009',
        legalSectionCreate: '0010',
        companyLoad: '0011',
        attachedBuildingCatalogLoad: '0012',
        siteHoldReasonLoad: '0013',
        vendorLoad: '0014',
        pricingGroupLoad: '0015',
        limitationsLoad: '0016',
        updateSite: '0017'
    };
var app2ApiMap = {
        community: {
            id: 'Id', code: 'Code', name: 'Name', divisionId: 'DivisionId', DivisionCode: 'Division',
            DivisionName: 'DivisionName', BudgetNeighborhoodId: 'BudgetNeighborhoodId',
            BudgetneighborhoodName: 'BudgetNeighborhoodName', isActiveLandManagement: 'IsActiveLandMgmt', isDeleted: 'IsDeleted'
        },
        fischerSection: {
            id: 'SectionId', name: 'SectionName',
            communityId: 'CommunityId', CommunityCode: 'CommunityCode', CommunityName: 'CommunityName',
            specLevelId: 'SpecLevelId', SpeclevelName: 'SpecLevelsSpecLevelDescr',
            isDeleted: 'IsDeleted'
        },
        legalSection: {
            id: 'LegalSectionId', name: 'LegalSectionName', CommunityCode: 'CommunityCode', communityId: 'CommunityId',
            CommunityCity: 'CommunityCity', CommunityCounty: 'CommunityCounty', CommunityCity1Name: 'CommunityCity1Name',
            CommunityCity2Name: 'CommunityCity2Name', CommunityCityOfClerk: 'CommunityCityOfClerk',
            sectionPhaseName: 'SectionPhaseName', platRecordingBook: 'PlatRecordingBook',
            platRecordingDate: 'PlatRecordingDate', recordedName: 'RecordedName', legalDescription: 'LegalDescription',
            covenantRestriction: 'CovenantRestriction', covenantRestriction2: 'CovenantRestriction2',
            covenantRestriction3: 'CovenantRestriction3', zoningJurisdiction: 'ZoningJurisdiction',
            zoningJurisdiction2: 'ZoningJurisdiction2', zoningClassification: 'ZoningClassification',
            zoningClassification2: 'ZoningClassification2', frontYardMinimum: 'FrontYardMinimum',
            frontYardMinimum2: 'FrontYardMinimum2', sideYardMinimum: 'SideYardMinimum', sideYardMinimum2: 'SideYardMinimum2',
            combinedSideMinimum: 'CombinedSideMinimum', combinedSideMinimum2: 'CombinedSideMinimum2',
            rearYardMinimum: 'RearYardMinimum', rearYardMinimum2: 'RearYardMinimum2',
            sideonCornerLotMin: 'SideonCornerLotMin', sideonCornerLotMin2: 'SideonCornerLotMin2',
            priorDeedReference: 'PriorDeedReference', comments: 'Comments', minLotSizeFrontEntry: 'MinLotSizeFrontEntry',
            minLotSizeSideEntry: 'MinLotSizeSideEntry', minLotSizeRearEntry: 'MinLotSizeRearEntry',
            tfgZoningJurisdictio: 'TfgZoningJurisdictio', tfgZoningClassificat: 'TfgZoningClassificat',
            tfgFrontYardMinimum: 'TfgFrontYardMinimum', tfgSideYardMinimum: 'TfgSideYardMinimum',
            tfgCombinedSideMinim: 'TfgCombinedSideMinim', tfgRearYardMinimum: 'TfgRearYardMinimum',
            tfgSideonCornerMinim: 'TfgSideonCornerMinim', widthAtSetback: 'WidthAtSetback',
            widthAtSetback2: 'WidthAtSetback2', tfgWidthAtSetback: 'TfgWidthAtSetback',
            sideNextToStreet1: 'SideNextToStreet1', sideNextToStreet2: 'SideNextToStreet2',
            tfgSideNextToStreet: 'TfgSideNextToStreet', minSqFtPerLot: 'MinSqFtPerLot', minSqFtRanch: 'MinSqFtRanch',
            minSqFt2Story: 'MinSqFt2Story', minSqFt2Story1Master: 'MinSqFt2Story1Master', baseSection: 'BaseSection',
            baseSectionOld: 'BaseSectionOld', minSqFtPerLot2: 'MinSqFtPerLot2', tfgZoningJuris2: 'TfgZoningJuris2',
            tfgZoningClass2: 'TfgZoningClass2', tfgFrontYardMin2: 'TfgFrontYardMin2', tfgSideYardMin2: 'TfgSideYardMin2',
            tfgCombinedSideMin2: 'TfgCombinedSideMin2', tfgRearYardMin2: 'TfgRearYardMin2',
            tfgSideOnCornerMin2: 'TfgSideOnCornerMin2', tfgWidthAtSetback2: 'TfgWidthAtSetback2', developer: 'Developer',
            engineer: 'Engineer', zone1SpecLevelId: 'Zone1SpecLevelId', zone2SpecLevelId: 'Zone2SpecLevelId',
            maxSqFtRanch: 'MaxSqFtRanch', ranchComments: 'RanchComments', twoStoryComments: 'TwoStoryComments',
            maxSqFt2Story: 'MaxSqFt2Story', isDeleted: 'IsDeleted', lastModifiedDate: 'LastModifiedDate',
            lastModifiedBy: 'LastModifiedBy', createdDate: 'CreatedDate', createdBy: 'CreatedBy'
        },
        site: {
            id: 'SiteId', communityId: 'CommunityId', fischerSectionId: 'SectionId', legalSectionId: 'LegalSectionId',
            siteNumber: 'SiteNumber', jobNumber: 'JobNumber', availableFlag: 'AvailableFlag',
            isAvailableFlagEditable: 'IsAvailableFlagEditable', gcJobNumber: 'GcJobNumber', purchaser: 'Purchaser',
            sitePremium: 'SitePremium', address1: 'Address1', address2: 'Address2', width: 'Width', depth: 'Depth',
            SpeclevelName: 'SpecLevelsSpecLevelDescr', specLevelId: 'SpecLevelId', setback: 'SetBack',
            garageLocation: 'GarageLocation', garageEntry: 'GarageEntry', buildableWidth: 'BuildableWidth',
            buildableDepth: 'BuildableDepth', rearExit: 'RearExit', basementDescription: 'BasementDescription',
            topOf: 'TopOf', tosOrF: 'TosOrF', aOrBCurb: 'AOrBCurb', aboveCurb: 'AboveCurb', sewerTap: 'SewerTap',
            waterTap: 'WaterTap', utilityEasement: 'UtilityEasement', sanitaryEasement: 'SanitaryEasement',
            stormEasement: 'StormEasement', waterEasement: 'WaterEasement', otherEasement: 'OtherEasement',
            sanitaryInvert: 'SanitaryInvert', transformers: 'Transformers', catchBasin: 'CatchBasin',
            sanitaryManhole: 'SanitaryManhole', stormManhole: 'StormManhole', fireHydrant: 'FireHydrant',
            secondaryPedestal: 'SecondaryPedestal', drainagePattern: 'DrainagePattern', sidewalks: 'Sidewalks',
            generalNotes: 'GeneralNotes', constructionNotes: 'ConstructionNotes', LegalSectionName: 'CommunitySectionLegalLegalSectionName',
            FischerSectionName: 'CommunitySectionSectionName', siteRating: 'SiteRating', siteNotes1: 'SiteNotes1',
            siteNotes2: 'SiteNotes2', siteCost: 'SiteCost', vendorId: 'VendorId', CommunityCode: 'CommunityCode',
            CommunityName: 'CommunityName', siteHoldId: 'HoldCode', estConstCost: 'EstConstCost', parcelId: 'ParcelId',
            pidnType: 'PidnType', lotNumber: 'LotNumber', legalLotNumber: 'LegalLotNumber', groupId: 'GroupId',
            buildingNumber: 'BuildingNumber', releasedForSale: 'ReleasedForSale', purchaseDate: 'PurchaseDate',
            checkNumber: 'CheckNumber', loptRecordId: 'LoptRecordId', fee1: 'Fee1', fee1AppSca: 'Fee1AppSca',
            fee2: 'Fee2', fee2AppSca: 'Fee2AppSca', fee3: 'Fee3', fee3AppSca: 'Fee3AppSca', fee4: 'Fee4',
            fee4AppSca: 'Fee4AppSca', isDeleted: 'IsDeleted', streetAddressRange: 'StreetAddrRange',
            fireSuppressRoomAddress: 'FireSuppressRoomAddr', walkOutPremium: 'WalkOutPremium',
            garagePremium: 'GaragePremium', viewPremium: 'ViewPremium', lowerLevel: 'LowerLevel', siteNotes: 'SiteNotes',
            credit: 'Credit', buildingType: 'BuildingType', unitNumber: 'UnitNumber', pricingGroupId: 'PricingGroupId',
            PricingGroupName: 'PricingGroupName'
        },
        siteHold: {
            id: 'SiteId', communityId: 'CommunityId', CommunityCode: 'CommunityCode', fischerSectionId: 'SectionId',
            legalSectionId: 'LegalSectionId', holdReasonId: 'HoldReasonId', SiteHoldReasonDescription: 'SiteHoldReasonDescription',
            siteNumber: 'SiteNumber', jobNumber: 'JobNumber', holdVendorId: 'HoldVendorId',
            holdDate: 'HoldDate', holdControlCode: 'HoldControlCode', holdDeposit: 'HoldDeposit', holdNotes: 'HoldNotes',
            holdCreatedBy: 'HoldCreatedBy', holdCreatedDate: 'HoldCreatedDate', holdLastModifiedBy: 'HoldLastModifiedBy',
            holdLastModifiedDate: 'HoldLastModifiedDate'
        },
        siteHoldReason: {
            id: 'SiteholdReasonId', description: 'Description', createdBy: 'CreatedBy',createdDate: 'createdDate',
            lastModifiedBy: 'LastModifiedBy', lastModifiedDate: 'lastModifiedDate'
        },
        division: {
            id: 'DivisionId', name: 'DivisionName', code: 'Division', RegionId: 'RegionId',
            recordId: 'RecordId', expensePercentage: 'ExpensePercentage', processingCode: 'ProcessingCode',
            active: 'Active'
        },
        specLevel: {
            id: 'SpecLevelId', name: 'SpecLevelDescr'
        },
        mortgage: {
            id: 'MortgageId', legalSectionId: 'LegalsectionId', info: 'Info', book: 'Book', page: 'Page',
            amount: 'Amount', date: 'Date', relDate: 'RelDate', isDeleted: 'IsDeleted'
        },
        attachedBuildingCatalog: {
            id: 'AttachedBuildingCatalogId', productType: 'ProductType', productCode: 'ProductCode',
            buildingType: 'BuildingType', buildingName: 'BuildingName', homeNumber: 'HomeNumber', planNumber: 'PlanNumber',
            planName: 'PlanName', lowerLevel: 'LowerLevel', garageEntry: 'GarageEntry', garageEntryAlt: 'GarageEntryAlt',
            rearExit: 'RearExit', floor: 'Floor'
        },
        company: {
            id: 'CompanyId', shortName: 'ShortName', fullName: 'FullName'
        },
        vendor: {
            id: 'VendorRecordId', vendorId: 'VendorId', name: 'Name'
        },
        siteMortgageAmmendment: {
            id: 'SiteMortgageAmmendmentId', siteId: 'siteId', ammendmentNumber: 'AmmendmentNumber', requestDate: 'RequestDate',
            receivedDate: 'ReceivedDate', recordedDate: 'RecordedDate', releaseDate: 'ReleaseDate', lender: 'Lender'
        },
        systemSync: {
            id: 'Id', isProcessed: 'IsProcessed', isErrored: 'IsErrored'
        },
        pricingGroup: {
            id: 'PricingGroupId', name: 'Name', description: 'Description'
        }
    };
var api2AppMap = {
        community: {},
        fischerSection: {},
        legalSection: {},
        site: {},
        siteHold: {},
        siteHoldReason: {},
        division: {},
        specLevel: {},
        mortgage: {},
        attachedBuildingCatalog: {},
        company: {},
        vendor: {},
        siteMortgageAmmendment: {},
        systemSync: {},
        pricingGroup: {}
    };
var $siteHoldContainer = $("#siteHoldContainer"),
    hotSiteHold;
var $siteContainer = $("#siteContainer"),
    hotSite;
var $legalSectionContainer = $("#legalSectionContainer"),
    hotLegalSection;
var $fischerSectionContainer = $("#fischerSectionContainer"),
    hotFischerSection;
var $communityContainer = $("#communityContainer"),
    hotCommunity;

// Initialization functions
$.each( app2ApiMap, function( entity, fields ) {
    for (var key in fields) {
        if (app2ApiMap[entity].hasOwnProperty(key)) {
            api2AppMap[entity][app2ApiMap[entity][key]] = key;
        }
    };
});

/*
    Name: renderLimitationsSelections
    @param: Obj limitationSections
    @param: String ElementID - Element to set selections too.
    @param: INTEGER selectedID - ID if legal sections is selected.
    Return: N/A
*/
function renderLimitationSectionSelections(limitationSections, elementID, selectedArray = []) {
    $("#" + elementID).html('');

    if(limitationSections.rows !== undefined && limitationSections.rows !== null) {
        $.each(limitationSections.rows, function(key, val) {
            $("#" + elementID).append('<option title="' + val.Code + '" value="' + val.LimitationId + '">' + val.Code + ' | ' + val.Name + '</option>');
            
            // If this id is in selectedArray prop selected.
            selectedArray.findIndex(function(e) {
                if(e.LimitationId == val.LimitationId) {
                    $("#" + elementID + " option[value='" + val.LimitationId + "']").attr('selected', true);
                }
            });
        });
    }
}

/*
    Name: getSiteLimitations
    @param: INT site_id
    Return: JSON Obj
*/
function getSiteLimitations(id, filters = []) {
    // Make ajax call to the api.
    return $.ajax({
        url: "/slimapi/v1/site/" + id + "/limitations",
        data: filters,
        success: function(data) {

        },
        error: function(data) {
            console.log("Error: 0016 - Failed to retrieve limitations.");
        }
    });
}

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

function setEnvironment() {
    // Api call.
    return $.ajax({
        type: "GET",
        url : 'slimapi/v1/configurations',
        dataType: "JSON",
        success: function(data) {
            config = data;
        }
    });
}

function getSyssyncQ() {
    // apiSortSystemSyncBy = app2ApiMap.systemSync[sortSystemSyncBy];
    data = {
        fields: {
            'SystemSync': {}
        },
        filter: {
            'SystemSync': {}
        },
        page: 1,
        per_page: 1
    };
    data.fields['SystemSync'] = 'Id,IsProcessed,IsErrored';
    data.filter['SystemSync'][app2ApiMap.systemSync.isProcessed] = {"operator": " = ", "value": 0};

    return $.ajax({
        url: 'slimapi/v1/systemSyncs',
        data: data
    })
        .done(function(data) {
            var msg;
            var queue = data.totalRecords;

            if(queue > 0) {
                if(queue === 1) {
                    msg = 'There is currently ' + queue + ' record waiting to be processed';
                } else {
                    msg = 'There are currently ' + queue + ' records waiting to be processed';
                }
                $('#syssync_icon').removeClass().addClass('fa fa-spinner fa-pulse fa-2x fa-fw text-primary').attr('title', msg);
                $('#syssync_num').text(queue);
            } else {
                $('#syssync_icon').removeClass().addClass('fa fa-check-circle-o fa-2x fa-fw text-success').attr('title', 'All records have been processed');
                $('#syssync_num').text('');
            }

            console.log("Loaded System Sync Queue");
        })
        .fail(function() {
            $('#syssync_icon').removeClass().addClass('fa fa-exclamation-circle fa-2x fa-fw text-danger').attr('title', 'There was an error checking for unprocessed records');
            $('#syssync_num').text('');

            console.log("Error loading System Sync Queue");
        });
}

function getCustomHots() {
    $.when(
        setEnvironment()
    ).done(function() {
        var environment = config['sitemanager-environment'];

        var defaultView = getCookie('defaultCommunitiesView');
        var permissions = getCookie('permissions');

        if(permissions.length > 1) {
            $('#toggleViewBtn').show();
        } else {
            $('#toggleViewBtn').hide();
        }

        $('#toggleViewBtn').val(defaultView[0]);

        if(defaultView[0] === "SiteDesign") {
            var environment = config['sitemanager-environment'];

            loadScript("/data/SiteManager/site_design_data.js", function() {
                if(environment === 'staging') {
                    $('#envLabel').show();
                }
            });
        }
        else if(defaultView[0] === "LandAdmin") {
            loadScript("/data/SiteManager/land_admin_data.js", function() {
                if(environment === 'staging') {
                    $('#envLabel').show();
                }
            });

            $("#addSiteLimitations").closest('div').hide();
            $("#addSite").closest('div').hide();
        }
    });
}

function api2App(apiEntity, entity) {
    appEntity = {};
    $.each( apiEntity, function( key, value ) {
        // Handle all keys we don't have mapped
        if (typeof api2AppMap[entity][key] != 'undefined') {
            appEntity[api2AppMap[entity][key]] = value;
        }
    });

    return appEntity;
}

function populateDivisionDropdown() {
    $('#divisionId').empty();
    $('#divisionId').append('<option value="0">Select Division</option>');
    if(typeof divisions !== 'undefined') {
        $.each(divisions, function(index, val) {
            $('#divisionId').append('<option value="'+val.id+'">' + val.code + ' - ' + val.name + '</option>');
        });
    }
    $('#divisionId').val(0);
}

function populateCommunityDropdown() {
    $('#communityId').empty();
    $('#communityId').append('<option value="0">Select Community</option>');
    if(typeof communitiesFiltered !== 'undefined') {
        $.each(communitiesFiltered, function(index, community) {
            selected = '';
            if(Number(community.id) === Number(communityId)) {
                selected = 'selected';
            }
            $('#communityId').append('<option value="'+community.id+'" ' + selected + '>'+community.code+' - '+community.name+'</option>');
        });
    }
}

/*
    TODO : Remove with the renderLimitationsFischerSectionSelections
    function the set_limitations_form.php just need to connect it
    to this function.
*/
function populateFischerSectionDropdown() {
    $('#fischerSectionId').empty();
    $('#fischerSectionId').append('<option value="0">Select Fischer Section</option>');
    if (communityId > 0) {
        $.ajax({
            url: '/slimapi/v1/communities/' + communityId + '/fischerSections',
            type: "GET",
        })
        .done(function(data) {
            if(typeof data.CommunitySections !== 'undefined') {
                $.each(data.CommunitySections, function(index, val) {
                    selected = '';
                    if(Number(val[app2ApiMap.fischerSection['id']]) === Number(fischerSectionId)) {
                        selected = 'selected';
                    }
                    $('#fischerSectionId').append('<option value="'+val[app2ApiMap.fischerSection['id']]+'" ' + selected + '>'+val[app2ApiMap.fischerSection['name']]+' Sites:'+val.Count+'</option>');
                });
            }
            else{
                console.log("error");
            }
        })
        .fail(function() {
            console.log("Error loading Fischer Section Options");
        });
    }
}

function populateLegalSectionDropdown() {
    $('#legalSectionId').empty();
    $('#legalSectionId').append('<option value="0">Select Legal Section</option>');
    if (communityId > 0) {
        $.ajax({
            url: '/slimapi/v1/communities/' + communityId + '/legalSections',
            type: "GET",
            data: {
                per_page : -1
            }
        })
        .done(function(data) {
            if(typeof data.rows !== 'undefined') {
                $.each(data.rows, function(index, val) {
                    selected = '';
                    if(Number(val[app2ApiMap.legalSection['id']]) === Number(legalSectionId)) {
                        selected = 'selected';
                    }
                    $('#legalSectionId').append('<option value="'+val[app2ApiMap.legalSection['id']]+'" ' + selected + '>'+val[app2ApiMap.legalSection['name']]+' Sites:'+val.Count+'</option>');
                });
            }
        })
        .fail(function() {
            console.log("Error loading Legal Section Options");
        });
    }
}

function populateSiteHoldReasonFilterDropdown() {
    $('#filterSiteHoldReason').empty();
    $('#filterSiteHoldReason').append('<option value="">Select Reason</option>');
    $.each(siteHoldReasons, function(index, val) {
        $('#filterSiteHoldReason').append('<option value="'+val.id+'" >'+val.description+'</option>');
    });
}

function getSpecLevels() {
    return $.ajax({
        url: '/slimapi/v1/specLevels',
    }).done(function(data) {
        specLevels.push({'id': null, 'name': 'N/A'});

        if(data.SpecLevelss.length > 0) {
            $.each(data.SpecLevelss, function (key, row) {
                specLevels.push( api2App(row, 'specLevel') );
            });
        }
    }).fail(function() {
        $.bigBox({
            title : "Error loading data (" + error.specLevelLoad + ")",
            content : "Please reload the page. If the problem persists please contact IS.",
            color : "#EA5939",
            icon : "fa fa-exclamation fadeInLeft animated"
        });
        window.ajaxEnabled = false;
        console.log("Error loading Spec Levels");
    });
}

function getPricingGroups() {
    apiSortPricingGroupsBy = app2ApiMap.pricingGroup[sortPricingGroupsBy];
    data = {
        filter: {},
        select: Object.keys(api2AppMap.pricingGroup),
        page: pricingGroupsPage,
        rows: pricingGroupsPerPage,
        orderBy: sortPricingGroupsBy,
        orderByAsc: sortPricingGroupsAsc,
    };
    if(pricingGroupId > 0) {
        data.filter[app2ApiMap.pricingGroup[id]] = {0:pricingGroupId, 1:" = "};
    }
    return $.ajax({
            url: '/slimapi/v1/pricingGroups',
            data: data
        })
        .done(function(data) {
            pricingGroupsTotal = data.totalRecords;
            pricingGroupsTotalPages = data.totalPages;
            pricingGroups = [];
            if (data.rows) {
                $.each(data.rows, function (key, row) {
                    pricingGroups.push( api2App(row, 'pricingGroup') );
                });
            }
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.pricingGroupLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            window.ajaxEnabled = false;
            console.log("Error loading Pricing Groups");
        });
}

function getDivisions() {
    return $.ajax({
        url: '/slimapi/v1/divisions'
    }).done(function(data) {
        divisions = [];

        divisionsTotal = data.Divisions.lenght;
        divisionsTotalPages = 1;

        if(data.Divisions.length > 0) {
            $.each(data.Divisions, function(key, row) {
                divisions.push( api2App(row, 'division') );
            }); 
        }
    }).fail(function() {
        $.bigBox({
            title : "Error loading data (" + error.divisionLoad + ")",
            content : "Please reload the page. If the problem persists please contact IS.",
            color : "#EA5939",
            icon : "fa fa-exclamation fadeInLeft animated"
        });
        window.ajaxEnabled = false;
        console.log("Error loading Divisons");
    });
}

function getCompanies() {
    apiSortCompaniesBy = app2ApiMap.company[sortCompaniesBy];
    data = {
        filter: {},
        select: Object.keys(api2AppMap.company),
        page: companiesPage,
        rows: companiesPerPage,
        orderBy: apiSortCompaniesBy,
        orderByAsc: sortCompaniesAsc,
    };
    if(companyId > 0) {
        data.filter[app2ApiMap.company[id]] = {0:companyId, 1:" = "};
    }
    return $.ajax({
            url: '/slimapi/v1/companies',
            data: data
        })
        .done(function(data) {
            companiesTotal = data.totalRecords;
            companiesTotalPages = data.totalPages;
            companies = [];
            if (data.rows) {
                $.each(data.rows, function (key, row) {
                    companies.push( api2App(row, 'company') );
                });
            }
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.companyLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            window.ajaxEnabled = false;
            console.log("Error loading Companies");
        });
}

function getAttachedBuildingCatalogs() {
    apiSortAttachedBuildingCatalogsBy = app2ApiMap.attachedBuildingCatalog[sortAttachedBuildingCatalogsBy];
    data = {
        filter: {},
        select: Object.keys(api2AppMap.attachedBuildingCatalog),
        page: attachedBuildingCatalogsPage,
        per_page: attachedBuildingCatalogsPerPage,
        orderBy: apiSortAttachedBuildingCatalogsBy,
        orderByAsc: sortAttachedBuildingCatalogsAsc,
    };
    if(attachedBuildingCatalogId > 0) {
        data.filter[app2ApiMap.attachedBuildingCatalog[id]] = {0:attachedBuildingCatalogId, 1:" = "};
    }
    return $.ajax({
            url: '/slimapi/v1/attachedBuildingCatalogs',
            data: data
        })
        .done(function(data) {
            attachedBuildingCatalogsTotal = data.totalRecords;
            attachedBuildingCatalogsTotalPages = data.totalPages;
            attachedBuildingCatalogs = [];
            if (data.rows) {
                attachedBuildingCatalogBuildingTypes = {};
                $.each(data.rows, function (key, row) {
                    attachedBuildingCatalogs.push( api2App(row, 'attachedBuildingCatalog') );
                    attachedBuildingCatalogBuildingTypes[row.BuildingType] = row.BuildingName;
                });
            }
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.attachedBuildingCatalogLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            window.ajaxEnabled = false;
            console.log("Error loading Attached Building Catalogs");
        });
}

function getSiteHoldReasons(include = '') {
    apiSortSiteHoldReasonsBy = app2ApiMap.siteHoldReason[sortSiteHoldReasonsBy];
    data = {
        fields: {
            'SiteHoldReason': {}
        },
        filter: {
            'SiteHoldReason': {}
        },
        include: include,
        orderBy: apiSortSiteHoldReasonsBy,
        orderByAsc: sortSiteHoldReasonsAsc,
        page: siteHoldReasonsPage,
        per_page: siteHoldReasonsPerPage,
    };
    data.fields['SiteHoldReason'] = 'SiteholdReasonId,Description';

    return $.ajax({
            url: '/slimapi/v1/siteHoldReasons',
            async: false,
            data: data
        })
        .done(function(data) {
            siteHoldReasonsTotal = data.totalRecords;
            siteHoldReasonsTotalPages = data.totalPages;
            siteHoldReasons = [];
            if (data.rows) {
                $.each(data.rows, function (key, row) {
                    siteHoldReasons.push( api2App(row, 'siteHoldReason') );
                });
            }
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.siteHoldReasonLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            console.log("Error loading Site Hold Reasons");
        });
}

function getVendors(include = '') {
    apiSortVendorsBy = app2ApiMap.vendor[sortVendorsBy];
    data = {
        fields: {
            'Vendor' : {}
        },
        filter: {
            'Vendor' : {}
        },
        include: include,
        orderBy: apiSortVendorsBy,
        orderByAsc: sortVendorsAsc,
        page: vendorsPage,
        per_page: vendorsPerPage,
    };

    data.fields['Vendor'] = Object.keys(api2AppMap.vendor).join();
    if($('.filterVendorId').val()){
        data.filter['Vendor'][app2ApiMap.vendor.vendorId] = {"value":$('.filterVendorId').val(), "operator":" rlike "};
    }

    return $.ajax({
            url: '/slimapi/v1/vendors',
            async: false,
            data: data
        })
        .done(function(data) {
            vendorsTotal = data.totalRecords;
            vendorsTotalPages = data.totalPages;
            vendors = [];
            if (data.rows) {
                $.each(data.rows, function (key, row) {
                    vendors.push( api2App(row, 'vendor') );
                });
            }
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.vendorLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            console.log("Error loading Vendors");
        });
}

function getSiteHolds(include = '') {
    apiSortSiteHoldsBy = app2ApiMap.siteHold[sortSiteHoldsBy];
    data = {
        fields: {
            'SiteHold' : {},
            'SiteHoldReason': {},
            'Community': {}
        },
        filter: {
            'SiteHold' : {},
            'SiteHoldReason': {},
            'Community': {}
        },
        include: include,
        orderBy: apiSortSiteHoldsBy,
        orderByAsc: sortSiteHoldsAsc,
        page: siteHoldsPage,
        per_page: siteHoldsPerPage,
    };
    data.fields['SiteHoldReason'] = 'Description';
    data.fields['Community'] = 'Code';
    data.fields['SiteHold'] = Object.keys(api2AppMap.siteHold).join();

    if ($('#filterSiteHoldSiteNumber').val()){
        data.filter['SiteHold'][app2ApiMap.siteHold.siteNumber] = {"value":$('#filterSiteHoldSiteNumber').val(), "operator":" rlike "};
    }
    if ($('#filterSiteHoldReason').val()) {
        data.filter['SiteHold'][app2ApiMap.siteHold.holdReasonId] = {"value":$('#filterSiteHoldReason').val(), "operator":" = "};
    }
    if (communityId > 0) {
        data.filter['SiteHold'][app2ApiMap.siteHold.communityId] = {"value":communityId, "operator":" = "};
    }
    if (fischerSectionId > 0) {
        data.filter['SiteHold'][app2ApiMap.siteHold.fischerSectionId] = {"value":fischerSectionId, "operator":" = "};
    }
    if (legalSectionId > 0) {
        data.filter['SiteHold'][app2ApiMap.siteHold.legalSectionId] = {"value":legalSectionId, "operator":" = "};
    }
    if (!communitiesShowInactive) {
        data.filter['Community']['IsActiveLandMgmt'] = {"operator": " = ", "value": 1};
    }
    return $.ajax({
            url: '/slimapi/v1/siteHolds',
            async: false,
            data: data
        })
        .done(function(data) {
            siteHoldsTotal = data.totalRecords;
            siteHoldsTotalPages = data.totalPages;
            siteHolds = [];
            if (data.rows) {
                $.each(data.rows, function (key, row) {
                    siteHolds.push( api2App(row, 'siteHold') );
                    // Add a row for actions
                    siteHolds[key].action = "EDIT";
                });
            }
            $('#siteHoldPagination').bootpag({total: siteHoldsTotalPages});
            $('#siteHoldTotalRecords').text("Total: " + data.totalRecords);
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.siteHoldLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            console.log("Error loading Site Holds");
        });
}

function getCommunities(include = '') {
    // FIXME find out max records and update perPage
//        communitiesPerPage = data.totalRecords;
    apiSortCommunitiesBy = app2ApiMap.community[sortCommunitiesBy];
    data = {
        fields: Object.keys(api2AppMap.community).join(),
        filter: {},
        include: include,//'Division,BudgetNeighborhood,FischerSection,LegalSection',
        orderBy: [apiSortCommunitiesBy, sortCommunitiesAsc],
        page: communitiesPage,
        per_page: communitiesPerPage
    };
    return $.ajax({
            url: '/slimapi/v1/communities',
            data: data
        })
        .done(function(data) {
            communitiesTotal = data.totalRecords;
            communitiesTotalPages = data.totalPages;
            communities = [];
            if (data.rows) {
                $.each(data.rows, function (key, row) {
                    arrData = api2App(row, 'community');
                    $.each(row, function (key1, row1) {
                        if (typeof row1 === 'object') {
                            arrData[key1] = row1;
                        }
                    });
                    communities.push( arrData );
                });
            }
            $('#communityPagination').bootpag({total: communitiesTotalPages});
            $('#communityTotalRecords').text("Total: " + data.totalRecords);
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.communityLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            window.ajaxEnabled = false;
            console.log("Error loading Communities");
        });
}
// TODO can refactor to have one filter function for all filters on all entities
function removeInactive(community) {
    return community.isActiveLandManagement !== communitiesShowInactive;
}
function filterCommunityByName(community) {
    if (community.name.toLowerCase().indexOf($('#filterCommunityName').val().toLowerCase()) > -1) {
        return true;
    }
}
function filterCommunityByCode(community) {
    if (community.code.toLowerCase().indexOf($('#filterCommunityCode').val().toLowerCase()) > -1) {
        return true;
    }
}
function filterCommunityByDivisionId(community) {
    if (divisionId == 0 || community.divisionId == divisionId) {
        return true;
    }
}
function filterCommunityById(community) {
    if (communityId == 0 || community.id == communityId) {
        return true;
    }
}
function getCommunitiesFiltered() {
    communitiesFiltered = JSON.parse(JSON.stringify(communities));
    communitiesFiltered = communitiesFiltered.filter(removeInactive);
    communitiesFiltered = communitiesFiltered.filter(filterCommunityByName);
    communitiesFiltered = communitiesFiltered.filter(filterCommunityByCode);
    communitiesFiltered = communitiesFiltered.filter(filterCommunityByDivisionId);
    communitiesFiltered = communitiesFiltered.filter(filterCommunityById);
}

function getCommunity(id, include = '') {
    if (!(id > 0)) return $.Deferred().reject();
    community = {};
    data = {
        include: include
    };
    return $.ajax({
        url: '/slimapi/v1/communities/' + id,
        type: 'GET',
        contentType: "application/json; charset=utf-8",
        data: data
    })
    .done(function(data) {
        communityFischerSections = [];
        communityLegalSections = [];
        communitySites = [];
        $.each(data, function (key, value) {
            community[api2AppMap.community[key]] = value;
            if (key == 'CommunitySections') {
                $.each(value, function (key1, row) {
                    communityFischerSections.push( api2App(row, 'fischerSection') );
                })
            } else if (key == 'CommunitySectionLegals') {
                $.each(value, function (key1, row) {
                    communityLegalSections.push( api2App(row, 'legalSection') );
                })
            } else if (key == 'CommunitySites') {
                $.each(value, function (key1, row) {
                    communitySites.push( api2App(row, 'site') );
                })
            }
        });
    })
    .fail(function(community) {
        console.log('Community ' + id + ' load failed.');
        return false;
    });
}

function getFischerSections(include = '') {
    apiSortFischerSectionsBy = app2ApiMap.fischerSection[sortFischerSectionsBy];
    data = {
        fields: {
            'FischerSection': {},
            'Community': {}
        },
        filter: {
            'FischerSection': {},
            'Community': {}
        },
        include: include,
        orderBy: apiSortFischerSectionsBy,
        orderByAsc: sortFischerSectionsAsc,
        page: fischerSectionsPage,
        per_page: fischerSectionsPerPage
    };
    data.fields['FischerSection'] = 'SectionId,SectionName,CommunityId,SpecLevelId';
    data.fields['Community'] = 'Code,Name';
    data.fields['SpecLevels'] = 'SpecLevelDescr';
    // FIXME use js variables instead of dom
    data.filter['FischerSection'][app2ApiMap.fischerSection.isDeleted] = {"operator": " = ", "value": 0};
    if($('#filterFischerSectionName').val()){
        data.filter['FischerSection'][app2ApiMap.fischerSection.name] = {"operator":" rlike ", "value":$('#filterFischerSectionName').val()};
    }
    if(communityId > 0) {
        data.filter['FischerSection'].CommunityId = {"operator":" = ", "value": communityId};
    }
    if(fischerSectionId > 0) {
        data.filter['FischerSection'][app2ApiMap.fischerSection.id] = {"operator": " = ", "value":fischerSectionId};
    }

    if (!communitiesShowInactive) {
        data.filter['Community']['IsActiveLandMgmt'] = {"operator": " = ", "value": 1};
    }
    return $.ajax({
            url: '/slimapi/v1/fischerSections',
            data: data
        })
        .done(function(data) {
            fischerSectionsTotal = data.totalRecords;
            fischerSectionsTotalPages = data.totalPages;
            fischerSections = [];
            if (data.rows) {
                $.each(data.rows, function (key, row) {
                    arrData = api2App(row, 'fischerSection');
                    $.each(row, function (key1, row1) {
                        if (typeof row1 === 'object') {
                            arrData[key1] = row1;
                        }
                    });
                    fischerSections.push( arrData );
                    fischerSections[key].action = "EDIT";
                });
            }
            $('#fischerSectionPagination').bootpag({total: fischerSectionsTotalPages});
            $('#fischerSectionTotalRecords').text("Total: " + data.totalRecords);
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.fischerSectionLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            window.ajaxEnabled = false;
            console.log("Error loading Community Fischer Sections");
        });
}

function getLegalSections(include = '') {
    apiSortLegalSectionsBy = app2ApiMap.legalSection[sortLegalSectionsBy];
    data = {
        fields: {
            'LegalSection': {},
            'Community': {}
        },
        filter: {
            'LegalSection': {},
            'Community': {}
        },
        include: include,
        orderBy: apiSortLegalSectionsBy,
        orderByAsc: sortLegalSectionsAsc,
        page: legalSectionsPage,
        per_page: legalSectionsPerPage
    };
    data.fields['LegalSection'] = Object.keys(api2AppMap.legalSection).join();
    data.fields['Community'] = 'Code,Name';
    data.filter['LegalSection'][app2ApiMap.legalSection.isDeleted]  = {0:0, 1:" = "};
    // FIXME use js variables instead of dom
    if($('#filterLegalSectionCode').val()){
        data.filter['LegalSection'][app2ApiMap.legalSection.name] = {0:$('#filterLegalSectionCode').val(), 1:" rlike "};
    }
    if($('#filterLegalSectionRecordedName').val()){
        data.filter['LegalSection'][app2ApiMap.legalSection.recordedName] = {0:$('#filterLegalSectionRecordedName').val(), 1:" rlike "};
    }
    if($('#filterLegalSectionPlatRecordingDate').val()){
        data.filter['LegalSection'][app2ApiMap.legalSection.platRecordingDate] = {0:$('#filterLegalSectionPlatRecordingDate').val(), 1:" rlike "};
    }
    if(communityId > 0) {
        data.filter['LegalSection'][app2ApiMap.legalSection.communityId] = {0:communityId, 1:" = "};
    }
    if(legalSectionId > 0) {
        data.filter['LegalSection'][app2ApiMap.legalSection.id] = {0:legalSectionId, 1:" = "};
    }
    if (!communitiesShowInactive) {
        data.filter['Community']['IsActiveLandMgmt'] = {"operator": "=", "value": 1};
    }
    return $.ajax({
            url: '/slimapi/v1/legalSections',
            data: data
        })
        .done(function(data) {
            legalSectionsTotal = data.totalRecords;
            legalSectionsTotalPages = data.totalPages;
            legalSections = [];
            if (data.rows) {
                $.each(data.rows, function (key, row) {
                    arrData = api2App(row, 'legalSection');
                    $.each(row, function (key1, row1) {
                        if (typeof row1 === 'object') {
                            arrData[key1] = row1;
                        }
                    });
                    legalSections.push( arrData );
                    legalSections[key].action = "EDIT";
                });
            }
            $('#legalSectionPagination').bootpag({total: legalSectionsTotalPages});
            $('#legalSectionTotalRecords').text("Total: " + data.totalRecords);
        })
        .fail(function() {
            $.bigBox({
                title : "Error loading data (" + error.legalSectionLoad + ")",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            window.ajaxEnabled = false;
            console.log("Error loading Community Legal Sections");
        });
}

/*
    Name: getSites
    @param: Array filters
    Description: Takes in an array of filters,
    passes them to an api call and returns the
    results in json.
*/
function getSites(filters) {
    return $.ajax({
        url: '/slimapi/v1/sites',
        async: false,
        data: filters,
        error: function(data) {
            $.bigBox({
                title : "Error fetching sites data",
                content : "Please reload the page. If the problem persists please contact IS.",
                color : "#EA5939",
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            console.log("Error: 0004 - Failed to retrieve sites by filter. " + data);
        }
    });
}

function getFischerSection(id) {
    if (!(id > 0)) return $.Deferred().reject();
    fischerSection = {};
    data = {
        include: 'Community,SpecLevel'
    };
    return $.ajax({
        url: '/slimapi/v1/fischerSections/' + id,
        type: 'GET',
        contentType: "application/json; charset=utf-8",
        data: data
    })
    .done(function(data) {
        $.each(data, function (key, value) {
            fischerSection[api2AppMap.fischerSection[key]] = value;
        });
    })
    .fail(function(fischerSection) {
        console.log('Fischer Section ' + id + ' load failed.');
        return false;
    });
}

function updateFischerSection(fischerSection) {
    let jsonData = [];
    let jsonRow = {};
    $.each(fischerSection, function(key, value) {
        jsonRow[app2ApiMap.fischerSection[key]] = value;
    });
    delete jsonRow['undefined'];
    jsonData.push(jsonRow);

    $.ajax({
        url: '/slimapi/v1/fischerSections',
        type: 'PATCH',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function() {
        return fischerSection;
    })
    .fail(function() {
        console.log('Fischer Section ' + fischerSection['id'] + ' update failed.');
        return false;
    });
}

function createFischerSection(fischerSection) {
    let jsonData = [];
    let jsonRow = {};
    let communityId = fischerSection.communityId;
    $.each(fischerSection, function(key, value) {
        jsonRow[app2ApiMap.fischerSection[key]] = value;
    });
    delete jsonRow['undefined'];
    jsonData.push(jsonRow);

    $.ajax({
        url: '/slimapi/v1/communities/' + communityId + '/fischerSections',
        type: 'POST',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function(data) {
        return fischerSection;
    })
    .fail(function() {
        console.log('Fischer Section ' + fischerSection['id'] + ' create failed.');
        return false;
    });
}

function getLegalSection(id, include = '') {
    if (!(id > 0)) return $.Deferred().reject();
    legalSection = {};
    legalSectionMortgages = [];
    data = {
        include: include
    };
    return $.ajax({
        url: '/slimapi/v1/legalSections/' + id,
        type: 'GET',
        contentType: "application/json; charset=utf-8",
        data: data
    })
    .done(function(data) {
        $.each(data, function (key, value) {
            legalSection[api2AppMap.legalSection[key]] = value;
            if (key == 'Mortgages') {
                $.each(value, function (key1, row) {
                    legalSectionMortgages.push( api2App(row, 'mortgage') );
                })
            }
        });
    })
    .fail(function(legalSection) {
        console.log('Legal Section ' + id + ' load failed.');
        return false;
    });
}

function updateLegalSection(legalSection) {
    let jsonData = [];
    let jsonRow = {};
    $.each(legalSection, function(key, value) {
        jsonRow[app2ApiMap.legalSection[key]] = value;
    });
    delete jsonRow['undefined'];
    jsonData.push(jsonRow);

    $.ajax({
        url: '/slimapi/v1/legalSections',
        type: 'PATCH',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function() {
        return legalSection;
    })
    .fail(function() {
        console.log('Legal Section ' + legalSection['id'] + ' update failed.');
        return false;
    });
}

function createLegalSection(legalSection) {
    let jsonData = [];
    let jsonRow = {};
    let communityId = legalSection.communityId;
    $.each(legalSection, function(key, value) {
        jsonRow[app2ApiMap.legalSection[key]] = value;
    });
    delete jsonRow['undefined'];
    jsonData.push(jsonRow);

    $.ajax({
        url: '/slimapi/v1/communities/' + communityId + '/legalSections',
        type: 'POST',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function(data) {
        return legalSection;
    })
    .fail(function() {
        console.log('Legal Section ' + legalSection['id'] + ' create failed.');
        return false;
    });
}

function updateLegalSectionMortgage(mortgages) {
    jsonData = {};
    i = 0;
    $.each(mortgages, function(key1, mortgage) {
        i++;
        jsonData[i] = {};
        $.each(mortgage, function(key, value) {
            if (key == 'legalSectionId') {
                legalSectionId = value;
            }
            jsonData[i][app2ApiMap.mortgage[key]] = value;
        });
    });

    $.ajax({
        url: '/slimapi/v1/mortgages',
        type: 'PATCH',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function() {
        return legalSection;
    })
    .fail(function() {
        console.log('Mortgages for ' + legalSection['id'] + ' update failed.');
        return false;
    });
}

function createLegalSectionMortgage(mortgages) {
    jsonData = {};
    i = 0;
    $.each(mortgages, function(key1, mortgage) {
        i++;
        jsonData[i] = {};
        $.each(mortgage, function(key, value) {
            if (key == 'legalSectionId') {
                legalSectionId = value;
            }
            jsonData[i][app2ApiMap.mortgage[key]] = value;
        });
    });
    // If there are no mortgages to create, no ajax request are needed
    if (i == 0) return $.Deferred().reject();
    $.ajax({
        url: '/slimapi/v1/legalSections/' + legalSectionId + '/mortgages',
        type: 'POST',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function(data) {
        return data;
    })
    .fail(function() {
        console.log('Mortgage create failed.');
        return false;
    });
}

function deleteLegalSectionMortgage(mortgageIds){
    $.each(mortgageIds, function(key, value) {
        $.ajax({
            url: '/slimapi/v1/mortgages/' + value,
            type: 'DELETE',
            dataType: 'json'
        })
        .fail(function() {
            console.log('Mortgage delete failed.');
        });
    });
}

function getSite(id, include = '') {
    if (!(id > 0)) return $.Deferred().reject();
    site = {};
    siteMortgageAmmendments = [];
    data = {
        include: 'Community,FischerSection,LegalSection,SpecLevel,SiteMortgageAmmendments'
    };
    return $.ajax({
        url: '/slimapi/v1/sites/' + id,
        type: 'GET',
        data: data,
        contentType: "application/json; charset=utf-8",
    })
    .done(function(data) {
        $.each(data, function (key, value) {
            site[api2AppMap.site[key]] = value;
            if (key == 'SiteMortgageAmmendments') {
                $.each(value, function (key1, row) {
                    siteMortgageAmmendments.push( api2App(row, 'siteMortgageAmmendment') );
                })
            }
        });
    })
    .fail(function(site) {
        console.log('Site ' + id + ' load failed.');
        return false;
    });
}

function createSites(sites) {
    jsonData = {};
    i = 0;
    $.each(sites, function(key1, site) {
        i++;
        jsonData[i] = {};
        $.each(site, function(key, value) {
            if (key == 'communityId') {
                communityId = value;
            }
            jsonData[i][app2ApiMap.site[key]] = value;
        });
    });
    $.ajax({
        url: '/slimapi/v1/communities/' + communityId + '/sites',
        type: 'POST',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function(data) {
        return data;
    })
    .fail(function(request, status, error) {

        $.bigBox({
            title : "Create Site failed",
            content : request.responseText,
            color : "#EA5939",
            timeout: 8000,
            icon : "fa fa-exclamation fadeInLeft animated"
        });

        console.log('Site create failed.');
        return false;
    });
}

function getSiteHold(id) {
    if (!(id > 0)) return $.Deferred().reject();
    siteHold = {};
    data = {
        include: 'Community,SiteHoldReason'
    };
    return $.ajax({
        url: '/slimapi/v1/siteHolds/' + id,
        type: 'GET',
        contentType: "application/json; charset=utf-8",
        data: data
    })
    .done(function(data) {
        $.each(data, function (key, value) {
            siteHold[api2AppMap.siteHold[key]] = value;
        });
    })
    .fail(function(siteHold) {
        console.log('Site Hold ' + id + ' load failed.');
        return false;
    });
}

function updateSiteHold(siteHold) {
    let jsonData = [];
    let jsonRow = {};
    $.each(siteHold, function(key, value) {
        jsonRow[app2ApiMap.siteHold[key]] = value;
    });
    delete jsonRow['undefined'];
    jsonData.push(jsonRow);

    $.ajax({
        url: '/slimapi/v1/siteHolds',
        type: 'PATCH',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function() {
        return siteHold;
    })
    .fail(function() {
        console.log('Site Hold ' + siteHold['id'] + ' update failed.');
        return false;
    });
}

function createSiteHold(siteHold) {
    console.log(siteHold)
    let jsonData = [];
    let jsonRow = {};
    let siteId = siteHold.id;
    $.each(siteHold, function(key, value) {
        jsonRow[app2ApiMap.siteHold[key]] = value;
    });
    delete jsonRow['undefined'];
    jsonData.push(jsonRow);

    $.ajax({
        url: '/slimapi/v1/sites/' + siteId + '/siteHolds',
        type: 'POST',
        processData: false,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify(jsonData),
    })
    .done(function(data) {
        return siteHold;
    })
    .fail(function() {
        console.log(' Site Hold ' + siteHold['id'] + ' create failed.');
        return false;
    });
}

function resetFilters() {
    $('.filterCommunities').val('');
    $('.filterFischerSections').val('');
    $('.filterLegalSections').val('');
    $('.filterSites').val('');
    $('.filterSiteHolds').val('');
    $('#filterSiteAvailableDisabled').click();
    $('#filterSitePurchasedDisabled').click();
    $('#chCommunitiesShowInactive').attr('checked', false);
    $('#chFischerSectionSave').attr('checked', false);
    $('#chLegalSectionSave').attr('checked', false);
    $('#chSiteSave').attr('checked', false);
    $('#chSiteHoldSave').attr('checked', false);
};

function resetDropdowns() {
    communitiesShowInactive = false;
    divisionId = 0;
    communityId = 0;
    fischerSectionId = 0;
    legalSectionId = 0;
    getCommunitiesFiltered();
    populateDivisionDropdown(),
    populateCommunityDropdown();
    populateFischerSectionDropdown();
    populateLegalSectionDropdown();
};

function resetSorts() {
    sortCommunitiesBy = 'name';
    sortCommunitiesAsc = 'asc';
    sortFischerSectionsBy = 'name';
    sortFischerSectionsAsc = 'asc';
    sortLegalSectionsBy = 'name';
    sortLegalSectionsAsc = 'asc';
    sortSitesBy = 'siteNumber';
    sortSitesAsc = 'asc';
    sortSiteHoldsBy = 'name',
    sortSiteHoldsAsc = 'asc';
}

siteNumberValidator = function (value, callback) {
    var pattern = /^[A-Z0-9]{3}\d{5}[A-Z0-9]{4}$/;
    setTimeout(function(){
        if(pattern.test(value)) {
            callback(true);
        } else {
            $.bigBox({
                title : "Invalid Site Number format",
                content : "Site Number must start with 3 capital case letters (or digits) followed by 9 digits.",
                color : "#EA5939",
                timeout: 8000,
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            callback(false);
        }
    }, 1000);
};

jobNumberValidator = function (value, callback) {
    var pattern = /^[A-Z0-9]{3}\d{5}[A-Z0-9]{4}$/;
    setTimeout(function(){
        if(value.match(pattern)) {
            callback(true);
        } else {
            $.bigBox({
                title : "Invalid Job Number format",
                content : "Job Number must start with 3 capital case letters (or digits) followed by 9 digits.",
                color : "#EA5939",
                timeout: 8000,
                icon : "fa fa-exclamation fadeInLeft animated"
            });
            callback(false);

        }
    }, 1000);
};

$('#divisionId').change(function() {
    divisionId = this.value;
    communityId = 0;
    fischerSectionId = 0;
    legalSectionId = 0;
    getCommunitiesFiltered();
    $.when(
        populateCommunityDropdown(),
        populateFischerSectionDropdown(),
        populateLegalSectionDropdown()
    ).then( function() {
        reloadActiveTab();
    });
});

$('#communityId').change(function() {
    communityId = this.value;
    fischerSectionId = 0;
    legalSectionId = 0;
    $.when(
        populateCommunityDropdown(),
        populateFischerSectionDropdown(),
        populateLegalSectionDropdown(),
    ).then(function() {
        reloadActiveTab();
    });
});

$('#chCommunitiesShowInactive').change(function(e) {
    if (this.checked) {
        communitiesShowInactive = true;
    } else {
        communitiesShowInactive = false;
    }
    getCommunitiesFiltered();
    resetCommunitiesPagination();
    reloadActiveTab();
});

$('#chSiteSave').change(function() {
    if (isNaN(communityId) || !(communityId > 0)) {
        $.bigBox({
            title : "Missing Community",
            content : "Please select Community before updating or adding data.",
            color : "#EA5939",
            timeout: 8000,
            icon : "fa fa-exclamation fadeInLeft animated"
        });
        this.checked = false;
        return;
    }
//        reloadActiveTab();
});

$('#fischerSectionId').change(function() {
    fischerSectionId = this.value;
    reloadActiveTab();
});

$('#legalSectionId').change(function() {
    legalSectionId = this.value;
    reloadActiveTab();
});

$('#communitiesTab').click(function() {
    activeTab = 'communities';
    reloadActiveTab();
});

$('#fischerSectionsTab').click(function() {
    activeTab = 'fischerSections';
    reloadActiveTab();
});

$('#legalSectionsTab').click(function() {
    activeTab = 'legalSections';
    reloadActiveTab();
});

$('#sitesTab').click(function() {
    activeTab = 'sites';
    reloadActiveTab();
});

$('#siteHoldsTab').click(function() {
    activeTab = 'siteHolds';
    reloadActiveTab();
});

$('#btnResetFilters').on('click', function(event) {
    event.preventDefault();
    resetFilters();
    resetDropdowns();
    resetCommunitiesPagination();
    resetFischerSectionsPagination();
    resetLegalSectionsPagination();
    resetSitesPagination();
    resetSiteHoldsPagination();
    resetSorts();
});

function reloadActiveTab() {
    if (activeTab === 'communities') {
        getCommunitiesFiltered();
        createCommunityHot();
        populateCommunityDropdown();
    } else if (activeTab === 'fischerSections') {
        $.when(
            refreshFischerSections = getFischerSections('Community,SpecLevels')
        ).then(function() {
            if(refreshFischerSections.readyState === 4) {
                createFischerSectionHot();
                populateFischerSectionDropdown();
            } else {
                hotFischerSection.clear();
            }
        });
    } else if (activeTab === 'legalSections') {
        $.when(
            refreshLegalSections = getLegalSections('Community')
        ).then(function() {
            if(refreshLegalSections.readyState === 4) {
                createLegalSectionHot();
                populateLegalSectionDropdown();
            } else {
                hotLegalSection.clear();
            }
        });
    } else if (activeTab === 'sites') {
        // Create filters here.
        var sitesFilter = sitesBasicFilter;

        sitesFilter.fields['FischerSection'] = 'SectionName';
        sitesFilter.fields['LegalSection'] = 'LegalSectionName';
        sitesFilter.fields['Community'] = 'Code,Name';
        sitesFilter.fields['SpecLevel'] = 'SpecLevelDescr';
        sitesFilter.filters['Site'][app2ApiMap.site.isDeleted] = {"operator": " = ", "value": 0};
        ///sitesFilter.pagination['OrderBy']['orderBy'] = app2ApiMap.site[sortSitesBy];

        if(filterSitesAvailable === 1){
            sitesFilter.filters['Site'][app2ApiMap.site.availableFlag] = {"value":1, "operator":" rlike "};
        } else if (filterSitesAvailable === 0) {
            sitesFilter.filters['Site'][app2ApiMap.site.availableFlag] = {"value":0, "operator":" rlike "};
        } else {
            delete sitesFilter.filters['Site'][app2ApiMap.site.availableFlag];
        }
        
        if(filterSitesPurchased === 1){
            sitesFilter.filters['Site'][app2ApiMap.site.purchaseDate] = {"value":1, "operator":" > "};
        } else if (filterSitesPurchased === 0) {
            sitesFilter.filters['Site'][app2ApiMap.site.purchaseDate] = {"value":"", "operator":" ISNULL "};
        } else{
            delete sitesFilter.filters['Site'][app2ApiMap.site.purchaseDate];
        }

        if($('#filterSiteSiteNumber').val()){
            sitesFilter.filters['Site'][app2ApiMap.site.siteNumber] = {"value":$('#filterSiteSiteNumber').val(), "operator":" rlike "};
        } else {
            delete sitesFilter.filters['Site'][app2ApiMap.site.siteNumber];
        }

        if($('#filterSiteJobNumber').val()){
            sitesFilter.filters['Site'][app2ApiMap.site.jobNumber] = {"value":$('#filterSiteJobNumber').val(), "operator":" rlike "};
        } else {
            delete sitesFilter.filters['Site'][app2ApiMap.site.jobNumber];
        }

        if(communityId > 0) {
            sitesFilter.filters['Site'][app2ApiMap.site.communityId] = {"value":communityId, "operator":" = "};
        } else {
            delete sitesFilter.filters['Site'][app2ApiMap.site.communityId];
            delete sitesFilter.filters['Site'][app2ApiMap.site.fischerSectionId];
            delete sitesFilter.filters['Site'][app2ApiMap.site.legalSectionId];
        }

        if(fischerSectionId > 0) {
            sitesFilter.filters['Site'][app2ApiMap.site.fischerSectionId] = {"value":fischerSectionId, "operator":" = "};
        } else {
            delete sitesFilter.filters['Site'][app2ApiMap.site.fischerSectionId];
        }

        if(legalSectionId > 0) {
            sitesFilter.filters['Site'][app2ApiMap.site.legalSectionId] = {"value":legalSectionId, "operator":" = "};
        } else {
            delete sitesFilter.filters['Site'][app2ApiMap.site.legalSectionId];
        }

        if (!communitiesShowInactive) {
            sitesFilter.filters['Community']['IsActiveLandMgmt'] = {"operator": " = ", "value": 1};
        }

        sitesFilter.pagination['OrderByAsc'] = sortSitesAsc;
        sitesFilter.pagination['PerPage'] = sitesPerPage;
        sitesFilter.pagination['Page'] = sitesPage;

        $.when(
            refreshSites = getSites(sitesFilter)
        ).then(function(res) {
            if(refreshSites.readyState === 4) {
                sitesTotal = res.totalRecords;
                sitesTotalPages = res.totalPages;
                sites = [];
                if (res.rows) {
                    $.each(res.rows, function (key, row) {
                        var site = row.SiteNumber.substring(5, row.SiteNumber.length - 4);
                        sites.push( api2App(row, 'site') );
                        // Add a row for actions
                        sites[key].action = "EDIT";
                        sites[key].site = site;
                    });
                }
                $('#sitePagination').bootpag({total: sitesTotalPages});
                $('#siteTotalRecords').text("Total: " + res.totalRecords);

                createSiteHot();
            } else {
                hotSite.clear();
            }
        });
    } else if (activeTab === 'siteHolds') {
        $.when(
            refreshSiteHolds = getSiteHolds('Community,SiteHoldReason')
        ).then(function() {
            if(refreshSiteHolds.readyState === 4) {
                createSiteHoldHot();
            } else {
                hotSiteHold.clear();
            }
        });
    }
}

function resetCommunitiesPagination() {
    communitiesPage = 1;
    $('#communityPagination').bootpag({page: 1});
}

function resetFischerSectionsPagination() {
    fischerSectionsPage = 1;
    $('#fischerSectionPagination').bootpag({page: 1});
}

function resetLegalSectionsPagination() {
    legalSectionsPage = 1;
    $('#legalSectionPagination').bootpag({page: 1});
}

function resetSitesPagination() {
    sitesPage = 1;
    $('#sitePagination').bootpag({page: 1});
}

function resetSiteHoldsPagination() {
    siteHoldsPage = 1;
    $('#siteHoldPagination').bootpag({page: 1});
}

function populateFischerSectionModal(id) {
    $.when(
        // Get Fischer Section from API
        getFischerSection(id)
    ).always(function (){
        $.each(Object.keys(app2ApiMap.fischerSection), function(index, field) {
            $("#currentFischerSection-" + field).val('');
        });
        // populate communities options
        $('#currentFischerSection-communityId').empty();
        $('#currentFischerSection-communityId').append( $('<option></option>').val('0').html('---') );
        $.each(communitiesFiltered, function(val, community) {
            $('#currentFischerSection-communityId').append( $('<option></option>').val(community.id).html(community.code + ' ' + community.name) );
        });
        // populate Spec Level options
        $('#currentFischerSection-specLevelId').empty();
        $.each(specLevels, function(val, specLevel) {
            $('#currentFischerSection-specLevelId').append( $('<option></option>').val(specLevel.id).html(specLevel.name) );
        });

    }).done(function() {
        getCommunity(fischerSection.communityId, 'CommunitySection');
        $.each(Object.keys(app2ApiMap.fischerSection), function(index, field) {
            $("#currentFischerSection-" + field).val(fischerSection[field]);
        });
        $("#currentFischerSection-communityId option[value='"+fischerSection.communityId+"']").prop('selected', true);
        $("#currentFischerSection-specLevelId option[value='"+fischerSection.specLevelId+"']").prop('selected', true);
        // No changing Community on update
        $('#currentFischerSection-communityId').attr("disabled", "disabled");
    }).fail(function() {
        $.when(
            getCommunity(communityId, 'CommunitySection')
        ).always(function () {
            $('#currentFischerSection-communityId').removeAttr("disabled");
        }).done(function () {
            $("#currentFischerSection-communityId option[value='"+communityId+"']").prop('selected', true);
        })
    })
}
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

function populateLegalSectionModal(id) {
    $.when(
        // Get legal section from api
        getLegalSection(id, 'Community,Mortgage')
    ).always(function (){
        $.each(Object.keys(app2ApiMap.legalSection), function(index, field) {
            $("#currentLegalSection-" + field).val('');
        });
        // populate communities options
        $('#currentLegalSection-communityId').empty();
        $('#currentLegalSection-communityId').append( $('<option></option>').val('0').html('---') );
        $.each(communitiesFiltered, function(val, community) {
            $('#currentLegalSection-communityId').append( $('<option></option>').val(community.id).html(community.code + ' ' + community.name) );
        });
    }).done(function() {
        $.each(Object.keys(app2ApiMap.legalSection), function(index, field) {
            $("#currentLegalSection-" + field).val(legalSection[field]);
            if(field == 'platRecordingDate' && legalSection[field]) {
                $("#currentLegalSection-" + field).val(moment(legalSection[field]).format('MM/DD/YYYY'));
            }
        });
        $("#currentLegalSection-communityId option[value='"+legalSection.communityId+"']").prop('selected', true);

        $('#currentLegalSection-mortgageInformation').empty();
        $.each(legalSectionMortgages, function(val, mortgage) {
            if(mortgage.date) {
                date = new Date(Date.parse(mortgage.date));
                date.setSeconds(date.getSeconds() + 18000);
                dateString = date.toDateString();
            } else {
                dateString = '';
            }
            if(mortgage.relDate) {
                relDate = new Date(Date.parse(mortgage.relDate));
                relDate.setSeconds(relDate.getSeconds() + 18000);
                relDateString = relDate.toDateString();
            } else {
                relDateString = '';
            }

            $('#currentLegalSection-mortgageInformation').append( $('<div class="well col col-6"></div>').html('<div class="col col-4">Book: </div><div class="col col-8">' + mortgage.book + '</div>\
                <div class="col col-4">Info:</div><div class="col col-8">' + mortgage.info + '</div>\
                <div class="col col-4">Page:</div><div class="col col-8">' + mortgage.page + '</div>\
                <div class="col col-4">Amount:</div><div class="col col-8">' + mortgage.amount + '</div>\
                <div class="col col-4">Date:</div><div class="col col-8">' + dateString + '</div>\
                <div class="col col-4">Rel Date:</div><div class="col col-8">' + relDateString + '</div>\
                '));
        })

        $('#currentLegalSection-mortgageInformationUpdate').empty();
        $('#currentLegalSection-mortgageInformationCreate').empty();
        legalSectionMortgagesDelete = [];
        $.each(legalSectionMortgages, function(val, mortgage) {
            $('#currentLegalSection-mortgageInformationUpdate').append( $('<span id="mortgageInformationUpdateSpan' + mortgage.id + '">\
                    <input type="hidden" name="book" id="currentLegalSectionMortgage-' + mortgage.id + '-id" value="' + mortgage.id + '" class="update-legalSection">\
                    <section>\
                        <div class="row">\
                            <label class="label col col-2">Book:</label>\
                            <div class="col col-4">\
                                <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                    <input type="text" name="book" id="currentLegalSectionMortgage-' + mortgage.id + '-book" value="' + mortgage.book + '" class="update-legalSection">\
                                </label>\
                            </div>\
                            <label class="label col col-2">Info:</label>\
                            <div class="col col-4">\
                                <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                    <input type="text" name="info" id="currentLegalSectionMortgage-' + mortgage.id + '-info" value="' + mortgage.info + '" class="update-legalSection">\
                                </label>\
                            </div>\
                        </div>\
                    </section>\
                    <section>\
                        <div class="row">\
                            <label class="label col col-2">Page:</label>\
                            <div class="col col-4">\
                                <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                    <input type="text" name="page" id="currentLegalSectionMortgage-' + mortgage.id + '-page" value="' + mortgage.page + '" class="update-legalSection">\
                                </label>\
                            </div>\
                            <label class="label col col-2">Amount:</label>\
                            <div class="col col-4">\
                                <label class="input"> <i class="icon-append fa fa-lock"></i>\
                                    <input type="text" name="amount" id="currentLegalSectionMortgage-' + mortgage.id + '-amount" value="' + mortgage.amount + '" class="update-legalSection">\
                                </label>\
                            </div>\
                        </div>\
                    </section>\
                    <section>\
                        <div class="row">\
                            <label class="label col col-2">Date:</label>\
                            <div class="col col-4">\
                                <label class="input"> <i class="icon-append fa fa-asterisk"></i>\
                                    <input type="text" name="date" id="currentLegalSectionMortgage-' + mortgage.id + '-date" class="update-legalSection mortgage-date">\
                                </label>\
                            </div>\
                            <label class="label col col-2">Rel Date:</label>\
                            <div class="col col-4">\
                                <label class="input"> <i class="icon-append fa fa-asterisk"></i>\
                                    <input type="text" name="relDate" id="currentLegalSectionMortgage-' + mortgage.id + '-relDate" class="update-legalSection mortgage-date">\
                                </label>\
                            </div>\
                        </div>\
                    </section>\
                    <a href="" class="deleteCurrentMortgage" id="currentLegalSectionMortgage-delete-' + mortgage.id + '">Delete</a>\
                    <hr class="simple">\
                </span>\
            '));

            $.each(Object.keys(app2ApiMap.mortgage), function(index, field) {
                if(mortgage.date) {
                    date = new Date(Date.parse(mortgage.date));
                    date.setSeconds(date.getSeconds() + 18000);
                    dateString = $.datepicker.formatDate('mm/dd/yy', date);
                } else {
                    dateString = '';
                }
                if(mortgage.relDate) {
                    relDate = new Date(Date.parse(mortgage.relDate));
                    relDate.setSeconds(relDate.getSeconds() + 18000);
                    relDateString = $.datepicker.formatDate('mm/dd/yy', relDate);
                } else {
                    relDateString = '';
                }

                $("#currentLegalSectionMortgage-" + mortgage.id + '-' + field).val(mortgage[field]);
                if(field == 'date' && mortgage[field]) {
                    $("#currentLegalSectionMortgage-" + mortgage.id + '-' + field).val(dateString);
                }
                if(field == 'relDate' && mortgage[field]) {
                    $("#currentLegalSectionMortgage-" + mortgage.id + '-' + field).val(relDateString);
                }
            });
        })
    }).fail(function() {
        $.when(
            getCommunity(communityId, 'CommunitySection')
        ).always(function () {
            $('#currentLegalSection-communityId').removeAttr("disabled");
        }).done(function () {
            $("#currentLegalSection-communityId option[value='"+communityId+"']").prop('selected', true);
        })
    })
}

// LegalSection validation script
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
            required : 'Please enter Legal Section Code',
        },
    },

    // Do not change code below
    errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
    }
});
$("#update-legalSection-form").on( "submit", function( event ) {
    event.preventDefault();
    legalSection = {};
    // do App2Api translation
    $('#update-legalSection-form input, #update-legalSection-form select, #update-legalSection-form textarea').each( function(){
        legalSection[$(this).attr('name')] = $(this).val();
    });
    // if id set do PATCH
    if (legalSection['id'] > 0) {
        updateLegalSection(legalSection);
        reloadActiveTab();
    } else {
        // else do POST
        createLegalSection(legalSection);
        reloadActiveTab();
    }

    $("#updateLegalSectionModal").modal('hide');
});
$("#update-legalSection-mortgage-form").on( "submit", function( event ) {
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

$( function() {
  $( "#currentLegalSection-platRecordingDate" ).datepicker();
  $("#currentSiteHold-holdDate").datepicker();
} );
$('body').on('focus', '.mortgage-date', function() {
    $(this).datepicker();
});
$('body').on('click', '.deleteCurrentMortgage', function(e){
    e.preventDefault();
    let id;
    id = this.id.replace('currentLegalSectionMortgage-delete-','');
    legalSectionMortgagesDelete.push(id);
    $('#mortgageInformationUpdateSpan' + id).hide();
    return false;
});
$('body').on('click', '.deleteNewMortgage', function(e){
    e.preventDefault();
    let id;
    id = this.id.replace('newLegalSectionMortgage-delete-','');
    $('#mortgageInformationCreateSpan' + id).remove();
    return false;
});
cntNewMortgage = 0;
$('body').on('click', "#addMortgage", function (e) {
    e.preventDefault();
    cntNewMortgage++;
    $('#currentLegalSection-mortgageInformationCreate').append( $('<span id="mortgageInformationCreateSpan' + cntNewMortgage + '">\
        <section>\
            <div class="row">\
                <label class="label col col-2">Book:</label>\
                <div class="col col-4">\
                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                        <input type="text" name="book" id="newLegalSectionMortgage-' + cntNewMortgage + '-book" class="create-legalSectionMortgage">\
                    </label>\
                </div>\
                <label class="label col col-2">Info:</label>\
                <div class="col col-4">\
                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                        <input type="text" name="info" id="newLegalSectionMortgage-' + cntNewMortgage + '-info" class="create-legalSectionMortgage">\
                    </label>\
                </div>\
            </div>\
        </section>\
        <section>\
            <div class="row">\
                <label class="label col col-2">Page:</label>\
                <div class="col col-4">\
                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                        <input type="text" name="page" id="newLegalSectionMortgage-' + cntNewMortgage + '-page" class="create-legalSectionMortgage">\
                    </label>\
                </div>\
                <label class="label col col-2">Amount:</label>\
                <div class="col col-4">\
                    <label class="input"> <i class="icon-append fa fa-lock"></i>\
                        <input type="text" name="amount" id="newLegalSectionMortgage-' + cntNewMortgage + '-amount" class="create-legalSectionMortgage">\
                    </label>\
                </div>\
            </div>\
        </section>\
        <section>\
            <div class="row">\
                <label class="label col col-2">Date:</label>\
                <div class="col col-4">\
                    <label class="input"> <i class="icon-append fa fa-asterisk"></i>\
                        <input type="text" name="date" id="newLegalSectionMortgage-' + cntNewMortgage + '-date" class="create-legalSectionMortgage mortgage-date">\
                    </label>\
                </div>\
                <label class="label col col-2">Rel Date:</label>\
                <div class="col col-4">\
                    <label class="input"> <i class="icon-append fa fa-asterisk"></i>\
                        <input type="text" name="relDate" id="newLegalSectionMortgage-' + cntNewMortgage + '-relDate" class="create-legalSectionMortgage mortgage-date">\
                    </label>\
                </div>\
            </div>\
        </section>\
        <a href="" class="deleteNewMortgage" id="newLegalSectionMortgage-delete-' + cntNewMortgage + '">Delete</a>\
        <hr class="simple">\
        </span>\
    '));
    $( "#newLegalSectionMortgage[" + cntNewMortgage + "][date]").datepicker();
    $( "#newLegalSectionMortgage[" + cntNewMortgage + "][relDate]").datepicker();

    return false;
});

function populateSiteModal(id) {
    $.when(
        // Get site from api
        getSite(id)
    ).then(function() {
        $('.siteNumber').text(site.jobNumber);

        $.each(Object.keys(app2ApiMap.site), function(index, field) {
            $("#currentSite-" + field).val('');
        });
        // populate communities options with current cummunity selected
        $('#currentSite-communityId').empty();
        $.each(communitiesFiltered, function(val, community) {
            $('#currentSite-communityId').append( $('<option></option>').val(community.id).html(community.code + ' ' + community.name) );
        });

        // populate Spec Level options
        $('#currentSite-specLevelId').empty();
        $.each(specLevels, function(val, specLevel) {
            $('#currentSite-specLevelId').append( $('<option></option>').val(specLevel.id).html(specLevel.name) );
        });
        // populate Spec Level options
        $('#currentSite-pricingGroupId').empty();
        $.each(prependNullObject(pricingGroups), function(val, pricingGroup) {
            $('#currentSite-pricingGroupId').append( $('<option></option>').val(pricingGroup.id).html(pricingGroup.name) );
        });
    }).done(function() {
        // populate site data
        $.each(Object.keys(app2ApiMap.site), function(index, field) {
            $("#currentSite-" + field).val(site[field]);
            if(field == 'purchaseDate' && site[field]) {
                $("#currentSite-" + field).val(moment(site[field]).format('MM/DD/YYYY'));
            }
        });
        $("#currentSite-communityId option[value='"+site.communityId+"']").prop('selected', true);
        $("#currentSite-fischerSectionId option[value='"+site.fischerSectionId+"']").prop('selected', true);
        $("#currentSite-legalSectionId option[value='"+site.legalSectionId+"']").prop('selected', true);
        $("#currentSite-specLevelId option[value='"+site.specLevelId+"']").prop('selected', true);
        $("#currentSite-pricingGroupId option[value='"+site.pricingGroupId+"']").prop('selected', true);
        $("#currentSite-availableFlag option[value='"+site.availableFlag+"']").prop('selected', true);
        $("#currentSite-availableFlag").css('cursor', 'auto');
        $("#currentSite-availableFlag").prop('disabled', false);
        if (!site.isAvailableFlagEditable) {
            $("#currentSite-availableFlag").prop('disabled', 'disabled');
            $("#currentSite-availableFlag").css('cursor', 'not-allowed');
        }
        $(".attached").show();
        if(site.buildingType == '' || site.buildingType == null) {
            $(".attached").hide();
        }

        // Mortgage Ammendment data
        $('#currentSite-mortgageInformation').empty();

        $.each(siteMortgageAmmendments, function(val, mortgageAmmendment) {
            if(mortgageAmmendment.requestDate) {
                requestDate = new Date(Date.parse(mortgageAmmendment.requestDate));
                requestDate.setSeconds(requestDate.getSeconds() + 18000);
                requestDateString = requestDate.toDateString();
            } else {
                releaseDateString = '';
            }
            if(mortgageAmmendment.receivedDate) {
                receivedDate = new Date(Date.parse(mortgageAmmendment.receivedDate));
                receivedDate.setSeconds(receivedDate.getSeconds() + 18000);
                receivedDateString = receivedDate.toDateString();
            } else {
                receivedDateString = '';
            }
            if(mortgageAmmendment.recordedDate) {
                recordedDate = new Date(Date.parse(mortgageAmmendment.recordedDate));
                recordedDate.setSeconds(recordedDate.getSeconds() + 18000);
                recordedDateString = recordedDate.toDateString();
            } else {
                recordedDateString = '';
            }
            if(mortgageAmmendment.releaseDate) {
                releaseDate = new Date(Date.parse(mortgageAmmendment.releaseDate));
                releaseDate.setSeconds(releaseDate.getSeconds() + 18000);
                releaseDateString = releaseDate.toDateString();
            } else {
                releaseDateString = '';
            }

            $('#currentSite-mortgageInformation').append( $('<div class="well col col-6"></div>').html('<div class="row"><div class="col col-4">FH Mortgage Amend #: </div><div class="col col-8">' + mortgageAmmendment.ammendmentNumber + '</div></div>\
                <div class="row"><div class="col col-4">Request Date:</div><div class="col col-8">' + releaseDateString + '</div></div>\
                <div class="row"><div class="col col-4">Received Date:</div><div class="col col-8">' + receivedDateString + '</div></div>\
                <div class="row"><div class="col col-4">Recorded Date:</div><div class="col col-8">' + recordedDateString + '</div></div>\
                <div class="row"><div class="col col-4">Release Date:</div><div class="col col-8">' + releaseDateString + '</div></div>\
                <div class="row"><div class="col col-4">Lender:</div><div class="col col-8">' + mortgageAmmendment.lender + '</div></div>\
                '));
        })

        $("#updateSiteModalLandAdmin").modal();
    })
}

$( function() {
  $( "#currentSite-purchaseDate" ).datepicker();
} );

function populateSiteModalAdd(communityId) {
    sitesToAdd = [];

    $.when(
            getCommunity(communityId, 'CommunitySection,CommunitySectionLegal,CommunitySite')
        ).always(function() {
            // populate communities options with current cummunity selected
            $('#addSite-communityId').empty();
            $('#addSite-communityId').append( $('<option></option>').val('0').html('---') );
            $.each(communitiesFiltered, function(val, community) {
                $('#addSite-communityId').append( $('<option></option>').val(community.id).html(community.code + ' ' + community.name) );
            });
            $('#addSite-fischerSectionId').empty();
            $('#addSite-legalSectionId').empty();
            if (communityId) {
                // populate Fischer Section options
                $.each(communityFischerSections, function(val, fischerSection) {
                    $('#addSite-fischerSectionId').append( $('<option></option>').val(fischerSection.id).html(fischerSection.name) );
                });
                // populate Legal Section options
                $.each(communityLegalSections, function(val, legalSection) {
                    $('#addSite-legalSectionId').append( $('<option></option>').val(legalSection.id).html(legalSection.name) );
                });
            }
        }).done(function() {
            $("#addSite-communityId option[value='"+community.id+"']").prop('selected', true);
            $("#addSite-communityId").prop("disabled", true);

            if(sitesToAdd.length < 1) {
                sitesToAdd.push(
                    { 
                        communityCode: community.code,
                        first: null, 
                        second: null, 
                        third: null,
                        range: null,
                        dupe: false,
                    }
                );
            }

            renderSiteNumbers(sitesToAdd);
        })
}
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

$(document).on("change", "#addSite-communityId", function() {
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

function populateSiteAttachedModalAdd(communityId) {
    $.when(
            getCommunity(communityId, 'CommunitySection,CommunitySectionLegal,CommunitySite')
        ).always(function() {
            // populate communities options with current cummunity selected
            $('#addSiteAttached-communityId').empty();
            $('#addSiteAttached-communityId').append( $('<option></option>').val('0').html('---') );
            $.each(communitiesFiltered, function(val, community) {
                $('#addSiteAttached-communityId').append( $('<option></option>').val(community.id).html(community.code + ' ' + community.name) );
            });
            // populate companies options
            $('#addSiteAttached-companyId').empty();
            $('#addSiteAttached-companyId').append( $('<option></option>').val('0').html('---') );
            $.each(companies, function(val, company) {
                $('#addSiteAttached-companyId').append( $('<option></option>').val(company.id).html(company.shortName + ' ' + company.fullName) );
            });
            // populate attachedBuildingCatalog options
            $('#addSiteAttached-attachedBuildingCatalogId').empty();
            $('#addSiteAttached-attachedBuildingCatalogId').append( $('<option></option>').val('0').html('---') );
            $.each(attachedBuildingCatalogBuildingTypes, function(key, attachedBuildingCatalogBuildingType) {
                $('#addSiteAttached-attachedBuildingCatalogId').append( $('<option></option>').val(key).html(key + ' - ' + attachedBuildingCatalogBuildingType) );
            });

            $('#addSiteAttached-fischerSectionId').empty();
            $('#addSiteAttached-legalSectionId').empty();
            if (communityId) {
                // populate Fischer Section options
                $.each(communityFischerSections, function(val, fischerSection) {
                    $('#addSiteAttached-fischerSectionId').append( $('<option></option>').val(fischerSection.id).html(fischerSection.name) );
                });
                // populate Legal Section options
                $.each(communityLegalSections, function(val, legalSection) {
                    $('#addSiteAttached-legalSectionId').append( $('<option></option>').val(legalSection.id).html(legalSection.name) );
                });
            }
            $('#siteAttachedForm-continue').show();
            $('#siteAttachedForm-submit').hide();
            $('#siteAttachedForm-sitesFieldset').empty();
        }).done(function() {
            $("#addSiteAttached-communityId option[value='"+community.id+"']").prop('selected', true);
            $('#addSiteAttached-siteNumberCommunityCode').removeAttr("disabled");
            $('#addSiteAttached-siteNumberCommunityCode').val(community.code);
            $('#addSiteAttached-siteNumberCommunityCode').attr("disabled", "disabled");
        })
}
// Site validation script
$("#siteAttachedForm-continue").on('click', function() {
    $('#siteAttachedForm-continue').hide();
    $('#siteAttachedForm-submit').show();
    jobNumberTemplate = $('#addSiteAttached-siteNumberCommunityCode').val() + $('#addSiteAttached-siteNumberFirstTwo').val() + $('#addSiteAttached-siteNumberMiddleThree').val();
    siteNumberTemplate = $('#addSiteAttached-siteNumberCommunityCode').val() + $('#addSiteAttached-siteNumberFirstTwo').val() + $('#addSiteAttached-siteNumberMiddleThree').val();
    $('#siteAttachedForm-sitesFieldset').empty();
    $('#siteAttachedForm-sitesFieldset').append('<fieldset></fieldset>');
    numberSiteAttached = 0;
    $.each(attachedBuildingCatalogs, function(key, attachedBuildingCatalog) {
        if (attachedBuildingCatalog.buildingType == $('#addSiteAttached-attachedBuildingCatalogId').val()) {
            numberSiteAttached++;
            $('#siteAttachedForm-sitesFieldset').append('<fieldset>\
                <section>\
                    <div class="row">\
                        <label class="label col col-2">Job Number</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="jobNumber-' + numberSiteAttached + '" id="addSiteAttached-jobNumber-' + numberSiteAttached + '" value="' + jobNumberTemplate + attachedBuildingCatalog.homeNumber + '">\
                            </label>\
                        </div>\
                        <label class="label col col-2">Site Number</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="siteNumber' + numberSiteAttached + '" id="addSiteAttached-siteNumber-' + numberSiteAttached + '" value="' + siteNumberTemplate + attachedBuildingCatalog.homeNumber + '">\
                            </label>\
                        </div>\
                    </div>\
                </section>\
                <section>\
                    <div class="row">\
                        <label class="label col col-2">Street Address</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="address1-' + numberSiteAttached + '" id="addSiteAttached-address1-' + numberSiteAttached + '">\
                            </label>\
                        </div>\
                        <label class="label col col-2"> &nbsp;</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="address2-' + numberSiteAttached + '" id="addSiteAttached-address2-' + numberSiteAttached + '">\
                            </label>\
                        </div>\
                    </div>\
                </section>\
                <section>\
                    <div class="row">\
                        <label class="label col col-2">GCL Job Number</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="gcJobNumber-' + numberSiteAttached + '" id="addSiteAttached-gcJobNumber-' + numberSiteAttached + '" value="' + $('#addSiteAttached-gcJobNumber').val() + '">\
                            </label>\
                        </div>\
                    </div>\
                </section>\
                <section>\
                    <div class="row">\
                        <label class="label col col-2">Walk-Out Premium</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="walkOutPremium-' + numberSiteAttached + '" id="addSiteAttached-walkOutPremium-' + numberSiteAttached + '">\
                            </label>\
                        </div>\
                        <label class="label col col-2">Garage Walk-Out Premium</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="garagePremium-' + numberSiteAttached + '" id="addSiteAttached-garagePremium-' + numberSiteAttached + '">\
                            </label>\
                        </div>\
                    </div>\
                </section>\
                <section>\
                    <div class="row">\
                        <label class="label col col-2">View Premium</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="viewPremium-' + numberSiteAttached + '" id="addSiteAttached-viewPremium-' + numberSiteAttached + '">\
                            </label>\
                        </div>\
                        <label class="label col col-2">Garage Entry</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="garageEntry-' + numberSiteAttached + '" id="addSiteAttached-garageEntry-' + numberSiteAttached + '" value="' + attachedBuildingCatalog.garageEntry + '">\
                            </label>\
                        </div>\
                    </div>\
                </section>\
                <section>\
                    <div class="row">\
                        <label class="label col col-2">Site Cost</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="siteCost-' + numberSiteAttached + '" id="addSiteAttached-siteCost-' + numberSiteAttached + '">\
                            </label>\
                        </div>\
                        <label class="label col col-2">Lower Level</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="lowerLevel-' + numberSiteAttached + '" id="addSiteAttached-lowerLevel-' + numberSiteAttached + '" value="' + attachedBuildingCatalog.lowerLevel + '">\
                            </label>\
                        </div>\
                    </div>\
                </section>\
                <section>\
                    <div class="row">\
                        <label class="label col col-2">Rear Exit</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="rearExit-' + numberSiteAttached + '" id="addSiteAttached-rearExit-' + numberSiteAttached + '" value="' + attachedBuildingCatalog.rearExit + '">\
                            </label>\
                        </div>\
                    </div>\
                </section>\
                <section>\
                    <div class="row">\
                        <label class="label col col-2">Notes</label>\
                        <div class="col col-4">\
                            <label class="input"> <i class="icon-append fa fa-book"></i>\
                                <input type="text" name="notes-' + numberSiteAttached + '" id="addSiteAttached-notes-' + numberSiteAttached + '">\
                            </label>\
                        </div>\
                    </div>\
                </section></fieldset>'
                    );
        }
    });
});
$("#add-siteAttached-form").validate({
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
        }
    },

    // Do not change code below
    errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
    },
    submitHandler: function(form) {
        let purchaser = companies.filter(function(el){ return el.id == $('#addSiteAttached-companyId').val()})[0].shortName;
        event.preventDefault();
        sitesToCreate = [];
        sitesToCreate[0] = {};
        sitesToCreate[0].communityId = $('#addSiteAttached-communityId').val();
        sitesToCreate[0].fischerSectionId = $('#addSiteAttached-fischerSectionId').val();
        sitesToCreate[0].legalSectionId = $('#addSiteAttached-legalSectionId').val();
        sitesToCreate[0].siteNumber = $('#addSiteAttached-siteNumberCommunityCode').val() + $('#addSiteAttached-siteNumberFirstTwo').val() + $('#addSiteAttached-siteNumberMiddleThree').val() + $('#addSiteAttached-siteNumberLastFour').val();;
        sitesToCreate[0].jobNumber = $('#addSiteAttached-siteNumberCommunityCode').val() + $('#addSiteAttached-siteNumberFirstTwo').val() + $('#addSiteAttached-siteNumberMiddleThree').val() + $('#addSiteAttached-siteNumberLastFour').val();
        sitesToCreate[0].gcJobNumber = $('#addSiteAttached-gcJobNumber').val();
        sitesToCreate[0].buildingNumber = $('#addSiteAttached-buildingNumber').val();
        sitesToCreate[0].buildingType = $('#addSiteAttached-attachedBuildingCatalogId').val();
        sitesToCreate[0].unitNumber = parseInt(sitesToCreate[0].jobNumber.substring(8,12));
        sitesToCreate[0].lotNumber = $('#addSiteAttached-lotNumber').val();
        sitesToCreate[0].purchaser = purchaser;
        sitesToCreate[0].vendorId = $('#addSiteAttached-vendorId').val();
        sitesToCreate[0].streetAddressRange = $('#addSiteAttached-streetAddressRange').val();
        sitesToCreate[0].fireSuppressRoomAddress = $('#addSiteAttached-fireSuppressRoomAddress').val();
        for (i = 1; i <= numberSiteAttached; i++) {
            sitesToCreate[i] = {};
            sitesToCreate[i].communityId = $('#addSiteAttached-communityId').val();
            sitesToCreate[i].fischerSectionId = $('#addSiteAttached-fischerSectionId').val();
            sitesToCreate[i].legalSectionId = $('#addSiteAttached-legalSectionId').val();
            sitesToCreate[i].siteNumber = $('#addSiteAttached-siteNumber-' + i).val();
            sitesToCreate[i].jobNumber = $('#addSiteAttached-jobNumber-' + i).val();
            sitesToCreate[i].gcJobNumber = $('#addSiteAttached-gcJobNumber-' + i).val();
            sitesToCreate[i].address1 = $('#addSiteAttached-address1-' + i).val();
            sitesToCreate[i].address2 = $('#addSiteAttached-address2-' + i).val();
            sitesToCreate[i].walkOutPremium = $('#addSiteAttached-walkOutPremium-' + i).val();
            sitesToCreate[i].garagePremium = $('#addSiteAttached-garagePremium-' + i).val();
            sitesToCreate[i].viewPremium = $('#addSiteAttached-viewPremium-' + i).val();
            sitesToCreate[i].garageEntry = $('#addSiteAttached-garageEntry-' + i).val();
            sitesToCreate[i].siteCost = $('#addSiteAttached-siteCost-' + i).val();
            sitesToCreate[i].lowerLevel = $('#addSiteAttached-lowerLevel-' + i).val();
            sitesToCreate[i].rearExit = $('#addSiteAttached-rearExit-' + i).val();
            sitesToCreate[i].notes = $('#addSiteAttached-notes-' + i).val();
            sitesToCreate[i].buildingNumber = $('#addSiteAttached-buildingNumber').val();
            sitesToCreate[i].buildingType = $('#addSiteAttached-attachedBuildingCatalogId').val();
            sitesToCreate[i].unitNumber = parseInt(sitesToCreate[i].jobNumber.substring(8,12));
            sitesToCreate[i].purchaser = purchaser;
            sitesToCreate[i].lotNumber = $('#addSiteAttached-lotNumber').val();
            sitesToCreate[i].vendorId = $('#addSiteAttached-vendorId').val();
            sitesToCreate[i].streetAddressRange = $('#addSiteAttached-streetAddressRange').val();
            sitesToCreate[i].fireSuppressRoomAddress = $('#addSiteAttached-fireSuppressRoomAddress').val();
        }

        createSites(sitesToCreate);
        $("#addSiteAttachedModal").modal("hide");
        reloadActiveTab();
    }
});
$("#addSiteAttached-communityId").on("change", function() {
    populateSiteAttachedModalAdd($(this).val());
});

function populateSiteHoldModal(id) {
    $.when(
        // Get Site Hold from API
        getSiteHold(id)
    ).always(function (){
        $.each(Object.keys(app2ApiMap.siteHold), function(index, field) {
            $("#currentSiteHold-" + field).val('');
        });
        // populate communities options
        $('#currentSiteHold-communityId').empty();
        $('#currentSiteHold-communityId').append( $('<option></option>').val('0').html('---') );
        $.each(communitiesFiltered, function(val, community) {
            $('#currentSiteHold-communityId').append( $('<option></option>').val(community.id).html(community.code + ' ' + community.name) );
        });
        // populate Site Hold Reason options
        $('#currentSiteHold-holdReasonId').empty();
        $.each(siteHoldReasons, function(val, holdReason) {
            $('#currentSiteHold-holdReasonId').append( $('<option></option>').val(holdReason.id).html(holdReason.description) );
        });
    }).done(function() {
        $.when(
            getCommunity(siteHold.communityId, 'CommunitySite')
        ).done(function () {
            $('#currentSiteHold-siteNumberCommunityCode').val(community.code);
            $('#currentSiteHold-siteNumberCommunityCode').attr("disabled", "disabled");
            $('#currentSiteHold-siteNumberNumbers').val(siteHold.siteNumber.substr(3));
            $('#currentSiteHold-siteNumberNumbers').attr("disabled", "disabled");
        });
        $.each(Object.keys(app2ApiMap.siteHold), function(index, field) {
            $("#currentSiteHold-" + field).val(siteHold[field]);
        });
        $("#currentSiteHold-communityId option[value='"+siteHold.communityId+"']").prop('selected', true);
        $("#currentSiteHold-holdReasonId option[value='"+siteHold.holdReasonId+"']").prop('selected', true);
        // No changing Community on update
        $('#currentSiteHold-communityId').attr("disabled", "disabled");

        if(siteHold.holdDate) {
            holdDate = new Date(Date.parse(siteHold.holdDate));
            holdDate.setSeconds(holdDate.getSeconds() + 18000);
            holdDateString = $.datepicker.formatDate('mm/dd/yy', holdDate);
        } else {
            holdDateString = '';
        }
        $("#currentSiteHold-holdDate").val(holdDateString);
    }).fail(function() {
        $('#currentSiteHold-siteNumberCommunityCode').val('');
        $('#currentSiteHold-siteNumberNumbers').val('');
        $.when(
            getCommunity(communityId, 'CommunitySite')
        ).always(function () {
            $('#currentSiteHold-communityId').removeAttr("disabled");
        }).done(function () {
            $("#currentSiteHold-communityId option[value='"+communityId+"']").prop('selected', true);
            $('#currentSiteHold-siteNumberCommunityCode').removeAttr("disabled");
            $('#currentSiteHold-siteNumberCommunityCode').val(community.code);
            $('#currentSiteHold-siteNumberCommunityCode').attr("disabled", "disabled");
        })
    })
}
// Site Hold validation script
$("#update-siteHold-form").validate({
    // Rules for form validation
    rules : {
        communityId : {
            required : true
        },
        siteNumberNumbers : {
            required : true
        },
        holdReasonId : {
            required : true
        }
    },
    // Messages for form validation
    messages : {
        communityId : {
            required : "Please select a Community"
        },
        siteNumberNumbers : {
            required : "Please provide site number"
        },
        holdReasonId : {
            required : "Please select a Reason"
        }
    },

    // Do not change code below
    errorPlacement : function(error, element) {
        error.insertAfter(element.parent());
    },
    submitHandler: function(form) {
        error = false;
        formSiteHold = {};
        // do App2Api translation
        $("#update-siteHold-form input, #update-siteHold-form select, #update-siteHold-form textarea").each( function(){
            formSiteHold[$(this).attr("name")] = $(this).val();
        });

        siteExists = false;
        siteNumber = formSiteHold.siteNumberCommunityCode + formSiteHold.siteNumberNumbers;
        $.each(communitySites, function(key, communitySite) {
            if (communitySite.siteNumber == siteNumber) {
                siteExists = true;
                $.when(
                    getSiteHold(communitySite.id, 'CommunitySites')
                ).then(function() {
                    if (!error) {
                        // if id set do PATCH
                        if (formSiteHold.id > 0) {
                            updateSiteHold(formSiteHold);
                        } else {
                            // else do POST
                            formSiteHold.id = communitySite.id;
                            createSiteHold(formSiteHold);
                        }
                    }
                });
            }
        });
        if (!siteExists && !error) {
            $.bigBox({
                    title : "Error: Site Number " + siteNumber + " not found",
                    content : "No Site Hold was created. Please review Site Number and try again.",
                    color : "#EA5939",
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
            error = true;
        }

        $("#updateSiteHoldModal").modal("hide");
    }
});
$('#currentSiteHold-communityId').on('change', function() {
    communityId = $(this).val();
    populateSiteHoldModal(0);
});
$( "#currentSiteHold-siteNumberNumbers" ).autocomplete({
    source: function(request, response) {
        let siteHoldNumbers = [];
        $.each( communitySites, function( key, communitySite ) {
            siteNumber = communitySite.siteNumber.substr(3);
            if (siteNumber.includes(request.term)) {
                siteHoldNumbers.push(siteNumber);
            }
        });
        response(siteHoldNumbers);
    }
});
$( "#currentSiteHold-holdVendorId" ).autocomplete({
    source: function(request, response) {
        $.when(
            getVendors()
        ).then(function() {
            let siteHoldVendors = [];
            $.each( vendors, function( key, vendor ) {
                siteHoldVendors.push(vendor.vendorId);
            });
            response(siteHoldVendors);
        })



    }
});

function createDefaultCommunityHot() {
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
        fixedColumnsLeft: 6,
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 6,
        colWidths: [80,115,250,80,160,210],
        colHeaders: ['Id', 'Community Code', 'Community Name', 'Division', 'Division Name', 'Budget Neighborhood'],
        columns: [
            {data: 'id', type: 'numeric', readOnly: true},
            {data: 'code', type: 'text', readOnly: true},
            {data: 'name', type: 'text', readOnly: true},
            {data: 'DivisionCode', type: 'text', readOnly: true},
            {data: 'DivisionName', type: 'text', readOnly: true},
            {data: 'BudgetneighborhoodName', type: 'text', readOnly: true}
        ],
    });
}

function createCommunityHot() {
    if(hotCommunity) {
        hotCommunity.destroy();
    }

    createCustomCommunityHot();

    hotCommunity.loadData(communitiesFiltered);
};

function createDefaultFischerSectionHot() {
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
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 5,
        wordWrap: false,
        colWidths: [80,80,115,220,100,160],
        colHeaders: [
            'F. Section Id', 'Action', '<span title="No sort">Community Code', '<span title="No sort">Community Name</sort>', 'Section Name', '<span title="No sort">Spec Level</sort>'
        ],
        columns: [
            {data: 'id', type: 'numeric', readOnly: true},
            {data: 'action', type: 'text'},
            {data: 'CommunityCode', type: 'text', readOnly: true},
            {data: 'CommunityName', type: 'text', readOnly: true},
            {data: 'name', type: 'text'},
            {
                data: 'SpeclevelName',
                type: 'handsontable',
                handsontable: {
                    colHeaders: ['Spec Level Id', 'Spec Level Descr'],
                    autoColumnSize: true,
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
//                    console.log('Loading data.');
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

function createFischerSectionHot() {
    if(hotFischerSection) {
        hotFischerSection.destroy();
    }

    createCustomFischerSectionHot();

    hotFischerSection.loadData(fischerSections);
}

/*********************************************************************************************************************
* Starts Legal Section Grid
*********************************************************************************************************************/
function createDefaultLegalSectionHot() {
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
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 20,
        wordWrap: false,
        colWidths: [
            130,80,115,125,140,130,130,120,115,140,
            145,145,130,145,150,150,150,150,150,150,
            165,170,140,145,150,150,
            150,80,80,155,150,150,140,150,160,150,165,150,160,130,130,140,140,140,150,120,120,130,165,110,
            120,130,130,120,140,140,160,140,160,150,80,80,130,130,110,120,150,120
        ],
        colHeaders: [
            'L. Section Record Id', 'Action', '<span title="No sort">Community Code</sort>', 'Legal Section Code', 'Section Phase Name','Plat Recording Book','Plat Recording Date',
            'Recorded Name','Legal Description','Covenant Restriction','Covenant Restriction2','Covenant Restriction3',
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
            ],
        columns: [
            {data: 'id', type: 'numeric', readOnly: true},
            {data: 'action', type: 'text'},
            {data: 'CommunityCode', type: 'text', readOnly: true},
            {data: 'name', type: 'text'},
            {data: 'sectionPhaseName', type: 'text'},
            {data: 'platRecordingBook', type: 'text'},
            {data: 'platRecordingDate', type: 'text'},
            {data: 'recordedName', type: 'text'},
            {data: 'legalDescription', type: 'text'},
            {data: 'covenantRestriction', type: 'text'},
            {data: 'covenantRestriction2', type: 'text'},
            {data: 'covenantRestriction3', type: 'text'},
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
            {data: 'maxSqFt2Story',type: 'text'}
        ],
        beforeOnCellMouseDown: function (event, coords) {
            if (!$('#chLegalSectionSave').is(':checked') && coords.row > -1 && this.colToProp(coords.col) === 'name') {
                legalSection = this.getDataAtRow(coords.row);
                legalSectionId = legalSection[0];
                populateLegalSectionDropdown();
            } else if (!$('#chLegalSectionSave').is(':checked') && coords.row > -1 && this.colToProp(coords.col) === 'action') {
                legalSection = this.getDataAtRow(coords.row);
                selectedModalLegalSectionId = legalSection[0];
                populateLegalSectionModal(selectedModalLegalSectionId);
                $("#updateLegalSectionModal").modal('show');
            }

            return;
        },
        afterChange: function (change, source) {
            if (source === 'loadData') {
//                    console.log('Loading data.');
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

function createLegalSectionHot() {
    if(hotLegalSection) {
        hotLegalSection.destroy();
    }

    createCustomLegalSectionHot();

    hotLegalSection.loadData(legalSections);
}

function createSiteHot() {
    if(hotSite) {
        hotSite.destroy();
    }

    createCustomSiteHot();

    hotSite.loadData(sites);
    hotSite.updateSettings({
        cells: function (row, col, prop) {
            if (prop == 'availableFlag') {
                isEditable = hotSite.getDataAtCell(row, col + 1);
                var cellProperties = {};
                if (!isEditable) {
                    cellProperties.readOnly = 'true';
                }
            }

            return cellProperties;
        }
    });
}

function createDefaultSiteHoldHot() {
    return hotSiteHold = new Handsontable($siteHoldContainer[0], {
        allowRemoveRow: true,
        allowRemovecolumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
        autoRowSize: false,
        autoColumnSize: false,
        columnSorting: true,
        contextMenu: true,
        data: [],
        minSpareCols: 0,
        minSpareRows: 0,
        rowHeaders: true,
        startRows: 2,
        startCols: 1,
        viewportColumnRenderingOffset: 20,
        wordWrap: false,
        colWidths: [
            50,110,70,115,250,
            80,100,130,90,150,
            80,90,110,90
        ],
        colHeaders: [
            'Site Id', 'Site Number', '<span title="No sort">Action</span>', '<span title="No sort">Community Code</sort>', 'Reason',
            'Vendor Id', 'Date', 'Control Code', 'Deposit', 'Notes',
            'Created By', 'Created Date', 'Last Modified By', 'Last Modifed Date'
        ],
        columns: [
            {data: 'id', type: 'numeric', readOnly: true},
            {data: 'siteNumber', type: 'text', readOnly: true},
            {data: 'action', type: 'text'},
            {data: 'CommunityCode', type: 'text', readOnly: true},
            {
                data: 'SiteHoldReasonDescription',
                type: 'handsontable',
                handsontable: {
                    colHeaders: ['Reason Id', 'Reason Description'],
                    autoColumnSize: true,
                    data: siteHoldReasons,
                    getValue: function() {
                        var selection = this.getSelected();
                        // Set selectedSiteholdReasonId to be used in creates/updates
                        selectedSiteholdReasonId = this.getSourceDataAtRow(selection[0]).id;
                        // Return name to be displayed
                        return this.getSourceDataAtRow(selection[0]).description;
                    }
                }
            },
            {data: 'holdVendorId', type: 'text', readOnly: true},
            {data: 'holdDate', type: 'text'},
            {data: 'holdControlCode', type: 'text'},
            {data: 'holdDeposit', type: 'numeric', format: '$0,0.00', language: 'en-US'},
            {data: 'holdNotes', type: 'text'},
            {data: 'holdCreatedBy', type: 'text'},
            {data: 'holdCreatedDate', type: 'text'},
            {data: 'holdLastModifiedBy', type: 'text'},
            {data: 'holdLastModifiedDate', type: 'text'},
        ],
        beforeOnCellMouseDown: function (event, coords) {
            if (!$('#chSiteHoldSave').is(':checked') && coords.row > -1 && this.colToProp(coords.col) === 'action') {
                siteHold = this.getDataAtRow(coords.row);
                siteHoldSiteId = siteHold[0];
                populateSiteHoldModal(siteHoldSiteId);
                $("#updateSiteHoldModal").modal('show');
            }

            return;
        },
        afterChange: function (change, source) {
            if (source === 'loadData') {
                return;
            }
            if (!$('#chSiteHoldSave').is(':checked')) {
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
                var row = hotSiteHold.getDataAtRow(val[0]);
                // Special handling for belongsTo relation
                if (change[index][1] === 'SiteholdReason') {
                    change[index][1] = app2ApiMap.site['siteholdReasonId'];
                    change[index][3] = selectedSiteholdReasonId;
                }
                change[index][0] = row[0];
                jsonData[index][app2ApiMap.siteHold['id']] = row[0];
                jsonData[index][change[index][1]] = change[index][3];
            });
            $.ajax({
                url: '/slimapi/v1/siteHolds',
                type: 'PATCH',
                processData: false,
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(jsonData),
            })
            .fail(function(request, status, error) {
                errorObject = JSON.parse(request.responseText);
                errorSites = errorObject.errors;
                $.each(errorSites, function(index, val) {
                    $.bigBox({
                        title : errorObject.message,
                        content : 'Site Hold ' + val.resource + ' could not be updated.',
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
                return false;
            }
            if (!$('#chSiteHoldSave').is(':checked')) {
                $.bigBox({
                    title : "Save on Change is not on",
                    content : "Please select Save on Change before deleting data.",
                    color : "#EA5939",
                    timeout: 8000,
                    icon : "fa fa-exclamation fadeInLeft animated"
                });
                reloadActiveTab();
                return false;
            }
            removeId = hotSiteHold.getDataAtCell(index, 0);
            $.ajax({
                url: '/slimapi/v1/siteHolds/' + removeId,
                type: 'DELETE',
                dataType: 'json'
            })
            .done(function() {
                reloadActiveTab();
            })
            .fail(function() {
                console.log('Site Hold delete failed.');
                reloadActiveTab();
            });
        },
        beforeColumnSort: function(column) {
            if (sortSiteHoldsBy === hotSiteHold.colToProp(column)) {
                if (sortSiteHoldsAsc === 'asc') {
                    sortSiteHoldsAsc = 'desc';
                } else {
                    sortSiteHoldsAsc = 'asc';
                }
            } else {
                sortSiteHoldsAsc = 'asc';
            }
            sortSiteHoldsBy = hotSiteHold.colToProp(column);
            reloadActiveTab();
            return false;
        }
    });
}

function createSiteHoldHot() {
    if(hotSiteHold) {
        hotSiteHold.destroy();
    }
    
    createCustomSiteHoldHot();

    hotSiteHold.loadData(siteHolds);
}

$(document).ready(function() {
    setEnvironment();
    getSyssyncQ();
    setInterval(function() {
        getSyssyncQ();
    }, 10000); // Every 10 seconds
    getCustomHots();
    $.when(
        loadScript("js/plugin/jquery-form/jquery-form.min.js", function() {}),
        getDivisions(),
        getCompanies(),
        getPricingGroups(),
        getAttachedBuildingCatalogs(),
        getSpecLevels(),
        getSiteHoldReasons(),
        getCommunities('Division,BudgetNeighborhood,FischerSection,LegalSection'),
    ).then(function() {
        populateDivisionDropdown();
        getCommunitiesFiltered();
        populateSiteHoldReasonFilterDropdown();
    }).then(function() {
        populateCommunityDropdown();
        createCommunityHot();
        $('#communityPagination').bootpag({
            total: communitiesTotalPages,
            page: 1,
            maxVisible: 5,
            leaps: true,
            firstLastUse: true,
            first: '←',
            last: '→',
            wrapClass: 'pagination no-margin',
            activeClass: 'active',
            disabledClass: 'disabled',
            nextClass: 'next',
            prevClass: 'prev',
            lastClass: 'last',
            firstClass: 'first'
        }).on("page", function(event, num){
            communitiesPage = num;
            reloadActiveTab();
        });
        $('#fischerSectionPagination').bootpag({
            total: fischerSectionsTotalPages,
            page: 1,
            maxVisible: 5,
            leaps: true,
            firstLastUse: true,
            first: '←',
            last: '→',
            wrapClass: 'pagination no-margin',
            activeClass: 'active',
            disabledClass: 'disabled',
            nextClass: 'next',
            prevClass: 'prev',
            lastClass: 'last',
            firstClass: 'first'
        }).on("page", function(event, num){
            fischerSectionsPage = num;
            reloadActiveTab();
        });
        $('#legalSectionPagination').bootpag({
            total: legalSectionsTotalPages,
            page: 1,
            maxVisible: 5,
            leaps: true,
            firstLastUse: true,
            first: '←',
            last: '→',
            wrapClass: 'pagination no-margin',
            activeClass: 'active',
            disabledClass: 'disabled',
            nextClass: 'next',
            prevClass: 'prev',
            lastClass: 'last',
            firstClass: 'first'
        }).on("page", function(event, num){
            legalSectionsPage = num;
            reloadActiveTab();
        });
        $('#sitePagination').bootpag({
            total: sitesTotalPages,
            page: 1,
            maxVisible: 5,
            leaps: true,
            firstLastUse: true,
            first: '←',
            last: '→',
            wrapClass: 'pagination no-margin',
            activeClass: 'active',
            disabledClass: 'disabled',
            nextClass: 'next',
            prevClass: 'prev',
            lastClass: 'last',
            firstClass: 'first'
        }).on("page", function(event, num){
            sitesPage = num;
            reloadActiveTab();
        });
        $('#siteHoldPagination').bootpag({
            total: siteHoldsTotalPages,
            page: 1,
            maxVisible: 5,
            leaps: true,
            firstLastUse: true,
            first: '←',
            last: '→',
            wrapClass: 'pagination no-margin',
            activeClass: 'active',
            disabledClass: 'disabled',
            nextClass: 'next',
            prevClass: 'prev',
            lastClass: 'last',
            firstClass: 'first'
        }).on("page", function(event, num){
            siteHoldsPage = num;
            reloadActiveTab();
        });

    });

    $('.filterCommunities').on('keyup', function(event) {
        event.preventDefault();
        resetCommunitiesPagination();
        clearTimeout(autosaveNotification);
        autosaveNotification = setTimeout(function () {
            reloadActiveTab();
        }, 500);
    });
    $('.filterFischerSections').on('keyup', function(event) {
        event.preventDefault();
        resetFischerSectionsPagination();
        clearTimeout(autosaveNotification);
        autosaveNotification = setTimeout(function () {
            reloadActiveTab();
        }, 500);
    });
    $('.filterLegalSections').on('keyup', function(event) {
        event.preventDefault();
        resetLegalSectionsPagination();
        clearTimeout(autosaveNotification);
        autosaveNotification = setTimeout(function () {
            reloadActiveTab();
        }, 500);
    });
    $('.filterSites').on('keyup click', function(event) {
        event.preventDefault();
        resetSitesPagination();
        clearTimeout(autosaveNotification);
        autosaveNotification = setTimeout(function () {
            reloadActiveTab();
        }, 500);
    });
    $('.filterSiteAvailable').on('click', function(event) {
        event.preventDefault();
        $('.filterSiteAvailable').removeClass("btn-primary");
        var clickedBtnID = $(this).attr('id');
        $("#" + clickedBtnID).addClass("btn-primary");
        if(clickedBtnID === 'filterSiteAvailableOn') {
            filterSitesAvailable = 1;
        } else if (clickedBtnID === 'filterSiteAvailableOff') {
            filterSitesAvailable = 0;
        } else {
            filterSitesAvailable = -1;
        }
    });
    $('#filterSiteAvailableDisabled').addClass("btn-primary");

    $('.filterSitePurchased').on('click', function(event) {
        event.preventDefault();
        $('.filterSitePurchased').removeClass("btn-primary");
        var clickedBtnID = $(this).attr('id');
        $("#" + clickedBtnID).addClass("btn-primary");
        if(clickedBtnID === 'filterSitePurchasedOn') {
            filterSitesPurchased = 1;
        } else if (clickedBtnID === 'filterSitePurchasedOff') {
            filterSitesPurchased = 0;
        } else {
            filterSitesPurchased = -1;
        }
    });
    $('#filterSitePurchasedDisabled').addClass("btn-primary");

    $('.saveSwitch').on('click', function(event) {
        reloadActiveTab();
    });

    $('.filterSiteHolds').on('keyup change', function(event) {
        event.preventDefault();
        resetSiteHoldsPagination();
        clearTimeout(autosaveNotification);
        autosaveNotification = setTimeout(function () {
            reloadActiveTab();
        }, 500);
    });

    $('#addFischerSection').on('click', function () {
        populateFischerSectionModal();
        $("#updateFischerSectionModal").modal('show');
    });
    $('#addLegalSection').on('click', function () {
        $('#currentLegalSection-id').val(0);
        populateLegalSectionModal();
        $("#updateLegalSectionModal").modal('show');
    });
    $('#addSite').on('click', function () {
        populateSiteModalAdd(communityId);
        $("#addSiteModal").modal('show');
        $(".addSiteRange").hide();
    });
    $('#addSiteAttached').on('click', function () {
        populateSiteAttachedModalAdd(communityId);
        $("#addSiteAttachedModal").modal('show');
    });
    $('#addSiteHold').on('click', function () {
        populateSiteHoldModal();
        $("#updateSiteHoldModal").modal('show');
    });

    $(document).on('click', '#loginButton', function() {
        $.getJSON("/js/config.json", function(json) {
            window.sessionStorage.removeItem("accessToken");
            window.sessionStorage.removeItem("tokenType");
            window.sessionStorage.removeItem("expiresIn");
            window.sessionStorage.removeItem("expiresDate");
            window.sessionStorage.removeItem("state");
            window.sessionStorage.removeItem("IDToken");

            $.ajax({
                type: "POST",
                url: json.oauthLogout,
                xhrFields: {
                    withCredentials: true
                },
                crossDomain: true
            }).fail(function() {
                // Route to login. Have oauth handle if failure.
                window.location.href = "/";
            });
        });
    });

    $(document).on('change', '#toggleViewBtn', function() {
        var defaultSelect = [];
        var selectionValue = $(this).val();

        defaultSelect.push(selectionValue);

        document.cookie ="defaultCommunitiesView=" + JSON.stringify(defaultSelect) + "; expires=Thu, 18 Dec 2033 12:00:00 UTC;";
    
        location.reload();
    });
    // End communities main ready
});
// End Document Ready