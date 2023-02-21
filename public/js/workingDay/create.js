let $value_assign_family;
let $value_essalud;
let $permissions;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $('#newWorkingDay').on('click', addWorkingDay);

    $(document).on('click','[data-save]', saveWorkingDay);

    $(document).on('click','[data-delete]', deleteWorkingDay);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('.datestart').on('timechanged', function(e){
        var card = $(this).parent().next().next().next().children();
        console.log(card);
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });
    $('.dateend').on('timechanged', function(e){
        var card = $(this).parent().next().next().children();
        console.log(card);
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });
    $(document).on('input', '[data-description]', function() {
        var card = $(this).parent().parent().next().next().next().next().children();
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });
    $('.checkbox').on('switchChange.bootstrapSwitch', function (event, state) {
        console.log($(this));
        var card = $(this).parent().parent().parent().parent().next().children();
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });

});

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function saveWorkingDay() {
    event.preventDefault();
    var button = $(this);
    var id_workingDay = $(this).data('save');

    var description = $(this).parent().parent().children().children().children().next().val();
    var time_start = $(this).parent().parent().children().next().children().children().attr('data-time');
    var time_fin = $(this).parent().parent().children().next().next().children().children().attr('data-time');
    var div_enable = $(this).parent().prev().children().children();

    console.log(time_start);
    console.log(time_fin);
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
                    button.tooltip('hide');
                    button.attr("disabled", false);
                    button.removeClass('btn-outline-warning');
                    button.addClass('btn-outline-success');
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
                    button.tooltip('hide');
                    button.attr("disabled", false);
                },
            });

        },
        cancel: function(){
            vdialog.alert('Jornada no guardada');
            button.tooltip('hide');
            button.attr("disabled", false);
        }
    });
}

function deleteWorkingDay() {
    event.preventDefault();
    var button = $(this);
    button.attr("disabled", true);
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
                    button.attr("disabled", false);
                    button.tooltip('hide');
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
                    button.tooltip('hide');
                    button.attr("disabled", false);
                },
            });

        },
        cancel: function(){
            vdialog.alert('Jornada no eliminada');
            button.tooltip('hide');
            button.attr("disabled", false);
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
                timeFormat: 'hh:mm:ss.000',
                format:'hh:mm tt',
                theme:'blue',
                readOnly:true,
                hourPadding:true,
                clearBtn:false,
                is24hour: false,
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

