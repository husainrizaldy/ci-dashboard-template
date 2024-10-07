<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Content menu</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Application</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Menu</a></li>
                    <li class="breadcrumb-item active">Content menu</li>
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
				<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="add-menu">
					<i class="fas fa-plus-circle fa-fw"></i> Tambah Content Menu
				</button>
            </div>
            <div class="card-body">
                <table id="tb-menu" class="table table-sm table-bordered nowrap align-middle" style="border-collapse: collapse; border-spacing: 0; width: 100%;"></table>
            </div>
        </div>
    </div>
</div>

<!-- forms -->
<div class="modal fade" id="modalAddMenu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAddMenuLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddMenuLabel"></h5>
            </div>
            <div class="modal-body">
                <form id="form-data-menu" autocomplete="off">
					<input type="hidden" name="id_menu">
					<div class="position-relative mb-3">
						<label class="form-label" for="field-path">Modul<span class="text-danger">*</span></label>
						<select class="form-select checkup_id_path" id="field-path" name="id_path"></select>
					</div>
					<div class="position-relative mb-3">
						<label class="form-label" for="field-menu-name">Nama Menu<span class="text-danger">*</span></label>
						<input type="text" name="menu_name" id="field-menu-name" class="form-control checkup_menu_name" placeholder="Nama menu">
					</div>
					<div class="mb-3">
                        <h5 class="font-size-14 mb-2">Group menu</h5>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="group_menu" value="group" id="group_menu1">
                            <label class="form-check-label" for="group_menu1">
                                Group
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="group_menu" value="single" id="group_menu2" checked="">
                            <label class="form-check-label" for="group_menu2">
                                Single
                            </label>
                        </div>
                    </div>
					<div class="position-relative mb-3">
						<label class="form-label" for="field-menu-route">Menu route<span class="text-danger">*</span></label>
						<input type="text" name="menu_route" id="field-menu-route" class="form-control checkup_menu_route" placeholder="ex : dash / dash-menu">
					</div>
					<div class="position-relative mb-3">
						<label class="form-label" for="field-data-icon">Menu icon</label>
						<input type="text" name="data_icon" id="field-data-icon" class="form-control checkup_data_icon" placeholder="default : bx bx-grid-alt">
					</div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Batalkan</button>
                <button type="button" class="btn btn-primary btn-sm" id="button-submit-menu">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	const REDIRECT_URL  = "<?php echo BASE_URL('apps/menu/content') ?>";
    const SERVICE_URL   = "<?php echo BASE_URL('apps/menu/app-menu-service') ?>";
    const XTOKEN        = "<?php echo $this->session->xtoken; ?>";
    const PARAMS        = "menu";

	let dtTables        = $('#tb-'+PARAMS);
	let modal 			= $('#modalAddMenu');
	let modalTitle      = $('#modalAddMenuLabel');
	let modalSubmit     = $('#button-submit-menu');
	let formDataEl 		= $('#form-data-menu');
	let submitToken;

	$(document).on('click', '#add-menu', function(e){
		e.preventDefault();
		modal.modal('show');
		modalTitle.text('Form tambah menu');
		modalSubmit.text('Tambahkan');
		submitToken = 'add_content';
		getPathModul('999',function(data){
			$('#field-path').html(data);
		});
		restrictInput('#field-menu-name', 'ls');
		restrictInput('#field-menu-route', 'ldash');
		formElementValidationReset(formDataEl);
	});

	$(document).on('click', '.btn-edit-menu', function(e){
		e.preventDefault();
		let id = $(this).attr('id');
		$.ajax({
			url: SERVICE_URL,
			method: 'POST',
			dataType: 'JSON',
			data: {
				token: 'get_content',
				xtoken: XTOKEN,
				id:id
			},
		}).done(function(data){
			if (data.status) {
				let res = data.result;
				formDataEl.find("input[name='id_menu']").val(res.id);
				getPathModul(res.id_path,function(data){
					formDataEl.find('#field-path').html(data);
				});
				formDataEl.find("input[name='menu_name']").val(res.menu_name);
				let menuRoute;
				if (res.group_status) {
					$('#group_menu1').prop('checked', true);
					menuRoute = res.menu_key;
				} else {
					$('#group_menu2').prop('checked', true);
					menuRoute = res.menu_route;
				}
				formDataEl.find("input[name='menu_route']").val(menuRoute);
				formDataEl.find("input[name='data_icon']").val(res.data_icon);

				modal.modal('show');
				modalTitle.text('Ubah menu');
				modalSubmit.text('Perbahrui');
				submitToken = 'update_content';
				restrictInput('#field-menu-name', 'ls');
				restrictInput('#field-menu-route', 'ldash');
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

	$(document).on('click', '.btn-delete-menu', function(e){
		e.preventDefault();
		let id = $(this).attr('id');
		let name = $(this).data('name');
		let cat = 'content';
		Swal.fire({
            title:'Konfirmasi',
            html:`menghapus menu <strong>${name}</strong> ?`,
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
		let cat = 'content';
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

	function getPathModul(id='999',callback){
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data:{token:'get_modul_list',xtoken:XTOKEN,id:id}
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
				token: 'fetch_content',
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
                        "title":"Modul",
                        "class": "text-left",
                        "render": function(data,type,row){
                            return `<strong>${data}</strong>`;
                        }
                    },
                    {
                        "data":"menu_name",
                        "title":"Nama menu",
                        "class": "text-left"
                    },
                    {
                        "data":"menu_route",
                        "title":"Menu route",
                        "class": "text-left",
                        "render": function(data, type, row) {
							return row.group_route ? row.menu_key : data;
						}
                    },
                    {
                        "data":"group_route",
                        "title":"Group",
                        "class": "text-left",
						"orderable":false,
                        "render": function(data, type, row) {
							return data ? `<span class="badge bg-primary">Yes</span>` : `<span class="badge bg-warning">No</span>`;
						}
                    },
                    {
                        "data":"menu_route",
                        "title":"Registered Route",
                        "class": "text-left",
						"orderable":false,
                        "render": function(data, type, row) {
							let dataRoute = row.group_route ? row.menu_key+'/..' : data;
							let badges;
							if (row.registered_route) {
								badges = '<span class="badge bg-success"><i class="fas fa-check"></i></span>';
							} else {
								badges = `<span class="badge bg-danger"><i class="fas fa-times"></i></span>`;
							}
							return ` <p class="text-primary mb-0">${badges} &nbsp; /${row.path_key}/${dataRoute}</p>`;
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
							<button type="button" class="btn btn-soft-primary btn-sm btn-edit-menu" title="Edit" id="${data}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
							<button type="button" class="btn btn-soft-danger btn-sm btn-delete-menu" title="Delete" data-name="${row.menu_name}" id="${data}">
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
