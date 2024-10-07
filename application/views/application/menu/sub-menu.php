<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Sub menu</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Application</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Menu</a></li>
                    <li class="breadcrumb-item active">Sub menu</li>
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
				<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="add-sub">
					<i class="fas fa-plus-circle fa-fw"></i> Tambah Submenu
				</button>
            </div>
            <div class="card-body">
                <table id="tb-sub" class="table table-sm table-bordered nowrap align-middle" style="border-collapse: collapse; border-spacing: 0; width: 100%;"></table>
            </div>
        </div>
    </div>
</div>
<!-- forms -->
<div class="modal fade" id="modalAddSub" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAddSubLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddSubLabel"></h5>
            </div>
            <div class="modal-body">
                <form id="form-data-sub" autocomplete="off">
					<input type="hidden" name="id_sub">
					<div class="position-relative mb-3">
						<label class="form-label" for="field-menu">Modul menu<span class="text-danger">*</span></label>
						<select class="form-select checkup_id_menu" id="field-menu" name="id_menu"></select>
					</div>
					<div class="position-relative mb-3">
						<label class="form-label" for="field-sub-name">Nama Submenu<span class="text-danger">*</span></label>
						<input type="text" name="sub_name" id="field-sub-name" class="form-control checkup_sub_name" placeholder="Nama menu">
					</div>
					<div class="position-relative mb-3">
						<label class="form-label" for="field-sub-route">Route submenu<span class="text-danger">*</span></label>
						<input type="text" name="sub_route" id="field-sub-route" class="form-control checkup_sub_route" placeholder="ex : submenu / sub-menu">
					</div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Batalkan</button>
                <button type="button" class="btn btn-primary btn-sm" id="button-submit-sub">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	const REDIRECT_URL  = "<?php echo BASE_URL('apps/menu/sub-content') ?>";
    const SERVICE_URL   = "<?php echo BASE_URL('apps/menu/app-menu-service') ?>";
    const XTOKEN        = "<?php echo $this->session->xtoken; ?>";
    const PARAMS        = "sub";

	let dtTables        = $('#tb-'+PARAMS);
	let modal 			= $('#modalAddSub');
	let modalTitle      = $('#modalAddSubLabel');
	let modalSubmit     = $('#button-submit-sub');
	let formDataEl 		= $('#form-data-sub');
	let submitToken;

	$(document).on('click', '#add-sub', function(e){
		e.preventDefault();
		modal.modal('show');
		modalTitle.text('Form tambah submenu');
		modalSubmit.text('Tambahkan');
		submitToken = 'add_sub';
		getPathModulMenu('999',function(data){
			$('#field-menu').html(data);
		});
		restrictInput('#field-sub-name', 'ls');
		restrictInput('#field-sub-route', 'ldash');
		formElementValidationReset(formDataEl);
	});

	$(document).on('click', '.btn-edit-sub', function(e){
		e.preventDefault();
		let id = $(this).attr('id');
		$.ajax({
			url: SERVICE_URL,
			method: 'POST',
			dataType: 'JSON',
			data: {
				token: 'get_sub',
				xtoken: XTOKEN,
				id:id
			},
		}).done(function(data){
			if (data.status) {
				let res = data.result;
				formDataEl.find("input[name='id_sub']").val(res.id);
				getPathModulMenu(res.id_menu,function(data){
					formDataEl.find('#field-menu').html(data);
				});
				formDataEl.find("input[name='sub_name']").val(res.sub_name);
				formDataEl.find("input[name='sub_route']").val(res.sub_route);

				modal.modal('show');
				modalTitle.text('Ubah menu');
				modalSubmit.text('Perbahrui');
				submitToken = 'update_sub';
				restrictInput('#field-sub-name', 'ls');
				restrictInput('#field-sub-route', 'ldash');
			}
		}).fail(function(jqXHR, textStatus, errorThrown){
			handleErrorCallback('toastr',jqXHR);
		});
	});

	modalSubmit.on('click',function(e){
		e.preventDefault();
		let forms = formDataEl.serialize();
		$.ajax({
			url: SERVICE_URL,
			method: 'POST',
			dataType: 'JSON',
			data: {
				token: submitToken,
				xtoken: XTOKEN,
				datalog:forms
			},
			beforeSend:function(){
				loadingAlert('wait');
			},
		}).done(function(data){
			Swal.close();
			if (data.status) {
				modal.modal('hide');
				formElementValidationReset(formDataEl);
				toastr.success(data.message.body,data.message.title);
				dtTables.DataTable().destroy();
                fetchData();
			} else {
				if (data.error_type == 'error-validation') {
					formElementValidationWithTimeout(data, formDataEl, 'checkup_', 'position-relative', 'tooltip');
                }
			}
		}).fail(function(jqXHR, textStatus, errorThrown){
			// handleErrorCallback('toastr', jqXHR, true, REDIRECT_URL);
		});
	});

	$(document).on('click', '.btn-delete-sub', function(e){
		e.preventDefault();
		let id = $(this).attr('id');
		let name = $(this).data('name');
		let cat = 'sub';
		Swal.fire({
            title:'Konfirmasi',
            html:`menghapus modul <strong>${name}</strong> ?`,
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
						token: 'delete_menu',
						xtoken: XTOKEN,
						id:id,
						cat:cat
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

	$(document).on('change', '#switchStatus', function() {
        let id = $(this).data('id');
        let status = this.checked ? 1 : 0;
		let cat = 'sub';
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'change_status',
                xtoken: XTOKEN,
                id:id,
                status:status,
				cat:cat
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

	function getPathModulMenu(id='999',callback){
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data:{token:'get_modul_menu_list',xtoken:XTOKEN,id:id}
        }).done(function(data){
            callback(data)
        }).fail(function(jqXHR, textStatus, errorThrown){
            handleErrorCallback('toastr', jqXHR);
        });
    }

	fetchData();
	function fetchData() {
		$.ajax({
			url: SERVICE_URL,
			method: 'POST',
			dataType: 'JSON',
			data: {
				token: 'fetch_sub',
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
                        "data":"menu_name",
                        "title":"Modul menu",
                        "class": "text-left",
                        "render": function(data,type,row){
                            return `<strong>${data}</strong>`;
                        }
                    },
                    {
                        "data":"sub_name",
                        "title":"Nama submenu",
                        "class": "text-left"
                    },
                    {
                        "data":"sub_route",
                        "title":"submenu route",
                        "class": "text-left",
                    },
                    {
                        "data":"full_path",
                        "title":"Registered Route",
                        "class": "text-left",
						"orderable":false,
                        "render": function(data, type, row) {
							let badges;
							if (row.registered_route) {
								badges = '<span class="badge bg-success"><i class="fas fa-check"></i></span>';
							} else {
								badges = `<span class="badge bg-danger"><i class="fas fa-times"></i></span>`;
							}
							return ` <p class="text-primary mb-0">${badges} &nbsp; ${data}</p>`;
						}
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
                                                <input type="checkbox" class="form-check-input" id="switchStatus" data-id="${row.id}" ${checked}>
                                            </div>
                                        </div>`;
                            return html;
                        },
                    },
                    {
                        "data":"id",
                        "title":"",
                        "class": "text-center",
                        "orderable":false,
                        "render": function(data, type, row) {
                            let btn = `
							<button type="button" class="btn btn-soft-primary btn-sm btn-edit-sub" title="Edit" id="${data}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
							<button type="button" class="btn btn-soft-danger btn-sm btn-delete-sub" title="Delete" data-name="${row.sub_name}" id="${data}">
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
