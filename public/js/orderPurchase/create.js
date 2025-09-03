let $materials=[];
let $locations=[];
let $materialsComplete=[];
let $locationsComplete=[];
let $items=[];
$(document).ready(function () {

    $(document).on('click', '[data-add]', addItem);

    $(document).on('click', '[data-check]', checkItem);

    $modalCheck = $("#modalCheck");

    $('#btn-submit').on('click', storeOrderPurchase);

    $(document).on('click', '[data-delete]', deleteItem);

    $formCreate = $("#formCreate");

    $('#btn-currency').on('switchChange.bootstrapSwitch', function (event, state) {

        if (this.checked) // if changed state is "CHECKED"
        {
            console.log($(this));
            $('.moneda').html('PEN');

        } else {
            console.log($(this));
            $('.moneda').html('USD');
        }
    });

    /*$(document).on('input', '[data-total]', function() {
        var total = parseFloat($(this).val());
        var price = parseFloat($(this).parent().parent().prev().prev().children().children().val());
        var quantity = parseFloat($(this).parent().parent().prev().prev().prev().children().children().val());
        var description = $(this).parent().parent().prev().prev().prev().prev().children().children().children().val();
        var id = $(this).parent().parent().prev().prev().prev().prev().prev().prev().children().children().children().val();
        var idCode = $(this).parent().parent().prev().prev().prev().prev().prev().prev().children().children().children().attr('data-code');

        $items = $items.filter(material => material.code != idCode);
        $items.push({'price': price, 'quantity':quantity ,'material': description, 'id_material': id, 'total': total });
        updateSummaryInvoice();
    });

    $(document).on('input', '[data-price2]', function() {
        var price = parseFloat($(this).parent().parent().prev().children().children().val());
        var quantity = parseFloat($(this).parent().parent().prev().prev().children().children().val());
        var description = $(this).parent().parent().prev().prev().prev().children().children().children().val();
        var id = $(this).parent().parent().prev().prev().prev().prev().prev().children().children().children().val();
        var idCode = $(this).parent().parent().prev().prev().prev().prev().prev().prev().children().children().children().attr('data-code');

        $items = $items.filter(material => material.code != idCode);
        $items.push({'price': price, 'quantity':quantity ,'material': description, 'id_material': id, 'total': quantity*price });
        updateSummaryInvoice();

    });

    $(document).on('input', '[data-price]', function() {
        var price = parseFloat($(this).val());
        var quantity = parseFloat($(this).parent().parent().prev().children().children().val());
        var description = $(this).parent().parent().prev().prev().children().children().children().val();
        var id = $(this).parent().parent().prev().prev().prev().prev().children().children().children().val();
        var idCode = $(this).parent().parent().prev().prev().prev().prev().prev().prev().children().children().children().attr('data-code');

        $items = $items.filter(material => material.code != idCode);
        $items.push({
            'price': price,
            'quantity':quantity ,
            'material': description,
            'id_material': id,
            'total': quantity*price
        });
        updateSummaryInvoice();

    });

    $(document).on('input', '[data-quantity]', function() {
        var quantity = parseFloat($(this).val());
        var price = parseFloat($(this).parent().parent().next().children().children().val());
        var description = $(this).parent().parent().prev().children().children().children().val();
        var id = $(this).parent().parent().prev().prev().prev().children().children().children().val();
        var idCode = $(this).parent().parent().prev().prev().prev().prev().prev().prev().children().children().children().attr('data-code');

        $items = $items.filter(material => material.code != idCode);
        $items.push({'price': price, 'quantity':quantity ,'material': description, 'id_material': id, 'total': quantity*price });
        updateSummaryInvoice();
    });*/

    $(document).on('input', '[data-quantity], [data-price], [data-price2]', function() {
        var $row = $(this).closest('.row');

        var $quantityInput = $row.find('[data-quantity]');
        var $priceCIInput = $row.find('[data-price]');
        var $priceSIInput = $row.find('[data-price2]');
        var $totalInput = $row.find('[data-total]');

        if (!$quantityInput.length || !$priceCIInput.length || !$priceSIInput.length || !$totalInput.length) {
            console.warn("⚠️ No se encontraron algunos inputs en la fila. Verifica el template.");
            return;
        }

        var quantity = parseFloat($quantityInput.val()) || 0;
        var priceCI = parseFloat($priceCIInput.val()) || 0;
        var priceSI = parseFloat($priceSIInput.val()) || 0;

        console.log("Valores obtenidos:", { quantity, priceCI, priceSI });

        // Si cambia el precio con IGV, recalculamos el precio sin IGV
        if ($(this).is('[data-price]') && priceCI > 0) {
            priceSI = (priceCI / 1.18).toFixed(2);
            console.log(`Nuevo Precio sin IGV: ${priceSI}`);

            // Evitar bucles infinitos
            $priceSIInput.off('input').val(priceSI).on('input', function() { $(this).trigger('change'); });
        }

        // Si cambia el precio sin IGV, recalculamos el precio con IGV
        if ($(this).is('[data-price2]') && priceSI > 0) {
            priceCI = (priceSI * 1.18).toFixed(2);
            console.log(`Nuevo Precio con IGV: ${priceCI}`);

            // Evitar bucles infinitos
            $priceCIInput.off('input').val(priceCI).on('input', function() { $(this).trigger('change'); });
        }

        // Calcular total
        var total = (quantity * priceCI).toFixed(2);
        console.log(`Total calculado: ${total}`);
        $totalInput.val(total);

        // Actualizar el array de items
        var idCode = $row.find('[data-id]').attr('data-codigo') || null;
        $items = $items.map(material => {
            if (material.code == idCode) {
                return { ...material, price: priceCI, quantity: quantity, total: total };
            }
            return material;
        });

        console.log("🛒 Items actualizados:", $items);
        updateSummaryInvoice();
    });

    $(document).on('input', '[data-largo], [data-ancho]', function() {
        var $row = $(this).closest('.row');

        var $largoInput = $row.find('[data-largo]');
        var $anchoInput = $row.find('[data-ancho]');

        if (!$largoInput.length || !$anchoInput.length) {
            console.warn("⚠️ No se encontraron los inputs de largo o ancho en la fila.");
            return;
        }

        var largo = parseFloat($largoInput.val()) || 0;
        var ancho = parseFloat($anchoInput.val()) || 0;

        console.log("📏 Nuevos valores:", { largo, ancho });

        // Actualizar el array de items sin modificar precios ni cantidades
        var idCode = $row.find('[data-id]').attr('data-codigo') || null;
        $items = $items.map(material => {
            if (material.code == idCode) {
                return { ...material, largo: largo, ancho: ancho };
            }
            return material;
        });

        console.log("🛒 Items actualizados con largo y ancho:", $items);
    });
});

