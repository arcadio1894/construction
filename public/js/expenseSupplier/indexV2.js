$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);
    /*var tabla = $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/get/finance/works",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: 'year' },

            { data: 'customer' },
            { data: 'responsible' },
            { data: 'area' },

            { data: 'quote'},
            { data: 'type' },
            { data: 'order_customer' },
            { data: 'description'},
            { data: 'initiation'},
            { data: 'delivery'},
            { data: 'state_work'},

            { data: 'act_of_acceptance'},
            { data: 'state_act_of_acceptance'},
            { data: 'docier'},
            { data: 'hes'},

            { data: 'advancement'},
            { data: 'amount_advancement'},
            { data: 'subtotal'},
            { data: 'igv'},
            { data: 'total'},
            { data: 'detraction'},
            { data: 'amount_detraction'},
            { data: 'discount_factoring'},
            { data: 'amount_include_detraction'},

            { data: 'pay_condition'},
            { data: 'invoiced'},
            { data: 'number_invoice'},
            { data: 'year_invoice'},
            { data: 'month_invoice'},
            { data: 'date_issue'},
            { data: 'date_admission'},
            { data: 'days'},
            { data: 'date_programmed'},

            { data: 'bank'},
            { data: 'state'},
            { data: 'year_paid'},
            { data: 'month_paid'},
            { data: 'date_paid'},
            { data: 'observation'},
            { data: 'revision'},
            { data: null,
                title: '',
                wrap: true,
                sortable:false,
                "render": function (item)
                {
                    var text = '';
                    text = text + ' <button data-formEditTrabajo="' + item.id + '" ' +
                        ' class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar Información Trabajo"><i class="fas fa-tools fa-lg"></i></button>';
                    text = text + ' <button data-formEditFacturacion="' + item.id + '" ' +
                        ' class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar Información Facturación"><i class="fas fa-donate fa-lg"></i></button>';

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
        }

    } );

    $('#filterYear, #filterCustomer, #filterStateWork, #filterState').on('change', function() {
        var filterYear = $('#filterYear').val();
        var filterCustomer = $('#filterCustomer').val();
        var filterStateWork = $('#filterStateWork').val();
        var filterState = $('#filterState').val();

        tabla.columns(0).search(filterYear).columns(1).search(filterCustomer).columns(8).search(filterStateWork).columns(28).search(filterState).draw();
    });
*/
    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    $('#sandbox-container1 .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    getDataExpenseSuppliers(1);

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
    });

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSearch);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $formEditFacturacion = $('#formEditFacturacion');

    $modalEditFacturacion = $('#modalEditFacturacion');

    $('#btnSubmitFormEditFacturacion').on('click', submitFormEditFacturacion);

    $(document).on('click', '[data-formEditFacturacion]', showModalEditFacturacion);

    $('#btn-export').on('click', exportExcel);
});

var $formEditTrabajo;
var $formEditFacturacion;
var $modalEditTrabajo;
var $modalEditFacturacion;
var $selectCustomer;
var $selectContact;

var $permissions;

function exportExcel() {
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");
    var rate = $('#rate').val();

    console.log(start);
    console.log(end);
    console.log(startDate);
    console.log(endDate);

    if ( start == '' || end == '' )
    {
        console.log('Sin fechas');
        $.confirm({
            icon: 'fas fa-file-excel',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó fechas',
            content: 'Si no hay fechas se descargará todos los ingresos',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        var query = {
                            start: start,
                            end: end,
                            rate: rate
                        };

                        $.alert('Descargando archivo ...');

                        var url = "/dashboard/exportar/reporte/ingresos/clientes/?" + $.param(query);

                        window.location = url;

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Exportación cancelada.");
                    },
                },
            },
        });
    } else {
        console.log('Con fechas');
        console.log(JSON.stringify(start));
        console.log(JSON.stringify(end));

        var query = {
            start: start,
            end: end,
            rate: rate
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

        var url = "/dashboard/exportar/reporte/ingresos/clientes/?" + $.param(query);

        window.location = url;

    }

}

function showDataSearch() {
    getDataExpenseSuppliers(1)
}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataExpenseSuppliers(numberPage)
}

