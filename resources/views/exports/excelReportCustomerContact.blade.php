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
    </thead>

    <tbody>
        @foreach ($data as $customer)
            <tr>
                <th width="80px" style="background-color:#074f91; color: #ffffff; text-align: center">Código</th>
                <th width="400px" style="background-color:#074f91; color: #ffffff; text-align: center">Razón Social</th>
                <th width="150px" style="background-color:#074f91; color: #ffffff; text-align: center">RUC</th>
                <th width="500px" style="background-color:#074f91; color: #ffffff; text-align: center">Dirección</th>
                <th width="100px" style="background-color:#074f91; color: #ffffff; text-align: center">Ubicación</th>
            </tr>

            <tr>
                <td style="text-align: center;">{!! $customer['code']!!}</td>
                <td>{!! htmlspecialchars($customer['business_name']) !!}</td>
                <td>{!! '&nbsp;' . htmlspecialchars($customer['RUC']) !!}</td>
                <td>{!! htmlspecialchars($customer['address']) !!}</td>
                <td>{!! htmlspecialchars($customer['location']) !!}</td>
            </tr>

            <tr>
                <th width="80px" style="background-color:#709ac1; color: #fff; text-align: center">Código</th>
                <th width="400px" style="background-color:#709ac1; color: #fff; text-align: center">Nombre</th>
                <th width="150px" style="background-color:#709ac1; color: #fff; text-align: center">Teléfono</th>
                <th width="500px" style="background-color:#709ac1; color: #fff; text-align: center">Email</th>
            </tr>

            @foreach($customer['contacts'] as $contact)
            <tr>
                <td style="text-align: center;">{!! htmlspecialchars($contact->code) !!}</td>
                <td>{!! htmlspecialchars($contact->name) !!}</td>
                <td>{!! '&nbsp;' . htmlspecialchars($contact->phone) !!}</td>
                <td>{!! htmlspecialchars($contact->email) !!}</td>
            </tr>
            @endforeach

            <tr></tr>
        @endforeach
    </tbody>
    
</table>
</body>
</html>