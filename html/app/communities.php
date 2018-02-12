<?php

require_once("../init.php");

require_once("inc/init.php");
?>

<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/handsontable.full.min.css">
<link data-jsfiddle="common" rel="stylesheet" media="screen" href="dist/pikaday/pikaday.css">
<link rel="stylesheet" href="css/bootstrap-select-1.12.4/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="css/floating-modal/floating-modal.css" />
<script src="css/bootstrap-select-1.12.4/dist/js/bootstrap-select.min.js"></script>
<script data-jsfiddle="common" src="dist/pikaday/pikaday.js"></script>
<script data-jsfiddle="common" src="dist/moment/moment.js"></script>
<script data-jsfiddle="common" src="dist/zeroclipboard/ZeroClipboard.js"></script>
<script data-jsfiddle="common" src="dist/numbro/numbro.js"></script>
<script data-jsfiddle="common" src="dist/numbro/languages.js"></script>
<script data-jsfiddle="common" src="dist/handsontable.full.min.js"></script>
<script src="js/bootpag/jquery.bootpag.min.js"></script>
<script type="text/javascript" src="js/component_js/global.js"></script>
<script type="text/javascript" src="js/sweetalerts2/sweetalerts.js"></script>
<!-- Top Selections Section Start -->
<div class="row">
	<div class="col-sm-2">
		<div class="icon-addon addon-md">
			<select class='form-control input-md' id='divisionId' name='divisionId'>
			</select>
			<label for="divisionId" class="fa fa-sitemap" rel="tooltip" title="Division"></label>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="icon-addon addon-md">
			<select class='form-control input-md' id='communityId' name='communityId'>
			</select>
			<label for="communityId" class="fa fa-sitemap" rel="tooltip" title="Community"></label>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="icon-addon addon-md">
			<select class='form-control input-md' id='fischerSectionId' name='fischerSectionId'><option value='0'>Select Fischer Section</option></select>
			<label for="fischerSectionId" class="fa fa-map-pin" rel="tooltip" title="Fischer Sections"></label>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="icon-addon addon-md">
			<select class='form-control input-md' id='legalSectionId' name='legalSectionId'><option value='0'>Select Legal Section</option></select>
			<label for="legalSectionId" class="fa fa-map-pin" rel="tooltip" title="Legal Sections"></label>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="icon-addon addon-md">
			<button type="reset" id="btnResetFilters" class="btn btn-primary">
                Reset
            </button>
        </div>
    </div>
    <div class="col-sm-2" style="line-height: 32px; text-align: right;">
        <span id="syssync_num" style="font-size: 16px;"></span>
        <i id="syssync_icon" class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
    </div>
</div>
<!-- Top Selections Section End -->
<br />
<!-- Tabs Section Start -->
<ul id="myTab1" class="nav nav-tabs bordered">
	<li class="active" id="communitiesTab">
		<a href="#s1" data-toggle="tab"> <i class="fa fa-fw fa-lg fa-users"></i> Communities </a>
	</li>
	<li id="fischerSectionsTab">
		<a href="#s2" data-toggle="tab"><i class="fa fa-fw fa-lg fa-sitemap"></i> Fischer Sections</a>
	</li>
	<li id="legalSectionsTab">
		<a href="#s3" data-toggle="tab"><i class="fa fa-fw fa-lg fa-sitemap"></i> Legal Sections</a>
	</li>
	<li id="sitesTab">
		<a href="#s4" data-toggle="tab" id="siteTablink"><i class="fa fa-fw fa-lg fa-map-pin"></i> Sites</a>
	</li>
<!--	<li id="siteHoldsTab">
		<a href="#s5" data-toggle="tab" id="siteHoldTablink"><i class="fa fa-fw fa-lg fa-map-pin"></i> Site Holds</a>
	</li>-->
	<li class="pull-right">
        <select id="toggleViewBtn" class="form-control" style="width: 150px; display: inline-block; margin-right: 20px; margin-top: 3px;">
            <option value="SiteDesign">Site Design</option>
            <option value="LandAdmin">Land Admin</option>
        </select>
	</li>
</ul>
<!-- Tabs Section End -->

