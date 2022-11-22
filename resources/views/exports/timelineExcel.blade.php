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
<table>
    <thead>
    <tr>
        <th width="20px" style="background-color: #1c3c80; font-size: 13px; color: white">#</th>
        <th width="160px" style="background-color: #1c3c80; font-size: 13px; color: white">COTIZACIÓN</th>
        <th width="120px" style="background-color: #1c3c80; font-size: 13px; color: white">ETAPA</th>
        <th width="400px" style="word-wrap: break-word; background-color: #1c3c80; font-size: 13px; color: white">DESCRIPCIÓN DE TAREA</th>
        <th width="150px" style="background-color: #1c3c80; font-size: 13px; color: white">RESPONSABLE</th>
        <th width="80px" style="background-color: #1c3c80; font-size: 13px; color: white">AVANCE</th>
        <th width="100px" style="background-color: #1c3c80; font-size: 13px; color: white">EJECUT.</th>
        <th width="60px" style="background-color: #1c3c80; font-size: 13px;word-wrap: break-word; color: white">H. PLAN</th>
        <th width="60px" style="word-wrap: break-word; background-color: #1c3c80; font-size: 13px; color: white">H. REAL</th>
    </tr>
    </thead>
    <tbody>
    @for( $i = 0; $i < count( $tasks ); $i++ )
        @if ( $i == 0 )
            <tr>
                <td width="20px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['id'] }}</td>
                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['quote'] }}</td>
                <td width="120px" style="word-wrap: break-word;border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['progress'] }}</td>
                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
            </tr>
        @else
            @if ( $tasks[$i]['quote'] == $tasks[$i-1]['quote'] )
                @if ( $tasks[$i]['phase'] == $tasks[$i-1]['phase'] )
                    @if ( $tasks[$i]['task'] == $tasks[$i-1]['task'] )
                        @if ( $i == count($tasks)-1 )
                            <tr>
                                <td width="20px" style="border-bottom:1px solid #1c3c80" >{{ $tasks[$i]['id'] }}</td>
                                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="120px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
                            </tr>
                        @else
                            <tr>
                                <td width="20px" >{{ $tasks[$i]['id'] }}</td>
                                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="120px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
                            </tr>
                        @endif

                    @else
                        @if ( $i == count($tasks)-1 )
                            <tr>
                                <td width="20px" style="border-bottom:1px solid #1c3c80" >{{ $tasks[$i]['id'] }}</td>
                                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="120px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['progress'] }}</td>
                                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
                            </tr>
                        @else
                            <tr>
                                <td width="20px" >{{ $tasks[$i]['id'] }}</td>
                                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="120px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['progress'] }}</td>
                                <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
                            </tr>
                        @endif

                    @endif

                @else
                    @if ( $i == count($tasks)-1 )
                        <tr>
                            <td width="20px" >{{ $tasks[$i]['id'] }}</td>
                            <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                            <td width="120px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                            <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                            <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                            <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['progress'] }}</td>
                            <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                            <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                            <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
                        </tr>
                    @else
                        <tr>
                            <td width="20px" >{{ $tasks[$i]['id'] }}</td>
                            <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                            <td width="120px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                            <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                            <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                            <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['progress'] }}</td>
                            <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                            <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                            <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
                        </tr>
                    @endif

                @endif

            @else
                @if ( $i == count($tasks)-1 )
                    <tr>
                        <td width="20px" style="border-top:1px solid #1c3c80">{{ $tasks[$i]['id'] }}</td>
                        <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['quote'] }}</td>
                        <td width="120px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                        <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                        <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                        <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['progress'] }}</td>
                        <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                        <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                        <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
                    </tr>
                @else
                    <tr>
                        <td width="20px" style="border-top:1px solid #1c3c80">{{ $tasks[$i]['id'] }}</td>
                        <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['quote'] }}</td>
                        <td width="120px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                        <td width="400px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                        <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                        <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['progress'] }}</td>
                        <td width="150px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                        <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                        <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_real'] }}</td>
                    </tr>
                @endif

            @endif


        @endif

    @endfor
    </tbody>
</table>

</body>
</html>