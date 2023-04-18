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
    $("#btn-submit").on("click", storeImages);
    //$formCreate.on('submit', storeQuote);

    $(document).on('input', '[data-imgold]', function() {
        var card = $(this).parent().parent().parent();
        card.removeClass('card-outline card-primary');
        card.addClass('card-gray-dark');
    });

    $('#addImageInvoice').on('click', addImageInvoice);
    $('#addImageGuide').on('click', addImageGuide);
    $('#addImageObservation').on('click', addImageObservation);

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
    $('#zoom').trigger('zoom.destroy');
    $('#zoom').zoom({
        url: url,
        on:'click',
        magnify: 1
    });

    $modalImage.modal('show');
}

function editImageOld() {
    event.preventDefault();
    var button = $(this);
    var card = $(this).parent().parent().parent();
    var id = $(this).parent().parent().next().children();
    var description = $(this).parent().parent().next().children().next().children().next();

    var image_id = id.val();
    var image_code = description.val();

    $.confirm({
        icon: 'fas fa-save',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'small',
        title: '¿Está seguro de guardar el código de la imagen?',
        content: 'Se va a modificar solo el código.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/modificar/image/ingreso/compra/'+image_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data:{'image_id':image_id, 'image_code':image_code},
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
    var image_code = description.val();

    $.confirm({
        icon: 'fas fa-trash',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        columnClass: 'small',
        title: '¿Está seguro de eliminar la imagen?',
        content: image_code,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/eliminar/image/ingreso/compra/'+image_id,
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

function addImageInvoice() {
    var type = 'i';
    renderTemplateImage(type);

}

function addImageGuide() {
    var type = 'g';
    renderTemplateImage(type);

}

function addImageObservation() {
    var type = 'o';
    renderTemplateImage(type);

}

function renderTemplateImage(type) {
    var clone = activateTemplate('#template-image');
    clone.querySelector("[name='types[]']").setAttribute('value', type);
    if ( type === 'i' )
    {
        $('#body-imagesInvoice').append(clone);
    } else {
        if ( type === 'g' )
        {
            $('#body-imagesGuide').append(clone);
        } else {
            $('#body-imagesObservation').append(clone);
        }
    }

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
    var codes = $(document).find("[name='codeimages[]']").length;
    var images = $("input[type='file'][name='images[]']").filter(function (){
        return this.value
    }).length;
    console.log(codes);
    console.log(images);
    if ( codes != images )
    {
        flag = true;
    }

    return flag;
}

function storeImages() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);

    var images = $("input[type='file'][name='images[]']").filter(function (){
        return this.value
    }).length;

    if ( images == 0 )
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
        toastr.error('No se puede guardar porque faltan imágenes o códigos.', 'Error',
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