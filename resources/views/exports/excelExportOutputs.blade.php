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
            <th width="100px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Solicitud</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Orden de Ejecución</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Descripción</th>
            <th width="90px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Fecha</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Usuario Solicitante</th>
            <th width="200px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Usuario Responsable</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Codigo</th>
            <th width="250px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Material</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Cantidad</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Precio Soles</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Precio Dolares</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Total Soles</th>
            <th width="75px" style="word-wrap: break-word;background-color: #1c3c80; font-size: 14px; color: white">Total Dolares</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($outputs); $i++ )
        <tr>
            <th width="90px">{{ $outputs[$i]['tipo'] }}</th>
            <th width="100px">{{ $outputs[$i]['solicitud'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $outputs[$i]['execution_order'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $outputs[$i]['description'] }}</th>
            <th width="90px" style="word-wrap: break-word">{{ $outputs[$i]['fecha'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $outputs[$i]['usuario_solicitante'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $outputs[$i]['usuario_responsable'] }}</th>
            <th width="75px">{{ $outputs[$i]['codigo'] }}</th>
            <th width="250px" style="word-wrap: break-word">{{ $outputs[$i]['material'] }}</th>
            <th width="75px">{{ $outputs[$i]['cantidad'] }}</th>
            @if ( $outputs[$i]['moneda'] == 'PEN' )
                <th width="75px">{{ $outputs[$i]['precio'] }}</th>
                <th width="75px"></th>
                <th width="75px">{{ $outputs[$i]['total_precio'] }}</th>
                <th width="75px"></th>
            @else
                <th width="75px"></th>
                <th width="75px">{{ $outputs[$i]['precio'] }}</th>
                <th width="75px"></th>
                <th width="75px">{{ $outputs[$i]['total_precio'] }}</th>
            @endif

        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>