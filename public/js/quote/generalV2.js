$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);
    /*var tabla = $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/get/finance/works",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: 'year' },

            { data: 'customer' },
            { data: 'responsible' },
            { data: 'area' },

            { data: 'quote'},
            { data: 'type' },
            { data: 'order_customer' },
            { data: 'description'},
            { data: 'initiation'},
            { data: 'delivery'},
            { data: 'state_work'},

            { data: 'act_of_acceptance'},
            { data: 'state_act_of_acceptance'},
            { data: 'docier'},
            { data: 'hes'},

            { data: 'advancement'},
            { data: 'amount_advancement'},
            { data: 'subtotal'},
            { data: 'igv'},
            { data: 'total'},
            { data: 'detraction'},
            { data: 'amount_detraction'},
            { data: 'discount_factoring'},
            { data: 'amount_include_detraction'},

            { data: 'pay_condition'},
            { data: 'invoiced'},
            { data: 'number_invoice'},
            { data: 'year_invoice'},
            { data: 'month_invoice'},
            { data: 'date_issue'},
            { data: 'date_admission'},
            { data: 'days'},
            { data: 'date_programmed'},

            { data: 'bank'},
            { data: 'state'},
            { data: 'year_paid'},
            { data: 'month_paid'},
            { data: 'date_paid'},
            { data: 'observation'},
            { data: 'revision'},
            { data: null,
                title: '',
                wrap: true,
                sortable:false,
                "render": function (item)
                {
                    var text = '';
                    text = text + ' <button data-formEditTrabajo="' + item.id + '" ' +
                        ' class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar Información Trabajo"><i class="fas fa-tools fa-lg"></i></button>';
                    text = text + ' <button data-formEditFacturacion="' + item.id + '" ' +
                        ' class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar Información Facturación"><i class="fas fa-donate fa-lg"></i></button>';

                    return text;
                }
            },

        ],
        "aaSorting": [],

        select: {
            style: 'single'
        },
        language: {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "Ningún dato disponible en esta tabla",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "search": "Buscar:",
            "infoThousands": ",",
            "loadingRecords": "Cargando...",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "aria": {
                "sortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad",
                "collection": "Colección",
                "colvisRestore": "Restaurar visibilidad",
                "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                "copySuccess": {
                    "1": "Copiada 1 fila al portapapeles",
                    "_": "Copiadas %d fila al portapapeles"
                },
                "copyTitle": "Copiar al portapapeles",
                "csv": "CSV",
                "excel": "Excel",
                "pageLength": {
                    "-1": "Mostrar todas las filas",
                    "1": "Mostrar 1 fila",
                    "_": "Mostrar %d filas"
                },
                "pdf": "PDF",
                "print": "Imprimir"
            },
            "autoFill": {
                "cancel": "Cancelar",
                "fill": "Rellene todas las celdas con <i>%d<\/i>",
                "fillHorizontal": "Rellenar celdas horizontalmente",
                "fillVertical": "Rellenar celdas verticalmentemente"
            },
            "decimal": ",",
            "searchBuilder": {
                "add": "Añadir condición",
                "button": {
                    "0": "Constructor de búsqueda",
                    "_": "Constructor de búsqueda (%d)"
                },
                "clearAll": "Borrar todo",
                "condition": "Condición",
                "conditions": {
                    "date": {
                        "after": "Despues",
                        "before": "Antes",
                        "between": "Entre",
                        "empty": "Vacío",
                        "equals": "Igual a",
                        "not": "No",
                        "notBetween": "No entre",
                        "notEmpty": "No Vacio"
                    },
                    "number": {
                        "between": "Entre",
                        "empty": "Vacio",
                        "equals": "Igual a",
                        "gt": "Mayor a",
                        "gte": "Mayor o igual a",
                        "lt": "Menor que",
                        "lte": "Menor o igual que",
                        "not": "No",
                        "notBetween": "No entre",
                        "notEmpty": "No vacío"
                    },
                    "string": {
                        "contains": "Contiene",
                        "empty": "Vacío",
                        "endsWith": "Termina en",
                        "equals": "Igual a",
                        "not": "No",
                        "notEmpty": "No Vacio",
                        "startsWith": "Empieza con"
                    }
                },
                "data": "Data",
                "deleteTitle": "Eliminar regla de filtrado",
                "leftTitle": "Criterios anulados",
                "logicAnd": "Y",
                "logicOr": "O",
                "rightTitle": "Criterios de sangría",
                "title": {
                    "0": "Constructor de búsqueda",
                    "_": "Constructor de búsqueda (%d)"
                },
                "value": "Valor"
            },
            "searchPanes": {
                "clearMessage": "Borrar todo",
                "collapse": {
                    "0": "Paneles de búsqueda",
                    "_": "Paneles de búsqueda (%d)"
                },
                "count": "{total}",
                "countFiltered": "{shown} ({total})",
                "emptyPanes": "Sin paneles de búsqueda",
                "loadMessage": "Cargando paneles de búsqueda",
                "title": "Filtros Activos - %d"
            },
            "select": {
                "1": "%d fila seleccionada",
                "_": "%d filas seleccionadas",
                "cells": {
                    "1": "1 celda seleccionada",
                    "_": "$d celdas seleccionadas"
                },
                "columns": {
                    "1": "1 columna seleccionada",
                    "_": "%d columnas seleccionadas"
                }
            },
            "thousands": ".",
            "datetime": {
                "previous": "Anterior",
                "next": "Proximo",
                "hours": "Horas"
            }
        }

    } );

    $('#filterYear, #filterCustomer, #filterStateWork, #filterState').on('change', function() {
        var filterYear = $('#filterYear').val();
        var filterCustomer = $('#filterCustomer').val();
        var filterStateWork = $('#filterStateWork').val();
        var filterState = $('#filterState').val();

        tabla.columns(0).search(filterYear).columns(1).search(filterCustomer).columns(8).search(filterStateWork).columns(28).search(filterState).draw();
    });
*/
    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    getDataQuotes(1);

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
    });

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSearch);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $formDelete = $('#formDelete');
    $formDelete.on('submit', destroySubCategory);
    $modalDelete = $('#modalDelete');
    $formDecimals = $('#formDecimals');
    $modalDecimals = $('#modalDecimals');
    $(document).on('click', '[data-delete]', cancelQuote);

    $(document).on('click', '[data-confirm]', confirmQuote);

    $(document).on('click', '[data-send]', sendQuote);

    $(document).on('click', '[data-renew]', renewQuote);

    $('#btn-export').on('click', exportQuotes);

    $(document).on('click', '[data-deselevar]', deselevarQuote);

    $(document).on('click', '[data-decimals]', showModalDecimals);

    $('#btn-changeDecimals').on('click', saveDecimals);
});

