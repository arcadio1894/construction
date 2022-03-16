$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    console.log($permissions);
    $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/all/order/execution",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: 'id' },
            { data: 'order_execution' },
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
                title: 'Acciones',
                wrap: true,
                sortable:false,
                "render": function (item)
                {
                    var text = '';

                    text = text + ' <button data-show="'+item.id+'" data-name="'+item.description_quote+'" '+
                            ' class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver materiales"><i class="fas fa-truck-loading"></i></button>';

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

    /*$formDelete = $('#formDelete');
    $formDelete.on('submit', destroySubCategory);
    $modalDelete = $('#modalDelete');
    $(document).on('click', '[data-delete]', cancelQuote);

    $(document).on('click', '[data-raise]', raiseQuote);
    $(document).on('click', '[data-raise2]', raise2Quote);

    $(document).on('click', '[data-renew]', renewQuote);*/
    $(document).on('click', '[data-show]', showMaterialsInOrder);
    $modalItems = $('#modalItems');
});

var $formDelete;
var $modalItems;

var $permissions;

function showMaterialsInOrder() {
    $('#table-items').html('');
    $('#table-consumables').html('');
    var quote_id = $(this).data('show');
    $.ajax({
        url: "/dashboard/get/json/materials/quote/almacen/"+quote_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            //
            for (var i=0; i<json.arrayMaterials.length; i++)
            {
                renderTemplateMaterial(json.arrayMaterials[i].id, json.arrayMaterials[i].code, json.arrayMaterials[i].material, json.arrayMaterials[i].length, json.arrayMaterials[i].width, json.arrayMaterials[i].percentage, json.arrayMaterials[i].quantity);
                //$materials.push(json[i].material);
            }

            for (var j=0; j<json.arrayConsumables.length; j++)
            {
                renderTemplateConsumable(json.arrayConsumables[j].id, json.arrayConsumables[j].code, json.arrayConsumables[j].material, json.arrayConsumables[j].quantity);
                //$materials.push(json[i].material);
            }

        }
    });
    $modalItems.modal('show');
}

function renderTemplateMaterial(id, code, material, length, width, percentage, quantity) {
    var clone = activateTemplate('#template-item');

    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-length]").innerHTML = length;
    clone.querySelector("[data-width]").innerHTML = width;
    clone.querySelector("[data-quantity]").innerHTML = quantity;

    $('#table-items').append(clone);
}

function renderTemplateConsumable(id, code, material, cantidad) {
    var clone = activateTemplate('#template-consumable');
    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-quantity]").innerHTML = cantidad;
    $('#table-consumables').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}