<!-- Content Section Start -->
<div id="myTabContent1" class="tab-content padding-10">
	<div class="tab-pane fade in active" id="s1">
        <div class="row">
            <div class="col-xs-3">
                Show All
                <span class="onoffswitch">
                    <input type="checkbox" name="chCommunitiesShowInactive" id="chCommunitiesShowInactive" class="onoffswitch-checkbox">
                    <label class="onoffswitch-label" for="chCommunitiesShowInactive">
                        <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </span>
            </div>
            <div class="col-xs-3" id="communityPagination"></div>
            <div class="col-xs-1 well well-sm" id="communityTotalRecords"></div>
        </div>
        <div class="row">
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterCommunities" type="text" name="filterCommunityCode" id="filterCommunityCode" placeholder="Community Code">
				</label>
			</div>
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterCommunities" type="text" name="filterCommunityName" id="filterCommunityName" placeholder="Community Name">
				</label>
			</div>
		</div>
		<div id="communityContainer" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: visible; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s2">
        <div class="row">
            <div class="col-xs-3">
                <button id="customViewFischerSectionOn" class="btn btn-success" style="display: none;">Default View</button>
                <button id="customViewFischerSectionOff" class="btn btn-success" style="display: none;">Custom View</button>
                Save on Change
                <span class="onoffswitch">
                    <input type="checkbox" name="chFischerSectionSave" id="chFischerSectionSave" class="onoffswitch-checkbox saveSwitch">
                    <label class="onoffswitch-label" for="chFischerSectionSave">
                        <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </span>
            </div>
            <div class="col-xs-3" id="fischerSectionPagination"></div>
            <div class="col-xs-1 well well-sm" id="fischerSectionTotalRecords"></div>
            <div class="col-xs-2">
                <button class="btn btn-primary" id="addFischerSection">Create Fischer Section</button>
            </div>
        </div>
		<div class="row">
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterFischerSections" type="text" name="filterFischerSectionName" id="filterFischerSectionName" placeholder="Fischer Section Name">
				</label>
			</div>
		</div>
		<div id="fischerSectionContainer" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: visible; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s3">
        <div class="row">
            <div class="col-xs-3">
                <button id="customViewLegalSectionOn" class="btn btn-success" style="display: none;">Default View</button>
                <button id="customViewLegalSectionOff" class="btn btn-success" style="display: none;">Custom View</button>
                Save on Change
                <span class="onoffswitch">
                    <input type="checkbox" name="chLegalSectionSave" id="chLegalSectionSave" class="onoffswitch-checkbox saveSwitch">
                    <label class="onoffswitch-label" for="chLegalSectionSave">
                        <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </span>
            </div>
            <div class="col-xs-3" id="legalSectionPagination"></div>
            <div class="col-xs-1 well well-sm" id="legalSectionTotalRecords"></div>
            <div class="col-xs-2">
                <button class="btn btn-primary" id="addLegalSection">Create Legal Section</button>
            </div>
        </div>
		<div class="row">
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterLegalSections" type="text" name="filterLegalSectionCode" id="filterLegalSectionCode" placeholder="Legal Section Code">
				</label>
			</div>
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterLegalSections" type="text" name="filterLegalSectionPlatRecordingDate" id="filterLegalSectionPlatRecordingDate" placeholder="Plat Recording Date">
				</label>
			</div>
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterLegalSections" type="text" name="filterLegalSectionRecordedName" id="filterLegalSectionRecordedName" placeholder="Recorded Name">
				</label>
			</div>
		</div>
		<div id="legalSectionContainer" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: visible; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s4">
        <div class="row">
            <div class="col-xs-3">
                <button id="customViewSiteOn" class="btn btn-success" style="display: none;">Default View</button>
                <button id="customViewSiteOff" class="btn btn-success" style="display: none;">Custom View</button>
                Save on Change
                <span class="onoffswitch">
                    <input type="checkbox" name="chSiteSave" id="chSiteSave" class="onoffswitch-checkbox saveSwitch">
                    <label class="onoffswitch-label" for="chSiteSave">
                        <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </span>
            </div>
            <div class="col-xs-3" id="sitePagination"></div>
            <div class="col-xs-1 well well-sm" id="siteTotalRecords"></div>
            <div class="col-xs-1">
                <button class="btn btn-primary" id="addSite">Create Sites</button>
            </div>
            <div class="col-xs-1">
                <button class="btn btn-primary" id="addSiteLimitations" onclick="getCreateSiteLimitationsForm();">Add Limitations</button>
            </div>
            <div class="col-xs-1">
                <button class="btn btn-primary" id="addSiteAttached">Create Attached Sites</button>
            </div>
        </div>
		<div class="row">
			<div class="col-sm-2">
                <div class="btn-group">
                    <label class="input">Available
                        <button type="button" class="btn btn-default filterSites filterSiteAvailable" id="filterSiteAvailableDisabled">
                            All
                        </button>
                        <button type="button" class="btn btn-default filterSites filterSiteAvailable" id="filterSiteAvailableOn">
                            Yes
                        </button>
                        <button type="button" class="btn btn-default filterSites filterSiteAvailable" id="filterSiteAvailableOff">
                            No
                        </button>
                    </label>
                </div>
			</div>
			<div class="col-sm-2">
                <div class="btn-group">
                    <label class="input">Purchased
                        <button type="button" class="btn btn-default filterSites filterSitePurchased" id="filterSitePurchasedDisabled">
                            All
                        </button>
                        <button type="button" class="btn btn-default filterSites filterSitePurchased" id="filterSitePurchasedOn">
                            Yes
                        </button>
                        <button type="button" class="btn btn-default filterSites filterSitePurchased" id="filterSitePurchasedOff">
                            No
                        </button>
                    </label>
                </div>
			</div>
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterSites" type="text" name="filterSiteSiteNumber" id="filterSiteSiteNumber" placeholder="Site Number">
				</label>
			</div>
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterSites" type="text" name="filterSiteJobNumber" id="filterSiteJobNumber" placeholder="Job Number">
				</label>
			</div>
		</div>
		<div id="siteContainer" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: visible; width: 100%;"></div>
	</div>
	<div class="tab-pane fade" id="s5">
        <div class="row">
            <div class="col-xs-3">
                <button id="customViewSiteHoldOn" class="btn btn-success" style="display: none;">Custom View</button>
                <button id="customViewSiteHoldOff" class="btn btn-success" style="display: none;">Default View</button>
                Save on Change
                <span class="onoffswitch">
                    <input type="checkbox" name="chSiteHoldSave" id="chSiteHoldSave" class="onoffswitch-checkbox saveSwitch">
                    <label class="onoffswitch-label" for="chSiteHoldSave">
                        <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </span>
            </div>
            <div class="col-xs-3" id="siteHoldPagination"></div>
            <div class="col-xs-1 well well-sm" id="siteHoldTotalRecords"></div>
            <div class="col-xs-2">
                <button class="btn btn-primary" id="addSiteHold">Create Site Hold</button>
            </div>
        </div>
		<div class="row">
			<div class="col-sm-2">
				<label class="input">
					<input class="form-control filterSiteHolds" type="text" name="filterSiteHoldSiteNumber" id="filterSiteHoldSiteNumber" placeholder="Site Number">
				</label>
			</div>
			<div class="col-sm-2">
				<label class="input">
                    <select class="form-control filterSiteHolds" name="filterSiteHoldReason" id="filterSiteHoldReason">
                    </select>
				</label>
			</div>
		</div>
		<div id="siteHoldContainer" class="hot handsontable htRowHeaders htColumnHeaders" style="height: 650px; overflow: hidden; width: 100%;" data-originalstyle="height: 650px; overflow: visible; width: 100%;"></div>
	</div>
