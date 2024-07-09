$(document).ready(function () {

    $('#destination').on('change', changeDestination);
    $('#type').on('change', changeType);
    //$formCreate.on('submit', storeCustomer);

    $('#customer_id').on('select2:select', function(e) {
        $("#receiver").val("");
        $("#receiver").removeAttr('readonly');
        $("#document").val("");
        $("#document").removeAttr('readonly');
        $("#puntoLlegada").val("");
        $("#puntoLlegada").removeAttr('readonly');

        var selectedOption = $(this).find('option:selected');
        var ruc = selectedOption.data('ruc');
        var address = selectedOption.data('address');
        console.log('Customer RUC:', ruc);
        console.log('Customer Address:', address);
        $("#receiver").attr('readonly', 'readonly');
        $("#document").val(ruc);
        $("#document").attr('readonly', 'readonly');
        $("#puntoLlegada").val(address);
        $("#puntoLlegada").attr('readonly', 'readonly');
    });

    // Manejar el evento de cambio para supplier_id
    $('#supplier_id').on('select2:select', function(e) {
        $("#receiver").val("");
        $("#receiver").removeAttr('readonly');

        $("#document").val("");
        $("#document").removeAttr('readonly');
        $("#puntoLlegada").val("");
        $("#puntoLlegada").removeAttr('readonly');

        var selectedOption = $(this).find('option:selected');
        var ruc = selectedOption.data('ruc');
        var address = selectedOption.data('address');
        console.log('Supplier RUC:', ruc);
        console.log('Supplier Address:', address);
        $("#receiver").attr('readonly', 'readonly');
        $("#document").val(ruc);
        $("#document").attr('readonly', 'readonly');
        $("#puntoLlegada").val(address);
        $("#puntoLlegada").attr('readonly', 'readonly');
    });

    $('#quantity').on('keypress', addRow);

    $(document).on('click', '[data-delete]', deleteRow);

    $("#btn-submit").on('click', saveReferralGuide);

    $('#customer_id').select2({
        placeholder: "Selecione Cliente",
        allowClear: true
    });

    $('#supplier_id').select2({
        placeholder: "Selecione Proveedor",
        allowClear: true
    });
});

var $formCreate;

