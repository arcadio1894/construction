$(document).ready(function () {

    fillPeriods();

    $('#btn-refresh').on('click', fillPeriods);

    $('#btn-newPeriod').on('click', showModalNewPeriod);

    $(document).on('click', '[data-edit]', showModalEditPeriod);
    $(document).on('click', '[data-delete]', showModalDeletePeriod);

    $formCreate = $('#formCreate');
    $modalCreate = $('#modalCreate');
    $formEdit = $('#formEdit');
    $modalEdit = $('#modalEdit');
    $formDelete = $('#formDelete');
    $modalDelete = $('#modalDelete');

    $('#btn-submit').on('click', storePeriod);
    $('#btn-submitEdit').on('click', updatePeriod);
    $('#btn-submitDelete').on('click', deletePeriod);

});

var $formCreate;
var $modalCreate;
var $formEdit;
var $modalEdit;
var $formDelete;
var $modalDelete;

function showModalDeletePeriod() {
    var description_period = $(this).data('description');
    var period_id = $(this).data('delete');

    $modalDelete.find('[id=period_id_delete]').val(period_id);
    $modalDelete.find('[id=descriptionDelete]').html(description_period);
    $modalDelete.modal('show');
}

function showModalEditPeriod() {
    var description_period = $(this).data('description');
    var period_id = $(this).data('edit');
    var month = $(this).data('month');
    var year = $(this).data('year');

    $modalEdit.find('[id=period_id_edit]').val(period_id);
    $modalEdit.find('[id=month_edit]').val(month);
    $modalEdit.find('[id=year_edit]').val(year);
    $modalEdit.find('[id=description_edit]').html(description_period);
    $modalEdit.modal('show');
}

function showModalNewPeriod() {
    $modalCreate.modal('show');
}

function fillPeriods() {
    $("#content-body").LoadingOverlay("show", {
        background  : "rgba(236, 91, 23, 0.5)"
    });



    $.ajax({
        url: "/dashboard/all/period/gratifications/",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var periods = json.periods;
            var workers = json.numWorkers;

            $('#body-periods').html('');

            renderTemplatePeriod(periods, workers);

            $("#content-body").LoadingOverlay("hide", true);
        }
    });
}

function renderTemplatePeriod(periods, workers) {

    for (let i = 0; i < periods.length; i++) {
        var clone = activateTemplate('#template-period');
        var url = 'crear/gratificacion/'+periods[i].id;
        clone.querySelector("[data-description]").innerHTML = periods[i].description;
        clone.querySelector("[data-registered]").innerHTML = 'Registrados: '+ periods[i].gratifications.length;
        clone.querySelector("[data-percentage]").setAttribute('aria-valuenow', (periods[i].gratifications.length/workers)*100);
        clone.querySelector("[data-percentage]").setAttribute('style', 'width: '+((periods[i].gratifications.length/workers)*100)+'%');
        clone.querySelector("[data-workers]").innerHTML = 'Num. Trabajadores: ' + workers;
        clone.querySelector("[data-link]").setAttribute('href', url);
        clone.querySelector("[data-edit]").setAttribute('data-edit', periods[i].id);
        clone.querySelector("[data-edit]").setAttribute('data-description', periods[i].description);
        clone.querySelector("[data-edit]").setAttribute('data-month', periods[i].month);
        clone.querySelector("[data-edit]").setAttribute('data-year', periods[i].year);
        clone.querySelector("[data-delete]").setAttribute('data-delete', periods[i].id);
        clone.querySelector("[data-delete]").setAttribute('data-description', periods[i].description);

        $('#body-periods').append(clone);
    }

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function storePeriod() {
    event.preventDefault();
    // Obtener la URL
    $("#btn-submit").attr("disabled", true);
    var formulario = $('#formCreate')[0];
    var form = new FormData(formulario);
    var createUrl = $formCreate.data('url');
    $.ajax({
        url: createUrl,
        method: 'POST',
        data: form,
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
            $("#btn-submit").attr("disabled", false);
            $modalCreate.modal('hide');
            toastr.success(data.message, 'Éxito',
                {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            setTimeout( function () {
                fillPeriods();
            }, 2000 )
        },
        error: function (data) {
            if( data.responseJSON.message && !data.responseJSON.errors )
            {
                toastr.error(data.responseJSON.message, 'Error',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }
            for ( var property in data.responseJSON.errors ) {
                toastr.error(data.responseJSON.errors[property], 'Error',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }

            $("#btn-submit").attr("disabled", false);
        },
    });
}

function updatePeriod() {
    event.preventDefault();
    // Obtener la URL
    $("#btn-submitEdit").attr("disabled", true);
    var formulario = $('#formEdit')[0];
    var form = new FormData(formulario);
    var editUrl = $formEdit.data('url');
    $.ajax({
        url: editUrl,
        method: 'POST',
        data: form,
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
            $("#btn-submitEdit").attr("disabled", false);
            $modalEdit.modal('hide');
            toastr.success(data.message, 'Éxito',
                {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            setTimeout( function () {
                fillPeriods();
            }, 2000 )
        },
        error: function (data) {
            if( data.responseJSON.message && !data.responseJSON.errors )
            {
                toastr.error(data.responseJSON.message, 'Error',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }
            for ( var property in data.responseJSON.errors ) {
                toastr.error(data.responseJSON.errors[property], 'Error',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }

            $("#btn-submitEdit").attr("disabled", false);
        },
    });
}

function deletePeriod() {
    event.preventDefault();
    // Obtener la URL
    $("#btn-submitDelete").attr("disabled", true);
    var formulario = $('#formDelete')[0];
    var form = new FormData(formulario);
    var deleteUrl = $formDelete.data('url');
    $.ajax({
        url: deleteUrl,
        method: 'POST',
        data: form,
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
            $("#btn-submitDelete").attr("disabled", false);
            $modalDelete.modal('hide');
            toastr.success(data.message, 'Éxito',
                {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            setTimeout( function () {
                fillPeriods();
            }, 2000 )
        },
        error: function (data) {
            if( data.responseJSON.message && !data.responseJSON.errors )
            {
                toastr.error(data.responseJSON.message, 'Error',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }
            for ( var property in data.responseJSON.errors ) {
                toastr.error(data.responseJSON.errors[property], 'Error',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }

            $("#btn-submitDelete").attr("disabled", false);
        },
    });
}
