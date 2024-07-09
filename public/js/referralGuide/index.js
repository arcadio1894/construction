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

    getDataGuides(1);

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
    });

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSearch);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('click', '[data-delete]', cancelGuide);

    $('#btn-download').on('click', exportExcel);

});

var $formDelete;
var $modalDelete;
var $modalDecimals;
var $formDecimals;

var $permissions;
var $modalDetraction;

function cancelGuide() {
    var guide_id = $(this).data('delete');
    var code = $(this).data('name');

    $.confirm({
        icon: 'fas fa-frown',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: '¿Está seguro de anular esta guía de remisión?',
        content: code,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/destroy/guide/referral/'+guide_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Guía de remisión anulada.");
                            setTimeout( function () {
                                /*location.reload();*/
                                getDataGuides(1);
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Anulación cancelada.");
                },
            },
        },
    });

}

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
            content: 'Si no hay fechas se descargará todas las guías de remisión',
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

                        var url = "/dashboard/exportar/guias/remision/v2/?" + $.param(query);

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

        var url = "/dashboard/exportar/guias/remision/v2/?" + $.param(query);

        window.location = url;

    }

}

function showDataSearch() {
    getDataGuides(1)
}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataGuides(numberPage)
}

function getDataGuides($numberPage) {
    $('[data-toggle="tooltip"]').tooltip('dispose').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    var code = $('#code').val();
    var year = $('#year').val();
    var reason = $('#reason').val();
    var responsible = $('#responsible').val();
    var state = $('#state').val();
    var customer = $('#customer').val();
    var supplier = $('#supplier').val();
    var receiver = $('#receiver').val();
    var startDate = $('#start').val();
    var endDate = $('#end').val();

    $.get('/dashboard/get/data/referral/guides/'+$numberPage, {
        code: code,
        year: year,
        reason:reason,
        responsible: responsible,
        state: state,
        customer: customer,
        supplier: supplier,
        receiver: receiver,
        startDate: startDate,
        endDate: endDate,
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataGuidesEmpty(data);
        } else {
            renderDataGuides(data);
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

function renderDataGuidesEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' guías de remisión');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataGuides(data) {
    var dataQuotes = data.data;
    var pagination = data.pagination;

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' guías de remisión.');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    for (let j = 0; j < dataQuotes.length ; j++) {
        renderDataTable(dataQuotes[j]);
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
   /* clone.querySelector("[data-id]").innerHTML = data.id;*/
    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-date_transfer]").innerHTML = data.date_transfer;
    clone.querySelector("[data-reason]").innerHTML = data.reason;
    clone.querySelector("[data-destination]").innerHTML = data.destinatario;
    clone.querySelector("[data-document]").innerHTML = data.documento;
    clone.querySelector("[data-puntoLlegada]").innerHTML = data.punto_llegada;
    clone.querySelector("[data-placa]").innerHTML = data.vehiculo;
    clone.querySelector("[data-conductor]").innerHTML = data.driver;
    clone.querySelector("[data-licencia]").innerHTML = data.driver_licence;
    clone.querySelector("[data-responsable]").innerHTML = data.responsible;
    clone.querySelector("[data-state]").innerHTML = data.enabled_status;

    var botones = clone.querySelector("[data-buttons]");

    if ( data.state == "1" )
    {
        var cloneBtnActive = activateTemplate('#template-active');

        if ( $.inArray('list_referralGuide', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/guia/remision/'+data.id;
            cloneBtnActive.querySelector("[data-ver_guide]").setAttribute("href", url);
        } else {
            let element = cloneBtnActive.querySelector("[data-ver_guide]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('print_referralGuide', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/guia/remision/'+data.id;
            cloneBtnActive.querySelector("[data-imprimir_guide]").setAttribute("href", url);
        } else {
            let element = cloneBtnActive.querySelector("[data-imprimir_guide]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('edit_referralGuide', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/guia/de/remision/'+data.id;
            cloneBtnActive.querySelector("[data-editar]").setAttribute("href", url);
        } else {
            let element = cloneBtnActive.querySelector("[data-editar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_referralGuide', $permissions) !== -1 ) {
            cloneBtnActive.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnActive.querySelector("[data-anular]").setAttribute("data-name", data.code);
        } else {
            let element = cloneBtnActive.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnActive);
    }

    if ( data.state == "0" )
    {
        var cloneBtnInactive = activateTemplate('#template-inactive');

        if ( $.inArray('list_referralGuide', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/guia/remision/'+data.id;
            cloneBtnInactive.querySelector("[data-ver_guide]").setAttribute("href", url);
        } else {
            let element = cloneBtnInactive.querySelector("[data-ver_guide]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('print_referralGuide', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/guia/remision/'+data.id;
            cloneBtnInactive.querySelector("[data-imprimir_guide]").setAttribute("href", url);
        } else {
            let element = cloneBtnInactive.querySelector("[data-imprimir_guide]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnInactive);
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

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}