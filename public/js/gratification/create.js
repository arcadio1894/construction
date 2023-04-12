$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    //fillGratifications();
    $(document).on('click', '[data-action]', showModalGratification);
    $(document).on('click', '[data-edit]', showModalEditGratification);
    $(document).on('click', '[data-delete]', showModalDeleteGratification);

    $formCreate = $('#formCreate');
    $modalCreate = $('#modalCreate');
    $formEdit = $('#formEdit');
    $modalEdit = $('#modalEdit');
    $formDelete = $('#formDelete');
    $modalDelete = $('#modalDelete');

    $('#btn-submit').on('click', storeGratification);
    $('#btn-submitEdit').on('click', updateGratification);
    $('#btn-submitDelete').on('click', deleteGratification);

});

var $formCreate;
var $modalCreate;
var $formEdit;
var $modalEdit;
var $formDelete;
var $modalDelete;

function showModalGratification() {
    var worker_id = $(this).attr('data-worker_id');
    var name_worker = $(this).attr('data-worker');
    var period_id = $(this).attr('data-period');
    var period_name = $(this).attr('data-period_name');

    $modalCreate.find('[id=period_id]').val(period_id);
    $modalCreate.find('[id=worker_id]').val(worker_id);
    $modalCreate.find('[id=name_worker]').val(name_worker);
    $modalCreate.find('[id=period_name]').val(period_name);

    $modalCreate.modal('show');
}

function showModalEditGratification() {
    var description_period = $(this).data('description_period');
    var period = $(this).data('period');
    var worker = $(this).data('worker');
    var worker_id = $(this).data('worker_id');
    var amount = $(this).data('amount');
    var date = $(this).data('date');
    var gratification_id = $(this).data('gratification_id');

    $modalEdit.find('[id=period_id]').val(period);
    $modalEdit.find('[id=worker_id]').val(worker_id);
    $modalEdit.find('[id=name_worker]').val(worker);
    $modalEdit.find('[id=period_name]').val(description_period);
    $modalEdit.find('[id=gratification_id]').val(gratification_id);
    $modalEdit.find('[id=amount]').val(amount);
    $modalEdit.find('[id=dateEdit]').val(date);

    $modalEdit.modal('show');
}

function showModalDeleteGratification() {
    var description_period = $(this).data('description_period');
    var period = $(this).data('period');
    var worker = $(this).data('worker');
    var worker_id = $(this).data('worker_id');
    var amount = $(this).data('amount');
    var date = $(this).data('date');
    var gratification_id = $(this).data('gratification_id');

    $modalDelete.find('[id=period_id]').val(period);
    $modalDelete.find('[id=worker_id]').val(worker_id);
    $modalDelete.find('[id=name_worker]').val(worker);
    $modalDelete.find('[id=period_name]').val(description_period);
    $modalDelete.find('[id=gratification_id]').val(gratification_id);
    $modalDelete.find('[id=amount]').val(amount);
    $modalDelete.find('[id=dateDelete]').val(date);

    $modalDelete.modal('show');
}

function fillGratifications() {
    var period_id = $('#period').val();
    $("#content-body").LoadingOverlay("show", {
        background  : "rgba(236, 91, 23, 0.5)"
    });
    $.ajax({
        url: "/dashboard/all/gratifications/by/period/"+period_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var period = json.period;
            var gratifications = json.gratifications;
            var workersNotRegistered = json.workersNotRegistered;

            $('#body-users').html('');
            $('#body-gratifications').html('');

            renderTemplateGratification(period, gratifications, workersNotRegistered);

            $("#content-body").LoadingOverlay("hide", true);
        }
    });


}

function renderTemplateGratification(period, gratifications, workersNotRegistered) {

    for (let i = 0; i < workersNotRegistered.length; i++) {

        var clone1 = activateTemplate('#template-user');

        clone1.querySelector("[data-id]").innerHTML = workersNotRegistered[i].id;
        clone1.querySelector("[data-name]").innerHTML = workersNotRegistered[i].first_name+' '+workersNotRegistered[i].last_name;
        clone1.querySelector("[data-action]").setAttribute('data-worker_id', workersNotRegistered[i].worker_id);
        clone1.querySelector("[data-action]").setAttribute('data-worker', workersNotRegistered[i].worker_name);
        clone1.querySelector("[data-action]").setAttribute('data-period', period.id);
        clone1.querySelector("[data-action]").setAttribute('data-period_name', period.description);

        $('#body-users').append(clone1)
    }

    for (let i = 0; i < gratifications.length; i++) {
        var clone = activateTemplate('#template-gratification');

        clone.querySelector("[data-worker]").innerHTML = gratifications[i].worker_name;
        clone.querySelector("[data-period]").innerHTML = gratifications[i].period;
        clone.querySelector("[data-date]").innerHTML = gratifications[i].date;
        clone.querySelector("[data-amount]").innerHTML = gratifications[i].amount;
        clone.querySelector("[data-edit]").setAttribute('data-gratification_id', workersNotRegistered[i].gratification_id);
        clone.querySelector("[data-edit]").setAttribute('data-date', workersNotRegistered[i].date);
        clone.querySelector("[data-edit]").setAttribute('data-amount', workersNotRegistered[i].amount);
        clone.querySelector("[data-edit]").setAttribute('data-worker_id', workersNotRegistered[i].worker_id);
        clone.querySelector("[data-edit]").setAttribute('data-worker', workersNotRegistered[i].worker_name);
        clone.querySelector("[data-edit]").setAttribute('data-period', workersNotRegistered[i].period_id);
        clone.querySelector("[data-edit]").setAttribute('data-description_period', workersNotRegistered[i].period);
        clone.querySelector("[data-delete]").setAttribute('data-worker_id', workersNotRegistered[i].worker_id);
        clone.querySelector("[data-delete]").setAttribute('data-gratification_id', workersNotRegistered[i].gratification_id);
        clone.querySelector("[data-delete]").setAttribute('data-period', workersNotRegistered[i].period_id);

        $('#body-gratifications').append(clone);
    }
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function storeGratification() {
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
                $("#btn-submit").attr("disabled", false);
                location.reload();
            }, 1500 )
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

function updateGratification() {
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
                $("#btn-submitEdit").attr("disabled", false);
                location.reload();
            }, 1500 )
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

function deleteGratification() {
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
                $("#btn-submitDelete").attr("disabled", false);
                location.reload();
            }, 1500 )
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
