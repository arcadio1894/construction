$(document).ready(function () {
    $formCreate = $('#formCreate');
    $formCreate.on('submit', storeMaterial);
    
    $('#btn-add').on('click', showTemplateSpecification);

    $(document).on('click', '[data-delete]', deleteSpecification);

    $selectCategory = $('#category');

    $selectSubCategory = $('#subcategory');

    $selectBrand = $('#brand');

    $selectExampler = $('#exampler');

    $selectType = $('#type');

    $selectSubtype = $('#subtype');

    $selectCategory.change(function () {
        $selectSubCategory.empty();
        var category =  $selectCategory.val();
        $.get( "/dashboard/get/subcategories/"+category, function( data ) {
            $selectSubCategory.append($("<option>", {
                value: '',
                text: 'Ninguna'
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectSubCategory.append($("<option>", {
                    value: data[i].id,
                    text: data[i].subcategory
                }));
            }
        });

    });

    $selectBrand.change(function () {
        $selectExampler.empty();
        var brand =  $selectBrand.val();
        $.get( "/dashboard/get/exampler/"+brand, function( data ) {
            $selectExampler.append($("<option>", {
                value: '',
                text: 'Ninguna'
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectExampler.append($("<option>", {
                    value: data[i].id,
                    text: data[i].exampler
                }));
            }
        });

    });

    $selectSubCategory.change(function () {
        let subcategory = $selectSubCategory.select2('data');
        //alert(subcategory[0].text);
        switch(subcategory[0].text) {
            case "INOX":
                //alert('Metalico');
                $selectType.empty();
                var subcategoria =  subcategory[0].id;
                $.get( "/dashboard/get/types/"+subcategoria, function( data ) {
                    $selectType.append($("<option>", {
                        value: '',
                        text: 'Ninguno'
                    }));
                    for ( var i=0; i<data.length; i++ )
                    {
                        $selectType.append($("<option>", {
                            value: data[i].id,
                            text: data[i].type
                        }));
                    }
                });
                $('#feature-body').css("display","");

                break;
            default :
                $('#feature-body').css("display","none");
                $selectType.val('0');
                $selectType.trigger('change');
                $selectSubtype.val('0');
                $selectSubtype.trigger('change');
                $('#warrant').val('0');
                $('#warrant').trigger('change');
                $('#quality').val('0');
                $('#quality').trigger('change');
                generateNameProduct();
                break;
        }
    });

    $selectType.change(function () {
        $selectSubtype.empty();
        let type = $selectType.select2('data');

        $.get( "/dashboard/get/subtypes/"+type[0].id, function( data ) {
            $selectSubtype.append($("<option>", {
                value: '',
                text: 'Ninguno'
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectSubtype.append($("<option>", {
                    value: data[i].id,
                    text: data[i].subtype
                }));
            }
        });

    });

    $selectExampler.select2({
        placeholder: "Selecione un modelo",
    });
    
    $('#btn-generate').on('click', generateNameProduct);

});

var $formCreate;
var $select;
var $selectCategory;
var $selectSubCategory;
var $selectBrand;
var $selectExampler;
var $selectType;
var $selectSubtype;

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

    let subcategory = ($('#subcategory option:selected').text() === 'Ninguno') ? '': ' '+$('#subcategory option:selected').text();
    let type = ($('#type option:selected').text() === 'Ninguno') ? '': ' '+$('#type option:selected').text();
    let subtype = ($('#subtype option:selected').text() === 'Ninguno') ? '': ' '+$('#subtype option:selected').text();
    let warrant = ($('#warrant option:selected').text() === 'Ninguno') ? '': ' '+$('#warrant option:selected').text();
    let quality = ($('#quality option:selected').text() === 'Ninguno') ? '': ' '+$('#quality option:selected').text();
    let measure = ' ' + $('#measure').val();

    let name = $('#description').val() + subcategory + type + subtype + warrant + quality + measure;
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