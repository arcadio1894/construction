$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('click', '[data-activityworker]', addWorker);

    $('#newActivity').on('click', addActivity);

    $('#btn-download').on('click', downloadTimeline)
});

var $permissions;

function downloadTimeline() {
    var timeline_id = $(this).data('id');

}

function addActivity() {
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    button.attr("disabled", true);

    /*$.ajax({
        url: "/dashboard/create/activity/timeline/",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            if ( json.error == 1 )
            {
                toastr.error(json.message, 'Error',
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

            } else {
                toastr.success(json.message, 'Éxito',
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
                    location.href = json.url;
                }, 2000 )
            }

        }
    });*/

    $.ajax({
        url: '/dashboard/create/activity/timeline/'+id_timeline,
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
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
            // Aqui se renderizará y se colocará los datos de la actividad
            renderTemplateActivity(id_timeline);
            button.attr("disabled", false);
        },
        error: function (data) {
            button.attr("disabled", false);
            toastr.error('Algo sucedió en el servidor.', 'Error',
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
        },
    });

    $('.quote_description').select2({
        placeholder: "Selecione cotización",
    });

}

function addWorker() {
    var render = $(this).parent().parent().next();

    renderTemplateWorker(render);

    $('.workers').select2({
        placeholder: "Seleccione colaborador",
    });
}

function renderTemplateActivity(data) {
    var clone = activateTemplate('#template-activity');

    $('#body-activities').append(clone);
}


function renderTemplateWorker(render) {
    var clone = activateTemplate('#template-worker');

    render.append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

