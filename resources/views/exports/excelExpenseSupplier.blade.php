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
        <th colspan="5" style="background-color: #FFC000;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">INFORMACIÓN DE LA ORDEN</th>
        <th colspan="2" style="background-color: #00B050;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">IMPORTE</th>
        <th colspan="7" style="background-color: #FFC000;color: #000000;text-align: center;font-weight: bold;font-size: 12px;">FACTURACIÓN</th>
    </tr>
    <tr>
        <th width="50px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Año</th>
        <th width="80px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Mes</th>
        <th width="100px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Fecha Orden</th>
        <th width="200px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Proveedor</th>
        <th width="95px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">OC / OS</th>

        <th width="90px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Soles</th>
        <th width="90px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Dólares</th>

        <th width="180px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Condición de Pago</th>
        <th width="85px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">N° Factura</th>
        <th width="110px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Fecha Emisión</th>
        <th width="110px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Crédito en Días</th>
        <th width="135px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Fecha Vencimiento</th>
        <th width="110px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Estado Crédito</th>
        <th width="100px" style="background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Estado Pago</th>

    </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($expenseSuppliers); $i++ )
        <tr>
            <th width="50px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['year'] }}</th>
            <th width="80px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['month'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['date_order'] }}</th>
            <th width="200px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['supplier'] }}</th>
            <th width="95px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['order'] }}</th>

            <th width="90px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['soles'] }}</th>
            <th width="90px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['dolares'] }}</th>

            <th width="180px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['deadline'] }}</th>
            <th width="85px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['invoice'] }}</th>
            <th width="110px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['date_invoice'] }}</th>
            <th width="110px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['days'] }}</th>
            <th width="135px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['due_date'] }}</th>
            <th width="110px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['state_credit'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $expenseSuppliers[$i]['state_paid'] }}</th>

        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>