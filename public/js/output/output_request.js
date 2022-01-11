let $materials=[];
let $users=[];
let $usersComplete=[];
let $materialsComplete=[];
let $items=[];
let $itemsComplete=[];
let $itemsSelected=[];
$(document).ready(function () {
    $('input[name="request_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),20),
        startDate: moment().format('DD/MM/YYYY'),
        locale: {
            "format": 'DD/MM/YYYY',
            "applyLabel": "Guardar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Personalizar",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Setiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        }
    });

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
    $.ajax({
        url: "/dashboard/get/users",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                $users.push(json[i].name);
                $usersComplete.push(json[i]);
            }

        }
    });

    /*$('#responsible_user').typeahead({
            hint: true,
            highlight: true, /!* Enable substring highlighting *!/
            minLength: 1 /!* Specify minimum characters required for showing suggestions *!/
        },
        {
            limit: 12,
            source: substringMatcher($users)
        });*/

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

    $(document).on('change', '[data-selected]', selectItem);

    $formCreate = $("#formCreate");
    //$formCreate.on('submit', storeOutputRequest);
    $('#btn-submit').on('click', storeOutputRequest);

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

let $caracteres = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

let $longitud = 20;

function saveTableItems() {
    console.log($itemsSelected);

    for ( var i=0; i<$itemsSelected.length; i++ )
    {
        $items.push({'item': $itemsSelected[i].id});
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

function renderTemplateMaterial(material, item, location, state, price, id) {
    var clone = activateTemplate('#materials-selected');
    clone.querySelector("[data-description]").innerHTML = material;
    clone.querySelector("[data-item]").innerHTML = item;
    clone.querySelector("[data-location]").innerHTML = location;
    clone.querySelector("[data-state]").innerHTML = state;
    clone.querySelector("[data-price]").innerHTML = price;
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

function storeOutputRequest() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);
    // Obtener la URL
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
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            setTimeout( function () {
                $("#btn-submit").attr("disabled", false);
                location.href = data.url;
            }, 2000 )
        },
        error: function (data) {
            console.log(data);
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
                        "timeOut": "2000",
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
