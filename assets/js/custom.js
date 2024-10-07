toastr.options = {
    "closeButton": true,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

function toastrNotification(message, title, options) {
    let defaultOptions = {
        closeButton: true,
        newestOnTop: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        preventDuplicates: false,
        onclick: null,
        showDuration: '300',
        hideDuration: '1000',
        timeOut: 0,
        extendedTimeOut: 0,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };

    let toastOptions = $.extend({}, defaultOptions, options);

    let toast = toastr.info(message, title, toastOptions);

    toast.delegate('.toast-close-button', 'click', function() {
        toastr.clear(toast, { force: true });
    });

    // example using default :
    // toastrNotification('default using', 'Informasi', {});
    // example using with extra option :
    // var customOptions = {
    //     timeOut: 5000, // Durasi tampil untuk pemberitahuan lain (opsional)
    //     extendedTimeOut: 1000 // Durasi tambahan untuk pemberitahuan lain (opsional)
    // };
    // toastrNotification('opsi tambahan, mau ngapain hayooo', 'Informasi', customOptions);
}

function handleErrorCallback(display = 'toastr', xhrMsg = null, timeout = false, redirect = null) {
    
    let message = {
        title: 'Peringatan!',
        body: 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
    };
    
    if (xhrMsg !== null && xhrMsg.responseJSON && xhrMsg.responseJSON.message) {
        message = xhrMsg.responseJSON.message;
    }

    let redirectUrl = redirect || window.location.href;

    function handleTimeoutAndRedirect() {
        if (timeout) {
            setTimeout(function() {
                window.location.href = redirectUrl;
            }, 1500);
        }
        if (display === 'toastr') {
            toastr.info('Halaman akan direfresh','Muat ulang');
        }
    }

    if (display === 'toastr') {
        toastr.error(message.body, message.title);
        if (timeout) {
            handleTimeoutAndRedirect();
        }
    } else if (display === 'swal') {
        Swal.fire({
            title: message.title,
            html: message.body,
            icon: 'error',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: timeout,
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer && timeout) {
                window.location.href = redirectUrl;
            }
        });
    } else {
        console.error('Invalid display type:', display);
    }
}

function alertSpam(title,text,icon) {
    Swal.fire({
        title: title,
        html: text,
        icon: icon,
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });
}

function AlertRedirect(title,text,icon,urls) {
    Swal.fire({
        title: title,
        html: text,
        icon: icon,
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            window.location.href = urls;
        }
    });
}

function loadingAlert(sts){
    if (sts == 'wait') {
        Swal.fire({
            title: "Sedang memproses",
            text: "Mohon tunggu..",
            icon:'info',
            showConfirmButton: false,
            allowOutsideClick: false
        });
    }
    if (sts == 'error') {
        Swal.fire({
            title: "Gagal!",
            icon:'error',
            showConfirmButton: false,
            timer: 2000
        });
    }
}

function convertDurationToHourMinute(duration) {
    let hours = Math.floor(duration / 60);
    let minutes = duration % 60;

    let formattedDuration = (hours > 0 ? hours + ' jam ' : '') + (minutes > 0 ? minutes + ' menit' : '');

    return formattedDuration;
}

// currency
function formatCurrencyIDR(amount) {
    // Mengonversi string menjadi angka dan menambahkan pemisah ribuan
    let formattedAmount = parseFloat(amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

    // Hapus nilai '00' di belakang koma
    if (formattedAmount.slice(-3) === ',00') {
        formattedAmount = formattedAmount.slice(0, -3);
    }

    return formattedAmount;
}

function formatRupiahForElementInputID(inputId) {
    let inputElement = document.getElementById(inputId);

    if (!inputElement) {
        console.error('Element with ID ' + inputId + ' not found.');
        return;
    }

    inputElement.addEventListener('input', function(e) {
        inputElement.value = formatRupiah(inputElement.value);
    });

    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split    = number_string.split(','),
            sisa     = split[0].length % 3,
            rupiah   = split[0].substr(0, sisa),
            ribuan   = split[0].substr(sisa).match(/\d{3}/g);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        
        return rupiah;
    }
}

