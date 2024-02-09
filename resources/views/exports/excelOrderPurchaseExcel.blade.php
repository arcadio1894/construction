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
            background-color: #1c3c80;
            font-size: 1.2em;
            text-align: center;
            vertical-align: center;
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
<h1>REPORTE DE ORDENES DE COMPRAS {{ $dates }}</h1>
<table id="table">
    <thead>
        <tr>
            <th width="50px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">ID</th>
            <th width="80px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Orden</th>
            <th width="120px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha de Orden</th>
            <th width="120px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha de llegada</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Proveedor</th>
            <th width="150px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Aprobado Por</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Moneda</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Total</th>
            <th width="100px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Tipo</th>
            <th width="85px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Estado</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Observación</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($orders); $i++ )
        @if ( ($i+1) % 2 == 0)
            <tr>
                <th width="50px">{{ $orders[$i]['id'] }}</th>
                <th width="80px">{{ $orders[$i]['code'] }}</th>
                <th width="120px">{{ $orders[$i]['date_order'] }}</th>
                <th width="120px">{{ $orders[$i]['date_arrival'] }}</th>
                <th width="200px" style="word-wrap: break-word">{{ $orders[$i]['supplier'] }}</th>
                <th width="150px">{{ $orders[$i]['approved_user'] }}</th>
                <th width="75px">{{ $orders[$i]['currency'] }}</th>
                <th width="75px">{{ $orders[$i]['total'] }}</th>
                <th width="100px">{{ $orders[$i]['typeText'] }}</th>
                <th width="85px">{{ $orders[$i]['stateText'] }}</th>
                <th width="200px">{{ $orders[$i]['observation'] }}</th>
            </tr>
        @else
            <tr>
                <th width="50px">{{ $orders[$i]['id'] }}</th>
                <th width="80px">{{ $orders[$i]['code'] }}</th>
                <th width="120px">{{ $orders[$i]['date_order'] }}</th>
                <th width="120px">{{ $orders[$i]['date_arrival'] }}</th>
                <th width="200px" style="word-wrap: break-word">{{ $orders[$i]['supplier'] }}</th>
                <th width="150px">{{ $orders[$i]['approved_user'] }}</th>
                <th width="75px">{{ $orders[$i]['currency'] }}</th>
                <th width="75px">{{ $orders[$i]['total'] }}</th>
                <th width="100px">{{ $orders[$i]['typeText'] }}</th>
                <th width="85px">{{ $orders[$i]['stateText'] }}</th>
                <th width="200px">{{ $orders[$i]['observation'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>