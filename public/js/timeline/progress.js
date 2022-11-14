$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('click', '[data-activityedit]', saveActivity);

    $(document).on('input', '[data-progress]', function() {
        var card = $(this).parent().parent().parent().parent().parent();
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
    console.log(progress);
    console.log(collaboratorsArray);*/

    var activity_complete = [];

    activity_complete.push({
        'activity_id':activity_id,
        'progress':progress,
        'workers':collaboratorsArray
    });

    /*console.log(activity_complete);*/

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
        title: '¿Esta seguro de registrar el avance de esta actividad?',
        content: 'Se guardará el avance y las horas reales.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/save/progress/activity/'+activity_id,
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
                    $.alert("Avance no registrado.");
                    button.attr("disabled", false);
                },
            },
        }
    });

    //var phase = $(this).parent().parent().next().children().children().next() .children().next().val();

}

function renderTemplateActivity(data) {
    var clone = activateTemplate('#template-activity');

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

