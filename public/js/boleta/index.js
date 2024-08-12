$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    console.log($permissions);
    var table = $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/get/workers/boletas/",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: 'id' },
            { data: 'dni' },
            { data: null,
                title: 'Apellidos y Nombres',
                wrap: true,
                "render": function (item)
                {
                    var first_name = (item.first_name == null) ? '':item.first_name;
                    var last_name = (item.last_name == null) ? '':item.last_name;
                    return last_name + ' ' + first_name ;
                }
            },
            { data: 'work_function' },
            { data: 'area_worker' },
            { data: null,
                title: 'Acciones',
                wrap: true,
                "render": function (item)
                {
                    var text = '';
                    //if ( $.inArray('update_material', $permissions) !== -1 ) {
                        //text = text + '<a href="'+document.location.origin+ '/dashboard/editar/colaborador/'+item.id+'" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pen"></i> </a>  ';
                    //}
                    text = text + '<a href="'+document.location.origin+ '/dashboard/ver/boletas/semanales/'+item.id+
                        '" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Boletas Semanales"><i class="fa fa-eye"></i></a> ';
                    /*text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/boleta/semanal/' + item.id +
                        '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir Boleta Semanal"><i class="fa fa-print"></i></a> ';*/
                    return text ;
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

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('#btn-download').on('click', showModalHaberes);

    $modalHaberes = $('#modalHaberes');

    $('#btn-submitExport').on('click', getReportExport);

    $selectYear = $('#yearG');
    $selectMonth = $('#monthG');
    $selectWeek = $('#weekG');

    $selectYear.change(function () {
        $selectMonth.empty();
        $selectMonth.val('');
        $selectMonth.trigger('change');
        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');

        let year =  $selectYear.val();
        console.log(year);
        if ( year != null || year != undefined )
        {
            $.get( "/dashboard/get/months/of/year/"+year, function( data ) {
                $selectMonth.append($("<option>", {
                    value: '',
                    text: ''
                }));
                for ( var i=0; i<data.length; i++ )
                {
                    $selectMonth.append($("<option>", {
                        value: data[i].month,
                        text: data[i].month_name
                    }));
                }
            });
        }

    });

    $selectMonth.change(function () {
        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');

        let year =  $selectYear.val();
        let month =  $selectMonth.val();

        console.log(year);
        console.log(month);

        if ( (year != null || year != undefined) && (month != null || month != undefined) )
        {
            $.get( "/dashboard/get/weeks/of/month/"+month+"/year/"+year, function( data ) {
                $selectWeek.append($("<option>", {
                    value: '',
                    text: ''
                }));
                for ( var i=0; i<data.length; i++ )
                {
                    $selectWeek.append($("<option>", {
                        value: data[i].week,
                        text: data[i].week
                    }));
                }
            });
        }

    });

    $selectWeek.change(function () {


    });

    $('#btn-submitGenerate').on('click', generateBoletaWorkers);
});

var $formDelete;
var $modalDelete;
var $permissions;
var $modalHaberes;
let $selectYear;
let $selectMonth;
let $selectWeek;

function generateBoletaWorkers() {
    let year = $selectYear.val();
    let month = $selectMonth.val();
    let week = $selectWeek.val();

    // TODO: Validaciones
    if ( year == '' || year == null )
    {
        toastr.error('Seleccione un año de la lista', 'Error',
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

    if ( month == '' || month == null )
    {
        toastr.error('Seleccione un mes de la lista', 'Error',
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

    if ( week == '' || week == null )
    {
        toastr.error('Seleccione una semana de la lista', 'Error',
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

    var query = {
        year: year,
        month: month,
        week: week
    };

    $.get( "/dashboard/generate/boletas/trabajadores?" + $.param(query), function( data ) {
        console.log( data );
    }).done(function(data) {
        $dataBoleta = data;

        console.log( data );
    }).fail(function(data) {
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

    });
}

function getReportExport() {
    $("#btn-submitExport").attr("disabled", true);
    let year = $('#year').val();
    var month  = $('#month').val();

    if ( year == '' || month == '' )
    {
        console.log('Sin fechas');
        $.confirm({
            icon: 'fas fa-file-excel',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó año ni mes',
            content: 'Si no hay año ni mes, se descargará del mes actual y año actual',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        var query = {
                            year: $('#currentYear').val(),
                            month: $('#currentMonth').val(),
                        };

                        $.alert('Descargando archivo ...');

                        var url = "/dashboard/get/report/monthly/workers/?" + $.param(query);

                        window.location = url;
                        $("#btn-submitExport").attr("disabled", false);
                        $modalHaberes.modal('hide');
                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Exportación cancelada.");
                        $("#btn-submitExport").attr("disabled", false);
                        $modalHaberes.modal('hide');
                    },
                },
            },
        });
    } else {
        var query = {
            year: year,
            month: month,
        };

        toastr.success('Descargando archivo ...', 'Éxito',
            {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "2000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });

        var url = "/dashboard/get/report/monthly/workers/?" + $.param(query);

        window.location = url;
        $("#btn-submitExport").attr("disabled", false);
        $modalHaberes.modal('hide');
    }
}

function showModalHaberes() {
    $modalHaberes.modal('show');
}

function destroyWorker() {
    event.preventDefault();
    var id_worker = $(this).data('delete');
    var button = $(this);
    var nombre = $(this).data('nombre');

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de inhabilitar este colaborador?',
        content: nombre,
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/destroy/worker/'+id_worker,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                processData:false,
                contentType:false,
                success: function (data) {
                    console.log(data);
                    vdialog.success(data.message);
                    setTimeout( function () {
                        location.reload();
                    }, 2000 )
                },
                error: function (data) {
                    toastr.error('Algo sucedió en el servidor.', 'Error',
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

                },
            });

        },
        cancel: function(){
            vdialog.alert('Colaborador no inhabilitado');

        }
    });
}