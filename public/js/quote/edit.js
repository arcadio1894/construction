let $materials=[];
let $consumables=[];
let $items=[];
let $equipments=[];
let $equipmentStatus=false;
let $total=0;
let $subtotal=0;
let $subtotal2=0;
let $subtotal3=0;

$(document).ready(function () {
    $.ajax({
        url: "/dashboard/get/quote/materials/",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                $materials.push(json[i]);
            }
        }
    });

    $.ajax({
        url: "/dashboard/get/quote/consumables/",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                $consumables.push(json[i]);
            }
        }
    });

    $modalAddMaterial = $('#modalAddMaterial');

    $(document).on('click', '[data-add]', addMaterial);
    
    $(document).on('click', '[data-confirm]', confirmEquipment);

    $(document).on('click', '[data-addMano]', addMano);

    $(document).on('click', '[data-addTorno]', addTorno);

    $(document).on('click', '[data-addConsumable]', addConsumable);

    $('#btn-addEquipment').on('click', addEquipment);

    $('#btn-addMaterial').on('click', addTableMaterials);
    
    $('#btnCalculate').on('click', calculatePercentage);

    $formCreate = $('#formEdit');
    $formCreate.on('submit', storeQuote);

    $('input[type=radio][name=presentation]').on('change', function() {
        switch ($(this).val()) {
            case 'fraction':
                $('#width_entered_material').show();
                $('#length_entered_material').show();
                $('#quantity_entered_material').hide();
                $('#material_length_entered').val('');
                $('#material_width_entered').val('');
                $('#material_quantity_entered').val('');
                break;
            case 'complete':
                $('#width_entered_material').hide();
                $('#length_entered_material').hide();
                $('#quantity_entered_material').show();
                $('#material_length_entered').val('');
                $('#material_width_entered').val('');
                $('#material_quantity_entered').val('');
                break;
        }
    });

    $('.material_search').select2({
        placeholder: 'Selecciona un material',
        ajax: {
            url: '/dashboard/select/materials',
            dataType: 'json',
            type: 'GET',
            processResults(data) {
                //console.log(data);
                return {
                    results: $.map(data, function (item) {
                        //console.log(item.full_description);
                        return {
                            text: item.full_description,
                            id: item.id,
                        }
                    })
                }
            }
        }
    });

    $('.consumable_search').select2({
        placeholder: 'Selecciona un consumible',
        ajax: {
            url: '/dashboard/select/consumables',
            dataType: 'json',
            type: 'GET',
            processResults(data) {
                //console.log(data);
                return {
                    results: $.map(data, function (item) {
                        //console.log(item.full_description);
                        return {
                            text: item.full_description,
                            id: item.id,
                        }
                    })
                }
            }
        }
    });

    $(document).on('click', '[data-delete]', deleteItem);

    $(document).on('click', '[data-deleteConsumable]', deleteConsumable);

    $(document).on('click', '[data-deleteMano]', deleteMano);

    $(document).on('click', '[data-deleteTorno]', deleteTorno);

    $(document).on('click', '[data-deleteEquipment]', deleteEquipment);

    $total = parseFloat($('#quote_total').val());
    $subtotal = parseFloat($('#quote_subtotal_utility').val());
    $subtotal2 = parseFloat($('#quote_subtotal_letter').val());
    $subtotal3 = parseFloat($('#quote_subtotal_rent').val());
});

var $formCreate;
var $modalAddMaterial;
var $material;
var $renderMaterial;

function deleteEquipment() {
    var button = $(this);
    $.confirm({
        icon: 'fas fa-frown',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: 'Eliminar Equipo',
        content: '¿Está seguro de eliminar este equipo?',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    var equipmentId = parseInt(button.data('deleteequipment'));
                    console.log(equipmentId);

                    var equipmentDeleted = $equipments.find(equipment => equipment.id === equipmentId);
                    console.log(equipmentDeleted);

                    $equipments = $equipments.filter(equipment => equipment.id !== equipmentId);
                    button.parent().parent().parent().parent().remove();
                    if ( $equipments.length === 0 ) {
                        renderTemplateEquipment();
                        $equipmentStatus = false;
                    }

                    $total = parseFloat($total) - parseFloat(equipmentDeleted.total);
                    $('#subtotal').html('S/. '+$total);
                    calculateMargen2($('#utility').val());
                    calculateLetter2($('#letter').val());
                    calculateRent2($('#taxes').val());

                    $.alert("Equipo eliminado!");

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Eliminación cancelada.");
                },
            },
        },
    });

}

