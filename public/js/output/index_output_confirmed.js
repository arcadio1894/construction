let $entriesComplete=[];
let $entriesJson=[];
let $materialsComplete=[];
let $locationsComplete=[];
let $items=[];
var $permissions;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    /*$("#element_loader").LoadingOverlay("show", {
        background  : "rgba(236, 91, 23, 0.5)"
    });*/

    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

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

    $modalAddItems = $('#modalAddItems');

    $modalItems = $('#modalItems');

    $modalConfirm = $('#modalConfirm');

    $modalDeleteTotal = $('#modalDeleteTotal');

    $formDeleteTotal = $('#formDeleteTotal');

    $modalItemsDelete = $('#modalDeletePartial');

    $formConfirm = $('#formConfirm');

    $formConfirm.on('submit', confirmOutput);

    $formDeleteTotal.on('submit', deleteTotalOutput);

    $(document).on('click', '[data-details]', showItems);

    $(document).on('click', '[data-deleteTotal]', showModalDeleteTotal);

    $(document).on('click', '[data-deletePartial]', showModalDeletePartial);

    $(document).on('click', '[data-itemDelete]', deletePartialOutput);

    $(document).on('click', '[data-materials]', showMaterialsInQuote);

    $(document).on('click', '[data-return]', showModalReturnMaterials);

    $(document).on('click', '[data-itemReturn]', returnItemMaterials);

    $modalItemsMaterials = $('#modalItemsMaterials');
    $modalReturnMaterials = $('#modalReturnMaterials');

    $('body').tooltip({
        selector: '[data-toggle]'
    });

    $(document).on('click', '[data-confirm]', openModalConfirm);

    $('#btn-allconfirm').on('click', confirmAllOutputs);

    $formEdit = $('#formEdit');
    $("#btn-submitEdit").on("click", editOrderExecution);
    $(document).on('click', '[data-edit]', showModalEdit);
    $modalEdit = $('#modalEdit');

    $('#btn-outputs').on('click', showOutputs);
});

let $modalEdit;
var $formEdit;

let $modalItems;

let $modalConfirm;

let $formCreate;

var $formConfirm;

let $modalDeleteTotal;

let $modalItemsDelete;

var $formDeleteTotal;

let $modalAddItems;

let $caracteres = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

let $longitud = 20;

let $modalItemsMaterials;

let $modalReturnMaterials;