function saveReferralGuide() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);

    // Obtener el token CSRF desde la meta etiqueta
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // TODO: Datos generales
    var guide_id = $("#guide_id").val();
    var date_transfer = $("#date_transfer").val();
    var reason_id = $("#reason_id").val();
    var destination = $("#destination").val();
    var customer_id = "";
    var supplier_id = "";

    if (destination == 'Cliente') {
        customer_id = $('#customer_id').val();
        console.log(customer_id);

        if (customer_id == null)
        {
            toastr.error("Seleccione un cliente", 'Error',
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
            return;
        }

    } else if (destination == 'Proveedor') {

        supplier_id = $('#supplier_id').val();
        console.log(supplier_id);
        if (supplier_id == null)
        {
            toastr.error("Seleccione un proveedor", 'Error',
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
            return;
        }

    } else if (destination == 'Otros') {
        if ( $('#receiver').val() == "" || $('#document').val() == "" || $('#puntoLlegada').val() == "" )
        {
            toastr.error("Ingrese los datos del destinatario correctamente.", 'Error',
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
            return;
        }
    }

    var receiver = $("#receiver").val();
    var puntoLlegada = $("#puntoLlegada").val();
    var document = $("#document").val();
    var placa = $("#placa").val();
    var driver = $("#driver").val();
    var driver_licence = $("#driver_licence").val();

    var responsible = $("#responsible_id").val();

    // TODO: Recorremos todas las secciones
    let rowsData = [];

    $('#body-rows [data-row_selected]').each(function(index) {
        let sectionData = {};

        sectionData.id = $(this).find('button[data-delete]').attr('data-delete');
        sectionData.code = $(this).find('[data-code]').html();
        sectionData.description = $(this).find('[data-description]').html();
        sectionData.unit = $(this).find('[data-unit]').html();
        sectionData.quantity = $(this).find('[data-quantity]').html();
        sectionData.type = $(this).find('button[data-type]').attr('data-type');

        rowsData.push(sectionData);
    });

    //console.log(sectionsData);
    // Crear FormData y añadir todos los datos
    var formData = new FormData();
    formData.append('guide_id', guide_id);
    formData.append('date_transfer', date_transfer);
    formData.append('reason_id', reason_id);
    formData.append('destination', destination);
    formData.append('customer_id', customer_id);
    formData.append('supplier_id', supplier_id);
    formData.append('receiver', receiver);
    formData.append('puntoLlegada', puntoLlegada);
    formData.append('document', document);
    formData.append('placa', placa);
    formData.append('driver', driver);
    formData.append('driver_licence', driver_licence);
    formData.append('responsible', responsible);
    formData.append('rows', JSON.stringify(rowsData));
    formData.append('_token', csrfToken);

    // Enviar los datos al servidor usando $.ajax
    $.ajax({
        url: $("#btn-submit").attr("data-url"), // Reemplaza con la URL de tu servidor
        type: 'POST',
        data: formData,
        contentType: false, // Para evitar que jQuery establezca el contentType
        processData: false, // Para evitar que jQuery procese los datos
        success: function(data) {
            toastr.success(data.message, 'Éxito',
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
                $("#btn-submit").attr("disabled", false);
                location.reload();
            }, 2000 )
        },
        error: function(data) {
            if( data.responseJSON.message && !data.responseJSON.errors )
            {
                toastr.error(data.responseJSON.message, 'Error',
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
            }
            for ( var property in data.responseJSON.errors ) {
                toastr.error(data.responseJSON.errors[property], 'Error',
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
            }
            // Habilitar el botón y manejar el error
            $("#btn-submit").attr("disabled", false);
        }
    });

}

function deleteRow() {
    $(this).parent().parent().remove();
}

function addRow(event) {
    if (event.which == 13) { // Código de tecla Enter es 13
        event.preventDefault(); // Prevenir la acción predeterminada

        // Obtener valores seleccionados de los select
        var materialId = $('#material_id').val();
        var quoteId = $('#quote_id').val();
        var quantity = $('#quantity').val();

        // Verificar si algún select tiene un valor seleccionado
        if (!materialId && !quoteId) {
            toastr.error('Debe seleccionar un material o una cotización', 'Error',
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
            return;
        }

        // Verificar si se ha ingresado una cantidad válida
        if (!quantity || quantity <= 0) {
            toastr.error('Debe ingresar una cantidad adecuada', 'Error',
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
            return;
        }

        // Si se selecciona un material, obtener sus datos
        if (materialId) {
            var selectedMaterial = $('#material_id option:selected');
            var materialData = {
                id: selectedMaterial.data('id'),
                code: selectedMaterial.data('code'),
                description: selectedMaterial.data('description'),
                unit: selectedMaterial.data('unit'),
                quantity: quantity,
                type: 'material'
            };
            console.log('Selected Material Data:', materialData);
            renderTemplateRow(materialData);
            $('#material_id').val(null).trigger('change');
            $('#quantity').val('');
        }

        // Si se selecciona una cotización, obtener sus datos (opcional)
        if (quoteId) {
            var selectedQuote = $('#quote_id option:selected');
            var quoteData = {
                id: selectedQuote.data('id'),
                code: selectedQuote.data('code'),
                description: selectedQuote.data('description'),
                unit: selectedQuote.data('unit'),
                quantity: quantity,
                type: 'quote'
            };
            console.log('Selected Quote Data:', quoteData);
            renderTemplateRow(quoteData);
            $('#quote_id').val(null).trigger('change');
            $('#quantity').val('');
        }

    }
}

function renderTemplateRow(data) {
    var clone = activateTemplate('#template-row');

    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-description]").innerHTML = data.description;
    clone.querySelector("[data-unit]").innerHTML = data.unit;
    clone.querySelector("[data-quantity]").innerHTML = data.quantity;
    clone.querySelector("[data-delete]").setAttribute('data-delete', data.id);
    clone.querySelector("[data-type]").setAttribute('data-type', data.type);

    $("#body-rows").append(clone);
}

function changeType() {
    // Obtener el valor seleccionado
    var selectedOption = $(this).val();

    // Ocultar todos los elementos y resetearlos
    $('#div_material_id').hide();
    $('#material_id').val(null).trigger('change');
    $('#div_quote_id').hide();
    $('#quote_id').val(null).trigger('change');

    // Mostrar el elemento correspondiente basado en la selección
    if (selectedOption === 'Materiales') {
        $('#div_material_id').show();
        $('#material_id').select2({
            placeholder: "Selecione materiales",
            allowClear: true
        });
    } else if (selectedOption === 'Cotizaciones') {
        $('#div_quote_id').show();
        $('#quote_id').select2({
            placeholder: "Selecione cotizaciones",
            allowClear: true
        });
    }
}

function changeDestination() {
    $("#receiver").val("");
    $("#receiver").removeAttr('readonly');
    $("#document").val("");
    $("#document").removeAttr('readonly');
    $("#puntoLlegada").val("");
    $("#puntoLlegada").removeAttr('readonly');

    // Obtener el valor seleccionado
    var selectedOption = $(this).val();

    // Ocultar todos los elementos y resetearlos
    $('#div_customer_id').hide();
    $('#customer_id').val(null).trigger('change');
    $('#div_supplier_id').hide();
    $('#supplier_id').val(null).trigger('change');
    $('#div_receiver').hide();
    $('#receiver').val("");

    // Mostrar el elemento correspondiente basado en la selección
    if (selectedOption === 'Cliente') {
        $("#receiver").val("");
        $("#receiver").attr('readonly', 'readonly');
        $('#div_customer_id').show();
        $('#customer_id').select2({
            placeholder: "Selecione Cliente",
            allowClear: true
        });
    } else if (selectedOption === 'Proveedor') {
        $("#receiver").val("");
        $("#receiver").attr('readonly', 'readonly');
        $('#div_supplier_id').show();
        $('#supplier_id').select2({
            placeholder: "Selecione Proveedor",
            allowClear: true
        });
    } else if (selectedOption === 'Otros') {
        $('#div_receiver').show();
        $("#receiver").val("");
        $("#receiver").removeAttr('readonly');
    }
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}
