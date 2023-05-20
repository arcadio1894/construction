$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    console.log($permissions);
    $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/all/quotes/confirmed",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: 'id' },
            { data: 'code' },
            { data: 'description_quote' },
            { data: null,
                title: 'Fecha Cotización',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.date_quote).format('DD-MM-YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Fecha Válida',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.date_validate).format('DD-MM-YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Forma de pago',
                wrap: true,
                "render": function (item)
                {
                    if ( item.deadline !== null )
                        return '<p> '+ item.deadline.description +'</p>';
                    else
                        return '<p>'+ item.way_to_pay +' </p>'
                }
            },
            { data: 'delivery_time' },
            { data: null,
                title: 'Cliente',
                wrap: true,
                "render": function (item)
                {
                    if ( item.customer !== null )
                        return '<p> '+ item.customer.business_name +'</p>';
                    else
                        return '<p> Sin cliente </p>'
                }
            },
            { data: null,
                title: 'Orden Servicio',
                wrap: true,
                "render": function (item)
                {
                    if ( item.code_customer !== null )
                        return '<p> '+ item.code_customer +'</p>';
                    else
                        return '<p> Sin código </p>'
                }
            },
            { data: null,
                title: 'Total Sin IGV',
                wrap: true,
                "render": function (item)
                {
                    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
                        return ((item.total_quote)/1.18).toFixed(0);
                    } else {
                        return '';
                    }

                }
            },
            { data: null,
                title: 'Total Con IGV',
                wrap: true,
                "render": function (item)
                {
                    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
                        return ((item.total_quote)/1).toFixed(0);
                    } else {
                        return '';
                    }

                }
            },
            { data: null,
                title: 'Moneda',
                wrap: true,
                "render": function (item)
                {
                    var text = '';
                    if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
                        text = text + '<p> '+ item.currency_invoice +'</p>'
                    }
                    return text;
                }
            },
            { data: null,
                title: 'Estado',
                wrap: true,
                "render": function (item)
                {
                    if ( item.state === 'created' ) {
                        //return '<span class="badge bg-primary">Creada</span>';
                        if ( item.send_state == 1 || item.send_state == true )
                        {
                            return '<span class="badge bg-warning">Enviado</span>';
                        } else {
                            return '<span class="badge bg-primary">Creada</span>';
                        }
                    }

                    if (item.state === 'confirmed' && item.raise_status === 0){
                        return '<span class="badge bg-success">Confirmada</span>';
                    }

                    if (item.state === 'confirmed' && item.raise_status === 1){
                        return '<span class="badge bg-success">Elevada</span>';
                    }

                    if (item.state === 'canceled'){
                        return '<span class="badge bg-danger">Cancelada</span>';
                    }

                }
            },
            { data: null,
                title: 'Fecha Creación',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.created_at).format('DD-MM-YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Creador',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ item.users[0].user.name +'</p>'
                }
            },
            { data: null,
                title: '',
                wrap: true,
                sortable:false,
                "render": function (item)
                {
                    var text = '';
                    if ( $.inArray('show_quote', $permissions) !== -1 ) {
                        text = text + '<a href="'+document.location.origin+ '/dashboard/ver/cotizacion/'+item.id+
                            '" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Detalles"><i class="fa fa-eye"></i></a> ';
                    }
                    if ( item.state === 'confirmed' ) {
                        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
                            text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +
                                '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a> ';
                        }
                        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
                            text = text + '<a target="_blank" href="'+document.location.origin+ '/dashboard/imprimir/interno/'+item.id+
                                '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir interna"><i class="fa fa-print"></i></a> ';
                        }
                    }

                    if ( item.state === 'confirmed' && item.raise_status === 0 ) {
                        if ( $.inArray('adjust_quote', $permissions) !== -1 ) {
                            text = text + '<a href="'+document.location.origin+ '/dashboard/ajustar/cotizacion/'+item.id+
                                '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Ajustar porcentajes"><i class="fas fa-percentage"></i></a> ';
                        }
                        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                            text = text + ' <button data-raise="' + item.id + '" data-code="' + item.code_customer + '" data-name="' + item.description_quote + '" ' +
                                ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Elevar"><i class="fa fa-level-up-alt"></i></button>';
                        }
                        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
                            text = text + ' <button data-delete="'+item.id+'" data-name="'+item.description_quote+'" '+
                                ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-trash"></i></button>';
                        }

                    }

                    if ( item.state === 'confirmed' && item.raise_status === 1 ) {
                        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                            text = text + ' <button data-raise2="'+item.id+'" data-code="'+item.code_customer+'" data-name="'+item.description_quote+'" '+
                                ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar código"><i class="fa fa-chart-line"></i></button>';
                        }
                        if ( $.inArray('finish_quote', $permissions) !== -1 ) {
                            text = text + ' <button data-finish="'+item.id+'" data-name="'+item.description_quote+'" '+
                                ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar"><i class="fas fa-window-close"></i></button>';
                        }
                        // TODO: Boton para reemplazar materiales
                        if ( $.inArray('replacement_quote', $permissions) !== -1 ) {
                            text = text + '<a href="'+document.location.origin+ '/dashboard/reemplazar/materiales/cotizacion/'+item.id+
                                '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Reemplazar materiales"><i class="fas fa-recycle"></i></a> ';
                        }
                        if ( $.inArray('finishEquipment_quote', $permissions) !== -1 ) {
                            text = text + '<a href="'+document.location.origin+ '/dashboard/finalizar/equipos/cotizacion/'+item.id+
                                '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar equipos"><i class="fas fa-times-circle"></i></i></a> ';
                        }

                        //TODO: Boton de deselevar
                        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                            text = text + ' <button data-deselevar="'+item.id+'" data-name="'+item.description_quote+'" '+
                                ' class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Regresar a enviado"><i class="fas fa-level-down-alt"></i></button>';
                        }
                    }

                    if ( $.inArray('renew_quote', $permissions) !== -1 ) {
                        text = text + ' <button data-renew="'+item.id+'" data-name="'+item.description_quote+'" '+
                            ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Recotizar"><i class="fas fa-sync"></i></button>';
                    }

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

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $formDelete = $('#formDelete');
    $formDelete.on('submit', destroySubCategory);
    $modalDelete = $('#modalDelete');
    $(document).on('click', '[data-delete]', cancelQuote);
    $(document).on('click', '[data-finish]', finishQuote);

    $(document).on('click', '[data-raise]', raiseQuote);
    $(document).on('click', '[data-raise2]', raise2Quote);

    $(document).on('click', '[data-renew]', renewQuote);

    $(document).on('click', '[data-deselevar]', deselevarQuote);
});

