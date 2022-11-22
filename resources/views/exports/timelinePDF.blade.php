<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cronograma {{ $timeline->date->format('d/m/Y') }}</title>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        header {
            padding: 10px 0;
            margin-bottom: 10px;
        }

        #logo {
            text-align: left;
            margin-bottom: 5px;
        }

        #logo img {
            width: 100px;
            height: 70px;
        }

        h1 {
            border-top: 1px solid  #5D6975;
            border-bottom: 1px solid  #5D6975;
            color: #ffffff;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: #1c3c80;
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: left;
            width: 80px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company2 {
            float: right;
            width: 300px;
        }

        #company3 {
            float: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
            white-space: normal;
        }

        table th {
            padding: 5px 5px;
            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: normal;
            font-weight: bold;
            background-color: #1c3c80;
            font-size: 1em;
        }

        table .desc {
            text-align: left;
        }

        table .total {
            text-align: right;
        }

        table td {
            padding: 5px;
            text-align: center;
        }

        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1em;
        }

        #sumary td {
            text-align: right;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #ffffff;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
            background-color: #1c3c80;
        }
        .center {
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }

        .plano {
            width: auto;
            height: 250px;
            max-width: 2000px;
        }

        .fill {
            object-fit: fill;
        }

        .contain {
            object-fit: contain;
        }

        .cover {
            object-fit: cover;
        }

        .scale-down {
            object-fit: scale-down;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="{{ asset('/landing/img/logo_pdf.png') }}">
    </div>

    <h1>PROGRAMACION DE ACTIVIDADES: {{ $timeline->date->format('d-m-Y') }}</h1>
</header>

<main>

    <table border="1px">
        <thead>
        <tr>
            <th>#</th>
            <th width="90px" style="word-wrap: break-word">COTIZACIÓN</th>
            <th>ETAPA</th>
            <th width="90px" style="word-wrap: break-word">DESCRIPCIÓN DE TAREA</th>
            <th>RESPONSABLE</th>
            <th>AVANCE</th>
            <th>EJECUT.</th>
            <th>TIEMPO PLAN</th>
            <th>TIEMPO REAL</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $timeline->activities as $key=>$activity )
        <tr>
            <td width="10px" rowspan="{{ count($activity->activity_workers) }}">{{ $key+1 }}</td>
            <td width="90px" style="word-wrap: break-word" rowspan="{{ count($activity->activity_workers) }}">{{ $activity->description_quote }} </td>
            <td rowspan="{{ count($activity->activity_workers) }}">{{ $activity->phase }}</td>
            <td width="90px" style="word-wrap: break-word" rowspan="{{ count($activity->activity_workers) }}">{{ $activity->activity }} </td>
            <td rowspan="{{ count($activity->activity_workers) }}">{{ ($activity->performer == null) ? 'No tiene responsable':$activity->performer_worker->first_name.' '.$activity->performer_worker->last_name }} </td>
            <td rowspan="{{ count($activity->activity_workers) }}">{{ ($activity->progress == null ) ? 0: $activity->progress }} </td>
            @if ( count($activity->activity_workers) > 0 )
                <td >{{ $activity->activity_workers[0]->worker->first_name }} </td>
                <td >{{ ($activity->activity_workers[0]->hours_plan == 0 || $activity->activity_workers[0]->hours_plan == null) ? '':$activity->activity_workers[0]->hours_plan }} </td>
                <td >{{ ($activity->activity_workers[0]->hours_real == 0 || $activity->activity_workers[0]->hours_real == null) ? '':$activity->activity_workers[0]->hours_real }} </td>
            @else
                <td > </td>
                <td > </td>
                <td > </td>
            @endif
        </tr>
            @for ( $i = 1; $i<count($activity->activity_workers); $i++ )
            <tr>
                <td >{{ $activity->activity_workers[$i]->worker->first_name }}</td>
                <td >{{ ($activity->activity_workers[$i]->hours_plan == 0 || $activity->activity_workers[$i]->hours_plan == null) ? '':$activity->activity_workers[$i]->hours_plan }} </td>
                <td >{{ ($activity->activity_workers[$i]->hours_real == 0 || $activity->activity_workers[$i]->hours_real == null) ? '':$activity->activity_workers[$i]->hours_real }} </td>
            </tr>
            @endfor
        @endforeach
        </tbody>
    </table>
    <br><br><br><br>
</main>

<footer>
    A.H. Ramiro Prialé Mz. 17 Lte. 1  |  +51 998-396-337
</footer>
</body>
</html>