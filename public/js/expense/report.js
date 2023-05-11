$(document).ready(function () {

    $selectType = $('#type');
    $selectYear = $('#year');
    $selectMonth = $('#month');
    $selectWeek = $('#week');

    $selectWorker = $('#worker');

    $selectWorker.change(function () {
        $('#report-expense').hide();

    });

    $selectType.change(function () {
        $('#report-expense').hide();

        var type =  $selectType.val();
        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');
        $selectMonth.empty();
        $selectMonth.val('');
        $selectMonth.trigger('change');
        $selectYear.empty();
        $selectYear.val('');
        $selectYear.trigger('change');

        if ( type == 1 )
        {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $.get( "/dashboard/get/years/of/system/", function( data ) {
            $selectYear.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectYear.append($("<option>", {
                    value: data[i].year,
                    text: data[i].year
                }));
            }
        });

    });

    $selectYear.change(function () {
        $('#report-expense').hide();
        var type =  $selectType.val();
        if ( type == 1 ) {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $selectMonth.empty();
        $selectMonth.val('');
        $selectMonth.trigger('change');
        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');

        let year =  $selectYear.val();
        //console.log(year);
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
        $('#report-expense').hide();

        var type =  $selectType.val();
        if ( type == 1 ) {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');

        let year =  $selectYear.val();
        let month =  $selectMonth.val();

        //console.log(year);
        //console.log(month);

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
        $('#report-expense').hide();

    });
    
    $('#btn-generate').on('click', generateReportExpense);

    $('#btn-download').on('click', downloadReportExpense);

    $table = $('#myTable').DataTable({
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
    });

});

let $selectType;
let $selectYear;
let $selectMonth;
let $selectWeek;
let $selectWorker;
let $table;

function downloadReportExpense() {
    let worker = $selectWorker.val();
    let type = $selectType.val();
    let year = $selectYear.val();
    let month = $selectMonth.val();
    let week = $selectWeek.val();

    var query = {
        worker: worker,
        type: type,
        year: year,
        month: month,
        week: week
    };

    toastr.success('Descargando el reporte.', 'Éxito',
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

    var url = "/dashboard/descargar/excel/rendicion/gastos?" + $.param(query);

    window.location = url;

}

function generateReportExpense() {

    let worker = $selectWorker.val();
    let type = $selectType.val();
    let year = $selectYear.val();
    let month = $selectMonth.val();
    let week = $selectWeek.val();

    // TODO: Validaciones
    if ( worker == '' || worker == null )
    {
        toastr.error('Seleccione un trabajador', 'Error',
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

    if ( type == 1 )
    {
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
        // Si es semanal
        var query = {
            worker: worker,
            type: type,
            year: year,
            month: month,
            week: week
        };

        $.get( "/dashboard/generate/report/expense?" + $.param(query), function( data ) {
            //console.log( data );
        }).done(function(data) {
            console.log("Semanal");
            agregarFilas(data.expenses);
            $('#report-expense').show();
            //console.log( data );
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
    } else {
        // Si es mensual
        var query2 = {
            worker: worker,
            type: type,
            year: year,
            month: month,
            week: week
        };

        $.get( "/dashboard/generate/report/expense?" + $.param(query2), function( data ) {
            //console.log( data );
        }).done(function(data) {
            console.log("Mensual");
            agregarFilas(data.expenses);
            $('#report-expense').show();
            //console.log( data );
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
}

function agregarFilas(datos) {
    console.log(datos);
    // Limpia la tabla
    $table.clear();

    // Agrega las filas con los datos recibidos
    for (var i = 0; i < datos.length; i++) {
        var fila = datos[i];
        var nuevaFila = '<tr>' +
            '<td>' + fila.worker.first_name + ' ' + fila.worker.last_name + '</td>' +
            '<td>' + fila.bill.description + '</td>' +
            '<td>' + moment(fila.date_expense).format('DD-MM-YYYY') + '</td>' +
            '<td>' + fila.total + '</td>' +
            '</tr>';
        $table.row.add($(nuevaFila));
    }

    // Dibuja la tabla con las nuevas filas
    $table.draw();
}