var $formDelete;
var $modalDelete;
var $modalDecimals;
var $formDecimals;

var $permissions;

function showModalDecimals() {
    $('#decimals').val('');
    $('#decimals').trigger('change');
    var quote_id = $(this).data('decimals');
    $.ajax({
        url: "/dashboard/get/decimals/quote/"+quote_id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $formDecimals.find("[id=quote_id]").val(quote_id);
            $('#decimals').val(data.decimals);
            $('#decimals').trigger('change');

            $modalDecimals.modal('show');
        }
    });
}

function saveDecimals() {
    var button = $(this);
    button.attr("disabled", true);
    var form = $formDecimals[0];
    $.confirm({
        icon: 'fas fa-toggle-on',
        theme: 'modern',
        closeIcon: false,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de guardar la elección?',
        content: 'Mostrar decimales implica que el PDF va a mostrar los valores con decimales.<br>Ocultar decimales implica que el PDF mostrará valores sin decimales.'  ,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/change/decimals/quote',
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: new FormData(form),
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                button.attr("disabled", false);
                                $modalDecimals.modal('hide');
                            }, 2000 )
                        },
                        error: function (data) {
                            button.attr("disabled", false);
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    button.attr("disabled", false);
                    $.alert("No se guardó ninguún dato.");
                },
            },
        }
    });

}

function deselevarQuote() {
    var quote_id = $(this).data('deselevar');

    $.confirm({
        icon: 'fas fa-level-down',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de regresar a enviado esta cotización?',
        content: 'Se va a regresar el estado enviado y se inhabilitará la orden de ejecución.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/deselevar/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                    //$.alert('Your name is ' + name);
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Se canceló el proceso.");
                },
            },
        }
    });

}

function exportQuotes() {
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");
    var typeQuote = $("input[name='typeQuote']:checked").val();

    console.log(start);
    console.log(end);
    console.log(startDate);
    console.log(endDate);

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
            content: 'Si no hay fechas se descargará todas las cotizaciones',
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
                            type: typeQuote
                        };

                        $.alert('Descargando archivo ...');

                        var url = "/dashboard/exportar/reporte/cotizaciones/?" + $.param(query);

                        window.location = url;

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Exportación cancelada.");
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
            type: typeQuote
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

        var url = "/dashboard/exportar/reporte/cotizaciones/?" + $.param(query);

        window.location = url;

    }

}

function renewQuote() {
    var quote_id = $(this).data('renew');
    var button = $(this);
    button.attr("disabled", true);
    $.confirm({
        icon: 'fas fa-sync',
        theme: 'modern',
        closeIcon: false,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de renovar esta cotización?',
        content: 'Se va a crear una nueva cotización pero con todos los mismos contenidos.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/renew/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                button.attr("disabled", false);
                                location.href = data.url;
                            }, 2000 )
                        },
                        error: function (data) {
                            button.attr("disabled", false);
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                    //$.alert('Your name is ' + name);
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    button.attr("disabled", false);
                    $.alert("Cotización no recotizada.");
                },
            },
        }
    });

}

