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

    getDataRotations(1);

    $(document).on('click', '[data-item]', showData);

    $('#btn-newRotation').on('click', saveNewRotation);
});

let $modalLocations;
let $modalEntries;

function saveNewRotation() {

    $("#btn-newRotation").attr("disabled", true);

    $.confirm({
        icon: 'fas fa-smile',
        theme: 'modern',
        closeIcon: false,
        animation: 'zoom',
        type: 'green',
        title: 'Guardar corte de rotación',
        content: 'Se tomará la fecha del último corte hasta la actualidad. A excepción del primer corte',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.get('/dashboard/store/rotation/material/', function(data) {
                        /*toastr.success(data.message, 'Éxito', {
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
                        });*/
                        $("#btn-newRotation").attr("disabled", false);
                        toastr.success(data.message, 'Éxito', {
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
                        getDataRotations(1);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        // Función de error, se ejecuta cuando la solicitud GET falla
                        console.error(textStatus, errorThrown);
                        if (jqXHR.responseJSON.message && !jqXHR.responseJSON.errors) {
                            toastr.error(jqXHR.responseJSON.message, 'Error', {
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
                        for (var property in jqXHR.responseJSON.errors) {
                            toastr.error(jqXHR.responseJSON.errors[property], 'Error', {
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
                        $("#btn-newRotation").attr("disabled", false);
                    }, 'json')
                        .done(function() {
                            // Configuración de encabezados
                            var headers = {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            };

                            $.ajaxSetup({
                                headers: headers
                            });
                        });

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Corte de rotación cancelada.");
                    $("#btn-newRotation").attr("disabled", false);
                },
            },
        },
    });


}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataRotations(numberPage)
}

function getDataRotations($numberPage) {

    $.get('/dashboard/get/data/rotations/v2/'+$numberPage, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataRotationsEmpty(data);
        } else {
            renderDataRotations(data);
        }

    }).fail(function(jqXHR, textStatus, errorThrown) {
        // Función de error, se ejecuta cuando la solicitud GET falla
        console.error(textStatus, errorThrown);
        if (jqXHR.responseJSON.message && !jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.message, 'Error', {
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
        for (var property in jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.errors[property], 'Error', {
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
    }, 'json')
        .done(function() {
            // Configuración de encabezados
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            };

            $.ajaxSetup({
                headers: headers
            });
        });
}

function renderDataRotationsEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' cortes de rotación');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataRotations(data) {
    var dataQuotes = data.data;
    var pagination = data.pagination;
    console.log(dataQuotes);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' cortes de rotación.');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    for (let j = 0; j < dataQuotes.length ; j++) {
        renderDataTable(dataQuotes[j]);
    }

    if (pagination.currentPage > 1)
    {
        renderPreviousPage(pagination.currentPage-1);
    }

    if (pagination.totalPages > 1)
    {
        if (pagination.currentPage > 3)
        {
            renderItemPage(1);

            if (pagination.currentPage > 4) {
                renderDisabledPage();
            }
        }

        for (var i = Math.max(1, pagination.currentPage - 2); i <= Math.min(pagination.totalPages, pagination.currentPage + 2); i++)
        {
            renderItemPage(i, pagination.currentPage);
        }

        if (pagination.currentPage < pagination.totalPages - 2)
        {
            if (pagination.currentPage < pagination.totalPages - 3)
            {
                renderDisabledPage();
            }
            renderItemPage(i, pagination.currentPage);
        }

    }

    if (pagination.currentPage < pagination.totalPages)
    {
        renderNextPage(pagination.currentPage+1);
    }
}

function renderDataTableEmpty() {
    var clone = activateTemplate('#item-table-empty');
    $("#body-table").append(clone);
}

function renderDataTable(data) {
    var clone = activateTemplate('#item-table');
    clone.querySelector("[data-id]").innerHTML = data.id;
    clone.querySelector("[data-fecha]").innerHTML = data.fecha;
    clone.querySelector("[data-user]").innerHTML = data.user;

    $("#body-table").append(clone);

    $('[data-toggle="tooltip"]').tooltip();
}

function renderPreviousPage($numberPage) {
    var clone = activateTemplate('#previous-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

function renderDisabledPage() {
    var clone = activateTemplate('#disabled-page');
    $("#pagination").append(clone);
}

function renderItemPage($numberPage, $currentPage) {
    var clone = activateTemplate('#item-page');
    if ( $numberPage == $currentPage )
    {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-active]").setAttribute('class', 'page-item active');
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    } else {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    }

    $("#pagination").append(clone);
}

function renderNextPage($numberPage) {
    var clone = activateTemplate('#next-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

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

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}