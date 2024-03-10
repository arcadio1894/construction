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
<h1>{{ $dates }}</h1>
<table id="table">
    <thead>
        <tr>
            <th width="80px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Orden</th>
            <th width="70px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Año</th>
            <th width="120px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Observación</th>
            <th width="120px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha de Orden</th>
            <th width="120px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha de llegada</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Proveedor</th>
            <th width="150px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Aprobado Por</th>
            <th width="85px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Estado</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Moneda</th>
            <th width="80px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Código</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Material</th>
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Cantidad</th>
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Precio</th>
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Total</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($orders); $i++ )
        <tr>
            <th width="80px">{{ $orders[$i]['code'] }}</th>
            <th width="70px">{{ $orders[$i]['year'] }}</th>
            <th width="120px" style="word-wrap: break-word">{{ $orders[$i]['observation'] }}</th>
            <th width="120px">{{ $orders[$i]['date_order'] }}</th>
            <th width="120px">{{ $orders[$i]['date_arrival'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $orders[$i]['supplier'] }}</th>
            <th width="150px" style="word-wrap: break-word">{{ $orders[$i]['approved_user'] }}</th>
            <th width="85px">{{ $orders[$i]['state'] }}</th>
            <th width="75px">{{ $orders[$i]['currency'] }}</th>
            <th width="80px">{{ $orders[$i]['material_code'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $orders[$i]['material'] }}</th>
            <th width="90px">{{ $orders[$i]['quantity'] }}</th>
            <th width="90px">{{ $orders[$i]['price'] }}</th>
            <th width="90px">{{ $orders[$i]['total'] }}</th>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>