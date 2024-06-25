$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);
    getDataInventory(1);


    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSearch);

    $("#btn-export").on('click', exportExcel);

    $("#btn-save").on('click', saveDataInventory);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).on('click', '[data-add_scraps]', addScraps);
    $modalScraps = $("#modalScraps");

    $(document).on('input', '#width_new', function() {
        //console.log($(this).val());
        let material_typescrap = $("#material_typescrap").val();
        let material_length = $("#material_length").val();
        let material_width = $("#material_width").val();

        let material_length_new = $("#length_new").val();
        let material_width_new = $("#width_new").val();

        if (  material_typescrap == 1 || material_typescrap == 2 || material_typescrap == 6 )
        {
            // Planchas
            let total = parseFloat(material_length)*parseFloat(material_width);
            let scrap = parseFloat(material_length_new)*parseFloat(material_width_new);

            let percentage_new = (scrap/total).toFixed(2);

            $("#percentage_new").val(percentage_new);
        } else {
            // Tubos
            let total = parseFloat(material_length);
            let scrap = parseFloat(material_length_new);

            let percentage_new = (scrap/total).toFixed(2);

            $("#percentage_new").val(percentage_new);
        }
    });

    $(document).on('input', '#length_new', function() {
        //console.log($(this).val());
        let material_typescrap = $("#material_typescrap").val();
        let material_length = $("#material_length").val();
        let material_width = $("#material_width").val();

        let material_length_new = $("#length_new").val();
        let material_width_new = $("#width_new").val();

        if (  material_typescrap == 1 || material_typescrap == 2 || material_typescrap == 6 )
        {
            // Planchas
            let total = parseFloat(material_length)*parseFloat(material_width);
            let scrap = parseFloat(material_length_new)*parseFloat(material_width_new);

            let percentage_new = (scrap/total).toFixed(2);

            $("#percentage_new").val(percentage_new);
        } else {
            // Tubos
            let total = parseFloat(material_length);
            let scrap = parseFloat(material_length_new);

            let percentage_new = (scrap/total).toFixed(2);

            $("#percentage_new").val(percentage_new);
        }

    });

    $("#btn-submit-new").on('click', saveScrap);
});

var $permissions;
var $modalScraps;

function saveScrap() {
    var id = $("#material_id").val();
    var percentage = $("#percentage_new").val();
    var $elemento = $('input[data-id="' + id + '"]');

    var value = $elemento.val();

    var new_total = (parseFloat(value) + parseFloat(percentage)).toFixed(2);

    $elemento.val(new_total);

    $modalScraps.modal("hide");
}

function addScraps() {
    // Resetear all
    $modalScraps.find('[id=material]').val("");
    $modalScraps.find('[id=material_id]').val("");
    $modalScraps.find('[id=length]').val("");
    $modalScraps.find('[id=width]').val("");
    $modalScraps.find('[id=length_new]').val(0);
    $modalScraps.find('[id=width_new]').val(0);


    var material_name = $(this).data('material');
    var material_id = $(this).data('id');
    var width = $(this).data('width');
    var length = $(this).data('length');
    var typescrap = $(this).data('typescrap');

    $modalScraps.find('[id=material]').val(material_name);
    $modalScraps.find('[id=material_id]').val(material_id);
    $modalScraps.find('[id=material_typescrap]').val(typescrap);
    $modalScraps.find('[id=material_length]').val(length);
    $modalScraps.find('[id=material_width]').val(width);


    if (  typescrap == 1 || typescrap == 2 || typescrap == 6 )
    {
        // Planchas
        $('#length').show();
        $('#width').show();
        $('#length_title').show();
        $('#width_title').show();
        $modalScraps.find('[id=length]').val(length);
        $modalScraps.find('[id=width]').val(width);
        $('#length_new_title').show();
        $('#width_new_title').show();
    } else {
        // Tubos
        $('#length').show();
        $('#length_title').show();
        $('#width').hide();
        $('#width_title').hide();
        $modalScraps.find('[id=length]').val(length);
        $('#length_new_title').show();
        $('#width_new_title').hide();
    }

    $modalScraps.modal('show');
}

function saveDataInventory() {
    $("#btn-save").attr("disabled", true);

    var arrayInventory = [];
    $('[data-inventory]').each(function(){
        var input = $(this);
        var material_id = input.attr('data-id');
        var quantity = input.val();

        arrayInventory.push({"material_id": material_id, "quantity": quantity});
    });

    //console.log(arrayInventory);
    $.ajax({
        url: '/dashboard/save/data/inventory/1',
        method: 'POST',
        data: JSON.stringify({ data: arrayInventory }),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData:false,
        contentType:'application/json; charset=utf-8',
        success: function (data) {
            console.log(data);

            $.alert(data.message);
            $("#btn-save").attr("disabled", false);

        },
        error: function (data) {
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
            $("#btn-save").attr("disabled", false);

        },
    });
}

