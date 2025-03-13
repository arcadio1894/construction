let $materials=[];
let $locations=[];
let $materialsComplete=[];
let $locationsComplete=[];
let $items=[];
$(document).ready(function () {
    updateSummaryInvoice();
    $.ajax({
        url: "/dashboard/get/locations",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                console.log(json[i].id);
                $('.location').append($("<option>", {
                    value: json[i].id,
                    text: json[i].location
                }));
            }
            $('.location').val(1).trigger('change');
        }
    });

    $(document).on('click', '[data-delete]', deleteItem);

    $formCreate = $("#formCreate");
    $formCreate.on('submit', storeOrderPurchase);

    $('#btn-currency').on('switchChange.bootstrapSwitch', function (event, state) {

        if (this.checked) // if changed state is "CHECKED"
        {
            console.log($(this));
            $('.moneda').html('USD');

        } else {
            console.log($(this));
            $('.moneda').html('PEN');
        }
    });

    $(document).on('input', '[data-entered]', function() {
        updateSummaryInvoice();
    });
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

function updateSummaryInvoice() {
    var subtotal = 0;
    var total = 0;
    var taxes = 0;

    var arrayId = [];
    var arrayCode = [];
    var arrayDescription = [];
    var arrayQuantity = [];
    var arrayEntered = [];
    var arrayPrice = [];
    var arrayLocation = [];

    $('[data-id]').each(function(e){
        arrayId.push($(this).val());
    });
    $('[data-code]').each(function(e){
        arrayCode.push($(this).val());
    });
    $('[data-description]').each(function(e){
        arrayDescription.push($(this).val());
    });
    $('[data-quantity]').each(function(e){
        arrayQuantity.push($(this).val());
    });
    $('[data-entered]').each(function(e){
        arrayEntered.push($(this).val());
    });
    $('[data-price]').each(function(e){
        arrayPrice.push($(this).val());
    });
    $('[data-location]').each(function(e){
        arrayLocation.push($(this).val());
    });

    var itemsArray = [];
    for (let i = 0; i < arrayId.length; i++) {
        itemsArray.push({'id':arrayId[i], 'code':arrayCode[i], 'description':arrayDescription[i], 'quantity': arrayQuantity[i], 'entered': arrayEntered[i], 'price': arrayPrice[i], 'id_location': arrayLocation[i]});
    }

    console.log(itemsArray);

    for ( var i=0; i<itemsArray.length; i++ )
    {
        subtotal += parseFloat( (parseFloat(itemsArray[i].price)*parseFloat(itemsArray[i].entered))/1.18 );
        total += parseFloat((parseFloat(itemsArray[i].price)*parseFloat(itemsArray[i].entered)));
        taxes = subtotal*0.18;
    }

    //$('#subtotal').html(subtotal.toFixed(2));
    //$('#taxes').html(taxes.toFixed(2));
    //$('#total').html(total.toFixed(2));

    var currency = $('#currency').val();
    if (currency === 'DOLARES')
    {
        $('.moneda').html('USD');
    } else {
        $('.moneda').html('PEN');
    }
}

function deleteItem() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
    updateSummaryInvoice();
}

function storeOrderPurchase() {
    event.preventDefault();
    var arrayId = [];
    var arrayCode = [];
    var arrayDescription = [];
    var arrayQuantity = [];
    var arrayEntered = [];
    var arrayPrice = [];
    var arrayLocation = [];

    $('[data-id]').each(function(e){
        arrayId.push($(this).val());
    });
    $('[data-code]').each(function(e){
        arrayCode.push($(this).val());
    });
    $('[data-description]').each(function(e){
        arrayDescription.push($(this).val());
    });
    $('[data-quantity]').each(function(e){
        arrayQuantity.push($(this).val());
    });
    $('[data-entered]').each(function(e){
        arrayEntered.push($(this).val());
    });
    $('[data-price]').each(function(e){
        arrayPrice.push($(this).val());
    });
    $('[data-location]').each(function(e){
        arrayLocation.push($(this).val());
    });

    var itemsArray = [];
    for (let i = 0; i < arrayId.length; i++) {
        itemsArray.push({'id':arrayId[i], 'code':arrayCode[i], 'description':arrayDescription[i], 'quantity': arrayQuantity[i], 'entered': arrayEntered[i], 'price': arrayPrice[i], 'id_location': arrayLocation[i]});
    }

    console.log(itemsArray);

    var subtotal_send = $('#subtotal').html();
    var taxes_send = $('#taxes').html();
    var total_send = $('#total').html();

    // Obtener la URL
    $("#btn-submit").attr("disabled", true);

    var createUrl = $formCreate.data('url');
    var items = JSON.stringify(itemsArray);
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
            // 🛑 Quitar loader
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
                location.href = data.url;
            }, 2000 )
        },
        error: function (data) {
            // 🛑 Quitar loader
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
