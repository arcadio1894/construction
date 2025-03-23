<!DOCTYPE html>
<html>
<head>
    <title>Alerta de Desfase de Stock</title>
</head>
<body>
<h2>🔴 Se ha detectado un desfase en los siguientes materiales:</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Stock Actual</th>
        <th>Cantidad Ítems</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($desfases as $desfase)
        <tr>
            <td>{{ $desfase->material_id }}</td>
            <td>{{ $desfase->full_name }}</td>
            <td>{{ $desfase->stock_current }}</td>
            <td>{{ $desfase->quantity_items }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<p>Por favor, revisa el sistema para más detalles.</p>
</body>
</html>
