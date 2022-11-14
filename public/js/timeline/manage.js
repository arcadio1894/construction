$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    /*$('input[id="date_timeline"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),20),
        startDate: moment().format('DD/MM/YYYY'),
        locale: {
            "format": 'DD/MM/YYYY',
            "applyLabel": "Guardar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Personalizar",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Setiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        }
    });*/
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('click', '[data-activityworker]', addWorker);
    $(document).on('click', '[data-activityworkerdelete]', removeWorker);

    $(document).on('click', '[data-activityedit]', saveActivity);
    $(document).on('click', '[data-activitydelete]', removeActivity);

    $('#newActivity').on('click', addActivity);

    $(document).on('select2:select', '.quote_description', function (e) {
        // Do something
        var card = $(this).parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
        var data = $(this).select2('data');
        console.log( data[0].element.dataset.quote );
        var description = data[0].element.dataset.quote;
        $(this).parent().next().children().next().text('');
        $(this).parent().next().children().next().text(description);
    });

    $(document).on('input', '[data-descriptionQuote]', function() {
        var card = $(this).parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
    });

    $(document).on('input', '[data-phase]', function() {
        var card = $(this).parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
    });

    $(document).on('input', '[data-activity]', function() {
        var card = $(this).parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
    });

    $(document).on('input', '[data-progress]', function() {
        var card = $(this).parent().parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
    });

    $(document).on('select2:select', '.workers', function (e) {
        // Do something
        console.log('Llegue');
        var card = $(this).parent().parent().parent().parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
    });

    $(document).on('select2:select', '.performers', function (e) {
        // Do something
        console.log('Llegue');
        var card = $(this).parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
    });
    //9
    $(document).on('input', '[data-hoursplan]', function() {
        var card = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
    });
    $(document).on('input', '[data-hoursreal]', function() {
        var card = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
        card.removeClass('card-outline card-success');
        card.addClass('card-gray-dark');
    });

});

var $permissions;

function saveActivity() {
    $(this).tooltip('hide');

    var button = $(this);
    button.attr("disabled", true);

    var activity_id = $(this).data('activityedit');
    var quote_id = $(this).parent().parent().next().children().children().children().next().val();
    var quote_description = $(this).parent().parent().next().children().children().next().children().next().val();
    var phase = $(this).parent().parent().next().children().children().next().next().children().next().val();
    var activity = $(this).parent().parent().next().children().children().next().next().next().children().next().val();
    var performer = $(this).parent().parent().next().children().children().next().next().next().next().children().next().val();
    var progress = $(this).parent().parent().next().children().children().next().next().next().next().next().children().next().children().val();

    var collaborators = $(this).parent().parent().next().children().children().next().next().next().next().next().children().next();

    var worker = [];
    var hoursplan = [];
    var hoursreal = [];

    collaborators.each(function(e){
        $(this).find('[data-worker]').each(function(){
            worker.push($(this).val());
        });
        $(this).find('[data-hoursplan]').each(function(){
            hoursplan.push($(this).val());
        });
        $(this).find('[data-hoursreal]').each(function(){
            hoursreal.push($(this).val());
        });

    });

    var collaboratorsArray = [];

    for (let i = 0; i < worker.length; i++) {
        collaboratorsArray.push({'worker':worker[i], 'hoursplan':hoursplan[i], 'hoursreal':hoursreal[i]});
    }

    /*console.log(activity_id);
    console.log(quote_id);
    console.log(quote_description);
    console.log(phase);
    console.log(activity);
    console.log(progress);
    console.log(collaboratorsArray);*/

    var activity_complete = [];

    activity_complete.push({
        'activity_id':activity_id,
        'quote_id':quote_id,
        'quote_description':quote_description,
        'phase':phase,
        'activity':activity,
        'performer':performer,
        'progress':progress,
        'workers':collaboratorsArray
    });

    console.log(activity_complete);

    var card = $(this).parent().parent().parent();

    /*card.removeClass('card-gray-dark');
    card.addClass('card-outline card-success');
    button.attr("disabled", false);*/

    $.confirm({
        icon: 'fas fa-save',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Esta seguro de actualizar esta actividad?',
        content: 'Se guardará toda la información de la actividad.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/save/activity/timeline/'+activity_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: JSON.stringify({ activity: activity_complete }),
                        processData:false,
                        contentType:'application/json; charset=utf-8',
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);

                            card.removeClass('card-gray-dark');
                            card.addClass('card-outline card-success');
                            button.attr("disabled", false);

                        },
                        error: function (data) {
                            button.attr("disabled", false);
                            toastr.error('Algo sucedió en el servidor.', 'Error',
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
                        },
                    });
                    //$.alert('Your name is ' + name);

                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Actividad no guardada.");
                    button.attr("disabled", false);
                },
            },
        }
    });

    //var phase = $(this).parent().parent().next().children().children().next() .children().next().val();

}

