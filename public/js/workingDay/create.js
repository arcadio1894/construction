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


    $('#newWorkingDay').on('click', addWorkingDay);

    $(document).on('click','[data-save]', saveWorkingDay);

    $(document).on('click','[data-delete]', deleteWorkingDay);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

});

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function saveWorkingDay() {
    event.preventDefault();
    var id_workingDay = $(this).data('save');

    var description = $(this).parent().parent().children().children().children().next().val();
    var time_start = $(this).parent().parent().children().next().children().children().data('time');
    var time_fin = $(this).parent().parent().children().next().next().children().children().data('time');
    var div_enable = $(this).parent().prev().children().children();

    //console.log(div_enable.find('[data-enable]').bootstrapSwitch('state'));

    var enable = div_enable.find('[data-enable]').bootstrapSwitch('state');

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de guardar los datos de la jornada?',
        content: 'Se guardará los datos de la jornada.',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/update/working/day/'+id_workingDay,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: JSON.stringify({ description: description, time_start:time_start, time_fin:time_fin, enable:enable }),
                processData:false,
                contentType:'application/json; charset=utf-8',
                success: function (data) {
                    console.log(data);
                    vdialog.success(data.message);
                    // Aqui se renderizará y se colocará los datos de la actividad
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

        },
        cancel: function(){
            vdialog.alert('Jornada no guardada');

        }
    });
}

function deleteWorkingDay() {
    event.preventDefault();
    var id_workingDay = $(this).data('delete');

    var card = $(this).parent().parent().parent();

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de eliminar los datos de la jornada?',
        content: 'Se eliminará los datos de la jornada. Si desea solo inhabilite la jornada',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/destroy/working/day/'+id_workingDay,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                processData:false,
                contentType:'application/json; charset=utf-8',
                success: function (data) {
                    console.log(data);
                    vdialog.success(data.message);
                    card.remove();
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

        },
        cancel: function(){
            vdialog.alert('Jornada no eliminada');

        }
    });
}

function addWorkingDay() {
    event.preventDefault();
    var button = $(this);
    button.attr("disabled", true);

    $.ajax({
        url: '/dashboard/create/working/day/1',
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData:false,
        contentType:'application/json; charset=utf-8',
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
            // Aqui se renderizará y se colocará los datos de la actividad
            //renderTemplateActivity(id_timeline, data.activity.id);
            renderTemplateWorkingDay( data.workingDay );

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });

            $('.timepicker').mdtimepicker({
                format:'h:mm tt',
                theme:'blue',
                readOnly:true,
                hourPadding:false,
                clearBtn:false

            });
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

function renderTemplateWorkingDay( workingDay ) {
    var clone = activateTemplate('#template-workingday');

    clone.querySelector("[data-save]").setAttribute('data-save', workingDay.id);
    clone.querySelector("[data-delete]").setAttribute('data-delete', workingDay.id);

    $('#body-workingDay').append(clone);

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

