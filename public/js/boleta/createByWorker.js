$(document).ready(function () {

    $selectType = $('#type');
    $selectYear = $('#year');
    $selectMonth = $('#month');
    $selectWeek = $('#week');

    $selectWorker = $('#worker');

    $selectType.change(function () {
        $('#boleta-semanal').hide();
        $('#boleta-mensual').hide();

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
        $('#boleta-semanal').hide();
        $('#boleta-mensual').hide();

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
        $('#boleta-semanal').hide();
        $('#boleta-mensual').hide();

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

    $selectWeek.change(function () {
        $('#boleta-semanal').hide();
        $('#boleta-mensual').hide();

    });
    
    $('#btn-generate').on('click', generateBoletaWorker);

    $('#btn-save-month').on('click', saveBoletaWorkerMonthly);

    $('#btn-save-week').on('click', saveBoletaWorkerWeekly);

});

let $selectType;
let $selectYear;
let $selectMonth;
let $selectWeek;
let $selectWorker;

let $dataBoleta;

function saveBoletaWorkerMonthly() {
    let worker = $selectWorker.val();
    let type = $selectType.val();
    let year = $selectYear.val();
    let month = $selectMonth.val();
    let week = $selectWeek.val();

    var query = {
        worker: worker,
        type: type,
        year: year,
        month: month,
        week: week,
        info: $dataBoleta
    };

    $.get( "/dashboard/save/boleta/worker/month?" + $.param(query), function( data ) {
        console.log( data );
    }).done(function(data) {
        console.log( data );
    }).fail(function(data) {
        console.log( data );
    });

}

function saveBoletaWorkerWeekly() {
    let worker = $selectWorker.val();
    let type = $selectType.val();
    let year = $selectYear.val();
    let month = $selectMonth.val();
    let week = $selectWeek.val();

    var query = {
        worker: worker,
        type: type,
        year: year,
        month: month,
        week: week,
        info: $dataBoleta
    };

    $.get( "/dashboard/save/boleta/worker/week?" + $.param(query), function( data ) {
        console.log( data );
    }).done(function(data) {
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

    }).fail(function(data) {
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

    });
}

function generateBoletaWorker() {

    let worker = $selectWorker.val();
    let type = $selectType.val();
    let year = $selectYear.val();
    let month = $selectMonth.val();
    let week = $selectWeek.val();

    // TODO: Validaciones
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
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        return;
    }

    if ( year == '' || year == null )
    {
        toastr.error('Seleccione un año de la lista', 'Error',
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
        return;
    }

    if ( month == '' || month == null )
    {
        toastr.error('Seleccione un mes de la lista', 'Error',
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
        return;
    }

    if ( type == 1 )
    {
        if ( week == '' || week == null )
        {
            toastr.error('Seleccione una semana de la lista', 'Error',
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
            return;
        }
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
            $dataBoleta = data;
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
            $("#diasTrabajados").html(data.diasTrabajados);
            $("#rentaQuintaCat").html(data.rentaQuintaCat);
            $("#pensionDeAlimentos").html(data.pensionDeAlimentos);
            $("#asignacionFamiliarDiaria").html(data.asignacionFamiliarDiaria);
            $("#asignacionFamiliarSemanal").html(data.asignacionFamiliarSemanal);
            $("#prestamo").html(data.prestamo);
            $("#otros").html(data.otros);
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
            $("#montoBono").html(data.montoBonus);
            $("#vacaciones").html(data.vacaciones);
            $("#montoVacaciones").html(data.montoVacaciones);
            $("#totalIngresos1").html(data.totalIngresos);
            $("#reintegro").html(data.reintegro);
            $("#gratificaciones").html(data.gratificaciones);
            $("#totalIngresos").html('TOTAL INGRESOS: '+data.totalIngresos);
            $("#totalNetoPagar").html(data.totalNetoPagar);

            $('#boleta-semanal').show();
            $('#boleta-mensual').hide();
            console.log( data );
        }).fail(function(data) {
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
            $dataBoleta = data;
            $("#ruc_m").html(data.ruc_m);
            $("#empleador_m").html(data.empleador_m);
            $("#periodo_m").html(data.periodo_m);
            $("#tipo_m").html(data.tipo_m);
            $("#dni_m").html(data.dni_m);
            $("#empleado_m").html(data.empleado_m);
            $("#situacion_m").html(data.situacion_m);
            $("#fecha_ingreso_m").html(data.fecha_ingreso_m);
            $("#tipo_trabajador_m").html(data.tipo_trabajador_m);
            $("#sistema_pensionario_m").html(data.sistema_pensionario_m);
            $("#cuspp_m").html(data.cuspp_m);
            $("#dias_laborados_m").html(data.dias_laborados_m);
            $("#dias_no_laborados_m").html(data.dias_no_laborados_m);
            $("#dias_subsidiados_m").html(data.dias_subsidiados_m);
            $("#condicion_m").html(data.condicion_m);
            $("#jornada_ordinaria_m").html(data.jornada_ordinaria_m);
            $("#sobretiempo_m").html(data.sobretiempo_m);
            $("#trabajo_sobretiempo_25_m").html(data.trabajo_sobretiempo_25_m);
            $("#trabajo_sobretiempo_35_m").html(data.trabajo_sobretiempo_35_m);
            $("#trabajo_en_feriado_m").html(data.trabajo_en_feriado_m);
            $("#remuneracion_jornal_basico_m").html(data.remuneracion_jornal_basico_m);
            $("#bonificacion_extraordinaria_temporal_m").html(data.bonificacion_extraordinaria_temporal_m);
            $("#gratificacion_m").html(data.gratificacion_m);
            $("#comision_afp_porcentual_m").html(data.comision_afp_porcentual_m);
            $("#renta_quinta_categoria_m").html(data.renta_quinta_categoria_m);
            $("#prima_seguro_afp_m").html(data.prima_seguro_afp_m);
            $("#aportacion_obligatoria_m").html(data.aportacion_obligatoria_m);
            $("#neto_pagar_m").html(data.neto_pagar_m);
            $("#essalud_m").html(data.essalud_m);

            $('#boleta-semanal').hide();
            $('#boleta-mensual').show();
        }).fail(function(data) {
            console.log( data );
        });
    }
}
