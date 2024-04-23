$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('#btn-refresh').on('click', getAmountReport);
    $('#btn-download').on('click', showModalLocations);

    $modalLocations = $('#modalLocations');

    $('#btn-submitDownload').on('click', getReportByLocation);

    $modalEntries = $('#modalEntries');

    $('#btn-downloadEntries').on('click', showModalEntries);
    $('#btn-submitExport').on('click', getReportExport);

    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });
});

let $modalLocations;
let $modalEntries;

function showModalEntries() {
    $modalEntries.modal('show');
}

function getReportExport() {
    $("#btn-submitExport").attr("disabled", true);
    let typeEntry = $('#typeEntry').val();
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");

    if ( start == '' || end == '' )
    {
        console.log('Sin fechas');
        $.confirm({
            icon: 'fas fa-file-excel',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó fechas',
            content: 'Si no hay fechas se descargará todos las entradas pero demorará bastante',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        var query = {
                            start: start,
                            end: end,
                            typeEntry: typeEntry
                        };

                        $.alert('Descargando archivo ...');

                        var url = "/dashboard/exportar/entradas/almacen/v2/?" + $.param(query);

                        window.location = url;
                        $("#btn-submitExport").attr("disabled", false);
                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Exportación cancelada.");
                        $("#btn-submitExport").attr("disabled", false);
                    },
                },
            },
        });
    } else {
        console.log('Con fechas');
        console.log(JSON.stringify(start));
        console.log(JSON.stringify(end));

        var query = {
            start: start,
            end: end,
            typeEntry: typeEntry
        };

        toastr.success('Descargando archivo ...', 'Éxito',
            {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "2000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });

        var url = "/dashboard/exportar/entradas/almacen/v2/?" + $.param(query);

        window.location = url;
        $("#btn-submitExport").attr("disabled", false);
    }
}

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