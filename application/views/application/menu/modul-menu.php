<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Modul menu</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Application</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Menu</a></li>
                    <li class="breadcrumb-item active">Modul menu</li>
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
				<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="add-path">
					<i class="fas fa-plus-circle fa-fw"></i> Tambah Modul
				</button>
            </div>
            <div class="card-body">
                <table id="tb-path" class="table table-sm table-bordered nowrap align-middle" style="border-collapse: collapse; border-spacing: 0; width: 100%;"></table>
            </div>
        </div>
    </div>
</div>
<!-- forms -->
<div class="modal fade" id="modalAddPath" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAddPathLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddPathLabel"></h5>
            </div>
            <div class="modal-body">
                <form id="form-data-path" autocomplete="off">
					<input type="hidden" name="id_path">
					<div class="position-relative mb-3">
						<label class="form-label" for="field-path-name">Nama Modul<span class="text-danger">*</span></label>
						<input type="text" name="path_name" id="field-path-name" class="form-control checkup_path_name" placeholder="Nama modul">
					</div>
					<div class="position-relative mb-3">
						<label class="form-label" for="field-path-key">Key Modul<span class="text-danger">*</span></label>
						<input type="text" name="path_key" id="field-path-key" class="form-control checkup_path_key" placeholder="ex : dash / dash-menu">
					</div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Batalkan</button>
                <button type="button" class="btn btn-primary btn-sm" id="button-submit-path">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	const REDIRECT_URL  = "<?php echo BASE_URL('apps/menu/modul') ?>";
    const SERVICE_URL   = "<?php echo BASE_URL('apps/menu/app-menu-service') ?>";
    const XTOKEN        = "<?php echo $this->session->xtoken; ?>";
    const PARAMS        = "path";

	let dtTables        = $('#tb-'+PARAMS);
	let modal 			= $('#modalAddPath');
	let modalTitle      = $('#modalAddPathLabel');
	let modalSubmit     = $('#button-submit-path');
	let formDataEl 		= $('#form-data-path');
	let submitToken;

	$(document).on('click', '#add-path', function(e){
		e.preventDefault();
		modal.modal('show');
		modalTitle.text('Form tambah modul');
		modalSubmit.text('Tambahkan');
		submitToken = 'add_path';
		restrictInput('#field-path-name', 'ls');
		restrictInput('#field-path-key', 'ldash');
		formElementValidationReset(formDataEl);
	});

	$(document).on('click', '.btn-edit-path', function(e){
		e.preventDefault();
		let id = $(this).attr('id');
		$.ajax({
			url: SERVICE_URL,
			method: 'POST',
			dataType: 'JSON',
			data: {
				token: 'get_path',
				xtoken: XTOKEN,
				id:id
			},
		}).done(function(data){
			if (data.status) {
				let res = data.result;
				formDataEl.find("input[name='id_path']").val(res.id);
				formDataEl.find("input[name='path_name']").val(res.path_name);
				formDataEl.find("input[name='path_key']").val(res.path_key);

				modal.modal('show');
				modalTitle.text('Ubah Modul');
				modalSubmit.text('Perbahrui');
				submitToken = 'update_path';
				restrictInput('#field-path-name', 'ls');
				restrictInput('#field-path-key', 'ldash');
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
			handleErrorCallback('toastr', jqXHR, true, REDIRECT_URL);
		});
	});

	$(document).on('click', '.btn-delete-path', function(e){
		e.preventDefault();
		let id = $(this).attr('id');
		let name = $(this).data('name');
		let cat = 'path';
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
		let cat = 'path';
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

	fetchData();
	function fetchData() {
		$.ajax({
			url: SERVICE_URL,
			method: 'POST',
			dataType: 'JSON',
			data: {
				token: 'fetch_modul',
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
                        "data":"path_name",
                        "title":"Nama modul",
                        "class": "text-left",
                        "render": function(data,type,row){
                            return `<strong>${data}</strong>`;
                        }
                    },
                    {
                        "data":"path_key",
                        "title":"Key modul",
                        "class": "text-left",
                    },
                    {
                        "data":"data_key",
                        "title":"Data key",
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
                                                <input type="checkbox" class="form-check-input" id="switchStatus" data-id="${row.id}" ${checked}>
                                            </div>
                                        </div>`;
                            return html;
                        },
                    },
                    {
                        "data":"count_content",
                        "title":"Jumlah menu",
                        "class": "text-left",
                    },
                    {
                        "data":"id",
                        "title":"",
                        "class": "text-center",
                        "orderable":false,
                        "render": function(data, type, row) {
                            let btn = `
							<button type="button" class="btn btn-soft-primary btn-sm btn-edit-path" title="Edit" id="${data}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
							<button type="button" class="btn btn-soft-danger btn-sm btn-delete-path" title="Delete" data-name="${row.path_name}" id="${data}">
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