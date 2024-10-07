<div class="border rounded p-3">
	<h5>Layout Settings</h5>
	<hr class="hr">
	<form id="form-layout-settings">
		<div class="row mb-4">
			<label class="col-sm-2 col-form-label">Layout Mode</label>
			<div class="col-sm-10 pt-2">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="layout" id="layout-vertical" value="vertical">
					<label class="form-check-label" for="layout-vertical">Vertical</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="layout" id="layout-horizontal" value="horizontal">
					<label class="form-check-label" for="layout-horizontal">Horizontal</label>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<label class="col-sm-2 col-form-label">Theme Mode</label>
			<div class="col-sm-10 pt-2">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="layout-mode" id="layout-mode-light" value="light">
					<label class="form-check-label" for="layout-mode-light">Light</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="layout-mode" id="layout-mode-dark" value="dark">
					<label class="form-check-label" for="layout-mode-dark">Dark</label>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<label class="col-sm-2 col-form-label">Top Bar</label>
			<div class="col-sm-10 pt-2">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="topbar-color" id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
					<label class="form-check-label" for="topbar-color-light">Light</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="topbar-color" id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
					<label class="form-check-label" for="topbar-color-dark">Dark</label>
				</div>
			</div>
		</div>
		<div class="row mb-4">
			<label class="col-sm-2 col-form-label">Side Bar</label>
			<div class="col-sm-10 pt-2">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="sidebar-color" id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
					<label class="form-check-label" for="sidebar-color-light">Light</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="sidebar-color" id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
					<label class="form-check-label" for="sidebar-color-dark">Dark</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-2"></div>
			<div class="col-sm-10">
				<button type="submit" class="btn btn-primary w-md">Update</button>
			</div>
		</div>
	</form>
</div>
