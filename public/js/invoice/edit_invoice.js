let $items=[];
let $subtotal = 0;
let $taxes = 0;
let $total = 0;
$(document).ready(function () {

    fillItems();
    $subtotal = parseFloat($('#subtotal').html());
    $taxes = parseFloat($('#taxes').html());
    $total = parseFloat($('#total').html());

    $modalImage = $('#modalImage');
    $(document).on('click', '[data-image]', showImage);

    $('#btn-add').on('click', addItems);

    $(document).on('click', '[data-deleteNew]', deleteItemNew);
    $(document).on('click', '[data-delete]', deleteItem);

    $formEdit = $("#formEdit");
    $formEdit.on('submit', updateInvoice);
});

let $modalImage;
let $formEdit;

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function fillItems() {
    var arrayDescription = [];
    var arrayQuantity = [];
    var arrayUnit = [];
    var arrayPrice = [];
    var arraySubtotal = [];
    var arrayTaxes = [];
    var arrayTotal = [];

    $("#tablita tbody").each(function (index) {
        $('[data-description]').each(function(e){
            arrayDescription.push($(this).text());
        });
        $('[data-quantity]').each(function(e){
            arrayQuantity.push($(this).text());
        });
        $('[data-unit]').each(function(e){
            arrayUnit.push($(this).text());
        });
        $('[data-price]').each(function(e){
            arrayPrice.push($(this).text());
        });
        $('[data-subtotal]').each(function(e){
            arraySubtotal.push($(this).text());
        });
        $('[data-taxes]').each(function(e){
            arrayTaxes.push($(this).text());
        });
        $('[data-total]').each(function(e){
            arrayTotal.push($(this).text());
        });
    });

    for (let i = 0; i < arrayDescription.length; i++) {
        $items.push({ 'id': $items.length+1, 'price': arrayPrice[i], 'material': arrayDescription[i], 'quantity': arrayQuantity[i], 'unit': arrayUnit[i], 'subtotal': arraySubtotal[i], 'taxes': arrayTaxes[i], 'total':arrayTotal[i], 'old':1});
    }

}

function deleteItemNew() {
    var materialId = $(this).data('deletenew');
    var itemDeleted = $items.find(material => material.id === materialId);
    $items = $items.filter(material => material.id !== materialId);
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
    updateSummaryInvoice();
}

function deleteItem() {
    var idDetail = $(this).data('delete');
    var idItem = $(this).data('i');
    var button = $(this);
    console.log(idDetail);
    $.confirm({
        icon: 'fas fa-frown',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: '¿Está seguro de eliminar este detalle?',
        content: 'Se eliminará directamente de la base de datos',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/destroy/detail/invoice/'+idDetail,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $items = $items.filter(material => material.id !== idItem);
                            button.parent().parent().remove();
                            updateSummaryInvoice();
                            $.alert(data.message);
                            /*setTimeout( function () {
                                location.reload();
                            }, 2000 )*/

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
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Modificación cancelada.");
                },
            },
        },
    });
}

function updateSummaryInvoice() {
    var subtotal = 0;
    var total = 0;
    var taxes = 0;

    for ( var i=0; i<$items.length; i++ )
    {
        subtotal += (parseFloat($items[i].total))/1.18;
        total += parseFloat($items[i].total);
        taxes = subtotal*0.18;
    }

    $('#subtotal').html(subtotal.toFixed(2));
    $('#taxes').html(taxes.toFixed(2));
    $('#total').html(total.toFixed(2));
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

    if ( $('#material_unit').val() == '' )
    {
        toastr.error('Debe elegir una unidad', 'Error',
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
    let material_unit = $( "#material_unit option:selected" ).text();
    let material_quantity = parseFloat($('#quantity').val()).toFixed(2);
    // TODO: Este precio ahora es total
    let material_price = parseFloat($('#price').val()).toFixed(2);

    var subtotal = parseFloat(material_price/1.18).toFixed(2);
    var taxes = parseFloat(subtotal*0.18).toFixed(2);
    var total = parseFloat(material_price).toFixed(2);

    $items.push({ 'id': $items.length+1, 'price': parseFloat(parseFloat(material_price)/parseFloat(material_quantity)).toFixed(4), 'material': material_name, 'quantity': material_quantity, 'unit': material_unit, 'subtotal': subtotal, 'taxes': taxes, 'total':total, 'old':0});

    renderTemplateMaterial($items.length, material_name, material_quantity, material_unit, parseFloat(material_price/material_quantity).toFixed(2), subtotal, taxes, total);

    $('#material_search').val('');
    $('#quantity').val('');
    $("#material_unit").val('').trigger('change');
    $('#price').val('');

    updateSummaryInvoice();
}

function showImage() {
    var path = $(this).attr('src');
    $('#image-document').attr('src', path);
    $modalImage.modal('show');
}

function renderTemplateMaterial(id, description, quantity, unit, price, subtotal, taxes, total) {
    var clone = activateTemplate('#materials-selected');
    clone.querySelector("[data-description]").innerHTML = description;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-unit]").innerHTML = unit;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-subtotal]").innerHTML = subtotal;
    clone.querySelector("[data-taxes]").innerHTML = taxes;
    clone.querySelector("[data-total]").innerHTML = total;
    clone.querySelector("[data-deleteNew]").setAttribute('data-deleteNew', id);
    $('#body-materials').append(clone);
}

function updateInvoice() {
    event.preventDefault();
    // Obtener la URL
    var createUrl = $formEdit.data('url');
    var state = $('#btn-grouped').bootstrapSwitch('state');
    var form = new FormData($('#formEdit')[0]);
    form.append('deferred_invoice', state);
    var items = JSON.stringify($items);
    //var form = new FormData(this);
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