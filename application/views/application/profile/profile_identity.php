
<div class="border rounded p-3">
	<h5>Account Identity</h5>
	<hr class="hr">
	<div class="row">
		<div class="col-md-6">
			<div class="border rounded p-3">
				<h6>Change Identity</h6>
				<hr class="hr">
				<form id="form-profile" autocomplete="off">
					<div class="mb-3 position-relative">
						<label class="form-label" for="field-fullname">Fullname</label>
						<input type="text" name="fullname" id="field-fullname" class="form-control checkup_fullname" placeholder="your name">
					</div>
					<div class="mb-3 position-relative">
						<label class="form-label" for="field-phone">Phone</label>
						<input type="text" name="phone" id="field-phone" class="form-control checkup_phone" placeholder="your phone number" autocomplete="off">
					</div>
					<div class="mb-3 position-relative">
						<label class="form-label" for="field-address">Address</label>
						<textarea name="address" class="form-control checkup_address" id="field-address" placeholder="your address"></textarea>
					</div>
			
					<hr class="hr">
					<button type="submit" class="btn btn-primary w-md">Update</button>
				</form>
			</div>
		</div>
		<div class="col-md-6">
			<div class="border rounded p-3">
				<h6>Photo profile</h6>
				<hr class="hr">
				<form id="form-upload-picture" enctype="multipart/form-data">
					<input type="file" name="file_docs" class="dropify" id="field-upload-docs">
					<hr class="hr">
					<button type="submit" class="btn btn-primary waves-effect btn-label waves-light w-md" id="submit-template-doc-file">
						<i class="bx bxs-cloud-upload label-icon"></i> 
						<span>Upload</span>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
