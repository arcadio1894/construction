let $materials=[];
let $materialsComplete=[];
let $itemsComplete=[];
let $items=[];
let $item=[];

$(document).ready(function () {
    $.ajax({
        url: "/dashboard/get/materials/scrap",
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

    $('#btn-add').on('click', addItems);
    $modalAddItems = $('#modalAddItems');

    $('#btn-saveItems').on('click', saveTableItems);

    $(document).on('click', '[data-delete]', deleteItem);

    $formCreate = $("#formCreate");
    $formCreate.on('submit', storeOrderScrap);

});

// Initializing the typeahead
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

let $formCreate;

let $modalAddItems;

function saveTableItems() {

    let material_name = $('#material_selected').val();
    let item_selected = $('#item_selected').val();
    let length = $('#length').val();
    let width = $('#width').val();
    let weight = $('#weight').val();

    const result = $itemsComplete.find( item => item.code === item_selected );

    if ( result.typescrap === 1 || result.typescrap === 2 )
    {
        if ( parseFloat(result.length)*parseFloat(result.width) < parseFloat(length)*parseFloat(width) )
        {
            toastr.error('Las medidas superan las mediads del material', 'Error',
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

        if ( length === '' || width === '' )
        {
            toastr.error('Las medidas no han sido ingresadas', 'Error',
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
    } else {
        if ( result.typescrap === 3  )
        {
            if ( parseFloat(result.length) < parseFloat(length) )
            {
                toastr.error('Las medidas superan las medidas del material', 'Error',
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

            if ( length === '' )
            {
                toastr.error('Las medidas no han sido ingresadas', 'Error',
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
    }

    let newPrice = 0;
    console.log(result.typescrap);
    if ( result.typescrap === 1 || result.typescrap === 2 )
    {
        //console.log('Entre a 1 y 2');
        let priceTotal = parseFloat(result.price);
        let areaTotal = parseFloat(result.length)*parseFloat(result.width);
        let areaReal = parseFloat(length)*parseFloat(width);
        newPrice = ((areaReal*priceTotal)/areaTotal).toFixed(2);
    }

    if ( result.typescrap === 3  )
    {
        //console.log('Entre a 3');
        let priceTotal = parseFloat(result.price);
        let lengthTotal = parseFloat(result.length);
        let lengthReal = parseFloat(length);
        newPrice = ((lengthReal*priceTotal)/lengthTotal).toFixed(2);
    }

    let state = ( result.state === "bad") ? 'Deficiente' : 'Buen estado';

    $item.push({ 'id': result.id, 'detailEntry': result.detailEntry, 'length':length, 'width':width, 'weight':weight, 'price': newPrice, 'material': material_name, 'typescrap_id': result.typescrap, 'material_id': result.material_id, 'code': result.code, 'location': result.location, 'location_id': result.location_id, 'state': result.state });
    //console.log($item);

    $('#item_selected').val('');
    $('#material_selected').val('');
    $('#length').val('');
    $('#width').val('');
    //$('#body-items').html('');

    renderTemplateItem(result.id, newPrice, result.material, result.code, result.location, state);

    $modalAddItems.modal('hide');
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

    $("#item_selected").typeahead("destroy");

    let material_name = $('#material_search').val();
    $modalAddItems.find('[id=material_selected]').val(material_name);
    $modalAddItems.find('[id=material_selected]').prop('disabled', true);

    //console.log($materialsComplete);
    const result = $materialsComplete.find( material => material.material === material_name );
    //console.log(result);

    $.get('/dashboard/get/items/'+result.id, function(json) {
        console.log(json);
        for (var i=0; i<json.length; i++)
        {
            $items.push(json[i].code);
            $itemsComplete.push(json[i]);
        }
    });

    $('#item_selected').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
            minLength: 1 /* Specify minimum characters required for showing suggestions */
        },
        {
            limit: 12,
            source: substringMatcher($items)
        });
    //console.log($items);

    if ( result.typescrap === 1 || result.typescrap === 2 )
    {
        $('#length').show();
        $('#label-largo').show();
        $('#width').show();
        $('#label-ancho').show();
        $('#weight').val(0);
        $('#weight').hide();
    } else {
        if ( result.typescrap === 3  )
        {
            $('#length').show();
            $('#label-largo').show();
            $('#width').hide();
            $('#label-ancho').hide();
            $('#weight').val(0);
            $('#weight').hide(0);
        }
    }

    $modalAddItems.modal('show');
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

function renderTemplateItem(id, price, material, item, location, state) {
    var clone = activateTemplate('#item-selected');
    clone.querySelector("[data-id]").innerHTML = id;
    clone.querySelector("[data-material]").innerHTML = material;
    clone.querySelector("[data-item]").innerHTML = item;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-state]").innerHTML = state;
    clone.querySelector("[data-price]").innerHTML = price;
    //clone.querySelector("[data-deleteItem]").setAttribute('data-deleteItem', id);
    $('#body-items').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function storeOrderScrap() {
    event.preventDefault();
    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var item = JSON.stringify($item);
    console.log(item);
    var form = new FormData(this);
    form.append('item', item);
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
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
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
