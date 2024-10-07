<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Data User</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Application</a></li>
                    <li class="breadcrumb-item active">Data User</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
				<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="add-users">
					<i class="fas fa-plus-circle fa-fw"></i> Tambah User
				</button>
            </div>
            <div class="card-body">
                <table id="tb-users" class="table table-sm table-bordered nowrap align-middle" style="border-collapse: collapse; border-spacing: 0; width: 100%;"></table>
            </div>
        </div>
    </div>
</div>

<!-- add forms -->
<div class="modal fade" id="modallAddUsers" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modallAddUsersLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modallAddUsersLabel"></h5>
            </div>
            <div class="modal-body">
                <form id="form-data-users" autocomplete="off">
					<div class="position-relative mb-3">
						<label class="form-label" for="field-fullname">Nama Lengkap<span class="text-danger">*</span></label>
						<input type="text" name="fullname" id="field-fullname" class="form-control checkup_fullname" placeholder="Nama pengguna">
					</div>

					<div class="position-relative mb-3">
						<label class="form-label" for="field-email">Email<span class="text-danger">*</span></label>
						<input type="email" name="email" id="field-email" class="form-control checkup_email" placeholder="Email">
					</div>
					
					<div class="position-relative mb-3">
						<label class="form-label" for="field-roles">Role Pengguna<span class="text-danger">*</span></label>
						<select class="form-select checkup_roles" id="field-roles" name="roles"></select>
					</div>

					<div class="position-relative mb-3">
						<label class="form-label" for="field-phone">Nomor Telepon</label>
						<input type="text" name="phone" id="field-phone" class="form-control checkup_phone" placeholder="Nomor Telepon">
					</div>

					<div class="position-relative mb-3">
						<label class="form-label" for="field-address">Alamat</label>
						<textarea name="address" id="field-address" class="form-control checkup_address" placeholder="Alamat pengguna"></textarea>
					</div>
                    <hr class="hr">
                    <p class="text-muted">
                        <small class="text-danger fw-bold">Default Password : 123qweasd</small><br>
                        <i>*Harap mengganti password secara manual pada profile</i>
                    </p>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Batalkan</button>
                <button type="button" class="btn btn-primary btn-sm" id="button-submit-users">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<!-- update field -->
<div class="modal fade" id="modallEditUsers" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modallEditUsersLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title form-edit-users" id="modallEditUsersLabel"></h5>
            </div>
            <div class="modal-body">
                <div class="section-edit-users">
                    <input type="hidden" name="uid_users">
                    
                        <form class="field-update field-fullname">
                            <div class="position-relative">
                                <label class="form-label" for="fe-fullname">Nama Lengkap<span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="hidden" name="old_value" class="form-control">
                                    <input type="text" name="fullname" id="fe-fullname" class="form-control check-fullname">
                                    <button class="btn btn-soft-success" type="submit">Update</button>
                                </div>
                            </div>
                        </form>

                        <form class="field-update field-email">
                            <div class="position-relative">
                                <label class="form-label" for="fe-email">Email<span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
									<input type="hidden" name="old_value" class="form-control">
                                    <input type="text" name="email" id="fe-email" class="form-control check-email" placeholder="Email">
                                    <button class="btn btn-soft-success" type="submit">Update</button>
                                </div>
                            </div>
                        </form>

						<form class="field-update field-roles">
                            <div class="position-relative">
                                <label class="form-label" for="fe-roles">Role<span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
									<input type="hidden" name="old_value" class="form-control">
									<select name="roles" id="fe-roles" class="form-select check-roles"></select>
                                    <button class="btn btn-soft-success" type="submit">Update</button>
                                </div>
                            </div>
                        </form>

                        <form class="field-update field-phone">
                            <div class="position-relative">
                                <label class="form-label" for="fe-phone">Nomor Telepon</label>
                                <div class="input-group mb-3">
									<input type="hidden" name="old_value" class="form-control">
                                    <input type="text" name="phone" id="fe-phone" class="form-control check-phone">
                                    <button class="btn btn-soft-success" type="submit">Update</button>
                                </div>
                            </div>
                        </form>

						<form class="field-update field-address">
                            <div class="position-relative">
                                <label class="form-label" for="fe-address">Address</label>
                                <div class="input-group mb-3">
									<input type="hidden" name="old_value" class="form-control">
									<textarea name="address" id="fe-address" class="form-control check-address"></textarea>
                                    <button class="btn btn-soft-success" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                        
                </div>
            </div>
			<div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- update password -->
