let $materials=[];
let $materialsTypeahead=[];
let $consumables=[];
let $items=[];
let $equipments=[];
let $equipmentStatus=false;
let $total=0;
let $totalUtility=0;
let $subtotal=0;
let $subtotal2=0;
let $subtotal3=0;
var $permissions;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $formCreate = $('#formCreate');
    $("#btn-submit").on("click", storeQuote);

    $selectCustomer = $('#customer_id');
    $selectContact = $('#contact_id');

    $selectCustomer.change(function () {
        $selectContact.empty();
        var customer =  $selectCustomer.val();
        $.get( "/dashboard/get/contact/"+customer, function( data ) {
            $selectContact.append($("<option>", {
                value: '',
                text: 'Seleccione contacto'
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectContact.append($("<option>", {
                    value: data[i].id,
                    text: data[i].contact
                }));
            }
        });

    });

    $("#btn-addEquipment").on("click", addEquipment);

    $modalAddEquipment = $("#modalAddEquipment");

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
    });

});

var $formCreate;
var $modalAddEquipment;
var $material;
var $renderMaterial;
var $selectCustomer;
var $selectContact;

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

    $('#subtotal2').html('USD '+$subtotal);
    $('#subtotal3').html('USD '+$subtotal2);
    $('#total').html('USD '+$subtotal3);

}

function calculateLetter(e) {
    var letter = e.value;

    var margen = $('#utility').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);
    $('#subtotal3').html('USD '+$subtotal2);
    $('#total').html('USD '+$subtotal3);

}

function calculateRent(e) {
    var rent = e.value;

    var margen = $('#utility').val();
    var letter = $('#letter').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#total').html('USD '+$subtotal3);

}

function calculateMargen2(margen) {
    var letter = $('#letter').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#subtotal2').html('USD '+$subtotal);
    $('#subtotal3').html('USD '+$subtotal2);
    $('#total').html('USD '+$subtotal3);

}

function calculateLetter2(letter) {
    var margen = $('#utility').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);
    $('#subtotal3').html('USD '+$subtotal2);
    $('#total').html('USD '+$subtotal3);

}

function calculateRent2(rent) {
    var margen = $('#utility').val();
    var letter = $('#letter').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#total').html('USD '+$subtotal3);
}

function calculateTotalC(e) {
    var cantidad = e.value;
    var precio = e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value;
    // CON IGV
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);
    // SIN IGV
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = ((parseFloat(cantidad)*parseFloat(precio))/1.18).toFixed(2);

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

function calculateTotalQuatity(e) {
    var cantidad = e.value;
    var hour = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    var price = e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value;

    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(hour)*parseFloat(price)).toFixed(2);

}

function calculateTotalHour(e) {
    var cantidad = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    var hour = e.value;
    var price = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(hour)*parseFloat(price)).toFixed(2);

}

function calculateTotalPrice(e) {
    var cantidad = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;
    var hour = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    var price = e.value;
    console.log(cantidad);
    console.log(hour);
    console.log(price);
    e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(hour)*parseFloat(price)).toFixed(2);
    console.log(e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value);
}

function addEquipment() {

    $modalAddEquipment.modal('show');
    /*renderTemplateEquipment();*/

}

