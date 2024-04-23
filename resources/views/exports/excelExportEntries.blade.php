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
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Tipo</th>
            <th width="100px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Orden</th>
            <th width="100px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Factura</th>
            <th width="170px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Proveedor</th>
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Observaciones</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Codigo</th>
            <th width="250px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Material</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Cantidad</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Precio</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Total</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($entries); $i++ )
        @if ( ($i+1) % 2 == 0)
            <tr>
                <th width="90px">{{ $entries[$i]['tipo'] }}</th>
                <th width="100px">{{ $entries[$i]['orden'] }}</th>
                <th width="100px">{{ $entries[$i]['factura'] }}</th>
                <th width="170px" style="word-wrap: break-word">{{ $entries[$i]['proveedor'] }}</th>
                <th width="90px" style="word-wrap: break-word">{{ $entries[$i]['fecha'] }}</th>
                <th width="200px" style="word-wrap: break-word">{{ $entries[$i]['observaciones'] }}</th>
                <th width="75px">{{ $entries[$i]['codigo'] }}</th>
                <th width="250px" style="word-wrap: break-word">{{ $entries[$i]['material'] }}</th>
                <th width="75px">{{ $entries[$i]['cantidad'] }}</th>
                <th width="75px">{{ $entries[$i]['precio'] }}</th>
                <th width="75px">{{ $entries[$i]['total_precio'] }}</th>
            </tr>
        @else
            <tr>
                <th width="90px">{{ $entries[$i]['tipo'] }}</th>
                <th width="100px">{{ $entries[$i]['orden'] }}</th>
                <th width="100px">{{ $entries[$i]['factura'] }}</th>
                <th width="170px" style="word-wrap: break-word">{{ $entries[$i]['proveedor'] }}</th>
                <th width="90px" style="word-wrap: break-word">{{ $entries[$i]['fecha'] }}</th>
                <th width="200px" style="word-wrap: break-word">{{ $entries[$i]['observaciones'] }}</th>
                <th width="75px">{{ $entries[$i]['codigo'] }}</th>
                <th width="250px" style="word-wrap: break-word">{{ $entries[$i]['material'] }}</th>
                <th width="75px">{{ $entries[$i]['cantidad'] }}</th>
                <th width="75px">{{ $entries[$i]['precio'] }}</th>
                <th width="75px">{{ $entries[$i]['total_precio'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>