function showOutputs(){
    $('#btn-outputs').attr("disabled", true);
    // Obtencion de datos y verificacion de las fechas
    var start  = $('#start').val();
    var end  = $('#end').val();
    var id  = $('#code').val();
    var order_execution  = $('#order_execution').val();
    var requesting_user  = $('#requesting_user').val();

    if ( start == '' || end == '' )
    {
        toastr.error('Es obligatorio seleccionar un rango de fechas', 'Error',
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
        $('#btn-outputs').attr("disabled", false);
        return;
    } else {

        $.confirm({
            icon: 'fas fa-search',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'Iniciar la búsqueda',
            content: 'Espere un momento para realizar la búsqueda',
            buttons: {
                confirm: {
                    text: 'BUSCAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);
                        $("#element_loader").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });
                        var query = {
                            start: start,
                            end: end,
                            id: id,
                            order_execution: order_execution,
                            requesting_user: requesting_user
                        };

                        $.ajax({
                            url: "/dashboard/get/json/outputs/filters/confirmed/?"+ $.param(query),
                            type: 'GET',
                            dataType: 'json',
                            success: function (json) {

                                var table = $('#dynamic-table').DataTable();
                                table.clear().draw();
                                for (var i=0; i<json.length; i++)
                                {
                                    var text = '';

                                    text = text + '<button data-toggle="tooltip" data-placement="top" title="Materiales en la cotización" data-materials="'+json[i].execution_order+'" class="btn btn-outline-info btn-sm"><i class="fas fa-hammer"></i> </button> ' +
                                        '<button data-toggle="tooltip" data-placement="top" title="Ver materiales pedidos" data-details="'+json[0].id+'" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus-square"></i> </button> ' +
                                        '<button data-toggle="tooltip" data-placement="top" title="Anular total" data-deleteTotal="'+json[0].id+'" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i> </button>  '+
                                        '<button data-toggle="tooltip" data-placement="top" title="Anular parcial" data-deletePartial="'+json[0].id+'" class="btn btn-outline-warning btn-sm"><i class="fa fa-trash"></i> </button>';

                                    text = text + '<button data-toggle="tooltip" data-placement="top" title="Devolver materiales" data-return="'+json[0].id+'" class="btn btn-outline-dark btn-sm"><i class="fas fa-exchange-alt"></i> </button> ';

                                    if ( json[i].description_quote == 'No hay datos' )
                                    {
                                        text = text + '<button data-toggle="tooltip" data-placement="top" title="Editar orden de ejecución" data-edit="' + json[0].id + '" data-execution_order="' + json[i].execution_order + '" class="btn btn-outline-secondary btn-sm"><i class="fas fa-edit"></i> </button>  ';
                                    }

                                    table.row.add( [
                                        "Solicitud-"+json[i].id,
                                        json[i].execution_order,
                                        json[i].description_quote,
                                        json[i].request_date,
                                        json[i].requesting_user,
                                        json[i].responsible_user,
                                        '<span class="badge bg-secondary">Solicitud confirmada</span>',
                                        text, // Acciones
                                    ]).draw();
                                }
                                $("#element_loader").LoadingOverlay("hide", true);
                            }
                        });
                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Busqueda cancelada.");
                    },
                },
            },
        });

        $('#btn-outputs').attr("disabled", false);
    }

}

function showModalEdit() {
    var output_id = $(this).data('edit');
    var execution_order = $(this).data('execution_order');

    $modalEdit.find('[id=output_id]').val(output_id);
    $modalEdit.find('[id=execution_order]').val(execution_order);

    $modalEdit.modal('show');
}

function editOrderExecution() {
    console.log('Llegue');
    $("#btn-submitEdit").attr("disabled", true);
    var formulario = $('#formEdit')[0];
    var form = new FormData(formulario);
    event.preventDefault();
    // Obtener la URL
    var editdUrl = $formEdit.data('url');
    $.ajax({
        url: editdUrl,
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
            $modalEdit.modal('hide');
            setTimeout( function () {
                $("#btn-submitEdit").attr("disabled", false);
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
                        "timeOut": "4000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }
            $("#btn-submitEdit").attr("disabled", false);

        },
    });
}

function confirmAllOutputs() {
    var url = $(this).data('url');
    $.confirm({
        icon: 'fas fa-check-double',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: '¿Está seguro de confirmar todas las solicitudes atendidas?',
        content: 'Acepte para continuar',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: url,
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
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Confirmación cancelada.");
                },
            },
        },
    });
}

function showMaterialsInQuote() {
    $modalItemsMaterials.find('[id=code_quote]').html('');
    $('#table-items-quote').html('');
    $('#table-consumables-quote').html('');
    var code_execution = $(this).data('materials');
    $.ajax({
        url: "/dashboard/get/json/materials/order/execution/almacen/"+code_execution,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            //
            for (var i=0; i<json.arrayMaterials.length; i++)
            {
                renderTemplateMaterialQuote(json.arrayMaterials[i].id, json.arrayMaterials[i].code, json.arrayMaterials[i].material, json.arrayMaterials[i].length, json.arrayMaterials[i].width, json.arrayMaterials[i].percentage, json.arrayMaterials[i].quantity);
                //$materials.push(json[i].material);
            }

            for (var j=0; j<json.arrayConsumables.length; j++)
            {
                renderTemplateConsumableQuote(json.arrayConsumables[j].id, json.arrayConsumables[j].code, json.arrayConsumables[j].material, json.arrayConsumables[j].quantity);
                //$materials.push(json[i].material);
            }
            $modalItemsMaterials.find('[id=code_quote]').html(json.quote.code);
        }
    });

    $modalItemsMaterials.modal('show');
}

