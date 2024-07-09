<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        #table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        #table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        #table th,
        #table td {
            text-align: center;
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
        .letraTabla {
            font-family: "Calibri", Arial, sans-serif; /* Utiliza Calibri si está instalado, de lo contrario, usa Arial o una fuente sans-serif similar */
            font-size: 14px; /* Tamaño de fuente 11 */
        }
        .normal-title {
            background-color: #203764; /* Color deseado para el fondo */
            color: #ffffff; /* Color deseado para el texto */
            text-align: center;
        }
        .cliente-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .trabajo-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .documentacion-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .importe-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .facturacion-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .abono-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
    </style>

</head>
<body>
<h1>{{ $dates }}</h1>
<table id="table">
    <thead>
    <tr>
        <th width="90px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Código</th>
        <th width="140px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Fecha de traslado</th>
        <th width="140px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Motivo de traslado</th>
        <th width="150px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Destinatario</th>
        <th width="100px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">RUC/DNI</th>
        <th width="180px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Punto de Llegada</th>
        <th width="100px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Placa</th>
        <th width="110px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Conductor</th>
        <th width="95px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Licencia</th>
        <th width="110px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Responsable</th>
        <th width="90px" style="word-wrap: break-word;background-color: #203764;color: #ffffff;text-align: center;font-weight: bold;font-size: 12px;">Estado</th>
    </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($guides); $i++ )
        <tr>
            <th width="90px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['code'] }}</th>
            <th width="140px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['date_transfer'] }}</th>
            <th width="140px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['reason'] }}</th>
            <th width="150px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['destinatario'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['documento'] }}</th>
            <th width="180px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['punto_llegada'] }}</th>
            <th width="100px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['vehiculo'] }}</th>
            <th width="110px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['driver'] }}</th>
            <th width="95px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['driver_licence'] }}</th>
            <th width="110px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['responsible'] }}</th>
            <th width="90px" style="font-size: 11px;word-wrap: break-word;vertical-align: top;">{{ $guides[$i]['enabled_status'] }}</th>
        </tr>
    @endfor
    </tbody>
</table>
</body>
</html>