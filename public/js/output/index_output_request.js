let $entriesComplete=[];
let $entriesJson=[];
let $materialsComplete=[];
let $locationsComplete=[];
let $items=[];

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    var table = $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/get/json/output/request",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: null,
                title: 'Solicitud',
                wrap: true,
                "render": function (item)
                {
                    return '<p> Solicitud-'+ item.id +'</p>';
                }
            },
            { data: null,
                title: 'Orden de ejecución',
                wrap: true,
                "render": function (item)
                {
                    return item.execution_order;
                }
            },
            /*{ data: 'execution_order' },*/
            { data: null,
                title: 'Descripción',
                wrap: true,
                "render": function (item)
                {
                    /*if ( item.quote != null )
                    {
                        return '<p>'+ item.quote.description_quote +'</p>';
                    } else {
                        return '<p> No hay datos </p>';
                    }*/
                    return '<p>'+ item.description_quote +'</p>';

                }
            },
            { data: null,
                title: 'Fecha de solicitud',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.request_date).format('DD-MM-YYYY H:m a') +'</p>'
                }
            },
            /*{ data: 'requesting_user.name' },*/
            { data: null,
                title: 'Usuario solicitante',
                wrap: true,
                "render": function (item)
                {
                    return item.requesting_user;
                }
            },
            /*{ data: 'responsible_user.name' },*/
            { data: null,
                title: 'Usuario responsable',
                wrap: true,
                "render": function (item)
                {
                    return item.responsible_user;
                }
            },
            { data: null,
                title: 'Estado',
                wrap: true,
                "render": function (item)
                {
                    var status = (item.state === 'created') ? '<span class="badge bg-success">Solicitud creada</span>' :
                        (item.state === 'attended') ? '<span class="badge bg-warning">Solicitud atendida</span>' :
                            (item.state === 'confirmed') ? '<span class="badge bg-secondary">Solicitud confirmada</span>' :
                                'Indefinido';
                    var custom = (item.custom == false) ? '': '<span class="badge bg-danger">Solicitud personalizada</span>';

                    return '<p> '+status+' </p>' + '<p> '+custom+' </p>'
                    //return item.state_custom;
                }
            },
            { data: null,
                title: 'Acciones',
                wrap: true,
                "render": function (item)
                {
                    var text = '';
                    if (item.state === 'attended' || item.state === 'confirmed')
                    {
                        text = text + '<button data-toggle="tooltip" data-placement="top" title="Materiales en la cotización" data-materials="'+item.execution_order+'" class="btn btn-outline-info btn-sm"><i class="fas fa-hammer"></i> </button> ' +
                            '<button data-toggle="tooltip" data-placement="top" title="Ver materiales pedidos" data-details="'+item.id+'" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus-square"></i> </button> ' +
                            '<button data-toggle="tooltip" data-placement="top" title="Anular total" data-deleteTotal="'+item.id+'" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i> </button>  '+
                            '<button data-toggle="tooltip" data-placement="top" title="Anular parcial" data-deletePartial="'+item.id+'" class="btn btn-outline-warning btn-sm"><i class="fa fa-trash"></i> </button>';
                            //'<button data-toggle="tooltip" data-placement="top" title="Anular por Cantidad" data-deleteQuantity="'+item.id+'" class="btn bg-orange color-palette btn-sm"><i class="fa fa-trash"></i> </button>';
                    } else {
                        text = text + '<button data-toggle="tooltip" data-placement="top" title="Materiales en la cotización" data-materials="'+item.execution_order+'" class="btn btn-outline-info btn-sm"><i class="fas fa-hammer"></i> </button> ' +
                            '<button data-toggle="tooltip" data-placement="top" title="Ver materiales pedidos" data-details="'+item.id+'" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus-square"></i> </button> ' +
                            '<button data-toggle="tooltip" data-placement="top" title="Anular total" data-deleteTotal="'+item.id+'" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i> </button>  '+
                            '<button data-toggle="tooltip" data-placement="top" title="Anular parcial" data-deletePartial="'+item.id+'" class="btn btn-outline-warning btn-sm"><i class="fa fa-trash"></i> </button>';
                            //'<button data-toggle="tooltip" data-placement="top" title="Anular por Cantidad" data-deleteQuantity="'+item.id+'" class="btn bg-orange color-palette btn-sm"><i class="fa fa-trash"></i> </button>';

                    }

                    if (item.state === 'confirmed')
                    {
                        text = text + '<button data-toggle="tooltip" data-placement="top" title="Devolver materiales" data-return="'+item.id+'" class="btn btn-outline-dark btn-sm"><i class="fas fa-exchange-alt"></i> </button> ';
                    }

                    /*var custom = false;
                    for (let value of item.details) {
                        if ( value.item_id == null )
                        {
                            custom = true;
                        }
                    }*/

                    if ( (item.custom == false) && (item.state !== 'attended' && item.state !== 'confirmed') )
                    {
                        if ( $.inArray('attend_request', $permissions) !== -1 ) {
                            text = text + '<button data-toggle="tooltip" data-placement="top" title="Atender" data-attend="' + item.id + '" class="btn btn-outline-success btn-sm"><i class="fa fa-check-square"></i> </button>  ';
                        }
                    }

                    if ( item.description_quote == 'No hay datos' )
                    {
                        text = text + '<button data-toggle="tooltip" data-placement="top" title="Editar orden de ejecución" data-edit="' + item.id + '" data-execution_order="' + item.execution_order + '" class="btn btn-outline-secondary btn-sm"><i class="fas fa-edit"></i> </button>  ';
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
        },

    } );

    $modalAddItems = $('#modalAddItems');

    //$(document).on('click', '[data-delete]', deleteItem);

    $modalItems = $('#modalItems');

    $modalAttend = $('#modalAttend');

    $modalDeleteTotal = $('#modalDeleteTotal');

    $formDeleteTotal = $('#formDeleteTotal');

    $formEdit = $('#formEdit');

    $modalItemsDelete = $('#modalDeletePartial');

    $modalItemsDeleteQuantity = $('#modalDeleteQuantity');

    $formAttend = $('#formAttend');

    //$formAttend.on('submit', attendOutput);
    $("#btn-submit").on("click", attendOutput);

    $("#btn-submitEdit").on("click", editOrderExecution);
    $(document).on('click', '[data-edit]', showModalEdit);
    $modalEdit = $('#modalEdit');

    $formDeleteTotal.on('submit', deleteTotalOutput);

    $(document).on('click', '[data-details]', showItems);

    $(document).on('click', '[data-deleteTotal]', showModalDeleteTotal);

    $(document).on('click', '[data-deletePartial]', showModalDeletePartial);

    $(document).on('click', '[data-itemDelete]', deletePartialOutput);

    $(document).on('click', '[data-materials]', showMaterialsInQuote);
    
    $(document).on('click', '[data-itemCustom]', goToCreateItem);

    $(document).on('click', '[data-return]', showModalReturnMaterials);

    $(document).on('click', '[data-itemReturn]', returnItemMaterials);

    $modalItemsMaterials = $('#modalItemsMaterials');
    $modalReturnMaterials = $('#modalReturnMaterials');

    $(document).on('click', '[data-deleteQuantity]', showModalDeleteQuantity);
    $(document).on('click', '[data-itemDeleteQuantity]', deleteOutputQuantity);

    /*$('body').tooltip({
        selector: '[data-toggle]'
    });
*/
    $(document).on('click', '[data-attend]', openModalAttend);
});

