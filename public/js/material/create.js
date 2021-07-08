$(document).ready(function () {
    $formCreate = $('#formCreate');
    $formCreate.on('submit', storeMaterial);
    
    $('#btn-add').on('click', showTemplateSpecification);

    $(document).on('click', '[data-delete]', deleteSpecification);

    $select = $('#exampler');
    $('#brand').change(function () {
        $select.empty();
        var brand =  $('#brand').val();
        $.get( "/dashboard/get/exampler/"+brand, function( data ) {
            $select.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $select.append($("<option>", {
                    value: data[i].id,
                    text: data[i].exampler
                }));
            }
        });

    });

    $('#feature').change(function () {
        let feature =  $('#feature').val();
        switch(feature) {
            case "2":
                //alert('Metalico');
                $('#feature-body').css("display","");
                break;
            case "1":
                $('#feature-body').css("display","none");
                $('#type').val('1');
                $('#type').trigger('change');
                $('#material').val('1');
                $('#material').trigger('change');
                $('#cedula').val('1');
                $('#cedula').trigger('change');
                $('#quality').val('1');
                $('#quality').trigger('change');
                generateNameProduct();
                break;
        }
    });

    $select.select2({
        placeholder: "Selecione un modelo",
    });
    
    $('#btn-generate').on('click', generateNameProduct);

});

var $formCreate;
var $select;

function generateNameProduct() {
    if( $('#description').val().trim() === '' )
    {
        toastr.error('Debe escribir una descripción', 'Error',
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

    if( $('#measure').val().trim() === '' )
    {
        toastr.error('Debe escribir una medida', 'Error',
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

    $('#name').val('');

    let type = ($('#type option:selected').text() === 'Ninguno') ? '': ' '+$('#type option:selected').text();
    let material = ($('#material option:selected').text() === 'Ninguno') ? '': ' '+$('#material option:selected').text();
    let cedula = ($('#cedula option:selected').text() === 'Ninguno') ? '': ' '+$('#cedula option:selected').text();
    let quality = ($('#quality option:selected').text() === 'Ninguno') ? '': ' '+$('#quality option:selected').text();
    let measure = ' ' + $('#measure').val();

    let name = $('#description').val() + type + material + cedula + quality + measure;
    $('#name').val(name);

}

function showTemplateSpecification() {
    var specification = $('#specification').val();
    var content = $('#content').val();

    $('#specification').val('');
    $('#content').val('');

    renderTemplateItem(specification, content);
}

function deleteSpecification() {
    //console.log($(this).parent().parent().parent());
    $(this).parent().parent().remove();
}

function storeMaterial() {
    event.preventDefault();
    // Obtener la URL
    var createUrl = $formCreate.data('url');
    $.ajax({
        url: createUrl,
        method: 'POST',
        data: new FormData(this),
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
                    "timeOut": "4000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
            setTimeout( function () {
                location.reload();
            }, 4000 )
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
                        "timeOut": "4000",
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

function renderTemplateItem(specification, content) {
    var clone = activateTemplate('#template-specification');
    clone.querySelector("[data-name]").setAttribute('value', specification);
    clone.querySelector("[data-content]").setAttribute('value', content);
    $('#body-specifications').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}