// end of currency
function formatDateToDmy(dateString) {
    let date = new Date(dateString);

    if (isNaN(date.getTime())) {
        return 'invalid format';
    }

    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    day = day < 10 ? '0' + day : day;
    month = month < 10 ? '0' + month : month;

    return day + '/' + month + '/' + year;
}

function curDate() {
    let currentDate = new Date();
    let year = currentDate.getFullYear();
    let month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    let day = ('0' + currentDate.getDate()).slice(-2);
    let formattedDate = year + '-' + month + '-' + day;
    return formattedDate;
}

function getCurrentYearMonth() {
    let currentDate = new Date();
    let year = currentDate.getFullYear();
    let month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    let formattedYearMonth = year + '-' + month;
    return formattedYearMonth;
}

function setDefaultMonthValue(inputId) {
    // Mendapatkan tanggal saat ini
    let currentDate = new Date();

    // Mendapatkan tahun dan bulan saat ini
    let year = currentDate.getFullYear();
    let month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Bulan dimulai dari 0, sehingga ditambah 1

    // Menetapkan nilai default ke input menggunakan jQuery
    let defaultValue = year + '-' + month;
    $(inputId).val(defaultValue);
}

function imageThumb(id,ids,nama,media){
    let image = '<img class="rounded-circle border border-light border-1 avatar-sm" src="'+media+id+'" alt="'+nama+'" />';
    return image;
}

function statusBadge(sts) {
    let status = (sts == '1') ? '<span class="badge badge-soft-success font-size-12">Aktif</span>' : '<span class="badge badge-soft-danger font-size-12">Non-Aktif</span>';
    return status;
}

function calculateAge(birthDate, type = 'all') {
    // using library moment js
    let birthDateObj;
    // Mengecek format tanggal input
    if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(birthDate)) {
        birthDateObj = moment(birthDate, 'DD/MM/YYYY', true);
    } else if (/^\d{4}-\d{2}-\d{2}$/.test(birthDate)) {
        birthDateObj = moment(birthDate, 'YYYY-MM-DD', true);
    } else {
        return "Format tanggal tidak valid";
    }

    if (!birthDateObj.isValid()) {
        return "Tanggal lahir tidak valid";
    }

    let today = moment();

    let years = today.diff(birthDateObj, 'years');
    let months = today.diff(birthDateObj, 'months') % 12;
    let days = today.diff(birthDateObj, 'days') % 30;

    if (type === 'year') {
        return years + " tahun";
    } else if(type === 'full') {
        return years + " tahun, " + months + " bulan, " + days + " hari";
    } else {
        return years + " thn, " + months + " bln, " + days + " hr";
    }
}

function actionBtnELD(id,sts,del=false) {
    let btn = '<button type="button" class="btn btn-soft-primary btn-sm btn-edit" id="'+ id + '">';
    btn += '<i class="fas fa-pencil-alt"></i></button>&nbsp;'
    if (sts == '1') {
        btn += '<button type="button" title="lock user" class="btn btn-soft-warning btn-sm btn-fn-status" data-value="0" id="'+ id + '">';
        btn += '<i class="fas fa-lock"></i></button>&nbsp;'
    } else {
        btn += '<button type="button" title="unlock user" class="btn btn-soft-success btn-sm btn-fn-status" data-value="1" id="'+ id + '">';
        btn += '<i class="fas fa-lock-open"></i></button>&nbsp;'
    }
    
    btn += '<button type="button" class="btn btn-soft-danger btn-sm btn-delete" id="'+ id + '">';
    btn += '<i class="fas fa-trash-alt"></i></button>';
    return btn;
}

function actionBtnESD(id, sts, del = false) {
    const buttons = [
        { type: 'edit', icon: 'fas fa-pencil-alt', title: 'Edit' },
        { type: 'status', icon: sts === '1' ? 'fas fa-lock' : 'fas fa-lock-open', title: sts === '1' ? 'Lock user' : 'Unlock user', value: sts === '1' ? '0' : '1' },
        { type: 'delete', icon: 'fas fa-trash-alt', title: 'Delete' }
    ];

    const btnHtml = buttons.map(button => {
        if (button.type === 'delete' && !del) {
            return '';
        }

        return `<button type="button" class="btn btn-soft-${button.type === 'status' ? (sts === '1' ? 'warning' : 'success') : 'primary'} btn-sm btn-${button.type}" title="${button.title}" ${button.type === 'status' ? `data-value="${button.value}"` : ''} id="${id}">
                    <i class="${button.icon}"></i>
                </button>&nbsp;`;
    }).join('');

    return btnHtml;
}

