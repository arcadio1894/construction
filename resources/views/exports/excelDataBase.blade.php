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
            <th width="80px" style="background-color: #7A8DC5; font-size: 14px">Código</th>
            <th width="600px" style="background-color: #7A8DC5; font-size: 14px">Material</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px">Medida</th>
            <th width="140px" style="background-color: #7A8DC5; font-size: 14px">Unidad Medida</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px">Stock Max.</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px">Stock Min.</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px">Stock Actual</th>
            <th width="90px" style="background-color: #7A8DC5; font-size: 14px">Prioridad</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px">Precio Unit.</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px">Precio Total</th>
            <th width="200px" style="background-color: #7A8DC5; font-size: 14px">Ubicaciones</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Categoría</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Subcategoría</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Tipo</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">SubTipo</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Marca</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Modelo</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Calidad</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Cédula</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Retacería</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($materials); $i++ )
        @if ( ($i+1) % 2 == 0)
        <tr>
            <th width="80px">{{ $materials[$i]['code'] }}</th>
            <th width="600px">{{ $materials[$i]['material'] }}</th>
            <th width="90px">{{ $materials[$i]['measure'] }}</th>
            <th width="140px">{{ $materials[$i]['unit'] }}</th>
            <th width="100px">{{ $materials[$i]['stock_max'] }}</th>
            <th width="100px">{{ $materials[$i]['stock_min'] }}</th>
            @if( $materials[$i]['stock_current'] == 0 )
                <th width="100px" style="color: red">{{ $materials[$i]['stock_current'] }}</th>
            @else
                <th width="100px">{{ $materials[$i]['stock_current'] }}</th>
            @endif
            @if( $materials[$i]['priority'] == 'Agotado' )
                <th width="190px" style="color: red">{{ $materials[$i]['priority'] }}</th>
            @endif
            @if( $materials[$i]['priority'] == 'Por agotarse' )
                <th width="90px" style="color: orange">{{ $materials[$i]['priority'] }}</th>
            @endif
            @if( $materials[$i]['priority'] == 'Aceptable' )
                <th width="90px" style="color: blue">{{ $materials[$i]['priority'] }}</th>
            @endif
            @if( $materials[$i]['priority'] == 'Completo' )
                <th width="90px" style="color: green">{{ $materials[$i]['priority'] }}</th>
            @endif
            <th width="100px">{{ $materials[$i]['price'] }}</th>
            <th width="100px">{{ round( ((float)$materials[$i]['price'] * (float)$materials[$i]['stock_current']) , 2 ) }}</th>
            <th width="200px">{{ $materials[$i]['location'] }}</th>
            <th width="150px">{{ $materials[$i]['category'] }}</th>
            <th width="150px">{{ $materials[$i]['subcategory'] }}</th>
            <th width="150px">{{ $materials[$i]['type'] }}</th>
            <th width="150px">{{ $materials[$i]['subtype'] }}</th>
            <th width="150px">{{ $materials[$i]['brand'] }}</th>
            <th width="150px">{{ $materials[$i]['exampler'] }}</th>
            <th width="150px">{{ $materials[$i]['quality'] }}</th>
            <th width="150px">{{ $materials[$i]['warrant'] }}</th>
            <th width="150px">{{ $materials[$i]['scrap'] }}</th>
        </tr>
        @else
            <tr>
                <th width="80px" style="background-color: #D0E4F7">{{ $materials[$i]['code'] }}</th>
                <th width="600px" style="background-color: #D0E4F7">{{ $materials[$i]['material'] }}</th>
                <th width="90px" style="background-color: #D0E4F7">{{ $materials[$i]['measure'] }}</th>
                <th width="140px" style="background-color: #D0E4F7">{{ $materials[$i]['unit'] }}</th>
                <th width="100px" style="background-color: #D0E4F7">{{ $materials[$i]['stock_max'] }}</th>
                <th width="100px" style="background-color: #D0E4F7">{{ $materials[$i]['stock_min'] }}</th>
                @if( $materials[$i]['stock_current'] == 0 )
                    <th width="100px" style="background-color: #D0E4F7; color: red">{{ $materials[$i]['stock_current'] }}</th>
                @else
                    <th width="100px" style="background-color: #D0E4F7">{{ $materials[$i]['stock_current'] }}</th>
                @endif
                @if( $materials[$i]['priority'] == 'Agotado' )
                    <th width="90px" style="background-color: #D0E4F7;color: red">{{ $materials[$i]['priority'] }}</th>
                @endif
                @if( $materials[$i]['priority'] == 'Por agotarse' )
                    <th width="90px" style="background-color: #D0E4F7;color: orange">{{ $materials[$i]['priority'] }}</th>
                @endif
                @if( $materials[$i]['priority'] == 'Aceptable' )
                    <th width="90px" style="background-color: #D0E4F7;color: blue">{{ $materials[$i]['priority'] }}</th>
                @endif
                @if( $materials[$i]['priority'] == 'Completo' )
                    <th width="90px" style="background-color: #D0E4F7;color: green">{{ $materials[$i]['priority'] }}</th>
                @endif
                <th width="100px" style="background-color: #D0E4F7">{{ $materials[$i]['price'] }}</th>
                <th width="100px" style="background-color: #D0E4F7">{{ round( ((float)$materials[$i]['price'] * (float)$materials[$i]['stock_current']) , 2 ) }}</th>
                <th width="200px" style="background-color: #D0E4F7">{{ $materials[$i]['location'] }}</th>
                <th width="200px" style="background-color: #D0E4F7">{{ $materials[$i]['category'] }}</th>
                <th width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['subcategory'] }}</th>
                <th width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['type'] }}</th>
                <th width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['subtype'] }}</th>
                <th width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['brand'] }}</th>
                <th width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['exampler'] }}</th>
                <th width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['quality'] }}</th>
                <th width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['warrant'] }}</th>
                <th width="150px" style="background-color: #D0E4F7">{{ $materials[$i]['scrap'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>