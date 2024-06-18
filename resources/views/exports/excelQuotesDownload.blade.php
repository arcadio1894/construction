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
            <th width="50px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Año</th>
            <th width="90px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Código</th>
            <th width="250px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Descripción</th>
            <th width="100px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Fecha Cotización</th>
            <th width="100px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Fecha Válida</th>
            <th width="150px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Forma Pago</th>
            <th width="150px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Tiempo Entrega</th>
            <th width="200px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Cliente</th>
            <th width="120px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Orden Servicio</th>
            <th width="90px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Total Sin IGV</th>
            <th width="90px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Total</th>
            <th width="90px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Moneda</th>
            <th width="90px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Estado</th>
            <th width="100px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Fecha Creación</th>
            <th width="100px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Creador</th>
            <th width="100px" style="background-color: #203764; font-size: 13px; word-wrap: break-word;color: #ffffff;text-align: center">Decimales</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($quotes); $i++ )
        <tr>
            <th width="50px" >{{ $quotes[$i]['year'] }}</th>
            <th width="90px" >{{ $quotes[$i]['code'] }}</th>
            <th width="250px" style="word-wrap: break-word">{{ $quotes[$i]['description'] }}</th>
            <th width="100px" >{{ $quotes[$i]['date_quote'] }}</th>
            <th width="100px">{{ $quotes[$i]['date_validate'] }}</th>
            <th width="150px">{{ $quotes[$i]['deadline'] }}</th>
            <th width="150px">{{ $quotes[$i]['time_delivery'] }}</th>
            <th width="200px" style="word-wrap: break-word">{{ $quotes[$i]['customer'] }}</th>
            <th width="120px" >{{ $quotes[$i]['order'] }}</th>
            <th width="90px" >{{ $quotes[$i]['total_igv'] }}</th>
            <th width="100px" >{{ $quotes[$i]['total'] }}</th>
            <th width="90px" >{{ $quotes[$i]['currency'] }}</th>
            <th width="90px" >{{ $quotes[$i]['stateText'] }}</th>
            <th width="100px" >{{ $quotes[$i]['created_at'] }}</th>
            <th width="100px" >{{ $quotes[$i]['creator'] }}</th>
            <th width="100px" >{{ $quotes[$i]['decimals'] }}</th>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>