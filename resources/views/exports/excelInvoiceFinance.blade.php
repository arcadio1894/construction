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
<h1>REPORTE DE FACTURAS {{ $dates }}</h1>
<table id="table">
    <thead>
        <tr>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Fecha de factura</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">OC / OS</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Factura</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Tipo de orden</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Proveedor</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Categoría</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Sub Total</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Impuestos</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Total</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($invoices); $i++ )
        @if ( ($i+1) % 2 == 0)
            <tr>
                <th width="150px">{{ $invoices[$i]['date'] }}</th>
                <th width="150px">{{ $invoices[$i]['order'] }}</th>
                <th width="150px">{{ $invoices[$i]['invoice'] }}</th>
                <th width="150px">{{ $invoices[$i]['type_order'] }}</th>
                <th width="150px">{{ $invoices[$i]['supplier'] }}</th>
                <th width="150px">{{ $invoices[$i]['category'] }}</th>
                <th width="150px">{{ $invoices[$i]['subtotal'] }}</th>
                <th width="150px">{{ $invoices[$i]['taxes'] }}</th>
                <th width="150px">{{ $invoices[$i]['total'] }}</th>
            </tr>
        @else
            <tr>
                <th width="150px">{{ $invoices[$i]['date'] }}</th>
                <th width="150px">{{ $invoices[$i]['order'] }}</th>
                <th width="150px">{{ $invoices[$i]['invoice'] }}</th>
                <th width="150px">{{ $invoices[$i]['type_order'] }}</th>
                <th width="150px">{{ $invoices[$i]['supplier'] }}</th>
                <th width="150px">{{ $invoices[$i]['category'] }}</th>
                <th width="150px">{{ $invoices[$i]['subtotal'] }}</th>
                <th width="150px">{{ $invoices[$i]['taxes'] }}</th>
                <th width="150px">{{ $invoices[$i]['total'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>