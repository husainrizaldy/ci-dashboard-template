<div class="border rounded p-3">
	<h5>Account Settings</h5>
	<hr class="hr">

	<div class="row">
		<div class="col-md-6">
			<div class="border rounded p-3">
				<h6>Account Email</h6>
				<hr class="hr">
				<form id="form-update-email">
					<div class="position-relative">
						<label class="form-label" for="fe-email">Change Email</label>
						<div class="input-group mb-3">
							<input type="hidden" name="old_value_email" class="form-control">
							<input type="email" name="email" id="fe-email" class="form-control checkup_email" placeholder="Email">
							<button class="btn btn-primary" type="submit">Update</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-6">
			<div class="border rounded p-3">
				<h6>Change Password</h6>
				<hr class="hr">
				<form id="form-change-password">
					<div class="mb-3 position-relative">
						<label class="form-label" for="current-password">Current password</label>
						<input type="password" name="current_password" class="form-control checkup_current_password" id="current-password" autocomplete="current-password">
					</div>
			
					<div class="mb-3 position-relative">
						<label class="form-label" for="new-password">New password</label>
						<input type="password" name="new_password" class="form-control checkup_new_password" id="new-password" autocomplete="new-password">
					</div>
			
					<div class="mb-3 position-relative">
						<label class="form-label" for="confirm-password">Re-type new password</label>
						<input type="password" name="confirm_password" class="form-control checkup_confirm_password" id="confirm-password" autocomplete="new-password">
					</div>
					
					<button type="submit" class="btn btn-primary w-md">Update</button>
				</form>
			</div>
		</div>
	</div>
</div>
