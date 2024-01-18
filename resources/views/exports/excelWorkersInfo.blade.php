<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        #table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        #table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        #table th,
        #table td {
            text-align: center;
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
<h1>REPORTE DE TRABAJADORES</h1>
<table id="table">
    <thead>
    <tr>
        @foreach($workers[0] as $encabezado)
            <th width="180px" style="background-color: #203764; color: #ffffff; text-align: center; font-weight: bold; font-size: 12px;">{{ $encabezado }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @for ($i = 1; $i < count($workers); $i++)
        <tr>
            @foreach($workers[$i] as $dato)
                <td width="180px" style="font-size: 11px; word-wrap: break-word; vertical-align: top;">{{ $dato }}</td>
            @endforeach
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>