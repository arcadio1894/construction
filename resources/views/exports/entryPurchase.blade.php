<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Orden de compra</title>
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
            width: 200px;
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
            background: #7A8DC5;
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
        }

        table th {
            padding: 5px 10px;
            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: bold;
            background-color: #7A8DC5;
            font-size: 1.2em;
        }

        table .desc {
            text-align: left;
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
            text-align: right;
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
            background-color: #7A8DC5;
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
<header class="clearfix">
    <div id="logo">
        <img src="{{ asset('/landing/img/logo_pdf.png') }}">
        <div id="company3" class="clearfix">
            <div>RUC 20580001384</div>
            <div>A.H. Ramiro Prialé Mz. 17 Lte. 1</div>
            <div>La Esperanza, Trujillo, Perú</div>
            <div>Sitio Web: www.sermeind.com.pe</div>
            <div>Teléfono: +51 998-396-337</div>
        </div>
    </div>

    <h1>ORDEN DE COMPRA: {{ $purchase_order->id }}</h1>

    <div id="company2" class="clearfix">
        <div>EMITIDO A:</div>
        <div>{{ ($purchase_order->supplier !== null) ? $purchase_order->supplier->business_name : 'No tiene proveedor' }}</div>
        <div>{{ ($purchase_order->supplier !== null) ? $purchase_order->supplier->ruc : 'No tiene ruc' }}</div>
        <div>{{ ($purchase_order->supplier !== null) ? $purchase_order->supplier->address : 'No tiene localización' }}</div>
        <div>{{ ($purchase_order->supplier !== null) ? $purchase_order->supplier->phone : 'No tiene telefono' }}</div>
        <div>{{ ($purchase_order->supplier !== null) ? $purchase_order->supplier->email : 'No tiene email' }}</div>
        <div>{{ ($purchase_order->quote_supplier !== null) ? $purchase_order->quote_supplier : 'No tiene cotización' }}</div>
    </div>

    <div id="project">
        <div><span>ORDEN #</span>: {{ $purchase_order->code }}</div>
        <div><span>FECHA</span>: {{ date( "d/m/Y", strtotime( $purchase_order->date_order )) }}</div>
        <div><span>APROBADO POR</span>: {{ ( $purchase_order->approved_user !== null ) ? $purchase_order->approved_user->name:'No tiene aprobador' }}</div>
        <div><span>CONDICIÓN PAGO</span>: {{ ($purchase_order->payment_condition !== null) ? $purchase_order->payment_condition:'No tiene condición' }} </div>
        <div><span>MONEDA</span>: {{ ($purchase_order->currency_order) === 'USD' ? 'DÓLARES':'SOLES' }} </div>

    </div>

</header>

<br><br>

<main>

    <table>
        <thead>
        <tr>
            <th class="desc">DESCRIPCIÓN</th>
            <th>UNIDAD </th>
            <th>PRECIO UNIT. </th>
            <th>CANT.</th>
            <th>TOTAL</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $purchase_order->details as $detail )
        <tr>
            <td class="desc">{{ $detail->material->full_description }}</td>
            <td class="desc">{{ $detail->material->unitMeasure->name }}</td>
            <td class="unit"> {{ $purchase_order->currency_order }} {{ number_format($detail->price, 2) }}</td>
            <td class="qty">{{ $detail->quantity }}</td>
            <td class="qty">{{ $purchase_order->currency_order }} {{ number_format($detail->price*$detail->quantity, 2) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <br><br><br><br>
    <table id="sumary">
        <tbody>
        <tr>
            <td class="desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="unit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="qty">SUBTOTAL</td>
            <td class="total">{{ $purchase_order->currency_order }} {{ number_format((float)($purchase_order->total-$purchase_order->igv), 2) }}</td>
        </tr>
        <tr>
            <td class="desc"></td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty">IGV</td>
            <td class="total">{{ $purchase_order->currency_order }} {{ $purchase_order->igv }}</td>
        </tr>
        <tr>
            <td class="desc"></td>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty">TOTAL </td>
            <td class="total">{{ $purchase_order->currency_order }} {{ $purchase_order->total }}</td>
        </tr>
        {{--<tr>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty">RENTA {{ $quote->rent }}%</td>
            <td class="total">S/. {{ $quote->subtotal_rent }}.00</td>
        </tr>--}}
        </tbody>
    </table>
    {{--<div id="notices">
        <div>TÉRMINOS Y CONDICIONES:</div>
        <div class="notice">FORMA DE PAGO: {{ $quote->way_to_pay }}</div>
        <div class="notice">TIEMPO DE ENTREGA: {{ $quote->delivery_time }}</div>
        <div class="notice">PRECIO NO INCLUYE IGV, EL PRECIO ESTA EXPRESADO EN DÓLARES AMERICANOS</div>

    </div>--}}
    <br><br>
    {{--<div id="notices">
        <div class="center">Los equipos cotizados cumplen con los estándares de fabricación de equipos para plantas de alimentos (diseño
            sanitarios) , adecuado uso de recursos (estándares de ahorro energético, emisiones).</div>
        <br><br>
        <div class="notice">Sin otro particular, quedamos de usted.</div>
        <div class="notice">Atentamente</div>
    </div>--}}
</main>
{{--<div class="page-break"></div>
<header class="clearfix">
    <div id="logo">
        <img src="{{ asset('/landing/img/logo_pdf.png') }}">
        <div id="company3" class="clearfix">
            <div>RUC 20580001384</div>
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
    A.H. Ramiro Prialé Mz. 17 Lte. 1  |  +51 998-396-337
</footer>
</body>
</html>