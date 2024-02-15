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

    $('#sandbox-container1 .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    getDataOrderPurchases(1);

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
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
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");

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
            content: 'Si no hay fechas se descargará todos las órdenes de compra',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        var query = {
                            start: start,
                            end: end
                        };

                        $.alert('Descargando archivo ...');

                        var url = "/dashboard/exportar/reporte/ordenes/compra/v2/?" + $.param(query);

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
            end: end
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

        var url = "/dashboard/exportar/reporte/ordenes/compra/v2/?" + $.param(query);

        window.location = url;

    }

}

function showDataSearch() {
    getDataOrderPurchases(1)
}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataOrderPurchases(numberPage)
}

function getDataOrderPurchases($numberPage) {
    $('[data-toggle="tooltip"]').tooltip('dispose').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    var year = $('#year').val();
    var supplier = $('#supplier').val();
    var code = $('#code').val();
    var quote = $('#quote').val();
    var type = $('#type').val();
    var state = $('#state').val();
    var deliveryDate = $('#deliveryDate').val();
    var startDate = $('#start').val();
    var endDate = $('#end').val();

    $.get('/dashboard/get/all/orders/entrie/v2/'+$numberPage, {
        year: year,
        supplier: supplier,
        code: code,
        quote: quote,
        type: type,
        state: state,
        deliveryDate: deliveryDate,
        startDate: startDate,
        endDate: endDate,
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataOrderPurchasesEmpty(data);
        } else {
            renderDataOrderPurchases(data);
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

function renderDataOrderPurchasesEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' órdenes de compra');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataOrderPurchases(data) {
    var dataFinanceWorks = data.data;
    var pagination = data.pagination;
    console.log(dataFinanceWorks);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' órdenes de compra.');
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
    clone.querySelector("[data-id]").innerHTML = data.id;
    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-date_order]").innerHTML = data.date_order;
    clone.querySelector("[data-date_arrival]").innerHTML = data.date_arrival;
    clone.querySelector("[data-observation]").innerHTML = data.observation;
    clone.querySelector("[data-supplier]").innerHTML = data.supplier;
    clone.querySelector("[data-approved_user]").innerHTML = data.approved_user;
    clone.querySelector("[data-currency]").innerHTML = data.currency;
    clone.querySelector("[data-total]").innerHTML = data.total;
    clone.querySelector("[data-state]").innerHTML = data.stateText;

    var botones = clone.querySelector("[data-buttons]");

    if ( data.type == "e" )
    {
        var cloneBtnExpress = activateTemplate('#template-express');

        if ( $.inArray('list_orderPurchaseNormal', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/orden/compra/'+data.id;
            cloneBtnExpress.querySelector("[data-imprimir]").setAttribute("href", url);
        } else {
            let element = cloneBtnExpress.querySelector("[data-imprimir]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_orderPurchaseNormal', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/orden/compra/express/'+data.id;
            cloneBtnExpress.querySelector("[data-ver_orden]").setAttribute("href", url);
        } else {
            let element = cloneBtnExpress.querySelector("[data-ver_orden]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if (data.regularize === 'nr') {
            if (data.status == 1) {
                let element = cloneBtnExpress.querySelector("[data-crear_entrada]");
                if (element) {
                    element.style.display = 'none';
                }

            } else if ( data.status == 0 ) {
                if ( $.inArray('create_entryPurchase', $permissions) !== -1 ) {
                    let url = document.location.origin+ '/dashboard/crear/entrada/compra/orden/'+data.id;
                    cloneBtnExpress.querySelector("[data-crear_entrada]").setAttribute("href", url);
                } else {
                    let element = cloneBtnExpress.querySelector("[data-crear_entrada]");
                    if (element) {
                        element.style.display = 'none';
                    }
                }
            } else if (data.status == 2) {
                if ( $.inArray('create_entryPurchase', $permissions) !== -1 ) {
                    let url = document.location.origin+ '/dashboard/crear/entrada/compra/orden/'+data.id;
                    cloneBtnExpress.querySelector("[data-crear_entrada]").setAttribute("href", url);
                } else {
                    let element = cloneBtnExpress.querySelector("[data-crear_entrada]");
                    if (element) {
                        element.style.display = 'none';
                    }
                }
            }
        }

        botones.append(cloneBtnExpress);
    }

    if ( data.type == "n" )
    {
        var cloneBtnNormal = activateTemplate('#template-normal');

        if ( $.inArray('list_orderPurchaseNormal', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/orden/compra/'+data.id;
            cloneBtnNormal.querySelector("[data-imprimir]").setAttribute("href", url);
        } else {
            let element = cloneBtnNormal.querySelector("[data-imprimir]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_orderPurchaseNormal', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/orden/compra/normal/'+data.id;
            cloneBtnNormal.querySelector("[data-ver_orden]").setAttribute("href", url);
        } else {
            let element = cloneBtnNormal.querySelector("[data-ver_orden]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if (data.regularize === 'nr') {
            if (data.status == 1) {
                let element = cloneBtnNormal.querySelector("[data-crear_entrada]");
                if (element) {
                    element.style.display = 'none';
                }

            } else if ( data.status == 0 ) {
                if ( $.inArray('create_entryPurchase', $permissions) !== -1 ) {
                    let url = document.location.origin+ '/dashboard/crear/entrada/compra/orden/'+data.id;
                    cloneBtnNormal.querySelector("[data-crear_entrada]").setAttribute("href", url);
                } else {
                    let element = cloneBtnNormal.querySelector("[data-crear_entrada]");
                    if (element) {
                        element.style.display = 'none';
                    }
                }
            } else if (data.status == 2) {
                if ( $.inArray('create_entryPurchase', $permissions) !== -1 ) {
                    let url = document.location.origin+ '/dashboard/crear/entrada/compra/orden/'+data.id;
                    cloneBtnNormal.querySelector("[data-crear_entrada]").setAttribute("href", url);
                } else {
                    let element = cloneBtnNormal.querySelector("[data-crear_entrada]");
                    if (element) {
                        element.style.display = 'none';
                    }
                }
            }
        }

        botones.append(cloneBtnNormal);
    }

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
