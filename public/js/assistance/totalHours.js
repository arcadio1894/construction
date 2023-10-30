$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    $('#btn-hours').on('click', fillTotalHoursTable);
    //fillTotalHoursTable();

    $('#btn-download').on('click', downloadExcelTotalDiary);

});

function downloadExcelTotalDiary() {
    var worker  = $('#worker').val();
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");

    if ( worker == '' || worker == null )
    {
        toastr.error('Seleccione un trabajador', 'Error',
            {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "2000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        return;
    }

    if ( start == '' || end == '' )
    {
        console.log('Sin fechas');
        $.confirm({
            icon: 'fas fa-file-excel',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó fechas',
            content: 'Si no hay fechas se descargarán el total de horas de todo el año',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        $("#hours-total").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });

                        $("#summary-hours").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });

                        var query = {
                            start: start,
                            end: end
                        };

                        $.ajax({
                            url: "/dashboard/get/total/hours/by/worker/"+ worker +"/?"  + $.param(query),
                            type: 'GET',
                            dataType: 'json',
                            success: function (json) {
                                $('#body-totalHours').html('');
                                $('#body-summaryHours').html('');

                                for (var i=0; i<json.arrayByDates.length; i++)
                                {
                                    console.log(json.arrayByDates[i].week);
                                    renderTemplateTotalHours(
                                        i+1,
                                        json.arrayByDates[i].week,
                                        json.arrayByDates[i].date,
                                        json.arrayByDates[i].assistances
                                    );
                                }

                                for (var j=0; j<json.arrayByWeek.length; j++)
                                {
                                    renderTemplateSummaryTotalHours(
                                        j+1,
                                        json.arrayByWeek[j].week,
                                        json.arrayByWeek[j].month,
                                        json.arrayByWeek[j].date,
                                        json.arrayByWeek[j].h_ord,
                                        json.arrayByWeek[j].h_25,
                                        json.arrayByWeek[j].h_35,
                                        json.arrayByWeek[j].h_100,
                                        json.arrayByWeek[j].h_esp
                                    );
                                }

                                $("#hours-total").LoadingOverlay("hide", true);

                                $("#summary-hours").LoadingOverlay("hide", true);

                                var query2 = {
                                    start: start,
                                    end: end,
                                    worker: worker
                                };

                                var url = "/dashboard/download/excel/total/hours/?" + $.param(query2);

                                window.location = url;
                            }
                        });

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Descarga cancelada.");
                    },
                },
            },
        });
    } else {
        console.log('Con fechas');
        $.confirm({
            icon: 'fas fa-search',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'Descargar total horas desde '+start+' hasta '+end,
            content: 'Se descargarán el total de horas desde la fecha indicada',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        $("#hours-total").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });

                        $("#summary-hours").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });
                        console.log(start);
                        console.log(end);

                        var query = {
                            start: start,
                            end: end
                        };

                        $.ajax({
                            url: "/dashboard/get/total/hours/by/worker/"+ worker +"/?"  + $.param(query),
                            type: 'GET',
                            dataType: 'json',
                            success: function (json) {
                                $('#body-totalHours').html('');
                                $('#body-summaryHours').html('');

                                for (var i=0; i<json.arrayByDates.length; i++)
                                {
                                    console.log(json.arrayByDates[i].week);
                                    renderTemplateTotalHours(
                                        i+1,
                                        json.arrayByDates[i].week,
                                        json.arrayByDates[i].date,
                                        json.arrayByDates[i].assistances
                                    );
                                }

                                for (var j=0; j<json.arrayByWeek.length; j++)
                                {
                                    renderTemplateSummaryTotalHours(
                                        j+1,
                                        json.arrayByWeek[j].week,
                                        json.arrayByWeek[j].month,
                                        json.arrayByWeek[j].date,
                                        json.arrayByWeek[j].h_ord,
                                        json.arrayByWeek[j].h_25,
                                        json.arrayByWeek[j].h_35,
                                        json.arrayByWeek[j].h_100,
                                        json.arrayByWeek[j].h_esp
                                    );
                                }

                                $("#hours-total").LoadingOverlay("hide", true);

                                $("#summary-hours").LoadingOverlay("hide", true);

                            }
                        });

                        var query2 = {
                            start: start,
                            end: end,
                            worker: worker
                        };

                        var url = "/dashboard/download/excel/total/hours/?" + $.param(query2);

                        window.location = url;

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Descarga cancelada.");
                    },
                },
            },
        });

    }

}

