const API_REGENCY_URL = 'https://portsync.github.io/api-wilayah-indonesia/api/';

function fetchProvincesData() {
    return $.ajax({
        url: API_REGENCY_URL + 'provinces.json',
        method: 'GET',
    });
}

function fetchRegenciesData(provinceId) {
    return $.ajax({
        url: API_REGENCY_URL + 'regencies/' + provinceId + '.json',
        method: 'GET',
    });
}

function fetchDistrictsData(regencyId) {
    return $.ajax({
        url: API_REGENCY_URL + 'districts/' + regencyId + '.json',
        method: 'GET',
    });
}

function fetchVillagesData(districtId) {
    return $.ajax({
        url: API_REGENCY_URL + 'villages/' + districtId + '.json',
        method: 'GET',
    });
}


function fetchProvincesAndPopulateSelect(elementId) {
    $.ajax({
        url: API_REGENCY_URL + 'provinces.json',
        method: 'GET',
    }).done(function(response) {
        $('#' + elementId).empty();
        $('#' + elementId).append("<option value=''>-</option>");
        response.forEach(function(province) {
            $('#' + elementId).append("<option value='" + province.id + "'>" + province.name + "</option>");
        });
    }).fail(function() {
        console.error('Gagal mengambil data provinsi');
    });
}

function fetchRegenciesAndPopulateSelect(provinceId, elementId) {
    $.ajax({
        url: API_REGENCY_URL + 'regencies/' + provinceId + '.json',
        method: 'GET',
    }).done(function(response) {
        $('#' + elementId).empty();
        $('#' + elementId).append("<option value=''>-</option>");
        response.forEach(function(regency) {
            $('#' + elementId).append("<option value='" + regency.id + "'>" + regency.name + "</option>");
        });
    }).fail(function() {
        console.error('Failed to fetch regencies data');
    });
}

function fetchDistrictsAndPopulateSelect(regencyId, elementId) {
    $.ajax({
        url: API_REGENCY_URL + 'districts/' + regencyId + '.json',
        method: 'GET',
    }).done(function(response) {
        $('#' + elementId).empty();
        $('#' + elementId).append("<option value=''>-</option>");
        response.forEach(function(district) {
            $('#' + elementId).append("<option value='" + district.id + "'>" + district.name + "</option>");
        });
    }).fail(function() {
        console.error('Failed to fetch districts data');
    });
}

function fetchVillagesAndPopulateSelect(districtId, elementId) {
    $.ajax({
        url: API_REGENCY_URL + 'villages/' + districtId + '.json',
        method: 'GET',
    }).done(function(response) {
        $('#' + elementId).empty();
        $('#' + elementId).append("<option value=''>-</option>");
        response.forEach(function(village) {
            $('#' + elementId).append("<option value='" + village.id + "'>" + village.name + "</option>");
        });
    }).fail(function() {
        console.error('Failed to fetch villages data');
    });
}