var $formDelete;
var $modalDelete;

var $permissions;

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

function renewQuote() {
    var quote_id = $(this).data('renew');
    var button = $(this);
    button.attr("disabled", true);
    $.confirm({
        icon: 'fas fa-sync',
        theme: 'modern',
        closeIcon: true,
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
                    $.alert("Cotización no elevada.");
                },
            },
        },
        onOpen: function () {
            // after the modal is displayed.
            button.attr("disabled", true);
        },
        onClose: function () {
            // before the modal is hidden.
            button.attr("disabled", false);
        },
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

function finishQuote() {
    var quote_id = $(this).data('finish');
    var description = $(this).data('name');

    $.confirm({
        icon: 'fas fa-smile',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: '¿Está seguro de finalizar esta cotización?',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/finish/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                location.href = data.url;
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

function raiseQuote() {
    var quote_id = $(this).data('raise');
    var code = ($(this).data('code')===null) ? 'No tiene' : $(this).data('code');

    $.confirm({
        icon: 'fa fa-level-up-alt',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de elevar esta cotización a orden de ejecución?',
        content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<strong>Código actual: </strong>' + code +
            '<br><label>Ingrese el código del cliente aquí: </label>' +
            '<input type="text" placeholder="Código" class="name form-control" required />' +
            '</div>' +
            '</form>',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    var name = this.$content.find('.name').val();
                    if(!name || name.trim()===''){
                        $.alert('Ingrese un código válido');
                        return false;
                    }
                    $.ajax({
                        url: '/dashboard/raise/quote/'+quote_id+'/code/'+name,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización elevada.");
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
                    $.alert("Cotización no elevada.");
                },
            },
        }
    });

}

function raise2Quote() {
    var quote_id = $(this).data('raise2');
    var code = ($(this).data('code')===null) ? 'No tiene' : $(this).data('code');

    $.confirm({
        icon: 'fa fa-level-up-alt',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de modificar el codigo del cliente?',
        content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<strong>Código actual: </strong>' + code +
            '<br><label>Ingrese el código del cliente aquí: </label>' +
            '<input type="text" placeholder="Código" class="name form-control" required />' +
            '</div>' +
            '</form>',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    var name = this.$content.find('.name').val();
                    if(!name || name.trim()===''){
                        $.alert('Ingrese un código válido');
                        return false;
                    }
                    $.ajax({
                        url: '/dashboard/raise/quote/'+quote_id+'/code/'+name,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Código modificado correctamente.");
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
                    $.alert("Cotización no elevada.");
                },
            },
        }
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