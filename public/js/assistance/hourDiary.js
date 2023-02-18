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
        title: 'Descargar horas diarias de '+meses[monthCurrent-1]+' del año '+yearCurrent,
        content: 'Se descargará las horas diarias del mes y año indicados en la pantalla',
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

                    var url = "/dashboard/download/excel/hours/diary/?" + $.param(query);

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
        url: "/dashboard/get/hour/diary/"+month+"/"+year,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var arrayDays = json.arrayDays;
            var arrayHeaders = json.arrayHeaders;
            var arrayAssistances = json.arrayAssistances;
            console.log(arrayDays);
            console.log(arrayHeaders);
            console.log(arrayAssistances);

            renderTemplateAssistances(arrayDays, arrayHeaders, arrayAssistances, id_tab);

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
        url: "/dashboard/get/hour/diary/"+month+"/"+year,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var arrayDays = json.arrayDays;
            var arrayHeaders = json.arrayHeaders;
            var arrayAssistances = json.arrayAssistances;
            console.log(arrayDays);
            console.log(arrayHeaders);
            console.log(arrayAssistances);

            renderTemplateAssistances(arrayDays, arrayHeaders, arrayAssistances, id_tab);

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
        url: "/dashboard/get/hour/diary/"+month+"/"+year,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var arrayDays = json.arrayDays;
            var arrayHeaders = json.arrayHeaders;
            var arrayAssistances = json.arrayAssistances;
            console.log(arrayDays);
            console.log(arrayHeaders);
            console.log(arrayAssistances);

            renderTemplateAssistances(arrayDays, arrayHeaders, arrayAssistances, id_tab);

        }
    });

}

function renderTemplateAssistances( arrayDays, arrayHeaders, arrayAssistances, id_tab ) {

    $('#'+id_tab).html('');

    var clone = activateTemplate('#template-complete');
    var titulos = clone.querySelector("[data-bodytitles]");
    var encabezados = clone.querySelector("[data-bodyheader]");
    var bodyAssistances = clone.querySelector("[data-bodyassists]");
    $('#'+id_tab).append(clone);
    console.log(titulos);

    var titles = '<th style="background-color:#203764; color: #ffffff;" >DATOS</th>';
    for (var k = 0; k < arrayDays.length; k++) {
        titles = titles + '<th colspan="'+arrayDays[k]['colspan']+'" style="background-color:'+arrayDays[k]['color'] +'">'+arrayDays[k]['nameDay']+'</th>';
    }
    titulos.innerHTML = titles;
    console.log(titles);

    var headers = '<th style="background-color:#203764; color: #ffffff;" >APELLIDOS Y NOMBRES</th>';
    for (var i = 0; i < arrayHeaders.length; i++) {
        for (var j = 0; j < (arrayHeaders[i].length - 1); j++) {
            headers = headers + '<th style="background-color:' + ( (j == arrayHeaders[i].length - 2) ? '#FFC000' : arrayHeaders[i][arrayHeaders[i].length - 1] ) + '">' + arrayHeaders[i][j] + '</th>';
        }
    }

    encabezados.innerHTML = headers;
    console.log(headers);

    for (var k = 0; k < arrayAssistances.length ; k++) {
        var clone3 = activateTemplate('#template-assistance');
        var assistances = '<td style="background-color:#203764; color: #ffffff;" >' + arrayAssistances[k]['worker'] +'</td>';
        for (var l = 0; l < arrayAssistances[k]['assistances'].length; l++) {
            for (var m = 0; m < arrayAssistances[k]['assistances'][l].length-1; m++) {

                assistances = assistances + '<td style="background-color:'+ ( (m === arrayAssistances[k]['assistances'][l].length-2) ? '#FFC000': arrayAssistances[k]['assistances'][l][arrayAssistances[k]['assistances'][l].length-1] ) + '">'+arrayAssistances[k]['assistances'][l][m]+'</td>';
            }
        }
        clone3.querySelector("[data-bodyassistances]").innerHTML = assistances;
        bodyAssistances.append(clone3);
    }

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

