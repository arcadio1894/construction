let $value_assign_family;
let $value_essalud;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $value_assign_family = parseFloat($("#value_assign_family").val());
    $value_essalud = parseFloat($("#essalud").val());

    $(document).on('input', '[id=num_children]', function() {
        var children = parseInt($(this).val());
        var assign_family = 0;
        if (children > 0)
        {
            // Seteamos la asignacion familiar
            assign_family = $value_assign_family/30;
        }
        $("#assign_family").val(assign_family.toFixed(2));

        // Verificamos el salario diario
        var salario_diario = parseFloat($("#daily_salary").val());

        var pago_diario = (assign_family + salario_diario).toFixed(2);

        $("#pay_daily").val(pago_diario);

        // Verificamos el salario mensual
        var salario_mensual = parseFloat(pago_diario*30).toFixed(2);

        $("#monthly_salary").val(salario_mensual);
    });

    $(document).on('input', '[id=daily_salary]', function() {
        var children = parseInt($("#num_children").val());
        var assign_family = 0;
        if (children > 0)
        {
            // Seteamos la asignacion familiar
            assign_family = $value_assign_family/30;
        }
        $("#assign_family").val(assign_family.toFixed(2));

        // Verificamos el salario diario
        var salario_diario = parseFloat($(this).val());

        var pago_diario = (assign_family + salario_diario).toFixed(2);

        $("#pay_daily").val(pago_diario);

        // Verificamos el salario mensual
        var salario_mensual = parseFloat(pago_diario*30).toFixed(2);

        $("#monthly_salary").val(salario_mensual);
    });

    $(document).on('select2:select', '.pension_system', function (e) {
        // Do something
        $("#percentage_system_pension").val('');
        var data = $(this).select2('data');
        console.log( data[0].element.dataset.percentage );
        var percentage = data[0].element.dataset.percentage;
        $("#percentage_system_pension").val(percentage);

    });

    $formCreate = $('#formCreate');
    $("#btn-submit").on("click", storeQuote);



});

var $formCreate;

function storeQuote() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);

    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var formulario = $('#formCreate')[0];
    var form = new FormData(formulario);

    $.ajax({
        url: createUrl,
        method: 'POST',
        data: form,
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
            setTimeout( function () {
                $("#btn-submit").attr("disabled", false);
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
            $("#btn-submit").attr("disabled", false);

        },
    });
}