function btnIdEditDetail(id) {
    let btn = '<button type="button" title="edit" class="btn btn-warning btn-sm btn-edit" id="'+id+'">';
        btn += '<i class="far fa-edit"></i></button>&nbsp;'
        btn += '<button type="button" title="detail" class="btn btn-primary btn-sm btn-detail" id="'+id+'">';
        btn += '<i class="far fa-address-book"></i></button>';
    return btn;
}

function badgeRole(r) {
    let badge = '<div class="badge badge-soft-success font-size-12">'+r+'</div>';
    return badge;
}

function initialAvatar(nm) {
    let xy = '<div class="avatar-sm d-inline-block">'+
                '<div class="avatar-title bg-soft-primary rounded-circle text-primary">'+
                    nm.charAt(0).toUpperCase()+
                '</div>'+
            '</div>';
    return xy;
}

function btnIdEditStatus(id,params) {
    let btn = '<button type="button" title="edit" class="btn btn-warning btn-sm btn-edit-'+params+'" id="'+id+'">';
        btn += '<i class="far fa-edit"></i></button>&nbsp;';
    return btn;
}

function btnIdEditDelete(id,params) {
    let btn = '<button type="button" title="edit" class="btn btn-warning btn-sm btn-edit-'+params+'" id="'+id+'">';
        btn += '<i class="far fa-edit"></i></button>&nbsp;';
        btn += '<button type="button" title="hapus" class="btn btn-danger btn-sm btn-delete-'+params+'" id="'+id+'">';
        btn += '<i class="fas fa-trash-alt"></i></button>&nbsp;';
    return btn;
}

function statusInt(sts) {
    let bdg;
    if (sts == 1) {
        bdg = '<div class="badge badge-soft-success font-size-12">Aktif</div>'
    }
    if (sts == 0) {
        bdg = '<div class="badge badge-soft-danger font-size-12">Non Aktif</div>'
    }
    return bdg;
}

function statusString(sts) {
    let bdg;
    if (sts == 'active') {
        bdg = '<div class="badge badge-soft-success font-size-12">Aktif</div>'
    }
    else if (sts == 'inactive') {
        bdg = '<div class="badge badge-soft-danger font-size-12">Tidak Aktif</div>'
    } else {
        bdg = '<div class="badge badge-danger font-size-12">NA</div>'
    }
    return bdg;
}

function dir_badge(dir) {
    let badge = '<div class="badge badge-soft-success font-size-12">';
        badge += '<i class="far fa-folder-open fa-fw"></i> ../documentation/'+dir+'</div>';
    return badge;
}

function dir_span(dir) {
    let badge = '<div class="badge badge-soft-success font-size-12">';
        badge += '<i class="far fa-folder-open fa-fw"></i> ../'+dir+'</div>';
    return badge;
}

function getCurrentDateFormatted() {
    let currentDate = new Date();
    let day = padZero(currentDate.getDate());
    let monthIndex = currentDate.getMonth();
    let year = currentDate.getFullYear();

    // Fungsi untuk mendapatkan nama bulan berdasarkan indeks bulan
    function getMonthName(monthIndex) {
        let monthNames = ['Januari', 'Februari', 'Maret','April', 'Mei', 'Juni','Juli', 'Agustus', 'September','Oktober', 'November', 'Desember'];
        return monthNames[monthIndex];
    }

    // Fungsi untuk menambahkan nol di depan angka jika angka kurang dari 10
    function padZero(number) {
        return number < 10 ? '0' + number : number;
    }

    return `${day} ${getMonthName(monthIndex)} ${year}`;
}