function getDataExpenseSuppliers($numberPage) {
    var number_order = $('#number_order').val();
    var year = $('#year').val();
    var supplier = $('#supplier').val();
    var date_due = $('#date_due').val();
    var stateCredit = $('#stateCredit').val();
    var statePaid = $('#statePaid').val();
    var month_order = $('#month_order').val();
    var startDate = $('#start').val();
    var endDate = $('#end').val();

    $.get('/dashboard/get/expenses/supplier/v2/'+$numberPage, {
        number_order:number_order,
        year: year,
        supplier: supplier,
        date_due: date_due,
        stateCredit: stateCredit,
        statePaid: statePaid,
        month_order: month_order,
        startDate: startDate,
        endDate: endDate,
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataExpenseSuppliersEmpty(data);
        } else {
            renderDataExpenseSuppliers(data);
        }


    }).fail(function(jqXHR, textStatus, errorThrown) {
        // Función de error, se ejecuta cuando la solicitud GET falla
        console.error(textStatus, errorThrown);
        if (jqXHR.responseJSON.message && !jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.message, 'Error', {
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
        for (var property in jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.errors[property], 'Error', {
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
    }, 'json')
        .done(function() {
            // Configuración de encabezados
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            };

            $.ajaxSetup({
                headers: headers
            });
        });
}

function renderDataExpenseSuppliersEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' egresos proveedores');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataExpenseSuppliers(data) {
    var dataFinanceWorks = data.data;
    var pagination = data.pagination;
    console.log(dataFinanceWorks);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' egresos proveedores.');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    for (let j = 0; j < dataFinanceWorks.length ; j++) {
        renderDataTable(dataFinanceWorks[j]);
    }

    if (pagination.currentPage > 1)
    {
        renderPreviousPage(pagination.currentPage-1);
    }

    if (pagination.totalPages > 1)
    {
        if (pagination.currentPage > 3)
        {
            renderItemPage(1);

            if (pagination.currentPage > 4) {
                renderDisabledPage();
            }
        }

        for (var i = Math.max(1, pagination.currentPage - 2); i <= Math.min(pagination.totalPages, pagination.currentPage + 2); i++)
        {
            renderItemPage(i, pagination.currentPage);
        }

        if (pagination.currentPage < pagination.totalPages - 2)
        {
            if (pagination.currentPage < pagination.totalPages - 3)
            {
                renderDisabledPage();
            }
            renderItemPage(i, pagination.currentPage);
        }

    }

    if (pagination.currentPage < pagination.totalPages)
    {
        renderNextPage(pagination.currentPage+1);
    }
}

function renderDataTableEmpty() {
    var clone = activateTemplate('#item-table-empty');
    $("#body-table").append(clone);
}

function renderDataTable(data) {
    var clone = activateTemplate('#item-table');
    clone.querySelector("[data-year]").innerHTML = data.year;
    clone.querySelector("[data-month]").innerHTML = data.month;
    clone.querySelector("[data-date_order]").innerHTML = data.date_order;
    clone.querySelector("[data-supplier]").innerHTML = data.supplier;
    clone.querySelector("[data-order]").innerHTML = data.order;

    clone.querySelector("[data-soles]").innerHTML = data.soles;
    clone.querySelector("[data-dolares]").innerHTML = data.dolares;

    clone.querySelector("[data-deadline]").innerHTML = data.deadline;
    clone.querySelector("[data-invoice]").innerHTML = data.invoice;
    clone.querySelector("[data-date_invoice]").innerHTML = data.date_invoice;
    clone.querySelector("[data-days]").innerHTML = data.days;
    clone.querySelector("[data-due_date]").innerHTML = data.due_date;
    clone.querySelector("[data-state_credit]").innerHTML = data.state_credit;
    clone.querySelector("[data-state_paid]").innerHTML = data.state_paid;

    clone.querySelector("[data-formEditFacturacion]").setAttribute('data-formEditFacturacion', data.id);
    clone.querySelector("[data-formEditFacturacion]").setAttribute('data-type', data.type);

    $("#body-table").append(clone);

    $('[data-toggle="tooltip"]').tooltip();
}

function renderPreviousPage($numberPage) {
    var clone = activateTemplate('#previous-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

function renderDisabledPage() {
    var clone = activateTemplate('#disabled-page');
    $("#pagination").append(clone);
}

function renderItemPage($numberPage, $currentPage) {
    var clone = activateTemplate('#item-page');
    if ( $numberPage == $currentPage )
    {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-active]").setAttribute('class', 'page-item active');
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    } else {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    }

    $("#pagination").append(clone);
}

function renderNextPage($numberPage) {
    var clone = activateTemplate('#next-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

function submitFormEditFacturacion(event) {
    event.preventDefault();
    var button = $(this);

    button.prop('disabled', true);
    var createUrl = $formEditFacturacion.data('url');
    $.ajax({
        url: createUrl, // La URL a la que enviarás la solicitud
        method: 'POST', // El método HTTP que utilizarás (en este caso, POST)
        data: new FormData($('#formEditFacturacion')[0]),
        processData:false,
        contentType:false,
        success: function(data) {
            // Esta función se ejecutará si la solicitud fue exitosa
            // La variable 'response' contendrá los datos devueltos por el servidor (según el tipo de datos especificado en 'dataType')
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
            $modalEditFacturacion.modal('hide');
            setTimeout( function () {
                button.attr("disabled", false);
                showDataSearch();
                //location.reload();
            }, 100 )
        },
        error: function(data) {
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
            button.attr("disabled", false);
            // Esta función se ejecutará si ocurre un error en la solicitud
            // Puedes utilizar las variables 'jqXHR', 'textStatus' y 'errorThrown' para obtener información sobre el error
        }
    });
}

function showModalEditFacturacion() {
    var invoice_id = $(this).attr('data-formEditFacturacion');
    var type = $(this).attr('data-type');

    $formEditFacturacion.find("[id=invoice_id]").val(invoice_id);
    $formEditFacturacion.find("[id=type]").val(type);

    if ( invoice_id == "" )
    {
        invoice_id = "nn";
    }

    $.get("/dashboard/get/info/facturacion/expense/supplier/"+invoice_id+"/"+type, function (data) {
        console.log(data);

        //$formEditFacturacion.find("[id=invoice_id]").val(invoice_id);

        $('#state').val(data.state);
        $('#state').trigger('change');

        $modalEditFacturacion.modal('show');
    }, "json");

}