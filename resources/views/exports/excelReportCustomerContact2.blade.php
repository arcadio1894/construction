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
            <th colspan="4" style="font-size: 16px; font-weight: bold;">Reporte de Clientes al: {{ $date }}</th>
        </tr>
        <tr>
            <th width="80px" style="background-color:#074f91; color: #ffffff; text-align: center">Código</th>
            <th width="400px" style="background-color:#074f91; color: #ffffff; text-align: center">Razón Social</th>
            <th width="150px" style="background-color:#074f91; color: #ffffff; text-align: center">RUC</th>
            <th width="500px" style="background-color:#074f91; color: #ffffff; text-align: center">Dirección</th>
            <th width="100px" style="background-color:#074f91; color: #ffffff; text-align: center">Ubicación</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $customer)
            <tr>
                <td style="text-align: center;">{!! $customer['code']!!}</td>
                <td>{!! htmlspecialchars($customer['business_name']) !!}</td>
                <td>{!! htmlspecialchars($customer['RUC']) !!}</td>
                <td>{!! htmlspecialchars($customer['address']) !!}</td>
                <td>{!! htmlspecialchars($customer['location']) !!}</td>
            </tr>
        @endforeach
        <tr></tr>
    </tbody>
    
</table>
</body>
</html>