$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('#btn-refresh').on('click', getAmountReport);
    $('#btn-download').on('click', showModalLocations);

    $modalLocations = $('#modalLocations');

    $('#btn-submitDownload').on('click', getReportByLocation);
});

let $modalLocations;

function getAmountReport() {
    $("#element_loader").LoadingOverlay("show", {
        background  : "rgba(61, 215, 239, 0.4)"
    });
    $.get( "/dashboard/report/amount/items", function( data ) {
        console.log( data );
        $('#amount_dollars').html(parseFloat(data.amount_dollars).toFixed(2));
        $('#amount_soles').html(parseFloat(data.amount_soles).toFixed(2));
        $('#quantity_items').html(parseFloat(data.quantity_items).toFixed(2));
        $("#element_loader").LoadingOverlay("hide", true);
    });
}

function showModalLocations() {
    $modalLocations.modal('show');
}

function getReportByLocation() {
    $("#btn-submitDownload").attr("disabled", true);
    let location_id = $('#location').val();

    if ( location_id == '' || location_id == null )
    {
        toastr.error('Seleccione una ubicación.', 'Error',
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
        return;
    }

    $modalLocations.modal('hide');

    toastr.success('Descargando el reporte.', 'Éxito',
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

    $("#box").LoadingOverlay("show", {
        background  : "rgba(61, 215, 239, 0.4)"
    });

    var url = "/dashboard/report/excel/bd/materials/warehouse/"+location_id;

    window.location = url;

    $("#box").LoadingOverlay("hide", true);
    $("#btn-submitDownload").attr("disabled", false);

}