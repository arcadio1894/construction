<!DOCTYPE html>
<html>
<head>
    <style>

    </style>
</head>
<body>
<table id="table">

    <thead>
        <tr>
            <th colspan="4" style="font-size: 16px; font-weight: bold;">Reporte de Proveedores al: {{ $date }}</th>
        </tr>
        <tr>
            <th width="100px" style="background-color:#074f91; color: #ffffff; text-align: center">Código</th>
            <th width="400px" style="background-color:#074f91; color: #ffffff; text-align: center">Razón Social</th>
            <th width="150px" style="background-color:#074f91; color: #ffffff; text-align: center">RUC</th>
            <th width="700px" style="background-color:#074f91; color: #ffffff; text-align: center">Dirección</th>
            <th width="100px" style="background-color:#074f91; color: #ffffff; text-align: center">Teléfono</th>
            <th width="400px" style="background-color:#074f91; color: #ffffff; text-align: center">Email</th>
        </tr>
    </thead>

    <tbody>

    @foreach ($data as $supplier)
        <tr>
            <td style="text-align: center;">{!! htmlspecialchars($supplier['code']) !!}</td>
            <td>{!! htmlspecialchars($supplier['business_name']) !!}</td>
            <td>{!! htmlspecialchars($supplier['RUC']) !!}</td>
            <td>{!! htmlspecialchars($supplier['address']) !!}</td>
            <td>{!! htmlspecialchars($supplier['phone']) !!}</td>
            <td>{!! htmlspecialchars($supplier['email']) !!}</td>

        </tr>
    @endforeach
    </tbody>

</table>
</body>
</html>