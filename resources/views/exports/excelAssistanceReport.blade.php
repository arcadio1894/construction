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
        <th width="220px">TRABAJADOR</th>
        @for( $a=0 ; $a<count($arrayAssistances[0]['assistances']) ; $a++ )
            <th style="width:35px;background-color: {{ $arrayAssistances[0]['assistances'][$a]['bg_color'] }}">
                {{$arrayAssistances[0]['assistances'][$a]['number_day']}}
            </th>
        @endfor
    </tr>
    </thead>
    <tbody>
    @for( $b=0 ; $b<count($arrayAssistances) ; $b++ )
        <tr>
            <td width="220px">
                {{ $arrayAssistances[$b]['worker'] }}
            </td>
            @for( $c=0 ; $c<count($arrayAssistances[$b]['assistances']) ; $c++ )
                <td style="width:35px;background-color: {{ $arrayAssistances[$b]['assistances'][$c]['color'] }}">
                    {{ $arrayAssistances[$b]['assistances'][$c]['status'] }}
                </td>
            @endfor
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>