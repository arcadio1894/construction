$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    console.log($permissions);
    $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/all/order/normal",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: 'id' },
            { data: 'code' },
            { data: null,
                title: 'Fecha Orden',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.date_order).format('DD-MM-YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Fecha Llegada',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.date_arrival).format('DD-MM-YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Proveedor',
                wrap: true,
                "render": function (item)
                {
                    if ( item.supplier !== null )
                        return '<p> '+ item.supplier.business_name +'</p>';
                    else
                        return '<p> Sin proveedor </p>'
                }
            },
            { data: null,
                title: 'Aprovado por',
                wrap: true,
                "render": function (item)
                {
                    if ( item.approved_user !== null )
                        return '<p> '+ item.approved_user.name +'</p>';
                    else
                        return '<p> Sin aprobar </p>'
                }
            },
            { data: null,
                title: 'Moneda',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ item.currency_order +'</p>';
                }
            },
            { data: null,
                title: 'Total',
                wrap: true,
                "render": function (item)
                {
                    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
                        return item.total;
                    } else {
                        return '';
                    }

                }
            },
            { data: null,
                title: 'Acciones',
                wrap: true,
                sortable:false,
                "render": function (item)
                {
                    var text = '';
                    if ( $.inArray('list_orderPurchaseNormal', $permissions) !== -1 ) {
                        text = text + '<a target="_blank" href="'+document.location.origin+ '/dashboard/imprimir/orden/compra/'+item.id+'" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir Orden"><i class="fa fa-print"></i> </a>  ';
                    }
                    if ( $.inArray('list_orderPurchaseNormal', $permissions) !== -1 ) {
                        text = text + '<a href="'+document.location.origin+ '/dashboard/ver/orden/compra/normal/'+item.id+
                            '" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Orden"><i class="fa fa-eye"></i></a> ';
                    }
                    if ( $.inArray('update_orderPurchaseNormal', $permissions) !== -1 ) {
                        text = text + '<a href="'+document.location.origin+ '/dashboard/editar/orden/compra/normal/'+item.id+
                            '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pen"></i></a> ';
                    }
                    if ( $.inArray('destroy_orderPurchaseNormal', $permissions) !== -1 ) {
                        text = text + ' <button data-delete="'+item.id+'" data-name="'+item.code+'" '+
                            ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-trash"></i></button>';
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

    $(document).on('click', '[data-delete]', cancelOrden);

});

var $formDelete;
var $modalDelete;

var $permissions;

function cancelOrden() {
    var order_id = $(this).data('delete');
    var description = $(this).data('name');

    $.confirm({
        icon: 'fas fa-frown',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: '¿Está seguro de eliminar esta orden de compra?',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/destroy/order/purchase/normal/'+order_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Orden de compra normal anulada.");
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
