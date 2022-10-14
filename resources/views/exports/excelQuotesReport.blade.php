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
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Código</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Fecha</th>
            <th width="180px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Descripción</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word" colspan="2">Monto Materiales</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word" colspan="2">Monto Consumibles</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Monto Serv. Varios</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Monto Serv. Adic.</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Monto Dias de Trabajo</th>
            <th width="80px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Moneda</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Pagó Cliente</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($quotes); $i++ )
        @if ( ($i+1) % 2 == 0)
            <tr>
                <th width="90px" rowspan="2">{{ $quotes[$i]['code'] }}</th>
                <th width="90px" rowspan="2">{{ $quotes[$i]['date'] }}</th>
                <th width="180px" rowspan="2" style="word-wrap: break-word">{{ $quotes[$i]['description'] }}</th>
                <th width="60px">Cotizado</th>
                <th width="90px">{{ $quotes[$i]['materials_quote'] }}</th>
                <th width="60px">Cotizado</th>
                <th width="90px">{{ $quotes[$i]['consumables_quote'] }}</th>
                <th width="100px" rowspan="2">{{ $quotes[$i]['monto_servicios_varios'] }}</th>
                <th width="100px" rowspan="2">{{ $quotes[$i]['monto_servicios_adicionales'] }}</th>
                <th width="100px" rowspan="2">{{ $quotes[$i]['monto_dias_trabajo'] }}</th>
                <th width="80px" rowspan="2">{{ $quotes[$i]['currency_invoice'] }}</th>
                <th width="100px" rowspan="2">{{ $quotes[$i]['total'] }}</th>
            </tr>
            <tr>
                <th width="60px">Real</th>
                <th width="90px">{{ $quotes[$i]['materials_real'] }}</th>
                <th width="60px">Real</th>
                <th width="90px">{{ $quotes[$i]['consumables_real'] }}</th>
            </tr>
        @else
            <tr>
                <th width="90px" rowspan="2">{{ $quotes[$i]['code'] }}</th>
                <th width="90px" rowspan="2">{{ $quotes[$i]['date'] }}</th>
                <th width="180px" rowspan="2" style="word-wrap: break-word">{{ $quotes[$i]['description'] }}</th>
                <th width="60px">Cotizado</th>
                <th width="90px">{{ $quotes[$i]['materials_quote'] }}</th>
                <th width="60px">Cotizado</th>
                <th width="90px">{{ $quotes[$i]['consumables_quote'] }}</th>
                <th width="100px" rowspan="2">{{ $quotes[$i]['monto_servicios_varios'] }}</th>
                <th width="100px" rowspan="2">{{ $quotes[$i]['monto_servicios_adicionales'] }}</th>
                <th width="100px" rowspan="2">{{ $quotes[$i]['monto_dias_trabajo'] }}</th>
                <th width="80px" rowspan="2">{{ $quotes[$i]['currency_invoice'] }}</th>
                <th width="100px" rowspan="2">{{ $quotes[$i]['total'] }}</th>
            </tr>
            <tr>
                <th width="60px">Real</th>
                <th width="90px">{{ $quotes[$i]['materials_real'] }}</th>
                <th width="60px">Real</th>
                <th width="90px">{{ $quotes[$i]['consumables_real'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>