let $value_assign_family;
let $value_essalud;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $(document).on('click','[data-tab]', changeTab);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('#btn-prev').on('click', changePrevYear);
    $('#btn-next').on('click', changeNextYear);

    $('#btn-download').on('click', downloadExcelAssistance);

});

function downloadExcelAssistance() {
    var yearCurrent = $('#yearCurrent').val();
    var monthCurrent = $('#monthCurrent').val();

    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    $.confirm({
        icon: 'fas fa-file-excel',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: 'Descargar asistencias de '+meses[monthCurrent-1]+' del año '+yearCurrent,
        content: 'Se descargará las asistencias del mes y año indicados en la pantalla',
        buttons: {
            confirm: {
                text: 'DESCARGAR',
                action: function (e) {
                    //$.alert('Descargado igual');
                    console.log(monthCurrent);
                    console.log(yearCurrent);

                    var query = {
                        year: yearCurrent,
                        month: monthCurrent
                    };

                    $.alert('Descargando archivo ...');

                    var url = "/dashboard/download/excel/assistance/?" + $.param(query);

                    window.location = url;

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Exportación cancelada.");
                },
            },
        },
    });
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function changePrevYear() {
    var yearCurrent = parseInt( $(this).parent().next().children().attr('data-year') );
    var prevYear = yearCurrent - 1;
    $(this).parent().next().children().attr('data-year', prevYear);
    $(this).parent().next().children().html(prevYear);
    $('#yearCurrent').val(prevYear);

    var ref_tab = $("div.nav-tabs a.active");
    console.log(ref_tab.attr('data-tab'));
    var id_tab = ref_tab.attr('data-tab');
    var month = ref_tab.attr('data-month');
    var year = $('#yearCurrent').val();

    $.ajax({
        url: "/dashboard/get/assistance/"+month+"/"+year,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var arrayAssistances = json.arrayAssistances;
            var arrayWeekWithDays = json.arrayWeekWithDays;
            var arraySummary = json.arraySummary;
            console.log(arrayAssistances);
            console.log(arrayWeekWithDays);

            renderTemplateAssistances(arrayAssistances, arrayWeekWithDays, id_tab, arraySummary);

        }
    });
}

function changeNextYear() {
    var yearCurrent = parseInt( $(this).parent().prev().children().attr('data-year') );
    var nextYear = yearCurrent + 1;
    $(this).parent().prev().children().attr('data-year', nextYear);
    $(this).parent().prev().children().html(nextYear);
    $('#yearCurrent').val(nextYear);

    var ref_tab = $("div.nav-tabs a.active");
    console.log(ref_tab.attr('data-tab'));
    var id_tab = ref_tab.attr('data-tab');
    var month = ref_tab.attr('data-month');
    var year = $('#yearCurrent').val();

    $.ajax({
        url: "/dashboard/get/assistance/"+month+"/"+year,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var arrayAssistances = json.arrayAssistances;
            var arrayWeekWithDays = json.arrayWeekWithDays;
            var arraySummary = json.arraySummary;
            console.log(arrayAssistances);
            console.log(arrayWeekWithDays);

            renderTemplateAssistances(arrayAssistances, arrayWeekWithDays, id_tab, arraySummary);

        }
    });
}

function changeTab() {
    var id_tab = $(this).data('tab');
    var month = $(this).data('month');
    var monthName = id_tab.substring(4);
    // Actualizar el monthCurrent
    $('#monthCurrent').val(month);
    $('#nameMonth').val(monthName);
    var year = $('#yearCurrent').val();

    $.ajax({
        url: "/dashboard/get/assistance/"+month+"/"+year,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var arrayAssistances = json.arrayAssistances;
            var arrayWeekWithDays = json.arrayWeekWithDays;
            var arraySummary = json.arraySummary;
            console.log(arrayAssistances);
            console.log(arrayWeekWithDays);
            console.log(arraySummary);
            renderTemplateAssistances(arrayAssistances, arrayWeekWithDays, id_tab, arraySummary);

        }
    });

}

