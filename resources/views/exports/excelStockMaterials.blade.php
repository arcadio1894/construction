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
<h1>REPORTE DE MATERIALES POR DESHABASTECERSE</h1>
<table id="table">
    <thead>
        <tr>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Código</th>
            <th width="180px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Material</th>
            <th width="180px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Categoría</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Stock Actual</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Stock Minimo</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Stock Maximo</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Estado</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($materials); $i++ )
        <tr>
            <td width="100px">{{ $materials[$i]['code'] }}</td>
            <td width="180px" style="word-wrap: break-word">{{ $materials[$i]['material'] }}</td>
            <td width="100px">{{ $materials[$i]['category'] }}</td>
            <td width="100px">{{ $materials[$i]['stock'] }}</td>
            <td width="100px">{{ $materials[$i]['stock_min'] }}</td>
            <td width="100px">{{ $materials[$i]['stock_max'] }}</td>
            <td width="100px">{{ $materials[$i]['state'] }}</td>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>