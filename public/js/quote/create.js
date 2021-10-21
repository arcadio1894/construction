let $materials=[];
let $materialsTypeahead=[];
let $consumables=[];
let $items=[];
let $equipments=[];
let $equipmentStatus=false;
let $total=0;
let $subtotal=0;
let $subtotal2=0;
let $subtotal3=0;
var $permissions;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

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

    $.ajax({
        url: "/dashboard/get/materials",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                $materialsTypeahead.push(json[i].material);
            }

        }
    });

    $('.materialTypeahead').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
            minLength: 1 /* Specify minimum characters required for showing suggestions */
        },
        {
            limit: 12,
            source: substringMatcher($materialsTypeahead)
        });
    /*$('.material_search').select2({
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
    });*/

    $modalAddMaterial = $('#modalAddMaterial');

    $(document).on('click', '[data-add]', addMaterial);
    
    $(document).on('click', '[data-confirm]', confirmEquipment);

    $(document).on('click', '[data-addMano]', addMano);

    $(document).on('click', '[data-addTorno]', addTorno);

    $(document).on('click', '[data-addDia]', addDia);

    $(document).on('click', '[data-addConsumable]', addConsumable);

    $('#btn-addEquipment').on('click', addEquipment);

    $('#btn-addMaterial').on('click', addTableMaterials);
    
    $('#btnCalculate').on('click', calculatePercentage);

    $formCreate = $('#formCreate');
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

    /*$('.material_search').select2({
        placeholder: 'Selecciona un material',
        ajax: {
            url: '/dashboard/select/materials',
            dataType: 'json',
            delay: 250,
            type: 'GET',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data.results, function (item) {
                        //console.log(item.full_description);
                        return {
                            text: item.full_description,
                            id: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });*/

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

    $(document).on('click', '[data-deleteDia]', deleteDia);

    $(document).on('click', '[data-deleteEquipment]', deleteEquipment);

    $(document).on('click', '[data-saveEquipment]', saveEquipment);

    //$("input[id='"+this.id+"']")   $('.materialTypeahead')
    $(document).on('typeahead:select', '.materialTypeahead', function(ev, suggestion) {
        var select_material = $(this);
        console.log($(this).val());
        // TODO: Tomar el texto no el val()
        var material_search = select_material.val();

        $material = $materials.find( mat=>mat.full_description === material_search );

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

        /*for (var i=0; i<$items.length; i++)
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
        }*/

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

            // TODO: Render esta fallando
            $renderMaterial = $(this).parent().parent().parent().parent().next().next().next();
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
            $renderMaterial = $(this).parent().parent().parent().parent().next().next().next();
            $modalAddMaterial.modal('show');
        }
    });
});

var $formCreate;
var $modalAddMaterial;
var $material;
var $renderMaterial;

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

