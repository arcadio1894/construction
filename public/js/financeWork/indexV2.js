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

    getDataFinanceWorks(1);

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
    });

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSearch);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $formEditTrabajo = $('#formEditTrabajo');
    $formEditFacturacion = $('#formEditFacturacion');

    $modalEditTrabajo = $('#modalEditTrabajo');
    $modalEditFacturacion = $('#modalEditFacturacion');

    $('#btnSubmitFormEditTrabajo').on('click', submitFormEditTrabajo);
    $('#btnSubmitFormEditFacturacion').on('click', submitFormEditFacturacion);

    $(document).on('click', '[data-formEditTrabajo]', showModalEditTrabajo);

    $(document).on('click', '[data-formEditFacturacion]', showModalEditFacturacion);

    $('#invoiced').on('change', function() {
        var opcionSeleccionada = $(this).val();

        if (opcionSeleccionada == 'y') {
            $('#number_invoice').attr("readonly", false);
            $('#month_invoice').attr("disabled", false);
            $('#year_invoice').attr("disabled", false);
            $('#date_issue').attr("readonly", false);
            $('#date_admission').attr("readonly", false);
            $('#bank_id').attr("disabled", false);
        } else if (opcionSeleccionada == 'n') {
            $('#number_invoice').attr("readonly", true);
            $('#month_invoice').attr("disabled", true);
            $('#year_invoice').attr("disabled", true);
            $('#date_issue').attr("readonly", true);
            $('#date_admission').attr("readonly", true);
            $('#bank_id').attr("disabled", true);
        }
    });

    $selectCustomer = $('#customer_id');
    $selectContact = $('#contact_id');

    $selectCustomer.change(function (event, extraData) {

        $selectContact.empty();
        var customer =  $selectCustomer.val();
        $.get( "/dashboard/get/contact/"+customer, function( data ) {
            $selectContact.append($("<option>", {
                value: '',
                text: 'Seleccione contacto'
            }));
            var contact_id = $('#contact_id').val();
            for ( var i=0; i<data.length; i++ )
            {
                if (data[i].id === parseInt(contact_id)) {
                    var newOption = new Option(data[i].contact, data[i].id, false, true);
                    // Append it to the select
                    $selectContact.append(newOption).trigger('change');

                } else {
                    var newOption2 = new Option(data[i].contact, data[i].id, false, false);
                    // Append it to the select
                    $selectContact.append(newOption2);
                }
            }
        });

    });

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
    getDataFinanceWorks(1)
}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataFinanceWorks(numberPage)
}