</div>

<!-- Update Fischer Section Modal -->
<div class="modal fade" id="updateFischerSectionModal" tabindex="-1" role="dialog" aria-labelledby="updateFischerSectionModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title">Fischer Section</h4>
            </div>

            <div class="modal-body no-padding">
                <form action="" id="update-fischerSection-form" class="smart-form" novalidate="novalidate">
                    <input type="hidden" name="id" id="currentFischerSection-id">
                    <fieldset>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Community</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append"></i>
                                        <select name="communityId" id="currentFischerSection-communityId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Fischer Section ID</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-sitemap"></i>
                                        <input type="text" name="name" id="currentFischerSection-name">
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Spec Level</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append"></i>
                                        <select name="specLevelId" id="currentFischerSection-specLevelId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                    </fieldset>

                    <footer>
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancel
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<!-- Update Legal Section Modal -->
<div class="modal fade" id="updateLegalSectionModal" tabindex="-1" role="dialog" aria-labelledby="updateLegalSectionModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title">Legal Section - DEFAULT VIEW</h4>
            </div>

            <div class="modal-body no-padding">
                <ul id="legalSectionUpdateModalTab" class="nav nav-tabs bordered">
                    <li class="active">
                        <a href="#legalSectionUpdateModalTab1" data-toggle="tab" aria-expanded="true">General </a>
                    </li>
                    <li class="">
                        <a href="#legalSectionUpdateModalTab2" data-toggle="tab" aria-expanded="false">Mortgages </a>
                    </li>
                </ul>
                <div id="legalSectionUpdateModalTabContent" class="tab-content padding-10">
                    <div id="legalSectionUpdateModalTab1" class="tab-pane fade active in">
                        <form action="" id="update-legalSection-form" class="smart-form" novalidate="novalidate">
                            <input type="hidden" name="id" id="currentLegalSection-id">
                            <fieldset>
                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Community</label>
                                        <div class="col col-4">
                                            <label class="select"> <i class="icon-append fa fa-book"></i>
                                                <select name="communityId" id="currentLegalSection-communityId">
                                                </select>
                                            </label>
                                        </div>
                                        <label class="label col col-2">Legal Section Code</label>
                                        <div class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-book"></i>
                                                <input type="text" name="name" id="currentLegalSection-name">
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Recorded Name</label>
                                        <div class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-book"></i>
                                                <input type="text" name="recordedName" id="currentLegalSection-recordedName">
                                            </label>
                                        </div>
                                        <label class="label col col-2">Section Phase Name</label>
                                        <div class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-book"></i>
                                                <input type="text" name="sectionPhaseName" id="currentLegalSection-sectionPhaseName">
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Plat Recording Information</label>
                                        <div class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="platRecordingBook" id="currentLegalSection-platRecordingBook">
                                            </label>
                                        </div>
                                        <label class="label col col-2">Plat Recording Date</label>
                                        <div class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-asterisk"></i>
                                                <input type="text" name="platRecordingDate" id="currentLegalSection-platRecordingDate">
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">County</label>
                                        <div class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="county" id="currentLegalSection-CommunityCity2Name" disabled="disabled">
                                            </label>
                                        </div>
                                        <label class="label col col-2">City</label>
                                        <div class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="city" id="currentLegalSection-CommunityCity1Name" disabled="disabled">
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">City Of Recordation</label>
                                        <div class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="cityOfRecordation" id="currentLegalSection-CommunityCityOfClerk" disabled="disabled">
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Legal Description</label>
                                        <div class="col col-10">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="legalDescription" id="currentLegalSection-legalDescription">
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Covenant Restriction</label>
                                        <div class="col col-10">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="covenantRestriction" id="currentLegalSection-covenantRestriction">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="label col col-2">Covenant Restriction 2</label>
                                        <div class="col col-10">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="covenantRestriction2" id="currentLegalSection-covenantRestriction2">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="label col col-2">Covenant Restriction 3</label>
                                        <div class="col col-10">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="covenantRestriction3" id="currentLegalSection-covenantRestriction3">
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Prior Deed Reference</label>
                                        <div class="col col-10">
                                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                                <input type="text" name="priorDeedReference" id="currentLegalSection-priorDeedReference">
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Comments</label>
                                        <div class="col col-10">
                                            <label class="textarea textarea-resizable">
                                                <textarea rows="3" class="custom-scroll" name="comments" id="currentLegalSection-comments"></textarea>
                                            </label>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <div class="row">
                                        <label class="label col col-2">Mortgage Information</label>
                                        <div class="col col-10">
                                            <div id="currentLegalSection-mortgageInformation">

                                            </div>
                                        </div>
                                    </div>
                                </section>

                            </fieldset>

                            <footer>
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Cancel
                                </button>
                            </footer>
                        </form>
                    </div>
                    <div id="legalSectionUpdateModalTab2" class="tab-pane fade">
                        <form action="" id="update-legalSection-mortgage-form" class="smart-form" novalidate="novalidate">
                            <input type="hidden" name="id" id="currentLegalSection-id">
                            <fieldset id="currentLegalSection-mortgageInformationUpdate">

                            </fieldset>
                            <fieldset id="currentLegalSection-mortgageInformationCreate">
                            </fieldset>

                            <footer>
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="button" id="addMortgage" class="btn btn-default">
                                    Add Mortgage
                                </button>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<!-- Update site Modal -->
