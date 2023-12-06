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

    getDataProformas(1);

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSeach);

    $(document).on('click', '[data-delete]', deleteProforma);

    $(document).on('click', '[data-confirm]', confirmProforma);

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
    });

});

function confirmProforma() {
    var button = $(this);
    var id = button.data('confirm');
    var description = $(this).data('description');
    $.confirm({
        icon: 'fas fa-check-double',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'small',
        title: '¿Esta seguro de dar el visto bueno a esta pre cotización?',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: "/dashboard/visto/bueno/proforma/" + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (json) {
                            $.alert(json.message);
                            setTimeout( function () {
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

                        },
                    });
                    //
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Visto bueno cancelado.");

                },
            },
        }
    });
}

function deleteProforma() {
    var button = $(this);
    var id = button.data('delete');
    var description = $(this).data('description');
    $.confirm({
        icon: 'far fa-trash',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        columnClass: 'small',
        title: '¿Esta seguro de eliminar esta pre cotización?',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: "/dashboard/destroy/proforma/" + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function (json) {
                            $.alert(json.message);
                            setTimeout( function () {
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

                        },
                    });
                    //
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Eliminación cancelada.");

                },
            },
        }
    });
}

function showDataSeach() {
    getDataDefaultEquipments(1)
}

function showData() {
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataDefaultEquipments(numberPage)
}

function getDataProformas($numberPage) {
    var categoryEquipmentid = $('#inputCategoryEquipmentid').val();
    var largeDefaultEquipment = $('#inputLarge').val(); 
    var widthDefaultEquipment = $('#inputWidth').val(); 
    var highDefaultEquipment = $('#inputHigh').val();

    $.get('/dashboard/get/data/proformas/'+$numberPage, {
        category_Equipment_id:categoryEquipmentid,
        large_Default_Equipment: largeDefaultEquipment,
        width_Default_Equipment: widthDefaultEquipment,
        high_Default_Equipment: highDefaultEquipment
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataProformaEmpty(data);
        } else {
            renderDataProformas(data);
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


function renderDataProformaEmpty(data) {
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

function renderDataProformas(data) {
    var dataProformas = data.data;
    var pagination = data.pagination;
    console.log(dataProformas);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' Pre Cotizaciones');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    for (let j = 0; j < dataProformas.length ; j++) {
        renderDataTable(dataProformas[j]);
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
    clone.querySelector("[data-description]").innerHTML = data.description;
    clone.querySelector("[data-date_quote]").innerHTML = data.date_quote;
    clone.querySelector("[data-date_validate]").innerHTML = data.date_validate;
    clone.querySelector("[data-deadline]").innerHTML = data.deadline;
    clone.querySelector("[data-delivery_time]").innerHTML = data.delivery_time;
    clone.querySelector("[data-customer]").innerHTML = data.customer;
    clone.querySelector("[data-total_con_igv]").innerHTML = data.total_con_igv;
    clone.querySelector("[data-total_sin_igv]").innerHTML = data.total_sin_igv;
    clone.querySelector("[data-currency]").innerHTML = data.currency;
    clone.querySelector("[data-state]").innerHTML = data.state;
    clone.querySelector("[data-created_at]").innerHTML = data.created_at;
    clone.querySelector("[data-creator]").innerHTML = data.creator;

    clone.querySelector("[data-show]").setAttribute('href', location.origin+'/dashboard/ver/pre/cotizacion/'+data.id);

    clone.querySelector("[data-print]").setAttribute('href', document.location.origin + '/dashboard/imprimir/proforma/cliente/' + data.id);

    clone.querySelector("[data-confirm]").setAttribute('data-confirm', data.id);
    clone.querySelector("[data-confirm]").setAttribute('data-description', data.description);

    clone.querySelector("[data-delete]").setAttribute('data-delete', data.id);
    clone.querySelector("[data-delete]").setAttribute('data-description', data.description);

    clone.querySelector("[data-edit]").setAttribute('href', location.origin+'/dashboard/editar/pre/cotizacion/'+data.id);

    if ( data.estado == 'destroy' )
    {
        let element = clone.querySelector("[data-confirm]");
        if (element) {
            element.style.display = 'none';
        }
        let elemento = clone.querySelector("[data-delete]");
        if (elemento) {
            elemento.style.display = 'none';
        }
        let elemento2 = clone.querySelector("[data-edit]");
        if (elemento2) {
            elemento2.style.display = 'none';
        }
    }

    if ( data.estado == 'confirmed' )
    {
        var element = clone.querySelector("[data-confirm]");
        if (element) {
            element.style.display = 'none';
        }
        var elemento2 = clone.querySelector("[data-edit]");
        if (elemento2) {
            elemento2.style.display = 'none';
        }
    }
    /*clone.querySelector("[data-confirm]").setAttribute('data-confirm', data.id);
    clone.querySelector("[data-confirm]").setAttribute('data-description', data.description);
*/
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

