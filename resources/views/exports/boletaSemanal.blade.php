<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        table,
        thead,
        tr,
        tbody,
        th,
        td {
            text-align: center;
        }

        .table td {
            text-align: center;
        }
        .datepicker {
            z-index: 10000 !important;
        }
        .tg  {
            border-collapse: separate;
            border-spacing:0;
        }
        .tg td{
            border-color:black;
            border-style:solid;
            border-width:0.5px;
            font-family:Arial, sans-serif;
            font-size:13px;
            padding:5px 5px;
            word-break:break-word;
        }
        .tg th{
            border-color:black;
            border-style:solid;
            border-width:1px;
            font-family:Arial, sans-serif;
            font-size:13px;
            font-weight:normal;
            padding:5px 5px;
            word-break:break-word;
            border-collapse: separate;
        }
        .tg .tg-0pky{
            text-align:left;
            vertical-align:top;
            border-collapse: separate;

        }

        .numbers {
            text-align:right !important;
        }

    </style>
    <meta charset="utf-8">
    <title>Boleta Semanal</title>
</head>
<body>
<table class="tg center" style="table-layout: auto; width: 100%">
    <tbody>
    <tr>
        <td class="tg-0pky" colspan="6"></td>
        <td class="tg-0pky" colspan="3" rowspan="4"><br><img width="180px" src="{{ asset('admin/dist/img/Logo.png') }}" alt=""></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="6" id="empresa">Empresa: {{ $boleta->empresa }}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="6" id="ruc">RUC: {{ $boleta->ruc }}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="6"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="3" id="codigo">Código: {{ $boleta->codigo }}</td>
        <td class="tg-0pky" colspan="6" id="semana">Semana: {{ $boleta->semana }}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="3" id="nombre">Nombre: {{ $boleta->nombre }}</td>
        <td class="tg-0pky" colspan="6" id="fecha">Fecha: {{ $boleta->fecha }}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="3" id="cargo">Cargo: {{ $boleta->cargo }}</td>
        <td class="tg-0pky" colspan="6"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="3"></td>
        <td class="tg-0pky" colspan="6" style="border-bottom-color: #ffffff"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="3">INGRESOS</td>
        <td class="tg-0pky" rowspan="7" style="border-color: #ffffff"></td>
        <td class="tg-0pky">DESCUENTOS</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky" rowspan="2" style="border-color: #ffffff"></td>
        <td class="tg-0pky">APORTE</td>
        <td class="tg-0pky"></td>
    </tr>
    <tr>
        <td class="tg-0pky">PAGO x DIA</td>
        <td class="tg-0pky numbers" id="pagoxdia">{{ $boleta->pagoxdia }}</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky" id="sistemaPension">{{ $boleta->sistemaPension }}</td>
        <td class="tg-0pky numbers" id="montoSistemaPension">{{ $boleta->montoSistemaPension }}</td>
        <td class="tg-0pky">ESSALUD</td>
        <td class="tg-0pky numbers" id="essalud">{{ $boleta->essalud }}</td>
    </tr>
    <tr>
        <td class="tg-0pky">PAGO x HORA</td>
        <td class="tg-0pky numbers" id="pagoXHora">{{ $boleta->pagoXHora }}</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky">RENTA 5° CAT</td>
        <td class="tg-0pky numbers" id="rentaQuintaCat">{{ $boleta->rentaQuintaCat }}</td>
        <td class="tg-0pky" colspan="3" rowspan="5" style="border-bottom-color: #ffffff;border-top-color: #ffffff;border-left-color: #ffffff"></td>
    </tr>
    <tr>
        <td class="tg-0pky">DIAS TRAB.</td>
        <td class="tg-0pky numbers" id="diasTrabajados">{{ $boleta->diasTrabajados }}</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky">PENSION</td>
        <td class="tg-0pky numbers" id="pensionDeAlimentos">{{ $boleta->pensionDeAlimentos }}</td>
    </tr>
    <tr>
        <td class="tg-0pky">ASIG. FAMILIAR</td>
        <td class="tg-0pky numbers" id="asignacionFamiliarDiaria">{{ $boleta->asignacionFamiliarDiaria }}</td>
        <td class="tg-0pky numbers" id="asignacionFamiliarSemanal">{{ $boleta->asignacionFamiliarSemanal }}</td>
        <td class="tg-0pky">PRÉSTAMOS</td>
        <td class="tg-0pky numbers" id="prestamo">{{ $boleta->prestamo }}</td>
    </tr>
    <tr>
        <td class="tg-0pky">H. ORDINAR</td>
        <td class="tg-0pky numbers" id="horasOrdinarias">{{ $boleta->horasOrdinarias }}</td>
        <td class="tg-0pky numbers" id="montoHorasOrdinarias">{{ $boleta->montoHorasOrdinarias }}</td>
        <td class="tg-0pky">OTROS</td>
        <td class="tg-0pky numbers" id="otros">{{ $boleta->otros }}</td>
    </tr>
    <tr>
        <td class="tg-0pky">H. AL 25%</td>
        <td class="tg-0pky numbers" id="horasAl25">{{ $boleta->horasAl25 }}</td>
        <td class="tg-0pky numbers" id="montoHorasAl25">{{ $boleta->montoHorasAl25 }}</td>
        <td class="tg-0pky">TOTAL DESC</td>
        <td class="tg-0pky numbers" id="totalDescuentos">{{ $boleta->totalDescuentos }}</td>
    </tr>
    <tr>
        <td class="tg-0pky">H. AL 35%</td>
        <td class="tg-0pky numbers" id="horasAl35">{{ $boleta->horasAl35 }}</td>
        <td class="tg-0pky numbers" id="montoHorasAl35">{{ $boleta->montoHorasAl35 }}</td>
        <td class="tg-0pky" colspan="6" rowspan="2" style="border-top-color: #ffffff;border-left-color: #ffffff;border-bottom-color: #ffffff"></td>
    </tr>
    <tr>
        <td class="tg-0pky">H. AL 100%</td>
        <td class="tg-0pky numbers" id="horasAl100">{{ $boleta->horasAl100 }}</td>
        <td class="tg-0pky numbers" id="montoHorasAl100">{{ $boleta->montoHorasAl100 }}</td>
    </tr>
    <tr>
        <td class="tg-0pky">DOMINICAL</td>
        <td class="tg-0pky numbers" id="dominical">{{ $boleta->dominical }}</td>
        <td class="tg-0pky numbers" id="montoDominical">{{ $boleta->montoDominical }}</td>
        <td class="tg-0pky" rowspan="8" style="border-color: #ffffff"></td>
        <td class="tg-0pky" colspan="4" style="border: 1px solid black;">RESUMEN</td>
        <td class="tg-0pky" rowspan="8" style="border-top-color: #ffffff;border-bottom-color: #ffffff;border-left-color: #ffffff"></td>
    </tr>
    <tr>
        <td class="tg-0pky">VACACIONES</td>
        <td class="tg-0pky numbers" id="vacaciones">{{ $boleta->vacaciones }}</td>
        <td class="tg-0pky numbers" id="montoVacaciones">{{ $boleta->montoVacaciones }}</td>
        <td class="tg-0pky" colspan="2">TOTAL INGRESOS</td>
        <td class="tg-0pky numbers" colspan="2" id="totalIngresos1">{{ $boleta->totalIngresos }}</td>
    </tr>
    <tr>
        <td class="tg-0pky">REINTEGRO</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky numbers" id="reintegro">{{ $boleta->reintegro }}</td>
        <td class="tg-0pky" colspan="2">TOTAL DESCUENTOS</td>
        <td class="tg-0pky numbers" colspan="2" id="totalDescuentos1">{{ $boleta->totalDescuentos }}</td>
    </tr>
    <tr>
        <td class="tg-0pky">GRATIFICACIÓN</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky numbers" id="gratificaciones">{{ $boleta->gratificaciones }}</td>
        <td class="tg-0pky" colspan="4" rowspan="4"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="3"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="3" id="totalIngresos">TOTAL INGRESOS: {{ $boleta->totalIngresos }}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="3" rowspan="2"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2">NETO A  PAGAR</td>
        <td class="tg-0pky numbers" colspan="2" id="totalNetoPagar">{{ $boleta->totalNetoPagar }}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="9" rowspan="11" style="border-top-color: #ffffff"></td>
    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    <tr>

    </tr>
    </tbody>
</table>
</body>
</html>