function formatDateID(inputDate) {
    // format input YYYY-MM-DD
    if (!inputDate) return '';
    
    const dateArray = inputDate.split('-');
    if (dateArray.length !== 3) return '';
    
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const year = dateArray[0];
    const monthIndex = parseInt(dateArray[1]) - 1;
    const day = dateArray[2];
    
    if (isNaN(monthIndex) || monthIndex < 0 || monthIndex > 11) return '';

    const formattedDate = `${day} ${monthNames[monthIndex]} ${year}`;
    return formattedDate;
}

function currentDateInd(cetakHari = false) {
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const today = new Date();
    const dayOfWeek = today.getDay();
    const dayOfMonth = today.getDate();
    const month = today.getMonth() + 1;
    const formattedDate = dayOfMonth + ' ' + months[month - 1] + ' ' + today.getFullYear();
    if (cetakHari) {
        return days[dayOfWeek] + ', ' + formattedDate;
    }
    return formattedDate;
}

function restrictInput(elementId, code) {
    let inputFunction;
    switch (code) {
        case 'n':
            inputFunction = restrictToNumbers;
            break;
        case 'ls':
            inputFunction = restrictToLettersAndSpace;
            break;
		case 'ldash':
			inputFunction = restrictToLettersAndDash;
			break;
        case 'ln':
            inputFunction = restrictToLettersAndNumbers;
            break;
        case 'email':
            inputFunction = restrictToValidEmail;
            break;
        default:
            return;
    }
    inputFunction(elementId);
}

function restrictToNumbers(selector) {
    $(selector).on('input', function(event) {
        let inputChar = event.originalEvent.data;
        let pattern = /[0-9]/;
        if (!pattern.test(inputChar)) {
            $(this).val(function(index, value) {
                return value.replace(/\D/g, '');
            });
        }
    });
}

function restrictToLettersAndSpace(selector) {
    $(selector).on('input', function(event) {
        let inputChar = event.originalEvent.data;
        let pattern = /[a-zA-Z\s]/;
        if (!pattern.test(inputChar)) {
            $(this).val(function(index, value) {
                return value.replace(/[^a-zA-Z\s]/g, '');
            });
        }
    });
}

function restrictToLettersAndDash(selector) {
    $(selector).on('input', function(event) {
        let inputChar = event.originalEvent.data;
        let pattern = /^[a-zA-Z\-]$/;
        if (!pattern.test(inputChar) && inputChar !== null) {
            $(this).val(function(index, value) {
                return value.replace(/[^a-zA-Z\-]/g, '');
            });
        }
    });
}

function restrictToLettersAndNumbers(selector) {
    $(selector).on('input', function(event) {
        let inputChar = event.originalEvent.data;
        let pattern = /[a-zA-Z0-9]/;
        if (!pattern.test(inputChar)) {
            $(this).val(function(index, value) {
                return value.replace(/[^a-zA-Z0-9]/g, '');
            });
        }
    });
}

function restrictToValidEmail(selector) {
    $(selector).on('input', function(event) {
        let inputValue = $(this).val();
        let newValue = '';
        for (let i = 0; i < inputValue.length; i++) {
            let currentChar = inputValue[i];
            let pattern = /[^a-zA-Z0-9_@.\-]/;
            if (!pattern.test(currentChar)) {
                newValue += currentChar;
            }
        }
        $(this).val(newValue);
    });
}

function validatePositiveInput(inputElement) {
    $(inputElement).on('input', function() {
        let inputValue = $(this).val();
        if (inputValue < 0) {
            $(this).val(0);
        }
    });
}

function formElementValidation(data, formElement, classInitial, divParent, typeValidation) {
    let containerClass = '.invalid-feedback'; // Default value
    if (typeValidation === 'tooltip') {
        containerClass = '.invalid-tooltip';
    } else {
        containerClass = '.invalid-feedback';
    }
    formElement.addClass('was-validate');
    if (data && data.message) {
        $.each(data.message, function(key, value) {
            let element = $('.' + classInitial + key);
            element.closest('div.' + divParent).find(containerClass).remove();
            element.removeClass('is-invalid');
            element.addClass(value.length > 0 ? 'is-invalid' : 'is-valid');
            element.after(value);
        });
    }
}
function formElementValidationWithTimeout(data, formElement, classInitial, divParent, typeValidation) {
    let containerClass = '.invalid-feedback';
    if (typeValidation === 'tooltip') {
        containerClass = '.invalid-tooltip';
    } else {
        containerClass = '.invalid-feedback';
    }
    formElement.addClass('was-validate');
    if (data && data.message) {
        $.each(data.message, function(key, value) {
            let element = $('.' + classInitial + key);
            element.closest('div.' + divParent).find(containerClass).remove();
            element.removeClass('is-invalid');
            element.addClass(value.length > 0 ? 'is-invalid' : 'is-valid');
            element.after(value);
        });

        setTimeout(function() {
            formElement.removeClass('was-validate');
            formElement.find(':input').removeClass('is-invalid').removeClass('is-valid');
        }, 5000);
    }
}

