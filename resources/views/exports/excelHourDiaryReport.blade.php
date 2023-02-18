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
        <th width="250px" style="background-color:#203764; color: #ffffff;">DATOS</th>
        @for($k=0 ; $k<count($arrayDays) ; $k++)
            <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
        @endfor
    </tr>
    <tr>
        <th width="250px" style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
        @for($i=0 ; $i<count($arrayHeaders) ; $i++)
            @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
            @endfor
        @endfor
    </tr>
    </thead>
    <tbody>
    @for($k=0 ; $k<count($arrayAssistances) ; $k++)
        <tr>
            <td width="250px" style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
            @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                    <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                @endfor
            @endfor
        </tr>
    @endfor
    </tbody>
</table>

</body>
</html>