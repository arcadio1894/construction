
let $materials=[];
let $materialsComplete=[];
let $items=[];
let $itemsComplete=[];
let $itemsSelected=[];
$(document).ready(function () {

    $.ajax({
        url: "/dashboard/get/materials/transfer",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            //console.log(json[0]);
            for (var i=0; i<json.length; i++)
            {
                //console.log(json[i].full_description);
                $('#material_search').append($("<option>", {
                    value: json[i].id,
                    text: json[i].code+' '+json[i].material
                }));
            }

        }
    });
    $.ajax({
        url: "/dashboard/get/materials",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                $materialsComplete.push(json[i]);
            }

        }
    });

    $('.typeahead').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
            minLength: 1 /* Specify minimum characters required for showing suggestions */
        },
        {
            limit: 12,
            source: substringMatcher($materials)
        });

    $('#btn-addItems').on('click', addItems);
    $modalAddItems = $('#modalAddItems');

    $('#btn-saveItems').on('click', saveTableItems);

    $(document).on('change', '[data-selected]', selectItem);

    //$(document).on('click', '[data-delete]', deleteItem);

    $formCreate = $('#formCreate');
    //$formCreate.on('submit', storeTransfer);
    $('#btn-submit').on('click', storeTransfer);

    $(document).on('click', '[data-deleteItem]', deleteItem);


    $selectWarehouse = $('#warehouse');
    $('#area').change(function () {
        $selectWarehouse.empty();
        $selectShelf.empty();
        $selectLevel.empty();
        $selectContainer.empty();
        $selectPosition.empty();
        var area =  $('#area').val();
        $.get( "/dashboard/get/warehouse/area/"+area, function( data ) {
            $selectWarehouse.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectWarehouse.append($("<option>", {
                    value: data[i].id,
                    text: data[i].warehouse
                }));
            }
        });
        $selectWarehouse.select2({
            placeholder: "Selecione un almacén",
        });
    });

    $selectShelf = $('#shelf');
    $selectWarehouse.change(function () {
        $selectShelf.empty();
        $selectLevel.empty();
        $selectContainer.empty();
        $selectPosition.empty();

        var warehouse =  $selectWarehouse.val();
        $.get( "/dashboard/get/shelf/warehouse/"+warehouse, function( data ) {
            $selectShelf.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectShelf.append($("<option>", {
                    value: data[i].id,
                    text: data[i].shelf
                }));
            }
        });

    });

    $selectLevel = $('#level');
    $selectShelf.change(function () {
        $selectLevel.empty();
        $selectContainer.empty();
        $selectPosition.empty();

        var shelf =  $selectShelf.val();
        $.get( "/dashboard/get/level/shelf/"+shelf, function( data ) {
            $selectLevel.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectLevel.append($("<option>", {
                    value: data[i].id,
                    text: data[i].level
                }));
            }
        });

    });

    $selectContainer = $('#container');
    $selectLevel.change(function () {
        $selectContainer.empty();
        $selectPosition.empty();
        var level =  $selectLevel.val();
        $.get( "/dashboard/get/container/level/"+level, function( data ) {
            $selectContainer.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectContainer.append($("<option>", {
                    value: data[i].id,
                    text: data[i].container
                }));
            }
        });

    });

    $selectPosition = $('#position');
    $selectContainer.change(function () {
        $selectPosition.empty();
        var container =  $selectContainer.val();
        $.get( "/dashboard/get/position/container/"+container, function( data ) {
            $selectPosition.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectPosition.append($("<option>", {
                    value: data[i].id,
                    text: data[i].position
                }));
            }
        });

    });

    $('#btn-request-quantity').on('click', requestItemsQuantity);
    $('#material_selected_quantity').on('keyup', requestItemsQuantity2);
});

var $formCreate;
var $selectWarehouse;
var $selectShelf;
var $selectLevel;
var $selectContainer;
var $selectPosition;

let $modalAddItems;

var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
        var matches, substringRegex;

        // an array that will be populated with substring matches
        matches = [];

        // regex used to determine if a string contains the substring `q`
        substrRegex = new RegExp(q, 'i');

        // iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(strs, function(i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });

        cb(matches);
    };
};

