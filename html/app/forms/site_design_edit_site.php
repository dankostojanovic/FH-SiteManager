<form id="update-site-form" class="form-horizontal" novalidate="novalidate">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#home">General</a></li>
		<li><a data-toggle="tab" href="#menu1">Notes</a></li>
		<li><a data-toggle="tab" href="#menu2">Limitations</a></li>
	</ul>

	<div class="tab-content" style="margin-top: 20px;">
		<div id="home" class="tab-pane fade in active">
			<input type="hidden" name="id" id="currentSiteUpdate-id">

			<fieldset>
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-siteNumber" class="col-md-6 col-sm-6 col-xs-12">Site Number</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="siteNumber" id="currentSiteUpdate-siteNumber">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-jobNumber" class="col-md-6 col-sm-6 col-xs-12">Job Number</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="jobNumber" id="currentSiteUpdate-jobNumber">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-communityId" class="col-md-6 col-sm-6 col-xs-12">Community</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<select name="communityId" class="form-control" id="currentSiteUpdate-communityId"></select>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-fischerSectionId" class="col-md-6 col-sm-6 col-xs-12">Fischer Section</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<select name="fischerSectionId" class="form-control" id="currentSiteUpdate-fischerSectionId"></select>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-availableFlag" class="col-md-6 col-sm-6 col-xs-12">Available</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<select name="availableFlag" class="form-control" id="currentSiteUpdate-availableFlag">
	                            <option value="true">Yes</option>
	                            <option value="false">No</option>
	                        </select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-legalSectionId" class="col-md-6 col-sm-6 col-xs-12">Legal Section</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<select name="legalSectionId" class="form-control" id="currentSiteUpdate-legalSectionId"></select>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-specLevelId" class="col-md-6 col-sm-6 col-xs-12">Spec Level</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<select name="specLevelId" class="form-control" id="currentSiteUpdate-specLevelId"></select>
						</div>
					</div>
				</div>
			</fieldset>
			
			<hr />
			
			<fieldset class="attached">
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-buildingType" class="col-md-6 col-sm-6 col-xs-12">Attached Building Type</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="attachedBuildingType" id="currentSiteUpdate-buildingType">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-buildingNumber" class="col-md-6 col-sm-6 col-xs-12">Attached Building Number</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="attachedBuildingNumber" id="currentSiteUpdate-buildingNumber">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-fireSuppressRoomAddress" class="col-md-6 col-sm-6 col-xs-12">Fire Suppression Room Address</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="fireSuppressRoomAddress" id="currentSiteUpdate-fireSuppressRoomAddress">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-unitNumber" class="col-md-6 col-sm-6 col-xs-12">Attached Unit Number</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="unitNumber" id="currentSiteUpdate-unitNumber">
						</div>
					</div>
				</div>
			</fieldset>
			
			<hr class="attached"/>

			<fieldset>
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-gcJobNumber" class="col-md-6 col-sm-6 col-xs-12">GC Job Number</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="gcJobNumber" id="currentSiteUpdate-gcJobNumber">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-purchaser" class="col-md-6 col-sm-6 col-xs-12">Purchaser</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="purchaser" id="currentSiteUpdate-purchaser">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-purchaseDate" class="col-md-6 col-sm-6 col-xs-12">Purchase Date</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="purchaseDate" id="currentSiteUpdate-purchaseDate">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-vendorId" class="col-md-6 col-sm-6 col-xs-12">Vendor ID</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="vendorId" id="currentSiteUpdate-vendorId">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-checkNumber" class="col-md-6 col-sm-6 col-xs-12">Check #</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="checkNumber" id="currentSiteUpdate-checkNumber">
						</div>
					</div>
				</div>
			</fieldset>

			<hr />

			<fieldset>
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-siteCost" class="col-md-6 col-sm-6 col-xs-12">Site Cost</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="siteCost" id="currentSiteUpdate-siteCost">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-sitePremium" class="col-md-6 col-sm-6 col-xs-12">Site Premium</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="sitePremium" id="currentSiteUpdate-sitePremium">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-estConstCost" class="col-md-6 col-sm-6 col-xs-12">Extra Construction Cost</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="estConstCost" id="currentSiteUpdate-estConstCost">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>
			</fieldset>

			<hr />

			<fieldset class="attached">
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-viewPremium" class="col-md-6 col-sm-6 col-xs-12">View Premium</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="viewPremium" id="currentSiteUpdate-viewPremium">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-garagePremium" class="col-md-6 col-sm-6 col-xs-12">Garage Premium</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="garagePremium" id="currentSiteUpdate-garagePremium">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-walkOutPremium" class="col-md-6 col-sm-6 col-xs-12">WalkOut Premium</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="walkOutPremium" id="currentSiteUpdate-walkOutPremium">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>
			</fieldset>
			
			<hr class="attached" />

			<fieldset>
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-address1" class="col-md-6 col-sm-6 col-xs-12">Address</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="address1" id="currentSiteUpdate-address1">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-address2" class="col-md-6 col-sm-6 col-xs-12">Address 2</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="address2" id="currentSiteUpdate-address2">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-width" class="col-md-6 col-sm-6 col-xs-12">Lot Width (in ft.)</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="width" id="currentSiteUpdate-width">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-buildableWidth" class="col-md-6 col-sm-6 col-xs-12">Buildable Width</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="buildableWidth" id="currentSiteUpdate-buildableWidth">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-depth" class="col-md-6 col-sm-6 col-xs-12">Lot Depth (in ft.)</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="depth" id="currentSiteUpdate-depth">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-buildableDepth" class="col-md-6 col-sm-6 col-xs-12">Buildable Depth</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="buildableDepth" id="currentSiteUpdate-buildableDepth">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-setback" class="col-md-6 col-sm-6 col-xs-12">Front Setback (in ft.)</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="setback" id="currentSiteUpdate-setback">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-siteRating" class="col-md-6 col-sm-6 col-xs-12">Site Rating</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="siteRating" id="currentSiteUpdate-siteRating">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-setback" class="col-md-6 col-sm-6 col-xs-12">
							<select name="tosOrF" class="form-control" id="currentSiteUpdate-tosOrF">
                                <option value="TOF">TOF (in ft.)</option>
                                <option value="TOS">TOS (in ft.)</option>
                            </select>
						</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="topOf" id="currentSiteUpdate-topOf">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-siteRating" class="col-md-6 col-sm-6 col-xs-12">
							<select name="aOrBCurb" class="form-control" id="currentSiteUpdate-aOrBCurb">
                                <option value="A">Above Curb (in ft.)</option>
                                <option value="B">Below Curb (in ft.)</option>
                            </select>
						</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="aboveCurb" id="currentSiteUpdate-aboveCurb">
						</div>
					</div>
				</div>
			</fieldset>

			<hr />

			<fieldset>
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-garageLocation" class="col-md-6 col-sm-6 col-xs-12">Garage Location (L/R)</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="garageLocation" id="currentSiteUpdate-garageLocation">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-garageEntry" class="col-md-6 col-sm-6 col-xs-12">Garage Entry (F/S/R)</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="garageEntry" id="currentSiteUpdate-garageEntry">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-rearExit" class="col-md-6 col-sm-6 col-xs-12">Rear Exit (U/L/M)</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="rearExit" id="currentSiteUpdate-rearExit">
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-basementDescription" class="col-md-6 col-sm-6 col-xs-12">Basement Description (B/S/E/L)</label>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" name="basementDescription" id="currentSiteUpdate-basementDescription">
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div id="menu1" class="tab-pane fade">
			<fieldset>
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-generalNotes" class="col-md-4 col-sm-4 col-xs-12">General Notes</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="7" class="custom-scroll form-control" name="generalNotes" id="currentSiteUpdate-generalNotes"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-constructionNotes" class="col-md-4 col-sm-4 col-xs-12">Construction Notes</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="7" class="custom-scroll form-control" name="constructionNotes" id="currentSiteUpdate-constructionNotes"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-sewerTap" class="col-md-4 col-sm-4 col-xs-12">Sewer Tap</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="3" class="custom-scroll form-control" name="sewerTap" id="currentSiteUpdate-sewerTap"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-waterTap" class="col-md-4 col-sm-4 col-xs-12">Water Tap</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="3" class="custom-scroll form-control" name="waterTap" id="currentSiteUpdate-waterTap"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-utilityEasement" class="col-md-4 col-sm-4 col-xs-12">Utility Easement</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="5" class="custom-scroll form-control" name="utilityEasement" id="currentSiteUpdate-utilityEasement"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-sanitaryEasement" class="col-md-4 col-sm-4 col-xs-12">Sanitary Easement</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="5" class="custom-scroll form-control" name="sanitaryEasement" id="currentSiteUpdate-sanitaryEasement"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-stormEasement" class="col-md-4 col-sm-4 col-xs-12">Storm Easement</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="5" class="custom-scroll form-control" name="stormEasement" id="currentSiteUpdate-stormEasement"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-waterEasement" class="col-md-4 col-sm-4 col-xs-12">Water Easement</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="5" class="custom-scroll form-control" name="waterEasement" id="currentSiteUpdate-waterEasement"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-otherEasement" class="col-md-4 col-sm-4 col-xs-12">Other Easement</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="7" class="custom-scroll form-control" name="otherEasement" id="currentSiteUpdate-otherEasement"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-sanitaryInvert" class="col-md-4 col-sm-4 col-xs-12">Sanitary Invert</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<input type="text" class="form-control" name="sanitaryInvert" id="currentSiteUpdate-sanitaryInvert">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-transformers" class="col-md-4 col-sm-4 col-xs-12">Transformers</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="7" class="custom-scroll form-control" name="transformers" id="currentSiteUpdate-transformers"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-catchBasin" class="col-md-4 col-sm-4 col-xs-12">Catch Basin</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="3" class="custom-scroll form-control" name="catchBasin" id="currentSiteUpdate-catchBasin"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-sanitaryManhole" class="col-md-4 col-sm-4 col-xs-12">Sanitary Manhole</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="3" class="custom-scroll form-control" name="sanitaryManhole" id="currentSiteUpdate-sanitaryManhole"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-stormManhole" class="col-md-4 col-sm-4 col-xs-12">Storm Manhole</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="3" class="custom-scroll form-control" name="stormManhole" id="currentSiteUpdate-stormManhole"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-fireHydrant" class="col-md-4 col-sm-4 col-xs-12">Fire Hydrant</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="3" class="custom-scroll form-control" name="fireHydrant" id="currentSiteUpdate-fireHydrant"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-secondaryPedestal" class="col-md-4 col-sm-4 col-xs-12">Secondary Pedestal</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<textarea rows="3" class="custom-scroll form-control" name="secondaryPedestal" id="currentSiteUpdate-secondaryPedestal"></textarea>
						</div>
					</div>

					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-drainagePattern" class="col-md-4 col-sm-4 col-xs-12">Drain Pattern</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<input type="text" class="form-control" name="drainagePattern" id="currentSiteUpdate-drainagePattern">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<label for="currentSiteUpdate-sidewalks" class="col-md-4 col-sm-4 col-xs-12">Sidewalks</label>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<input type="text" class="form-control" name="sidewalks" id="currentSiteUpdate-sidewalks">
						</div>
					</div>
				</div>
			</fieldset>
		</div>
		<div id="menu2" class="tab-pane fade">
			<fieldset>
				<div class="row">
					<div class="form-group col-md-12">
						<label for="currentSiteUpdate-limitation">Site Limitations</label>
						<select id="currentSiteUpdate-limitation" name="limitation" class="selectpicker form-control" multiple data-actions-box="true" data-live-search="true">
						</select>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</form>

<div class="container-fluid">
	<div class="row">
		<button class="btn btn-default pull-right cancel-form" style="margin-right: 20px;">Cancel</button>
		<button class="btn btn-primary pull-right submit-form" style="margin-right: 10px;">Update</button>
	</div>
</div>