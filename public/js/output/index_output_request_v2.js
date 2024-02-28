$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);
    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    getDataOutputsRequest(1);

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
    });

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSearch);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('#btn-export').on('click', exportExcel);

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

    $(document).on('click', '[data-attend]', openModalAttend);

});

var $permissions;

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
    var idMaterial = $(this).data('itemdeletequantity');

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
        return;
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
            content: 'Si no hay fechas se descargará todos las órdenes de compra',
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

                        var url = "/dashboard/exportar/reporte/ordenes/compra/v2/?" + $.param(query);

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

        var url = "/dashboard/exportar/reporte/ordenes/compra/v2/?" + $.param(query);

        window.location = url;

    }

}

function showDataSearch() {
    getDataOutputsRequest(1)
}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataOutputsRequest(numberPage)
}

function getDataOutputsRequest($numberPage) {
    $('[data-toggle="tooltip"]').tooltip('dispose').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    var year = $('#year').val();
    var code = $('#code').val();
    var execution_order = $('#execution_order').val();
    var quote = $('#quote').val();
    var code_quote = $('#code_quote').val();
    var description_quote = $('#description_quote').val();
    var type = $('#type').val();
    var state = $('#state').val();
    var requesting_user = $('#requesting_user').val();
    var responsible_user = $('#responsible_user').val();
    var startDate = $('#start').val();
    var endDate = $('#end').val();

    $.get('/dashboard/get/all/outputs/requests/v2/'+$numberPage, {
        year: year,
        code: code,
        execution_order: execution_order,
        quote: quote,
        code_quote: code_quote,
        description_quote: description_quote,
        type: type,
        state: state,
        requesting_user: requesting_user,
        responsible_user: responsible_user,
        startDate: startDate,
        endDate: endDate,
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataOutputsRequestEmpty(data);
        } else {
            renderDataOutputsRequest(data);
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

function renderDataOutputsRequestEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' solicitudes de salidas');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataOutputsRequest(data) {
    var dataFinanceWorks = data.data;
    var pagination = data.pagination;
    console.log(dataFinanceWorks);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' solicitudes de salidas.');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    for (let j = 0; j < dataFinanceWorks.length ; j++) {
        renderDataTable(dataFinanceWorks[j]);
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
    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-year]").innerHTML = data.year;
    clone.querySelector("[data-execution_order]").innerHTML = data.execution_order;
    clone.querySelector("[data-quote]").innerHTML = data.quote;
    clone.querySelector("[data-description]").innerHTML = data.description;
    clone.querySelector("[data-request_date]").innerHTML = data.request_date;
    clone.querySelector("[data-requesting_user]").innerHTML = data.requesting_user;
    clone.querySelector("[data-responsible_user]").innerHTML = data.responsible_user;
    clone.querySelector("[data-typeText]").innerHTML = data.typeText;
    clone.querySelector("[data-stateText]").innerHTML = data.stateText;

    var botones = clone.querySelector("[data-buttons]");

    if ( data.state == "attended" )
    {
        var cloneBtnAttended = activateTemplate('#template-attended');

        cloneBtnAttended.querySelector("[data-materiales_cotizacion]").setAttribute("data-materials", data.execution_order);
        cloneBtnAttended.querySelector("[data-ver_materiales_pedidos]").setAttribute("data-details", data.id);

        if ( data.description == 'No hay datos' )
        {
            cloneBtnAttended.querySelector("[data-editar_orden_ejecucion]").setAttribute("data-edit", data.id);
            cloneBtnAttended.querySelector("[data-editar_orden_ejecucion]").setAttribute("data-execution_order", data.execution_order);
        } else {
            let element = cloneBtnAttended.querySelector("[data-editar_orden_ejecucion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnAttended);
    }

    if ( data.state == "created" )
    {
        var cloneBtnCreated = activateTemplate('#template-created');

        cloneBtnCreated.querySelector("[data-materiales_cotizacion]").setAttribute("data-materials", data.execution_order);
        cloneBtnCreated.querySelector("[data-ver_materiales_pedidos]").setAttribute("data-details", data.id);

        cloneBtnCreated.querySelector("[data-anular_total]").setAttribute("data-deleteTotal", data.id);
        cloneBtnCreated.querySelector("[data-anular_parcial]").setAttribute("data-deletePartial", data.id);
        cloneBtnCreated.querySelector("[data-anular_cantidad]").setAttribute("data-deleteQuantity", data.id);


        if ( (data.custom == false) && (data.state !== 'attended' && data.state !== 'confirmed') )
        {
            if ( $.inArray('attend_request', $permissions) !== -1 ) {
                cloneBtnCreated.querySelector("[data-atender]").setAttribute("data-attend", data.id);

            } else {
                let element = cloneBtnCreated.querySelector("[data-atender]");
                if (element) {
                    element.style.display = 'none';
                }
            }
        } else {
            let element = cloneBtnCreated.querySelector("[data-atender]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( data.description == 'No hay datos' )
        {
            cloneBtnCreated.querySelector("[data-editar_orden_ejecucion]").setAttribute("data-edit", data.id);
            cloneBtnCreated.querySelector("[data-editar_orden_ejecucion]").setAttribute("data-execution_order", data.execution_order);
        } else {
            let element = cloneBtnCreated.querySelector("[data-editar_orden_ejecucion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnCreated);
    }

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