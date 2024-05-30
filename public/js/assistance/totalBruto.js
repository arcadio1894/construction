$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $selectYear = $('#year');
    $selectWeekStart = $('#weekStart');
    $selectWeekEnd = $('#weekEnd');

    $selectYear.change(function () {
        $('#total-pays').hide();

        $selectWeekStart.empty();
        $selectWeekStart.val('');
        $selectWeekStart.trigger('change');
        $selectWeekEnd.empty();
        $selectWeekEnd.val('');
        $selectWeekEnd.trigger('change');

        let year =  $selectYear.val();
        console.log(year);
        if ( year != null || year != undefined )
        {
            $.get( "/dashboard/get/weeks/total/bruto/"+year, function( data ) {
                $selectWeekStart.append($("<option>", {
                    value: '',
                    text: ''
                }));
                for ( var i=0; i<data.length; i++ )
                {
                    $selectWeekStart.append($("<option>", {
                        value: data[i].week,
                        text: "Sem "+data[i].week + "  |  Del " + data[i].dateStart +" - al "+data[i].dateEnd
                    }));
                }

                $selectWeekEnd.append($("<option>", {
                    value: '',
                    text: ''
                }));
                for ( var j=0; j<data.length; j++ )
                {
                    $selectWeekEnd.append($("<option>", {
                        value: data[j].week,
                        text: "Sem "+data[j].week + "  |  Del " + data[j].dateStart +" - al "+data[j].dateEnd
                    }));
                }
            });
        }

    });

    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    $('#btn-pays').on('click', searchTotalPays);
    //fillTotalHoursTable();

    $('#btn-download').on('click', downloadExcelTotalDiary);

});

let $selectYear;
let $selectWeekStart;
let $selectWeekEnd;

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

function searchTotalPays() {
    var year  = $('#year').val();
    var weekStart  = $('#weekStart').val();
    var weekEnd  = $('#weekEnd').val();

    if ( year == '' || year == null )
    {
        toastr.error('Seleccione un año', 'Error',
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

    if ( weekStart != '' || weekEnd != '' )
    {
        if ( parseInt(weekStart) > parseInt(weekEnd) )
        {
            toastr.error('El orden de las semanas es incorrecta.', 'Error',
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
    }

    if ( weekStart == '' || weekEnd == '' )
    {
        console.log('Sin semanas');
        $.confirm({
            icon: 'fas fa-search',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó semanas',
            content: 'Si no hay fechas se mostrarán los pagos de todo el año',
            buttons: {
                confirm: {
                    text: 'BUSCAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        $("#total-pays-load").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });

                        var query = {
                            year: year,
                            weekStart: weekStart,
                            weekEnd: weekEnd
                        };

                        $.ajax({
                            url: "/dashboard/get/total/bruto/by/year/week/?"  + $.param(query),
                            type: 'GET',
                            dataType: 'json',
                            success: function (json) {
                                $('#total-pays').html('');

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

                                $("#total-pays-load").LoadingOverlay("hide", true);

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
            title: 'Buscar pagos desde la SEM '+weekStart+' hasta la SEM '+weekEnd,
            content: 'Se buscarán los pagos desde la semana indicada',
            buttons: {
                confirm: {
                    text: 'BUSCAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        $("#total-pays-load").LoadingOverlay("show", {
                            background  : "rgba(236, 91, 23, 0.5)"
                        });

                        var query = {
                            year: year,
                            weekStart: weekStart,
                            weekEnd: weekEnd
                        };

                        $.ajax({
                            url: "/dashboard/get/total/bruto/by/year/week/?"  + $.param(query),
                            type: 'GET',
                            dataType: 'json',
                            success: function (json) {
                                $('#total-pays').html('');

                                for (var i=0; i<json.weeks.length; i++)
                                {
                                    console.log(json.weeks[i].week);
                                    renderTemplateTotalPays(
                                        json.weeks[i].year,
                                        json.weeks[i].week,
                                        json.weeks[i].title,
                                        json.weeks[i].boletas
                                    );
                                }

                                $("#total-pays-load").LoadingOverlay("hide", true);

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

}

function renderTemplateTotalPays(year, week, title, boletas ) {

    var clone = activateTemplate('#template-totalPays');

    clone.querySelector("[data-semana]").innerHTML = title;

    var render = clone.querySelector("[data-pays]");

    for (let i = 0; i <boletas.length ; i++) {
        var clone2 = activateTemplate('#template-pays');
        clone2.querySelector("[data-codigo]").innerHTML = boletas[i]["codigo"];
        clone2.querySelector("[data-trabajador]").innerHTML = boletas[i]["trabajador"];
        clone2.querySelector("[data-monto]").innerHTML = boletas[i]["monto"];

        render.append(clone2);
    }

    $('#total-pays').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