function renderTemplateMaterialQuote(id, code, material, length, width, percentage, quantity) {
    var clone = activateTemplate('#template-item-quote');

    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-length]").innerHTML = length;
    clone.querySelector("[data-width]").innerHTML = width;
    clone.querySelector("[data-quantity]").innerHTML = quantity;

    $('#table-items-quote').append(clone);
}

function renderTemplateConsumableQuote(id, code, material, cantidad) {
    var clone = activateTemplate('#template-consumable-quote');
    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-quantity]").innerHTML = cantidad;
    $('#table-consumables-quote').append(clone);
}

function showModalDeleteTotal() {
    var output_id = $(this).data('deletetotal');

    $modalDeleteTotal.find('[id=output_id]').val(output_id);
    $modalDeleteTotal.find('[id=descriptionDeleteTotal]').html('Solicitud-'+output_id);

    $modalDeleteTotal.modal('show');
}

function showModalDeletePartial() {
    $('#table-itemsDelete').html('');
    var output_id = $(this).data('deletepartial');
    //console.log(output_id);
    $.ajax({
        url: "/dashboard/get/json/items/output/"+output_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            console.log(json);
            for (var i=0; i<json.array.length; i++)
            {
                renderTemplateItemDetailDelete(json.array[i].id, json.array[i].code, json.array[i].material, json.array[i].length, json.array[i].width, json.array[i].percentage, json.array[i].detail_id, json.array[i].id_item);

                //renderTemplateItemDetailDelete(json.array[i].id, json.array[i].id_item, output_id, json.array[i].material, json.array[i].code, json.array[i].length, json.array[i].width);
            }

        }
    });
    $modalItemsDelete.modal('show');
}

function showItems() {
    $('#table-items').html('');
    $('#table-consumables').html('');
    $('#table-materiales').html('');
    var output_id = $(this).data('details');
    $.ajax({
        url: "/dashboard/get/json/items/output/"+output_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            //
            for (var i=0; i<json.array.length; i++)
            {
                renderTemplateItemDetail(json.array[i].id, json.array[i].material, json.array[i].code, json.array[i].length, json.array[i].width, json.array[i].price, json.array[i].location, json.array[i].state, json.array[i].detail_id);
                //$materials.push(json[i].material);
            }

            for (var k=0; k<json.materials.length; k++)
            {
                renderTemplateMaterials(k+1, json.materials[k].material_complete.code, json.materials[k].material, json.materials[k].quantity);
                //$materials.push(json[i].material);
            }

            for (var j=0; j<json.consumables.length; j++)
            {
                renderTemplateConsumable(json.consumables[j].id, json.consumables[j].material_complete.code, json.consumables[j].material, json.consumables[j].quantity);
                //$materials.push(json[i].material);
            }

        }
    });
    $modalItems.modal('show');
}

function showModalReturnMaterials() {
    $('#table-itemsReturn').html('');
    var output_id = $(this).data('return');
    console.log(output_id);
    $.ajax({
        url: "/dashboard/get/json/items/output/devolver/"+output_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            console.log(json);
            for (var i=0; i<json.array.length; i++)
            {
                //for (var i=0; i<json.array.length; i++)
                //{
                renderTemplateItemReturn(json.array[i].id, json.array[i].code, json.array[i].material, json.array[i].length, json.array[i].width, json.array[i].percentage, json.array[i].detail_id, json.array[i].id_item);
                //$materials.push(json[i].material);
                //}
                //renderTemplateItemDetailDelete(json[i].id, json[i].id_item, output_id, json[i].material, json[i].code);
            }

        }
    });
    $modalReturnMaterials.modal('show');
}

