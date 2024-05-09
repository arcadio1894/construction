let $entriesComplete=[];
let $entriesJson=[];
let $materialsComplete=[];
let $locationsComplete=[];
let $items=[];

$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $.ajax({
        url: "/dashboard/get/locations",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                console.log(json[i].id);
                $('.location').append($("<option>", {
                    value: json[i].id,
                    text: json[i].location
                }));
            }

        }
    });

    var material_id = $('#material_id').val();

    var table = $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/get/json/index/items/material/"+material_id,
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: null,
                title: 'Código',
                wrap: true,
                "render": function (item)
                {
                    return item.code;
                }
            },
            { data: null,
                title: 'Material',
                wrap: true,
                "render": function (item)
                {
                    return item.material.full_description;
                }
            },
            { data: null,
                title: 'Largo (mm)',
                wrap: true,
                "render": function (item)
                {
                    return item.length;
                }
            },
            { data: null,
                title: 'Ancho (mm)',
                wrap: true,
                "render": function (item)
                {
                    return item.width;
                }
            },
            { data: null,
                title: 'Precio Unit.',
                wrap: true,
                "render": function (item)
                {
                    if (item.detail_entry.entry.currency_invoice != null)
                    {
                        if ( item.detail_entry.entry.currency_invoice == 'USD' )
                        {
                            return 'USD ' + item.price;
                        } else {
                            return ((item.state_item == 'scraped') ? 'USD ':'PEN ') + item.price;
                        }
                    } else {
                        return 'USD ' + item.price;
                    }

                }
            },
            { data: null,
                title: 'Porcentaje',
                wrap: true,
                "render": function (item)
                {
                    return item.percentage;
                }
            },
            { data: null,
                title: 'Estado',
                wrap: true,
                "render": function (item)
                {
                    return (item.state_item === 'reserved') ? '<span class="badge bg-info">Reservado</span>':
                        (item.state_item === 'exited') ? '<span class="badge bg-danger">Salido</span>':
                            (item.state_item === 'entered') ? '<span class="badge bg-success">Ingresado</span>':'<span class="badge bg-warning">Retazo</span>';

                }
            },
            { data: null,
                title: 'Fecha de creación',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.created_at).tz('America/Lima').format('DD/MM/YYYY hh:mm A') +'</p>';

                }
            },
            { data: null,
                title: 'Acciones',
                wrap: true,
                "render": function (item)
                {
                    var text = '';
                    text = text + '<button data-typescrap="'+item.material.typescrap_id+'" data-idItem="'+item.id+'" data-widthItem="'+item.width+'" data-lengthItem="'+item.length+'" data-codeItem="'+item.code+'" data-priceBD="'+item.material.unit_price+'" data-materialId="'+item.material.id+'" data-material="'+item.material.full_description+'" data-toggle="tooltip" data-placement="top" title="Crear retazo" data-scrap="'+item.id+'" class="btn btn-outline-primary btn-sm"><i class="fas fa-search-plus"></i> </button>';
                        /*'<button data-toggle="tooltip" data-placement="top" title="Confirmar" data-confirm="'+item.id+'" class="btn btn-outline-success btn-sm"><i class="fa fa-check-square"></i> </button> ';*/
                    return text;
                }
            },

        ],
        "aaSorting": [],

        select: {
            style: 'single'
        },
        language: {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "Ningún dato disponible en esta tabla",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "search": "Buscar:",
            "infoThousands": ",",
            "loadingRecords": "Cargando...",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "aria": {
                "sortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad",
                "collection": "Colección",
                "colvisRestore": "Restaurar visibilidad",
                "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                "copySuccess": {
                    "1": "Copiada 1 fila al portapapeles",
                    "_": "Copiadas %d fila al portapapeles"
                },
                "copyTitle": "Copiar al portapapeles",
                "csv": "CSV",
                "excel": "Excel",
                "pageLength": {
                    "-1": "Mostrar todas las filas",
                    "1": "Mostrar 1 fila",
                    "_": "Mostrar %d filas"
                },
                "pdf": "PDF",
                "print": "Imprimir"
            },
            "autoFill": {
                "cancel": "Cancelar",
                "fill": "Rellene todas las celdas con <i>%d<\/i>",
                "fillHorizontal": "Rellenar celdas horizontalmente",
                "fillVertical": "Rellenar celdas verticalmentemente"
            },
            "decimal": ",",
            "searchBuilder": {
                "add": "Añadir condición",
                "button": {
                    "0": "Constructor de búsqueda",
                    "_": "Constructor de búsqueda (%d)"
                },
                "clearAll": "Borrar todo",
                "condition": "Condición",
                "conditions": {
                    "date": {
                        "after": "Despues",
                        "before": "Antes",
                        "between": "Entre",
                        "empty": "Vacío",
                        "equals": "Igual a",
                        "not": "No",
                        "notBetween": "No entre",
                        "notEmpty": "No Vacio"
                    },
                    "number": {
                        "between": "Entre",
                        "empty": "Vacio",
                        "equals": "Igual a",
                        "gt": "Mayor a",
                        "gte": "Mayor o igual a",
                        "lt": "Menor que",
                        "lte": "Menor o igual que",
                        "not": "No",
                        "notBetween": "No entre",
                        "notEmpty": "No vacío"
                    },
                    "string": {
                        "contains": "Contiene",
                        "empty": "Vacío",
                        "endsWith": "Termina en",
                        "equals": "Igual a",
                        "not": "No",
                        "notEmpty": "No Vacio",
                        "startsWith": "Empieza con"
                    }
                },
                "data": "Data",
                "deleteTitle": "Eliminar regla de filtrado",
                "leftTitle": "Criterios anulados",
                "logicAnd": "Y",
                "logicOr": "O",
                "rightTitle": "Criterios de sangría",
                "title": {
                    "0": "Constructor de búsqueda",
                    "_": "Constructor de búsqueda (%d)"
                },
                "value": "Valor"
            },
            "searchPanes": {
                "clearMessage": "Borrar todo",
                "collapse": {
                    "0": "Paneles de búsqueda",
                    "_": "Paneles de búsqueda (%d)"
                },
                "count": "{total}",
                "countFiltered": "{shown} ({total})",
                "emptyPanes": "Sin paneles de búsqueda",
                "loadMessage": "Cargando paneles de búsqueda",
                "title": "Filtros Activos - %d"
            },
            "select": {
                "1": "%d fila seleccionada",
                "_": "%d filas seleccionadas",
                "cells": {
                    "1": "1 celda seleccionada",
                    "_": "$d celdas seleccionadas"
                },
                "columns": {
                    "1": "1 columna seleccionada",
                    "_": "%d columnas seleccionadas"
                }
            },
            "thousands": ".",
            "datetime": {
                "previous": "Anterior",
                "next": "Proximo",
                "hours": "Horas"
            }
        },

    } );
    // Array to track the ids of the details displayed rows
    var detailRows = [];

    /*$('#dynamic-table tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
        var idx = $.inArray( tr.attr('id'), detailRows );

        if ( row.child.isShown() ) {
            tr.removeClass( 'details' );
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice( idx, 1 );
        }
        else {
            tr.addClass( 'details' );
            row.child( format( row.data() ) ).show();

            // Add to the 'open' array
            if ( idx === -1 ) {
                detailRows.push( tr.attr('id') );
            }
        }
    } );
*/
    // On each draw, loop over the `detailRows` array and show any child rows
    /*table.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );
    } );*/

    /*$(document).on('click', '[data-column]', function (e) {
        //e.preventDefault();

        // Get the column API object
        var column = table.column( $(this).attr('data-column') );

        // Toggle the visibility
        column.visible( ! column.visible() );
    } );*/

    /*$.ajax({
        url: "/dashboard/get/json/entries/purchase",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                $entriesComplete.push(json[i]);
            }

        }
    });*/
    /*$.ajax({
        url: "/dashboard/get/entries/purchase",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.length; i++)
            {
                $entriesComplete.push(json[i]);
            }

        }
    });*/

    $modalCreateScrap = $('#modalCreateScrap');
    $formScrap = $('#formScrap');
    $(document).on('click', '[data-scrap]', showModalCreateScrap);
    $('#btn-submit').on('click', saveScrap);

    $btnBlockLength = $('#btb-block-length');
    $btnBlockWidth = $('#btb-block-width');

    $btnBlockLength.on('click', blockLength);
    $btnBlockWidth.on('click', blockWidth);

    $modalCreateNewScrap = $('#modalCreateNewScrap');
    $formNewScrap = $('#formNewScrap');
    $('#btn-newscrap').on('click', showModalNewScrap);
    $('#btn-submit-new').on('click', saveNewScrap);

});