function cancelQuote() {
    var quote_id = $(this).data('delete');
    var description = $(this).data('name');

    $.confirm({
        icon: 'fas fa-frown',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: '¿Está seguro de eliminar esta cotización?',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/destroy/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización anulada.");
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Anulación cancelada.");
                },
            },
        },
    });

}

function confirmQuote() {
    var quote_id = $(this).data('confirm');
    var description = $(this).data('name');

    var status_send = $(this).data('status');

    if ( status_send == 0 )
    {
        toastr.error('No puede confirmar sin antes enviar.', 'Error',
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
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        return;
    }

    $.confirm({
        icon: 'fas fa-smile',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: '¿Está seguro de confirmar esta cotización? ',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/confirm/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización confirmada con éxito.");
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Anulación cancelada.");
                },
            },
        },
    });

}

function sendQuote() {
    var quote_id = $(this).data('send');
    var description = $(this).data('name');

    $.confirm({
        icon: 'fas fa-paper-plane',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: '¿Está seguro de enviar esta cotización? ',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/send/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización enviada con éxito.");
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Anulación cancelada.");
                },
            },
        },
    });

}

function destroySubCategory() {
    event.preventDefault();
    // Obtener la URL
    var deleteUrl = $formDelete.data('url');
    $.ajax({
        url: deleteUrl,
        method: 'POST',
        data: new FormData(this),
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
            $modalDelete.modal('hide');
            setTimeout( function () {
                location.reload();
            }, 2000 )
        },
        error: function (data) {
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


        },
    });
}

function exportExcel() {
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");

    console.log(start);
    console.log(end);
    console.log(startDate);
    console.log(endDate);

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
            content: 'Si no hay fechas se descargará todos los ingresos',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        var query = {
                            start: start,
                            end: end
                        };

                        $.alert('Descargando archivo ...');

                        var url = "/dashboard/exportar/reporte/egresos/proveedores/?" + $.param(query);

                        window.location = url;

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Exportación cancelada.");
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
            end: end
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

        var url = "/dashboard/exportar/reporte/egresos/proveedores/?" + $.param(query);

        window.location = url;

    }

}

function showDataSearch() {
    getDataQuotes(1)
}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataQuotes(numberPage)
}

