$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $('#newAccount').on('click', addNewAccount);

    $(document).on('click', '[data-updateaccount]', updateAccount);
    $(document).on('click', '[data-deleteaccount]', deleteAccount);

    $(document).on('select2:select', '.bank', function (e) {
        var card = $(this).parent().parent().next().next().children().children().next();
        card.removeClass('btn-outline-warning');
        card.addClass('btn-outline-secondary');
    });

    $(document).on('select2:select', '.currency', function (e) {
        var card = $(this).parent().parent().next().children().children().next();
        card.removeClass('btn-outline-warning');
        card.addClass('btn-outline-secondary');
    });

    $(document).on('input', '[data-number_account]', function() {
        var card = $(this).parent().parent().next().next().next().children().children().next();
        card.removeClass('btn-outline-warning');
        card.addClass('btn-outline-secondary');
    });

});

var $formCreate;

function deleteAccount() {
    var button = $(this);
    var account_id = $(this).attr('data-deleteAccount');
    var account_number = $(this).attr('data-number');
    button.attr("disabled", true);
    $.confirm({
        icon: 'far fa-times-circle',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: '¿Esta seguro de eliminar esta cuenta?',
        content: account_number,
        buttons: {
            confirm: {
                text: 'ELIMINAR',
                action: function (e) {
                    //$.alert('Descargado igual');
                    $.ajax({
                        url: "/dashboard/worker/account/destroy/"+account_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            /*toastr.success(data.message, 'Éxito',
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
                                });*/
                            $.alert(data.message);
                            button.parent().parent().parent().parent().remove();
                            button.attr("disabled", false);

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
                            button.attr("disabled", false);

                        },
                    });

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Eliminación cancelada.");
                    button.attr("disabled", false);
                },
            },
        },
    });
}

function updateAccount() {
    var button = $(this);
    var account_id = button.attr('data-updateAccount');
    var number_account = button.parent().parent().prev().prev().prev().children().next().children().next().val();
    var bank_id = button.parent().parent().prev().prev().children().children().next().val();
    var currency = button.parent().parent().prev().children().children().next().val();

    button.attr("disabled", true);
    $.confirm({
        icon: 'far fa-save',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'orange',
        title: '¿Esta seguro de guardar los cambios?',
        content: "Se guardará en la base de datos",
        buttons: {
            confirm: {
                text: 'GUARDAR',
                action: function (e) {
                    $.ajax({
                        url: "/dashboard/worker/account/update/"+account_id,
                        method: 'POST',
                        data:{account_id:account_id, number_account:number_account, bank_id:bank_id, currency:currency},
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            button.attr("disabled", false);
                            button.removeClass('btn-outline-secondary');
                            button.addClass('btn-outline-warning');

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

                            button.attr("disabled", false);

                        },
                    });

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Modificación cancelada.");
                    button.attr("disabled", false);
                },
            },
        },
    });
}

function addNewAccount() {
    var button = $(this);
    var createUrl = $(this).attr('data-url');
    button.attr("disabled", true);
    $.ajax({
        url: createUrl,
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
            renderTemplateAccount(data.account);
            button.attr("disabled", false);

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
            button.attr("disabled", false);

        },
    });


}

function renderTemplateAccount(account) {
    var clone = activateTemplate('#template-account');

    clone.querySelector("[data-number_account]").innerHTML = account.number_account;
    var selectBank = clone.querySelector("[data-bank]");
    selectBank.setAttribute('value', account.bank_id);

    var selectCurrency = clone.querySelector("[data-currency]");
    selectCurrency.setAttribute('value', account.currency);

    clone.querySelector("[data-updateAccount]").setAttribute('data-updateAccount', account.id);
    clone.querySelector("[data-deleteAccount]").setAttribute('data-deleteAccount', account.id);

    $('#body-accounts').append(clone);

    $('.bank').select2({
        placeholder: "Seleccione un banco",
    });
    $('.currency').select2({
        placeholder: "Seleccione una moneda",
    });
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}