function exportExcel() {
    $("#btn-save").attr("disabled", true);
    toastr.success('Descargando archivo ...', 'Éxito',
        {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "2000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        });

    var url = "/dashboard/exportar/listado/inventario/v2/";

    window.location = url;

    $("#btn-save").attr("disabled", false);
}

function showDataSearch() {
    getDataInventory(1)
}

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataInventory(numberPage)
}

function getDataInventory($numberPage) {
    $('[data-toggle="tooltip"]').tooltip('dispose').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    var full_name = $('#full_name').val();

    $.get('/dashboard/get/data/inventory/'+$numberPage, {
        full_name:full_name,
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataInventoryEmpty(data);
        } else {
            renderDataInventory(data);
        }


    }).fail(function(jqXHR, textStatus, errorThrown) {
        // Función de error, se ejecuta cuando la solicitud GET falla
        console.error(textStatus, errorThrown);
        if (jqXHR.responseJSON.message && !jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.message, 'Error', {
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
        for (var property in jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.errors[property], 'Error', {
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
    }, 'json')
        .done(function() {
            // Configuración de encabezados
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            };

            $.ajaxSetup({
                headers: headers
            });
        });
}

function renderDataInventoryEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' materiales');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataInventory(data) {
    var dataQuotes = data.data;
    var pagination = data.pagination;
    console.log(dataQuotes);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' materiales.');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    for (let j = 0; j < dataQuotes.length ; j++) {
        renderDataTable(dataQuotes[j]);
    }

    if (pagination.currentPage > 1)
    {
        renderPreviousPage(pagination.currentPage-1);
    }

    if (pagination.totalPages > 1)
    {
        if (pagination.currentPage > 3)
        {
            renderItemPage(1);

            if (pagination.currentPage > 4) {
                renderDisabledPage();
            }
        }

        for (var i = Math.max(1, pagination.currentPage - 2); i <= Math.min(pagination.totalPages, pagination.currentPage + 2); i++)
        {
            renderItemPage(i, pagination.currentPage);
        }

        if (pagination.currentPage < pagination.totalPages - 2)
        {
            if (pagination.currentPage < pagination.totalPages - 3)
            {
                renderDisabledPage();
            }
            renderItemPage(i, pagination.currentPage);
        }

    }

    if (pagination.currentPage < pagination.totalPages)
    {
        renderNextPage(pagination.currentPage+1);
    }
}

function renderDataTableEmpty() {
    var clone = activateTemplate('#item-table-empty');
    $("#body-table").append(clone);
}

function renderDataTable(data) {
    var clone = activateTemplate('#item-table');
    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-full_name]").innerHTML = data.full_name;
    clone.querySelector("[data-stock]").innerHTML = data.stock;

    clone.querySelector("[data-inventory]").setAttribute('data-id', data.id);
    clone.querySelector("[data-inventory]").setAttribute('value', data.inventory);

    //console.log(data.typescrap != null );
    if ( data.typescrap != null )
    {
        clone.querySelector("[data-add_scraps]").setAttribute('data-id', data.id);
        clone.querySelector("[data-add_scraps]").setAttribute('data-length', data.length);
        clone.querySelector("[data-add_scraps]").setAttribute('data-width', data.width);
        clone.querySelector("[data-add_scraps]").setAttribute('data-typescrap', data.typescrap);
        clone.querySelector("[data-add_scraps]").setAttribute('data-material', data.full_name);
    } else {
        let element = clone.querySelector("[data-add_scraps]");
        //console.log(element);
        if (element) {
            element.style.display = 'none';
        }
    }

    clone.querySelector("[data-location]").innerHTML = data.location;

    /*clone.querySelector("[data-formEditFacturacion]").setAttribute('data-formEditFacturacion', data.id);
    clone.querySelector("[data-formEditFacturacion]").setAttribute('data-type', data.type);*/

    $("#body-table").append(clone);

    $('[data-toggle="tooltip"]').tooltip();
}

function renderPreviousPage($numberPage) {
    var clone = activateTemplate('#previous-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

function renderDisabledPage() {
    var clone = activateTemplate('#disabled-page');
    $("#pagination").append(clone);
}

function renderItemPage($numberPage, $currentPage) {
    var clone = activateTemplate('#item-page');
    if ( $numberPage == $currentPage )
    {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-active]").setAttribute('class', 'page-item active');
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    } else {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    }

    $("#pagination").append(clone);
}

function renderNextPage($numberPage) {
    var clone = activateTemplate('#next-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}