$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('click', '[data-activityworker]', addWorker);
    $(document).on('click', '[data-activityworkerdelete]', removeWorker);

    $(document).on('click', '[data-activityedit]', saveActivity);
    $(document).on('click', '[data-activitydelete]', removeActivity);

    $('#newActivity').on('click', addActivity);

    $('#newWork').on('click', addWork);
    $(document).on('click', '[data-editwork]', openModalQuotes);
    $modalQuotes = $('#modalQuotes');
    $('#btn-quote').on('click', editWork);

    $(document).on('click', '[data-editphase]', openModalPhases);
    $modalPhases = $('#modalPhases');
    $('#btn-savePhase').on('click', editPhase);

    $(document).on('click', '[data-addphasework]', addPhase);

    $(document).on('click', '[data-addtaskphase]', addTask);

    $(document).on('click', '[data-taskworker]', addTaskWorker);

    $(document).on('click', '[data-taskworkerdelete]', removeTaskWorker);

    $(document).on('click', '[data-savetask]', saveTask);
    $(document).on('click', '[data-deletetask]', removeTask);

    $(document).on('click', '[data-deletephase]', removePhase);

    $(document).on('click', '[data-deletework]', removeWork);

    $(document).on('select2:select', '.quote_description', function (e) {
        // Do something
        $("#descriptionQuote").val('');
        $("#descriptionQuote").val('');
        var data = $(this).select2('data');
        console.log( data[0].element.dataset.quote );
        var description = data[0].element.dataset.quote;
        $("#descriptionQuote").val(description);
        $("#descriptionQuote").text(description);
        /*$(this).parent().next().children().next().text('');
        $(this).parent().next().children().next().text(description);*/
    });

    $(document).on('input', '[data-activity]', function() {
        var card = $(this).parent().parent().parent().parent().prev();
        card.removeClass('ponto');
        card.addClass('class-edit');
    });

    $(document).on('input', '[data-progress]', function() {
        var card = $(this).parent().parent().parent().parent().parent().prev();
        card.removeClass('ponto');
        card.addClass('class-edit');
    });

    $(document).on('select2:select', '.workers', function (e) {
        // Do something
        console.log('Llegue');
        var card = $(this).parent().parent().parent().parent().parent().parent().parent().prev();
        card.removeClass('ponto');
        card.addClass('class-edit');
    });

    $(document).on('select2:select', '.performers', function (e) {
        // Do something
        console.log('Llegue');
        var card = $(this).parent().parent().parent().parent().prev();
        card.removeClass('ponto');
        card.addClass('class-edit');
    });
    //9
    $(document).on('input', '[data-hoursplan]', function() {
        var card = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().prev();
        card.removeClass('ponto');
        card.addClass('class-edit');
    });
    $(document).on('input', '[data-hoursreal]', function() {
        var card = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().prev();
        card.removeClass('ponto');
        card.addClass('class-edit');
    });

    $('#lostActivity').on('click', getLostActivities);
    $modalActivities = $('#modalActivities');
    $(document).on('click', '[data-activitylostid]', assignActivity);

});

var $permissions;
var $modalActivities;

var $modalQuotes;
var $modalPhases;

var $id_work = 0;
var $id_phase = 0;

function removeWork() {
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    var id_work = $(this).data('deletework');

    var card = $(this).parent().parent().parent().parent().parent().parent().parent();

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de eliminar este trabajo?',
        content: 'Se eliminará toda información de este trabajo, sus etapas y tareas.',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/remove/work/'+id_work,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                processData:false,
                contentType:false,
                success: function (data) {
                    console.log(data);
                    vdialog.success(data.message);
                    // Aqui se renderizará y se colocará los datos de la actividad
                    card.remove();
                },
                error: function (data) {
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

        },
        cancel: function(){
            vdialog.alert('Trabajo no eliminado');

        }
    });
}

function removePhase() {
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    var id_phase = $(this).data('deletephase');

    var card = $(this).parent().parent().parent().parent().parent();

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de eliminar esta etapa?',
        content: 'Se eliminará toda información de esta etapa y sus tareas.',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/remove/phase/'+id_phase,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                processData:false,
                contentType:false,
                success: function (data) {
                    console.log(data);
                    vdialog.success(data.message);
                    // Aqui se renderizará y se colocará los datos de la actividad
                    card.remove();
                },
                error: function (data) {
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

        },
        cancel: function(){
            vdialog.alert('Etapa no eliminada');

        }
    });
}

