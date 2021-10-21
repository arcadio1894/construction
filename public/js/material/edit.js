$(document).ready(function () {

    $formEdit = $('#formEdit');
    $formEdit.on('submit', updateMaterial);

    getExampler();
    getSubcategory();

    $('#btn-add').on('click', showTemplateSpecification);

    $(document).on('click', '[data-delete]', deleteSpecification);

    $selectExample = $('#exampler');

    $selectCategory = $('#category');

    $selectSubCategory = $('#subcategory');

    $selectBrand = $('#brand');

    $selectType = $('#type');

    $selectSubtype = $('#subtype');

    $selectCategory.change(function () {
        $selectSubCategory.empty();
        $('#feature-body').css("display","none");
        $selectType.val('0');
        $selectType.trigger('change');
        $selectSubtype.val('0');
        $selectSubtype.trigger('change');
        $('#warrant').val('0');
        $('#warrant').trigger('change');
        $('#quality').val('0');
        $('#quality').trigger('change');
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
        $selectExample.empty();
        var brand =  $selectBrand.val();
        if ( brand !== '' || brand !== null )
        {
            $.get( "/dashboard/get/exampler/"+brand, function( data ) {
                $selectExample.append($("<option>", {
                    value: '',
                    text: 'Ninguna'
                }));
                for ( var i=0; i<data.length; i++ )
                {
                    $selectExample.append($("<option>", {
                        value: data[i].id,
                        text: data[i].exampler
                    }));
                }
            });
        }


    });

    $selectSubCategory.change(function () {
        let subcategory = $selectSubCategory.select2('data');
        let option = $selectSubCategory.find(':selected');

        console.log(subcategory[0].id);
        if(subcategory[0].text === 'INOX') {
            $selectType.empty();
            var subcategoria =  subcategory[0].id;
            $.get( "/dashboard/get/types/"+subcategoria, function( data ) {
                $selectType.append($("<option>", {
                    value: '',
                    text: 'Ninguno'
                }));
                var type_id = $('#type_id').val();
                for ( var i=0; i<data.length; i++ )
                {
                    /*$selectType.append($("<option>", {
                        value: data[i].id,
                        text: data[i].type
                    }));*/
                    if (data[i].id === parseInt(type_id)) {
                        var newOption = new Option(data[i].type, data[i].id, false, true);
                        // Append it to the select
                        $selectType.append(newOption).trigger('change');

                    } else {
                        var newOption2 = new Option(data[i].type, data[i].id, false, false);
                        // Append it to the select
                        $selectType.append(newOption2);
                    }
                }
            });
            $('#feature-body').css("display","");
        } else {
            console.log(subcategory[0].text);
            $('#feature-body').css("display","none");
            $selectType.val('0');
            $selectType.trigger('change');
            $selectSubtype.val('0');
            $selectSubtype.trigger('change');
            $('#warrant').val('0');
            $('#warrant').trigger('change');
            $('#quality').val('0');
            $('#quality').trigger('change');
            $selectSubCategory.select2('close');
        }
        //alert(subcategory[0].text);
        /*switch(subcategory[0].text) {
            case "INOX":
                //alert('Metalico');
                $selectType.empty();
                var subcategoria =  subcategory[0].id;
                $.get( "/dashboard/get/types/"+subcategoria, function( data ) {
                    $selectType.append($("<option>", {
                        value: '',
                        text: 'Ninguno'
                    }));
                    var type =  $('#type_id').val();
                    for ( var i=0; i<data.length; i++ )
                    {
                        if ( data[i].id === parseInt(type) )
                        {
                            var newOption = new Option(data[i].type, data[i].id, false, true);
                            // Append it to the select
                            $selectType.append(newOption).trigger('change');

                        } else {
                            var newOption2 = new Option(data[i].type, data[i].id, false, false);
                            // Append it to the select
                            $selectType.append(newOption2);
                        }
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
        }*/
    });

    $selectType.change(function () {
        $selectSubtype.empty();
        let type = $selectType.select2('data');
        if( type.length !== 0) {
            $.get("/dashboard/get/subtypes/" + type[0].id, function (data) {
                $selectSubtype.append($("<option>", {
                    value: '',
                    text: 'Ninguno'
                }));
                var subtype = $('#subtype_id').val();
                for (var i = 0; i < data.length; i++) {
                    /*$selectSubtype.append($("<option>", {
                        value: data[i].id,
                        text: data[i].subtype
                    }));*/

                    if (data[i].id === parseInt(subtype)) {
                        var newOption = new Option(data[i].subtype, data[i].id, false, true);
                        // Append it to the select
                        $selectSubtype.append(newOption).trigger('change');

                    } else {
                        var newOption2 = new Option(data[i].subtype, data[i].id, false, false);
                        // Append it to the select
                        $selectSubtype.append(newOption2);
                    }
                }
            });
        }
    });

    //generateNameProduct();

    $selectExample.select2({
        placeholder: "Selecione un modelo",
    });
    $('#btn-generate').on('click', generateNameProduct);

});

var $formEdit;
var $selectCategory;
var $selectSubCategory;
var $selectBrand;
var $selectExample;
var $selectType;
var $selectSubtype;

function mayus(e) {
    e.value = e.value.toUpperCase();
}

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

    $('#name').val('');

    let type = ($('#type option:selected').text() === 'Ninguno') ? '': ' '+$('#type option:selected').text();
    let subtype = ($('#subtype option:selected').text() === 'Ninguno') ? '': ' '+$('#subtype option:selected').text();
    let warrant = ($('#warrant option:selected').text() === 'Ninguno') ? '': ' '+$('#warrant option:selected').text();
    let quality = ($('#quality option:selected').text() === 'Ninguno') ? '': ' '+$('#quality option:selected').text();
    let measure = ' ' + $('#measure').val();

    let name = $('#description').val() + type + subtype + warrant + quality + measure;
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

function getSubcategory() {
    var category =  $('#category_id').val();
    $.get( "/dashboard/get/subcategories/"+category, function( data ) {
        $selectSubCategory.append($("<option>", {
            value: '',
            text: ''
        }));
        for ( var i=0; i<data.length; i++ )
        {
            if ( data[i].id === parseInt($('#subcategory_id').val()) )
            {
                var newOption = new Option(data[i].subcategory, data[i].id, false, true);
                // Append it to the select
                $selectSubCategory.append(newOption).trigger('change');

            } else {
                var newOption2 = new Option(data[i].subcategory, data[i].id, false, false);
                // Append it to the select
                $selectSubCategory.append(newOption2);
            }

        }
    });
}

function getExampler() {
    //$select.empty();
    var brand =  $('#brand_id').val();
    //alert(brand);
    if ( typeof brand !== 'undefined' )
    {
        //alert(brand);
        $.get( "/dashboard/get/examplers/"+brand, function( data ) {
            $selectExample.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                if ( data[i].id === parseInt($('#exampler_id').val()) )
                {
                    var newOption = new Option(data[i].exampler, data[i].id, false, true);
                    // Append it to the select
                    $selectExample.append(newOption).trigger('change');

                } else {
                    var newOption2 = new Option(data[i].exampler, data[i].id, false, false);
                    // Append it to the select
                    $selectExample.append(newOption2).trigger('change');
                }

            }
        });
    }

}

function updateMaterial() {
    event.preventDefault();
    // Obtener la URL
    var editUrl = $formEdit.data('url');
    $.ajax({
        url: editUrl,
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
                //location.reload();
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