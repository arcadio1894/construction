<!DOCTYPE html>
<html lang="en">
<head>
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
            margin-bottom: 30px;
        }

        #logo {
            text-align: left;
            margin-bottom: 5px;
        }

        #logo img {
            width: 250px;
            height: 150px;
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
             width: 90px;
             margin-right: 10px;
             display: inline-flex;
             font-size: 1em;
        }

        #project2 span {
            color: #5D6975;
            text-align: left;
            width: 140px;
            margin-right: 10px;
            display: inline-flex;
            font-size: 1em;
        }

        #project3 span {
            color: #5D6975;
            text-align: left;
            width: 160px;
            margin-right: 10px;
            display: inline-flex;
            font-size: 1em;
        }

        #company2 {
            float: right;

        }

        #company3 {
            float: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        #table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        #sumary {
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
            white-space: pre-line;
            font-weight: bold;
            background-color: #1c3c80;
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

        #cuadro td {
            border: 1px solid black;
            padding: 2px;
        }

        #sumary td {
            text-align: right !important;
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
    </style>
    <meta charset="utf-8">
    <title>Guia de remision</title>
</head>
<body>
<header class="clearfix">
    <div id="logo">
        <img src="{{ asset('/landing/img/logo_pdf.png') }}">
        <div id="company3" class="clearfix">
            <div>RUC 20540001384</div>
            <div>Predio el Horcón - Sector el Horcón U.C 02972- F-Moche</div>
            {{--<div>La Esperanza, Trujillo, Perú</div>--}}
            <div>Sitio Web: www.sermeind.com.pe</div>
            <div>Teléfono: +51 959 332 205</div>
        </div>
    </div>

    <h1>GUIA DE REMISIÓN: {{ $arrayGuide[0]['code'] }}</h1>

    <div id="company2" class="clearfix">
        <table id="cuadro" width="150">
            <tr>
                <td>CODIGO #:</td>
                <td style="max-width:20px;overflow-wrap: break-word;">{{ $arrayGuide[0]['code'] }}</td>
            </tr>
            <tr>
                <td>FECHA:</td>
                <td style="max-width:20px;overflow-wrap: break-word;">{{ $arrayGuide[0]['date_transfer'] }}</td>
            </tr>
            <tr>
                <td>RESPONSABLE:</td>
                <td style="max-width:20px;overflow-wrap: break-word;">{{ $arrayGuide[0]['responsible'] }}</td>
            </tr>
        </table>
    </div>

    <div id="project">
        <div><span>RAZON SOCIAL</span>: SERMEIND FABRICACIONES INDUSTRIALES S.A.C</div>
        <div><span>RUC</span>: 20540001384</div>
        <div><span>DOMICILIO</span>: Predio el Horcón - Sector el Horcón U.C 02972- F-Moche<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - TRUJILLO</div>
        <div><span>TELÉFONO</span>: (+51) 959 332 205</div>
        {{--<div><span>CORREO</span>: KPAREDES@SERMEIND.COM</div>--}}

    </div>
    <br><br><br><br><br><br><br><br>
    <div id="project2">
        <div><strong><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div><strong><span>MOTIVO DE TRASLADO</span></strong>: {{ $arrayGuide[0]['reason'] }}</div>
        <div><strong><span>DESTINATARIO</span></strong>: {{ $arrayGuide[0]['destinatario'] }}</div>
        <div><strong><span>PUNTO DE LLEGADA</span></strong>: {{ $arrayGuide[0]['punto_llegada'] }} </div>
        <div><strong><span>DOCUMENTO</span></strong>: {{ $arrayGuide[0]['documento'] }} </div>

    </div>

</header>

<br><br>

<main>

    <table id="table">
        <thead>
        <tr>
            <th class="desc" style="width: 40px">CÓDIGO</th>
            <th class="desc" style="width: 200px">DESCRIPCIÓN</th>
            <th style="width: 40px">UNIDAD </th>
            <th style="width: 40px">CANTIDAD</th>

        </tr>
        </thead>
        <tbody>
        @foreach ($arrayGuide[0]['details'] as $detail)
        <tr>
            <td class="desc" style="text-align: center">{{ $detail['code'] }}</td>
            <td class="desc">{{ $detail['description'] }}</td>
            <td class="qty" style="text-align: center">{{ $detail['unit'] }}</td>
            <td class="qty" style="text-align: center">{{ $detail['quantity'] }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <br><br><br><br>
    <div id="project3">
        <div><strong><span>DATOS DEL VEHICULO</span></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div><span>PLACA</span>: {{ $arrayGuide[0]['vehiculo'] }}</div>
        <div><strong><span>DATOS DEL CONDUCTOR</span></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div><span>CONDUCTOR</span>: {{ $arrayGuide[0]['driver'] }} </div>
        <div><span>LICENCIA DE CONDUCIR</span>: {{ $arrayGuide[0]['driver_licence'] }} </div>
    </div>
</main>
{{--<div class="page-break"></div>
<header class="clearfix">
    <div id="logo">
        <img src="{{ asset('/landing/img/logo_pdf.png') }}">
        <div id="company3" class="clearfix">
            <div>RUC 20540001384</div>
            <div>A.H. Ramiro Prialé Mz. 17 Lte. 1</div>
            <div>La Esperanza, Trujillo, Perú</div>
            <div>Sitio Web: www.sermeind.com.pe</div>
            <div>Teléfono: +51 998-396-337</div>
        </div>
    </div>

    <h1>COTIZACIÓN: {{ $quote->code }}</h1>

    <div id="company2" class="clearfix">
        <div>CLIENTE</div>
        <div>{{ ($quote->customer !== null) ? $quote->customer->business_name : 'No tiene cliente' }}</div>
        <div>{{ ($quote->customer !== null) ? $quote->customer->address : 'No tiene dirección' }}</div>
        <div>{{ ($quote->customer !== null) ? $quote->customer->location : 'No tiene localización' }}</div>
    </div>

    <div id="project">
        <div><span>COTIZACIÓN #</span>: {{ $quote->id }}</div>
        <div><span>FECHA</span>: {{ date( "d/m/Y", strtotime( $quote->date_quote )) }}</div>
        <div><span>CLIENTE ID</span>: {{ ($quote->customer !== null) ? $quote->customer_id : 'No tiene localización'}}</div>
        <div><span>VALIDO HASTA</span>: {{ date( "d/m/Y", strtotime( $quote->date_validate )) }} </div>

    </div>

</header>
<div id="notices">
    <div>CARACTERISTICAS DE {{ $quote->code }}:</div>
    <br>
    @foreach( $quote->equipments as $equipment )
        <div class="notice"><strong>{{ $equipment->description }}</strong> </div>
        <div class="notice">{!! nl2br($equipment->detail) !!}</div><br>
    @endforeach
</div>--}}
<footer>
    Predio el Horcón - Sector el Horcón U.C 02972- F-Moche  |  +51 959 332 205
</footer>
</body>
</html>