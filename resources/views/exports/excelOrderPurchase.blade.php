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
            <th width="60px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Orden</th>
            <th width="120px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha de Orden</th>
            <th width="120px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha de llegada</th>
            <th width="150px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Empresa</th>
            <th width="150px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Material</th>
            <th width="150px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Categoría</th>
            <th width="80px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Cantidad</th>
            <th width="80px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Moneda</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Precio C/IGV</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Precio S/IGV</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Total C/IGV</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($orders); $i++ )
        @if ( ($i+1) % 2 == 0)
            <tr>
                <th width="70px">{{ $orders[$i]['order'] }}</th>
                <th width="130px">{{ $orders[$i]['date_order'] }}</th>
                <th width="130px">{{ $orders[$i]['date_arrive'] }}</th>
                <th width="180px" style="word-wrap: break-word">{{ $orders[$i]['supplier'] }}</th>
                <th width="300px" style="word-wrap: break-word">{{ $orders[$i]['material'] }}</th>
                <th width="150px">{{ $orders[$i]['category'] }}</th>
                <th width="80px">{{ $orders[$i]['quantity'] }}</th>
                <th width="80px">{{ $orders[$i]['currency'] }}</th>
                <th width="70px">{{ $orders[$i]['price_igv'] }}</th>
                <th width="70px">{{ $orders[$i]['price_sin_igv'] }}</th>
                <th width="70px">{{ $orders[$i]['total_igv'] }}</th>
            </tr>
        @else
            <tr>
                <th width="70px">{{ $orders[$i]['order'] }}</th>
                <th width="120px">{{ $orders[$i]['date_order'] }}</th>
                <th width="120px">{{ $orders[$i]['date_arrive'] }}</th>
                <th width="180px" style="word-wrap: break-word">{{ $orders[$i]['supplier'] }}</th>
                <th width="300px" style="word-wrap: break-word">{{ $orders[$i]['material'] }}</th>
                <th width="150px">{{ $orders[$i]['category'] }}</th>
                <th width="75px">{{ $orders[$i]['quantity'] }}</th>
                <th width="80px">{{ $orders[$i]['currency'] }}</th>
                <th width="70px">{{ $orders[$i]['price_igv'] }}</th>
                <th width="70px">{{ $orders[$i]['price_sin_igv'] }}</th>
                <th width="70px">{{ $orders[$i]['total_igv'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>