let $entriesComplete=[];
let $entriesJson=[];
let $materialsComplete=[];
let $locationsComplete=[];
let $items=[];

$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    var $modalItems = $('#modalItems');

    var table = $('#dynamic-table').DataTable( {
        bAutoWidth: false,
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
        },
    } );

    $.ajax({
        url: "/dashboard/get/json/areas/in/output",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            //console.log(json[0]);
            for (var i=0; i<json.length; i++)
            {
                console.log(json[i].area);
                $('#area').append($("<option>", {
                    value: json[i].id,
                    text: json[i].area
                }));
            }

        }
    });

    $('#btn-outputs').on('click', showOutputs);

});

function showOutputs(){
    $('#btn-outputs').attr("disabled", true);
    var id_area = $('#area').val();
    var startDate = $('#startDate').val();
    var endDate = $('#endDate').val();
    if (!area) {
        toastr.error('Seleccione un área.', 'ERROR');
        $('#btn-outputs').attr("disabled", false);
        return;
    }
    if (!startDate || !endDate) {
        toastr.error('Ambos campos de fechas son obligatorios.', 'ERROR');
        $('#btn-outputs').attr("disabled", false);
        return;
    }
    var diffMonths = calculateMonthDifference(startDate, endDate);
    console.log(diffMonths);
    if (diffMonths > 6) {
        toastr.warning('El tiempo de demora será mayor, ya que la diferencia de meses es mayor a 6.', 'PRECAUCIÓN', { toastClass: 'black-text' });
    }
    console.log(id_area);
    function calculateMonthDifference(startDate, endDate) {
        var start = new Date(startDate);
        var end = new Date(endDate);

        var diff = (end.getFullYear() - start.getFullYear()) * 12 + end.getMonth() - start.getMonth();

        return diff;
    }
    $("#element_loader").LoadingOverlay("show", {
        background  : "rgba(236, 91, 23, 0.5)"
    });
    $.ajax({
        url: "/dashboard/get/json/outputs/simple/of/materialxarea/"+id_area,
        type: 'GET',
        dataType: 'json',
        data: {
            startDate: startDate,
            endDate: endDate
        },
        success: function (json) {
            console.log(json);
            var table = $('#dynamic-table').DataTable();
            table.clear().draw();
            for (var i=0; i<json.length; i++)
            {
                table.row.add( [
                    "Solicitud-"+json[i].output,
                    json[i].order_execution,
                    json[i].description,
                    json[i].date,
                    json[i].user_request,
                    json[i].user_responsible,
                    json[i].indicator,
                    '<button title="Ver materiales pedidos"  class="btn btn-outline-primary btn-sm btn-view-items" data-output-id="' + json[i].output + '"><i class="fa fa-plus-square"></i> </button>',
                ]).draw();

            }
            $(document).on("click", ".btn-view-items", function () {
                var outputId = $(this).data("output-id");
                showItems(outputId);
            });
            $("#element_loader").LoadingOverlay("hide", true);
            $('#btn-outputs').attr("disabled", false);

        }
    });
}
function showItems(output_id) {
    $('#table-materiales').html('');
    $.ajax({
        url: "/dashboard/get/json/items/output/areas/" + output_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var k=0; k<json.materials.length; k++)
            {
                renderTemplateMaterials(k+1, json.materials[k].material_complete.code, json.materials[k].material, json.materials[k].quantity);
                //$materials.push(json[i].material);
            }

            $('#modalItems').modal('show');
        }
    });
}

function renderTemplateMaterials(id, code, material, cantidad) {
    var clone = activateTemplate('#template-materiale');
    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-quantity]").innerHTML = cantidad;
    $('#table-materiales').append(clone);
}
function renderTemplateOutput(output, order_execution, description, date, user_request, user_responsible,indicator) {
    var clone = activateTemplate('#template-output');
    clone.querySelector("[data-output]").innerHTML = 'Solicitud-'+output;
    clone.querySelector("[data-order_execution]").innerHTML = order_execution;
    clone.querySelector("[data-description]").innerHTML = description;
    clone.querySelector("[data-date]").innerHTML = date;
    clone.querySelector("[data-user_request]").innerHTML = user_request;
    clone.querySelector("[data-user_responsible]").innerHTML = user_responsible;
    clone.querySelector("[data-indicator]").innerHTML = indicator;
    var btnViewItems = clone.querySelector(".btn-view-items");
    btnViewItems.setAttribute("data-output-id", output);
    btnViewItems.addEventListener("click", function () {
        console.log("Ver Items Clicked for Output: " + output);
    });

    $('#body-outputs').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}
$(document).on("click", ".btn-view-items", function () {
    var outputId = $(this).data("output-id");
    console.log("Ver Items Clicked for Output ID: " + outputId);
});



