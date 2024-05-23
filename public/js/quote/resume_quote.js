$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);

    $('#btn-search').on('click', showDataSearch);
    $('#btn-download').on('click', downloadPDF);

});

var $formDelete;
var $modalDelete;
var $modalDecimals;
var $formDecimals;

var $permissions;
var $modalDetraction;

function downloadPDF() {
    var quote_id  = $('#quote').val();

    if ( quote_id == "" )
    {
        toastr.error('Seleccione una cotización.', 'Error',
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
        return;
    }

    $.get('/dashboard/get/info/resumen/quote/'+quote_id, function(data) {
        console.log(data);

        if ( data.havePDF == 1 )
        {
            $.confirm({
                icon: 'fas fa-file-excel',
                theme: 'modern',
                closeIcon: true,
                animation: 'zoom',
                type: 'green',
                title: '¿Desea descargar el PDF de materiales adjunto?',
                content: 'PDF encontrado',
                buttons: {
                    confirm: {
                        text: 'DESCARGAR',
                        action: function (e) {
                            var query = {
                                quote_id: quote_id
                            };

                            $.alert('Descargando archivo ...');

                            var url = "/dashboard/exportar/pdf/materiales/cotizaciones/v2/?" + $.param(query);

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
            toastr.error("Lo sentimos la cotización no cuenta con un PDF", 'Error', {
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

function showDataSearch() {
    getDataQuote()
}

function getDataQuote() {
    var quote = $('#quote').val();

    if (quote != "")
    {
        $.get('/dashboard/get/resumen/quote/', {
            quote:quote,

        }, function(data) {
            console.log(data);

            $("#body-summary").html('');
            $("#body-equipments").html('');
            $("#body-total").html('');

            if ( data.equipmentsOfQuote.length != 0 )
            {
                renderDataQuote(data);
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
    } else {
        toastr.error("Seleccione una cotización del listado", 'Error', {
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


}

function renderDataQuote(data) {
    var equipmentsOfQuote = data.equipmentsOfQuote;
    var resumenEquipments = data.resumenEquipments;
    var totalQuote = data.totalQuote;
    console.log(equipmentsOfQuote);
    console.log(resumenEquipments);
    console.log(totalQuote);

    for (let j = 0; j < equipmentsOfQuote.length ; j++) {
        //console.log(equipmentsOfQuote[j]);
        renderDataEquipmentsOfQuote(equipmentsOfQuote[j]);
    }

    for (let k = 0; k < resumenEquipments.length ; k++) {
        console.log(resumenEquipments[k]);
        renderDataResumenEquipments(resumenEquipments[k]);
    }

    renderDataTotalQuote(totalQuote);

    /*for (let j = 0; j < dataQuotes.length ; j++) {
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
    }*/
}

function renderDataTotalQuote(data) {
    var clone = activateTemplate('#template-total');
    clone.querySelector("[data-total_sin_igv]").innerHTML = data.total_sin_igv;
    clone.querySelector("[data-total_con_igv]").innerHTML = data.total_con_igv;
    clone.querySelector("[data-total_utilidad_sin_igv]").innerHTML = data.total_utilidad_sin_igv;
    clone.querySelector("[data-total_utilidad_con_igv]").innerHTML = data.total_utilidad_con_igv;
    $("#body-total").append(clone);
}

function renderDataResumenEquipments(data) {
    var clone = activateTemplate('#template-equipment');
    clone.querySelector("[data-equipment]").innerHTML = data.equipment;
    clone.querySelector("[data-total_materials]").innerHTML = data.total_materials;
    clone.querySelector("[data-total_consumables]").innerHTML = data.total_consumables;
    clone.querySelector("[data-total_electrics]").innerHTML = data.total_electrics;
    clone.querySelector("[data-total_workforces]").innerHTML = data.total_workforces;
    clone.querySelector("[data-total_tornos]").innerHTML = data.total_tornos;
    clone.querySelector("[data-total_dias]").innerHTML = data.total_dias;
    $("#body-equipments").append(clone);
}

function renderDataEquipmentsOfQuote(data) {
    var clone = activateTemplate('#template-summary');
    clone.querySelector("[data-equipo]").innerHTML = data.equipo;
    clone.querySelector("[data-cantidad]").innerHTML = data.cantidad;
    clone.querySelector("[data-subtotal_sin_igv]").innerHTML = data.subtotal_sin_igv;
    clone.querySelector("[data-utilidad]").innerHTML = data.utilidad;
    clone.querySelector("[data-gastos_varios]").innerHTML = data.gastos_varios;
    clone.querySelector("[data-precio_unit_sin_igv]").innerHTML = data.precio_unit_sin_igv;
    clone.querySelector("[data-total_sin_igv]").innerHTML = data.total_sin_igv;
    $("#body-summary").append(clone);
}

function renderDataTable(data) {
    var clone = activateTemplate('#item-table');
    clone.querySelector("[data-id]").innerHTML = data.id;
    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-description]").innerHTML = data.description;
    clone.querySelector("[data-date_quote]").innerHTML = data.date_quote;
    clone.querySelector("[data-date_validate]").innerHTML = data.date_validate;
    clone.querySelector("[data-deadline]").innerHTML = data.deadline;
    clone.querySelector("[data-time_delivery]").innerHTML = data.time_delivery;
    clone.querySelector("[data-customer]").innerHTML = data.customer;
    clone.querySelector("[data-order]").innerHTML = data.order;
    clone.querySelector("[data-total_igv]").innerHTML = data.total_igv;
    clone.querySelector("[data-total]").innerHTML = data.total;
    clone.querySelector("[data-currency]").innerHTML = data.currency;
    clone.querySelector("[data-state]").innerHTML = data.stateText;
    clone.querySelector("[data-created_at]").innerHTML = data.created_at;
    clone.querySelector("[data-creator]").innerHTML = data.creator;
    clone.querySelector("[data-decimals]").innerHTML = data.decimals;

    var botones = clone.querySelector("[data-buttons]");

    if ( data.state == "created" )
    {
        var cloneBtnCreated = activateTemplate('#template-btn_created');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnCreated.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnCreated.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnCreated.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnCreated.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('send_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-enviar]").setAttribute("data-send", data.id);
            cloneBtnCreated.querySelector("[data-enviar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-enviar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/cotizacion/'+data.id;
            cloneBtnCreated.querySelector("[data-editar]").setAttribute("href", url);
        } else {
            let element = cloneBtnCreated.querySelector("[data-editar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-confirmar]").setAttribute("data-status", data.send_state);
            cloneBtnCreated.querySelector("[data-confirmar]").setAttribute("data-confirm", data.id);
            cloneBtnCreated.querySelector("[data-confirmar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-confirmar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnCreated.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnCreated.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnCreated.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnCreated.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCreated.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnCreated);
    }

    if ( data.state == "send" )
    {
        var cloneBtnSend = activateTemplate('#template-btn_send');
        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnSend.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnSend.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnSend.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnSend.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/cotizacion/'+data.id;
            cloneBtnSend.querySelector("[data-editar]").setAttribute("href", url);
        } else {
            let element = cloneBtnSend.querySelector("[data-editar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
            cloneBtnSend.querySelector("[data-confirmar]").setAttribute("data-status", data.send_state);
            cloneBtnSend.querySelector("[data-confirmar]").setAttribute("data-confirm", data.id);
            cloneBtnSend.querySelector("[data-confirmar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnSend.querySelector("[data-confirmar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnSend.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnSend.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnSend.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnSend.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnSend.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnSend.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnSend.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnSend.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnSend.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnSend);
    }

    if ( data.state == "confirm" )
    {
        var cloneBtnConfirm = activateTemplate('#template-btn_confirm');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnConfirm.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnConfirm.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-regresar_enviado]").setAttribute("data-deselevar", data.id);
            cloneBtnConfirm.querySelector("[data-regresar_enviado]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-regresar_enviado]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('adjust_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ajustar/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-ajustar_porcentajes]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-ajustar_porcentajes]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-raise", data.id);
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-code", data.order);
            cloneBtnConfirm.querySelector("[data-elevar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-elevar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/cotizar/soles/cotizacion/'+data.id;
            cloneBtnConfirm.querySelector("[data-cotizar_soles]").setAttribute("href", url);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-cotizar_soles]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnConfirm.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnConfirm.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnConfirm.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnConfirm.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnConfirm.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnConfirm);
    }

    if ( data.state == "raise" )
    {
        var cloneBtnRaised = activateTemplate('#template-btn_raised');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnRaised.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnRaised.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnRaised.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnRaised.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-modificar_codigo]").setAttribute("data-raise2", data.id);
            cloneBtnRaised.querySelector("[data-modificar_codigo]").setAttribute("data-code", data.order);
            cloneBtnRaised.querySelector("[data-modificar_codigo]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-modificar_codigo]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-seleccionar_detraccion]").setAttribute("data-detraction", data.id);
            cloneBtnRaised.querySelector("[data-seleccionar_detraccion]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-seleccionar_detraccion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('finish_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-finalizar]").setAttribute("data-finish", data.id);
            cloneBtnRaised.querySelector("[data-finalizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-finalizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('replacement_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/reemplazar/materiales/cotizacion/'+data.id;
            cloneBtnRaised.querySelector("[data-reemplazar_materiales]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-reemplazar_materiales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('finishEquipment_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/finalizar/equipos/cotizacion/'+data.id;
            cloneBtnRaised.querySelector("[data-finalizar_equipos]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-finalizar_equipos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-regresar_enviado]").setAttribute("data-deselevar", data.id);
            cloneBtnRaised.querySelector("[data-regresar_enviado]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-regresar_enviado]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/cotizar/soles/cotizacion/'+data.id;
            cloneBtnRaised.querySelector("[data-cotizar_soles]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-cotizar_soles]");
            if (element) {
                element.style.display = 'none';
            }
        }

        /*if ( $.inArray('VBFinances_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-visto_bueno_finanzas]").setAttribute("data-vb_finances", data.id);
            cloneBtnRaised.querySelector("[data-visto_bueno_finanzas]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-visto_bueno_finanzas]");
            if (element) {
                element.style.display = 'none';
            }
        }*/

        if ( $.inArray('VBOperations_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-visto_bueno_operaciones]").setAttribute("data-vb_operations", data.id);
            cloneBtnRaised.querySelector("[data-visto_bueno_operaciones]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-visto_bueno_operaciones]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnRaised.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnRaised.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnRaised.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnRaised.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnRaised.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnRaised);
    }

    if ( data.state == "VB_finance" )
    {
        var cloneBtnVB_finance = activateTemplate('#template-btn_VB_finance');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnVB_finance.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        /*if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/editar/planos/cotizacion/'+data.id;
            cloneBtnRaised.querySelector("[data-editar_planos]").setAttribute("href", url);
        } else {
            let element = cloneBtnRaised.querySelector("[data-editar_planos]");
            if (element) {
                element.style.display = 'none';
            }
        }*/

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnVB_finance.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnVB_finance.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnVB_finance.querySelector("[data-modificar_codigo]").setAttribute("data-raise2", data.id);
            cloneBtnVB_finance.querySelector("[data-modificar_codigo]").setAttribute("data-code", data.order);
            cloneBtnVB_finance.querySelector("[data-modificar_codigo]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-modificar_codigo]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnVB_finance.querySelector("[data-seleccionar_detraccion]").setAttribute("data-detraction", data.id);
            cloneBtnVB_finance.querySelector("[data-seleccionar_detraccion]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-seleccionar_detraccion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('finish_quote', $permissions) !== -1 ) {
            cloneBtnVB_finance.querySelector("[data-finalizar]").setAttribute("data-finish", data.id);
            cloneBtnVB_finance.querySelector("[data-finalizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-finalizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('replacement_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/reemplazar/materiales/cotizacion/'+data.id;
            cloneBtnVB_finance.querySelector("[data-reemplazar_materiales]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-reemplazar_materiales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('finishEquipment_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/finalizar/equipos/cotizacion/'+data.id;
            cloneBtnVB_finance.querySelector("[data-finalizar_equipos]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-finalizar_equipos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnVB_finance.querySelector("[data-regresar_enviado]").setAttribute("data-deselevar", data.id);
            cloneBtnVB_finance.querySelector("[data-regresar_enviado]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-regresar_enviado]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('VBOperations_quote', $permissions) !== -1 ) {
            cloneBtnVB_finance.querySelector("[data-visto_bueno_operaciones]").setAttribute("data-vb_operations", data.id);
            cloneBtnVB_finance.querySelector("[data-visto_bueno_operaciones]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-visto_bueno_operaciones]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnVB_finance.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnVB_finance.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnVB_finance.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnVB_finance.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnVB_finance.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnVB_finance.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_finance.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnVB_finance);
    }

    if ( data.state == "VB_operation" )
    {
        var cloneBtnVB_operation = activateTemplate('#template-btn_VB_operation');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnVB_operation.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnVB_operation.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/interno/'+data.id;
            cloneBtnVB_operation.querySelector("[data-imprimir_interna]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-imprimir_interna]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnVB_operation.querySelector("[data-modificar_codigo]").setAttribute("data-raise2", data.id);
            cloneBtnVB_operation.querySelector("[data-modificar_codigo]").setAttribute("data-code", data.order);
            cloneBtnVB_operation.querySelector("[data-modificar_codigo]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-modificar_codigo]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnVB_operation.querySelector("[data-seleccionar_detraccion]").setAttribute("data-detraction", data.id);
            cloneBtnVB_operation.querySelector("[data-seleccionar_detraccion]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-seleccionar_detraccion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('finish_quote', $permissions) !== -1 ) {
            cloneBtnVB_operation.querySelector("[data-finalizar]").setAttribute("data-finish", data.id);
            cloneBtnVB_operation.querySelector("[data-finalizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-finalizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('finishEquipment_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/finalizar/equipos/cotizacion/'+data.id;
            cloneBtnVB_operation.querySelector("[data-finalizar_equipos]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-finalizar_equipos]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('update_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/modificar/lista/materiales/cotizacion/'+data.id;
            cloneBtnVB_operation.querySelector("[data-modificar_lista_materiales]").setAttribute("href", url);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-modificar_lista_materiales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnVB_operation.querySelector("[data-regresar_enviado]").setAttribute("data-deselevar", data.id);
            cloneBtnVB_operation.querySelector("[data-regresar_enviado]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-regresar_enviado]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
            cloneBtnVB_operation.querySelector("[data-anular]").setAttribute("data-delete", data.id);
            cloneBtnVB_operation.querySelector("[data-anular]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-anular]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnVB_operation.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnVB_operation.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('list_quote', $permissions) !== -1 ) {
            cloneBtnVB_operation.querySelector("[data-decimales]").setAttribute("data-decimals", data.id);
            cloneBtnVB_operation.querySelector("[data-decimales]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnVB_operation.querySelector("[data-decimales]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnVB_operation);
    }

    if ( data.state == "close" )
    {
        var cloneBtnClose = activateTemplate('#template-btn_close');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnClose.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnClose.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/imprimir/cliente/'+data.id;
            cloneBtnClose.querySelector("[data-imprimir_cliente]").setAttribute("href", url);
        } else {
            let element = cloneBtnClose.querySelector("[data-imprimir_cliente]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
            cloneBtnClose.querySelector("[data-modificar_codigo]").setAttribute("data-raise2", data.id);
            cloneBtnClose.querySelector("[data-modificar_codigo]").setAttribute("data-code", data.order);
            cloneBtnClose.querySelector("[data-modificar_codigo]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnClose.querySelector("[data-modificar_codigo]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnClose.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnClose.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnClose.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('finish_quote', $permissions) !== -1 ) {
            cloneBtnClose.querySelector("[data-reactivar]").setAttribute("data-active", data.id);
            cloneBtnClose.querySelector("[data-reactivar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnClose.querySelector("[data-reactivar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnClose);
    }

    if ( data.state == "canceled" ) {
        var cloneBtnCanceled = activateTemplate('#template-btn_canceled');

        if ( $.inArray('show_quote', $permissions) !== -1 ) {
            let url = document.location.origin+ '/dashboard/ver/cotizacion/'+data.id;
            cloneBtnCanceled.querySelector("[data-ver_cotizacion]").setAttribute("href", url);
        } else {
            let element = cloneBtnCanceled.querySelector("[data-ver_cotizacion]");
            if (element) {
                element.style.display = 'none';
            }
        }

        if ( $.inArray('renew_quote', $permissions) !== -1 ) {
            cloneBtnCanceled.querySelector("[data-recotizar]").setAttribute("data-renew", data.id);
            cloneBtnCanceled.querySelector("[data-recotizar]").setAttribute("data-name", data.description);
        } else {
            let element = cloneBtnCanceled.querySelector("[data-recotizar]");
            if (element) {
                element.style.display = 'none';
            }
        }

        botones.append(cloneBtnCanceled);
    }

    /*clone.querySelector("[data-formEditFacturacion]").setAttribute('data-formEditFacturacion', data.id);
    clone.querySelector("[data-formEditFacturacion]").setAttribute('data-type', data.type);*/

    $("#body-table").append(clone);

    $('[data-toggle="tooltip"]').tooltip();
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}