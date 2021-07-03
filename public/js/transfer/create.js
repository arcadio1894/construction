$(document).ready(function () {
    $formCreate = $('#formCreate');
    $formCreate.on('submit', storeMaterial);
    
    $('#btn-add').on('click', showTemplateSpecification);

    $(document).on('click', '[data-delete]', deleteSpecification);

    $selectWarehouse = $('#warehouse');
    $('#area').change(function () {
        $selectWarehouse.empty();
        $selectShelf.empty();
        $selectLevel.empty();
        $selectContainer.empty();
        $selectPosition.empty();
        var area =  $('#area').val();
        $.get( "/dashboard/get/warehouse/area/"+area, function( data ) {
            $selectWarehouse.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectWarehouse.append($("<option>", {
                    value: data[i].id,
                    text: data[i].warehouse
                }));
            }
        });
        $selectWarehouse.select2({
            placeholder: "Selecione un almacén",
        });
    });

    $selectShelf = $('#shelf');
    $selectWarehouse.change(function () {
        $selectShelf.empty();
        $selectLevel.empty();
        $selectContainer.empty();
        $selectPosition.empty();

        var warehouse =  $selectWarehouse.val();
        $.get( "/dashboard/get/shelf/warehouse/"+warehouse, function( data ) {
            $selectShelf.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectShelf.append($("<option>", {
                    value: data[i].id,
                    text: data[i].shelf
                }));
            }
        });

    });

    $selectLevel = $('#level');
    $selectShelf.change(function () {
        $selectLevel.empty();
        $selectContainer.empty();
        $selectPosition.empty();

        var shelf =  $selectShelf.val();
        $.get( "/dashboard/get/level/shelf/"+shelf, function( data ) {
            $selectLevel.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectLevel.append($("<option>", {
                    value: data[i].id,
                    text: data[i].level
                }));
            }
        });

    });

    $selectContainer = $('#container');
    $selectLevel.change(function () {
        $selectContainer.empty();
        $selectPosition.empty();
        
        var level =  $selectLevel.val();
        $.get( "/dashboard/get/container/level/"+level, function( data ) {
            $selectContainer.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectContainer.append($("<option>", {
                    value: data[i].id,
                    text: data[i].container
                }));
            }
        });

    });

    $selectPosition = $('#position');
    $selectContainer.change(function () {
        $selectPosition.empty();
        var container =  $selectContainer.val();
        $.get( "/dashboard/get/position/container/"+container, function( data ) {
            $selectPosition.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectPosition.append($("<option>", {
                    value: data[i].id,
                    text: data[i].position
                }));
            }
        });

    });
});

var $formCreate;
var $selectWarehouse;
var $selectShelf;
var $selectLevel;
var $selectContainer;
var $selectPosition;

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