let $permissions;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('click', '[data-save]', saveAssistance);

    $('#timepicker').on('timechanged', function(e){
        var card = $('#timepicker').parent().next().next().next().next().children();
        console.log(card);
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });
    $('#timepicker2').on('timechanged', function(e){
        var card = $('#timepicker2').parent().next().next().next().children();
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });

    $(document).on('select2:select', '.state', function (e) {
        var card = $(this).parent().next().next().children();
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });

    $(document).on('select2:select', '.workingDays', function (e) {
        var card = $(this).parent().next().next().next().next().next().children();
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });

    $(document).on('input', '[data-observacion]', function() {
        var card = $(this).parent().next().children();
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-warning');
    });

});

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function saveAssistance() {
    event.preventDefault();
    var button = $(this);
    var assistanceDetail = $(this).attr('data-assistancedetail');
    var worker_id = $(this).attr('data-worker');
    var assistance_id = $('#assistance_id').val();

    // Datos a guardar
    var name_worker = button.parent().prev().prev().prev().prev().prev().prev().children().val();
    var working_day = button.parent().prev().prev().prev().prev().prev().children().val();
    var time_entry = button.parent().prev().prev().prev().prev().children().children().attr('data-time');
    var time_out = button.parent().prev().prev().prev().children().children().attr('data-time');
    var status = button.parent().prev().prev().children().val();
    var obs_justification = button.parent().prev().children().val();

    /*console.log(name_worker);
    console.log(working_day);
    console.log(time_entry);
    console.log(time_out);
    console.log(status);
    console.log(obs_justification);
    console.log(assistanceDetail);
    console.log(worker_id);
    console.log(assistance_id);*/

    if ( assistanceDetail == '' )
    {
        // No ha habido asistencia hasta ahora
        vdialog({
            type:'alert',// alert, success, error, confirm
            title: '¿Esta seguro de guardar la asistencia del trabajador?',
            content: 'Se guardará todos los datos de esta asistencia',
            okValue:'Aceptar',
            modal:true,
            cancelValue:'Cancelar',
            ok: function(){

                $.ajax({
                    url: '/dashboard/store/assistance/'+assistance_id+'/worker/'+worker_id,
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: JSON.stringify({ name_worker: name_worker, working_day:working_day, time_entry:time_entry, time_out:time_out, status:status, obs_justification:obs_justification }),
                    processData:false,
                    contentType:'application/json; charset=utf-8',
                    success: function (data) {
                        console.log(data);
                        vdialog.success(data.message);
                        // Actualizar la assitanceDetail
                        button.attr('data-assistancedetail', data.assistanceDetail.id);
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
                    },
                });

            },
            cancel: function(){
                vdialog.alert('Asistencia no guardada');

            }
        });
    } else {
        vdialog({
            type:'alert',// alert, success, error, confirm
            title: '¿Esta seguro de modificar esta asistencia?',
            content: 'Se guardarán los datos actualizados',
            okValue:'Aceptar',
            modal:true,
            cancelValue:'Cancelar',
            ok: function(){

                $.ajax({
                    url: '/dashboard/update/assistance/detail/'+assistanceDetail,
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: JSON.stringify({ name_worker: name_worker, working_day:working_day, time_entry:time_entry, time_out:time_out, status:status, obs_justification:obs_justification }),
                    processData:false,
                    contentType:'application/json; charset=utf-8',
                    success: function (data) {
                        console.log(data);
                        vdialog.success(data.message);
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
                    },
                });

            },
            cancel: function(){
                vdialog.alert('Asistencia no actualizada');

            }
        });
    }
}

