<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cotización</title>
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
            margin-bottom: 20px;
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
        }

        table th {
            padding: 5px 10px;
            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: bold;
            background-color: #1c3c80;
            font-size: 1.2em;
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

        #notices .notice2 {
            color: #5D6975;
            font-size: 0.9em;
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
            max-width: 720px;
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
        <div id="company3" class="clearfix">
            <div>RUC 20540001384</div>
            <div>Predio el Horcón - Sector el Horcón U.C 02972- F-Moche</div>
            {{--<div>La Esperanza, Trujillo, Perú</div>--}}
            <div>Sitio Web: www.sermeind.com.pe</div>
            <div>Teléfono: +51 959 332 205</div>
            <div>Email: servicios@sermeind.com.pe</div>
            <div>Cotizado por: </div>
        </div>
    </div>

    <h1>COTIZACIÓN: {{ $quote->code }}</h1>

    <div id="company2" class="clearfix">
        <div>CLIENTE</div>
        <div>{{ ($quote->customer !== null) ? $quote->customer->business_name : 'No tiene cliente' }}</div>
        <div>{{ ($quote->contact !== null) ? $quote->contact->name : 'No tiene contacto' }}</div>
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
    <div>Nos es grato dirigirnos a ustedes para hacerles llegar la presente cotización de acuerdo a nuestra conversación.</div>
</div>
<br>

<main>

    <table>
        <thead>
        <tr>
            <th class="desc">DESCRIPCIÓN</th>
            <th>PRECIO UNIT. </th>
            <th>CANT.</th>
            <th>TOTAL</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $quote->equipments as $equipment )
            @if ( $quote->state_decimals == 1 )
                <tr>
                    <td class="desc">{{ $equipment->description }}</td>
                    @php
                        $subtotal = round(($equipment->subtotal_rent/1.18), 2);
                        $unit_price = $subtotal / $equipment->quantity;
                    @endphp
                    <td class="unit">{{ $quote->currency_invoice }} {{ number_format( $unit_price, 2) }}</td>
                    <td class="qty">{{ $equipment->quantity }}</td>
                    <td class="total">{{ $quote->currency_invoice }} {{ number_format( $subtotal, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td class="desc">{{ $equipment->description }}</td>
                    @php
                        $subtotal = round(($equipment->subtotal_rent/1.18), 0);
                        $unit_price = $subtotal / $equipment->quantity;
                    @endphp
                    <td class="unit">{{ $quote->currency_invoice }} {{ number_format( $unit_price, 2) }}</td>
                    <td class="qty">{{ $equipment->quantity }}</td>
                    <td class="total">{{ $quote->currency_invoice }} {{ number_format( $subtotal, 0) }}</td>
                </tr>
            @endif

        @endforeach
        </tbody>
    </table>
    <br><br>
    <table id="sumary">
        <tbody>
        <tr>
            <td class=""></td>
            <td class=""></td>
            <td class="qty">TOTAL</td>
            @if ( $quote->state_decimals == 1 )
                <td class="total">{{ $quote->currency_invoice }} {{ number_format( (float)($quote->total_quote/1.18), 2) }}</td>
            @else
                <td class="total">{{ $quote->currency_invoice }} {{ number_format( (float)($quote->total_quote/1.18), 0) }}.00</td>
            @endif
        </tr>
        {{--<tr>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty">UTILIDAD {{ $quote->utility }}%</td>
            <td class="total">{{ $quote->currency_invoice }} {{ $quote->subtotal_utility }}</td>
        </tr>
        <tr>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty">LETRA {{ $quote->letter }}%</td>
            <td class="total">{{ $quote->currency_invoice }} {{ $quote->subtotal_letter }}</td>
        </tr>
        <tr>
            <td class="desc"></td>
            <td class="unit"></td>
            <td class="qty">RENTA {{ $quote->rent }}%</td>
            <td class="total">S/. {{ $quote->subtotal_rent }}.00</td>
        </tr>--}}
        </tbody>
    </table>
    <div id="notices">
        <div>TÉRMINOS Y CONDICIONES:</div>
        <div class="notice">FORMA DE PAGO: {{ ($quote->deadline !== null) ? $quote->deadline->description : 'No tiene forma de pago' }} </div>
        <div class="notice">TIEMPO DE ENTREGA: {{ ($quote->time_delivery == null || $quote->time_delivery == "") ? $quote->time_delivery: $quote->time_delivery . " DÍAS" }}</div>
        {{--@if( $quote->currency_invoice === 'USD' )
            <div class="notice">PRECIO NO INCLUYE IGV, EL PRECIO ESTA EXPRESADO EN {{ ( $quote->currency_invoice === 'USD' ) ? 'DÓLARES AMERICANOS':'SOLES' }} </div>
        @else
            <div class="notice">PRECIO INCLUYE IGV, EL PRECIO ESTA EXPRESADO EN {{ ( $quote->currency_invoice === 'USD' ) ? 'DÓLARES AMERICANOS':'SOLES' }} </div>
        @endif--}}
        <div class="notice">PRECIO NO INCLUYE IGV, EL PRECIO ESTA EXPRESADO EN {{ ( $quote->currency_invoice === 'USD' ) ? 'DÓLARES AMERICANOS':'SOLES' }} </div>

        <br>
        <div>OBSERVACIONES:</div>
        <div class="notice2">{!! nl2br($quote->observations) !!}</div>
    </div>
    <br>
    <div id="notices">
        <div class="center">Los equipos cotizados cumplen con los estándares de fabricación de equipos para plantas de alimentos (diseño
            sanitarios) , adecuado uso de recursos (estándares de ahorro energético, emisiones).</div>
        <br>
        <div class="notice">Sin otro particular, quedamos de usted.</div>
        <div class="notice">Atentamente</div>
    </div>
</main>
@if($quote->have_details || $quote->have_images)
<div class="page-break"></div>

<header class="clearfix">
    <div id="logo">
        <img src="{{ asset('/landing/img/logo_pdf.png') }}">
        <div id="company3" class="clearfix">
            <div>RUC 20540001384</div>
            <div>Predio el Horcón - Sector el Horcón U.C 02972- F-Moche</div>
            {{--<div>La Esperanza, Trujillo, Perú</div>--}}
            <div>Sitio Web: www.sermeind.com.pe</div>
            <div>Teléfono: +51 959 332 205</div>
            <div>Email: servicios@sermeind.com.pe</div>
            <div>Cotizado por: </div>
        </div>
    </div>

    <h1>COTIZACIÓN: {{ $quote->code }}</h1>

    <div id="company2" class="clearfix">
        <div>CLIENTE</div>
        <div>{{ ($quote->customer !== null) ? $quote->customer->business_name : 'No tiene cliente' }}</div>
        <div>{{ ($quote->contact !== null) ? $quote->contact->name : 'No tiene contacto' }}</div>
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
    {{--<div>CARACTERISTICAS DE {{ $quote->code }}:</div>--}}
    {{--<br>--}}
    @foreach( $quote->equipments as $equipment )
        <div class="notice"><strong>{{ $equipment->description }}</strong> </div>
        <div class="notice">{!! nl2br($equipment->detail) !!}</div><br>
    @endforeach
</div>

<div id="notices">
    @if ( count($images) > 0 )
        <div><strong>PLANOS DE LA COTIZACIÓN {{ $quote->code }}</strong> </div>
        <br>
    @endif

    @foreach( $images as $image )

        <div class="notice">
            <div class="notice"><em><u>{{ $image->description }}</u></em></div><br>
            {{--<img src="{{ asset('/images/planos/'.$image->image) }}" class="plano contain"><br>
            <img src="{{ asset('/images/planos/'.$image->image) }}" style="width:500px; height:500px" ><br>
--}}
            <img src="{{ asset('/images/planos/'.$image->image) }}" {{ ($image->height == 0 && $image->width == 0) ? 'class="plano contain"':'width='.(37*$image->width).'px height='.(37*$image->height).'px' }} ><br>
        </div><br>
    @endforeach
</div>

@endif

<footer>
    Predio el Horcón - Sector el Horcón U.C 02972- F-Moche  |  +51 959 332 205
</footer>
</body>
</html>