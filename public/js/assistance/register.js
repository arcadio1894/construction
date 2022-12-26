let $permissions;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('click', '[data-save]', saveAssistance);

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
                    processData:false,
                    contentType:'application/json; charset=utf-8',
                    success: function (data) {
                        console.log(data);
                        vdialog.success(data.message);
                        // Actualizar la assitanceDetail
                        button.attr('data-assistancedetail', data.assistanceDetail.id);
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
                    processData:false,
                    contentType:'application/json; charset=utf-8',
                    success: function (data) {
                        console.log(data);
                        vdialog.success(data.message);

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

