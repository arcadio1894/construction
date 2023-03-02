$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/all/loans",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: null,
                title: 'Código',
                wrap: true,
                "render": function (item)
                {
                    return item.id;
                }
            },
            { data: null,
                title: 'Colaborador',
                wrap: true,
                "render": function (item)
                {
                    return item.worker.first_name + ' ' + item.worker.last_name;
                }
            },
            { data: null,
                title: 'Motivo',
                wrap: true,
                "render": function (item)
                {
                    return item.reason;
                }
            },
            { data: null,
                title: 'Fecha',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.date).format('DD/MM/YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Monto',
                wrap: true,
                "render": function (item)
                {
                    return item.amount_total;
                }
            },
            { data: null,
                title: '# Cuotas',
                wrap: true,
                "render": function (item)
                {
                    return item.num_dues;
                }
            },
            { data: null,
                title: 'Intervalo Días',
                wrap: true,
                "render": function (item)
                {
                    return item.time_pay;
                }
            },
            { data: null,
                title: 'Interés',
                wrap: true,
                "render": function (item)
                {
                    return item.rate;
                }
            },
            /*{ data: null,
                title: 'Archivo',
                wrap: true,
                "render": function (item)
                {
                    var id = item.file;
                    if ( id != null ){
                        var string = id.substr(id.length - 3);
                        if( string.toUpperCase() == 'PDF')
                        {
                            return ' <a target="_blank" href="'+document.location.origin+ '/images/medicalRest/'+item.file+'" '+
                                ' class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver PDF"><i class="fa fa-file-pdf"></i></a>';

                        } else {
                            return ' <button data-src="'+document.location.origin+ '/images/medicalRest/'+item.file+'" data-image="'+item.id+'" '+
                                ' class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="fa fa-image"></i></button>';

                        }
                    } else {
                        return 'No tiene';
                    }

                } },*/
            { data: null,
                title: 'Acciones',
                wrap: true,
                sortable: false,
                "render": function (item)
                {
                    var text = '';
                    if ( $.inArray('edit_loan', $permissions) !== -1 ) {
                        text = text + '<a href="'+document.location.origin+ '/dashboard/editar/prestamo/'+item.id+
                            '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar préstamo"><i class="fa fa-pen"></i></a>';
                    }
                    if ( $.inArray('destroy_loan', $permissions) !== -1 ) {
                        text = text + ' <button data-dues="'+item.id+'" data-reason="'+ item.reason +'" '+
                            ' class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver cuotas"><i class="fas fa-search-dollar"></i></button>';
                        text = text + ' <button data-delete="'+item.id+'" data-reason="'+ item.reason +'" data-date="'+moment(item.date).format('DD/MM/YYYY')+'" '+
                            ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar préstamo"><i class="fa fa-trash"></i></button>';
                    }
                    return text;

                } },

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
        }

    } );

    $formDelete = $('#formDelete');
    //$formDelete.on('submit', destroyContract);
    $('#btn-submit').on('click', destroyLoan);
    $modalDelete = $('#modalDelete');
    $(document).on('click', '[data-delete]', openModalDelete);

    $modalDues = $('#modalDues');
    $(document).on('click', '[data-dues]', openModalDues);
});

var $formDelete;
var $modalDelete;
var $modalDues;
var $permissions;

function openModalDues() {
    $('#table-dues').html('');
    var loan_id = $(this).data('dues');
    var reason = $(this).data('reason');
    $('#loan-reason').html(reason);
    $.ajax({
        url: "/dashboard/all/dues/loan/"+loan_id,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            for (var i=0; i<json.dues.length; i++)
            {
                renderTemplateDue(json.dues[i].num_due, moment(json.dues[i].date).format('DD/MM/YYYY'), json.dues[i].amount);
                //$materials.push(json[i].material);
            }

        }
    });
    $modalDues.modal('show');
}

function openModalDelete() {
    var loan_id = $(this).data('delete');
    var date = $(this).data('date');
    var reason = $(this).data('reason');

    var texto = reason + ' - ' + date;

    $modalDelete.find('[id=loan_id]').val(loan_id);
    $modalDelete.find('[id=code]').html(texto);

    $modalDelete.modal('show');
}

function destroyLoan() {
    event.preventDefault();
    // Obtener la URL
    $("#btn-submit").attr("disabled", true);
    var formulario = $('#formDelete')[0];
    var form = new FormData(formulario);
    var deleteUrl = $formDelete.data('url');
    $.ajax({
        url: deleteUrl,
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
            $modalDelete.modal('hide');
            setTimeout( function () {
                $("#btn-submit").attr("disabled", false);
                location.reload();
            }, 1000 )
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

function renderTemplateDue(num_due, date, amount) {
    var clone = activateTemplate('#template-due');
    clone.querySelector("[data-num_due]").innerHTML = num_due;
    clone.querySelector("[data-date]").innerHTML = date;
    clone.querySelector("[data-amount]").innerHTML = amount;
    $('#table-dues').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}