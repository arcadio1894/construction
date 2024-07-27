<!DOCTYPE html>
<html lang="en">
<head>
    <style>

        body {
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        /*#table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }
        #table th,
        #table td {
            text-align: center;
        }
        #table tr th {
            padding: 5px 10px;
            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: bold;
            background-color: #1c3c80;
            font-size: 1.2em;
            text-align: center;
            vertical-align: center;
        }
        #table td {
            padding: 5px;
            text-align: center;
        }*/
        #table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }
        #table th,
        #table td {
            text-align: center;
            vertical-align: middle; /* Asegura que el contenido esté centrado verticalmente */
        }
        #table tr th {
            padding: 5px 10px;
            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: bold;
            background-color: #1c3c80;
            font-size: 1.2em;
            text-align: center;
            vertical-align: middle; /* Corrige a 'middle' en lugar de 'center' */
        }
        #table td {
            padding: 5px;
            text-align: center;
            vertical-align: middle; /* Asegura que el contenido esté centrado verticalmente */
        }

        .trabajador-title {
            background-color: #1c3c80; /* Color deseado para el fondo */
            color: white; /* Color deseado para el texto */
            text-align: center;
        }
        .ingresos-title {
            font-weight: bold;
            vertical-align: center;
            text-align: center;
            word-wrap: break-word;
            background-color: #087019;
            font-size: 12px;
            color: white
        }
        .descuentos-title {
            font-weight: bold;
            vertical-align: center;
            text-align: center;
            word-wrap: break-word;
            background-color: #D31325;
            font-size: 12px;
            color: white
        }
    </style>

</head>
<body>
<h2> {{ $title }}</h2>
<h3> {{ $subtitle }}</h3>
<table id="table">
    <thead>
        <tr>
            <th colspan="7" class="trabajador-title" style="font-size: 12px;font-weight: bold;background-color: #1c3c80;color: #ffffff;text-align: center;">INFORMACIÓN DEL TRABAJADOR</th>
            <th colspan="13" class="ingresos-title" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019;font-size: 12px;color: white">INGRESOS</th>
            <th colspan="1" class="trabajador-title" style="font-size: 12px;font-weight: bold;background-color: #EAEE0A;color: #ffffff;text-align: center;"></th>
            <th colspan="5" class="descuentos-title" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #D31325;font-size: 12px;color: white">DESCUENTOS</th>
            <th colspan="1" class="trabajador-title" style="font-size: 12px;font-weight: bold;background-color: #EAEE0A;color: #ffffff;text-align: center;"></th>
            <th colspan="1" class="trabajador-title" style="font-size: 12px;font-weight: bold;background-color: #1c3c80;color: #ffffff;text-align: center;"></th>
        </tr>
        <tr>
            <th width="25px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #1c3c80; font-size: 12px; color: white">N°</th>
            <th width="260px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #1c3c80; font-size: 12px; color: white">Trabajador</th>
            <th width="70px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #1c3c80; font-size: 12px; color: white">Sueldo Diario</th>
            <th width="70px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #1c3c80; font-size: 12px; color: white">Pago Por Hora</th>
            <th width="90px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #1c3c80; font-size: 12px; color: white">Dias Trabajados</th>
            <th width="90px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #1c3c80; font-size: 12px; color: white">Dias Dominical</th>
            <th width="90px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #1c3c80; font-size: 12px; color: white">Horas Trabajadas</th>
            <th width="65px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">H. Ord.</th>
            <th width="65px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">H. Al 25%</th>
            <th width="65px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">H. Al 35%</th>
            <th width="65px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">H. Al 100%</th>
            <th width="65px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">Asig. Fam.</th>
            <th width="75px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">BASE</th>
            <th width="65px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">Monto H. 25%</th>
            <th width="65px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">Monto H. 35%</th>
            <th width="65px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">Monto H. 100%</th>
            <th width="80px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">Dominical</th>
            <th width="80px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">Bonos Esp.</th>
            <th width="90px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">Vacaciones</th>
            <th width="80px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #087019; font-size: 12px; color: white">Gratific.</th>

            <th width="150px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #EAEE0A; font-size: 12px; color: black">REMUNERACION BRUTA</th>

            <th width="70px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #D31325; font-size: 12px; color: white">AFP</th>
            <th width="70px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #D31325; font-size: 12px; color: white">Renta</th>
            <th width="85px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #D31325; font-size: 12px; color: white">Prestamos</th>
            <th width="85px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #D31325; font-size: 12px; color: white">Desc. Judic.</th>
            <th width="75px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #D31325; font-size: 12px; color: white">Otros</th>

            <th width="150px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #EAEE0A; font-size: 12px; color: black">REMUNERACION NETA</th>

            <th width="85px" style="font-weight: bold;vertical-align: center;text-align: center;word-wrap: break-word;background-color: #1c3c80; font-size: 12px; color: white">ESSALUD</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($haberes); $i++ )
        <tr>
            <th width="25px">{{ $haberes[$i]['codigo'] }}</th>
            <th width="260px" style="word-wrap: break-word">{{ $haberes[$i]['trabajador'] }}</th>
            <th width="70px">{{ $haberes[$i]['sueldoDiario'] }}</th>
            <th width="70px">{{ $haberes[$i]['pagoXHora'] }}</th>
            <th width="90px">{{ $haberes[$i]['diasTrabajados'] }}</th>
            <th width="65px">{{ $haberes[$i]['diasDominical'] }}</th>
            <th width="90px">{{ $haberes[$i]['horasTrabajadas'] }}</th>
            <th width="65px">{{ $haberes[$i]['horasOrdinarias'] }}</th>
            <th width="65px">{{ $haberes[$i]['horasAl25'] }}</th>
            <th width="65px">{{ $haberes[$i]['horasAl35'] }}</th>
            <th width="65px">{{ $haberes[$i]['horasAl100'] }}</th>
            <th width="65px">{{ $haberes[$i]['asignacionFamiliarSemanal'] }}</th>
            <th width="75px">{{ $haberes[$i]['base'] }}</th>

            <th width="65px">{{ $haberes[$i]['montoHorasAl25'] }}</th>
            <th width="65px">{{ $haberes[$i]['montoHorasAl35'] }}</th>
            <th width="65px">{{ $haberes[$i]['montoHorasAl100'] }}</th>
            <th width="80px">{{ $haberes[$i]['dominical'] }}</th>
            <th width="80px">{{ $haberes[$i]['bonosEspeciales'] }}</th>
            <th width="90px">{{ $haberes[$i]['vacaciones'] }}</th>
            <th width="80px">{{ $haberes[$i]['gratificaciones'] }}</th>
            <th width="150px">{{ $haberes[$i]['remuneracionBruta'] }}</th>

            <th width="70px">{{ $haberes[$i]['afp'] }}</th>
            <th width="70px">{{ $haberes[$i]['renta'] }}</th>
            <th width="85px">{{ $haberes[$i]['prestamo'] }}</th>
            <th width="85px">{{ $haberes[$i]['descJudiciales'] }}</th>
            <th width="75px">{{ $haberes[$i]['otros'] }}</th>
            <th width="150px">{{ $haberes[$i]['remuneracionNeta'] }}</th>
            <th width="85px">{{ $haberes[$i]['essalud'] }}</th>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>