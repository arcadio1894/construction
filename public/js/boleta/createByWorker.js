$(document).ready(function () {

    $selectType = $('#type');
    $selectYear = $('#year');
    $selectMonth = $('#month');
    $selectWeek = $('#week');

    $selectWorker = $('#worker');

    $selectType.change(function () {
        var type =  $selectType.val();
        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');
        $selectMonth.empty();
        $selectMonth.val('');
        $selectMonth.trigger('change');
        $selectYear.empty();
        $selectYear.val('');
        $selectYear.trigger('change');

        if ( type == 1 )
        {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $.get( "/dashboard/get/years/of/system/", function( data ) {
            $selectYear.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectYear.append($("<option>", {
                    value: data[i].year,
                    text: data[i].year
                }));
            }
        });

    });

    $selectYear.change(function () {
        var type =  $selectType.val();
        if ( type == 1 ) {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $selectMonth.empty();
        $selectMonth.val('');
        $selectMonth.trigger('change');
        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');

        let year =  $selectYear.val();
        console.log(year);
        if ( year != null || year != undefined )
        {
            $.get( "/dashboard/get/months/of/year/"+year, function( data ) {
                $selectMonth.append($("<option>", {
                    value: '',
                    text: ''
                }));
                for ( var i=0; i<data.length; i++ )
                {
                    $selectMonth.append($("<option>", {
                        value: data[i].month,
                        text: data[i].month_name
                    }));
                }
            });
        }

    });

    $selectMonth.change(function () {
        var type =  $selectType.val();
        if ( type == 1 ) {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');

        let year =  $selectYear.val();
        let month =  $selectMonth.val();

        console.log(year);
        console.log(month);

        if ( (year != null || year != undefined) && (month != null || month != undefined) )
        {
            $.get( "/dashboard/get/weeks/of/month/"+month+"/year/"+year, function( data ) {
                $selectWeek.append($("<option>", {
                    value: '',
                    text: ''
                }));
                for ( var i=0; i<data.length; i++ )
                {
                    $selectWeek.append($("<option>", {
                        value: data[i].week,
                        text: data[i].week
                    }));
                }
            });
        }

    });
    
    $('#btn-generate').on('click', generateBoletaWorker);
});

let $selectType;
let $selectYear;
let $selectMonth;
let $selectWeek;
let $selectWorker;

function generateBoletaWorker() {

    let worker = $selectWorker.val();
    let type = $selectType.val();
    let year = $selectYear.val();
    let month = $selectMonth.val();
    let week = $selectWeek.val();

    if ( type == 1 )
    {
        // Si es semanal
        var query = {
            worker: worker,
            type: type,
            year: year,
            month: month,
            week: week
        };

        $.get( "/dashboard/generate/boleta/worker?" + $.param(query), function( data ) {
            console.log( data );
        }).done(function(data) {
            $("#empresa").html('EMPRESA: '+data.empresa);
            $("#ruc").html('RUC: '+data.ruc);
            $("#codigo").html('CÓDIGO: '+data.codigo);
            $("#semana").html('SEMANA: '+data.semana);
            $("#nombre").html('NOMBRE: '+data.nombre);
            $("#fecha").html('FECHA: '+data.fecha);
            $("#cargo").html('CARGO: '+data.cargo);
            $("#pagoxdia").html(data.pagoXDia);
            $("#sistemaPension").html(data.sistemaPension);
            $("#montoSistemaPension").html(data.montoSistemaPension);
            $("#essalud").html(data.essalud);
            $("#pagoXHora").html(data.pagoXHora);
            $("#rentaQuintaCat").html(data.rentaQuintaCat);
            $("#pensionDeAlimentos").html(data.pensionDeAlimentos);
            $("#asignacionFamiliarDiaria").html(data.asignacionFamiliarDiaria);
            $("#asignacionFamiliarSemanal").html(data.asignacionFamiliarSemanal);
            $("#prestamo").html(data.prestamo);
            $("#horasOrdinarias").html(data.horasOrdinarias);
            $("#montoHorasOrdinarias").html(data.montoHorasOrdinarias);
            $("#horasAl25").html(data.horasAl25);
            $("#montoHorasAl25").html(data.montoHorasAl25);
            $("#totalDescuentos").html(data.totalDescuentos);
            $("#totalDescuentos1").html(data.totalDescuentos);
            $("#horasAl35").html(data.horasAl35);
            $("#montoHorasAl35").html(data.montoHorasAl35);
            $("#horasAl100").html(data.horasAl100);
            $("#montoHorasAl100").html(data.montoHorasAl100);
            $("#dominical").html(data.dominical);
            $("#montoDominical").html(data.montoDominical);
            $("#vacaciones").html(data.vacaciones);
            $("#montoVacaciones").html(data.montoVacaciones);
            $("#totalIngresos1").html(data.totalIngresos);
            $("#reintegro").html(data.reintegro);
            $("#gratificaciones").html(data.gratificaciones);
            $("#totalIngresos").html('TOTAL INGRESOS: '+data.totalIngresos);
            $("#totalNetoPagar").html(data.totalNetoPagar);
            console.log( data );
        }).fail(function(data) {
            console.log( data );
        });
    } else {
        // Si es mensual
        var query2 = {
            worker: worker,
            type: type,
            year: year,
            month: month,
            week: week
        };

        $.get( "/dashboard/generate/boleta/worker?" + $.param(query2), function( data ) {
            console.log( data );
        }).done(function(data) {
            console.log( data );
        }).fail(function(data) {
            console.log( data );
        });
    }
}