function renderTemplateItemReturn(id, code, material, length, width, percentage, output_detail, id_item) {
    var clone = activateTemplate('#template-itemReturn');
    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-length]").innerHTML = length;
    clone.querySelector("[data-width]").innerHTML = width;
    clone.querySelector("[data-percentage]").innerHTML = percentage;
    clone.querySelector("[data-itemReturn]").setAttribute('data-itemReturn', id_item);
    clone.querySelector("[data-itemReturn]").setAttribute('data-output', output_detail);
    $('#table-itemsReturn').append(clone);
}

function renderTemplateItemDetail(id, material, code, length, width, price, location, state, output_detail) {
    var status = (state === 'good') ? '<span class="badge bg-success">En buen estado</span>' :
        (state === 'bad') ? '<span class="badge bg-secondary">En mal estado</span>' :
            'Personalizado';
    var clone = activateTemplate('#template-item');
    if ( status !== 'Personalizado' )
    {
        clone.querySelector("[data-i]").innerHTML = id;
        clone.querySelector("[data-material]").innerHTML = material;
        clone.querySelector("[data-code]").innerHTML = code;
        clone.querySelector("[data-itemCustom]").setAttribute('style', 'display:none');
        clone.querySelector("[data-length]").innerHTML = length;
        clone.querySelector("[data-width]").innerHTML = width;
        clone.querySelector("[data-price]").innerHTML = price;
        clone.querySelector("[data-location]").innerHTML = location;
        clone.querySelector("[data-state]").innerHTML = status;
        $('#table-items').append(clone);
    } else {
        clone.querySelector("[data-i]").innerHTML = id;
        clone.querySelector("[data-material]").innerHTML = material;
        clone.querySelector("[data-code]").innerHTML = code;
        clone.querySelector("[data-itemCustom]").setAttribute('data-itemCustom', output_detail);
        clone.querySelector("[data-length]").innerHTML = length;
        clone.querySelector("[data-width]").innerHTML = width;
        clone.querySelector("[data-price]").innerHTML = price;
        clone.querySelector("[data-location]").innerHTML = location;
        clone.querySelector("[data-state]").innerHTML = status;
        $('#table-items').append(clone);
    }
}

function renderTemplateMaterials(id, code, material, cantidad) {
    var clone = activateTemplate('#template-materiale');
    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-quantity]").innerHTML = cantidad;
    $('#table-materiales').append(clone);
}

function renderTemplateConsumable(id, code, material, cantidad) {
    var clone = activateTemplate('#template-consumable');
    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-quantity]").innerHTML = cantidad;
    $('#table-consumables').append(clone);
}

function renderTemplateItemDetailDelete(id, code, material, length, width, percentage, output_detail, id_item) {
    var clone = activateTemplate('#template-itemDelete');
    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-length]").innerHTML = length;
    clone.querySelector("[data-width]").innerHTML = width;
    clone.querySelector("[data-percentage]").innerHTML = percentage;
    clone.querySelector("[data-itemDelete]").setAttribute('data-itemDelete', id_item);
    clone.querySelector("[data-itemDelete]").setAttribute('data-output', output_detail);
    $('#table-itemsDelete').append(clone);
}

function openModalConfirm() {
    var output_id = $(this).data('confirm');

    $modalConfirm.find('[id=output_id]').val(output_id);
    $modalConfirm.find('[id=descriptionAttend]').html('Solicitud-'+output_id);

    $modalConfirm.modal('show');
}

