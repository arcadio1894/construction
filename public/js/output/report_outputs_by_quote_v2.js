$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);
    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSearch);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('#btn-export').on('click', exportExcel);

});

var $permissions;

function exportExcel() {
    $("#btn-export").attr("disabled", true);
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");
    var quote = $('#quote').val();
    console.log(start);
    console.log(end);
    console.log(startDate);
    console.log(endDate);

    if (quote != "")
    {
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
                content: 'Si no hay fechas se descargará todas las solicitudes',
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
                                quote: quote
                            };

                            $.alert('Descargando archivo ...');
                            //$("#btn-export").attr("disabled", false);

                            var url = "/dashboard/exportar/reporte/ordenes/by/quote/v2/?" + $.param(query);

                            window.location = url;
                            $("#btn-export").attr("disabled", false);
                        },
                    },
                    cancel: {
                        text: 'CANCELAR',
                        action: function (e) {
                            $.alert("Exportación cancelada.");
                            $("#btn-export").attr("disabled", false);
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
                quote: quote
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

            var url = "/dashboard/exportar/reporte/ordenes/by/quote/v2/?" + $.param(query);

            window.location = url;
            $("#btn-export").attr("disabled", false);

        }
    } else
    {
        toastr.error("Seleccione una cotización para poder hacer la búsqueda", 'Error', {
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
        $("#btn-export").attr("disabled", false);
    }


}

function showDataSearch() {
    getDataOutputsRequest(1)
}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataOutputsRequest(numberPage)
}

function getDataOutputsRequest($numberPage) {
    $("#btn-search").attr("disabled", true);
    $('[data-toggle="tooltip"]').tooltip('dispose').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    var year = $('#year').val();
    var quote = $('#quote').val();
    var startDate = $('#start').val();
    var endDate = $('#end').val();

    if ( quote != "" )
    {
        $.get('/dashboard/get/outputs/by/quote/v2/'+$numberPage, {
            year: year,
            quote: quote,
            startDate: startDate,
            endDate: endDate,
        }, function(data) {
            $("#btn-search").attr("disabled", false);
            if ( data.data.length == 0 )
            {
                renderDataOutputsRequestEmpty(data);
            } else {
                renderDataOutputsRequest(data);
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
            $("#btn-search").attr("disabled", false);
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
    } else {
        toastr.error("Seleccione una cotización para poder hacer la búsqueda", 'Error', {
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
        $("#btn-search").attr("disabled", false);
    }

}

function renderDataOutputsRequestEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' solicitudes de salidas');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataOutputsRequest(data) {
    var dataFinanceWorks = data.data;
    var pagination = data.pagination;
    console.log(dataFinanceWorks);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' solicitudes de salidas.');
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
    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-year]").innerHTML = data.year;
    /*clone.querySelector("[data-execution_order]").innerHTML = data.execution_order;
    clone.querySelector("[data-quote]").innerHTML = data.quote;
    clone.querySelector("[data-description]").innerHTML = data.description;*/
    clone.querySelector("[data-request_date]").innerHTML = data.request_date;
    clone.querySelector("[data-requesting_user]").innerHTML = data.requesting_user;
    clone.querySelector("[data-responsible_user]").innerHTML = data.responsible_user;
    clone.querySelector("[data-typeText]").innerHTML = data.typeText + "<br>"+data.stateText;
    clone.querySelector("[data-material_code]").innerHTML = data.material_code;
    clone.querySelector("[data-material]").innerHTML = data.material;
    clone.querySelector("[data-quantity]").innerHTML = parseFloat(data.quantity).toFixed(2);
    clone.querySelector("[data-price]").innerHTML = data.currency + " "+ parseFloat(data.price).toFixed(2);


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