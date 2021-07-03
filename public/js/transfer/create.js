let $materials=[];
let $materialsComplete=[];
let $items=[];
let $itemsComplete=[];
let $itemsSelected=[];
$(document).ready(function () {
    $.ajax({
        url: "/dashboard/get/materials",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                $materials.push(json[i].material);
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
    $formCreate.on('submit', storeTransfer);

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

    for ( var i=0; i<$itemsSelected.length; i++ )
    {
        $items.push({'item': $itemsSelected[i].id, 'code': $itemsSelected[i].code});
        renderTemplateMaterial($itemsSelected[i].material, $itemsSelected[i].code, $itemsSelected[i].location, $itemsSelected[i].state,  $itemsSelected[i].price, $itemsSelected[i].id);
    }

    $('#material_search').val('');
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

    let material_name = $('#material_search').val();
    $modalAddItems.find('[id=material_selected]').val(material_name);
    $modalAddItems.find('[id=material_selected]').prop('disabled', true);

    $('#body-items').html('');

    const result = $materialsComplete.find( material => material.material === material_name );

    $.ajax({
        url: "/dashboard/get/items/output/"+result.id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                //$users.push(json[i].name);
                $itemsComplete.push(json[i]);
                renderTemplateItem(i+1, json[i].code, json[i].location, json[i].length, json[i].width, json[i].weight, json[i].price, json[i].id);
            }

        }
    });

    console.log($itemsComplete);

    $modalAddItems.modal('show');

    /*$items.push({
        "productId" : sku,
        "qty" : qty,
        "price" : price
    });*/
}

function storeTransfer() {
    event.preventDefault();
    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var items = JSON.stringify($items);
    var form = new FormData(this);
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
                location.reload();
            }, 4000 )
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

function renderTemplateMaterial(material, item, location, state, price, id) {
    var clone = activateTemplate('#item-selected');
    clone.querySelector("[data-description]").innerHTML = material;
    clone.querySelector("[data-item]").innerHTML = item;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-state]").innerHTML = state;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-deleteitem]").setAttribute('data-deleteitem', id);
    $('#body-materials').append(clone);
}

function renderTemplateItem(i, code, location, length, width, weight, price, id) {
    var clone = activateTemplate('#template-item');
    clone.querySelector("[data-id]").innerHTML = i;
    clone.querySelector("[data-serie]").innerHTML = code;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-length]").innerHTML = length;
    clone.querySelector("[data-width]").innerHTML = width;
    clone.querySelector("[data-weight]").innerHTML = weight;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-selected]").setAttribute('data-selected', id);
    clone.querySelector("[data-selected]").setAttribute('id', 'checkboxSuccess'+id);
    clone.querySelector("[data-label]").setAttribute('for', 'checkboxSuccess'+id);
    $('#body-items').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}