function formElementValidationReset(formElement) {
    formElement[0].reset();
    formElement.removeClass('was-validate');
    formElement.find(':input').removeClass('is-invalid').removeClass('is-valid');
}

function resetModalFormValidation(el) {
    let form_target = $(el);
    form_target[0].reset();
    form_target.removeClass('was-validate');
    form_target.find(':input').removeClass('is-invalid').removeClass('is-valid');
}

function convGender(value) {
    switch (value.toLowerCase()) {
        case 'male':
            return 'Laki-Laki';
        case 'female':
            return 'Perempuan';
        default:
            return '-';
    }
}

function shortenFileName(fileName) {
    let parts = fileName.split('.');
    let extension = parts.pop();
    let name = parts.join('.');
    let shortenedName = name.substr(0, 8) + '.....' + name.substr(-4) + '.' + extension;
    return shortenedName;
}

/*=========================== DATATABLES*/
function reinitializeDataTables(elementTableSelector,fetchFunctionDataTables) {
    elementTableSelector.DataTable().destroy();
    fetchFunctionDataTables();
}

function dtDomBftri() {
    return "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-12'i>>"
}

function dtDomlftrip() {
    return "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
}

function dtDomltrip() {
    return "<'row'<'col-sm-12'l>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
}

function dtDomFullFeatureConfig() {
    return "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
}

function dtDomMinFeatureConfig() {
    return "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
}

function dtLanguageConfig() {
    return {
        "sEmptyTable": "Tidak ada data yang tersedia pada tabel",
        "sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
        "sInfoEmpty": "0 entri",
        "sInfoFiltered": "(disaring dari _MAX_ total entri)",
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": "Lihat&nbsp;&nbsp;_MENU_",
        "sLoadingRecords": "Memuat...",
        "sProcessing": "Sedang memproses...",
        "sSearch": "Cari:",
        "sZeroRecords": "Tidak ditemukan data yang cocok",
        "decimal": "",
        "thousands": ",",
        "oPaginate": {
            "sFirst": "<i class='fas fa-fast-backward'></i>",
            "sLast": "<i class='fas fa-fast-forward'></i>",
            "sNext": "<i class='fas fa-chevron-right'></i>",
            "sPrevious": "<i class='fas fa-chevron-left'></i>"
        },
        "oAria": {
            "sSortAscending": ": aktifkan untuk mengurutkan kolom secara naik",
            "sSortDescending": ": aktifkan untuk mengurutkan kolom secara turun"
        }
    }
}

function dtMinLanguageConfig() {
    return {
        "sEmptyTable": "Tidak ada data yang tersedia pada tabel",
        "sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
        "sInfoEmpty": "0 entri",
        "sInfoFiltered": "(disaring dari _MAX_ total entri)",
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": "_MENU_",
        "sLoadingRecords": "Memuat...",
        "sProcessing": "Sedang memproses...",
        "sSearch": "Cari:",
        "sZeroRecords": "Tidak ditemukan data yang cocok",
        "decimal": "",
        "thousands": ",",
        "oPaginate": {
            "sFirst": "<i class='fas fa-fast-backward'></i>",
            "sLast": "<i class='fas fa-fast-forward'></i>",
            "sNext": "<i class='fas fa-chevron-right'></i>",
            "sPrevious": "<i class='fas fa-chevron-left'></i>"
        },
        "oAria": {
            "sSortAscending": ": aktifkan untuk mengurutkan kolom secara naik",
            "sSortDescending": ": aktifkan untuk mengurutkan kolom secara turun"
        }
    }
}

