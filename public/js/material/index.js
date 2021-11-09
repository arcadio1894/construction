function format ( d ) {
    var subcategory = (d.subcategory===null) ? 'Ninguno':d.subcategory.name;
    var type = (d.material_type===null) ? 'Ninguno':d.material_type.name;
    var subtype = (d.sub_type===null) ? 'Ninguno':d.sub_type.name;
    var warrant = (d.warrant===null) ? 'Ninguno':d.warrant.name;
    var quality = (d.quality===null) ? 'Ninguno':d.quality.name;
    var brand = (d.brand===null) ? 'Ninguno':d.brand.name;
    var exampler = (d.exampler===null) ? 'Ninguno':d.exampler.name;
    var typescrap = (d.type_scrap===null) ? 'Ninguno':d.type_scrap.name;
    return 'Medida: '+d.measure+'<br>'+
        'Unidad de medida: '+d.unit_measure.name+'<br>'+
        'Stock máximo: '+d.stock_max+'<br>'+
        'Stock míximo: '+d.stock_min+'<br>'+
        'Categoría: '+d.category.name+'<br>'+
        'SubCategoría: '+subcategory+'<br>'+
        'Tipo de material: '+type+'<br>'+
        'Sub tipo: '+subtype+'<br>'+
        'Cédula: '+warrant+'<br>'+
        'Calidad: '+quality+'<br>'+
        'Marca: '+brand+'<br>'+
        'Modelo: '+exampler+'<br>'+
        'Retacería: '+typescrap+'<br>';
}

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    console.log($permissions);
    var table = $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/all/materials",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            {
                "class":          "details-control",
                "orderable":      false,
                "data":           null,
                "defaultContent": ""
            },
            { data: 'code' },
            { data: 'full_description' },
            { data: 'measure' },
            { data: 'unit_measure.name' },
            { data: 'stock_max' },
            { data: 'stock_min' },
            { data: 'stock_current' },
            { data: 'priority' },
            { data: 'unit_price' },
            { data: null,
                title: 'Imagen',
                wrap: true,
                "render": function (item)
                {
                    return '<img src="'+document.location.origin+ '/images/material/'+item.image+'" alt="'+item.name+'" width="50px" height="50px">'
                }
            },
            { data: 'category.name' },
            { data: null,
                title: 'Subcategoría',
                wrap: true,
                "render": function (item)
                {
                    if ( item.subcategory === null )
                        return '<p>Ninguno</p>';
                    return '<p>'+ item.subcategory.name +'</p>';
                }
            },
            { data: null,
                title: 'Tipo',
                wrap: true,
                "render": function (item)
                {
                    if ( item.material_type === null )
                        return '<p>Ninguno</p>';
                    return '<p>'+ item.material_type.name +'</p>';
                }
            },
            { data: null,
                title: 'Subtipo',
                wrap: true,
                "render": function (item)
                {
                    if ( item.sub_type === null )
                        return '<p>Ninguno</p>';
                    return '<p>'+ item.sub_type.name +'</p>';
                }
            },
            { data: null,
                title: 'Cédula',
                wrap: true,
                "render": function (item)
                {
                    if ( item.warrant === null )
                        return '<p>Ninguno</p>';
                    return '<p>'+ item.warrant.name +'</p>';
                }
            },
            { data: null,
                title: 'Calidad',
                wrap: true,
                "render": function (item)
                {
                    if ( item.quality === null )
                        return '<p>Ninguno</p>';
                    return '<p>'+ item.quality.name +'</p>';
                }
            },
            { data: null,
                title: 'Marca',
                wrap: true,
                "render": function (item)
                {
                    if ( item.brand === null )
                        return '<p>Ninguno</p>';
                    return '<p>'+ item.brand.name +'</p>';
                }
            },
            { data: null,
                title: 'Modelo',
                wrap: true,
                "render": function (item)
                {
                    if ( item.exampler === null )
                        return '<p>Ninguno</p>';
                    return '<p>'+ item.exampler.name +'</p>';
                }
            },
            { data: null,
                title: 'Retacería',
                wrap: true,
                "render": function (item)
                {
                    if ( item.type_scrap === null )
                        return '<p>Ninguno</p>';
                    return '<p>'+ item.type_scrap.name +'</p>';
                }
            },
            { data: null,
                title: 'Acciones',
                wrap: true,
                "render": function (item)
                {
                    var text = '';
                    if ( $.inArray('update_material', $permissions) !== -1 ) {
                        text = text + '<a href="'+document.location.origin+ '/dashboard/editar/material/'+item.id+'" class="btn btn-outline-warning btn-sm"><i class="fa fa-pen"></i> </a>  ';
                    }
                    if ( $.inArray('destroy_customer', $permissions) !== -1 ) {
                        text = text + '<button data-delete="'+item.id+'" data-description="'+item.description+'" data-measure="'+item.measure+'" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i> </button>  ';
                    }
                    return text + '<a href="'+document.location.origin+ '/dashboard/view/material/items/'+item.id+'" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i> </a>';
                }
            },

        ],
        "aaSorting": [],
        "columnDefs": [
            {
                "visible": false,
                "targets": [ 3, 5, 6, 13, 14, 15, 16, 17, 18, 19 ]
            }],

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

    $('#dynamic-table tbody').on( 'click', 'tr td.details-control', function () {
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

    // On each draw, loop over the `detailRows` array and show any child rows
    table.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );
    } );

    $(document).on('click', '[data-column]', function (e) {
        //e.preventDefault();

        // Get the column API object
        var column = table.column( $(this).attr('data-column') );

        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
    
    $(".select2").select2({
        width : 'resolve',
        placeholder: "Selecione los permisos",
        allowClear: true
    });

    $formDelete = $('#formDelete');
    $formDelete.on('submit', destroyMaterial);
    $modalDelete = $('#modalDelete');
    $(document).on('click', '[data-delete]', openModalDelete);

});

var $formDelete;
var $modalDelete;
var $permissions;

function openModalDelete() {
    var material_id = $(this).data('delete');
    var description = $(this).data('description');
    var measure = $(this).data('measure');

    $modalDelete.find('[id=material_id]').val(material_id);
    $modalDelete.find('[id=descriptionDelete]').html(description+' '+measure);

    $modalDelete.modal('show');
}

function destroyMaterial() {
    event.preventDefault();
    // Obtener la URL
    var deleteUrl = $formDelete.data('url');
    $.ajax({
        url: deleteUrl,
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
            $modalDelete.modal('hide');
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