var $btnBlockLength;
var $btnBlockWidth;
let $blobkLength = 0;
let $blobkWidth = 0;

let $formScrap;

let $formNewScrap;

let $modalCreateScrap;

let $modalCreateNewScrap;

let $caracteres = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

let $longitud = 20;

function saveNewScrap() {
    $("#btn-submit-new").attr("disabled", true);

    var typescrap_nuevo = $('#typescrap_nuevo');

    if ( typescrap_nuevo == 1 || typescrap_nuevo == 2 || typescrap_nuevo == 6 )
    {
        if( $('#length_new_nuevo').val().trim() === '' || $('#length_new_nuevo').val()<0 )
        {
            toastr.error('Debe ingresar una longitud', 'Error',
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
            $("#btn-submit-new").attr("disabled", true);
            return;
        }

        if( $('#width_new_nuevo').val().trim() === '' || $('#width_new_nuevo').val()<0 )
        {
            toastr.error('Debe ingresar un ancho', 'Error',
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
            $("#btn-submit-new").attr("disabled", true);
            return;
        }
    }

    // TODO: Agregamos tubos pequeños
    if ( typescrap_nuevo == 3 || typescrap_nuevo == 4 || typescrap_nuevo == 5 )
    {
        if( $('#length_new_nuevo').val().trim() === '' || $('#length_new_nuevo').val()<0 )
        {
            toastr.error('Debe ingresar una longitud', 'Error',
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
            $("#btn-submit-new").attr("disabled", true);
            return;
        }
    }

    var createUrl = $formNewScrap.data('url');
    var formulario = $('#formNewScrap')[0];
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
            $modalCreateNewScrap.modal('hide');
            setTimeout( function () {
                $("#btn-submit-new").attr("disabled", false);

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

            $("#btn-submit-new").attr("disabled", false);
        },
    });
}

function showModalNewScrap() {
    var material_id = $('#material_id').val();
    //console.log(material_id);
    $.ajax({
        url: "/dashboard/get/data/material/scrap/"+material_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            //console.log(json); type_scrap
            $modalCreateNewScrap.find('[id=material_id_nuevo]').val(json.id);
            $modalCreateNewScrap.find('[id=material_nuevo]').val(json.full_description);
            $modalCreateNewScrap.find('[id=price_nuevo]').val(json.unit_price);
            $modalCreateNewScrap.find('[id=typescrap_nuevo]').val(json.type_scrap.id);
            $modalCreateNewScrap.find('[id=code_nuevo]').val(rand_code($caracteres, $longitud));
            $modalCreateNewScrap.find('[id=length_nuevo]').val(json.type_scrap.length);
            $modalCreateNewScrap.find('[id=width_nuevo]').val(json.type_scrap.width);

            if ( json.type_scrap.id == 1 || json.type_scrap.id == 2 || json.type_scrap.id == 6 )
            {
                $('#length_item_nuevo').show();
                $('#width_item_nuevo').show();
                $('#length_new_item_nuevo').show();
                $('#width_new_item_nuevo').show();
            }

            // TODO: Agregamos tubos pequeños
            if ( json.type_scrap.id == 3 || json.type_scrap.id == 4 || json.type_scrap.id == 5 )
            {
                $('#length_item_nuevo').show();
                $('#width_item_nuevo').hide();
                $('#length_new_item_nuevo').show();
                $('#width_new_item_nuevo').hide();
            }

            $('#length_new_nuevo').removeAttr('readonly');
            $('#width_new_nuevo').removeAttr('readonly');
            $('#length_new_nuevo').val('');
            $('#width_new_nuevo').val('');

            $modalCreateNewScrap.modal('show');

        }
    });
}

function blockLength() {
    $blobkLength = 1;
    $blobkWidth = 0;
    // Bloquear el largo
    $btnBlockLength.removeClass("btn-success");
    $btnBlockLength.addClass("btn-danger");
    $btnBlockLength.tooltip('hide');
    //$btnBlockLength.attr('title', 'Desbloquear');
    $btnBlockLength.attr('data-original-title', 'Bloqueado');
    $btnBlockLength.tooltip('show');

    // Desbloquear el ancho
    $btnBlockWidth.removeClass("btn-danger");
    $btnBlockWidth.addClass("btn-success");
    //$btnBlockWidth.attr('title', 'Bloquear');
    $btnBlockWidth.attr('data-original-title', 'Desbloqueado');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    // Colocar el valor del length bloqueado y el valor que es
    var longitud = $('#length').val();
    $('#length_new').val(longitud);
    $('#length_new').attr('readonly', 'readonly');

    $('#width_new').val('');
    $('#width_new').removeAttr('readonly');
}

function blockWidth() {
    $blobkLength = 0;
    $blobkWidth = 1;
    // Bloquear el ancho
    $btnBlockWidth.removeClass("btn-success");
    $btnBlockWidth.addClass("btn-danger");
    $btnBlockWidth.tooltip('hide');
    //$btnBlockWidth.attr('title', 'Desbloquear');
    $btnBlockWidth.attr('data-original-title', 'Bloqueado');
    $btnBlockWidth.tooltip('show');

    // Desbloquear el largo
    $btnBlockLength.removeClass("btn-danger");
    $btnBlockLength.addClass("btn-success");
    //$btnBlockLength.attr('title', 'Bloquear');
    $btnBlockLength.attr('data-original-title', 'Desbloqueado');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    var ancho = $('#width').val();
    $('#width_new').val(ancho);
    $('#width_new').attr('readonly', 'readonly');

    $('#length_new').val('');
    $('#length_new').removeAttr('readonly');
}

function saveScrap() {
    $("#btn-submit").attr("disabled", true);

    var typescrap = $('#typescrap');

    if ( typescrap == 1 || typescrap == 2 || typescrap == 6 )
    {
        if( $('#length_new').val().trim() === '' || $('#length_new').val()<0 )
        {
            toastr.error('Debe ingresar una longitud', 'Error',
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
            $("#btn-submit").attr("disabled", true);
            return;
        }

        if( $('#width_new').val().trim() === '' || $('#width_new').val()<0 )
        {
            toastr.error('Debe ingresar un ancho', 'Error',
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
            $("#btn-submit").attr("disabled", true);
            return;
        }

        if( $blobkWidth == 0 && $blobkLength == 0 )
        {
            toastr.error('Debe bloquear el largo o el ancho', 'Error',
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
            $("#btn-submit").attr("disabled", true);
            return;
        }
    }

    // TODO: Agregamos tubos pequeños
    if ( typescrap == 3 || typescrap == 4 || typescrap == 5 )
    {
        if( $('#length_new').val().trim() === '' || $('#length_new').val()<0 )
        {
            toastr.error('Debe ingresar una longitud', 'Error',
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
            $("#btn-submit").attr("disabled", true);
            return;
        }
    }

    var createUrl = $formScrap.data('url');
    var formulario = $('#formScrap')[0];
    var blockAncho = JSON.stringify($blobkWidth);
    var blockLargo = JSON.stringify($blobkLength);
    var form = new FormData(formulario);
    form.append('blockAncho', blockAncho);
    form.append('blockLargo', blockLargo);

    $.ajax({
        url: createUrl,
        method: 'POST',
        data: form,
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
            $modalCreateScrap.modal('hide');
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

function showModalCreateScrap() {
    var materialId = $(this).attr('data-materialId');
    var material = $(this).attr('data-material');
    var priceBD = $(this).attr('data-priceBD');
    var codeItem = $(this).attr('data-codeItem');
    var idItem = $(this).attr('data-idItem');
    var lengthItem = $(this).attr('data-lengthItem');
    var widthItem = $(this).attr('data-widthItem');
    var typescrap = $(this).attr('data-typescrap');

    $modalCreateScrap.find('[id=material_id]').val(materialId);
    $modalCreateScrap.find('[id=material]').val(material);
    $modalCreateScrap.find('[id=price]').val(priceBD);
    $modalCreateScrap.find('[id=code]').val(codeItem);
    $modalCreateScrap.find('[id=idItem]').val(idItem);
    $modalCreateScrap.find('[id=length]').val(lengthItem);
    $modalCreateScrap.find('[id=width]').val(widthItem);
    $modalCreateScrap.find('[id=typescrap]').val(typescrap);

    if ( typescrap == 1 || typescrap == 2 || typescrap == 6 )
    {
        $('#length_item').show();
        $('#width_item').show();
        $('#length_new_item').show();
        $('#width_new_item').show();
        $('#show-block-length').show();
    }

    // TODO: Agregamos tubos pequeños
    if ( typescrap == 3 || typescrap == 4 || typescrap == 5 )
    {
        $('#length_item').show();
        $('#width_item').hide();
        $('#length_new_item').show();
        $('#width_new_item').hide();
        $('#show-block-length').hide();
    }

    $('#length_new').removeAttr('readonly');
    $('#width_new').removeAttr('readonly');
    $('#length_new').val('');
    $('#width_new').val('');
    $btnBlockLength.removeClass("btn-danger");
    $btnBlockLength.addClass("btn-success");
    $btnBlockLength.attr('data-original-title', 'Desbloqueado');
    $btnBlockWidth.removeClass("btn-danger");
    $btnBlockWidth.addClass("btn-success");
    $btnBlockWidth.attr('data-original-title', 'Desbloqueado');
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    $blobkLength = 0;
    $blobkWidth = 0;

    $modalCreateScrap.modal('show');
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
    $modalAddItems.find('[id=material_selected]').val(material_name);
    $modalAddItems.find('[id=material_selected]').prop('disabled', true);
    let material_quantity = $('#quantity').val();
    $modalAddItems.find('[id=quantity_selected]').val(material_quantity);
    $modalAddItems.find('[id=quantity_selected]').prop('disabled', true);
    let material_price = $('#price').val();
    $modalAddItems.find('[id=price_selected]').val(material_price);
    $modalAddItems.find('[id=price_selected]').prop('disabled', true);

    $('#body-items').html('');

    for (var i = 0; i<material_quantity; i++)
    {
        renderTemplateItem();
        $('.select2').select2();
    }

    $('.locations').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
            minLength: 1 /* Specify minimum characters required for showing suggestions */
        },
        {
            limit: 12,
            source: substringMatcher($locations)
        });

    $modalAddItems.modal('show');

    /*$items.push({
        "productId" : sku,
        "qty" : qty,
        "price" : price
    });*/
}

function rand_code($caracteres, $longitud){
    var code = "";
    for (var x=0; x < $longitud; x++)
    {
        var rand = Math.floor(Math.random()*$caracteres.length);
        code += $caracteres.substr(rand, 1);
    }
    return code;
}

