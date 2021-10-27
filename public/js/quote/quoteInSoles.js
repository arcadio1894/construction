$(document).ready(function () {
    $(document).on('click', '#btn-submit', quoteInSoles);
});

var $formDelete;
var $modalDelete;

var $permissions;

function quoteInSoles() {
    $("#btn-submit").attr("disabled", true);
    var quote_id = $('#idQuote').val();

    $.confirm({
        icon: 'fa fa-money-bill',
        theme: 'modern',
        closeIcon: false,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de recotizar a soles?',
        content: 'Se aplicará la tasa de cambio de la fecha de cotización',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/quote/in/soles/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización convertida a soles.");
                            $("#btn-submit").attr("disabled", false);
                            setTimeout( function () {
                                location.reload();
                            }, 2000 );

                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                            $("#btn-submit").attr("disabled", false);
                        },
                    });
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Cotización no convertida.");
                    $("#btn-submit").attr("disabled", false);
                },
            },
        }
    });

}
