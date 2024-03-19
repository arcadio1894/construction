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
    <tr >
        <th width="220px" style="background-color: #1c3c80; font-size: 13px; color: white">TRABAJADOR</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">ASISTIÓ</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">FALTAS</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">TARDANZAS</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">D. MEDICO</th>
        <th width="120px" style="background-color: #1c3c80; font-size: 13px; color: white">F. JUSTIFICADA</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">VACACIONES</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">PERMISOS</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">SUSPENSION</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">FERIADOS</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">LICENCIA</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">L. SIN GOZO</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">PERMISOS POR HORAS</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">TERMINO CONTRATO</th>
    </tr>
    </thead>
    <tbody>
    @for( $t=0 ; $t<count($arraySummary) ; $t++ )
        <tr >
            <td width="220px">
                {{ $arraySummary[$t]['worker'] }}
            </td>
            <td width="100px">{{ $arraySummary[$t]['cantA'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantF'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantT'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantM'] }}</td>
            <td width="120px">{{ $arraySummary[$t]['cantJ'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantV'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantP'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantS'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantH'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantL'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantU'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantPH'] }}</td>
            <td width="100px">{{ $arraySummary[$t]['cantTC'] }}</td>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>