function fillTotalHoursTable() {
    var worker  = $('#worker').val();
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");

    if ( worker == '' || worker == null )
    {
        toastr.error('Seleccione un trabajador', 'Error',
            {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "2000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        return;
    }

    if ( start == '' || end == '' )
    {
        console.log('Sin fechas');
        $.confirm({
            icon: 'fas fa-search',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó fechas',
            content: 'Si no hay fechas se mostrarán las asistencias de todo el año',
            buttons: {
                confirm: {
                    text: 'BUSCAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        $("#hours-total").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });

                        $("#summary-hours").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });

                        var query = {
                            start: start,
                            end: end
                        };

                        $.ajax({
                            url: "/dashboard/get/total/hours/by/worker/"+ worker +"/?"  + $.param(query),
                            type: 'GET',
                            dataType: 'json',
                            success: function (json) {
                                $('#body-totalHours').html('');
                                $('#body-summaryHours').html('');

                                for (var i=0; i<json.arrayByDates.length; i++)
                                {
                                    console.log(json.arrayByDates[i].week);
                                    renderTemplateTotalHours(
                                        i+1,
                                        json.arrayByDates[i].week,
                                        json.arrayByDates[i].date,
                                        json.arrayByDates[i].assistances
                                    );
                                }

                                for (var j=0; j<json.arrayByWeek.length; j++)
                                {
                                    renderTemplateSummaryTotalHours(
                                        j+1,
                                        json.arrayByWeek[j].week,
                                        json.arrayByWeek[j].month,
                                        json.arrayByWeek[j].date,
                                        parseFloat(json.arrayByWeek[j].h_ord).toFixed(2),
                                        parseFloat(json.arrayByWeek[j].h_25).toFixed(2),
                                        parseFloat(json.arrayByWeek[j].h_35).toFixed(2),
                                        parseFloat(json.arrayByWeek[j].h_100).toFixed(2),
                                        parseFloat(json.arrayByWeek[j].h_esp).toFixed(2)
                                    );
                                }

                                $("#hours-total").LoadingOverlay("hide", true);

                                $("#summary-hours").LoadingOverlay("hide", true);

                            }
                        });

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Búsqueda cancelada.");
                    },
                },
            },
        });
    } else {
        console.log('Con fechas');
        $.confirm({
            icon: 'fas fa-search',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'Buscar asistencias desde '+start+' hasta '+end,
            content: 'Se buscarán las asistencias desde la fecha indicada',
            buttons: {
                confirm: {
                    text: 'BUSCAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        $("#hours-total").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });

                        $("#summary-hours").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });
                        console.log(start);
                        console.log(end);

                        var query = {
                            start: start,
                            end: end
                        };

                        $.ajax({
                            url: "/dashboard/get/total/hours/by/worker/"+ worker +"/?"  + $.param(query),
                            type: 'GET',
                            dataType: 'json',
                            success: function (json) {
                                $('#body-totalHours').html('');
                                $('#body-summaryHours').html('');

                                for (var i=0; i<json.arrayByDates.length; i++)
                                {
                                    console.log(json.arrayByDates[i].week);
                                    renderTemplateTotalHours(
                                        i+1,
                                        json.arrayByDates[i].week,
                                        json.arrayByDates[i].date,
                                        json.arrayByDates[i].assistances
                                    );
                                }

                                for (var j=0; j<json.arrayByWeek.length; j++)
                                {
                                    renderTemplateSummaryTotalHours(
                                        j+1,
                                        json.arrayByWeek[j].week,
                                        json.arrayByWeek[j].month,
                                        json.arrayByWeek[j].date,
                                        parseFloat(json.arrayByWeek[j].h_ord).toFixed(2),
                                        parseFloat(json.arrayByWeek[j].h_25).toFixed(2),
                                        parseFloat(json.arrayByWeek[j].h_35).toFixed(2),
                                        parseFloat(json.arrayByWeek[j].h_100).toFixed(2),
                                        parseFloat(json.arrayByWeek[j].h_esp).toFixed(2)
                                    );
                                }

                                $("#hours-total").LoadingOverlay("hide", true);

                                $("#summary-hours").LoadingOverlay("hide", true);

                            }
                        });

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Búsqueda cancelada.");
                    },
                },
            },
        });

    }

    /*$.ajax({
        url: "/dashboard/get/total/hours/by/worker/1",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            $('#body-totalHours').html('');
            $('#body-summaryHours').html('');

            for (var i=0; i<json.arrayByDates.length; i++)
            {
                console.log(json.arrayByDates[i].week);
                renderTemplateTotalHours(
                    i+1,
                    json.arrayByDates[i].week,
                    json.arrayByDates[i].date,
                    json.arrayByDates[i].assistances
                );
            }

            for (var j=0; j<json.arrayByWeek.length; j++)
            {
                renderTemplateSummaryTotalHours(
                    j+1,
                    json.arrayByWeek[j].week,
                    json.arrayByWeek[j].month,
                    json.arrayByWeek[j].date,
                    json.arrayByWeek[j].h_ord,
                    json.arrayByWeek[j].h_25,
                    json.arrayByWeek[j].h_35,
                    json.arrayByWeek[j].h_100,
                    json.arrayByWeek[j].h_esp
                );
            }

            $("#hours-total").LoadingOverlay("hide", true);

            $("#summary-hours").LoadingOverlay("hide", true);

        }
    });*/

}

