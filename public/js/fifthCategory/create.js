$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    //fillGratifications();
    $('#btn-new').on('click', showModalCreateFifthCategory);

    $(document).on('click', '[data-edit]', showModalEditFifthCategory);
    $(document).on('click', '[data-delete]', showModalDeleteFifthCategory);

    $formCreate = $('#formCreate');
    $modalCreate = $('#modalCreate');
    $formEdit = $('#formEdit');
    $modalEdit = $('#modalEdit');
    $formDelete = $('#formDelete');
    $modalDelete = $('#modalDelete');

    $('#btn-submit').on('click', storeFifthCategory);
    $('#btn-submitEdit').on('click', updateFifthCategory);
    $('#btn-submitDelete').on('click', deleteFifthCategory);

});

var $formCreate;
var $modalCreate;
var $formEdit;
var $modalEdit;
var $formDelete;
var $modalDelete;

function showModalCreateFifthCategory() {
    var worker_id = $(this).attr('data-worker_id');
    var name_worker = $(this).attr('data-worker_name');
    $modalCreate.find('[id=worker_id]').val(worker_id);
    $modalCreate.find('[id=name_worker]').val(name_worker);

    $modalCreate.modal('show');
}

function showModalEditFifthCategory() {
    var worker = $(this).data('worker');
    var worker_id = $(this).data('worker_id');
    var amount = $(this).data('amount');
    var date = $(this).data('date');
    var fifthCategory_id = $(this).data('fifthcategory_id');

    $modalEdit.find('[id=worker_id]').val(worker_id);
    $modalEdit.find('[id=name_worker]').val(worker);
    $modalEdit.find('[id=fifthCategory_id]').val(fifthCategory_id);
    $modalEdit.find('[id=amount]').val(amount);
    $modalEdit.find('[id=dateEdit]').val(date);

    $modalEdit.modal('show');
}

function showModalDeleteFifthCategory() {
    var worker = $(this).data('worker');
    var worker_id = $(this).data('worker_id');
    var amount = $(this).data('amount');
    var date = $(this).data('date');
    var fifthCategory_id = $(this).data('fifthcategory_id');

    $modalDelete.find('[id=worker_id]').val(worker_id);
    $modalDelete.find('[id=name_worker]').val(worker);
    $modalDelete.find('[id=fifthCategory_id]').val(fifthCategory_id);
    $modalDelete.find('[id=amount]').val(amount);
    $modalDelete.find('[id=dateDelete]').val(date);

    $modalDelete.modal('show');
}

function storeFifthCategory() {
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

function updateFifthCategory() {
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

function deleteFifthCategory() {
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
