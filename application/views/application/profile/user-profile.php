<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">User Profile</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Application</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
	<div class="col-md-3">
		<div class="card">
			<div class="card-body">
				<div class="row border rounded p-3 mb-3" id="account-header-display">
					<div class="col-12 mb-3 text-center">
						<img src="<?php echo base_url('assets/images/users/default.png') ?>" alt="user photo" height="100" class="rounded d-img-profile">
					</div>
					<div class="col-12 text-center">
						<h4 class="mb-1 d-account-fullname text-capitalize font-size-16">-</h4>
						<p class="mb-0 d-account-role font-size-14">-</p>
					</div>
				</div>
				<div class="nav flex-column nav-pills" id="v-pills-users-profile-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link mb-2 active" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">
							<span class="d-inline-block"><i class="mdi mdi-account-details-outline me-1"></i> Identity</span>
						</a>
						<a class="nav-link mb-2" id="v-pills-account-tab" data-bs-toggle="pill" href="#v-pills-account" role="tab" aria-controls="v-pills-account" aria-selected="true">
							<span class="d-inline-block"><i class="mdi mdi-account-cog-outline me-1"></i> Account</span>
						</a>
						<a class="nav-link mb-2" id="v-pills-layout-tab" data-bs-toggle="pill" href="#v-pills-layout" role="tab" aria-controls="v-pills-layout" aria-selected="true">
							<span class="d-inline-block"><i class="mdi mdi-desktop-mac-dashboard me-1"></i> Layout</span>
						</a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-9">
		<div class="card">
			<div class="card-body">
				<div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
					<div class="tab-pane fade active show" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
						<?php include 'profile_identity.php'; ?>
					</div>
					<div class="tab-pane fade" id="v-pills-account" role="tabpanel" aria-labelledby="v-pills-account-tab">
						<?php include 'change_password.php'; ?>
					</div>
					<div class="tab-pane fade" id="v-pills-layout" role="tabpanel" aria-labelledby="v-pills-layout-tab">
						<?php include 'layout_settings.php'; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<script>