function getDataFinanceWorks($numberPage) {
    var description = $('#description').val();
    var year = $('#year').val();
    var code = $('#code').val();
    var order = $('#order').val();
    var customer = $('#customer').val();
    var stateWork = $('#stateWork').val();
    var year_factura = $('#year_factura').val();
    var month_factura = $('#month_factura').val();
    var year_abono = $('#year_factura').val();
    var month_abono = $('#month_factura').val();
    var state = $('#stateInvoiced').val();
    var startDate = $('#start').val();
    var endDate = $('#end').val();

    var rate = $('#rate').val();

    $.get('/dashboard/get/finance/works/v2/'+$numberPage, {
        description:description,
        year: year,
        code: code,
        order: order,
        customer: customer,
        stateWork: stateWork,
        year_factura: year_factura,
        month_factura: month_factura,
        year_abono: year_abono,
        month_abono: month_abono,
        state_invoice: state,
        startDate: startDate,
        endDate: endDate,
        rate:rate
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataFinanceWorksEmpty(data);
        } else {
            renderDataFinanceWorks(data);
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

function renderDataFinanceWorksEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' Pre Cotizaciones');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataFinanceWorks(data) {
    var dataFinanceWorks = data.data;
    var pagination = data.pagination;
    console.log(dataFinanceWorks);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' ingresos clientes.');
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

    clone.querySelector("[data-customer]").innerHTML = data.customer;
    clone.querySelector("[data-responsible]").innerHTML = data.responsible;
    clone.querySelector("[data-area]").innerHTML = data.area;

    clone.querySelector("[data-quote]").innerHTML = data.quote;
    clone.querySelector("[data-type]").innerHTML = data.type;
    clone.querySelector("[data-order_customer]").innerHTML = data.order_customer;
    clone.querySelector("[data-description]").innerHTML = data.description;
    clone.querySelector("[data-initiation]").innerHTML = data.initiation;
    clone.querySelector("[data-delivery]").innerHTML = data.delivery;
    if ( data.delivery_past == 's' )
    {
        clone.querySelector("[data-delivery]").style.backgroundColor = '#ffc107';
    }
    clone.querySelector("[data-state_work]").innerHTML = data.state_work;

    clone.querySelector("[data-act_of_acceptance]").innerHTML = data.act_of_acceptance;
    clone.querySelector("[data-state_act_of_acceptance]").innerHTML = data.state_act_of_acceptance;
    clone.querySelector("[data-docier]").innerHTML = data.docier;
    clone.querySelector("[data-hes]").innerHTML = data.hes;

    clone.querySelector("[data-advancement]").innerHTML = data.advancement;
    clone.querySelector("[data-amount_advancement]").innerHTML = data.amount_advancement;
    clone.querySelector("[data-currency]").innerHTML = data.currency;
    clone.querySelector("[data-subtotal]").innerHTML = data.subtotal;
    clone.querySelector("[data-igv]").innerHTML = data.igv;
    clone.querySelector("[data-total]").innerHTML = data.total;
    clone.querySelector("[data-detraction]").innerHTML = data.detraction;
    clone.querySelector("[data-amount_detraction]").innerHTML = data.amount_detraction;
    clone.querySelector("[data-discount_factoring]").innerHTML = data.discount_factoring;
    clone.querySelector("[data-amount_include_detraction]").innerHTML = data.amount_include_detraction;

    clone.querySelector("[data-pay_condition]").innerHTML = data.pay_condition;
    clone.querySelector("[data-invoiced]").innerHTML = data.invoiced;
    clone.querySelector("[data-number_invoice]").innerHTML = data.number_invoice;
    clone.querySelector("[data-year_invoice]").innerHTML = data.year_invoice;
    clone.querySelector("[data-month_invoice]").innerHTML = data.month_invoice;
    clone.querySelector("[data-date_issue]").innerHTML = data.date_issue;
    clone.querySelector("[data-date_admission]").innerHTML = data.date_admission;
    clone.querySelector("[data-days]").innerHTML = data.days;
    clone.querySelector("[data-date_programmed]").innerHTML = data.date_programmed;

    clone.querySelector("[data-bank]").innerHTML = data.bank;
    clone.querySelector("[data-state]").innerHTML = data.state;
    clone.querySelector("[data-year_paid]").innerHTML = data.year_paid;
    clone.querySelector("[data-month_paid]").innerHTML = data.month_paid;
    clone.querySelector("[data-date_paid]").innerHTML = data.date_paid;
    clone.querySelector("[data-observation]").innerHTML = data.observation;
    clone.querySelector("[data-revision]").innerHTML = data.revision;

    clone.querySelector("[data-formEditTrabajo]").setAttribute('data-formEditTrabajo', data.id);
    clone.querySelector("[data-formEditFacturacion]").setAttribute('data-formEditFacturacion', data.id);

    /*if ( $.inArray('show_proforma', $permissions) !== -1 ) {
        clone.querySelector("[data-show]").setAttribute('href', location.origin + '/dashboard/ver/pre/cotizacion/' + data.id);
    } else {
        let element = clone.querySelector("[data-show]");
        if (element) {
            element.style.display = 'none';
        }
    }

    if ( $.inArray('print_proforma', $permissions) !== -1 ) {
        clone.querySelector("[data-print]").setAttribute('href', document.location.origin + '/dashboard/imprimir/proforma/cliente/' + data.id);
    } else {
        let element = clone.querySelector("[data-print]");
        if (element) {
            element.style.display = 'none';
        }
    }*/

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

function getContacts(contact_id) {
    var customer =  $('#customer_id').val();
    $.get( "/dashboard/get/contact/"+customer, function( data ) {
        $selectContact.append($("<option>", {
            value: '',
            text: ''
        }));
        for ( var i=0; i<data.length; i++ )
        {
            if (data[i].id === parseInt(contact_id)) {
                var newOption = new Option(data[i].contact, data[i].id, false, true);
                // Append it to the select
                $selectContact.append(newOption).trigger('change');

            } else {
                var newOption2 = new Option(data[i].contact, data[i].id, false, false);
                // Append it to the select
                $selectContact.append(newOption2);
            }

        }
    });
}

function submitFormEditTrabajo(event) {
    event.preventDefault();
    var button = $(this);

    button.prop('disabled', true);
    var createUrl = $formEditTrabajo.data('url');
    $.ajax({
        url: createUrl, // La URL a la que enviarás la solicitud
        method: 'POST', // El método HTTP que utilizarás (en este caso, POST)
        data: new FormData($('#formEditTrabajo')[0]),
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
            $modalEditTrabajo.modal('hide');
            setTimeout( function () {
                button.attr("disabled", false);
                location.reload();
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
                location.reload();
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

function showModalEditTrabajo() {
    var financeWork_id = $(this).attr('data-formEditTrabajo');

    $formEditTrabajo.find("[id=financeWork_id]").val(financeWork_id);
    $.get("/dashboard/get/info/trabajo/finance/work/"+financeWork_id, function (data) {
        console.log(data);

        $formEditTrabajo.find("[id=financeWork_id]").val(financeWork_id);
        $('#detraction').val(data.detraction);
        $('#detraction').trigger('change');
        $('#act_of_acceptance').val(data.act_of_acceptance);
        $('#act_of_acceptance').trigger('change');
        $('#date_initiation').val(data.date_initiation);
        $('#date_delivery').val(data.date_delivery);
        $('#state_act_of_acceptance').val("");
        $('#state_act_of_acceptance').trigger('change');
        $('#customer_id').val(data.customer_id);
        $('#customer_id').trigger('change');
        getContacts(data.contact_id);
        $('#state_work').val(data.state_work);
        $('#state_work').trigger('change');
        $('#docier').val(data.docier);
        $('#docier').trigger('change');
        $('#hes').val(data.hes);
        if ( data.state_act_of_acceptance != null )
        {
            $('#state_act_of_acceptance').val(data.state_act_of_acceptance);
            $('#state_act_of_acceptance').trigger('change');
        }

        $modalEditTrabajo.modal('show');
    }, "json");


}

function showModalEditFacturacion() {
    var financeWork_id = $(this).attr('data-formEditFacturacion');

    $formEditFacturacion.find("[id=financeWork_id]").val(financeWork_id);
    $.get("/dashboard/get/info/facturacion/finance/work/"+financeWork_id, function (data) {
        console.log(data);

        $formEditFacturacion.find("[id=financeWork_id]").val(financeWork_id);

        $('#advancement').val(data.advancement);
        $('#advancement').trigger('change');
        $('#amount_advancement').val(data.amount_advancement);
        $('#invoiced').val(data.invoiced);
        $('#invoiced').trigger('change');

        if ( data.invoiced != null && data.invoiced == 'n' )
        {
            $('#number_invoice').attr("readonly", true);
            $('#number_invoice').val(data.number_invoice);
            $('#month_invoice').attr("disabled", true);
            $('#month_invoice').val(data.month_invoice);
            $('#month_invoice').trigger('change');
            $('#year_invoice').attr("disabled", true);
            $('#year_invoice').val(data.year_invoice);
            $('#year_invoice').trigger('change');
            $('#date_issue').attr("readonly", true);
            $('#date_issue').val(data.date_issue);
            $('#date_admission').attr("readonly", true);
            $('#date_admission').val(data.date_admission);
            $('#bank_id').attr("disabled", true);
            $('#bank_id').val(data.bank_id);
            $('#bank_id').trigger('change');
        } else {
            $('#number_invoice').attr("readonly", false);
            $('#number_invoice').val(data.number_invoice);
            $('#month_invoice').attr("disabled", false);
            $('#month_invoice').val(data.month_invoice);
            $('#month_invoice').trigger('change');
            $('#year_invoice').attr("disabled", false);
            $('#year_invoice').val(data.year_invoice);
            $('#year_invoice').trigger('change');
            $('#date_issue').attr("readonly", false);
            $('#date_issue').val(data.date_issue);
            $('#date_admission').attr("readonly", false);
            $('#date_admission').val(data.date_admission);
            $('#bank_id').attr("disabled", false);
            $('#bank_id').val(data.bank_id);
            $('#bank_id').trigger('change');
        }

        $('#state').val(data.state);
        $('#state').trigger('change');

        $('#month_paid').val(data.month_paid);
        $('#month_paid').trigger('change');

        $('#year_paid').val(data.year_paid);
        $('#year_paid').trigger('change');

        $('#date_paid').val(data.date_paid);

        $('#observation').val(data.observation);

        $('#discount_factoring').val(data.discount_factoring);

        $('#revision').val(data.revision);
        $('#revision').trigger('change');

        $modalEditFacturacion.modal('show');
    }, "json");

}