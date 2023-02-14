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

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $formCreate = $('#formEdit');
    $("#btn-submit").on("click", storeQuote);
    //$formCreate.on('submit', storeQuote);

    $(document).on('input', '[data-imgold]', function() {
        var card = $(this).parent().parent().parent();
        card.removeClass('card-outline card-primary');
        card.addClass('card-gray-dark');
    });

    $(document).on('input', '[data-order]', function() {
        var card = $(this).parent().parent().parent();
        card.removeClass('card-outline card-primary');
        card.addClass('card-gray-dark');
    });

    $(document).on('input', '[data-width]', function() {
        var card = $(this).parent().parent().parent().parent();
        card.removeClass('card-outline card-primary');
        card.addClass('card-gray-dark');
    });

    $(document).on('input', '[data-height]', function() {
        var card = $(this).parent().parent().parent().parent();
        card.removeClass('card-outline card-primary');
        card.addClass('card-gray-dark');
    });

    $('#addImage').on('click', addImage);
    $(document).on('click', '[data-imagedelete]', imageDelete);

    $(document).on('click', '[data-imageeditold]', editImageOld);
    $(document).on('click', '[data-imagedeleteold]', deleteImageOld);

    $modalImage = $('#modalImage');
    $(document).on('click', '[data-image]', showImagePreview);
});

var $formCreate;
var $modalAddMaterial;

var $modalImage;

function showImagePreview() {
    var url = $(this).attr('data-image');
    $('#imagePreview').attr('src', url);
    $('#zoom').zoom({
        on:'click',
        magnify: 0.35
    });

    $modalImage.modal('show');
}

function editImageOld() {
    event.preventDefault();
    var button = $(this);
    var card = $(this).parent().parent().parent();
    var id = $(this).parent().parent().next().children();
    var description = $(this).parent().parent().next().children().next().children().next();
    var order = $(this).parent().parent().next().children().next().next().children().next();
    var height = $(this).parent().parent().next().children().next().next().next().children().children().next();
    var width = $(this).parent().parent().next().children().next().next().next().children().next().children().next();
    var image_id = id.val();
    var image_description = description.val();
    var image_order = order.val();
    var image_height = height.val();
    var image_width = width.val();

    $.confirm({
        icon: 'fas fa-save',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'small',
        title: '¿Está seguro de guardar la descripción y el orden de la imagen?',
        content: 'Se va a modificar solo la descripción y el orden.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/modificar/planos/cotizacion/'+image_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:{'image_id':image_id, 'image_description':image_description, 'image_order':image_order, 'image_height':image_height, 'image_width':image_width},
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            button.tooltip('hide');
                            card.removeClass('card-gray-dark');
                            card.addClass('card-outline card-primary');

                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                    //$.alert('Your name is ' + name);
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Se canceló el proceso.");
                },
            },
        }
    });
}

function deleteImageOld() {
    event.preventDefault();
    var button = $(this);
    var card = $(this).parent().parent().parent();
    var id = $(this).parent().parent().next().children();
    var description = $(this).parent().parent().next().children().next().children().next();
    var image_id = id.val();
    var image_description = description.val();

    $.confirm({
        icon: 'fas fa-trash',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        columnClass: 'small',
        title: '¿Está seguro de eliminar la imagen?',
        content: image_description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/eliminar/planos/cotizacion/'+image_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:{'image_id':image_id},
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            button.tooltip('hide');
                            card.parent().remove();
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                    //$.alert('Your name is ' + name);
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Se canceló el proceso.");
                },
            },
        }
    });
}

function imageDelete() {
    console.log('click');
    var element = $(this).parent().parent().parent().parent();
    $(this).tooltip('hide');
    element.remove();
}

function addImage() {

    var order = $(document).find("[data-img]").length +1;

    renderTemplateImage(order);

}

function renderTemplateImage(order) {
    var clone = activateTemplate('#template-image');
    clone.querySelector("[name='orderplanos[]']").setAttribute('value', order);
    $('#body-images').append(clone);
}

function previewFile(input) {
    var preview = input.parentElement.parentElement.nextElementSibling;
    var file = input.files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        preview.src = reader.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
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

    var planos = $("input[type='file'][name='planos[]']").filter(function (){
        return this.value
    }).length;

    if ( planos == 0 )
    {
        toastr.error('No se puede guardar porque no hay nuevas imagenes.', 'Error',
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

    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var formulario = $('#formEdit')[0];
    var form = new FormData(formulario);
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

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}