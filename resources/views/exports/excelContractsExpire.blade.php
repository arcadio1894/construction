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
<h1>CONTRATOS POR CADUCAR</h1>
<table id="table">
    <thead>
        <tr>
            <th width="250px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Colaborador</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Contrato</th>
            <th width="120px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Fecha Inicio</th>
            <th width="120px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Fecha Fin</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px; word-wrap: break-word">Dias para vencer</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($contracts); $i++ )
        <tr>
            <td width="250px" style="word-wrap: break-word">{{ $contracts[$i]['worker_name'] }}</td>
            <td width="100px">{{ $contracts[$i]['code'] }}</td>
            <td width="120px">{{ $contracts[$i]['date_start'] }}</td>
            <td width="120px">{{ $contracts[$i]['date_fin'] }}</td>
            <td width="150px">{{ $contracts[$i]['days_remaining']." días" }}</td>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>