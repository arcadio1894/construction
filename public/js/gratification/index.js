$(document).ready(function () {

    fillPeriods();

    $('#btn-refresh').on('click', fillPeriods);

    $formCreate = $('#formCreate');

    $('#btn-submit').on('click', storeLoan);

});

var $formCreate;

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
        clone.querySelector("[data-percentage]").setAttribute('aria-valuenow', periods[i].gratifications.length/workers);
        clone.querySelector("[data-percentage]").setAttribute('style', 'width: '+periods[i].gratifications.length/workers+'%');
        clone.querySelector("[data-workers]").innerHTML = 'Num. Trabajadores: ' + workers;
        clone.querySelector("[data-link]").setAttribute('href', url);

        $('#body-periods').append(clone);
    }

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function storeLoan() {
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
