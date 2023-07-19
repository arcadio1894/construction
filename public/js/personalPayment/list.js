$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    console.log($permissions);
    $("#btn-pays").on('click', reportPersonalPayment);

});

var $permissions;

function reportPersonalPayment() {
    // Variables year y month con los valores deseados
    var year = $("#year").val();
    var month = $("#month").val();

    var monthName = $("#month").select2('data')[0].text;

    var params = {
        year: year,
        month: month
    };

    var proyectadoDolares = 10000; // Valor proyectado en dólares
    var proyectadoSoles = 30000; // Valor proyectado en soles

    // Realizar la petición $.get para obtener los datos del servidor
    $.get("/dashboard/personal/payments", params, function(data) {
        console.log(data);
        $("#titleCard").html("<strong>PAGO DE PERSONAL - " + monthName.toUpperCase()+"<strong>");
        // data ya será un objeto JavaScript (no es necesario parsearlo)
        // Construir la tabla dinámica
        $("#tablaContainer").html("");
        var tabla = $("<table>").addClass("table table-sm table-bordered table-striped letraTabla");

        var headerRow = $("<tr>").addClass("letraTablaGrande");

        // Encabezados de las columnas
        headerRow.append($("<th>").addClass("titleHeader").text("N°"));
        headerRow.append($("<th>").addClass("titleHeader").addClass("text-center").text("Trabajador"));

        // Iterar sobre las semanas para agregarlas como encabezados
        data[0].weeks.forEach(function(week) {
            headerRow.append($("<th>").addClass("titleHeader").addClass("text-center").text("Semana " + week.semana));
        });

        headerRow.append($("<th>").addClass("titleTotal").addClass("text-center").text("Total"));
        tabla.append(headerRow);

        // Iterar sobre cada trabajador para agregarlos a la tabla
        for (var i = 0; i < data.length - 1; i++) {
            var trabajador = data[i];
            var row = $("<tr>");

            row.append($("<td>").addClass("text-center").addClass("celdas").text(trabajador.codigo));
            row.append($("<td>").addClass("celdas").text(trabajador.trabajador.toUpperCase()));

            // Iterar sobre las semanas para agregar los montos
            trabajador.weeks.forEach(function (week) {
                row.append($("<td>").addClass("celdas").addClass("text-right").text(week.monto.toFixed(2)));
            });

            row.append($("<td>").addClass("totalWorker").addClass("text-right").text(trabajador.total.toFixed(2)));
            tabla.append(row);
        }

        // Agregar la primera fila con los montos en la moneda original
        var primeraFila = $("<tr>").addClass("totales");
        primeraFila.append($("<td>").attr("colspan", 2).addClass("text-center").text("TOTAL SOLES"));
        data[data.length - 1].weeks.forEach(function(week) {
            primeraFila.append($("<td>").addClass("text-right").text(week.monto.toFixed(2)));
        });
        primeraFila.append($("<td>").addClass("titleTotal").addClass("text-right").text(data[data.length - 1].total.toFixed(2)));
        tabla.append(primeraFila);

        // Agregar la segunda fila con los montos en dólares
        var segundaFila = $("<tr>").addClass("totales");
        segundaFila.append($("<td>").attr("colspan", 2).addClass("text-center").text("TOTAL DOLARES"));
        data[data.length - 1].weeks.forEach(function(week) {
            segundaFila.append($("<td>").addClass("text-right").text(week.montoEnDolares.toFixed(2)));
        });
        segundaFila.append($("<td>").addClass("titleTotal").addClass("text-right").text(data[data.length - 1].totalDolares.toFixed(2)));
        tabla.append(segundaFila);

        // Tercera fila: PROYECTADO EN DOLARES
        var terceraFila = $("<tr>").addClass("totales");
        terceraFila.append($("<td>").addClass("text-right").attr("colspan", data[0].weeks.length+2).text("PROYECTADO EN DOLARES"));
        terceraFila.append($("<td>").addClass("titleTotal").addClass("text-right").text(proyectadoDolares.toFixed(2)));
        tabla.append(terceraFila);

        // Cuarta fila: DIFERENCIA EN DOLARES
        var cuartaFila = $("<tr>").addClass("totales");
        cuartaFila.append($("<td>").addClass("text-right").attr("colspan", data[0].weeks.length + 2).text("DIFERENCIA EN DOLARES"));
        cuartaFila.append($("<td>").addClass("titleTotal").addClass("text-right").text((proyectadoDolares - data[data.length - 1].totalDolares).toFixed(2)));
        tabla.append(cuartaFila);

        // Quinta fila: PROYECTADO EN SOLES
        var quintaFila = $("<tr>").addClass("totales");
        quintaFila.append($("<td>").addClass("text-right").attr("colspan", data[0].weeks.length + 2).text("PROYECTADO EN SOLES"));
        quintaFila.append($("<td>").addClass("titleTotal").addClass("text-right").text(proyectadoSoles.toFixed(2)));
        tabla.append(quintaFila);

        // Sexta fila: DIFERENCIA EN SOLES
        var sextaFila = $("<tr>").addClass("totales");
        sextaFila.append($("<td>").addClass("text-right").attr("colspan", data[0].weeks.length + 2).text("DIFERENCIA EN SOLES"));
        sextaFila.append($("<td>").addClass("titleTotal").addClass("text-right").text((proyectadoSoles - data[data.length - 1].total).toFixed(2)));
        tabla.append(sextaFila);


        $("#tablaContainer").append(tabla);


    });

    /*$.ajax({
        url: "/dashboard/personal/payments",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            if ( json.error == 1 )
            {
                toastr.error(json.message, 'Error',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });

            } else {
                toastr.success(json.message, 'Éxito',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
                setTimeout( function () {
                    location.href = json.url;
                }, 2000 )
            }

        }
    });*/
}