function saveEquipment() {
    var button = $(this);
    console.log(button);
    $.confirm({
        icon: 'fas fa-smile',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'orange',
        title: 'Guardar cambios',
        content: '¿Está seguro de guardar los cambios en este equipo?',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    var equipmentId = parseInt(button.data('saveequipment'));
                    console.log(equipmentId);
                    var equipmentDeleted = $equipments.find(equipment => equipment.id === equipmentId);
                    console.log(equipmentDeleted);

                    $equipments = $equipments.filter(equipment => equipment.id !== equipmentId);
                    //button.parent().parent().parent().parent().remove();
                    /*if ( $equipments.length === 0 ) {
                        renderTemplateEquipment();
                        $equipmentStatus = false;
                    }*/
                    // TODO: Capturar los materiales y recorrerlos y agregar al anterior
                    // TODO: En data-delete (material) debe estar el equipo tambien

                    $total = parseFloat($total) - parseFloat(equipmentDeleted.total);
                    $('#subtotal').html('S/. '+$total);
                    calculateMargen2($('#utility').val());
                    calculateLetter2($('#letter').val());
                    calculateRent2($('#taxes').val());

                    //TODO: Otra vez guardamos el equipo

                    var quantity = button.parent().parent().next().children().children().children().next().val();
                    var description = button.parent().parent().next().children().children().next().next().children().next().val();
                    var detail = button.parent().parent().next().children().children().next().next().next().children().next().val();
                    var materials = button.parent().parent().next().children().next().children().next().children().next().next().next();
                    var consumables = button.parent().parent().next().children().next().next().children().next().children().next().next();
                    var workforces = button.parent().parent().next().children().next().next().next().children().next().children().next().next();
                    var tornos = button.parent().parent().next().children().next().next().next().children().next().children().next().next().next().next().children().next().children().next().next();
                    var dias = button.parent().parent().next().children().next().next().next().next().children().next().children().next().next().next();

                    var materialsDescription = [];
                    var materialsUnit = [];
                    var materialsLargo = [];
                    var materialsAncho = [];
                    var materialsQuantity = [];
                    var materialsPrice = [];
                    var materialsTotal = [];

                    materials.each(function(e){
                        $(this).find('[data-materialDescription]').each(function(){
                            materialsDescription.push($(this).val());
                        });
                        $(this).find('[data-materialUnit]').each(function(){
                            materialsUnit.push($(this).val());
                        });
                        $(this).find('[data-materialLargo]').each(function(){
                            materialsLargo.push($(this).val());
                        });
                        $(this).find('[data-materialAncho]').each(function(){
                            materialsAncho.push($(this).val());
                        });
                        $(this).find('[data-materialQuantity]').each(function(){
                            materialsQuantity.push($(this).val());
                        });
                        $(this).find('[data-materialPrice]').each(function(){
                            materialsPrice.push($(this).val());
                        });
                        $(this).find('[data-materialTotal]').each(function(){
                            materialsTotal.push($(this).val());
                        });
                    });

                    var materialsArray = [];

                    for (let i = 0; i < materialsDescription.length; i++) {
                        var materialSelected = $materials.find( mat=>mat.full_description === materialsDescription[i] );
                        materialsArray.push({'id':materialSelected.id,'material':materialSelected, 'description':materialsDescription[i], 'unit':materialsUnit[i], 'length':materialsLargo[i], 'width':materialsAncho[i], 'quantity':materialsQuantity[i], 'price': materialsPrice[i], 'total': materialsTotal[i]});
                    }

                    var diasCantidad = [];
                    var diasHoras = [];
                    var diasPrecio = [];
                    var diasTotal = [];

                    dias.each(function(e){
                        $(this).find('[data-cantidad]').each(function(){
                            diasCantidad.push($(this).val());
                        });
                        $(this).find('[data-horas]').each(function(){
                            diasHoras.push($(this).val());
                        });
                        $(this).find('[data-precio]').each(function(){
                            diasPrecio.push($(this).val());
                        });
                        $(this).find('[data-total]').each(function(){
                            diasTotal.push($(this).val());
                        });
                    });

                    var diasArray = [];

                    for (let i = 0; i < diasCantidad.length; i++) {
                        diasArray.push({'quantity':diasCantidad[i], 'hours':diasHoras[i], 'price':diasPrecio[i], 'total': diasTotal[i]});
                    }

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
                        $(this).find('[data-consumableid]').each(function(){
                            console.log($(this).attr('data-consumableid'));
                            consumablesIds.push($(this).attr('data-consumableid'));
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
                    for (let i = 0; i < materialsTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(materialsTotal[i]);
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
                    for (let i = 0; i < diasTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(diasTotal[i]);
                    }
                    totalEquipment = parseFloat((totalEquipment * quantity)).toFixed(2);

                    $total = parseFloat($total) + parseFloat(totalEquipment);

                    $('#subtotal').html('S/. '+$total);

                    calculateMargen2($('#utility').val());
                    calculateLetter2($('#letter').val());
                    calculateRent2($('#taxes').val());

                    button.attr('data-saveEquipment', $equipments.length);
                    button.next().attr('data-deleteEquipment', $equipments.length);
                    $equipments.push({'id':$equipments.length, 'quantity':quantity, 'total':totalEquipment, 'description':description, 'detail':detail, 'materials': materialsArray, 'consumables':consumablesArray, 'workforces':manosArray, 'tornos':tornosArray, 'dias':diasArray});

                    $items = [];

                    $.alert("Equipo guardado!");

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

function deleteDia() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
}

function deleteTorno() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
}

function addConsumable() {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
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
    } else {
        var consumableID2 = $(this).parent().parent().find('[data-consumable]').val();
        //console.log(material);
        var inputQuantity2 = $(this).parent().parent().find('[data-cantidad]');
        var cantidad2 = inputQuantity2.val();
        if ( cantidad2 === '' || parseInt(cantidad2) === 0 )
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

        if ( consumableID2 === '' || consumableID2 === null )
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

        var render2 = $(this).parent().parent().next().next();

        var consumable2 = $consumables.find( mat=>mat.id === parseInt(consumableID2) );
        var consumables2 = $(this).parent().parent().next().next().children();

        consumables2.each(function(e){
            var id = $(this).children().children().children().next().val();
            if (parseInt(consumable2.id) === parseInt(id)) {
                inputQuantity2.val(0);
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
        inputQuantity2.val(0);
        $(".consumable_search").empty().trigger('change');
        renderTemplateConsumable(render2, consumable2, cantidad2);
    }

}

function addMano() {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
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
        $(".unitMeasure").val('').trigger('change');
        $(this).parent().prev().prev().children().children().next().val(0);
        $(this).parent().prev().children().children().next().val(0);
        //console.log(descripcion);
        var render = $(this).parent().parent().next().next().next();
        renderTemplateMano(render, descripcion, unidad, cantidad, precio);
    } else {
        var precio2 = 0;
        var cantidad2 = $(this).parent().prev().children().children().next().val();
        var unidad2 = $(this).parent().prev().prev().children().children().next().next().text();
        var unidadID2 = $(this).parent().prev().prev().children().children().next().val();
        var descripcion2 = $(this).parent().prev().prev().prev().children().children().next().val();

        if ( descripcion2 === '' )
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
        if ( unidadID2 === '' || parseInt(unidadID2) === 0 )
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
        if ( cantidad2 === '' || parseInt(cantidad2) === 0 )
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


        $(this).parent().prev().prev().prev().children().children().next().val('');
        $(".unitMeasure").val('').trigger('change');
        $(this).parent().prev().children().children().next().val(0);
        $(this).parent().children().children().next().val(0);
        //console.log(descripcion);
        var render2 = $(this).parent().parent().next().next().next();
        console.log(render2);
        renderTemplateMano(render2, descripcion2, unidad2, cantidad2, precio2);
    }

}

function addDia() {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        var pricePerHour = $(this).parent().prev().children().children().next().val();
        var hoursPerPerson = $(this).parent().prev().prev().children().children().next().val();
        var quantityPerson = $(this).parent().prev().prev().prev().children().children().next().val();

        if ( quantityPerson === '' || parseFloat(quantityPerson) === 0 )
        {
            toastr.error('Ingrese un valor correcto.', 'Error',
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
        if ( hoursPerPerson === '' || parseFloat(hoursPerPerson) === 0 )
        {
            toastr.error('Ingrese un valor válido.', 'Error',
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
        if ( pricePerHour === '' || parseFloat(pricePerHour) === 0 )
        {
            toastr.error('Ingrese un precio válido.', 'Error',
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

        $(this).parent().prev().children().children().next().val(0);
        $(this).parent().prev().prev().children().children().next().val(0);
        $(this).parent().prev().prev().prev().children().children().next().next().val(0);
        //console.log(descripcion);
        var render = $(this).parent().parent().next().next().next();
        var total = parseFloat(pricePerHour)*parseFloat(hoursPerPerson)*parseFloat(quantityPerson);
        renderTemplateDia(render, pricePerHour, hoursPerPerson, quantityPerson, total.toFixed(2));
    } else {
        var pricePerHour2 = 0;
        var hoursPerPerson2 = $(this).parent().prev().children().children().next().val();
        var quantityPerson2 = $(this).parent().prev().prev().children().children().next().val();

        if ( quantityPerson2 === '' || parseFloat(quantityPerson2) === 0 )
        {
            toastr.error('Ingrese un valor correcto.', 'Error',
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
        if ( hoursPerPerson2 === '' || parseFloat(hoursPerPerson2) === 0 )
        {
            toastr.error('Ingrese un valor válido.', 'Error',
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

        //$(this).parent().prev().children().children().next().val(0);
        $(this).parent().prev().prev().children().children().next().val(0);
        $(this).parent().prev().children().children().next().val(0);
        //console.log(descripcion);
        var render2 = $(this).parent().parent().next().next().next();
        console.log(render2);
        var total2 = 0;
        renderTemplateDia(render2, pricePerHour2, hoursPerPerson2, quantityPerson2, total2);
    }

}

function addTorno() {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
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
    } else {
        var precio2 = 0;
        var cantidad2 = $(this).parent().prev().children().children().next().val();
        var descripcion2 = $(this).parent().prev().prev().children().children().next().val();
        console.log($(this).parent().prev().children().children().next());
        console.log($(this).parent().prev().prev().children().children().next());
        if ( descripcion2 === '' )
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
        if ( cantidad2 === '' || parseInt(cantidad2) === 0 )
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

        $(this).parent().prev().children().children().next().val(0);
        $(this).parent().prev().prev().children().children().next().val('');

        //console.log(descripcion);
        var render2 = $(this).parent().parent().next().next();
        renderTemplateTorno(render2, descripcion2, cantidad2, precio2);
    }

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
                    /*$equipmentStatus = true;*/
                    // Quitamos el boton
                    button.hide();
                    //$items.push({ 'id': $items.length+1, 'material': $material, 'material_quantity': material_quantity, 'material_price':total});
                    //console.log(button);
                    button.next().show();
                    button.next().next().show();

                    var quantity = button.parent().parent().next().children().children().children().next().val();
                    var description = button.parent().parent().next().children().children().next().next().children().next().val();
                    var detail = button.parent().parent().next().children().children().next().next().next().children().next().val();
                    var materials = button.parent().parent().next().children().next().children().next().children().next().next().next();
                    var consumables = button.parent().parent().next().children().next().next().children().next().children().next().next();
                    var workforces = button.parent().parent().next().children().next().next().next().children().next().children().next().next();
                    var tornos = button.parent().parent().next().children().next().next().next().children().next().children().next().next().next().next().children().next().children().next().next();
                    var dias = button.parent().parent().next().children().next().next().next().next().children().next().children().next().next().next();

                    var materialsDescription = [];
                    var materialsUnit = [];
                    var materialsLargo = [];
                    var materialsAncho = [];
                    var materialsQuantity = [];
                    var materialsPrice = [];
                    var materialsTotal = [];

                    materials.each(function(e){
                        $(this).find('[data-materialDescription]').each(function(){
                            materialsDescription.push($(this).val());
                        });
                        $(this).find('[data-materialUnit]').each(function(){
                            materialsUnit.push($(this).val());
                        });
                        $(this).find('[data-materialLargo]').each(function(){
                            materialsLargo.push($(this).val());
                        });
                        $(this).find('[data-materialAncho]').each(function(){
                            materialsAncho.push($(this).val());
                        });
                        $(this).find('[data-materialQuantity]').each(function(){
                            materialsQuantity.push($(this).val());
                        });
                        $(this).find('[data-materialPrice]').each(function(){
                            materialsPrice.push($(this).val());
                        });
                        $(this).find('[data-materialTotal]').each(function(){
                            materialsTotal.push($(this).val());
                        });
                    });

                    var materialsArray = [];

                    for (let i = 0; i < materialsDescription.length; i++) {
                        var materialSelected = $materials.find( mat=>mat.full_description === materialsDescription[i] );
                        materialsArray.push({'id':materialSelected.id,'material':materialSelected, 'description':materialsDescription[i], 'unit':materialsUnit[i], 'length':materialsLargo[i], 'width':materialsAncho[i], 'quantity':materialsQuantity[i], 'price': materialsPrice[i], 'total': materialsTotal[i]});
                    }

                    var diasCantidad = [];
                    var diasHoras = [];
                    var diasPrecio = [];
                    var diasTotal = [];

                    dias.each(function(e){
                        $(this).find('[data-cantidad]').each(function(){
                            diasCantidad.push($(this).val());
                        });
                        $(this).find('[data-horas]').each(function(){
                            diasHoras.push($(this).val());
                        });
                        $(this).find('[data-precio]').each(function(){
                            diasPrecio.push($(this).val());
                        });
                        $(this).find('[data-total]').each(function(){
                            diasTotal.push($(this).val());
                        });
                    });

                    var diasArray = [];

                    for (let i = 0; i < diasCantidad.length; i++) {
                        diasArray.push({'quantity':diasCantidad[i], 'hours':diasHoras[i], 'price':diasPrecio[i], 'total': diasTotal[i]});
                    }

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
                            consumablesIds.push($(this).attr('data-consumableid'));
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
                    for (let i = 0; i < materialsTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(materialsTotal[i]);
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
                    for (let i = 0; i < diasTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(diasTotal[i]);
                    }
                    totalEquipment = parseFloat((totalEquipment * quantity)).toFixed(2);

                    $total = parseFloat($total) + parseFloat(totalEquipment);

                    $('#subtotal').html('S/. '+$total);

                    calculateMargen2($('#utility').val());
                    calculateLetter2($('#letter').val());
                    calculateRent2($('#taxes').val());

                    button.next().attr('data-saveEquipment', $equipments.length);
                    button.next().next().attr('data-deleteEquipment', $equipments.length);
                    $equipments.push({'id':$equipments.length, 'quantity':quantity, 'total':totalEquipment, 'description':description, 'detail':detail, 'materials': materialsArray, 'consumables':consumablesArray, 'workforces':manosArray, 'tornos':tornosArray, 'dias':diasArray});

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
    //var result = document.querySelectorAll('[data-equip]');
    //console.log(result);
    /*for (var index in result){
        if (result.hasOwnProperty(index)){
            if(result[index].getAttribute('style')!==null){
                //console.log(result[index].getAttribute('style'));
                $equipmentStatus=true;
            }
        }
    }*/
    //var equipmentStat = confirmEquipment.css('display') === 'none';
    //console.log(confirmEquipment);
    /*if ( !$equipmentStatus )
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
    }*/

    renderTemplateEquipment();

    $('.materialTypeahead').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
            minLength: 1 /* Specify minimum characters required for showing suggestions */
        },
        {
            limit: 12,
            source: substringMatcher($materialsTypeahead)
        });

    /*$('.material_search').select2({
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
*/
    /*for (var i=0; i<$materials.length; i++)
    {
        var newOption = new Option($materials[i].full_description, $materials[i].id, false, false);
        $('.material_search').append(newOption).trigger('change');
    }*/

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
    //$equipmentStatus = false;
}

function deleteItem() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
    var itemId = $(this).data('delete');
    //$items = $items.filter(item => item.id !== itemId);
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
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
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

        //$items.push({ 'id': $items.length+1, 'material': $material, 'material_quantity': material_quantity, 'material_price':total, 'material_length':length, 'material_width':witdh});
        //console.log($renderMaterial);
        renderTemplateMaterial($material.code, $material.full_description, material_quantity, $material.unit_measure.name, $material.unit_price, total, $renderMaterial, length, witdh);

        $('#material_length_entered').val('');
        $('#material_width_entered').val('');
        $('#material_percentage_entered').val('');
        $('#material_price_entered').val('');
        $('#material_quantity_entered').val('');
        $(".material_search").empty().trigger('change');
        $modalAddMaterial.modal('hide');
    } else {
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

        var material_quantity2 = ($("#material_quantity_entered").css('display') === '') ? $("#material_quantity_entered").val(): $("#material_percentage_entered").val();
        var length2 = $('#material_length_entered').val();
        var witdh2 = $('#material_width_entered').val();
        console.log($renderMaterial);
        //$items.push({ 'id': $items.length+1, 'material': $material, 'material_quantity': material_quantity2, 'material_price':0, 'material_length':length2, 'material_width':witdh2});
        renderTemplateMaterial($material.code, $material.full_description, material_quantity2, $material.unit_measure.name, $material.unit_price, 0, $renderMaterial, length2, witdh2);

        $('#material_length_entered').val('');
        $('#material_width_entered').val('');
        $('#material_percentage_entered').val('');
        $('#material_quantity_entered').val('');
        $(".material_search").empty().trigger('change');
        $modalAddMaterial.modal('hide');
    }

}

function addMaterial() {
    var select_material = $(this).parent().parent().children().children().children().next();
    // TODO: Tomar el texto no el val()
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

        $renderMaterial = $(this).parent().parent().next().next().next();

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

        $renderMaterial = $(this).parent().parent().next().next().next();

        $modalAddMaterial.modal('show');
    }


}

function storeQuote() {
    event.preventDefault();
    if( $equipments.length === 0 )
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
    }
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

function renderTemplateMaterial(code, description, quantity, unit, price, total, render, length, width) {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        var clone = activateTemplate('#materials-selected');
        clone.querySelector("[data-materialDescription]").setAttribute('value', description);
        clone.querySelector("[data-materialUnit]").setAttribute('value', unit);
        clone.querySelector("[data-materialLargo]").setAttribute('value', length);
        clone.querySelector("[data-materialAncho]").setAttribute('value', width);
        clone.querySelector("[data-materialQuantity]").setAttribute('value', quantity);
        clone.querySelector("[data-materialPrice]").setAttribute('value', price);
        clone.querySelector("[data-materialTotal]").setAttribute( 'value', (parseFloat(total)).toFixed(2));
        clone.querySelector("[data-delete]").setAttribute('data-delete', code);
        render.append(clone);
    } else {
        var clone2 = activateTemplate('#materials-selected');
        clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
        clone2.querySelector("[data-materialUnit]").setAttribute('value', unit);
        clone2.querySelector("[data-materialLargo]").setAttribute('value', length);
        clone2.querySelector("[data-materialAncho]").setAttribute('value', width);
        clone2.querySelector("[data-materialQuantity]").setAttribute('value', quantity);
        clone2.querySelector("[data-materialPrice]").setAttribute('value', price);
        clone2.querySelector("[data-materialTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(price)).toFixed(2));
        clone2.querySelector("[data-materialPrice]").setAttribute("style","display:none;");
        clone2.querySelector("[data-materialTotal]").setAttribute("style","display:none;");
        clone2.querySelector("[data-delete]").setAttribute('data-delete', code);
        render.append(clone2);
    }
}

function renderTemplateConsumable(render, consumable, quantity) {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        var clone = activateTemplate('#template-consumable');
        clone.querySelector("[data-consumableDescription]").setAttribute('value', consumable.full_description);
        clone.querySelector("[data-consumableId]").setAttribute('data-consumableId', consumable.id);
        clone.querySelector("[data-consumableUnit]").setAttribute('value', consumable.unit_measure.name);
        clone.querySelector("[data-consumableQuantity]").setAttribute('value', quantity);
        clone.querySelector("[data-consumablePrice]").setAttribute('value', consumable.unit_price);
        clone.querySelector("[data-consumableTotal]").setAttribute( 'value', (parseFloat(consumable.unit_price)*parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-deleteConsumable]").setAttribute('data-deleteConsumable', consumable.id);
        render.append(clone);
    } else {
        var clone2 = activateTemplate('#template-consumable');
        clone2.querySelector("[data-consumableDescription]").setAttribute('value', consumable.full_description);
        clone2.querySelector("[data-consumableId]").setAttribute('data-consumableId', consumable.id);
        clone2.querySelector("[data-consumableUnit]").setAttribute('value', consumable.unit_measure.name);
        clone2.querySelector("[data-consumableQuantity]").setAttribute('value', quantity);
        clone2.querySelector("[data-consumablePrice]").setAttribute('value', consumable.unit_price);
        clone2.querySelector("[data-consumableTotal]").setAttribute( 'value', (parseFloat(consumable.unit_price)*parseFloat(quantity)).toFixed(2));
        clone2.querySelector("[data-consumablePrice]").setAttribute("style","display:none;");
        clone2.querySelector("[data-consumableTotal]").setAttribute("style","display:none;");
        clone2.querySelector("[data-deleteConsumable]").setAttribute('data-deleteConsumable', consumable.id);
        render.append(clone2);
    }


}

function renderTemplateMano(render, description, unit, quantity, unitPrice) {
    var clone = activateTemplate('#template-mano');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        clone.querySelector("[data-manoDescription]").setAttribute('value', description);
        clone.querySelector("[data-manoUnit]").setAttribute('value', unit);
        clone.querySelector("[data-manoQuantity]").setAttribute('value', quantity);
        clone.querySelector("[data-manoPrice]").setAttribute('value', unitPrice);
        clone.querySelector("[data-manoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));
    } else {
        clone.querySelector("[data-manoDescription]").setAttribute('value', description);
        clone.querySelector("[data-manoUnit]").setAttribute('value', unit);
        clone.querySelector("[data-manoQuantity]").setAttribute('value', quantity);
        clone.querySelector("[data-manoPrice]").setAttribute('value', unitPrice);
        clone.querySelector("[data-manoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));
        clone.querySelector("[data-manoPrice]").setAttribute("style","display:none;");
        clone.querySelector("[data-manoTotal]").setAttribute("style","display:none;");

    }

    render.append(clone);
}

function renderTemplateTorno(render, description, quantity, unitPrice) {
    var clone = activateTemplate('#template-torno');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        clone.querySelector("[data-tornoDescription]").setAttribute('value', description);
        clone.querySelector("[data-tornoQuantity]").setAttribute('value', quantity);
        clone.querySelector("[data-tornoPrice]").setAttribute('value', unitPrice);
        clone.querySelector("[data-tornoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));
    } else {
        clone.querySelector("[data-tornoDescription]").setAttribute('value', description);
        clone.querySelector("[data-tornoQuantity]").setAttribute('value', quantity);
        clone.querySelector("[data-tornoPrice]").setAttribute('value', unitPrice);
        clone.querySelector("[data-tornoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));
        clone.querySelector("[data-tornoPrice]").setAttribute("style","display:none;");
        clone.querySelector("[data-tornoTotal]").setAttribute("style","display:none;");

    }

    render.append(clone);
}

function renderTemplateDia(render, pricePerHour2, hoursPerPerson2, quantityPerson2, total2) {
    var clone = activateTemplate('#template-dia');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        clone.querySelector("[data-cantidad]").setAttribute('value', quantityPerson2);
        clone.querySelector("[data-horas]").setAttribute('value', hoursPerPerson2);
        clone.querySelector("[data-precio]").setAttribute('value', pricePerHour2);
        clone.querySelector("[data-total]").setAttribute( 'value', total2);
    } else {
        clone.querySelector("[data-cantidad]").setAttribute('value', quantityPerson2);
        clone.querySelector("[data-horas]").setAttribute('value', hoursPerPerson2);
        clone.querySelector("[data-precio]").setAttribute('value', pricePerHour2);
        clone.querySelector("[data-total]").setAttribute( 'value', total2);
        clone.querySelector("[data-precio]").setAttribute("style","display:none;");
        clone.querySelector("[data-total]").setAttribute("style","display:none;");

    }

    render.append(clone);
}

function renderTemplateEquipment() {
    var clone = activateTemplate('#template-equipment');

    $('#body-equipment').append(clone);

    $('.unitMeasure').select2({
        placeholder: "Seleccione unidad",
    });
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}