function deleteItem() {
    //console.log($(this).parent().parent().parent());
    var card = $(this).parent().parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');

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
    if( $('#material_width_entered').val().trim() === '' && $("#quantity_entered_material").css('display') === 'none' && $("#width_entered_material").css('display') !== 'none' )
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

    if ($('#material_length_entered').val().trim() !== '' && $("#width_entered_material").css('display') === 'none' )
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
        renderTemplateMaterial($material.code, $material.full_description, material_quantity, $material.unit_measure.name, $material.unit_price, total, $renderMaterial, length, witdh, $material);

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
        renderTemplateMaterial($material.code, $material.full_description, material_quantity2, $material.unit_measure.name, $material.unit_price, 0, $renderMaterial, length2, witdh2, $material);

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
            case 4:
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

function editedActive() {
    var flag = false;
    $(document).find('[data-equip]').each(function(){
        console.log($(this));
        if ($(this).hasClass('card-gray-dark'))
        {
            flag = true;
        }
    });

    return flag;
}

function imagesIncomplete() {
    var flag = false;
    var descripciones = $(document).find("[name='descplanos[]']").length;
    var planos = $("input[type='file'][name='planos[]']").filter(function (){
        return this.value
    }).length;
    console.log(descripciones);
    console.log(planos);
    if ( descripciones != planos )
    {
        flag = true;
    }

    return flag;
}

function storeQuote() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);
    console.log(imagesIncomplete());
    if ( imagesIncomplete() )
    {
        toastr.error('No se puede guardar porque faltan imagenes o descripciones.', 'Error',
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
        $("#btn-submit").attr("disabled", false);
        return;
    }

    if ( editedActive() )
    {
        toastr.error('No se puede guardar porque hay equipos no confirmados.', 'Error',
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
        $("#btn-submit").attr("disabled", false);
        return;
    }
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
        $("#btn-submit").attr("disabled", false);
        return;
    }
    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var equipos = JSON.stringify($equipments);
    var formulario = $('#formCreate')[0];
    var form = new FormData(formulario);
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

function calculateTotalMaterialQuantity(e) {
    var cantidad = e.value;
    var material_id = e.getAttribute('material_id');
    console.log(material_id);
    var igvRate = 0.18;

    var width = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    var length = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;

    var material = $materials.find( mat=>mat.id === parseInt(material_id) );

    if ( material.type_scrap == null )
    {
        var newPriceConIgv = parseFloat(cantidad*material.unit_price).toFixed(2);

        var newPriceSinIgv = parseFloat(newPriceConIgv / (1 + igvRate)).toFixed(2);

        var newPriceConIgvTotal = parseFloat(material.unit_price).toFixed(2);

        var newPriceSinIgvTotal = parseFloat(newPriceConIgvTotal / (1 + igvRate)).toFixed(2);

        //var priceSinIgv =
        e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotal;
        //var priceConIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotal;
        //var priceSinIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgv ;
        //var priceConIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgv ;

    } else {

        // TODO: Si es tubo
        if (material && material.type_scrap && (material.type_scrap.id === 3 || material.type_scrap.id === 4 || material.type_scrap.id === 5))
        {
            if ( length == null || length == '' )
            {
                // TODO: Solo colocaron cantidad
                var newPriceConIgvT = parseFloat(cantidad*material.unit_price).toFixed(2);

                var newPriceSinIgvT = parseFloat(newPriceConIgvT / (1 + igvRate)).toFixed(2);

                var newPriceConIgvTotalT = parseFloat(material.unit_price).toFixed(2);

                var newPriceSinIgvTotalT = parseFloat(newPriceConIgvTotalT / (1 + igvRate)).toFixed(2);

                //var priceSinIgv =
                e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalT;
                //var priceConIgv =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalT;
                //var priceSinIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvT ;
                //var priceConIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvT ;

            } else {
                // TODO: Solo colocaron largo
                var lengthOriginalMaterial = material.type_scrap.length;
                var newLength = parseFloat(cantidad*lengthOriginalMaterial).toFixed(2);

                // Actualizamos la cantidad automaticamente
                e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value = newLength;

                var newPriceConIgvT2 = parseFloat(cantidad*material.unit_price).toFixed(2);

                var newPriceSinIgvT2 = parseFloat(newPriceConIgvT2 / (1 + igvRate)).toFixed(2);

                var newPriceConIgvTotalT2 = parseFloat(material.unit_price).toFixed(2);

                var newPriceSinIgvTotalT2 = parseFloat(newPriceConIgvTotalT2 / (1 + igvRate)).toFixed(2);

                //var priceSinIgv =
                e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalT2;
                //var priceConIgv =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalT2;
                //var priceSinIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvT2 ;
                //var priceConIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvT2 ;

            }

        } else {

            // TODO: Si es plancha
            if ( length == "" || width == "" )
            {
                // TODO: Solo colocaron cantidad
                var newPriceConIgvP = parseFloat(cantidad*material.unit_price).toFixed(2);

                var newPriceSinIgvP = parseFloat(newPriceConIgvP / (1 + igvRate)).toFixed(2);

                var newPriceConIgvTotalP = parseFloat(material.unit_price).toFixed(2);

                var newPriceSinIgvTotalP = parseFloat(newPriceConIgvTotalP / (1 + igvRate)).toFixed(2);

                //var priceSinIgv =
                e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalP;
                //var priceConIgv =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalP;
                //var priceSinIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvP ;
                //var priceConIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvP ;

            } else {
                // TODO: Colocaron largo y ancho, no se puede asi que seteamos el largo y ancho a 0
                var newLengthP = 0;

                var newWidthP = 0;

                // Actualizamos la cantidad automaticamente

                e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value = newLengthP;
                e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value = newWidthP;

                var newPriceConIgvP2 = parseFloat(cantidad*material.unit_price).toFixed(2);

                var newPriceSinIgvP2 = parseFloat(newPriceConIgvP2 / (1 + igvRate)).toFixed(2);

                var newPriceConIgvTotalP2 = parseFloat(material.unit_price).toFixed(2);

                var newPriceSinIgvTotalP2 = parseFloat(newPriceConIgvTotalP2 / (1 + igvRate)).toFixed(2);

                //var priceSinIgv =
                e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalP2;
                //var priceConIgv =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalP2;
                //var priceSinIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvP2 ;
                //var priceConIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvP2 ;

            }
        }
    }


}

function calculateTotalMaterialLargo(e) {
    var largo = e.value;
    var material_id = e.getAttribute('material_id');
    console.log(material_id);
    var igvRate = 0.18;

    var width = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    //var length = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;

    var material = $materials.find( mat=>mat.id === parseInt(material_id) );

    // TODO: Si es tubo
    if (material && material.type_scrap && (material.type_scrap.id === 3 || material.type_scrap.id === 4 || material.type_scrap.id === 5))
    {

        // TODO: Solo colocaron cantidad
        var lengthOriginalMaterial = material.type_scrap.length;
        var cantidad = parseFloat(largo/lengthOriginalMaterial).toFixed(2);

        var newPriceConIgvT = parseFloat(cantidad*material.unit_price).toFixed(2);

        var newPriceSinIgvT = parseFloat(newPriceConIgvT / (1 + igvRate)).toFixed(2);

        var newPriceConIgvTotalT = parseFloat(material.unit_price).toFixed(2);

        var newPriceSinIgvTotalT = parseFloat(newPriceConIgvTotalT / (1 + igvRate)).toFixed(2);

        //var cantidad =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = cantidad;
        //var priceSinIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalT;
        //var priceConIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalT;
        //var priceSinIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvT ;
        //var priceConIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvT ;

    } else {

        // TODO: Si es plancha falta
        var lengthOriginalMaterialP = material.type_scrap.length;
        var widthOriginalMaterialP = material.type_scrap.width;

        var areaOriginal = lengthOriginalMaterialP*widthOriginalMaterialP;

        var areaNew = largo*width;

        var cantidadP = parseFloat(areaNew/areaOriginal).toFixed(2);

        var newPriceConIgvP = parseFloat(cantidadP*material.unit_price).toFixed(2);

        var newPriceSinIgvP = parseFloat(newPriceConIgvP / (1 + igvRate)).toFixed(2);

        var newPriceConIgvTotalP = parseFloat(material.unit_price).toFixed(2);

        var newPriceSinIgvTotalP = parseFloat(newPriceConIgvTotalP / (1 + igvRate)).toFixed(2);

        //var cantidad =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = cantidadP;
        //var priceSinIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalP;
        //var priceConIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalP;
        //var priceSinIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvP ;
        //var priceConIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvP ;

    }
}

function calculateTotalMaterialAncho(e) {
    var ancho = e.value;
    var material_id = e.getAttribute('material_id');
    console.log(material_id);
    var igvRate = 0.18;

    var length = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    //var length = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;

    var material = $materials.find( mat=>mat.id === parseInt(material_id) );

    // TODO: Si es plancha falta
    var lengthOriginalMaterialP = material.type_scrap.length;
    var widthOriginalMaterialP = material.type_scrap.width;

    var areaOriginal = lengthOriginalMaterialP*widthOriginalMaterialP;

    var areaNew = length*ancho;

    var cantidadP = parseFloat(areaNew/areaOriginal).toFixed(2);

    var newPriceConIgvP = parseFloat(cantidadP*material.unit_price).toFixed(2);

    var newPriceSinIgvP = parseFloat(newPriceConIgvP / (1 + igvRate)).toFixed(2);

    var newPriceConIgvTotalP = parseFloat(material.unit_price).toFixed(2);

    var newPriceSinIgvTotalP = parseFloat(newPriceConIgvTotalP / (1 + igvRate)).toFixed(2);

    //var cantidad =
    e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = cantidadP;
    //var priceSinIgv =
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalP;
    //var priceConIgv =
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalP;
    //var priceSinIgvTotal =
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvP ;
    //var priceConIgvTotal =
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvP ;

}

function renderTemplateMaterial(code, description, quantity, unit, price, total, render, length, width, material) {
    var card = render.parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        var clone = activateTemplate('#materials-selected');
        if ( material.stock_current == 0 )
        {
            clone.querySelector("[data-materialDescription]").setAttribute('value', description);
            clone.querySelector("[data-materialDescription]").setAttribute("style", "color:red;");
        } else {
            clone.querySelector("[data-materialDescription]").setAttribute('value', description);
        }
        clone.querySelector("[data-materialUnit]").setAttribute('value', unit);
        if (material.type_scrap == null)
        {
            clone.querySelector("[data-materialLargo]").setAttribute('value', length);
            clone.querySelector("[data-materialAncho]").setAttribute('value', width);
            clone.querySelector("[data-materialLargo]").setAttribute('readonly', 'readonly');
            clone.querySelector("[data-materialAncho]").setAttribute('readonly', 'readonly');

            clone.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
            clone.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);

        } else {
            if (material && material.type_scrap && (material.type_scrap.id === 3 || material.type_scrap.id === 4 || material.type_scrap.id === 5))
            {
                if ( length == null || length == '' )
                {
                    clone.querySelector("[data-materialLargo]").setAttribute('value', length);
                    clone.querySelector("[data-materialLargo]").setAttribute('readonly', 'readonly');
                } else {
                    clone.querySelector("[data-materialLargo]").setAttribute('value', length);
                }

                clone.querySelector("[data-materialAncho]").setAttribute('readonly', 'readonly');

                clone.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
                clone.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);
            } else {
                if ( length == null || width == null )
                {
                    clone.querySelector("[data-materialLargo]").setAttribute('readonly', 'readonly');
                    clone.querySelector("[data-materialAncho]").setAttribute('readonly', 'readonly');
                } else {
                    clone.querySelector("[data-materialLargo]").setAttribute('value', length);
                    clone.querySelector("[data-materialAncho]").setAttribute('value', width);
                }
                clone.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
                clone.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);
            }
        }

        clone.querySelector("[data-materialQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-materialQuantity]").setAttribute('material_id', material.id);
        clone.querySelector("[data-materialPrice2]").setAttribute('value', (parseFloat(price)/1.18).toFixed(2));
        clone.querySelector("[data-materialPrice]").setAttribute('value', (parseFloat(price)).toFixed(2));
        clone.querySelector("[data-materialTotal2]").setAttribute( 'value', (parseFloat(total)/1.18).toFixed(2));
        clone.querySelector("[data-materialTotal]").setAttribute( 'value', (parseFloat(total)).toFixed(2));
        clone.querySelector("[data-delete]").setAttribute('data-delete', code);
        render.append(clone);
    } else {
        var clone2 = activateTemplate('#materials-selected');
        if ( material.stock_current == 0 )
        {
            clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
            clone2.querySelector("[data-materialDescription]").setAttribute("style", "color:red;");
        } else {
            clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
        }
        //clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
        clone2.querySelector("[data-materialUnit]").setAttribute('value', unit);
        if (material.type_scrap == null)
        {
            clone2.querySelector("[data-materialLargo]").setAttribute('value', length);
            clone2.querySelector("[data-materialAncho]").setAttribute('value', width);
            clone2.querySelector("[data-materialLargo]").setAttribute('readonly', 'readonly');
            clone2.querySelector("[data-materialAncho]").setAttribute('readonly', 'readonly');

            clone2.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
            clone2.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);

        } else {
            clone2.querySelector("[data-materialLargo]").setAttribute('value', length);
            clone2.querySelector("[data-materialAncho]").setAttribute('value', width);

            clone2.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
            clone2.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);
        }
        clone2.querySelector("[data-materialQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone2.querySelector("[data-materialQuantity]").setAttribute('material_id', material.id);
        clone2.querySelector("[data-materialPrice]").setAttribute('value', (parseFloat(price)).toFixed(2));
        clone2.querySelector("[data-materialTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(price)).toFixed(2));
        clone2.querySelector("[data-materialPrice]").setAttribute("style","display:none;");
        clone2.querySelector("[data-materialTotal]").setAttribute("style","display:none;");

        clone2.querySelector("[data-materialPrice2]").setAttribute('value', (parseFloat(price)/1.18).toFixed(2));
        clone2.querySelector("[data-materialTotal2]").setAttribute( 'value', ((parseFloat(quantity)*parseFloat(price))/1.18).toFixed(2));
        clone2.querySelector("[data-materialPrice2]").setAttribute("style","display:none;");
        clone2.querySelector("[data-materialTotal2]").setAttribute("style","display:none;");


        clone2.querySelector("[data-delete]").setAttribute('data-delete', code);
        render.append(clone2);
    }
}

