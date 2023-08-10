$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);
    var tabla = $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/get/finance/works",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: 'year' },
            { data: 'customer' },
            { data: 'responsible' },
            { data: 'initiation'},
            { data: 'delivery'},
            { data: 'quote'},
            { data: 'order_customer' },
            { data: 'description'},
            { data: 'state_work'},
            { data: 'act_of_acceptance'},
            { data: 'state_act_of_acceptance'},
            { data: 'pay_condition'},
            { data: 'advancement'},
            { data: 'amount_advancement'},
            { data: 'subtotal'},
            { data: 'igv'},
            { data: 'total'},
            { data: 'detraction'},
            { data: 'amount_detraction'},
            { data: 'amount_include_detraction'},
            { data: 'invoiced'},
            { data: 'number_invoice'},
            { data: 'month_invoice'},
            { data: 'date_issue'},
            { data: 'date_admission'},
            { data: 'days'},
            { data: 'date_programmed'},
            { data: 'bank'},
            { data: 'state'},
            { data: 'date_paid'},
            { data: 'observation'},
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

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $formEditTrabajo = $('#formEditTrabajo');
    $formEditFacturacion = $('#formEditFacturacion');

    $modalEditTrabajo = $('#modalEditTrabajo');
    $modalEditFacturacion = $('#modalEditFacturacion');

    $('#btnSubmitFormEditTrabajo').on('click', submitFormEditTrabajo);
    $('#btnSubmitFormEditFacturacion').on('click', submitFormEditFacturacion);

    $(document).on('click', '[data-formEditTrabajo]', showModalEditTrabajo);

    $(document).on('click', '[data-formEditFacturacion]', showModalEditFacturacion);

    $('#invoiced').on('change', function() {
        var opcionSeleccionada = $(this).val();

        if (opcionSeleccionada == 'y') {
            $('#number_invoice').attr("readonly", false);
            $('#month_invoice').attr("disabled", false);
            $('#date_issue').attr("readonly", false);
            $('#date_admission').attr("readonly", false);
            $('#bank_id').attr("disabled", false);
        } else if (opcionSeleccionada == 'n') {
            $('#number_invoice').attr("readonly", true);
            $('#month_invoice').attr("disabled", true);
            $('#date_issue').attr("readonly", true);
            $('#date_admission').attr("readonly", true);
            $('#bank_id').attr("disabled", true);
        }
    });
});

var $formEditTrabajo;
var $formEditFacturacion;
var $modalEditTrabajo;
var $modalEditFacturacion;

var $permissions;

function submitFormEditTrabajo(event) {
    event.preventDefault();
    var button = $(this);

    button.prop('disabled', true);
    var createUrl = $formEditTrabajo.data('url');
    $.ajax({
        url: createUrl, // La URL a la que enviarás la solicitud
        method: 'POST', // El método HTTP que utilizarás (en este caso, POST)
        data: new FormData($('#formEditTrabajo')[0]),
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
            $modalEditTrabajo.modal('hide');
            setTimeout( function () {
                button.attr("disabled", false);
                location.reload();
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

function showModalEditTrabajo() {
    var financeWork_id = $(this).attr('data-formEditTrabajo');

    $formEditTrabajo.find("[id=financeWork_id]").val(financeWork_id);
    $.get("/dashboard/get/info/trabajo/finance/work/"+financeWork_id, function (data) {
        console.log(data);

        $formEditTrabajo.find("[id=financeWork_id]").val(financeWork_id);
        $('#act_of_acceptance').val(data.act_of_acceptance);
        $('#act_of_acceptance').trigger('change');
        if ( data.state_act_of_acceptance != null )
        {
            $('#state_act_of_acceptance').val(data.state_act_of_acceptance);
            $('#state_act_of_acceptance').trigger('change');
        }

        $modalEditTrabajo.modal('show');
    }, "json");


}

function showModalEditFacturacion() {
    var financeWork_id = $(this).attr('data-formEditFacturacion');

    $formEditFacturacion.find("[id=financeWork_id]").val(financeWork_id);
    $.get("/dashboard/get/info/facturacion/finance/work/"+financeWork_id, function (data) {
        console.log(data);

        $formEditFacturacion.find("[id=financeWork_id]").val(financeWork_id);

        $('#advancement').val(data.advancement);
        $('#advancement').trigger('change');
        $('#amount_advancement').val(data.amount_advancement);
        $('#invoiced').val(data.invoiced);
        $('#invoiced').trigger('change');

        if ( data.invoiced != null && data.invoiced == 'n' )
        {
            $('#number_invoice').attr("readonly", true);
            $('#number_invoice').val(data.number_invoice);
            $('#month_invoice').attr("disabled", true);
            $('#month_invoice').val(data.month_invoice);
            $('#month_invoice').trigger('change');
            $('#date_issue').attr("readonly", true);
            $('#date_issue').val(data.date_issue);
            $('#date_admission').attr("readonly", true);
            $('#date_admission').val(data.date_admission);
            $('#bank_id').attr("disabled", true);
            $('#bank_id').val(data.bank_id);
            $('#bank_id').trigger('change');
        } else {
            $('#number_invoice').attr("readonly", false);
            $('#number_invoice').val(data.number_invoice);
            $('#month_invoice').attr("disabled", false);
            $('#month_invoice').val(data.month_invoice);
            $('#month_invoice').trigger('change');
            $('#date_issue').attr("readonly", false);
            $('#date_issue').val(data.date_issue);
            $('#date_admission').attr("readonly", false);
            $('#date_admission').val(data.date_admission);
            $('#bank_id').attr("disabled", false);
            $('#bank_id').val(data.bank_id);
            $('#bank_id').trigger('change');
        }

        $('#state').val(data.state);
        $('#state').trigger('change');

        $('#date_paid').val(data.date_paid);

        $('#observation').val(data.observation);

        $modalEditFacturacion.modal('show');
    }, "json");

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
                    $.alert("Cotización no elevada.");
                    button.attr("disabled", false);
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