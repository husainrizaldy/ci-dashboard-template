<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Role Access Menu</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Application</a></li>
                    <li class="breadcrumb-item active">Role Access Menu</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <table id="tb-roles" class="table table-sm table-bordered nowrap align-middle" style="border-collapse: collapse; border-spacing: 0; width: 100%;"></table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAccessMenu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalAccessMenuLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title-access-menu" id="modalAccessMenuLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="menu-division"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	const REDIRECT_URL  = "<?php echo BASE_URL('apps/access') ?>";
    const SERVICE_URL   = "<?php echo BASE_URL('apps/access/app-access-service') ?>";
    const XTOKEN        = "<?php echo $this->session->xtoken; ?>";
    const PARAMS        = "roles";

	let dtTables        = $('#tb-'+PARAMS);

	$(document).on('click','.anc-role-menu', function(e){
        e.preventDefault();
        let id = $(this).data('id');
        let name = $(this).data('string');
        $('.title-access-menu').text('Role : '+name);
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'access_menu',
                xtoken: XTOKEN,
                id:id
            },
        }).done(function(data, textStatus, jqXHR){
			if (jqXHR.status === 204) {
				toastr.warning('Data menu tidak tersedia','Info');
			} else {
				$('#modalAccessMenu').modal('show');
				$('#menu-division').html(renderMenu(data.result,id));
			}
        }).fail(function(jqXHR, textStatus, errorThrown){
			handleErrorCallback('toastr',jqXHR);
        });
    });

	function renderMenu(data, role) {
		return data.map(category => `
			<h6>${category.path}</h6>
			<ul class="list-unstyled">
				${category.menu.map(menuItem => `
					<li>
						<div class="form-check">
							<input class="form-check-input a-check" data-role="${role}" data-menu="${menuItem.id}" type="checkbox" id="ckm${menuItem.id}"${menuItem.checked === "checked" ? ' checked' : ''}>
							<label class="form-check-label" for="ckm${menuItem.id}">${menuItem.name}</label>
						</div>
					</li>
				`).join('')}
			</ul>
		`).join('');
	}

    $(document).on('click','.a-check', function(e) {
        let role = $(this).data('role');
        let menu = $(this).data('menu');
        $.ajax({
            url: SERVICE_URL,
            method: 'POST',
            dataType: 'JSON',
            data: {
                token: 'assign_menu',
                xtoken: XTOKEN,
                role: role,
                menu: menu
            },
        }).done(function(data){
            if (data.status) {
                toastr.success(data.message.body,data.message.title);
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            handleErrorCallback('toastr', jqXHR, true, REDIRECT_URL);
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
                        "title":"Nama Role",
                        "class": "text-left",
                        "render": function(data,type,row){
                            return `<strong>${data}</strong>`;
                        }
                    },
                    {
                        "data":"code",
                        "title":"Kode Role",
                        "class": "text-left",
                    },
                    {
                        "data":"id",
                        "title":"Data Menu",
                        "class": "text-center",
						"orderable":false,
                        "render": function(data, type, row) {
                            let btn = `
							<a href="javascript:void(0)" class="btn btn-sm btn-soft-success anc-role-menu" data-id="${data}" data-string="${row.role_name}">Akses menu</a>
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