function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Initializing the typeahead
var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
        var matches = [];
        var safeQuery = escapeRegex(q); // escapamos caracteres especiales
        var substrRegex = new RegExp(safeQuery, 'i');
        $.each(strs, function(i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });
        cb(matches);
    };
};

/*var substringMatcher = function(strs) {
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
};*/

let $formCreate;
let $modalCheck;

function checkItem() {
    var material_id = $(this).attr('data-check');

    $.ajax({
        url: "/dashboard/get/information/quantity/material/"+material_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            console.log(json);
            $("#stockActual").html(parseFloat(json.stockActual).toFixed(2));
            $("#cantidadOrdenes").html(parseFloat(json.cantidadOrdenes).toFixed(2));
            $("#cantidadDisponibleReal").html(parseFloat(json.cantidadDisponibleReal).toFixed(2));
            $("#cantidadCotizaciones").html(parseFloat(json.cantidadCotizaciones).toFixed(2));
            $("#cantidadSolicitada").html(parseFloat(json.cantidadSolicitada).toFixed(2));
            $("#cantidadNecesitadaReal").html(parseFloat(json.cantidadNecesitadaReal).toFixed(2));
            $("#cantidadParaComprar").html(parseFloat(json.cantidadParaComprar).toFixed(2));
            $modalCheck.modal('show');
        }
    });
}