<div class="modal fade" id="updateSiteModalLandAdmin" tabindex="-1" role="dialog" aria-labelledby="updateSiteModalLandAdminLabel" aria-hidden="true" style="display: none;">
</div>

<!-- Add Site Modal -->
<div class="modal fade" id="addSiteModal" tabindex="-1" role="dialog" aria-labelledby="addSiteModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title">Create Sites</h4>
            </div>

            <div class="modal-body no-padding">
                <form action="" id="add-site-form" class="smart-form" novalidate="novalidate">
                    <fieldset>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Community</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append fa fa-sitemap"></i>
                                        <select name="communityId" id="addSite-communityId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Fischer Section</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append"></i>
                                        <select name="fischerSectionId" id="addSite-fischerSectionId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Legal Section</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append"></i>
                                        <select name="legalSectionId" id="addSite-legalSectionId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Site Number</label>
                                <div class="col col-4">
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberCommunityCode" id="addSite-siteNumberCommunityCode">
                                    </label>
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberFirstTwo" id="addSite-siteNumberFirstTwo" placeholder="##" required>
                                    </label>
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberMiddleThreeStart" id="addSite-siteNumberMiddleThreeStart" placeholder="###" required>
                                    </label>
                                    <label class="input col col-3" style="padding: 0px">
                                        <input type="text" name="siteNumberLastFour" id="addSite-siteNumberLastFour" placeholder="####" required>
                                    </label>
                                </div>
                                <label class="label col col-1 addSiteRange">To</label>
                                <div class="col col-4 addSiteRange">
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberCommunityCode" id="addSite-siteNumberCommunityCodeTo">
                                    </label>
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberFirstTwo" id="addSite-siteNumberFirstTwoTo" placeholder="##" required>
                                    </label>
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberMiddleThreeEnd" id="addSite-siteNumberMiddleThreeEnd" placeholder="###" required>
                                    </label>
                                    <label class="input col col-3" style="padding: 0px">
                                        <input type="text" name="siteNumberLastFour" id="addSite-siteNumberLastFourTo" placeholder="####" required>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <div class="col col-2">&nbsp;
                                </div>
                                <label class="label col col-4"><a href="" id="addSiteRange" >Add Multiple</a></label>
                            </div>
                        </section>

                    </fieldset>

                    <footer>
                        <button type="submit" name="Submit" class="btn btn-primary">
                            Submit
                        </button>
                        <button type="button" name="Cancel" class="btn btn-default" data-dismiss="modal">
                            Cancel
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<!-- Add Site Attached Modal -->
<div class="modal fade" id="addSiteAttachedModal" tabindex="-1" role="dialog" aria-labelledby="addSiteAttachedModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title">Create Attached Sites - DEFAULT VIEW</h4>
            </div>

            <div class="modal-body no-padding">
                <form action="" id="add-siteAttached-form" class="smart-form" novalidate="novalidate">
                    <fieldset>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Community</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append fa fa-sitemap"></i>
                                        <select name="communityId" id="addSiteAttached-communityId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Fischer Section</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append"></i>
                                        <select name="fischerSectionId" id="addSiteAttached-fischerSectionId">
                                        </select>
                                    </label>
                                </div>
                                <label class="label col col-2">Legal Section</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append"></i>
                                        <select name="legalSectionId" id="addSiteAttached-legalSectionId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Site Number</label>
                                <div class="col col-4">
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberCommunityCode" id="addSiteAttached-siteNumberCommunityCode">
                                    </label>
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberFirstTwo" id="addSiteAttached-siteNumberFirstTwo" placeholder="##" required>
                                    </label>
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberMiddleThreeStart" id="addSiteAttached-siteNumberMiddleThree" placeholder="###" required>
                                    </label>
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberLastFour" id="addSiteAttached-siteNumberLastFour" placeholder="xxxx" disabled="disabled" value="0000">
                                    </label>
                                </div>
                                <label class="label col col-2">GCL Job Number</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-book"></i>
                                        <input type="text" name="gcJobNumber" id="addSiteAttached-gcJobNumber">
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Building</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append fa fa-book"></i>
                                        <select name="attachedBuildingCatalogId" id="addSiteAttached-attachedBuildingCatalogId">
                                        </select>
                                    </label>
                                </div>
                                <label class="label col col-2">Building Number</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-book"></i>
                                        <input type="text" name="buildingNumber" id="addSiteAttached-buildingNumber">
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Lot Number</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-book"></i>
                                        <input type="text" name="lotNumber" id="addSiteAttached-lotNumber">
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Purchaser</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append fa fa-book"></i>
                                        <select name="companyId" id="addSiteAttached-companyId">
                                        </select>
                                    </label>
                                </div>
                                <label class="label col col-2">Vendor ID</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-book"></i>
                                        <input type="text" name="vendorId" id="addSiteAttached-vendorId">
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Street Address Range</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-book"></i>
                                        <input type="text" name="streetAddressRange" id="addSiteAttached-streetAddressRange">
                                    </label>
                                </div>
                                <label class="label col col-2">Fire Suppress Room Address</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-book"></i>
                                        <input type="text" name="fireSuppressRoomAddress" id="addSiteAttached-fireSuppressRoomAddress">
                                    </label>
                                </div>
                            </div>
                        </section>
                    </fieldset>

                    <div id="siteAttachedForm-sitesFieldset">

                    </div>

                    <footer>
                        <button type="button" id="siteAttachedForm-continue" class="btn btn-primary">
                            Continue
                        </button>
                        <button type="submit" id="siteAttachedForm-submit" name="Submit" class="btn btn-primary" style="display: none;">
                            Submit
                        </button>
                        <button type="button" name="Cancel" class="btn btn-default" data-dismiss="modal">
                            Cancel
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<!-- Update Site Hold Modal -->
<div class="modal fade" id="updateSiteHoldModal" tabindex="-1" role="dialog" aria-labelledby="updateSiteHoldModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title">Site Hold</h4>
            </div>

            <div class="modal-body no-padding">
                <form action="" id="update-siteHold-form" class="smart-form" novalidate="novalidate">
                    <input type="hidden" name="id" id="currentSiteHold-id">
                    <fieldset>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Community</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append"></i>
                                        <select name="communityId" id="currentSiteHold-communityId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Site Number</label>
                                <div class="col col-4">
                                    <label class="input col col-3" style="padding-left: 0px">
                                        <input type="text" name="siteNumberCommunityCode" id="currentSiteHold-siteNumberCommunityCode">
                                    </label>
                                    <label class="input col col-9" style="padding: 0px">
                                        <div class="ui-widget">
                                            <input type="text" name="siteNumberNumbers" id="currentSiteHold-siteNumberNumbers" required>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Hold Reason</label>
                                <div class="col col-4">
                                    <label class="select"> <i class="icon-append"></i>
                                        <select name="holdReasonId" id="currentSiteHold-holdReasonId">
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Hold Requester</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-sitemap"></i>
                                        <input type="text" name="holdVendorId" id="currentSiteHold-holdVendorId" class="filterVendorId" />
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Hold Date</label>
                                <div class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-sitemap"></i>
                                        <input type="text" name="holdDate" id="currentSiteHold-holdDate" />
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="row">
                                <label class="label col col-2">Hold Notes</label>
                                <div class="col col-4">
                                    <label class="textarea textarea-resizable">
                                        <textarea rows="3" class="custom-scroll" name="holdNotes" id="currentSiteHold-holdNotes"></textarea>
                                    </label>
                                </div>
                            </div>
                        </section>

                    </fieldset>

                    <footer>
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancel
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<div id ="customCodeHolder"></div>
<div class="sapphire_fade"></div>
<div class="sapphire_loader">
    <img src="img/sapphire.gif" />
</div>
<!-- Content Section End -->
<script type="text/javascript" src="js/component_js/communities/communities.js"></script>
<script type="text/javascript" src="js/floating-modal/floating-modal.js"></script>