function dtTbLanguageConfig() {
    return {
        "sEmptyTable": "Tidak ada dokumen",
        "sInfo": "_START_ - _END_ dari _TOTAL_ data",
        "sInfoEmpty": "0 data",
        "sInfoFiltered": "(disaring dari _MAX_ total data)",
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": "_MENU_",
        "sLoadingRecords": "Memuat...",
        "sProcessing": "Sedang memproses...",
        "sSearch": "Cari:",
        "sZeroRecords": "Tidak ditemukan data yang cocok",
        "decimal": "",
        "thousands": ",",
        "oPaginate": {
            "sFirst": "<i class='fas fa-fast-backward'></i>",
            "sLast": "<i class='fas fa-fast-forward'></i>",
            "sNext": "<i class='fas fa-chevron-right'></i>",
            "sPrevious": "<i class='fas fa-chevron-left'></i>"
        },
        "oAria": {
            "sSortAscending": ": aktifkan untuk mengurutkan kolom secara naik",
            "sSortDescending": ": aktifkan untuk mengurutkan kolom secara turun"
        }
    }
}

function dtButtonsExcellPdfConfig(e) {
    return [
            {
                extend: 'excelHtml5',
                className: 'btn-success btn-sm',
                title: function() {return e},
                text: '<i class="far fa-file-excel fa-fw"></i> EXCEL',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdfHtml5',
                className: 'btn-danger btn-sm',
                title: function() {return e},
                text: '<i class="far fa-file-pdf fa-fw"></i> PDF',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            }
        ]
}

function dtButtonsEP(e) {
    return [
            {
                extend: 'excelHtml5',
                className: 'btn-success btn-sm',
                title: function() {return e},
                text: '<i class="far fa-file-excel fa-fw"></i> EXCEL',
            },
            {
                extend: 'pdfHtml5',
                className: 'btn-danger btn-sm',
                title: function() {return e},
                text: '<i class="far fa-file-pdf fa-fw"></i> PDF',
            }
        ]
}

// Datatables button export
function btnExPDFnotLastChild(e) {
    return [
            {
                extend: 'excelHtml5',
                className: 'btn-success btn-sm',
                title: function() {return e+' - '+getCurrentDateFormatted()},
                text: '<i class="far fa-file-excel fa-fw"></i> EXCEL',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdfHtml5',
                className: 'btn-danger btn-sm',
                title: function() {return e+' - '+getCurrentDateFormatted()},
                text: '<i class="far fa-file-pdf fa-fw"></i> PDF',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                },
                orientation: 'potrait',
                customize: function(doc) {
                    doc.content[1].layout = 'Borders';
                }
            }
        ]
}
function btnExPDFAllCols(e) {
    return [
            {
                extend: 'excelHtml5',
                className: 'btn-success btn-sm',
                title: function() {return e+' - '+getCurrentDateFormatted()},
                text: '<i class="far fa-file-excel fa-fw"></i> EXCEL',
            },
            {
                extend: 'pdfHtml5',
                className: 'btn-danger btn-sm',
                title: function() {return e+' - '+getCurrentDateFormatted()},
                text: '<i class="far fa-file-pdf fa-fw"></i> PDF',
                orientation: 'potrait',
                customize: function(doc) {
                    doc.content[1].layout = 'Borders';
                }
            }
        ]
}
// daterange
function localeDaterange(){
    return {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Terapkan",
        "cancelLabel": "Batalkan",
        "fromLabel": "Dari",
        "toLabel": "Ke",
        "customRangeLabel": "Kustom tanggal",
        "daysOfWeek": [
            "Mg",
            "Sn",
            "Sl",
            "Rb",
            "Km",
            "Jm",
            "Sb"
        ],
        "monthNames": [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ],
        "firstDay": 1
    }
}
function momentDaterange() {
    // using moment();
    return {
        'Hari ini': [moment(), moment()],
        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 Hari terakhir': [moment().subtract(6, 'days'), moment()],
        '30 Hari terakhir': [moment().subtract(29, 'days'), moment()],
        'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
        'Bulan kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Tahun ini': [moment().startOf('year'), moment()]
    }
}
