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

});

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
            console.log(arrayAssistances);
            console.log(arrayWeekWithDays);

            renderTemplateAssistances(arrayAssistances, arrayWeekWithDays, id_tab);

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
            console.log(arrayAssistances);
            console.log(arrayWeekWithDays);

            renderTemplateAssistances(arrayAssistances, arrayWeekWithDays, id_tab);

        }
    });
}

function changeTab() {
    var id_tab = $(this).data('tab');
    var month = $(this).data('month');
    // Actualizar el monthCurrent
    $('#monthCurrent').val(month);
    var year = $('#yearCurrent').val();

    $.ajax({
        url: "/dashboard/get/assistance/"+month+"/"+year,
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            var arrayAssistances = json.arrayAssistances;
            var arrayWeekWithDays = json.arrayWeekWithDays;
            console.log(arrayAssistances);
            console.log(arrayWeekWithDays);

            renderTemplateAssistances(arrayAssistances, arrayWeekWithDays, id_tab);

        }
    });

}

function renderTemplateAssistances( arrayAssistances, arrayWeekWithDays, id_tab ) {

    $('#'+id_tab).html('');

    var clone = activateTemplate('#template-complete');
    var bodyWeeks = clone.querySelector("[data-bodyweeks]");
    var titulos = clone.querySelector("[data-bodytitles]");
    var bodyAssistances = clone.querySelector("[data-bodyassists]");
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
        titles = titles + '<th style="width:35px">'+arrayAssistances[0]['assistances'][k]['number_day']+'</th>'
    }
    titulos.innerHTML = titles;

    for (var l = 0; l < arrayAssistances.length ; l++) {
        var clone3 = activateTemplate('#template-assistance');
        var assistances = '<td class="col-md-3" >' + arrayAssistances[l]['worker'] +'</td>';
        for (var m = 0; m < arrayAssistances[l]['assistances'].length; m++) {
            var color = (arrayAssistances[l]["assistances"][m]["status"] === "N") ? "color:black":"color:white";
            var background = arrayAssistances[l]['assistances'][m]['color'];
            assistances = assistances + '<td style="width:35px; ' + color +';background-color: '+ background + '">'+arrayAssistances[l]['assistances'][m]['status']+'</td>'
        }
        clone3.querySelector("[data-bodyassistances]").innerHTML = assistances;
        bodyAssistances.append(clone3);
    }

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