function deleteTotalOutput() {
    console.log('Llegue');
    event.preventDefault();
    // Obtener la URL
    var attendUrl = $formDeleteTotal.data('url');
    $.ajax({
        url: attendUrl,
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
            $modalDeleteTotal.modal('hide');
            setTimeout( function () {
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
                        "timeOut": "4000",
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

function deletePartialOutput() {
    console.log('Llegue');
    event.preventDefault();
    $(this).attr("disabled", true);
    var button = $(this);
    // Obtener la URL
    var idOutputDetail = $(this).data('output');
    var idItem = $(this).data('itemdelete');
    $.ajax({
        url: '/dashboard/destroy/output/'+idOutputDetail+'/item/'+idItem,
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData:false,
        contentType:'application/json; charset=utf-8',
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
            button.attr("disabled", false);
            $(this).parent().parent().remove();
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
                        "timeOut": "4000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }
            button.attr("disabled", false);

        },
    });

}

function returnItemMaterials() {
    console.log('Llegue');
    event.preventDefault();
    $(this).attr("disabled", true);
    var button = $(this);
    // Obtener la URL
    var idOutputDetail = $(this).data('output');
    var idItem = $(this).data('itemreturn');
    $.ajax({
        url: '/dashboard/return/output/'+idOutputDetail+'/item/'+idItem,
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData:false,
        contentType:'application/json; charset=utf-8',
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
            button.attr("disabled", false);
            $(this).parent().parent().remove();
            $modalReturnMaterials.modal('hide');
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
                        "timeOut": "4000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }
            button.attr("disabled", false);

        },
    });

}

function confirmOutput() {
    //console.log('Llegue');
    event.preventDefault();
    // Obtener la URL
    var attendUrl = $formConfirm.data('url');
    $.ajax({
        url: attendUrl,
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
            $modalConfirm.modal('hide');
            setTimeout( function () {
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
                        "timeOut": "4000",
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

function addItems() {
    if( $('#material_search').val().trim() === '' )
    {
        toastr.error('Debe elegir un material', 'Error',
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

    if( $('#quantity').val().trim() === '' || $('#quantity').val()<0 )
    {
        toastr.error('Debe ingresar una cantidad', 'Error',
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

    if( $('#price').val().trim() === '' || $('#price').val()<0 )
    {
        toastr.error('Debe ingresar un precio adecuado', 'Error',
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

    let material_name = $('#material_search').val();
    $modalAddItems.find('[id=material_selected]').val(material_name);
    $modalAddItems.find('[id=material_selected]').prop('disabled', true);
    let material_quantity = $('#quantity').val();
    $modalAddItems.find('[id=quantity_selected]').val(material_quantity);
    $modalAddItems.find('[id=quantity_selected]').prop('disabled', true);
    let material_price = $('#price').val();
    $modalAddItems.find('[id=price_selected]').val(material_price);
    $modalAddItems.find('[id=price_selected]').prop('disabled', true);

    $('#body-items').html('');

    for (var i = 0; i<material_quantity; i++)
    {
        renderTemplateItem();
        $('.select2').select2();
    }

    $('.locations').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
            minLength: 1 /* Specify minimum characters required for showing suggestions */
        },
        {
            limit: 12,
            source: substringMatcher($locations)
        });

    $modalAddItems.modal('show');

    /*$items.push({
        "productId" : sku,
        "qty" : qty,
        "price" : price
    });*/
}

function rand_code($caracteres, $longitud){
    var code = "";
    for (var x=0; x < $longitud; x++)
    {
        var rand = Math.floor(Math.random()*$caracteres.length);
        code += $caracteres.substr(rand, 1);
    }
    return code;
}

function deleteItem() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().parent().remove();
}

function renderTemplateMaterial(id, price, material, item, location, state) {
    var clone = activateTemplate('#materials-selected');
    clone.querySelector("[data-id]").innerHTML = id;
    clone.querySelector("[data-description]").innerHTML = material;
    clone.querySelector("[data-item]").innerHTML = item;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-state]").innerHTML = state;
    clone.querySelector("[data-price]").innerHTML = price;
    $('#body-materials').append(clone);
}

function renderTemplateItem() {
    var clone = activateTemplate('#template-item');
    clone.querySelector("[data-series]").setAttribute('value', rand_code($caracteres, $longitud));
    $('#body-items').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