$(document).ready(function() {
    const REDIRECT_URL  = "<?php echo BASE_URL('apps/profile') ?>";
    const SERVICE_URL   = "<?php echo BASE_URL('apps/profile/app-profile-service') ?>";
    const XTOKEN        = "<?php echo $this->session->xtoken; ?>";
    const IMG_PROFILE   = "<?php echo IMG_PROFILE; ?>";

    let account_header	= $('#account-header-display');
    let form_user       = $('#form-profile');
    let form_settings   = $('#form-layout-settings');
    let form_email   	= $('#form-update-email');
    let form_password   = $('#form-change-password');
    
	restrictInput('#field-fullname', 'ls');
	restrictInput('#field-phone', 'n');

    usersData();
    function usersData() 
    {
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'get_profile_data',
                xtoken: XTOKEN,
            },
        }).done(function(response){
            if (response.status) {
                let obj = response.result;
				// header profile
				account_header.find('.d-account-fullname').text(obj.fullname);
				account_header.find('.d-account-role').text(obj.role_name);
				account_header.find('.d-img-profile').attr('src', IMG_PROFILE + obj.picture);
                // form identity
                form_user.find("input[name='fullname']").val(obj.fullname);
                form_user.find("input[name='phone']").val(obj.phone);
                form_user.find("textarea[name='address']").val(obj.address);
                // form email
				form_email.find("input[name='old_value_email']").val(obj.email);
				form_email.find("input[name='email']").val(obj.email);
                // form setting
                form_settings.find('input[name="layout"][value="'+ obj.theme_layout +'"]').prop('checked', true);
                form_settings.find('input[name="layout-mode"][value="'+ obj.theme_mode +'"]').prop('checked', true);
                form_settings.find('input[name="topbar-color"][value="'+ obj.topbar +'"]').prop('checked', true);
                form_settings.find('input[name="sidebar-color"][value="'+ obj.sidebar +'"]').prop('checked', true);
            }
        }).fail(function(error){
			form_user.find(':input').prop('disabled', true);
			form_email.find(':input').prop('disabled', true);
            form_settings.find(':input').prop('disabled', true);
        });
    }

	let inputDropify = $('#field-upload-docs');
    inputDropify.dropify({
        messages: {
            'default': 'Pilih foto',
            'replace': 'Ganti file',
            'remove':  'Hapus',
            'error':   'Ooops, terjadi kesalahan.'
        },
        allowedFileExtensions: ['png', 'jpg'],
        maxFileSize: '2M'
    });

	$(document).on('submit', '#form-upload-picture', function(e){
        e.preventDefault();
        let docs = $('#field-upload-docs')[0].files[0];
        if (!docs) {
            toastr.warning('tidak ada foto yang dipilih','peringatan');
            return;
        }
        let formdata = new FormData();
            formdata.append("token", 'upload_picture');
            formdata.append("xtoken", XTOKEN);
            formdata.append("file_docs", docs);
		
			$.ajax({
					url:SERVICE_URL,
					method:"POST",
					cache: false,
					contentType: false,
					processData: false,
					data:formdata,
					beforeSend: function() {
						loadingAlert('wait')
					}
				}).done(function(data){
					Swal.close();
					if (data.status) {
						$(".dropify-clear").trigger("click");
						toastr.success(data.message.body,data.message.title);
						usersData();
					}
				}).fail(function (xhr, status, error) {
					Swal.close();
					handleErrorCallback('toastr', jqXHR, true, REDIRECT_URL);
				});
    });

    form_user.on('submit',function(e){
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'save_profile',
                xtoken: XTOKEN,
                datalog:formData
            },
        }).done(function(data){
            if (data.status) {
				toastr.success(data.message.body,data.message.title);
				usersData();
            } else {
                if (data.error_type == 'error-validation') {
					formElementValidationWithTimeout(data, form_user, 'checkup_', 'position-relative', 'tooltip');
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            handleErrorCallback('toastr',jqXHR);
        });
    });

	form_email.on('submit',function(e){
        e.preventDefault();
        let formData = $(this).serialize();

		let params = new URLSearchParams(formData);
		let oldValue = params.get('old_value_email');
		for (let [key, value] of params.entries()) {
			if (key !== 'old_value_email') { 
				if (oldValue === decodeURIComponent(value).trim()) {
					toastr.warning('Anda tidak membuat perubahan','No change!');
					return;
				}
			}
		}
		params.delete('old_value_email');
		let dataElement = params.toString();

        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'update_email',
                xtoken: XTOKEN,
                datalog:dataElement
            },
        }).done(function(data){
            if (data.status) {
				toastr.success(data.message.body,data.message.title);
				usersData();
            } else {
                if (data.error_type == 'error-validation') {
					formElementValidationWithTimeout(data, form_user, 'checkup_', 'position-relative', 'tooltip');
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            handleErrorCallback('toastr',jqXHR);
        });
    });

    form_settings.on('submit',function(e){
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'save_layout_settings',
                xtoken: XTOKEN,
                datalog:formData
            },
        }).done(function(data){
            if (data.status) {
                toastr.success(data.message.body,data.message.title);
				usersData();
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            handleErrorCallback('toastr', jqXHR, true, REDIRECT_URL);
        });
    });
    
    form_password.on('submit',function(e){
        e.preventDefault();

        let newPassword = form_password.find('#new-password').val();
        let confirmNewPassword = form_password.find('#confirm-password').val();

        if (newPassword !== confirmNewPassword) {
            form_password.find('#new-password').val('');
            form_password.find('#confirm-password').val('');
			toastr.warning('konfirmasi password tidak cocok','Peringatan!');
            return;
        }

        let formData = $(this).serialize();
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'change_password',
                xtoken: XTOKEN,
                datalog:formData
            },
        }).done(function(data){
            Swal.close();
            if (data.status) {
				form_password[0].reset();
				toastr.success(data.message.body,data.message.title);
				usersData();
            } else {
				if (data.error_type == 'error-validation') {
					formElementValidationWithTimeout(data, form_password, 'checkup_', 'position-relative', 'tooltip');
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            handleErrorCallback('toastr', jqXHR, true, REDIRECT_URL);
        });
    });

});
</script>