function removeTask() {
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    button.attr("disabled", true);
    var id_task = $(this).data('deletetask');

    var card = $(this).parent().parent().parent().parent();

    /*$.confirm({
        icon: 'fas fa-times',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        columnClass: 'medium',
        title: '¿Esta seguro de eliminar esta tarea?',
        content: 'Se eliminará toda información de esta tarea.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/remove/task/'+id_task,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            // Aqui se renderizará y se colocará los datos de la actividad
                            card.remove();
                        },
                        error: function (data) {
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
                    $.alert("Tarea no eliminada.");
                    button.attr("disabled", false);
                },
            },
        }
    });*/

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de eliminar esta tarea?',
        content: 'Se eliminará toda la información de la tarea.',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/remove/task/'+id_task,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                processData:false,
                contentType:false,
                success: function (data) {
                    console.log(data);
                    vdialog.success(data.message);
                    // Aqui se renderizará y se colocará los datos de la actividad
                    card.remove();
                },
                error: function (data) {
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

        },
        cancel: function(){
            vdialog.alert('Tarea no eliminada');

        }
    });
}

function saveTask() {
    event.preventDefault();
    var button = $(this);

    var task_id = $(this).data('savetask');
    var activity = $(this).parent().parent().parent().next().children().children().children().children().next().val();
    var performer = $(this).parent().parent().parent().next().children().children().children().next().children().next().val();
    var progress = $(this).parent().parent().parent().next().children().children().children().next().next().children().next().children().val();

    var collaborators = $(this).parent().parent().parent().next().children().children().children().next().next().next().children().next();

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
        collaboratorsArray.push({'worker':worker[i], 'hoursplan':parseFloat(hoursplan[i]), 'hoursreal':parseFloat(hoursreal[i])});
    }

    /*console.log(activity_id);
    console.log(quote_id);
    console.log(quote_description);
    console.log(phase);
    console.log(activity);
    console.log(progress);
    console.log(collaboratorsArray);*/

    var task_complete = [];

    task_complete.push({
        'task_id':task_id,
        'activity':activity,
        'performer':performer,
        'progress':progress,
        'workers':collaboratorsArray
    });

    console.log(task_complete);

    var card = $(this).parent().parent().parent();

    /*card.removeClass('card-gray-dark');
    card.addClass('card-outline card-success');
    button.attr("disabled", false);*/

    vdialog({
        type:'alert',// alert, success, error, confirm
        title: '¿Esta seguro de guardar el avance de la tarea?',
        content: 'Se guardará toda la información de la tarea.',
        okValue:'Aceptar',
        modal:true,
        cancelValue:'Cancelar',
        ok: function(){

            $.ajax({
                url: '/dashboard/save/progress/task/'+task_id,
                method: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: JSON.stringify({ task: task_complete }),
                processData:false,
                contentType:'application/json; charset=utf-8',
                success: function (data) {
                    console.log(data);
                    //$.alert(data.message);
                    vdialog.success(data.message);
                    card.removeClass('class-edit');
                    card.addClass('ponto');
                    // Colocar la nueva descripcion en el card
                    button.parent().parent().prev().html(activity);

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
            vdialog.alert('Avance no guardado');

        }
    });

    //var phase = $(this).parent().parent().next().children().children().next() .children().next().val();
}

function removeTaskWorker() {
    $(this).tooltip('hide');
    var card = $(this).parent().parent().parent().parent().parent().parent().parent().parent().prev();
    card.removeClass('ponto');
    card.addClass('class-edit');
    $(this).parent().parent().parent().remove();
}

function addTaskWorker() {
    $(this).tooltip('hide');

    var render = $(this).parent().next();

    var card = render.parent().parent().parent().parent().prev();
    card.removeClass('ponto');
    card.addClass('class-edit');

    renderTemplateWorker(render);

    $('.workers').select2({
        placeholder: "Seleccione colaborador",
    });
}

function addTask() {
    console.log('Llegue');
    $id_phase = $(this).data('addtaskphase');
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    var render = button.parent().parent().parent().next().children();
    console.log(render);
    //renderTemplatePhase(id_timeline, $id_work, $id_phase, render);
    $.ajax({
        url: '/dashboard/create/task/phase/'+$id_phase,
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
            renderTemplateTask($id_phase, data.task.id, render);
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

function renderTemplateTask(phase_id, task_id, render) {
    var clone = activateTemplate('#template-task');
    clone.querySelector("[data-task]").setAttribute('data-task', task_id);
    clone.querySelector("[data-taskid]").setAttribute('data-taskid', task_id);
    clone.querySelector("[data-task]").setAttribute('href', '#task'+task_id);
    clone.querySelector("[data-savetask]").setAttribute('data-savetask', task_id);
    clone.querySelector("[data-deletetask]").setAttribute('data-deletetask', task_id);
    clone.querySelector("[data-idaccordion]").setAttribute('id', 'task'+task_id);

    render.append(clone);
}

function editPhase() {
    var button = $(this);
    button.attr("disabled", true);

    var id_timeline = $('#idtimeline').val();
    var card_quote1 = $(this).parent().prev().children().children().children().next();
    var card_quote3 = $(this).parent().prev().prev();

    var phase_id = card_quote3.val();
    var phase_description = card_quote1.val();

    console.log(phase_id);
    console.log(phase_description);

    $.ajax({
        url: '/dashboard/edit/phase/'+phase_id+'/timeline/'+id_timeline,
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: JSON.stringify({ phase_id: phase_id, phase_description:phase_description }),
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
            // Aqui se renderizará y se colocará los datos del trabajo
            $(document).find('[data-phase="'+phase_id+'"]').html((phase_description=='') ? 'Etapa #':phase_description);
            $(document).find('[data-phase="'+phase_id+'"]').attr('data-idphase', phase_id);
            $(document).find('[data-phase="'+phase_id+'"]').attr('data-description', (phase_description=='') ? '':phase_description);

            button.attr("disabled", false);

        },
        error: function (data) {
            button.attr("disabled", false);
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

    $modalPhases.modal('hide');

}

function openModalPhases() {
    event.preventDefault();
    var phase_id = $(this).data('editphase');
    var description = $(this).data('editdescriptionphase');
    console.log(phase_id);
    $modalPhases.find('[id=phase_id]').val(phase_id);
    $modalPhases.find('[id=descriptionPhase]').val(description);
    $modalPhases.modal('show');
}

function addPhase() {
    console.log('Llegue');
    $id_work = $(this).data('addphasework');
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    var render = button.parent().parent().parent().next().children();
    console.log(render);
    //renderTemplatePhase(id_timeline, $id_work, $id_phase, render);
    $.ajax({
        url: '/dashboard/create/phase/work/'+$id_work,
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
            renderTemplatePhase($id_work, data.phase.id, render);
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

function renderTemplatePhase(work_id, phase_id, render) {
    var clone = activateTemplate('#template-phase');
    clone.querySelector("[data-phase]").setAttribute('data-phase', phase_id);
    clone.querySelector("[data-workid]").setAttribute('data-workid', work_id);
    clone.querySelector("[data-phase]").setAttribute('href', '#phase'+phase_id);
    clone.querySelector("[data-description]").setAttribute('data-description', phase_id);
    clone.querySelector("[data-addtaskphase]").setAttribute('data-addtaskphase', phase_id);
    clone.querySelector("[data-editphase]").setAttribute('data-editphase', phase_id);
    clone.querySelector("[data-deletephase]").setAttribute('data-deletephase', phase_id);
    clone.querySelector("[data-idaccordion]").setAttribute('id', 'phase'+phase_id);

    render.append(clone);
}

function editWork() {

    var button = $(this);
    button.attr("disabled", true);

    var id_timeline = $('#idtimeline').val();
    var card_quote1 = $(this).parent().prev().children().children().children().next();
    var card_quote2 = $(this).parent().prev().children().children().next().children().next();
    var card_quote3 = $(this).parent().prev().prev();

    console.log(card_quote1);
    console.log(card_quote2);
    console.log(card_quote3);

    var quote_id = card_quote1.val();
    var quote_description = card_quote2.val();
    var work_id = card_quote3.val();

    console.log(quote_id);
    console.log(quote_description);
    console.log(work_id);

    $.ajax({
        url: '/dashboard/edit/work/'+work_id+'/timeline/'+id_timeline,
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: JSON.stringify({ quote_id: quote_id, quote_description:quote_description }),
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
            // Aqui se renderizará y se colocará los datos del trabajo
            $(document).find('[data-idwork='+work_id+']').html((quote_description=='') ? 'Trabajo #':quote_description);
            $(document).find('[data-idwork='+work_id+']').attr('data-quoteid', (quote_id==0)? '':quote_id);
            $(document).find('[data-idwork='+work_id+']').attr('data-description', (quote_description=='') ? '':quote_description);


            button.attr("disabled", false);

        },
        error: function (data) {
            button.attr("disabled", false);
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
    $("#quote_description").val('').trigger('change');
    $("#descriptionQuote").val('');
    $modalQuotes.modal('hide');

}

function openModalQuotes() {
    event.preventDefault();
    var work_id = $(this).data('editwork');
    console.log(work_id);
    $modalQuotes.find('[id=work_id]').val(work_id);
    $modalQuotes.modal('show');
}

function addWork() {
    $(this).tooltip('hide');
    $id_work = $id_work+1;
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    button.attr("disabled", true);
    //renderTemplateWork(id_timeline, $id_work);
    //button.attr("disabled", false);
    $.ajax({
        url: '/dashboard/create/work/timeline/'+id_timeline,
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
            //renderTemplateActivity(id_timeline, data.activity.id);
            renderTemplateWork(id_timeline, data.work.id);
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

function renderTemplateWork(timeline_id, work_id) {
    var clone = activateTemplate('#template-work');
    clone.querySelector("[data-idwork]").setAttribute('data-idwork', work_id);
    clone.querySelector("[data-idwork]").setAttribute('href', '#work'+work_id);
    clone.querySelector("[data-editwork]").setAttribute('data-editwork', work_id);
    clone.querySelector("[data-addphasework]").setAttribute('data-addphasework', work_id);
    clone.querySelector("[data-deletework]").setAttribute('data-deletework', work_id);
    clone.querySelector("[data-idaccordion]").setAttribute('id', 'work'+work_id);

    $('#body-works').append(clone);
}

function getLostActivities() {
    $(this).tooltip('hide');
    event.preventDefault();
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    //button.attr("disabled", true);

    $.get( "/dashboard/get/activity/forget/"+id_timeline, function( data ) {
        $('#table-lost-activities').html('');

        for ( var i=0; i<data.activities.length; i++ )
        {
            renderTemplateLostActivities(i+1 ,data.activities[i].activity_id, data.activities[i].description_quote, data.activities[i].phase, data.activities[i].activity, data.activities[i].progress);
        }
    });

    $modalActivities.modal('show');
}

function assignActivity() {
    var id_timeline = $('#idtimeline').val();
    var button = $(this);
    button.attr("disabled", true);

    var activity_id = $(this).data('activitylostid');

    $.confirm({
        icon: 'fas fa-save',
        theme: 'modern',
        //closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Esta seguro de asignar esta actividad a este cronograma?',
        content: 'Se guardará toda la información de la actividad.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/assign/activity/'+ activity_id +'/timeline/'+id_timeline,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:'application/json; charset=utf-8',
                        success: function (data) {
                            console.log(data.activity[0].activity);
                            button.attr("disabled", false);

                            $.alert(data.message);

                            // Quitar de la tabla y agregar a las actividades
                            button.parent().parent().remove();

                            renderActivityLost( data.activity[0] );

                            $modalActivities.modal('hide');

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
                    $.alert("Actividad no asignada.");
                    button.attr("disabled", false);
                },
            },
        }
    });

    //var phase = $(this).parent().parent().next().children().children().next() .children().next().val();

}

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

function renderActivityLost(activity) {
    var clone = activateTemplate('#template-activity');

    clone.querySelector("[data-activityedit]").setAttribute('data-activityedit', activity.id);
    clone.querySelector("[data-activitydelete]").setAttribute('data-activitydelete', activity.id);
    //clone.querySelector("[data-quote_description]").setAttribute('value', activity_id);
    var select_quote = clone.querySelector("[data-quote_description]");
    select_quote.value = activity.quote_id;
    clone.querySelector("[data-descriptionQuote]").innerHTML = activity.description_quote;
    clone.querySelector("[data-phase]").innerHTML = activity.phase;
    clone.querySelector("[data-activity]").innerHTML = activity.activity;
    //clone.querySelector("[data-performer]").setAttribute('data-activityworker', activity_id);
    var select_performer = clone.querySelector("[data-performer]");
    select_performer.value = activity.performer;
    clone.querySelector("[data-progress]").setAttribute('value', activity.progress);

    var render = clone.querySelector("[id=body-workers]");
    for (let i = 0; i < activity.activity_workers.length ; i++) {
        var cloneWorker = activateTemplate('#template-worker');
        var select_worker = cloneWorker.querySelector("[data-worker]");
        select_worker.value = activity.activity_workers[i].worker_id;
        cloneWorker.querySelector("[data-hoursplan]").setAttribute('value', activity.activity_workers[i].hours_plan);
        cloneWorker.querySelector("[data-hoursreal]").setAttribute('value', activity.activity_workers[i].hours_real);
        cloneWorker.querySelector("[data-activityworkerdelete]").setAttribute('data-activityworkerdelete', activity.activity_workers[i].id);
        render.append(cloneWorker);
    }

    $('#body-activities').append(clone);

    $('.quote_description').select2({
        placeholder: "Selecione cotización",
    });
    $('.workers').select2({
        placeholder: "Seleccione colaborador",
    });

    $('.performers').select2({
        placeholder: "Seleccione responsable",
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

function renderTemplateLostActivities(i, id, quote, phase, activity, progress) {
    var clone = activateTemplate('#template-lostActivity');
    clone.querySelector("[data-i]").innerHTML = i;
    clone.querySelector("[data-quote]").innerHTML = quote;
    clone.querySelector("[data-phase]").innerHTML = phase;
    clone.querySelector("[data-activity]").innerHTML = activity;
    clone.querySelector("[data-progress]").innerHTML = progress;
    clone.querySelector("[data-activitylostid]").setAttribute('data-activitylostid', id);
    $('#table-lost-activities').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

