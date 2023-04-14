$(document).ready(function () {

    fillFifthCategory();

    $('#btn-refresh').on('click', fillFifthCategory);

    $('#btn-newWorker').on('click', showModalNewWorker);

    $(document).on('click', '[data-delete]', showModalDeleteWorker);

    $formCreate = $('#formCreate');
    $modalCreate = $('#modalCreate');
    $formDelete = $('#formDelete');
    $modalDelete = $('#modalDelete');

    $('#btn-submit').on('click', storeWorker);
    $('#btn-submitDelete').on('click', deleteFifthCategory);

    $selectWorker = $('#worker');

});

var $formCreate;
var $modalCreate;
var $formEdit;
var $modalEdit;
var $formDelete;
var $modalDelete;
var $selectWorker;

function fillWorkers() {
    $selectWorker.empty();
    $.get( "/dashboard/all/workers/not/fifthCategory/", function( data ) {
        $selectWorker.append($("<option>", {
            value: '',
            text: ''
        }));
        for ( var i=0; i<data.length; i++ )
        {
            $selectWorker.append($("<option>", {
                value: data[i].id,
                text: data[i].first_name+' '+data[i].last_name
            }));
        }
    });
}

function showModalDeleteWorker() {
    var nameWorker = $(this).data('worker_name');
    var worker_id = $(this).data('worker_id');

    $modalDelete.find('[id=worker_id]').val(worker_id);
    $modalDelete.find('[id=nameWorker]').html(nameWorker);
    $modalDelete.modal('show');
}

function showModalNewWorker() {
    $modalCreate.modal('show');
}

function fillFifthCategory() {
    $("#content-body").LoadingOverlay("show", {
        background  : "rgba(236, 91, 23, 0.5)"
    });

    $.ajax({
        url: "/dashboard/all/workers/fifthCategory/",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var workers = json.workers;

            $('#body-workers').html('');

            renderTemplateWorker(workers);

            $("#content-body").LoadingOverlay("hide", true);
        }
    });
}

function renderTemplateWorker(workers) {

    for (let i = 0; i < workers.length; i++) {
        var clone = activateTemplate('#template-worker');
        var url = document.location.origin+'/dashboard/crear/renta/quinta/categoria/'+workers[i].worker_id;
        var urlImage = document.location.origin+ '/images/users/'+workers[i].image;
        clone.querySelector("[data-username]").innerHTML = workers[i].worker_name;
        clone.querySelector("[data-function]").innerHTML = workers[i].workerFunction;
        clone.querySelector("[data-num_register]").innerHTML = workers[i].numRegister;
        clone.querySelector("[data-image]").setAttribute('src', urlImage);
        clone.querySelector("[data-register]").setAttribute('href', url);
        clone.querySelector("[data-delete]").setAttribute('data-worker_id', workers[i].worker_id);
        clone.querySelector("[data-delete]").setAttribute('data-worker_name', workers[i].worker_name);
        $('#body-workers').append(clone);
    }

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function storeWorker() {
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
                fillWorkers();
                fillFifthCategory();
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
                fillWorkers();
                fillFifthCategory();
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