function removeActivity() {
    event.preventDefault();
    $(this).tooltip('hide');
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    button.attr("disabled", true);
    var id_activity = $(this).data('activitydelete');
    
    var card = $(this).parent().parent().parent().parent();

    $.confirm({
        icon: 'fas fa-times',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        columnClass: 'medium',
        title: '¿Esta seguro de eliminar esta actividad?',
        content: 'Se eliminará toda información de esta actividad.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/remove/activity/timeline/'+id_activity,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
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
                            // Aqui se renderizará y se colocará los datos de la actividad
                            card.remove();
                            button.attr("disabled", false);
                        },
                        error: function (data) {
                            button.attr("disabled", false);
                            toastr.error('Algo sucedió en el servidor.', 'Error',
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
                        },
                    });
                    //$.alert('Your name is ' + name);
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Actividad no eliminada.");
                    button.attr("disabled", false);
                },
            },
        }
    });
}

function removeWorker() {
    $(this).tooltip('hide');
    var card = $(this).parent().parent().parent().parent().parent().parent().parent().parent();
    card.removeClass('card-outline card-success');
    card.addClass('card-gray-dark');
    $(this).parent().parent().parent().remove();
}

function editedActive() {
    var flag = false;
    $(document).find('[data-equip]').each(function(){
        console.log($(this));
        if ($(this).hasClass('card-gray-dark'))
        {
            flag = true;
        }
    });

    return flag;
}

function addActivity() {
    $(this).tooltip('hide');
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    button.attr("disabled", true);
    //renderTemplateActivity(id_timeline);

    $.ajax({
        url: '/dashboard/create/activity/timeline/'+id_timeline,
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
            // Aqui se renderizará y se colocará los datos de la actividad
            renderTemplateActivity(id_timeline, data.activity.id);
            $('.quote_description').select2({
                placeholder: "Selecione cotización",
            });
            $('.workers').select2({
                placeholder: "Seleccione colaborador",
            });

            $('.performers').select2({
                placeholder: "Seleccione responsable",
            });
            button.attr("disabled", false);
        },
        error: function (data) {
            button.attr("disabled", false);
            toastr.error('Algo sucedió en el servidor.', 'Error',
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
        },
    });

}

function addWorker() {
    $(this).tooltip('hide');

    var render = $(this).parent().next();

    var card = render.parent().parent().parent().parent();
    card.removeClass('card-outline card-success');
    card.addClass('card-gray-dark');

    renderTemplateWorker(render);

    $('.workers').select2({
        placeholder: "Seleccione colaborador",
    });
}

function renderTemplateActivity(timeline_id, activity_id) {
    var clone = activateTemplate('#template-activity');

    clone.querySelector("[data-activityedit]").setAttribute('data-activityedit', activity_id);
    clone.querySelector("[data-activitydelete]").setAttribute('data-activitydelete', activity_id);
    clone.querySelector("[data-activityworker]").setAttribute('data-activityworker', activity_id);

    $('#body-activities').append(clone);
}

function renderTemplateWorker(render) {
    var clone = activateTemplate('#template-worker');

    render.append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

