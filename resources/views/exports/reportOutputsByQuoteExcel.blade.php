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
            <th width="100px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">N°</th>
            <th width="40px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Año</th>
            <th width="100px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha de solicitud</th>
            <th width="160px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Usuario solicitante</th>
            <th width="160px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Usuario responsable</th>
            <th width="150px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Tipo / Estado</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Equipo</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Código</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Material</th>
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Cantidad</th>
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Moneda</th>
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Precio</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($outputs); $i++ )
        <tr>
            <th width="100px">{{ $outputs[$i]['code'] }}</th>
            <th width="40px">{{ $outputs[$i]['year'] }}</th>
            <th width="100px">{{ $outputs[$i]['request_date'] }}</th>
            <th width="160px" style="word-wrap: break-word">{{ $outputs[$i]['requesting_user'] }}</th>
            <th width="160px" style="word-wrap: break-word">{{ $outputs[$i]['responsible_user'] }}</th>
            <th width="150px" style="word-wrap: break-word">{{ $outputs[$i]['stateText'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $outputs[$i]['equipment'] }}</th>
            <th width="75px">{{ $outputs[$i]['material_code'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $outputs[$i]['material'] }}</th>
            @if($outputs[$i]['quantity'] == "TOTAL")
            <th width="90px">{{ $outputs[$i]['quantity'] }}</th>
            @else
            <th width="90px">{{ round((float)($outputs[$i]['quantity']), 2) }}</th>
            @endif
            <th width="90px">{{ $outputs[$i]['currency'] }}</th>
            <th width="90px">{{ round((float)($outputs[$i]['price']), 2) }}</th>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>