function getDataQuotes($numberPage) {
    var description_quote = $('#description_quote').val();
    var year = $('#year').val();
    var code = $('#code').val();
    var order = $('#order').val();
    var customer = $('#customer').val();
    var stateQuote = $('#stateQuote').val();
    var startDate = $('#start').val();
    var endDate = $('#end').val();

    $.get('/dashboard/get/data/quotes/v2/'+$numberPage, {
        description_quote:description_quote,
        year: year,
        code: code,
        order: order,
        customer: customer,
        stateQuote: stateQuote,
        startDate: startDate,
        endDate: endDate,
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataQuotesEmpty(data);
        } else {
            renderDataQuotes(data);
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

function renderDataQuotesEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' cotizaciones');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataQuotes(data) {
    var dataQuotes = data.data;
    var pagination = data.pagination;
    console.log(dataQuotes);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' cotizaciones.');
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
    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-description]").innerHTML = data.description;
    clone.querySelector("[data-date_quote]").innerHTML = data.date_quote;
    clone.querySelector("[data-date_validate]").innerHTML = data.date_validate;
    clone.querySelector("[data-deadline]").innerHTML = data.deadline;
    clone.querySelector("[data-time_delivery]").innerHTML = data.time_delivery;
    clone.querySelector("[data-customer]").innerHTML = data.customer;
    clone.querySelector("[data-order]").innerHTML = data.order;
    clone.querySelector("[data-total_igv]").innerHTML = data.total_igv;
    clone.querySelector("[data-total]").innerHTML = data.total;
    clone.querySelector("[data-currency]").innerHTML = data.currency;
    clone.querySelector("[data-state]").innerHTML = data.stateText;
    clone.querySelector("[data-created_at]").innerHTML = data.created_at;
    clone.querySelector("[data-creator]").innerHTML = data.creator;
    clone.querySelector("[data-decimals]").innerHTML = data.decimals;

    var botones = clone.querySelector("[data-buttons]");

    if ( data.state == "created" )
    {
        var cloneBtnCreated = activateTemplate('#template-btn_created');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnCreated.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnCreated.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnCreated.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnCreated.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('send_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-enviar]").setAttribute("data-send", data.id);
            cloneBtnCreated.querySelector("[data-enviar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-enviar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/cotizacion/'+data.id;
            cloneBtnCreated.querySelector("[data-editar]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-editar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-confirmar]").setAttribute("data-status", data.send_state);
            cloneBtnCreated.querySelector("[data-confirmar]").setAttribute("data-confirm", data.id);
            cloneBtnCreated.querySelector("[data-confirmar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-confirmar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnCreated.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnCreated.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnCreated.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnCreated);
    }

    if ( data.state == "send" ) {
        var cloneBtnSend = activateTemplate('#template-btn_send');
        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnSend.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnSend.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnSend.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnSend.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/cotizacion/'+data.id;
            cloneBtnSend.querySelector("[data-editar]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-editar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
            cloneBtnSend.querySelector("[data-confirmar]").setAttribute("data-status", data.send_state);
            cloneBtnSend.querySelector("[data-confirmar]").setAttribute("data-confirm", data.id);
            cloneBtnSend.querySelector("[data-confirmar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnSend.querySelector("[data-confirmar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnSend.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnSend.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnSend.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnSend.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnSend.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnSend.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnSend.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnSend.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnSend.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnSend);
    }

    if ( data.state == "confirm" ) {
        var cloneBtnConfirm = activateTemplate('#template-btn_confirm');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnConfirm.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnConfirm.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-editar]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-editar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('adjust_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ajustar/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-ajustar_porcentajes]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-ajustar_porcentajes]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-raise", data.id);
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-code", data.code);
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-elevar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/cotizar/soles/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-cotizar_soles]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-cotizar_soles]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnConfirm.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnConfirm.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnConfirm.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnConfirm);
    }

    if ( data.state == "raise" ) {
        var cloneBtnRaised = activateTemplate('#template-btn_raised');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnRaised.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnRaised.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnRaised.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnRaised.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-editar]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-editar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('adjust_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ajustar/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-ajustar_porcentajes]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-ajustar_porcentajes]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-raise", data.id);
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-code", data.code);
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-elevar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/cotizar/soles/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-cotizar_soles]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-cotizar_soles]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnConfirm.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnConfirm.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnConfirm.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnRaised);
    }

    if ( data.state == "VB_finance" ) {
        var cloneBtnVB_finance = activateTemplate('#template-btn_VB_finance');
        botones.append(cloneBtnVB_finance);
    }

    if ( data.state == "VB_operation" ) {
        var cloneBtnVB_operation = activateTemplate('#template-btn_VB_operation');
        botones.append(cloneBtnVB_operation);
    }

    if ( data.state == "close" ) {
        var cloneBtnClose = activateTemplate('#template-btn_close');
        botones.append(cloneBtnClose);
    }

    if ( data.state == "canceled" ) {
        var cloneBtnCanceled = activateTemplate('#template-btn_canceled');
        botones.append(cloneBtnCanceled);
    }

    /*clone.querySelector("[data-formEditFacturacion]").setAttribute('data-formEditFacturacion', data.id);
    clone.querySelector("[data-formEditFacturacion]").setAttribute('data-type', data.type);*/

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

function submitFormEditFacturacion(event) {
    event.preventDefault();
    var button = $(this);

    button.prop('disabled', true);
    var createUrl = $formEditFacturacion.data('url');
    $.ajax({
        url: createUrl, // La URL a la que enviarás la solicitud
        method: 'POST', // El método HTTP que utilizarás (en este caso, POST)
        data: new FormData($('#formEditFacturacion')[0]),
        processData:false,
        contentType:false,
        success: function(data) {
            // Esta función se ejecutará si la solicitud fue exitosa
            // La variable 'response' contendrá los datos devueltos por el servidor (según el tipo de datos especificado en 'dataType')
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
            $modalEditFacturacion.modal('hide');
            setTimeout( function () {
                button.attr("disabled", false);
                showDataSearch();
                //location.reload();
            }, 100 )
        },
        error: function(data) {
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
            button.attr("disabled", false);
            // Esta función se ejecutará si ocurre un error en la solicitud
            // Puedes utilizar las variables 'jqXHR', 'textStatus' y 'errorThrown' para obtener información sobre el error
        }
    });
}

function showModalEditFacturacion() {
    var invoice_id = $(this).attr('data-formEditFacturacion');
    var type = $(this).attr('data-type');

    $formEditFacturacion.find("[id=invoice_id]").val(invoice_id);
    $formEditFacturacion.find("[id=type]").val(type);

    if ( invoice_id == "" )
    {
        invoice_id = "nn";
    }

    $.get("/dashboard/get/info/facturacion/expense/supplier/"+invoice_id+"/"+type, function (data) {
        console.log(data);

        //$formEditFacturacion.find("[id=invoice_id]").val(invoice_id);

        $('#state').val(data.state);
        $('#state').trigger('change');

        $modalEditFacturacion.modal('show');
    }, "json");

}