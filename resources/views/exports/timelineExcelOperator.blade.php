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

        }

        #sumary tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        #table th,
        #table td {
            padding-left: 3px;
            text-align: left;
        }

        #table th {

            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: bold;
            background-color: #7A8DC5;
            font-size: 1em;
        }

        #table .desc {
            text-align: left;
        }

        #table td {
            padding-left: 3px;
            text-align: left;
        }

        #table th {
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
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<h1>{{ $title }}</h1>
<table id="table">
    <thead>
    <tr>
        <th width="20px" style="background-color: #1c3c80; font-size: 13px; color: white">#</th>
        <th width="160px" style="background-color: #1c3c80; font-size: 13px; color: white">COTIZACIÓN</th>
        <th width="110px" style="background-color: #1c3c80; font-size: 13px; color: white">ETAPA</th>
        <th width="230px" style="word-wrap: break-word; background-color: #1c3c80; font-size: 13px; color: white">DESCRIPCIÓN DE TAREA</th>
        <th width="90px" style="background-color: #1c3c80; font-size: 13px; color: white">RESPONSABLE</th>
        <th width="80px" style="background-color: #1c3c80; font-size: 13px; color: white">EJECUT.</th>
        <th width="60px" style="background-color: #1c3c80; font-size: 13px;word-wrap: break-word; color: white">H. PLAN</th>
        <th width="60px" style="background-color: #1c3c80; font-size: 13px;word-wrap: break-word; color: white">C. PLAN</th>
    </tr>
    </thead>
    <tbody>
    @for( $i = 0; $i < count( $tasks ); $i++ )
        @if ( $i == 0 )
            <tr>
                <td width="20px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['id'] }}</td>
                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['quote'] }}</td>
                <td width="110px" style="word-wrap: break-word;border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
            </tr>
        @else
            @if ( $tasks[$i]['quote'] == $tasks[$i-1]['quote'] )
                @if ( $tasks[$i]['phase'] == $tasks[$i-1]['phase'] )
                    @if ( $tasks[$i]['task'] == $tasks[$i-1]['task'] )
                        @if ( $i == count($tasks)-1 )
                            <tr>
                                <td width="20px" style="border-bottom:1px solid #1c3c80;border-left:1px solid #1c3c80; border-right:1px solid #1c3c80;" >{{ $tasks[$i]['id'] }}</td>
                                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="110px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
                            </tr>
                        @else
                            <tr>
                                <td width="20px" style="border-left:1px solid #1c3c80; border-right:1px solid #1c3c80;">{{ $tasks[$i]['id'] }}</td>
                                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="110px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
                            </tr>
                        @endif

                    @else
                        @if ( $i == count($tasks)-1 )
                            <tr>
                                <td width="20px" style="border-bottom:1px solid #1c3c80;border-left:1px solid #1c3c80; border-right:1px solid #1c3c80;" >{{ $tasks[$i]['id'] }}</td>
                                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="110px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                                <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                                <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
                            </tr>
                        @else
                            <tr>
                                <td width="20px" style="border-left:1px solid #1c3c80; border-right:1px solid #1c3c80;">{{ $tasks[$i]['id'] }}</td>
                                <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="110px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                                <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                                <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                                <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                                <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
                            </tr>
                        @endif

                    @endif

                @else
                    @if ( $i == count($tasks)-1 )
                        <tr>
                            <td width="20px" style="border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['id'] }}</td>
                            <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80"></td>
                            <td width="110px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                            <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                            <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                            <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                            <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                            <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
                        </tr>
                    @else
                        <tr>
                            <td width="20px" style="border-left:1px solid #1c3c80; border-right:1px solid #1c3c80">{{ $tasks[$i]['id'] }}</td>
                            <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80"></td>
                            <td width="110px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                            <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                            <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                            <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                            <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                            <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-bottom:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
                        </tr>
                    @endif

                @endif

            @else
                @if ( $i == count($tasks)-1 )
                    <tr>
                        <td width="20px" style="border-top:1px solid #1c3c80;border-left:1px solid #1c3c80; border-right:1px solid #1c3c80;">{{ $tasks[$i]['id'] }}</td>
                        <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['quote'] }}</td>
                        <td width="110px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                        <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                        <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                        <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                        <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                        <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
                    </tr>
                @else
                    <tr>
                        <td width="20px" style="border-top:1px solid #1c3c80;border-left:1px solid #1c3c80; border-right:1px solid #1c3c80;">{{ $tasks[$i]['id'] }}</td>
                        <td width="160px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['quote'] }}</td>
                        <td width="110px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['phase'] }}</td>
                        <td width="230px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['task'] }}</td>
                        <td width="90px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80">{{ $tasks[$i]['performer'] }}</td>
                        <td width="80px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['worker'] }}</td>
                        <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['hours_plan'] }}</td>
                        <td width="60px" style="word-wrap: break-word; border-left:1px solid #1c3c80; border-right:1px solid #1c3c80; border-top:1px solid #1c3c80; border-bottom:1px solid #1c3c80">{{ $tasks[$i]['quantity_plan'] }}</td>
                    </tr>
                @endif

            @endif


        @endif

    @endfor
    </tbody>
</table>
<div class="page-break"></div>
</body>
</html>