function renderTemplateTotalHours(i, week, date, assistances ) {

    var clone = activateTemplate('#template-totalHours');

    if ( i%2 !== 0 )
    {
        clone.querySelector("[data-color]").setAttribute('class', 'table-warning');
    } else {
        clone.querySelector("[data-color]").setAttribute('class', 'table-light');
    }

    clone.querySelector("[data-week]").innerHTML = week;
    clone.querySelector("[data-date]").innerHTML = date;
    if ( assistances[0][0] === '' )
    {
        clone.querySelector("[data-h_ord]").innerHTML = parseFloat(assistances[0][0]).toFixed(2);
        clone.querySelector("[data-h_ord]").setAttribute('class', 'bg-dark');
    } else {
        clone.querySelector("[data-h_ord]").innerHTML = parseFloat(assistances[0][0]).toFixed(2);
    }

    if ( assistances[0][1] === '' )
    {
        clone.querySelector("[data-h_25]").innerHTML = parseFloat(assistances[0][1]).toFixed(2);

        clone.querySelector("[data-h_25]").setAttribute('class', 'bg-dark');
    } else {
        clone.querySelector("[data-h_25]").innerHTML = parseFloat(assistances[0][1]).toFixed(2);
    }

    if ( assistances[0][2] === '' )
    {
        clone.querySelector("[data-h_35]").innerHTML = parseFloat(assistances[0][2]).toFixed(2);
        clone.querySelector("[data-h_35]").setAttribute('class', 'bg-dark');
    } else {
        clone.querySelector("[data-h_35]").innerHTML = parseFloat(assistances[0][2]).toFixed(2);
    }

    clone.querySelector("[data-h_100]").innerHTML = parseFloat(assistances[0][3]).toFixed(2);
    clone.querySelector("[data-h_esp]").innerHTML = parseFloat(assistances[0][4]).toFixed(2);


    $('#body-totalHours').append(clone);
}

function renderTemplateSummaryTotalHours(i, week, month, date, h_ord, h_25, h_35, h_100, h_esp ) {

    var clone = activateTemplate('#template-summaryHours');

    if ( i%2 !== 0 )
    {
        clone.querySelector("[data-color]").setAttribute('class', 'table-warning');
    } else {
        clone.querySelector("[data-color]").setAttribute('class', 'table-light');
    }
    clone.querySelector("[data-week]").innerHTML = week;
    clone.querySelector("[data-month]").innerHTML = month;
    clone.querySelector("[data-date]").innerHTML = date;
    clone.querySelector("[data-h_ord]").innerHTML = h_ord;
    clone.querySelector("[data-h_25]").innerHTML = h_25;
    clone.querySelector("[data-h_35]").innerHTML = h_35;
    clone.querySelector("[data-h_100]").innerHTML = h_100;
    clone.querySelector("[data-h_esp]").innerHTML = h_esp;


    $('#body-summaryHours').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

