let $formEdit;
let $modalEdit;
let $permissions;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $('#newRegime').on('click', addRegime);
    $("#btn-saveDetails").on('click', saveDetails);

    $modalEdit = $("#modalEdit");
    $formEdit = $("#formEdit");

    $(document).on('click','[data-save]', saveRegime);

    $(document).on('click','[data-edit]', showModalEdit);

    $(document).on('click','[data-delete]', deleteRegime);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('input', '[data-name]', function() {
        var card = $(this).parent().parent().next().next().next().children().next().next().first();
        console.log(card);

        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-dark');
    });

    $(document).on('input', '[data-description]', function() {
        var card = $(this).parent().parent().next().next().children().next().next().first();
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-dark');
    });
    $('.checkbox').on('switchChange.bootstrapSwitch', function (event, state) {
        console.log($(this));
        var card = $(this).parent().parent().parent().parent().next().children().next().next().first();
        card.removeClass('btn-outline-success');
        card.addClass('btn-outline-dark');
    });

});

function saveDetails() {
    event.preventDefault();
    var button = $(this);
    button.attr("disabled", true);
    console.log(button);
    var id_regime = $('#id_regime').val();

    var formulario = $('#formEdit')[0];
    var form = new FormData(formulario);

    $modalEdit.modal('hide');

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de guardar los horarios del régimen de trabajo?',
        content: 'Se guardará los horarios del régimen de trabajo.',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/update/details/regime/'+id_regime,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: form,
                processData:false,
                contentType:false,
                success: function (data) {
                    console.log(data);
                    vdialog.success(data.message);
                    button.tooltip('hide');
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
                    button.tooltip('hide');
                    button.attr("disabled", false);
                },
            });

        },
        cancel: function(){
            vdialog.alert('Detalles de régimen no guardados');
            button.tooltip('hide');
            button.attr("disabled", false);
        }
    });
}

function showModalEdit() {

    var id_regime = $(this).attr('data-edit');
    $.ajax({
        url: "/dashboard/get/workings/day/by/regime/"+id_regime,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var regime = json.regime;
            var details = json.regime.details;
            console.log(details);
            $('#id_regime').val(regime.id);
            $("#body-details").html('');
            for (var i=0; i<details.length; i++)
            {
                renderTemplateDetailRegime(details[i]);
            }
        }
    });

    $modalEdit.modal('show');
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function saveRegime() {
    event.preventDefault();
    var button = $(this);
    button.attr("disabled", true);
    console.log(button);
    var id_regime = $(this).data('save');

    var name = $(this).parent().parent().children().children().next().children().next().val();
    var description = $(this).parent().parent().children().next().children().next().children().next().val();
    var div_active = $(this).parent().prev().children().children().next();

    var active = div_active.find('[data-active]').bootstrapSwitch('state');

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de guardar los datos del régimen de trabajo?',
        content: 'Se guardará los datos del régimen de trabajo.',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/update/regime/'+id_regime,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: JSON.stringify({ description: description, name:name, active:active }),
                processData:false,
                contentType:'application/json; charset=utf-8',
                success: function (data) {
                    console.log(data);
                    vdialog.success(data.message);
                    button.tooltip('hide');
                    button.attr("disabled", false);
                    button.removeClass('btn-outline-dark');
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
            vdialog.alert('Régimen no guardada');
            button.tooltip('hide');
            button.attr("disabled", false);
        }
    });
}

function deleteRegime() {
    event.preventDefault();
    var button = $(this);
    button.attr("disabled", true);
    var id_regime = $(this).data('delete');

    var card = $(this).parent().parent().parent();

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de eliminar los datos del régimen?',
        content: 'Se eliminará los datos del régimen. Si desea solo inhabilite el régimen',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/destroy/regime/'+id_regime,
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
            vdialog.alert('Régimen no eliminado');
            button.tooltip('hide');
            button.attr("disabled", false);
        }
    });
}

function addRegime() {
    event.preventDefault();
    var button = $(this);
    button.attr("disabled", true);

    $.ajax({
        url: '/dashboard/create/regime',
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
            renderTemplateRegime( data.regime );

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

function renderTemplateDetailRegime(detail) {
    var clone = activateTemplate('#template-detail');

    clone.querySelector("[data-daynum]").setAttribute('data-daynum', detail.dayNumber);
    clone.querySelector("[data-day]").setAttribute('data-day', detail.dayName);
    clone.querySelector("[data-daynum]").setAttribute('value', detail.dayNumber);
    clone.querySelector("[data-day]").setAttribute('value', detail.dayName);
    clone.querySelector("[data-detailid]").setAttribute('data-detailid', detail.id);
    clone.querySelector("[data-detailid]").setAttribute('value', detail.id);

    // Ver la forma de tomar los working days
    var workingDay = detail.working_day_id;
    console.log(workingDay);
    if ( workingDay != null )
    {
        console.log("Entre el if");
        clone.querySelector("[data-workingday]").value = workingDay;
        clone.querySelector("[data-workingday]").setAttribute('selected', 'selected');

    } else {
        console.log("Entre el else");
        clone.querySelector("[data-workingday]").value = 0;
        clone.querySelector("[data-workingday]").setAttribute('selected', 'selected');
    }
    $('#body-details').append(clone);
}

function renderTemplateRegime( regime ) {
    var clone = activateTemplate('#template-regime');

    clone.querySelector("[data-save]").setAttribute('data-save', regime.id);
    clone.querySelector("[data-edit]").setAttribute('data-edit', regime.id);
    clone.querySelector("[data-delete]").setAttribute('data-delete', regime.id);

    $('#body-regime').append(clone);

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