function renderTemplateConsumable(render, consumable, quantity) {
    var card = render.parent().parent().parent().parent();
    card.removeClass('card-sucess');
    card.addClass('card-gray-dark');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        var clone = activateTemplate('#template-consumable');
        //console.log(consumable.stock_current );
        if ( consumable.stock_current == 0 )
        {
            clone.querySelector("[data-consumableDescription]").setAttribute('value', consumable.full_description);
            clone.querySelector("[data-consumableDescription]").setAttribute("style", "color:red;");
        } else {
            clone.querySelector("[data-consumableDescription]").setAttribute('value', consumable.full_description);
        }
        clone.querySelector("[data-consumableId]").setAttribute('data-consumableId', consumable.id);
        clone.querySelector("[data-consumableUnit]").setAttribute('value', consumable.unit_measure.description);
        clone.querySelector("[data-consumableQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-consumablePrice]").setAttribute('value', (parseFloat(consumable.unit_price)).toFixed(2));
        clone.querySelector("[data-consumablePrice2]").setAttribute('value', ( (parseFloat(consumable.unit_price))/1.18 ).toFixed(2));
        clone.querySelector("[data-consumableTotal2]").setAttribute( 'value', ( (parseFloat(consumable.unit_price)*parseFloat(quantity))/1.18 ).toFixed(2));
        clone.querySelector("[data-consumableTotal]").setAttribute( 'value', (parseFloat(consumable.unit_price)*parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-deleteConsumable]").setAttribute('data-deleteConsumable', consumable.id);
        render.append(clone);
    } else {
        var clone2 = activateTemplate('#template-consumable');
        //console.log(consumable.stock_current );
        if ( consumable.stock_current == 0 )
        {
            clone2.querySelector("[data-consumableDescription]").setAttribute('value', consumable.full_description);
            clone2.querySelector("[data-consumableDescription]").setAttribute("style", "color:red;");
        } else {
            clone2.querySelector("[data-consumableDescription]").setAttribute('value', consumable.full_description);
        }
        clone2.querySelector("[data-consumableDescription]").setAttribute('value', consumable.full_description);
        clone2.querySelector("[data-consumableId]").setAttribute('data-consumableId', consumable.id);
        clone2.querySelector("[data-consumableUnit]").setAttribute('value', consumable.unit_measure.description);
        clone2.querySelector("[data-consumableQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone2.querySelector("[data-consumablePrice]").setAttribute('value', (parseFloat(consumable.unit_price)).toFixed(2));
        clone2.querySelector("[data-consumableTotal]").setAttribute( 'value', (parseFloat(consumable.unit_price)*parseFloat(quantity)).toFixed(2));
        clone2.querySelector("[data-consumablePrice]").setAttribute("style","display:none;");
        clone2.querySelector("[data-consumableTotal]").setAttribute("style","display:none;");
        clone2.querySelector("[data-consumablePrice2]").setAttribute('value', ( (parseFloat(consumable.unit_price))/1.18 ).toFixed(2));
        clone2.querySelector("[data-consumableTotal2]").setAttribute( 'value', ( (parseFloat(consumable.unit_price)*parseFloat(quantity))/1.18 ).toFixed(2));
        clone2.querySelector("[data-consumablePrice2]").setAttribute("style","display:none;");
        clone2.querySelector("[data-consumableTotal2]").setAttribute("style","display:none;");
        clone2.querySelector("[data-deleteConsumable]").setAttribute('data-deleteConsumable', consumable.id);
        render.append(clone2);
    }


}

function renderTemplateMano(render, description, unit, quantity, unitPrice) {
    var card = render.parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    var clone = activateTemplate('#template-mano');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        clone.querySelector("[data-manoDescription]").setAttribute('value', description);
        clone.querySelector("[data-manoUnit]").setAttribute('value', unit);
        clone.querySelector("[data-manoQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-manoPrice]").setAttribute('value', (parseFloat(unitPrice)).toFixed(2));
        clone.querySelector("[data-manoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));
    } else {
        clone.querySelector("[data-manoDescription]").setAttribute('value', description);
        clone.querySelector("[data-manoUnit]").setAttribute('value', unit);
        clone.querySelector("[data-manoQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-manoPrice]").setAttribute('value', (parseFloat(unitPrice)).toFixed(2));
        clone.querySelector("[data-manoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));
        clone.querySelector("[data-manoPrice]").setAttribute("style","display:none;");
        clone.querySelector("[data-manoTotal]").setAttribute("style","display:none;");

    }

    render.append(clone);
}

function renderTemplateTorno(render, description, quantity, unitPrice) {
    var card = render.parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    var clone = activateTemplate('#template-torno');

    clone.querySelector("[data-tornoDescription]").setAttribute('value', description);
    clone.querySelector("[data-tornoQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
    clone.querySelector("[data-tornoPrice]").setAttribute('value', (parseFloat(unitPrice)).toFixed(2));
    clone.querySelector("[data-tornoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));

    render.append(clone);
}

function renderTemplateDia(render, description, pricePerHour2, hoursPerPerson2, quantityPerson2, total2) {
    var card = render.parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    var clone = activateTemplate('#template-dia');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        clone.querySelector("[data-description]").setAttribute('value', description);
        clone.querySelector("[data-cantidad]").setAttribute('value', (parseFloat(quantityPerson2)).toFixed(2));
        clone.querySelector("[data-horas]").setAttribute('value', (parseFloat(hoursPerPerson2)).toFixed(2));
        clone.querySelector("[data-precio]").setAttribute('value', (parseFloat(pricePerHour2)).toFixed(2));
        clone.querySelector("[data-total]").setAttribute( 'value', (parseFloat(total2)).toFixed(2));
    } else {
        clone.querySelector("[data-description]").setAttribute('value', description);
        clone.querySelector("[data-cantidad]").setAttribute('value', (parseFloat(quantityPerson2)).toFixed(2));
        clone.querySelector("[data-horas]").setAttribute('value', (parseFloat(hoursPerPerson2)).toFixed(2));
        clone.querySelector("[data-precio]").setAttribute('value', (parseFloat(pricePerHour2)).toFixed(2));
        clone.querySelector("[data-total]").setAttribute( 'value', (parseFloat(total2)).toFixed(2));
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