function renderTemplateAssistances( arrayAssistances, arrayWeekWithDays, id_tab, arraySummary ) {

    $('#'+id_tab).html('');

    var clone = activateTemplate('#template-complete');
    var bodyWeeks = clone.querySelector("[data-bodyweeks]");
    var titulos = clone.querySelector("[data-bodytitles]");
    var bodyAssistances = clone.querySelector("[data-bodyassists]");
    var bodySummary = clone.querySelector("[data-bodySummary]");
    $('#'+id_tab).append(clone);

    for (var i = 0; i < arrayWeekWithDays.length; i++) {
        var clone2 = activateTemplate('#template-week');
        clone2.querySelector("[data-week]").innerHTML = arrayWeekWithDays[i]['week'];
        var days = '';
        for (var j = 0; j < arrayWeekWithDays[i]['days'].length ; j++) {
            days = days + '<span class="bg-gradient-success p-1">'+ arrayWeekWithDays[i]['days'][j] +'</span> ';
        }
        clone2.querySelector("[data-days]").innerHTML = days;
        bodyWeeks.append(clone2);
    }

    console.log(titulos);

    var titles = '<th class="col-md-3" >Trabajador</th>';
    for (var k = 0; k < arrayAssistances[0]['assistances'].length; k++) {
        titles = titles + '<th style="width:35px;background-color:'+arrayAssistances[0]['assistances'][k]['bg_color'] +'">'+arrayAssistances[0]['assistances'][k]['number_day']+'</th>'
    }
    titulos.innerHTML = titles;

    for (var l = 0; l < arrayAssistances.length ; l++) {
        var clone3 = activateTemplate('#template-assistance');
        var assistances = '<td class="col-md-3" >' + arrayAssistances[l]['worker'] +'</td>';
        for (var m = 0; m < arrayAssistances[l]['assistances'].length; m++) {
            var color = (arrayAssistances[l]["assistances"][m]["status"] === "N") ? "color:black":"color:white";
            var background = arrayAssistances[l]['assistances'][m]['color'];
            var td_background = arrayAssistances[l]['assistances'][m]['bg_color'];
            assistances = assistances + '<td style="width:35px; ' + color +';background-color: '+ td_background + '"><span style="display:block; text-align:center; margin:0 auto;padding: 1px;background-color:'+background+' ">'+arrayAssistances[l]['assistances'][m]['status']+'</span></td>'
        }
        clone3.querySelector("[data-bodyassistances]").innerHTML = assistances;
        bodyAssistances.append(clone3);
    }
    bodySummary.innerHTML = '';
    for (var t = 0; t < arraySummary.length ; t++) {
        console.log(arraySummary[t]['cantA']);
        var clone4 = activateTemplate('#template-summary');
        clone4.querySelector("[data-summaryworker]").innerHTML = arraySummary[t]['worker'];
        clone4.querySelector("[data-canta]").innerHTML = arraySummary[t]['cantA'];
        clone4.querySelector("[data-cantf]").innerHTML = arraySummary[t]['cantF'];
        clone4.querySelector("[data-cantt]").innerHTML = arraySummary[t]['cantT'];
        clone4.querySelector("[data-cantm]").innerHTML = arraySummary[t]['cantM'];
        clone4.querySelector("[data-cantj]").innerHTML = arraySummary[t]['cantJ'];
        clone4.querySelector("[data-cantv]").innerHTML = arraySummary[t]['cantV'];
        clone4.querySelector("[data-cantp]").innerHTML = arraySummary[t]['cantP'];
        clone4.querySelector("[data-cants]").innerHTML = arraySummary[t]['cantS'];
        clone4.querySelector("[data-canth]").innerHTML = arraySummary[t]['cantH'];
        clone4.querySelector("[data-cantl]").innerHTML = arraySummary[t]['cantL'];
        bodySummary.append(clone4);
    }
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

