$(document).ready(function () {
    $.ajax({
        url: "/api/sunat",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            console.log(json.compra);
            $('#tasaCompra').html('Compra: '+json.compra);
            $('#tasaVenta').html('Venta: '+json.venta);
        }
    });

});