function addItem() {

    /*let id = $(this).parent().prev().prev().prev().prev().prev().prev().html();
    let code = $(this).parent().prev().prev().prev().prev().prev().html();
    let description = $(this).parent().prev().prev().prev().prev().html();
    let quantity = $(this).parent().prev().prev().html();
    let price = $(this).parent().prev().html();

    let flag = false;

    $('[data-id]').each(function(e){
        if( $(this).val() === id ) {
            toastr.error('Ya esta agregado este material.', 'Error',
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
            flag = true;
            return false;
        }
    });

    if ( !flag )
    {
        $items.push({'price': price, 'quantity':quantity ,'material': description, 'id_material': id, 'total': quantity*price });
        renderTemplateMaterial(id, code, description, quantity, price);
        updateSummaryInvoice();
    }*/
    let id = $(this).parent().prev().prev().prev().prev().prev().prev().html();
    let code = $(this).parent().prev().prev().prev().prev().prev().html();
    let description = $(this).parent().prev().prev().prev().prev().html();
    let quantity = parseFloat($(this).parent().prev().prev().html());
    let price = parseFloat($(this).parent().prev().html());

    let flag = false;

    // Verificar si el material ya está agregado
    $('[data-id]').each(function () {
        if ($(this).val() === id) {
            toastr.error('Ya está agregado este material.', 'Error', {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "2000"
            });
            flag = true;
            return false;
        }
    });

    if (flag) return;

    // Hacer la petición AJAX para verificar si el material es retazable
    $.ajax({
        url: '/dashboard/materials/check-retazable/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (!response.retazable) {
                // Si no es retazable, redondear la cantidad y mostrar error si tenía decimales
                if (!Number.isInteger(quantity)) {
                    let cantidadEntera = Math.ceil(quantity);
                    toastr.info('Este material no permite decimales. Debe ingresar ' + cantidadEntera, 'Cuidado', {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "3000"
                    });
                    // Agregar el material normalmente a $items
                    $items.push({
                        'id_material': id,
                        'code': id,
                        'material': description,
                        'largo': 0,
                        'ancho': 0,
                        'quantity': cantidadEntera,
                        'scrap': false,
                        'price': price,
                        'total': (cantidadEntera * price).toFixed(2)
                    });
                    // Agregar el material normalmente
                    //alert(id);
                    renderTemplateMaterial(id, id, code, description, 0, 0,cantidadEntera, price, false);
                } else {
                    // Agregar el material normalmente a $items
                    $items.push({
                        'id_material': id,
                        'code': id,
                        'material': description,
                        'largo': 0,
                        'ancho': 0,
                        'quantity': quantity,
                        'scrap': false,
                        'price': price,
                        'total': (quantity * price).toFixed(2)
                    });
                    // Agregar el material normalmente
                    //alert(id);
                    renderTemplateMaterial(id, id, code, description, 0, 0,quantity, price, false);
                }

            } else {
                // Si es retazable, dividir en dos partes
                let cantidadEntera = Math.floor(quantity);
                let cantidadDecimal = quantity - cantidadEntera;

                if (cantidadEntera > 0) {
                    $items.push({
                        'id_material': id,
                        'code': id,
                        'material': description,
                        'largo': 0,
                        'ancho': 0,
                        'quantity': cantidadEntera,
                        'price': price,
                        'scrap': false,
                        'total': (cantidadEntera * price).toFixed(2)
                    });
                    renderTemplateMaterial(id, id, code, description, 0, 0, cantidadEntera, price, false);
                }
                if (cantidadDecimal > 0) {
                    let idDecimal = id + '-dec';
                    $items.push({
                        'id_material': id,
                        'code': idDecimal,
                        'material': description,
                        'largo': 0,
                        'ancho': 0,
                        'quantity': cantidadDecimal.toFixed(2),
                        'price': price,
                        'scrap': true,
                        'total': (cantidadDecimal * price).toFixed(2)
                    });
                    renderTemplateMaterial(id, idDecimal, code, description, 0, 0, cantidadDecimal.toFixed(2), price, true);
                }
            }

            updateSummaryInvoice();
        },
        error: function () {
            toastr.error('Error al verificar el material.', 'Error', {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            });
        }
    });

}

function updateSummaryInvoice() {
    var subtotal = 0;
    var total = 0;
    var taxes = 0;

    for ( var i=0; i<$items.length; i++ )
    {
        subtotal += (parseFloat($items[i].total))/1.18 ;
        total += parseFloat($items[i].total);
        taxes = subtotal*0.18;
    }

    $('#subtotal').val(subtotal.toFixed(2));
    $('#taxes').val(taxes.toFixed(2));
    $('#total').val(total.toFixed(2));

}

/*function calculateTotal(e) {
    var cantidad = e.value;
    var precio = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);
    updateSummaryInvoice();
}

function calculateTotal2(e) {
    var precio = e.value;
    var cantidad = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(precio)/1.18).toFixed(2);
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);
    updateSummaryInvoice();
}

function calculateTotal3(e) {
    var precioSI = e.value;
    var precioCI = (parseFloat(precioSI)*1.18).toFixed(2);
    console.log(precioSI);
    console.log(precioCI);
    var cantidad = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;
    console.log(cantidad);
    e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precioCI)).toFixed(2);
    e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value = precioCI;
    updateSummaryInvoice();
}*/