function saveTableItems() {

    console.log($itemsSelected);
    let material_name = $('#material_selected').val();
    let material_id = $('#material_selected_id').val();

    for ( var j=0; j<$itemsSelected.length; j++ )
    {
        if ( $items.find(x => x.item === $itemsSelected[j].id ) )
        {
            toastr.error('Hay items repetios. Elija otro', 'Error',
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
    }

    for ( var i=0; i<$itemsSelected.length; i++ )
    {
        $items.push({'item': $itemsSelected[i].id, 'code': $itemsSelected[i].code});
        renderTemplateMaterial(material_name, $itemsSelected[i].code, $itemsSelected[i].location, $itemsSelected[i].length, $itemsSelected[i].width, $itemsSelected[i].state_item, $itemsSelected[i].id);
    }

    $('#material_search').val('').trigger('change');
    $('#material_selected').val('');
    $('#body-items').html('');

    $itemsSelected = [];

    $modalAddItems.modal('hide');
}

function selectItem() {

    if (this.checked) {
        let itemId = $(this).data('selected');
        const result = $itemsComplete.find( item => item.id === itemId );
        $itemsSelected.push(result);
        console.log($itemsSelected);
    } else {
        let itemD = $(this).data('selected');
        const result = $itemsComplete.find( item => item.id === itemD );
        if (result)
        {
            $itemsSelected = $.grep($itemsSelected, function(e){
                return e.id !== itemD;
            });
        }
        console.log($itemsSelected);
    }
}

function requestItemsQuantity() {
    let material_name = $('#material_selected').val();
    let material_id = $('#material_selected_id').val();
    let material_quantity = $('#material_selected_quantity').val();
    const result = $materialsComplete.find( material => material.id == material_id );
    let material_stock = result.stock_current;
    if( material_name.trim() === '' )
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
                "hideDuration": "2000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        return;
    }
    if( parseFloat(material_quantity) > parseFloat(material_stock) )
    {
        toastr.error('No hay stock suficiente en el almacén', 'Error',
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

    $("#body-items-load").LoadingOverlay("show", {
        background  : "rgba(236, 91, 23, 0.5)"
    });

    $('#body-items').html('');

    $.ajax({
        url: "/dashboard/get/items/transfer/"+result.id,
        type: 'GET',
        dataType: 'json',
        success: function (json){
            console.log(json);
            let iterator = 1;
            for (var i=0; i<json.length; i++)
            {
                //$users.push(json[i].name);
                /*$itemsComplete.push(json[i]);*/
                //console.log($itemsComplete);
                if (iterator <= material_quantity)
                {
                    renderTemplateItemSelected(i+1, json[i].code, json[i].location, json[i].length, json[i].width, json[i].id);
                    const result = $itemsComplete.find( item => item.id === json[i].id );
                    $itemsSelected.push(result);
                    iterator+=1;
                } else {
                    renderTemplateItem(i+1, json[i].code, json[i].location, json[i].length, json[i].width, json[i].id);
                    iterator+=1;
                }
            }
            $("#body-items-load").LoadingOverlay("hide", true);
        }
    });



}

function requestItemsQuantity2(event) {
    if (event.keyCode === 13) {
        let material_name = $('#material_selected').val();
        let material_id = $('#material_selected_id').val();
        let material_quantity = $('#material_selected_quantity').val();
        const result = $materialsComplete.find( material => material.id == material_id );
        let material_stock = result.stock_current;
        if( material_name.trim() === '' )
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
        if( parseFloat(material_quantity) > parseFloat(material_stock) )
        {
            toastr.error('No hay stock suficiente en el almacén', 'Error',
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

        $('#body-items').html('');

        $("#body-items-load").LoadingOverlay("show", {
            background  : "rgba(236, 91, 23, 0.5)"
        });

        $.ajax({
            url: "/dashboard/get/items/transfer/"+material_id,
            type: 'GET',
            dataType: 'json',
            success: function (json){
                let iterator = 1;
                for (var i=0; i<json.length; i++)
                {
                    //$users.push(json[i].name);
                    /*$itemsComplete.push(json[i]);*/
                    //console.log($itemsComplete);
                    if (iterator <= material_quantity)
                    {
                        renderTemplateItemSelected(i+1, json[i].code, json[i].location, json[i].length, json[i].width, json[i].id);
                        const result = $itemsComplete.find( item => item.id === json[i].id );
                        $itemsSelected.push(result);
                        iterator+=1;
                    } else {
                        renderTemplateItem(i+1, json[i].code, json[i].location, json[i].length, json[i].width, json[i].id);
                        iterator+=1;
                    }
                }
                $("#body-items-load").LoadingOverlay("hide", true);
            }
        });
    }
}

function addItems() {
    $itemsComplete = [];
    $itemsSelected = [];
    var data = $('#material_search').select2('data');

    console.log(data[0].text);

    if( $('#material_search').val() == '' )
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

    let material_name = data[0].text.trim();
    let material_id = data[0].id.trim();

    $modalAddItems.find('[id=material_selected]').val(material_name);
    $modalAddItems.find('[id=material_selected_id]').val(material_id);
    $modalAddItems.find('[id=material_selected]').prop('disabled', true);

    $modalAddItems.find('[id=material_selected_quantity]').prop('disabled', false);

    $('#body-items').html('');

    //const result = $materialsComplete.find( material => material.material === material_name );

    $("#body-items-load").LoadingOverlay("show", {
        background  : "rgba(236, 91, 23, 0.5)"
    });

    $.ajax({
        url: "/dashboard/get/items/transfer/"+material_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                //$users.push(json[i].name);
                $itemsComplete.push(json[i]);
                renderTemplateItem(i+1, json[i].code, json[i].location, json[i].length, json[i].width, json[i].id);
            }
            $("#body-items-load").LoadingOverlay("hide", true);
        }
    });

    console.log($itemsComplete);
    $('#material_selected_quantity').val('');

    $modalAddItems.modal('show');
}

function storeTransfer() {
    event.preventDefault();
    // Obtener la URL
    $("#btn-submit").attr("disabled", true);
    var createUrl = $formCreate.data('url');
    var items = JSON.stringify($items);
    var form = new FormData($('#formCreate')[0]);
    form.append('items', items);
    $.ajax({
        url: createUrl,
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
                    "timeOut": "4000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            setTimeout( function () {
                $("#btn-submit").attr("disabled", false);
                location.reload();
            }, 4000 )
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

function deleteItem() {
    //console.log($(this).parent().parent().parent());
    var itemToRemove = $(this).attr('data-deleteItem');
    console.log(itemToRemove);
    $items = $.grep($items, function(value) {
        return value.item != itemToRemove;
    });
    $(this).parent().parent().remove();
    console.log($items);
}

function renderTemplateMaterial(material, item, location, length, width, status, id) {
    var state = (status === 'entered') ? '<span class="badge bg-success">Ingresado</span>' :
        (status === 'scraped') ? '<span class="badge bg-warning">Retazo</span>' :
            (status === 'reserved') ? '<span class="badge bg-secondary">Reservado</span>' :
                '<span class="badge bg-danger">Indefinido</span>';
    var clone = activateTemplate('#item-selected');
    clone.querySelector("[data-description]").innerHTML = material;
    clone.querySelector("[data-item]").innerHTML = item;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-length]").innerHTML = length;
    clone.querySelector("[data-width]").innerHTML = width;
    clone.querySelector("[data-state]").innerHTML = state;
    clone.querySelector("[data-deleteitem]").setAttribute('data-deleteitem', id);
    $('#body-materials').append(clone);
}

function renderTemplateItemSelected(i, code, location, length, width, id) {
    var clone = activateTemplate('#template-item');
    clone.querySelector("[data-id]").innerHTML = i;
    clone.querySelector("[data-serie]").innerHTML = code;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-length]").innerHTML = length;
    clone.querySelector("[data-width]").innerHTML = width;
    clone.querySelector("[data-selected]").setAttribute('data-selected', id);
    clone.querySelector("[data-selected]").setAttribute('id', 'checkboxSuccess'+id);
    clone.querySelector("[data-selected]").setAttribute('checked', 'checked');
    clone.querySelector("[data-label]").setAttribute('for', 'checkboxSuccess'+id);
    $('#body-items').append(clone);
}

function renderTemplateItem(i, code, location, length, width, id) {
    var clone = activateTemplate('#template-item');
    clone.querySelector("[data-id]").innerHTML = i;
    clone.querySelector("[data-serie]").innerHTML = code;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-length]").innerHTML = length;
    clone.querySelector("[data-width]").innerHTML = width;
    clone.querySelector("[data-selected]").setAttribute('data-selected', id);
    clone.querySelector("[data-selected]").setAttribute('id', 'checkboxSuccess'+id);
    clone.querySelector("[data-label]").setAttribute('for', 'checkboxSuccess'+id);
    $('#body-items').append(clone);

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}
