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
<h1>REPORTE DE MATERIALES POR DESAHABASTECERSE</h1>
<table id="table">
    <thead>
        <tr>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Código</th>
            <th width="180px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Material</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Stock Actual</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Stock Minimo</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Stock Maximo</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Estado</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($materials); $i++ )

        <tr>
            <th width="90px">{{ $materials[$i]['code'] }}</th>
            <th width="180px" style="word-wrap: break-word">{{ $materials[$i]['material'] }}</th>
            <th width="90px">{{ $materials[$i]['stock'] }}<</th>
            <th width="90px">{{ $materials[$i]['stock_min'] }}<</th>
            <th width="90px">{{ $materials[$i]['stock_max'] }}<</th>
            <th width="90px">{{ $quotes[$i]['state'] }}</th>
        </tr>

    @endfor
    </tbody>
</table>
</body>
</html>