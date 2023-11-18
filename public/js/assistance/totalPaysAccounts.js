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
            $.get( "/dashboard/get/weeks/total/pays/"+year, function( data ) {
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

    $('#btn-download').on('click', downloadExcelTotalPaysAccounts);

});

let $selectYear;
let $selectWeekStart;
let $selectWeekEnd;

function downloadExcelTotalPaysAccounts() {
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
        if ( weekStart > weekEnd )
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
            icon: 'fas fa-download',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó semanas',
            content: 'Si no hay fechas se mostrarán los pagos de todo el año',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {

                        var query2 = {
                            year: year,
                            weekStart: weekStart,
                            weekEnd: weekEnd
                        };

                        var url = "/dashboard/download/excel/pagar/finanzas/?" + $.param(query2);

                        window.location = url;
                        document.getElementById("btn-download").hidden = true;

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
            icon: 'fas fa-download',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'Descargar pagos desde la SEM '+weekStart+' hasta la SEM'+weekEnd,
            content: 'Se descargarán los pagos desde la semana indicada',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {

                        var query2 = {
                            year: year,
                            weekStart: weekStart,
                            weekEnd: weekEnd
                        };

                        var url = "/dashboard/download/excel/pagar/finanzas/?" + $.param(query2);

                        window.location = url;
                        document.getElementById("btn-download").hidden = true;

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
        if ( weekStart > weekEnd )
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
                            url: "/dashboard/get/total/pays/accounts/by/year/week/?"  + $.param(query),
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

                                $("#btn-download").removeAttr("hidden");

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
            title: 'Buscar pagos desde la SEM '+weekStart+' hasta la SEM'+weekEnd,
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
                            url: "/dashboard/get/total/pays/accounts/by/year/week/?"  + $.param(query),
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

                                $("#btn-download").removeAttr("hidden");

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
        clone2.querySelector("[data-cuentas]").innerHTML = boletas[i]["cuentas"];
        clone2.querySelector("[data-monto]").innerHTML = boletas[i]["monto"];

        render.append(clone2);
    }

    $('#total-pays').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

