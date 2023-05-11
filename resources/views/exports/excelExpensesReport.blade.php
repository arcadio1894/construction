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
            <th width="250px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Trabajador</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Fecha</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Semana</th>
            <th width="180px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Tipo de gasto</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Total</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($expenses); $i++ )
        @if ( ($i+1) % 2 == 0)
            <tr>
                <th width="250px">{{ $expenses[$i]['trabajador'] }}</th>
                <th width="90px">{{ $expenses[$i]['fecha'] }}</th>
                <th width="100px" style="word-wrap: break-word">{{ $expenses[$i]['week'] }}</th>
                <th width="200px" style="word-wrap: break-word">{{ $expenses[$i]['tipo'] }}</th>
                <th width="80px" style="text-align: right">{{ $expenses[$i]['total'] }}</th>
            </tr>
        @else
            <tr>
                <th width="520px">{{ $expenses[$i]['trabajador'] }}</th>
                <th width="90px">{{ $expenses[$i]['fecha'] }}</th>
                <th width="100px" style="word-wrap: break-word">{{ $expenses[$i]['week'] }}</th>
                <th width="200px" style="word-wrap: break-word">{{ $expenses[$i]['tipo'] }}</th>
                <th width="80px" style="text-align: right">{{ $expenses[$i]['total'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>