function deleteItem() {
    var materialId = $(this).data('delete');
    console.log(materialId);
    $items = $items.filter(material => material.code != materialId);
    $(this).parent().parent().remove();

    updateSummaryInvoice();
}

function renderTemplateMaterial(id,idCode, code, description, largo, ancho,quantity, price, scrap) {
    var clone = activateTemplate('#materials-selected');
    if ( scrap == true )
    {
        clone.querySelector("[data-id]").setAttribute('value', id);
        clone.querySelector("[data-id]").setAttribute('data-codigo', idCode);
        clone.querySelector("[data-code]").setAttribute('value', code);
        clone.querySelector("[data-description]").setAttribute('value', description);
        clone.querySelector("[data-largo]").setAttribute('value', largo);
        clone.querySelector("[data-ancho]").setAttribute('value', ancho);
        clone.querySelector("[data-quantity]").setAttribute('value', quantity);
        clone.querySelector("[data-quantity]").setAttribute('max', quantity);
        clone.querySelector("[data-price]").setAttribute('value', price);
        clone.querySelector("[data-price2]").setAttribute('value', (parseFloat(price)/1.18).toFixed(2) );
        clone.querySelector("[data-total]").setAttribute('value', (parseFloat(price)*parseFloat(quantity)).toFixed(2) );
        clone.querySelector("[data-delete]").setAttribute('data-delete', idCode);

    } else {
        clone.querySelector("[data-id]").setAttribute('value', id);
        clone.querySelector("[data-id]").setAttribute('data-codigo', idCode);
        clone.querySelector("[data-code]").setAttribute('value', code);
        clone.querySelector("[data-description]").setAttribute('value', description);
        clone.querySelector("[data-largo]").setAttribute('value', largo);
        clone.querySelector("[data-largo]").setAttribute('readonly', 'readonly');
        clone.querySelector("[data-ancho]").setAttribute('readonly', 'readonly');
        clone.querySelector("[data-ancho]").setAttribute('value', ancho);
        clone.querySelector("[data-quantity]").setAttribute('value', quantity);
        clone.querySelector("[data-quantity]").setAttribute('max', quantity);
        clone.querySelector("[data-price]").setAttribute('value', price);
        clone.querySelector("[data-price2]").setAttribute('value', (parseFloat(price)/1.18).toFixed(2) );
        clone.querySelector("[data-total]").setAttribute('value', (parseFloat(price)*parseFloat(quantity)).toFixed(2) );
        clone.querySelector("[data-delete]").setAttribute('data-delete', idCode);

    }

    $('#body-materials').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function storeOrderPurchase() {
    event.preventDefault();
    // Obtener la URL
    $("#btn-submit").attr("disabled", true);

    var subtotal_send = $('#subtotal').val();
    var taxes_send = $('#taxes').val();
    var total_send = $('#total').val();

    var items2 = JSON.parse(JSON.stringify($items)); // Clonamos los items para validar

    // 🚨 Validación de Scrap
    for (var i = 0; i < items2.length; i++) {
        if (items2[i].scrap) {
            var largo = items2[i].largo;
            var ancho = items2[i].ancho;

            if ((!largo || largo == 0) && (!ancho || ancho == 0)) {
                toastr.error(`El item ${items2[i].material} requiere al menos una medida válida (largo o ancho).`, 'Error en medidas',
                    {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right"
                    });
                $("#btn-submit").attr("disabled", false);
                return; // ⛔ Detener la ejecución
            }
        }
    }

    var createUrl = $formCreate.data('url');
    var items = JSON.stringify($items);
    var form = new FormData($('#formCreate')[0]);
    form.append('items', items);
    form.append('subtotal_send', subtotal_send);
    form.append('taxes_send', taxes_send);
    form.append('total_send', total_send);

    // 🚀 Mostrar loader en toda la pantalla
    $.blockUI({
        message: '<h3>⏳ Procesando solicitud...</h3>',
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: 0.5,
            color: '#fff'
        }
    });

    $.ajax({
        url: createUrl,
        method: 'POST',
        data: form,
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
            $.unblockUI();
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
                location.reload();
            }, 2000 )
        },
        error: function (data) {
            $.unblockUI();
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
