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
        <th colspan="4" style="font-size: 16px; font-weight: bold;">{{ $week['title'] }}</th>
    </tr>
    <tr>
        <th width="50px" style="background-color:#fd9137; color: #fff; text-align: center">Código</th>
        <th width="250px" style="background-color:#fd9137; color: #fff; text-align: center">Trabajador</th>
        <th width="200px" style="background-color:#fd9137; color: #fff; text-align: center">Cuentas</th>
        <th width="100px" style="background-color:#fd9137; color: #fff; text-align: center">Monto</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($week['boletas'] as $payslip)
        <tr>
            <td style="text-align: center;">{!! $payslip['codigo']!!}</td>
            <td>{!! $payslip['trabajador'] !!}</td>
            <td>{!! $payslip['cuentas'] !!}</td>
            <td>{!! $payslip['monto'] !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
