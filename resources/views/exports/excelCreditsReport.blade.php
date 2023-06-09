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

        #table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        #table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        #sumary tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        #table th,
        #table td {
            text-align: center;
        }

        #table th {
            padding: 5px 10px;
            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: bold;
            background-color: #7A8DC5;
            font-size: 1.2em;
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
    </style>

</head>
<body>
<h1>{{ $dates }}</h1>
<table id="table">
    <thead>
        <tr>
            <th width="70px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Orden</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Correlativo</th>
            <th width="180px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Proveedor</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Moneda</th>
            <th width="130px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Condición</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Monto Dólares</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Monto Soles</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Deuda Actual Dolares</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Deuda Actual Soles</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Pago</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Deuda Actual</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Factura</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Fecha emisión</th>
            <th width="120px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Fecha vencimiento</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Vence en</th>
            <th width="200px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Estado pago</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($credits); $i++ )
        <tr>
            <th width="70px">{{ $credits[$i]['order'] }}</th>
            <th width="100px">{{ $credits[$i]['correlativo'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $credits[$i]['proveedor'] }}</th>
            <th width="90px">{{ $credits[$i]['moneda'] }}</th>
            <th width="130px">{{ $credits[$i]['condicion'] }}</th>
            <th width="90px">{{ $credits[$i]['montoDolares'] }}</th>
            <th width="90px">{{ $credits[$i]['montoSoles'] }}</th>
            <th width="90px" >{{ $credits[$i]['deudaActualDolares'] }}</th>
            <th width="90px" >{{ $credits[$i]['deudaActualSoles'] }}</th>
            <th width="90px" >{{ $credits[$i]['adelanto'] }}</th>
            <th width="90px" >{{ $credits[$i]['deudaActual'] }}</th>
            <th width="100px" >{{ $credits[$i]['factura'] }}</th>
            <th width="100px" >{{ $credits[$i]['fechaEmision'] }}</th>
            <th width="120px" >{{ $credits[$i]['fechaVencimiento'] }}</th>
            <th width="100px" >{{ $credits[$i]['estado'] }}</th>
            <th width="200px" >{{ $credits[$i]['estadoPago'] }}</th>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>