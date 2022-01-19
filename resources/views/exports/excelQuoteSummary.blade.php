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
<h1>REPORTE DE COTIZACIONES RESUMIDO</h1>
<table id="table">
    <thead>
        <tr>
            <th width="80px" style="background-color: #7A8DC5; font-size: 14px">Código</th>
            <th width="600px" style="background-color: #7A8DC5; font-size: 14px">Descripción</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Monto Materiales</th>
            <th width="200px" style="background-color: #7A8DC5; font-size: 14px">Monto Consumibles</th>
            <th width="200px" style="background-color: #7A8DC5; font-size: 14px">Monto Servicios Varios</th>
            <th width="250px" style="background-color: #7A8DC5; font-size: 14px">Monto Servicios Adicionales</th>
            <th width="200px" style="background-color: #7A8DC5; font-size: 14px">Monto Días de Trabajo</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Subtotal</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Utilidad</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Renta</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Letra</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Pagó Cliente</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Adicionales</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Costo Real</th>
            <th width="150px" style="background-color: #7A8DC5; font-size: 14px">Diferencia Neta</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($quotes); $i++ )
        @if ( ($i+1) % 2 == 0)
        <tr>
            <th width="80px">{{ $quotes[$i]['codigo'] }}</th>
            <th width="600px">{{ $quotes[$i]['descripcion'] }}</th>
            <th width="150px">{{ $quotes[$i]['monto_materiales'] }}</th>
            <th width="200px">{{ $quotes[$i]['monto_consumibles'] }}</th>
            <th width="200px">{{ $quotes[$i]['monto_servicios_varios'] }}</th>
            <th width="250px">{{ $quotes[$i]['monto_servicios_adicionales'] }}</th>
            <th width="200px">{{ $quotes[$i]['monto_dias_trabajo'] }}</th>
            <th width="150px">{{ $quotes[$i]['subtotal'] }}</th>
            <th width="150px">{{ $quotes[$i]['utilidad'] }}</th>
            <th width="150px">{{ $quotes[$i]['renta'] }}</th>
            <th width="150px">{{ $quotes[$i]['letra'] }}</th>
            <th width="150px">{{ $quotes[$i]['pago_cliente'] }}</th>
            <th width="150px">{{ $quotes[$i]['adicionales'] }}</th>
            <th width="150px">{{ $quotes[$i]['costo_real'] }}</th>
            <th width="150px">{{ $quotes[$i]['diferencia_neta'] }}</th>
        </tr>
        @else
            <tr>
                <th width="80px">{{ $quotes[$i]['codigo'] }}</th>
                <th width="600px">{{ $quotes[$i]['descripcion'] }}</th>
                <th width="150px">{{ $quotes[$i]['monto_materiales'] }}</th>
                <th width="200px">{{ $quotes[$i]['monto_consumibles'] }}</th>
                <th width="200px">{{ $quotes[$i]['monto_servicios_varios'] }}</th>
                <th width="250px">{{ $quotes[$i]['monto_servicios_adicionales'] }}</th>
                <th width="200px">{{ $quotes[$i]['monto_dias_trabajo'] }}</th>
                <th width="150px">{{ $quotes[$i]['subtotal'] }}</th>
                <th width="150px">{{ $quotes[$i]['utilidad'] }}</th>
                <th width="150px">{{ $quotes[$i]['renta'] }}</th>
                <th width="150px">{{ $quotes[$i]['letra'] }}</th>
                <th width="150px">{{ $quotes[$i]['pago_cliente'] }}</th>
                <th width="150px">{{ $quotes[$i]['adicionales'] }}</th>
                <th width="150px">{{ $quotes[$i]['costo_real'] }}</th>
                <th width="150px">{{ $quotes[$i]['diferencia_neta'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>