<form id="set-limitations-form" class="form large-right-form">
	<div class="col-md-12">
		<div class="errors-box">

		</div>
		<div class="form-group">
			<label for="community-limitations">Community</label>
			<select id="limitations-community" name="limitations-community" class="selectpicker form-control" data-live-search="true">
				<option value="">-- Select Community --</option>
			</select>
		</div>
		<div class="form-group">
			<label for="community-fischer">Fischer Section</label>
			<select id="limitations-fischer" name="limitations-fischer" class="selectpicker form-control" data-live-search="true">
			</select>
		</div>
		<div class="form-group">
			<label for="limitations-legal">Legal Section</label>
			<select id="limitations-legal" name="limitations-legal" class="selectpicker form-control" data-live-search="true">
			</select>
		</div>
		<div class="form-group">
			<label for="site-select">Site Selection</label>
			<select id="site-select" name="site-select" class="selectpicker form-control" multiple data-actions-box="true" data-live-search="true">
			</select>
		</div>
	</div><br/>
	<div class="col-md-12 col-wrap-margin">
		<div class="form-group">
			<!-- This will be a checkbox list -->
			<label for="limitation-select">Limitations</label>
			<select id="limitation-select" name="limitation-select" class="selectpicker form-control" multiple data-actions-box="true" data-live-search="true">
			</select>
		</div>
	</div>
	<div class="col-md-12 col-wrap-margin">
		<div class="form-group">
			<button class="btn btn-primary submit-right-form">Submit</button>
		</div>
	</div>
</form>
<script>
	if(typeof renderCommunitySelections === "undefined") {
		$.getScript("js/component_js/forms/addLimitations.js");
	}
	else {
		$(document).ready(function() {
			onLoadFunction(communities, $("#communityId").val(), $("#fischerSectionId").val(), $("#legalSectionId").val());
		});
	}
</script>