<div class="modal fade" id="modalUpdatePassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdatePasswordLabel">Form ubah password</h5>
            </div>
            <div class="modal-body">

				<div class="border px-3 py-2 account-info">
					<h6>Account info :</h6>
					<dl class="row mb-0">
						<dt class="col-sm-2">Name</dt>
						<dd class="col-sm-9 mb-0 ac-name"></dd>
						<dt class="col-sm-2">Role</dt>
						<dd class="col-sm-9 mb-0 ac-role"></dd>
					</dl>
				</div>
				<hr class="hr">
                <form id="form-update-password-users">
					<input type="hidden" name="uid_users">
					<div class="mb-3 position-relative">
						<label class="form-label" for="current-password">Password saat ini</label>
						<input type="password" name="current_password" class="form-control checkup_current_password" id="current-password" autocomplete="current-password">
					</div>

					<div class="mb-3 position-relative">
						<label class="form-label" for="new-password">Password baru</label>
						<input type="password" name="new_password" class="form-control checkup_new_password" id="new-password" autocomplete="new-password">
					</div>

					<div class="mb-3 position-relative">
						<label class="form-label" for="confirm-password">Konfirmasi password baru</label>
						<input type="password" name="confirm_password" class="form-control checkup_confirm_password" id="confirm-password" autocomplete="new-password">
					</div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Batalkan</button>
                <button type="button" class="btn btn-primary btn-sm" id="button-update-password-users">Perbahrui</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	const REDIRECT_URL  = "<?php echo BASE_URL('apps/users') ?>";
    const SERVICE_URL   = "<?php echo BASE_URL('apps/users/app-user-service') ?>";
    const XTOKEN        = "<?php echo $this->session->xtoken; ?>";
    const PARAMS        = "users";
	
	let dtTables        = $('#tb-'+PARAMS);
	let modal 			= $('#modallAddUsers');
	let modalTitle      = $('#modallAddUsersLabel');
	let formDataEl 		= $('#form-data-users');

	$(document).on('click','#add-'+PARAMS, function(){
        modal.modal('show');
        modalTitle.text('Form Tambah User');
        getUserRoles('999',function(data){
			$('#field-roles').html(data);
		});
		restrictInput('#field-fullname', 'ls')
		restrictInput('#field-phone', 'n')
        formElementValidationReset(formDataEl);
    });

	$(document).on('click', '#button-submit-users', function(e) {
        e.preventDefault();
        let datasrc = formDataEl.serialize();
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'add_user',
                xtoken: XTOKEN,
                datalog:datasrc
            },
            beforeSend:function(){
                loadingAlert('wait');
            },
        }).done(function(data){
            Swal.close();
            if (data.status) {
                formElementValidationReset(formDataEl);
				modal.modal('hide');
				toastr.success(data.message.body,data.message.title);
				dtTables.DataTable().destroy();
                fetchData();
            }else{
                if (data.error_type == 'error-validation') {
					formElementValidationWithTimeout(data, formDataEl, 'checkup_', 'position-relative', 'tooltip');
                } else {
					handleErrorCallback('toastr', null, true, REDIRECT_URL);
				}
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            handleErrorCallback('toastr', jqXHR, true, REDIRECT_URL);
        });
    });
	
	$(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
		restrictInput('#field-fullname', 'ls');
		restrictInput('#field-phone', 'n');
        let idx = $(this).attr('id');
		$('#modallEditUsers').modal('show');
        getUserRows(idx);
    });
	function getUserRows(id) {
		$.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'get_user',
                xtoken: XTOKEN,
                data: id
            },
        }).done(function(data){
            if (data.status) {
                let res = data.result;
                $('.section-edit-users').find("[name='uid_users']").val(res.uid);

                $('.field-fullname').find("[name='old_value']").val(res.fullname);
                $('.field-fullname').find("[name='fullname']").val(res.fullname);

                $('.field-email').find("[name='old_value']").val(res.email);
                $('.field-email').find("[name='email']").val(res.email);

				$('.field-roles').find("[name='old_value']").val(res.roles);
				getUserRoles(res.roles,function(data){
					$('.field-roles').find("select[name='roles']").html(data);
				});

                $('.field-phone').find("[name='old_value']").val(res.phone);
                $('.field-phone').find("[name='phone']").val(res.phone);
				
                $('.field-address').find("[name='old_value']").val(res.address);
                $('.field-address').find("[name='address']").val(res.address);

                $('.form-edit-users').text('Account : '+res.fullname);
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            handleErrorCallback('toastr',jqXHR);
        });
	}
	$(document).on('submit', '.field-update', function(e) {
        e.preventDefault();
        let uid = $('.section-edit-users').find("[name='uid_users']").val();
        let el = $(this).serialize();

		let params = new URLSearchParams(el);
		let oldValue = params.get('old_value');
		for (let [key, value] of params.entries()) {
			if (key !== 'old_value') { 
				if (oldValue === decodeURIComponent(value).trim()) {
					toastr.warning('Anda tidak membuat perubahan','Info update');
					return;
				}
			}
		}
		params.delete('old_value');
		let dataElement = params.toString();
		$.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'update_users',
                xtoken: XTOKEN,
                i:uid,
                d:dataElement,
            },
        }).done(function(data){
            if (data.status) {
                let field_form = $('.field-' + data.data_field);
                let field_element = $('.check-' + data.data_field);
                field_form.find('.invalid-tooltip').remove();
                field_element.removeClass('is-invalid is-valid').addClass(data.invalid_message.length > 0 ? 'is-invalid' : 'is-valid');
                if (data.invalid_message.length > 0) {
                    field_element.after(data.invalid_message);
                }
                if (field_element.hasClass('is-valid')) {
                    setTimeout(function() {
                        field_element.removeClass('is-valid');
                        field_form.removeClass('was-validate');
                    }, 3000);
                }
				getUserRows(uid);
                toastr.success('Data berhasil diperbahrui','Info update');
            } else {
                if (data.error_type == 'error-validation') {
					let formEl = $('.field-'+data.error_field);
					formElementValidationWithTimeout(data, formEl, 'check-', 'position-relative', 'tooltip');
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            handleErrorCallback('toastr', jqXHR, true, REDIRECT_URL);
        });
    });

	$(document).on('hidden.bs.modal', '#modallEditUsers', function(e) {
        dtTables.DataTable().destroy();
        fetchData();
    });

	function getUserRoles(id='999',callback){
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data:{token:'get_role',xtoken:XTOKEN,id:id}
        }).done(function(data){
            callback(data)
        }).fail(function(jqXHR, textStatus, errorThrown){
            handleErrorCallback('toastr', jqXHR);
        });
    }
	$(document).on('change', '#switchStatus', function() {
        let id = $(this).data('id');
        let status = this.checked ? 1 : 0;
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'change_status',
                xtoken: XTOKEN,
                id:id,
                status:status
            },
        }).done(function(data){
            if (data.status) {
                toastr.success(data.message.body,data.message.title);
                dtTables.DataTable().destroy();
                fetchData();
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
			handleErrorCallback('toastr',jqXHR);
        });
    });

	$(document).on('click', '.btn-update-password', function(e){
		e.preventDefault();
		let modal = $('#modalUpdatePassword');
		modal.modal('show');
		let forms = $('#form-update-password-users');
		formElementValidationReset(forms);
		let idx = $(this).attr('id');
		$.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'get_user',
                xtoken: XTOKEN,
                data: idx
            },
        }).done(function(data){
            if (data.status) {
                let res = data.result;
                $('#form-update-password-users').find("[name='uid_users']").val(res.uid);
                $('.account-info').find(".ac-name").text(res.fullname);
                $('.account-info').find(".ac-role").text(res.role_name);
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            handleErrorCallback('toastr',jqXHR);
        });

	});
	$(document).on('click', '#button-update-password-users', function(e){
		e.preventDefault();
		let forms = $('#form-update-password-users');
		let el = forms.serialize();
		let aData = {
			token: 'change_password',
			xtoken: XTOKEN,
			datalog: el
		};
		Swal.fire({
            title:'Konfirmasi',
            text:'Password akan diganti',
            icon:"question",
            showCancelButton:!0,
            confirmButtonColor:"#00a65a",
            cancelButtonColor:"#d33",
            confirmButtonText:"Ya, lanjutkan",
            cancelButtonText:"Batalkan"
        }).then(function(t){ 
            if(t.value) {
				$.ajax({
					url: SERVICE_URL,
					method: 'POST',
					dataType: 'JSON',
					data: aData
				}).done(function(data){
					if (data.status) {
						let modal = $('#modalUpdatePassword');
						modal.modal('hide');
						toastr.success(data.message.body,data.message.title);
						dtTables.DataTable().destroy();
						fetchData();
					} else {
						if (data.error_type == 'error-validation') {
							formElementValidationWithTimeout(data, forms, 'checkup_', 'position-relative', 'tooltip');
						} else {
							handleErrorCallback('toastr', null, true, REDIRECT_URL);
						}
					}
				}).fail(function(jqXHR, textStatus, errorThrown){
					handleErrorCallback('toastr',jqXHR);
				});
			}
		});
	});

	$(document).on('click', '.btn-delete-users', function(e){
		e.preventDefault();
		let id = $(this).attr('id');
		let name = $(this).data('name');
		Swal.fire({
            title:'Konfirmasi',
            html:`menghapus pengguna <strong>${name}</strong> ?`,
            icon:"question",
            showCancelButton:!0,
            confirmButtonColor:"#00a65a",
            cancelButtonColor:"#d33",
            confirmButtonText:"Ya, hapus",
            cancelButtonText:"Batalkan"
        }).then(function(t){ 
            if(t.value) {
				$.ajax({
					url: SERVICE_URL,
					method: 'POST',
					dataType: 'JSON',
					data: {
						token: 'delete_users',
						xtoken: XTOKEN,
						id:id
					},
				}).done(function(data){
					if (data.status) {
						toastr.success(data.message.body,data.message.title);
						dtTables.DataTable().destroy();
						fetchData();
					}
				}).fail(function(jqXHR, textStatus, errorThrown){
					handleErrorCallback('toastr',jqXHR);
				});
			}
		});
	});

	fetchData();
	function fetchData() {
		$.ajax({
			url: SERVICE_URL,
			method: 'POST',
			dataType: 'JSON',
			data: {
				token: 'fetch_result',
				xtoken: XTOKEN,
			},
		}).done(function(data){
			let tb = dtTables.DataTable({
                dom: dtDomlftrip(),
                aaData: data,
                processing: true,
                autoWidth: false,
                scrollCollapse: true,
                paginationType: "full_numbers",
                lengthMenu: [
                    [ 10, -1 ],
                    [ '10', 'Semua' ]
                ],
                language: dtLanguageConfig(),
                createdRow: function(row, data, dataIndex) {
                    if (data.status == '0') {
                        $(row).addClass('bg-soft-warning');
                    }
                },
                columns: [
                    {
                        "data":"role_name",
                        "title":"Role",
                        "class": "text-left",
                        "render": function(data,type,row){
                            if (data === null || data === '') {
								return '<strong class="text-danger">tidak terdaftar</strong>';
							}
							return `<strong>${data}</strong>`;
                        }
                    },
                    {
                        "data":"fullname",
                        "title":"Nama",
                        "class": "text-left",
                    },
                    {
                        "data":"email",
                        "title":"Email",
                        "orderable":false,
                        "class": "text-left",
                    },
                    {
                        "data":"last_login",
                        "title":"Last Login",
                        "orderable":true,
                        "class": "text-left",
                    },
                    {
                        "data":"status",
                        "title":"Status",
                        "class": "text-center",
						"orderable":false,
                        "render": function(data, type, row) {
                            let checked = data == 1 ? 'checked' : '';
                            let html = `<div class="d-flex justify-content-center">
                                            <div class="form-check form-switch" dir="ltr">
                                                <input type="checkbox" class="form-check-input" id="switchStatus" data-id="${row.uid}" ${checked}>
                                            </div>
                                        </div>`;
                            return html;
                        },
                    },
                    {
                        "data":"uid",
                        "title":"",
                        "class": "text-center",
                        "orderable":false,
                        "render": function(data, type, row) {
                            let btn = `
							<button type="button" class="btn btn-soft-primary btn-sm btn-edit" title="Update Info" id="${data}">
                                <i class="fas fa-user-edit"></i>
                            </button>
							<button type="button" class="btn btn-soft-warning btn-sm btn-update-password" title="Reset Password" id="${data}">
                                <i class="fas fa-lock"></i>
                            </button>
							<button type="button" class="btn btn-soft-danger btn-sm btn-delete-users" title="Delete user" data-name="${row.fullname}" id="${data}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
							`;
                            return btn;
                        },
                    },
                ],
            });
		}).fail(function(jqXHR, textStatus, errorThrown){
			handleErrorCallback('toastr',jqXHR);
		});
	}

});
</script>