let $permissions;

let $modalEdit;

let $modalItems;

let $modalAttend;

let $modalDeleteTotal;

let $modalItemsDelete;

let $modalItemsDeleteQuantity;

let $formCreate;

var $formAttend;

var $formDeleteTotal;

var $formEdit;

let $modalAddItems;

let $caracteres = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

let $longitud = 20;

let $modalItemsMaterials;

let $modalReturnMaterials;

function showModalDeleteQuantity() {
    $('#table-itemsDeleteQuantity').html('');
    var output_id = $(this).data('deletequantity');
    console.log(output_id);
    $.ajax({
        url: "/dashboard/get/json/items/output/"+output_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            console.log(json);
            for (var i=0; i<json.materials.length; i++)
            {
                //for (var i=0; i<json.array.length; i++)
                //{
                renderTemplateItemDetailDeleteQuantity(i+1, json.materials[i].material_id, json.materials[i].code, json.materials[i].material, json.materials[i].quantity, output_id);
                //$materials.push(json[i].material);
                //}
                //renderTemplateItemDetailDelete(json[i].id, json[i].id_item, output_id, json[i].material, json[i].code);
            }

        }
    });
    $modalItemsDeleteQuantity.modal('show');
}

function deleteOutputQuantity() {
    console.log('Llegue');
    event.preventDefault();
    var button = $(this);
    button.attr("disabled", true);

    // Obtener la URL
    var idOutput = $(this).data('output');
    var idMaterial = $(this).data('itemDeleteQuantity');

    var quantityDelete = parseFloat($(this).parent().prev().children().val());
    var quantityRequest = parseFloat($(this).parent().prev().prev().html());

    if ( quantityDelete > quantityRequest )
    {
        toastr.error('No puede anular más de lo que fue solicitado', 'Error',
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
    }

    $.ajax({
        url: '/dashboard/destroy/output/'+idOutput+'/material/'+idMaterial+'/quantity/'+quantityDelete,
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
            button.parent().parent().remove();
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

    $modalItemsDeleteQuantity.modal('hide');
}

function renderTemplateItemDetailDeleteQuantity(id, material_id, code, material, quantity, output_id) {
    var clone = activateTemplate('#template-itemDeleteQuantity');
    clone.querySelector("[data-i]").innerHTML = id;
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-anular]").setAttribute('value', quantity);
    clone.querySelector("[data-anular]").setAttribute('data-anular', material_id);
    clone.querySelector("[data-itemDeleteQuantity]").setAttribute('data-itemDeleteQuantity', material_id);
    clone.querySelector("[data-itemDeleteQuantity]").setAttribute('data-output', output_id);
    $('#table-itemsDeleteQuantity').append(clone);
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

function goToCreateItem() {
    let id_detail = $(this).data('itemcustom');
    //console.log(id_detail);
    window.location.href = "/dashboard/crear/item/personalizado/" + id_detail;
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
                renderTemplateItemDetailDelete(json.array[i].id, json.array[i].code, json.array[i].material, json.array[i].length, json.array[i].width, json.array[i].percentage, json.array[i].detail_id, json.array[i].id_item);
                    //$materials.push(json[i].material);
                //}
                //renderTemplateItemDetailDelete(json[i].id, json[i].id_item, output_id, json[i].material, json[i].code);
            }

        }
    });
    $modalItemsDelete.modal('show');
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
            //console.log(json.consumables.length);
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

function openModalAttend() {
    var output_id = $(this).data('attend');

    $modalAttend.find('[id=output_id]').val(output_id);
    $modalAttend.find('[id=descriptionAttend]').html('Solicitud-'+output_id);

    $modalAttend.modal('show');
}

function attendOutput() {
    console.log('Llegue');
    $("#btn-submit").attr("disabled", true);
    var formulario = $('#formAttend')[0];
    var form = new FormData(formulario);
    event.preventDefault();
    // Obtener la URL
    var attendUrl = $formAttend.data('url');
    $.ajax({
        url: attendUrl,
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
            $modalAttend.modal('hide');
            setTimeout( function () {
                $("#btn-submit").attr("disabled", false);
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
            $("#btn-submit").attr("disabled", false);

        },
    });
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