function deleteConsumable() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
}

function deleteMano() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
}

function deleteTorno() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
}

function addConsumable() {
    var consumableID = $(this).parent().parent().find('[data-consumable]').val();
    //console.log(material);
    var inputQuantity = $(this).parent().parent().find('[data-cantidad]');
    var cantidad = inputQuantity.val();
    if ( cantidad === '' || parseInt(cantidad) === 0 )
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

    if ( consumableID === '' || consumableID === null )
    {
        toastr.error('Debe seleccionar un consumible', 'Error',
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

    var render = $(this).parent().parent().next().next();

    var consumable = $consumables.find( mat=>mat.id === parseInt(consumableID) );

    var consumables = $(this).parent().parent().next().next().children();

    consumables.each(function(e){
        var id = $(this).children().children().children().next().val();
        if (parseInt(consumable.id) === parseInt(id)) {
            inputQuantity.val(0);
            $(".consumable_search").empty().trigger('change');
            toastr.error('Este material ya esta seleccionado', 'Error',
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
            e.stopPropagation();
            return false ;
        }
    });
    inputQuantity.val(0);
    $(".consumable_search").empty().trigger('change');
    renderTemplateConsumable(render, consumable, cantidad);
}

function addMano() {
    var precio = $(this).parent().prev().children().children().next().val();
    var cantidad = $(this).parent().prev().prev().children().children().next().val();
    var unidad = $(this).parent().prev().prev().prev().children().children().next().next().text();
    var unidadID = $(this).parent().prev().prev().prev().children().children().next().val();
    var descripcion = $(this).parent().prev().prev().prev().prev().children().children().next().val();

    if ( descripcion === '' )
    {
        toastr.error('Escriba una descripción adecuada.', 'Error',
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
    if ( unidadID === '' || parseInt(unidadID) === 0 )
    {
        toastr.error('Seleccione una unidad válida.', 'Error',
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
    if ( cantidad === '' || parseInt(cantidad) === 0 )
    {
        toastr.error('Agregue una cantidad válida.', 'Error',
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
    if ( precio === '' || parseFloat(precio) === 0 )
    {
        toastr.error('Agregue un precio válido.', 'Error',
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

    $(this).parent().prev().prev().prev().prev().children().children().next().val('');
    $(".unitMeasure").empty().trigger('change');
    $(this).parent().prev().prev().children().children().next().val(0);
    $(this).parent().prev().children().children().next().val(0);
    //console.log(descripcion);
    var render = $(this).parent().parent().next().next();
    renderTemplateMano(render, descripcion, unidad, cantidad, precio);
}

function addTorno() {
    var precio = $(this).parent().prev().children().children().next().val();
    var cantidad = $(this).parent().prev().prev().children().children().next().val();
    var descripcion = $(this).parent().prev().prev().prev().children().children().next().val();

    if ( descripcion === '' )
    {
        toastr.error('Escriba una descripción adecuada.', 'Error',
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
    if ( cantidad === '' || parseInt(cantidad) === 0 )
    {
        toastr.error('Agregue una cantidad válida.', 'Error',
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
    if ( precio === '' || parseFloat(precio) === 0 )
    {
        toastr.error('Agregue un precio válido.', 'Error',
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

    $(this).parent().prev().prev().prev().children().children().next().val('');
    $(this).parent().prev().prev().children().children().next().val(0);
    $(this).parent().prev().children().children().next().val(0);
    //console.log(descripcion);
    var render = $(this).parent().parent().next().next();
    renderTemplateTorno(render, descripcion, cantidad, precio);
}

function confirmEquipment() {
    var button = $(this);
    $.confirm({
        icon: 'fas fa-smile',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: 'Confirmar Equipo',
        content: 'Debe confirmar para almacenar el equipo en memoria',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    //var cantidad = button.parent().parent().next().children().children().children().next();
                    //console.log($(this));
                    $equipmentStatus = true;
                    // Quitamos el boton
                    button.hide();
                    //$items.push({ 'id': $items.length+1, 'material': $material, 'material_quantity': material_quantity, 'material_price':total});
                    //console.log(button);
                    button.next().show();
                    var quantity = button.parent().parent().next().children().children().children().next().val();
                    var description = button.parent().parent().next().children().children().next().next().children().next().val();
                    var detail = button.parent().parent().next().children().children().next().next().next().children().next().val();
                    var consumables = button.parent().parent().next().children().next().next().children().next().children().next().next();
                    var workforces = button.parent().parent().next().children().next().next().next().children().next().children().next().next();
                    var tornos = button.parent().parent().next().children().next().next().next().children().next().children().next().next().next().next().children().next().children().next().next();

                    var consumablesDescription = [];
                    var consumablesIds = [];
                    var consumablesUnit = [];
                    var consumablesQuantity = [];
                    var consumablesPrice = [];
                    var consumablesTotal = [];

                    consumables.each(function(e){
                        $(this).find('[data-consumableDescription]').each(function(){
                            consumablesDescription.push($(this).val());
                        });
                        $(this).find('[data-consumableId]').each(function(){
                            consumablesIds.push($(this).val());
                        });
                        $(this).find('[data-consumableUnit]').each(function(){
                            consumablesUnit.push($(this).val());
                        });
                        $(this).find('[data-consumableQuantity]').each(function(){
                            consumablesQuantity.push($(this).val());
                        });
                        $(this).find('[data-consumablePrice]').each(function(){
                            consumablesPrice.push($(this).val());
                        });
                        $(this).find('[data-consumableTotal]').each(function(){
                            consumablesTotal.push($(this).val());
                        });
                    });

                    var consumablesArray = [];

                    for (let i = 0; i < consumablesDescription.length; i++) {
                        consumablesArray.push({'id':consumablesIds[i], 'description':consumablesDescription[i], 'unit':consumablesUnit[i], 'quantity':consumablesQuantity[i], 'price': consumablesPrice[i], 'total': consumablesTotal[i]});
                    }

                    var manosDescription = [];
                    var manosIds = [];
                    var manosUnit = [];
                    var manosQuantity = [];
                    var manosPrice = [];
                    var manosTotal = [];

                    workforces.each(function(e){
                        $(this).find('[data-manoDescription]').each(function(){
                            manosDescription.push($(this).val());
                        });
                        $(this).find('[data-manoId]').each(function(){
                            manosIds.push($(this).val());
                        });
                        $(this).find('[data-manoUnit]').each(function(){
                            manosUnit.push($(this).val());
                        });
                        $(this).find('[data-manoQuantity]').each(function(){
                            manosQuantity.push($(this).val());
                        });
                        $(this).find('[data-manoPrice]').each(function(){
                            manosPrice.push($(this).val());
                        });
                        $(this).find('[data-manoTotal]').each(function(){
                            manosTotal.push($(this).val());
                        });
                    });

                    var manosArray = [];

                    for (let i = 0; i < manosDescription.length; i++) {
                        manosArray.push({'id':manosIds[i], 'description':manosDescription[i], 'unit':manosUnit[i], 'quantity':manosQuantity[i], 'price':manosPrice[i], 'total': manosTotal[i]});
                    }

                    var tornosDescription = [];
                    var tornosQuantity = [];
                    var tornosPrice = [];
                    var tornosTotal = [];

                    tornos.each(function(e){
                        $(this).find('[data-tornoDescription]').each(function(){
                            tornosDescription.push($(this).val());
                        });
                        $(this).find('[data-tornoQuantity]').each(function(){
                            tornosQuantity.push($(this).val());
                        });
                        $(this).find('[data-tornoPrice]').each(function(){
                            tornosPrice.push($(this).val());
                        });
                        $(this).find('[data-tornoTotal]').each(function(){
                            tornosTotal.push($(this).val());
                        });
                    });

                    var tornosArray = [];

                    for (let i = 0; i < tornosDescription.length; i++) {
                        tornosArray.push({'description':tornosDescription[i], 'quantity':tornosQuantity[i], 'price':tornosPrice[i], 'total': tornosTotal[i]});
                    }

                    var totalEquipment = 0;
                    for (let i = 0; i < $items.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat($items[i].material_price);
                    }
                    for (let i = 0; i < tornosTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(tornosTotal[i]);
                    }
                    for (let i = 0; i < manosTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(manosTotal[i]);
                    }
                    for (let i = 0; i < consumablesTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(consumablesTotal[i]);
                    }
                    totalEquipment = parseFloat((totalEquipment * quantity)).toFixed(2);

                    $total = parseFloat($total) + parseFloat(totalEquipment);

                    $('#subtotal').html('S/. '+$total);

                    calculateMargen2($('#utility').val());
                    calculateLetter2($('#letter').val());
                    calculateRent2($('#taxes').val());

                    button.next().attr('data-deleteEquipment', $equipments.length);
                    $equipments.push({'id':$equipments.length, 'quantity':quantity, 'total':totalEquipment, 'description':description, 'detail':detail, 'materials': $items, 'consumables':consumablesArray, 'workforces':manosArray});

                    $items = [];
                    $.alert("Equipo confirmado!");

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $equipmentStatus = false;
                    $.alert("Confirmación cancelada.");
                },
            },
        },
    });

}

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function calculateMargen(e) {
    var margen = e.value;

    var letter = $('#letter').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#subtotal2').html('S/. '+$subtotal);
    $('#subtotal3').html('S/. '+$subtotal2);
    $('#total').html('S/. '+$subtotal3);
}

function calculateLetter(e) {
    var letter = e.value;
    var margen = $('#utility').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);
    $('#subtotal3').html('S/. '+$subtotal2);
    $('#total').html('S/. '+$subtotal3);
}

function calculateRent(e) {
    var rent = e.value;
    var margen = $('#utility').val();
    var letter = $('#letter').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#total').html('S/. '+$subtotal3);

}

function calculateMargen2(margen) {
    var letter = $('#letter').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#subtotal2').html('S/. '+$subtotal);
    $('#subtotal3').html('S/. '+$subtotal2);
    $('#total').html('S/. '+$subtotal3);

}

function calculateLetter2(letter) {
    var margen = $('#utility').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);
    $('#subtotal3').html('S/. '+$subtotal2);
    $('#total').html('S/. '+$subtotal3);

}

function calculateRent2(rent) {
    var margen = $('#utility').val();
    var letter = $('#letter').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#total').html('S/. '+$subtotal3);

}

function calculateTotal(e) {
    var cantidad = e.value;
    var precio = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);

}

function calculateTotal2(e) {
    var precio = e.value;
    var cantidad = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);

}

function addEquipment() {
    // TODO: Aqui voy a preguntar si hay equipos con
    var result = document.querySelectorAll('[data-equip]');
    //console.log(result);
    for (var index in result){
        if (result.hasOwnProperty(index)){
            if(result[index].getAttribute('style')!==null){
                //console.log(result[index].getAttribute('style'));
                $equipmentStatus=true;
            }
        }
    }

    if ( !$equipmentStatus )
    {
        toastr.error('Confirme el equipo antes de agregar otro.', 'Error',
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
    renderTemplateEquipment();
    $('.material_search').select2({
        placeholder: 'Selecciona un material',
        ajax: {
            url: '/dashboard/select/materials',
            dataType: 'json',
            type: 'GET',
            processResults(data) {
                //console.log(data);
                return {
                    results: $.map(data, function (item) {
                        //console.log(item.full_description);
                        return {
                            text: item.full_description,
                            id: item.id,
                        }
                    })
                }
            }
        }
    });
    $('.consumable_search').select2({
        placeholder: 'Selecciona un consumible',
        ajax: {
            url: '/dashboard/select/consumables',
            dataType: 'json',
            type: 'GET',
            processResults(data) {
                //console.log(data);
                return {
                    results: $.map(data, function (item) {
                        //console.log(item.full_description);
                        return {
                            text: item.full_description,
                            id: item.id,
                        }
                    })
                }
            }
        }
    });
    $equipmentStatus = false;
}

function deleteItem() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
    var itemId = $(this).data('delete');
    $items = $items.filter(item => item.id !== itemId);
}

function calculatePercentage() {
    if( $('#material_length_entered').val().trim() === '' && $("#quantity_entered_material").css('display') === 'none' )
    {
        toastr.error('Debe ingresar la longitud del material', 'Error',
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
    if( $('#material_width_entered').val().trim() === '' && $("#quantity_entered_material").css('display') === 'none' )
    {
        toastr.error('Debe ingresar el ancho del material', 'Error',
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
    if( $('#material_quantity_entered').val().trim() === '' && $("#quantity_entered_material").attr('style') === '' )
    {
        toastr.error('Debe ingresar la cantidad del material', 'Error',
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

    if ($('#material_length_entered').val().trim() !== '' && $('#material_width_entered').val().trim() !== '')
    {
        var price_material = parseFloat($('#material_price').val());
        var length_material = parseFloat($('#material_length').val());
        var width_material = parseFloat($('#material_width').val());
        var length = parseFloat($('#material_length_entered').val());
        var width = parseFloat($('#material_width_entered').val());
        var areaTotal = length_material*width_material;
        var areaNueva = length*width;
        var percentage = parseFloat(areaNueva/areaTotal).toFixed(2);
        var new_price = parseFloat(percentage*price_material).toFixed(2);
        $('#material_percentage_entered').val(percentage);
        $('#material_price_entered').val(new_price);
    }

    if ($('#material_length_entered').val().trim() !== '' && $("#width_entered_material").attr('style') === '' )
    {
        var price_material2 = parseFloat($('#material_price').val());
        var length_material2 = parseFloat($('#material_length').val());

        var length2 = parseFloat($('#material_length_entered').val());

        var percentage2 = parseFloat(length2/length_material2).toFixed(2);
        var new_price2 = parseFloat(percentage2*price_material2).toFixed(2);
        $('#material_percentage_entered').val(percentage2);
        $('#material_price_entered').val(new_price2);
    }

    if ( $('#material_quantity_entered').val().trim() !== '' )
    {
        var price_material3 = parseFloat($('#material_price').val());
        var quantity_entered = parseFloat($('#material_quantity_entered').val());
        var new_price3 = parseFloat(quantity_entered*price_material3).toFixed(2);
        $('#material_percentage_entered').val(quantity_entered);
        $('#material_price_entered').val(new_price3);

    }
}

function addTableMaterials() {
    if( $('#material_length_entered').val().trim() === '' && $("#length_entered_material").attr('style') === '' )
    {
        toastr.error('Debe ingresar la longitud del material', 'Error',
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
    if( $('#material_width_entered').val().trim() === '' && $("#width_entered_material").attr('style') === '' )
    {
        toastr.error('Debe ingresar el ancho del material', 'Error',
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
    if( $("#material_quantity_entered").css('display') === '' && $('#material_quantity_entered').val().trim() === '' )
    {
        toastr.error('Debe ingresar la cantidad del material', 'Error',
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
    if( $('#material_percentage_entered').val().trim() === '' )
    {
        toastr.error('Debe hacer click en calcular', 'Error',
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
    if( $('#material_price_entered').val().trim() === '' )
    {
        toastr.error('Debe hacer click en calcular', 'Error',
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

    var material_quantity = ($("#material_quantity_entered").css('display') === '') ? $("#material_quantity_entered").val(): $("#material_percentage_entered").val();
    var total = $("#material_price_entered").val();
    var length = $('#material_length_entered').val();
    var witdh = $('#material_width_entered').val();

    $items.push({ 'id': $items.length+1, 'material': $material, 'material_quantity': material_quantity, 'material_price':total, 'material_length':length, 'material_width':witdh});
    renderTemplateMaterial($items.length, $material.code, $material.full_description, material_quantity, $material.unit_measure.name, $material.unit_price, total, $renderMaterial);

    $('#material_length_entered').val('');
    $('#material_width_entered').val('');
    $('#material_percentage_entered').val('');
    $('#material_price_entered').val('');
    $('#material_quantity_entered').val('');
    $(".material_search").empty().trigger('change');
    $modalAddMaterial.modal('hide');
}

function addMaterial() {
    var select_material = $(this).parent().parent().children().children().children().next();
    //console.log(select_material.val());
    var material_search = select_material.val();

    $material = $materials.find( mat=>mat.id === parseInt(material_search) );

    if( $material === undefined )
    {
        toastr.error('Debe seleccionar un material', 'Error',
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

    for (var i=0; i<$items.length; i++)
    {
        var mat = $items.find( mat=>mat.material.id === $material.id );
        if (mat !== undefined)
        {
            toastr.error('Este material ya esta seleccionado', 'Error',
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

    if ( $material.type_scrap === null )
    {
        $('#presentation').hide();
        $('#length_material').hide();
        $('#width_material').hide();
        $('#width_entered_material').hide();
        $('#length_entered_material').hide();
        $('#material_quantity').val($material.stock_current);
        $('#quantity_entered_material').show();
        $('#material_price').val($material.unit_price);

        $renderMaterial = $(this).parent().parent().next().next().children().children().next();

        $modalAddMaterial.modal('show');
    } else {
        switch($material.type_scrap.id) {
            case 1:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#width_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#material_width').val($material.type_scrap.width);
                $('#material_quantity').val($material.stock_current);
                $('#quantity_entered_material').hide();
                $('#material_price').val($material.unit_price);
                break;
            case 2:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#width_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#material_width').val($material.type_scrap.width);
                $('#quantity_entered_material').hide();
                $('#material_quantity').val($material.stock_current);
                $('#material_price').val($material.unit_price);
                break;
            case 3:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#width_material').hide();
                $('#width_entered_material').hide();
                $('#quantity_entered_material').hide();
                $('#material_quantity').val($material.stock_current);
                $('#material_price').val($material.unit_price);
                break;
            default:
                $('#length_material').hide();
                $('#width_material').hide();
                $('#width_entered_material').hide();
                $('#length_entered_material').hide();
                $('#material_quantity').val($material.stock_current);
                $('#material_percentage_entered').hide();
                $('#material_price').val($material.unit_price);

        }
        //var idMaterial = $(this).select2('data').id;

        $renderMaterial = $(this).parent().parent().next().next().children().children().next();

        $modalAddMaterial.modal('show');
    }


}

function storeQuote() {
    event.preventDefault();
    /*if( $equipments.length === 0 )
    {
        toastr.error('No se puede crear una cotización sin equipos.', 'Error',
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
    }*/
    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var equipos = JSON.stringify($equipments);
    var form = new FormData(this);
    form.append('equipments', equipos);
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
}

function renderTemplateMaterial(id, code, description, quantity, unit, price, total, render) {
    var clone = activateTemplate('#materials-selected');
    clone.querySelector("[data-code]").innerHTML = code;
    clone.querySelector("[data-description]").innerHTML = description;
    clone.querySelector("[data-unit]").innerHTML = unit;
    clone.querySelector("[data-quantity]").innerHTML = quantity;
    clone.querySelector("[data-price]").innerHTML = price;
    clone.querySelector("[data-total]").innerHTML = total;
    clone.querySelector("[data-delete]").setAttribute('data-delete', id);
    render.append(clone);
}

function renderTemplateConsumable(render, consumable, quantity) {
    var clone = activateTemplate('#template-consumable');
    clone.querySelector("[data-consumableDescription]").setAttribute('value', consumable.full_description);
    clone.querySelector("[data-consumableId]").setAttribute('value', consumable.id);
    clone.querySelector("[data-consumableUnit]").setAttribute('value', consumable.unit_measure.name);
    clone.querySelector("[data-consumableQuantity]").setAttribute('value', quantity);
    clone.querySelector("[data-consumablePrice]").setAttribute('value', consumable.unit_price);
    clone.querySelector("[data-consumableTotal]").setAttribute( 'value', (parseFloat(consumable.unit_price)*parseFloat(quantity)).toFixed(2));
    clone.querySelector("[data-deleteConsumable]").setAttribute('data-deleteConsumable', consumable.id);
    render.append(clone);
}

function renderTemplateMano(render, description, unit, quantity, unitPrice) {
    var clone = activateTemplate('#template-mano');
    clone.querySelector("[data-manoDescription]").setAttribute('value', description);
    clone.querySelector("[data-manoUnit]").setAttribute('value', unit);
    clone.querySelector("[data-manoQuantity]").setAttribute('value', quantity);
    clone.querySelector("[data-manoPrice]").setAttribute('value', unitPrice);
    clone.querySelector("[data-manoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));

    render.append(clone);
}

function renderTemplateTorno(render, description, quantity, unitPrice) {
    var clone = activateTemplate('#template-torno');
    clone.querySelector("[data-tornoDescription]").setAttribute('value', description);
    clone.querySelector("[data-tornoQuantity]").setAttribute('value', quantity);
    clone.querySelector("[data-tornoPrice]").setAttribute('value', unitPrice);
    clone.querySelector("[data-tornoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));

    render.append(clone);
}

function renderTemplateEquipment() {
    var clone = activateTemplate('#template-equipment');

    $('#body-equipment').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}