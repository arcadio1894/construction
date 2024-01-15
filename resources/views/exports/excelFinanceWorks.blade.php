<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        #table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        #table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        #table th,
        #table td {
            text-align: center;
        }

        #table .desc {
            text-align: left;
        }

        #table td {
            padding: 5px;
            text-align: center;
        }

        #table td.desc {
            vertical-align: top;
        }

        #table td.unit,
        #table td.qty,
        #table td.total {
            font-size: 1em;
            text-align: right;
        }

        .center {
            text-align: center;
        }
        .letraTabla {
            font-family: "Calibri", Arial, sans-serif; /* Utiliza Calibri si está instalado, de lo contrario, usa Arial o una fuente sans-serif similar */
            font-size: 14px; /* Tamaño de fuente 11 */
        }
        .normal-title {
            background-color: #203764; /* Color deseado para el fondo */
            color: #ffffff; /* Color deseado para el texto */
            text-align: center;
        }
        .cliente-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .trabajo-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .documentacion-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .importe-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .facturacion-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .abono-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
    </style>

</head>
<body>
<h1>{{ $dates }}</h1>
<table id="table">
    <thead>
    <tr>
        <th colspan="1" style="background-color: #203764;text-align: center;"></th>
        <th colspan="3" style="background-color: #FFC000;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">INFORMACIÓN DEL CLIENTE</th>
        <th colspan="7" style="background-color: #00B050;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">INFORMACIÓN DEL TRABAJO</th>
        <th colspan="4" style="background-color: #FFC000;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">DOCUMENTACIÓN</th>
        <th colspan="10" style="background-color: #00B050;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">IMPORTE $</th>
        <th colspan="9" style="background-color: #FFC000;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">FACTURACIÓN</th>
        <th colspan="5" style="background-color: #00B050;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">PAGO/ABONO</th>
        <th colspan="2" style=" background-color: #203764;text-align: center;"></th>
    </tr>
    <tr>
        <th width="50px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Año</th>

        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Cliente</th>
        <th width="150px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Responsable</th>
        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Área</th>

        <th width="95px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">N° Cotización</th>
        <th width="60px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Tipo</th>
        <th width="110px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">N° O.C / O.S</th>
        <th width="180px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Descripción</th>
        <th width="85px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Inicio</th>
        <th width="85px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Entrega</th>
        <th width="130px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Estado del Trabajo</th>

        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Acta Aceptacion</th>
        <th width="170px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Estado Acta Aceptacion</th>
        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Docier Calidad</th>
        <th width="85px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">H.E.S</th>

        <th width="80px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Adelanto</th>
        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Monto Adelanto</th>
        <th width="100px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Moneda</th>
        <th width="150px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Subtotal</th>
        <th width="85px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">I.G.V</th>
        <th width="85px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Precio Total</th>
        <th width="100px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">S.P.O.T.</th>
        <th width="110px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Monto S.P.O.T.</th>
        <th width="130px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Dscto. Factoring</th>
        <th width="130px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Monto A Abonar</th>

        <th width="150px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Condición de Pago</th>
        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Facturado</th>
        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">N° Factura</th>
        <th width="90px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Año Fact.</th>
        <th width="90px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Mes Fact.</th>
        <th width="100px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Fecha Emisión</th>
        <th width="100px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Fecha Ingreso</th>
        <th width="60px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Días</th>
        <th width="140px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Fecha Programado</th>

        <th width="110px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Banco</th>
        <th width="150px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Estado Factura</th>
        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Año Abono</th>
        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Mes Abono</th>
        <th width="120px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Fecha Pago</th>

        <th width="150px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Observación</th>
        <th width="100px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Revisión / VB</th>
    </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($financeWorks); $i++ )
        <tr>
            <th width="50px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['year'] }}</th>

            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['customer'] }}</th>
            <th width="150px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['responsible'] }}</th>
            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['area'] }}</th>

            <th width="95px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['quote'] }}</th>
            <th width="60px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['type'] }}</th>
            <th width="110px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['order_customer'] }}</th>
            <th width="180px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['description'] }}</th>
            <th width="85px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['initiation'] }}</th>
            <th width="85px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['delivery'] }}</th>
            <th width="130px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['state_work'] }}</th>

            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['act_of_acceptance'] }}</th>
            <th width="170px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['state_act_of_acceptance'] }}</th>
            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['docier'] }}</th>
            <th width="85px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['hes'] }}</th>

            <th width="80px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['advancement'] }}</th>
            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['amount_advancement'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['currency'] }}</th>
            <th width="150px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['subtotal'] }}</th>
            <th width="85px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['igv'] }}</th>
            <th width="85px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['total'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['detraction'] }}</th>
            <th width="110px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['amount_detraction'] }}</th>
            <th width="130px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['discount_factoring'] }}</th>
            <th width="130px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['amount_include_detraction'] }}</th>

            <th width="150px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['pay_condition'] }}</th>
            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['invoiced'] }}</th>
            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['number_invoice'] }}</th>
            <th width="90px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['year_invoice'] }}</th>
            <th width="90px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['month_invoice'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['date_issue'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['date_admission'] }}</th>
            <th width="60px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['days'] }}</th>
            <th width="140px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['date_programmed'] }}</th>

            <th width="110px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['bank'] }}</th>
            <th width="150px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['state'] }}</th>
            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['year_paid'] }}</th>
            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['month_paid'] }}</th>
            <th width="120px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['date_paid'] }}</th>

            <th width="150px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['observation'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $financeWorks[$i]['revision'] }}</th>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>