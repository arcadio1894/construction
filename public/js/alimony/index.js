$(document).ready(function () {

    fillAlimony();

    $('#btn-refresh').on('click', fillAlimony);
});

var $formCreate;
var $modalCreate;
var $formEdit;
var $modalEdit;
var $formDelete;
var $modalDelete;
var $selectWorker;

function fillAlimony() {
    $("#content-body").LoadingOverlay("show", {
        background  : "rgba(236, 91, 23, 0.5)"
    });

    $.ajax({
        url: "/dashboard/all/workers/alimony/",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var workers = json.workers;

            $('#body-workers').html('');

            renderTemplateWorker(workers);

            $("#content-body").LoadingOverlay("hide", true);
        }
    });
}

function renderTemplateWorker(workers) {

    for (let i = 0; i < workers.length; i++) {
        var clone = activateTemplate('#template-worker');
        var url = document.location.origin+'/dashboard/ver/pension/alimentos/'+workers[i].worker_id;
        var urlImage = document.location.origin+ '/images/users/'+workers[i].image;
        clone.querySelector("[data-username]").innerHTML = workers[i].worker_name;
        clone.querySelector("[data-function]").innerHTML = workers[i].workerFunction;
        clone.querySelector("[data-num_register]").innerHTML = workers[i].numRegister;
        clone.querySelector("[data-image]").setAttribute('src', urlImage);
        clone.querySelector("[data-register]").setAttribute('href', url);
        $('#body-workers').append(clone);
    }

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}