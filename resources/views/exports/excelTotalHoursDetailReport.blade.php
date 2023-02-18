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
<h1>{{ $title }}</h1>
<table id="table">
    <thead>
    <tr>
        <th width="40px" style="background-color:#001028; color: #ffffff;">SEM.</th>
        <th width="90px" style="background-color:#001028; color: #ffffff;">FECHA</th>
        <th width="55px" style="background-color:#001028; color: #ffffff;">H. ORD.</th>
        <th width="55px" style="background-color:#001028; color: #ffffff;">H. 25%</th>
        <th width="55px" style="background-color:#001028; color: #ffffff;">H. 35%</th>
        <th width="55px" style="background-color:#001028; color: #ffffff;">H. 100%</th>
        <th width="55px" style="background-color:#001028; color: #ffffff;">H. ESP</th>
    </tr>
    </thead>
    <tbody>
    @for( $b=0 ; $b<count($arrayByDates) ; $b++ )
        <tr>
            <td width="40px">{{ $arrayByDates[$b]['week'] }}</td>
            <td width="90px">{{ $arrayByDates[$b]['date'] }}</td>
            @for( $c=0 ; $c<count($arrayByDates[$b]['assistances']) ; $c++ )
                @if( $arrayByDates[$b]['assistances'][$c][0] === '' )
                    <td width="55px" style="background-color:#ae1c17;">{{ $arrayByDates[$b]['assistances'][$c][0] }}</td>
                @else
                    <td width="55px">{{ $arrayByDates[$b]['assistances'][$c][0] }}</td>
                @endif
                @if( $arrayByDates[$b]['assistances'][$c][1] === '' )
                    <td width="55px" style="background-color:#ae1c17;">{{ $arrayByDates[$b]['assistances'][$c][1] }}</td>
                @else
                    <td width="55px">{{ $arrayByDates[$b]['assistances'][$c][1] }}</td>
                @endif
                @if( $arrayByDates[$b]['assistances'][$c][2] === '' )
                    <td width="55px" style="background-color:#ae1c17;">{{ $arrayByDates[$b]['assistances'][$c][2] }}</td>
                @else
                    <td width="55px">{{ $arrayByDates[$b]['assistances'][$c][2] }}</td>
                @endif
                <td width="55px">{{ $arrayByDates[$b]['assistances'][$c][3] }}</td>
                <td width="55px">{{ $arrayByDates[$b]['assistances'][$c][4] }}</td>
            @endfor
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>