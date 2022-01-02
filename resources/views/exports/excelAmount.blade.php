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
<h1>REPORTE DE MONTOS Y CANTIDADES DE MATERIALES EN ALMACÉN</h1>
<table id="table">
    <thead>
    <tr>
        <th width="700px" style="background-color: #7A8DC5; font-size: 14px">Material</th>
        <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Stock Dólares</th>
        <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Stock Soles</th>
        <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Monto Dólares</th>
        <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Monto Soles</th>
    </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($materials); $i++ )
        @if ( ($i+1) % 2 == 0)
        <tr>
            <td width="700px">{{ $materials[$i]['material'] }}</td>
            <td width="150px">{{ $materials[$i]['stock_dollars'] }}</td>
            <td width="150px">{{ $materials[$i]['stock_soles'] }}</td>
            <td width="150px">{{ $materials[$i]['amount_dollars'] }}</td>
            <td width="150px">{{ $materials[$i]['amount_soles'] }}</td>
        </tr>
        @else
            <tr>
                <td width="700px" style="background-color: #D0E4F7">{{ $materials[$i]['material'] }}</td>
                <td width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['stock_dollars'] }}</td>
                <td width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['stock_soles'] }}</td>
                <td width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['amount_dollars'] }}</td>
                <td width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['amount_soles'] }}</td>
            </tr>
        @endif
    @endfor
    <tr>
        <td width="700px" style="background-color: #FFC000; color: red; font-size: 14px; font-weight: bold; text-align: right">TOTALES</td>
        <td width="150px" style="background-color: #FFC000; color: red; font-size: 14px; font-weight: bold; text-align: right">{{ $total_quantity_dollars }}</td>
        <td width="150px" style="background-color: #FFC000; color: red; font-size: 14px; font-weight: bold; text-align: right">{{ $total_quantity_soles }}</td>
        <td width="150px" style="background-color: #FFC000; color: red; font-size: 14px; font-weight: bold; text-align: right">USD {{ $total_amount_dollars }}</td>
        <td width="150px" style="background-color: #FFC000; color: red; font-size: 14px; font-weight: bold; text-align: right">PEN {{ $total_amount_soles }}</td>
    </tr>
    </tbody>
</table>
</body>
</html>