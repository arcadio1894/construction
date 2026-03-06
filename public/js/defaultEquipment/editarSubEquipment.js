let $materials=[];
let $materialsTypeahead=[];
let $consumables=[];
let $electrics=[];
let $items=[];
let $equipments=[];
let $equipmentStatus=false;
let $total=0;
let $totalUtility=0;
let $subtotal=0;
let $subtotal2=0;
let $subtotal3=0;
var $permissions;

$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    $modalAddMaterial = $('#modalAddMaterial');

    $(document).on('click', '[data-add]', addMaterial);
    
    $(document).on('click', '[data-confirm]', confirmEquipment);

    $(document).on('click', '[data-addMano]', addMano);

    $(document).on('click', '[data-addTorno]', addTorno);

    $(document).on('click', '[data-addDia]', addDia);

    $(document).on('click', '[data-addConsumable]', addConsumable);

    $(document).on('click', '[data-addElectric]', addElectric);

    $('#btn-addEquipment').on('click', addEquipment);

    $('#btn-addMaterial').on('click', addTableMaterials);
    
    $('#btnCalculate').on('click', calculatePercentage);

    $formCreate = $('#formCreate');
    $("#btn-submit").on("click", storeQuote);

    $('input[type=radio][name=presentation]').on('change', function() {
        switch ($(this).val()) {
            case 'fraction':
                if($material.typescrap_id == 3 || $material.typescrap_id == 4 || $material.typescrap_id == 5)
                {
                    $('#width_entered_material').hide();
                    $('#length_entered_material').show();
                    $('#quantity_entered_material').hide();
                    $('#material_length_entered').val('');
                    $('#material_width_entered').val('');
                    $('#material_quantity_entered').val('');
                } else {
                    $('#width_entered_material').show();
                    $('#length_entered_material').show();
                    $('#quantity_entered_material').hide();
                    $('#material_length_entered').val('');
                    $('#material_width_entered').val('');
                    $('#material_quantity_entered').val('');
                }

                break;
            case 'complete':
                $('#width_entered_material').hide();
                $('#length_entered_material').hide();
                $('#quantity_entered_material').show();
                $('#material_length_entered').val('');
                $('#material_width_entered').val('');
                $('#material_quantity_entered').val('');
                break;
        }
    });

    $('#material_search')
        .select2({
            theme: 'bootstrap4',
            placeholder: 'Buscar material',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: window.routeSearchMaterials,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: (data || []).map(function (item) {
                            return {
                                id: item.id,
                                text: item.full_name + (item.unit ? ' (' + item.unit + ')' : ''),
                                raw: item
                            };
                        })
                    };
                },
                cache: true
            }
        })
        .on('select2:select', function (e) {

            // ✅ acá está la clave
            const selected = e.params.data;
            $material = selected && selected.raw ? selected.raw : null;

            if (!$material) {
                toastr.error('No se pudo leer el material seleccionado', 'Error');
                return;
            }

            // ✅ contenedor fijo
            $renderMaterial = $('[data-bodyMaterials]').first();

            // ✅ validar duplicado por hidden
            const materialId = String($material.id);
            const exists = $('[data-bodyMaterials]')
                .find('[data-materialId]')
                .filter(function () {
                    return String($(this).val()) === materialId;
                }).length > 0;

            if (exists) {
                toastr.error('Este material ya está seleccionado', 'Error');
                $(this).val(null).trigger('change');
                return;
            }

            // Reset inputs modal
            $('#material_length_entered').val('');
            $('#material_width_entered').val('');
            $('#material_quantity_entered').val('');
            $('#material_percentage_entered').val('');
            $('#material_price_entered').val('');

            // --- lógica del modal ---
            if ($material.type_scrap === null) {

                $('#presentation').hide();
                $('#length_material').hide();
                $('#width_material').hide();
                $('#width_entered_material').hide();
                $('#length_entered_material').hide();

                $('#material_quantity').val($material.stock_current);
                $('#quantity_entered_material').show();

                $('#material_price').val($material.unit_price);

                $modalAddMaterial.modal('show');

            } else {

                $('#presentation').show();
                $("#fraction").prop("checked", true);

                switch (parseInt($material.type_scrap.id)) {
                    case 1:
                    case 2:
                    case 6:
                        $('#length_entered_material').show();
                        $('#width_entered_material').show();
                        $('#length_material').show();
                        $('#width_material').show();

                        $('#material_length').val($material.type_scrap.length);
                        $('#material_width').val($material.type_scrap.width);

                        $('#material_quantity').val($material.stock_current);
                        $('#quantity_entered_material').hide();
                        $('#material_price').val($material.unit_price);
                        break;

                    case 3:
                    case 4:
                    case 5:
                        $('#length_entered_material').show();
                        $('#length_material').show();
                        $('#material_length').val($material.type_scrap.length);

                        $('#width_material').hide();
                        $('#width_entered_material').hide();

                        $('#quantity_entered_material').hide();
                        $('#material_quantity').val($material.stock_current);
                        $('#material_price').val($material.unit_price);
                        break;

                    default:
                        $('#length_material').hide();
                        $('#width_material').hide();
                        $('#width_entered_material').hide();
                        $('#length_entered_material').hide();

                        $('#material_quantity').val($material.stock_current);
                        $('#material_percentage_entered').hide();
                        $('#material_price').val($material.unit_price);
                }

                $modalAddMaterial.modal('show');
            }

            // limpiar select2
            $(this).val(null).trigger('change');
        });

    $('.consumable_search').select2({
        theme: 'bootstrap4',
        placeholder: 'Selecciona un consumible',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: routeSelectConsumables,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term, page: params.page || 1 };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: (data.results || []).map(function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                            raw: item // ✅ guardas todo
                        };
                    }),
                    pagination: { more: data.more }
                };
            }
        }
    });

    $('.electric_search').select2({
        theme: 'bootstrap4',
        placeholder: 'Selecciona un material eléctrico',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: routeSelectElectrics,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term, page: params.page || 1 };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: (data.results || []).map(function (item) {
                        // item ya trae {id, text, ...} desde el controller
                        return {
                            id: item.id,
                            text: item.text || item.full_name || '',
                            raw: item
                        };
                    }),
                    pagination: { more: !!data.more }
                };
            }
        }
    });

    $(document).on('click', '[data-delete]', deleteItem);

    $(document).on('click', '[data-deleteConsumable]', deleteConsumable);

    $(document).on('click', '[data-deleteElectric]', deleteElectric);

    $(document).on('click', '[data-deleteMano]', deleteMano);

    $(document).on('click', '[data-deleteTorno]', deleteTorno);

    $(document).on('click', '[data-deleteDia]', deleteDia);

    $(document).on('click', '[data-deleteEquipment]', deleteEquipment);

    $(document).on('click', '[data-saveEquipment]', saveEquipment);

    $selectCustomer = $('#customer_id');
    $selectContact = $('#contact_id');

    $selectCustomer.change(function () {
        $selectContact.empty();
        var customer =  $selectCustomer.val();
        $.get( "/dashboard/get/contact/"+customer, function( data ) {
            $selectContact.append($("<option>", {
                value: '',
                text: 'Seleccione contacto'
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectContact.append($("<option>", {
                    value: data[i].id,
                    text: data[i].contact
                }));
            }
        });

    });

    $('#addImage').on('click', addImage);
    $(document).on('click', '[data-imagedelete]', imageDelete);


    // inicial
    setKeyword($('#keyword').val() || 'materials');

    // click
    $(document).on('click', '.btn-keyword', function(){
        setKeyword($(this).data('keyword'));
    });

    $(document).on('click', '[data-saveDefaultEquipment]', function () {
        saveDefaultEquipmentSection();
    });

    $(function () {
        if (window.isEditMode && window.initialEquipment) {
            // activar sección correcta y bloquear las otras
            activateKeywordSection(window.initialKeyword, true);

            // llenar nombre del equipo
            $('[data-nameequipment]').val(window.initialEquipment.description || '');

            // cargar filas según keyword
            hydrateSection(window.initialKeyword, window.initialEquipment);
        }
    });
});

var $formCreate;
var $modalAddMaterial;
var $material;
var $renderMaterial;
var $selectCustomer;
var $selectContact;

function hydrateSection(keyword, equipment) {
    // limpiar bodies
    /*$('[data-bodyMaterials]').empty();
    $('[data-bodyConsumable]').empty();
    $('[data-bodyElectric]').empty();
    $('[data-bodyMano]').empty();
    $('[data-bodyTorno]').empty();
    $('[data-bodyDia]').empty();*/

    switch (keyword) {
        case 'materials':
            hydrateMaterials(equipment.materials || []);
            break;
        case 'consumibles':
            hydrateConsumables(equipment.consumables || []);
            break;
        case 'electrics':
            hydrateElectrics(equipment.electrics || []);
            break;
        case 'servicios_varios':
            hydrateWorkforces(equipment.workforces || []);
            hydrateTurnstiles(equipment.turnstiles || []);
            break;
        case 'dias_trabajo':
            hydrateWorkdays(equipment.workdays || []);
            break;
    }
}

function setVal($el, value) { $el.val(value ?? ''); }

function hydrateMaterials(items) {
    items.forEach(it => {
        const row = activateTemplate('#materials-selected');
        const $row = $(row);

        // it: DefaultEquipmentMaterial (con material)
        setVal($row.find('[data-materialDescription]'), it.material?.full_name || it.material?.description || '');
        setVal($row.find('[data-materialId]'), it.material_id);

        setVal($row.find('[data-materialUnit]'), it.material?.unit || '');

        setVal($row.find('[data-materialLargo]'), it.length ?? 0);
        setVal($row.find('[data-materialAncho]'), it.width ?? 0);
        setVal($row.find('[data-materialQuantity]'), it.quantity ?? 0);

        // precios (si guardas unit_price/total_price en tabla)
        setVal($row.find('[data-materialPrice]'), it.unit_price ?? 0);
        setVal($row.find('[data-materialTotal]'), it.total_price ?? 0);

        // si manejas los “sin igv” como Price2/Total2, aquí setea si los tienes
        // setVal($row.find('[data-materialPrice2]'), it.unit_price/1.18);
        // setVal($row.find('[data-materialTotal2]'), it.total_price/1.18);

        $('[data-bodyMaterials]').append($row);
    });

    if (window.isEditMode) lockKeywordChange();
}

function hydrateConsumables(items) {
    items.forEach(it => {
        const row = activateTemplate('#template-consumable');
        const $row = $(row);

        setVal($row.find('[data-consumableDescription]'), it.material?.full_name || it.material?.description || '');
        setVal($row.find('[data-consumableId]'), it.material_id);
        setVal($row.find('[data-consumableUnit]'), it.material?.unit || '');

        setVal($row.find('[data-consumableQuantity]'), it.quantity ?? 0);
        setVal($row.find('[data-consumablePrice]'), it.unit_price ?? 0);
        setVal($row.find('[data-consumableTotal]'), it.total_price ?? 0);

        $('[data-bodyConsumable]').append($row);
    });
}

function hydrateElectrics(items) {
    console.log(items);
    items.forEach(it => {
        const row = activateTemplate('#template-electric');
        const $row = $(row);

        setVal($row.find('[data-electricDescription]'), it.material?.full_name || it.material?.description || '');
        setVal($row.find('[data-electricId]'), it.material_id);
        setVal($row.find('[data-electricUnit]'), it.material?.unit_measure.description || '');

        setVal($row.find('[data-electricQuantity]'), it.quantity ?? 0);
        setVal($row.find('[data-electricPrice]'), it.price ?? 0);
        setVal($row.find('[data-electricTotal]'), it.total ?? 0);

        $('[data-bodyElectric]').append($row);
    });
}

function hydrateWorkforces(items) {
    items.forEach(it => {
        const row = activateTemplate('#template-mano');
        const $row = $(row);

        setVal($row.find('[data-manoDescription]'), it.description || '');
        setVal($row.find('[data-manoUnit]'), it.unit || '');
        setVal($row.find('[data-manoQuantity]'), it.quantity ?? 0);
        setVal($row.find('[data-manoPrice]'), it.unit_price ?? 0);
        setVal($row.find('[data-manoTotal]'), it.total_price ?? 0);

        $('[data-bodyMano]').append($row);
    });
}

function hydrateTurnstiles(items) {
    items.forEach(it => {
        const row = activateTemplate('#template-torno');
        const $row = $(row);

        setVal($row.find('[data-tornoDescription]'), it.description || '');
        setVal($row.find('[data-tornoQuantity]'), it.quantity ?? 0);
        setVal($row.find('[data-tornoPrice]'), it.unit_price ?? 0);
        setVal($row.find('[data-tornoTotal]'), it.total_price ?? 0);

        $('[data-bodyTorno]').append($row);
    });
}

function hydrateWorkdays(items) {
    // ojo: en tu tabla se llama quantityPerson/hoursPerPerson/pricePerHour
    items.forEach(it => {
        const row = activateTemplate('#template-dia');
        const $row = $(row);

        setVal($row.find('[data-description]'), it.description || '');
        setVal($row.find('[data-cantidad]'), it.quantityPerson ?? 0);
        setVal($row.find('[data-horas]'), it.hoursPerPerson ?? 0);
        setVal($row.find('[data-precio]'), it.pricePerHour ?? 0);
        setVal($row.find('[data-total]'), it.total_price ?? 0);

        $('[data-bodyDia]').append($row);
    });
}

function lockKeywordChange() {
    $('#keyword-selector').css('pointer-events', 'none');
    $('#keyword-selector .btn-keyword.is-active').css('pointer-events', 'auto'); // solo el activo “sin efecto”
}

function activateKeywordSection(keyword, lockOthers = false) {
    // set hidden
    $('#keyword').val(keyword);

    // ocultar/mostrar secciones
    $('.keyword-section').addClass('d-none');
    $('.keyword-section[data-keyword="' + keyword + '"]').removeClass('d-none');

    // botones visual
    $('.btn-keyword').removeClass('is-active is-inactive');
    const $activeBtn = $('.btn-keyword[data-keyword="' + keyword + '"]');
    $activeBtn.addClass('is-active').find('i.fas').removeClass('d-none');

    $('.btn-keyword').not($activeBtn).addClass('is-inactive').find('i.fas').addClass('d-none');

    if (lockOthers) {
        // bloquear botones y secciones no activas
        $('.btn-keyword').not($activeBtn).prop('disabled', true).css('pointer-events', 'none');
        $('.keyword-section').not('[data-keyword="' + keyword + '"]')
            .find('input, select, textarea, button')
            .prop('disabled', true);
    }
}

function num(v) {
    const n = parseFloat(String(v ?? '').replace(',', '.'));
    return isNaN(n) ? 0 : n;
}
function str(v) {
    return String(v ?? '').trim();
}

function collectMaterials() {
    const rows = $('[data-bodyMaterials] .row');
    const items = [];

    rows.each(function () {
        const $row = $(this);

        const material_id = num($row.find('[data-materialId]').val());
        if (!material_id) return; // salta rows vacíos

        const quantity = num($row.find('[data-materialQuantity]').val());
        const length   = num($row.find('[data-materialLargo]').val());
        const width    = num($row.find('[data-materialAncho]').val());

        // precios / totales (tú ya los renderizas)
        const unit_price  = num($row.find('[data-materialPrice]').val());
        const total_price = num($row.find('[data-materialTotal]').val());

        // en tu modelo guardabas percentage = quantity
        const percentage = quantity;

        items.push({ material_id, quantity, length, width, percentage, unit_price, total_price });
    });

    return items;
}

function collectConsumables() {
    const rows = $('[data-bodyConsumable] .row');
    const items = [];

    rows.each(function () {
        const $row = $(this);

        const material_id = num($row.find('[data-consumableId]').val());
        if (!material_id) return;

        const quantity = num($row.find('[data-consumableQuantity]').val());

        // Si NO tienes permiso, estos inputs están ocultos.
        // Usa fallback: si no hay data-consumablePrice, usa data-consumablePrice2.
        const price = num($row.find('[data-consumablePrice]').val()) || num($row.find('[data-consumablePrice2]').val());
        const total = num($row.find('[data-consumableTotal]').val()) || num($row.find('[data-consumableTotal2]').val());

        items.push({ material_id, quantity, price, total });
    });

    return items;
}

function collectElectrics() {
    const rows = $('[data-bodyElectric] .row');
    const items = [];

    rows.each(function () {
        const $row = $(this);

        const material_id = num($row.find('[data-electricId]').val());
        if (!material_id) return;

        const quantity = num($row.find('[data-electricQuantity]').val());
        const price    = num($row.find('[data-electricPrice]').val());
        const total    = num($row.find('[data-electricTotal]').val());

        items.push({ material_id, quantity, price, total });
    });

    return items;
}

function collectWorkforces() {
    const items = [];

    $('[data-bodyMano] .row').each(function () {
        const $row = $(this);

        const description = str($row.find('[data-manoDescription]').val());
        const unit        = str($row.find('[data-manoUnit]').val());
        const quantity    = num($row.find('[data-manoQuantity]').val());
        const unit_price  = num($row.find('[data-manoPrice]').val());
        const total_price = num($row.find('[data-manoTotal]').val());

        if (!description) return;

        items.push({
            description,
            unit,
            quantity,
            unit_price,
            total_price
        });
    });

    return items;
}

function collectTurnstiles() {
    const items = [];

    $('[data-bodyTorno] .row').each(function () {
        const $row = $(this);

        const description = str($row.find('[data-tornoDescription]').val());
        const quantity    = num($row.find('[data-tornoQuantity]').val());
        const unit_price  = num($row.find('[data-tornoPrice]').val());
        const total_price = num($row.find('[data-tornoTotal]').val());

        if (!description) return;

        items.push({
            description,
            quantity,
            unit_price,
            total_price
        });
    });

    return items;
}

function getEquipmentName() {
    return str($('[data-nameequipment]').val());
}

function collectWorkdays() {
    const items = [];

    $('[data-bodyDia] .row').each(function () {
        const $row = $(this);

        const description    = str($row.find('[data-description]').val());
        const quantityPerson = num($row.find('[data-cantidad]').val());
        const hoursPerPerson = num($row.find('[data-horas]').val());
        const pricePerHour   = num($row.find('[data-precio]').val());
        const total_price    = num($row.find('[data-total]').val());

        if (!description) return;

        items.push({
            description,
            quantityPerson,
            hoursPerPerson,
            pricePerHour,
            total_price
        });
    });

    return items;
}

function buildPayloadByKeyword(keyword) {
    var category_equipment_id = $('#category_equipment_id').val();
    const default_equipment_id = $('#default_equipment_id').val();
    const payload = {
        keyword: keyword,
        description: getEquipmentName(),
        category_equipment_id: category_equipment_id,
        default_equipment_id: default_equipment_id
    };

    switch (keyword) {
        case 'materials':
            payload.materials = collectMaterials();       // (tu función existente)
            break;

        case 'consumibles':
            payload.consumables = collectConsumables();   // (tu función existente)
            break;

        case 'electrics':
            payload.electrics = collectElectrics();       // (tu función existente)
            break;

        case 'servicios_varios':
            payload.workforces = collectWorkforces();
            payload.turnstiles = collectTurnstiles();
            break;

        case 'dias_trabajo':
            payload.workdays = collectWorkdays();
            break;
    }

    return payload;
}

function saveDefaultEquipmentSection() {
    const keyword = $('#keyword').val();
    const name = getEquipmentName();

    if (!name) {
        toastr.error('Debe ingresar el Nombre del Equipo', 'Error');
        return;
    }

    $.confirm({
        title: '¿Estás seguro de guardar los cambios?',
        content: 'Recuerda que solo se guardarán los cambios de la sección actual.',
        type: 'blue',
        buttons: {
            cancelar: {
                text: 'Cancelar',
                btnClass: 'btn-secondary'
            },
            guardar: {
                text: 'Guardar',
                btnClass: 'btn-primary',
                action: function () {

                    const payload = buildPayloadByKeyword(keyword);

                    $.ajax({
                        url: window.routeStoreDefaultEquipment, // <-- define esta ruta en blade
                        method: 'POST',
                        data: JSON.stringify(payload),
                        contentType: 'application/json; charset=utf-8',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (res) {
                            toastr.success(res.message || 'Guardado correctamente');
                            location.reload();
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Error al guardar';
                            toastr.error(msg, 'Error');
                        }
                    });
                }
            }
        }
    });
}

function setKeyword(key) {
    $('#keyword').val(key);

    // botones: activo/inactivo
    $('.btn-keyword')
        .removeClass('is-active')
        .addClass('is-inactive')
        .find('.fa-check').addClass('d-none');

    $('.btn-keyword[data-keyword="'+key+'"]')
        .addClass('is-active')
        .removeClass('is-inactive')
        .find('.fa-check').removeClass('d-none');

    // secciones
    $('.keyword-section').addClass('d-none');
    $('.keyword-section[data-keyword="'+key+'"]').removeClass('d-none');
}

function imageDelete() {
    console.log('click');
    var element = $(this).parent().parent().parent().parent();
    $(this).tooltip('hide');
    element.remove();
}

function addImage() {

    renderTemplateImage();
}

function renderTemplateImage() {
    var clone = activateTemplate('#template-image');

    $('#body-images').append(clone);
}

function previewFile(input) {
    var preview = input.parentElement.parentElement.nextElementSibling;
    var file = input.files[0];
    var reader = new FileReader();

    reader.onloadend = function() {
        preview.src = reader.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
}

/*var materialsBH = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('full_name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: routeSearchMaterials + '?q=%QUERY',
        wildcard: '%QUERY',
        transform: function (response) {
            return response; // array plano
        }
    }
});*/

var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
        var matches, substringRegex;

        // an array that will be populated with substring matches
        matches = [];

        // regex used to determine if a string contains the substring `q`
        substrRegex = new RegExp(q, 'i');

        // iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(strs, function(i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });

        cb(matches);
    };
};

function deleteEquipment() {
    var button = $(this);
    $.confirm({
        icon: 'fas fa-frown',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: 'Eliminar Equipo',
        content: '¿Está seguro de eliminar este equipo?',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    var equipmentId = parseInt(button.data('deleteequipment'));
                    console.log(equipmentId);
                    var equipmentDeleted = $equipments.find(equipment => equipment.id === equipmentId);
                    console.log(equipmentDeleted);

                    $equipments = $equipments.filter(equipment => equipment.id !== equipmentId);
                    button.parent().parent().parent().parent().remove();
                    if ( $equipments.length === 0 ) {
                        renderTemplateEquipment();
                        $equipmentStatus = false;
                    }

                    var totalEquipmentU = 0;
                    var totalEquipmentL = 0;
                    var totalEquipmentR = 0;
                    var totalEquipmentUtility = 0;

                    totalEquipmentU = equipmentDeleted.total*((equipmentDeleted.utility/100)+1);
                    totalEquipmentL = totalEquipmentU*((equipmentDeleted.letter/100)+1);
                    totalEquipmentR = totalEquipmentL*((equipmentDeleted.rent/100)+1);
                    totalEquipmentUtility = totalEquipmentR.toFixed(2);

                    $total = parseFloat($total) - parseFloat(equipmentDeleted.total);
                    $totalUtility = parseFloat($totalUtility) - parseFloat(totalEquipmentUtility);

                    //$total = parseFloat($total) - parseFloat(equipmentDeleted.total);
                    //$('#subtotal').html('USD '+($total/1.18).toFixed(2));
                    //$('#total').html('USD '+ $total.toFixed(2));
                    $('#subtotal').html('USD '+ ($total/1.18).toFixed(2));
                    $('#total').html('USD '+$total.toFixed(2));
                    $('#subtotal_utility').html('USD '+ ($totalUtility/1.18).toFixed(2));
                    $('#total_utility').html('USD '+$totalUtility.toFixed(2));

                    //calculateMargen2($('#utility').val());
                    //calculateLetter2($('#letter').val());
                    //calculateRent2($('#taxes').val());

                    $.alert("Equipo eliminado!");

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Eliminación cancelada.");
                },
            },
        },
    });

}

function saveEquipment() {
    var button = $(this);
    console.log(button);
    $.confirm({
        icon: 'fas fa-smile',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'orange',
        title: 'Guardar cambios',
        content: '¿Está seguro de guardar los cambios en este equipo?',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    var equipmentId = parseInt(button.data('saveequipment'));
                    console.log(equipmentId);
                    var equipmentDeleted = $equipments.find(equipment => equipment.id === equipmentId);
                    console.log(equipmentDeleted);

                    $equipments = $equipments.filter(equipment => equipment.id !== equipmentId);
                    //button.parent().parent().parent().parent().remove();
                    /*if ( $equipments.length === 0 ) {
                        renderTemplateEquipment();
                        $equipmentStatus = false;
                    }*/
                    var totalEquipmentU = 0;
                    var totalEquipmentL = 0;
                    var totalEquipmentR = 0;
                    var totalEquipmentUtility = 0;
                    // TODO: Capturar los materiales y recorrerlos y agregar al anterior
                    // TODO: En data-delete (material) debe estar el equipo tambien
                    totalEquipmentU = equipmentDeleted.total*((equipmentDeleted.utility/100)+1);
                    totalEquipmentL = totalEquipmentU*((equipmentDeleted.letter/100)+1);
                    totalEquipmentR = totalEquipmentL*((equipmentDeleted.rent/100)+1);
                    totalEquipmentUtility = totalEquipmentR.toFixed(2);

                    $total = parseFloat($total) - parseFloat(equipmentDeleted.total);
                    $totalUtility = parseFloat($totalUtility) - parseFloat(totalEquipmentUtility);

                    // $('#subtotal').html('USD '+($total/1.18).toFixed(2));
                    // $('#total').html('USD '+ $total.toFixed(2));

                    $('#subtotal').html('USD '+ ($total/1.18).toFixed(2));
                    $('#total').html('USD '+$total.toFixed(2));
                    $('#subtotal_utility').html('USD '+ ($totalUtility/1.18).toFixed(2));
                    $('#total_utility').html('USD '+$totalUtility.toFixed(2));
                    //calculateMargen2($('#utility').val());
                    //calculateLetter2($('#letter').val());
                    //calculateRent2($('#taxes').val());

                    //TODO: Otra vez guardamos el equipo

                    var quantity =  button.parent().parent().next().children().children().children().next().val();

                    var nameequipment =         button.parent().parent().next().children().children().next().children().next().val();

                    var largeequipment =        button.parent().parent().next().children().children().next().next().children().next().val();
                    var widthequipment =        button.parent().parent().next().children().children().next().next().next().children().next().val();
                    var highequipment =         button.parent().parent().next().children().children().next().next().next().next().children().next().val();
                    var categoryequipment =     button.parent().parent().next().children().children().next().next().next().next().next().children().next().val();
                    var categoryequipmentid =   button.parent().parent().next().children().children().next().next().next().next().next().next().next().next().next().children().next().val();
                    

                    var utility =   button.parent().parent().next().children().children().children().next().next().val();
                    var rent =      button.parent().parent().next().children().children().children().next().next().next().val();
                    var letter =    button.parent().parent().next().children().children().children().next().next().next().next().val();

                    var description = button.parent().parent().next().children().children().next().next().next().next().next().next().children().next().val();
                    var detail = button.parent().parent().next().children().children().next().next().next().next().next().next().next().next().children().next().val();
                    var materials = button.parent().parent().next().children().next().children().next().children().next().next().next();
                    var consumables = button.parent().parent().next().children().next().next().children().next().children().next().next();
                    var electrics = button.parent().parent().next().children().next().next().next().children().next().children().next().next();
                    var workforces = button.parent().parent().next().children().next().next().next().children().next().children().next().next();
                    var tornos = button.parent().parent().next().children().next().next().next().children().next().children().next().next().next().next().children().next().children().next().next();
                    var dias = button.parent().parent().next().children().next().next().next().next().children().next().children().next().next().next();

                    var materialsDescription = [];
                    var materialsUnit = [];
                    var materialsLargo = [];
                    var materialsAncho = [];
                    var materialsQuantity = [];
                    var materialsPrice = [];
                    var materialsTotal = [];

                    materials.each(function(e){
                        $(this).find('[data-materialDescription]').each(function(){
                            materialsDescription.push($(this).val());
                        });
                        $(this).find('[data-materialUnit]').each(function(){
                            materialsUnit.push($(this).val());
                        });
                        $(this).find('[data-materialLargo]').each(function(){
                            materialsLargo.push($(this).val());
                        });
                        $(this).find('[data-materialAncho]').each(function(){
                            materialsAncho.push($(this).val());
                        });
                        $(this).find('[data-materialQuantity]').each(function(){
                            materialsQuantity.push($(this).val());
                        });
                        $(this).find('[data-materialPrice]').each(function(){
                            materialsPrice.push($(this).val());
                        });
                        $(this).find('[data-materialTotal]').each(function(){
                            materialsTotal.push($(this).val());
                        });
                    });

                    var materialsArray = [];

                    for (let i = 0; i < materialsDescription.length; i++) {
                        var materialSelected = $materials.find( mat=>mat.full_name === materialsDescription[i] );
                        materialsArray.push({'id':materialSelected.id,'material':materialSelected, 'description':materialsDescription[i], 'unit':materialsUnit[i], 'length':materialsLargo[i], 'width':materialsAncho[i], 'quantity':materialsQuantity[i], 'price': materialsPrice[i], 'total': materialsTotal[i]});
                    }

                    var diasDescription = [];
                    var diasCantidad = [];
                    var diasHoras = [];
                    var diasPrecio = [];
                    var diasTotal = [];

                    dias.each(function(e){
                        $(this).find('[data-description]').each(function(){
                            diasDescription.push($(this).val());
                        });
                        $(this).find('[data-cantidad]').each(function(){
                            diasCantidad.push($(this).val());
                        });
                        $(this).find('[data-horas]').each(function(){
                            diasHoras.push($(this).val());
                        });
                        $(this).find('[data-precio]').each(function(){
                            diasPrecio.push($(this).val());
                        });
                        $(this).find('[data-total]').each(function(){
                            diasTotal.push($(this).val());
                        });
                    });

                    var diasArray = [];

                    for (let i = 0; i < diasCantidad.length; i++) {
                        diasArray.push({'description':diasDescription[i], 'quantity':diasCantidad[i], 'hours':diasHoras[i], 'price':diasPrecio[i], 'total': diasTotal[i]});
                    }

                    var consumablesDescription = [];
                    var consumablesIds = [];
                    var consumablesUnit = [];
                    var consumablesQuantity = [];
                    var consumablesPrice = [];
                    var consumablesTotal = [];

                    consumables.each(function(e){
                        $(this).find('[data-consumableDescription]').each(function(){
                            consumablesDescription.push($(this).val());
                        });
                        $(this).find('[data-consumableid]').each(function(){
                            console.log($(this).attr('data-consumableid'));
                            consumablesIds.push($(this).attr('data-consumableid'));
                        });
                        $(this).find('[data-consumableUnit]').each(function(){
                            consumablesUnit.push($(this).val());
                        });
                        $(this).find('[data-consumableQuantity]').each(function(){
                            consumablesQuantity.push($(this).val());
                        });
                        $(this).find('[data-consumablePrice]').each(function(){
                            consumablesPrice.push($(this).val());
                        });
                        $(this).find('[data-consumableTotal]').each(function(){
                            consumablesTotal.push($(this).val());
                        });
                    });

                    var consumablesArray = [];

                    for (let i = 0; i < consumablesDescription.length; i++) {
                        consumablesArray.push({'id':consumablesIds[i], 'description':consumablesDescription[i], 'unit':consumablesUnit[i], 'quantity':consumablesQuantity[i], 'price': consumablesPrice[i], 'total': consumablesTotal[i]});
                    }

                    // TODO: seccion electricos
                    var electricsDescription = [];
                    var electricsIds = [];
                    var electricsUnit = [];
                    var electricsQuantity = [];
                    var electricsPrice = [];
                    var electricsTotal = [];

                    electrics.each(function(e){
                        $(this).find('[data-electricDescription]').each(function(){
                            electricsDescription.push($(this).val());
                        });
                        $(this).find('[data-electricid]').each(function(){
                            //console.log($(this).attr('data-consumableid'));
                            electricsIds.push($(this).attr('data-electricid'));
                        });
                        $(this).find('[data-electricUnit]').each(function(){
                            electricsUnit.push($(this).val());
                        });
                        $(this).find('[data-electricQuantity]').each(function(){
                            electricsQuantity.push($(this).val());
                        });
                        $(this).find('[data-electricPrice]').each(function(){
                            electricsPrice.push($(this).val());
                        });
                        $(this).find('[data-electricTotal]').each(function(){
                            electricsTotal.push($(this).val());
                        });
                    });

                    var electricsArray = [];

                    for (let i = 0; i < electricsDescription.length; i++) {
                        electricsArray.push({'id':electricsIds[i], 'description':electricsDescription[i], 'unit':electricsUnit[i], 'quantity':electricsQuantity[i], 'price': electricsPrice[i], 'total': electricsTotal[i]});
                    }

                    var manosDescription = [];
                    var manosIds = [];
                    var manosUnit = [];
                    var manosQuantity = [];
                    var manosPrice = [];
                    var manosTotal = [];

                    workforces.each(function(e){
                        $(this).find('[data-manoDescription]').each(function(){
                            manosDescription.push($(this).val());
                        });
                        $(this).find('[data-manoId]').each(function(){
                            manosIds.push($(this).val());
                        });
                        $(this).find('[data-manoUnit]').each(function(){
                            manosUnit.push($(this).val());
                        });
                        $(this).find('[data-manoQuantity]').each(function(){
                            manosQuantity.push($(this).val());
                        });
                        $(this).find('[data-manoPrice]').each(function(){
                            manosPrice.push($(this).val());
                        });
                        $(this).find('[data-manoTotal]').each(function(){
                            manosTotal.push($(this).val());
                        });
                    });

                    var manosArray = [];

                    for (let i = 0; i < manosDescription.length; i++) {
                        manosArray.push({'id':manosIds[i], 'description':manosDescription[i], 'unit':manosUnit[i], 'quantity':manosQuantity[i], 'price':manosPrice[i], 'total': manosTotal[i]});
                    }

                    var tornosDescription = [];
                    var tornosQuantity = [];
                    var tornosPrice = [];
                    var tornosTotal = [];

                    tornos.each(function(e){
                        $(this).find('[data-tornoDescription]').each(function(){
                            tornosDescription.push($(this).val());
                        });
                        $(this).find('[data-tornoQuantity]').each(function(){
                            tornosQuantity.push($(this).val());
                        });
                        $(this).find('[data-tornoPrice]').each(function(){
                            tornosPrice.push($(this).val());
                        });
                        $(this).find('[data-tornoTotal]').each(function(){
                            tornosTotal.push($(this).val());
                        });
                    });

                    var tornosArray = [];

                    for (let i = 0; i < tornosDescription.length; i++) {
                        tornosArray.push({'description':tornosDescription[i], 'quantity':tornosQuantity[i], 'price':tornosPrice[i], 'total': tornosTotal[i]});
                    }

                    var totalEquipment = 0;
                    var totalEquipmentU = 0;
                    var totalEquipmentL = 0;
                    var totalEquipmentR = 0;
                    var totalEquipmentUtility = 0;
                    var totalDias = 0;
                    for (let i = 0; i < materialsTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(materialsTotal[i]);
                    }
                    for (let i = 0; i < tornosTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(tornosTotal[i]);
                    }
                    for (let i = 0; i < manosTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(manosTotal[i]);
                    }
                    for (let i = 0; i < consumablesTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(consumablesTotal[i]);
                    }
                    for (let i = 0; i < electricsTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(electricsTotal[i]);
                    }
                    for (let i = 0; i < diasTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(diasTotal[i]);
                    }
                    totalEquipment = parseFloat((totalEquipment * quantity)/*+totalDias*/).toFixed(2);
                    totalEquipmentU = totalEquipment*((utility/100)+1);
                    totalEquipmentL = totalEquipmentU*((letter/100)+1);
                    totalEquipmentR = totalEquipmentL*((rent/100)+1);
                    totalEquipmentUtility = totalEquipmentR.toFixed(2);

                    $total = parseFloat($total) + parseFloat(totalEquipment);
                    $totalUtility = parseFloat($totalUtility) + parseFloat(totalEquipmentUtility);

                    $('#subtotal').html('USD '+ ($total/1.18).toFixed(2));
                    $('#total').html('USD '+$total.toFixed(2));
                    $('#subtotal_utility').html('USD '+ ($totalUtility/1.18).toFixed(2));
                    $('#total_utility').html('USD '+$totalUtility.toFixed(2));


                    //calculateMargen2($('#utility').val());
                    //calculateLetter2($('#letter').val());
                    //calculateRent2($('#taxes').val());

                    button.attr('data-saveEquipment', $equipments.length);
                    button.next().attr('data-deleteEquipment', $equipments.length);
                    $equipments.push({'id':equipmentId, 'nameequipment':nameequipment,'largeequipment':largeequipment,'widthequipment':widthequipment,'highequipment':highequipment,'categoryequipment':categoryequipment,'categoryequipmentid':categoryequipmentid,'quantity':quantity, 'utility':utility, 'rent':rent, 'letter':letter, 'total':totalEquipment, 'description':description, 'detail':detail, 'materials': materialsArray, 'consumables':consumablesArray, 'electrics':electricsArray, 'workforces':manosArray, 'tornos':tornosArray, 'dias':diasArray});
                    updateTableTotalsEquipment(button, {'id':equipmentId, 'nameequipment':nameequipment,'largeequipment':largeequipment,'widthequipment':widthequipment,'highequipment':highequipment,'categoryequipment':categoryequipment,'categoryequipmentid':categoryequipmentid,'quantity':quantity, 'utility':utility, 'rent':rent, 'letter':letter, 'total':totalEquipment, 'description':description, 'detail':detail, 'materials': materialsArray, 'consumables':consumablesArray, 'electrics':electricsArray, 'workforces':manosArray, 'tornos':tornosArray, 'dias':diasArray});
                    var card = button.parent().parent().parent();
                    card.removeClass('card-gray-dark');
                    card.addClass('card-success');

                    $items = [];

                    $.alert("Equipo guardado!");

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Modificación cancelada.");
                },
            },
        },
    });

}

function deleteConsumable() {
    //console.log($(this).parent().parent().parent());
    var card = $(this).parent().parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    $(this).parent().parent().remove();
}

function deleteElectric() {
    //console.log($(this).parent().parent().parent());
    var card = $(this).parent().parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    $(this).parent().parent().remove();
}

function deleteMano() {
    //console.log($(this).parent().parent().parent());
    var card = $(this).parent().parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    $(this).parent().parent().remove();
}

function deleteDia() {
    //console.log($(this).parent().parent().parent());
    var card = $(this).parent().parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    $(this).parent().parent().remove();
}

function deleteTorno() {
    //console.log($(this).parent().parent().parent());
    var card = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    $(this).parent().parent().remove();
}

function addConsumable() {
    const $card = $(this).closest('.card');
    const $select = $card.find('.consumable_search');
    const $qty = $card.find('[data-cantidad]');

    const cantidad = parseFloat(($qty.val() || '0').toString());
    if (!cantidad || cantidad <= 0) {
        toastr.error('Debe ingresar una cantidad', 'Error', toastOpts());
        return;
    }

    const selected = $select.select2('data')[0]; // {id,text,raw}
    if (!selected || !selected.id) {
        toastr.error('Debe seleccionar un consumible', 'Error', toastOpts());
        return;
    }

    const consumable = selected.raw || null;
    if (!consumable) {
        toastr.error('No se pudo obtener el consumible seleccionado', 'Error', toastOpts());
        return;
    }

    const $render = $card.find('[data-bodyConsumable]'); // donde se dibuja
    // ✅ verificar duplicado leyendo los hidden ya renderizados
    const exists = $render.find('[data-consumableId]').filter(function () {
        return parseInt($(this).val() || $(this).attr('value') || 0) === parseInt(consumable.id);
    }).length > 0;

    if (exists) {
        $qty.val(0);
        $select.val(null).trigger('change');
        toastr.error('Este material ya está seleccionado', 'Error', toastOpts());
        return;
    }

    // ✅ render
    renderTemplateConsumable($render, consumable, cantidad);

    // ✅ reset
    $qty.val(0);
    $select.val(null).trigger('change');
}

function toastError(msg){
    toastr.error(msg, 'Error', {
        closeButton:true, progressBar:true, positionClass:"toast-top-right",
        timeOut:"2000", showDuration:"300", hideDuration:"1000"
    });
}

function addElectric() {
    var $btn = $(this);
    var $row = $btn.closest('.row'); // fila del select + cantidad + botón

    var qty = parseFloat($row.find('[data-cantidad]').val() || 0);
    if (!qty) return toastError('Debe ingresar una cantidad');

    var sel = $row.find('[data-electric]').select2('data')[0];
    if (!sel || !sel.id) return toastError('Debe seleccionar un material eléctrico');

    var electric = sel.raw || { id: sel.id, full_name: sel.text, text: sel.text };

    // contenedor donde se renderiza
    var $render = $btn.closest('.card-body').find('[data-bodyElectric]');

    // validar duplicado leyendo el hidden del template renderizado
    var exists = $render.find('[data-electricId]').filter(function(){
        return String($(this).val()) === String(electric.id);
    }).length > 0;

    if (exists) {
        $row.find('[data-cantidad]').val(0);
        $row.find('[data-electric]').val(null).trigger('change');
        return toastError('Este material ya está seleccionado');
    }

    // limpiar inputs
    $row.find('[data-cantidad]').val(0);
    $row.find('[data-electric]').val(null).trigger('change');

    // render
    renderTemplateElectric($render, electric, qty);
}

function addMano() {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        var precio = $(this).parent().prev().children().children().next().val();
        var cantidad = $(this).parent().prev().prev().children().children().next().val();
        var unidad = $(this).parent().prev().prev().prev().children().children().next().next().text();
        var unidadID = $(this).parent().prev().prev().prev().children().children().next().val();
        var descripcion = $(this).parent().prev().prev().prev().prev().children().children().next().val();

        if ( descripcion === '' )
        {
            toastr.error('Escriba una descripción adecuada.', 'Error',
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
        if ( unidadID === '' || parseInt(unidadID) === 0 )
        {
            toastr.error('Seleccione una unidad válida.', 'Error',
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
        if ( cantidad === '' || parseFloat(cantidad) === 0 )
        {
            toastr.error('Agregue una cantidad válida.', 'Error',
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
        if ( precio === '' || parseFloat(precio) === 0 )
        {
            toastr.error('Agregue un precio válido.', 'Error',
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

        $(this).parent().prev().prev().prev().prev().children().children().next().val('');
        $(".unitMeasure").val('').trigger('change');
        $(this).parent().prev().prev().children().children().next().val(0);
        $(this).parent().prev().children().children().next().val(0);
        //console.log(descripcion);
        var render = $(this).parent().parent().next().next().next();
        renderTemplateMano(render, descripcion, unidad, cantidad, precio);
    } else {
        var precio2 = 0;
        var cantidad2 = $(this).parent().prev().children().children().next().val();
        var unidad2 = $(this).parent().prev().prev().children().children().next().next().text();
        var unidadID2 = $(this).parent().prev().prev().children().children().next().val();
        var descripcion2 = $(this).parent().prev().prev().prev().children().children().next().val();

        if ( descripcion2 === '' )
        {
            toastr.error('Escriba una descripción adecuada.', 'Error',
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
        if ( unidadID2 === '' || parseInt(unidadID2) === 0 )
        {
            toastr.error('Seleccione una unidad válida.', 'Error',
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
        if ( cantidad2 === '' || parseFloat(cantidad2) === 0 )
        {
            toastr.error('Agregue una cantidad válida.', 'Error',
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


        $(this).parent().prev().prev().prev().children().children().next().val('');
        $(".unitMeasure").val('').trigger('change');
        $(this).parent().prev().children().children().next().val(0);
        $(this).parent().children().children().next().val(0);
        //console.log(descripcion);
        var render2 = $(this).parent().parent().next().next().next();
        console.log(render2);
        renderTemplateMano(render2, descripcion2, unidad2, cantidad2, precio2);
    }

}

function addDia() {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        var pricePerHour = $(this).parent().prev().children().children().next().val();
        var hoursPerPerson = $(this).parent().prev().prev().children().children().next().val();
        var quantityPerson = $(this).parent().prev().prev().prev().children().children().next().val();
        var description = $(this).parent().prev().prev().prev().prev().children().children().next().val();

        if ( quantityPerson === '' || parseFloat(quantityPerson) === 0 )
        {
            toastr.error('Ingrese un valor correcto.', 'Error',
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
        if ( hoursPerPerson === '' || parseFloat(hoursPerPerson) === 0 )
        {
            toastr.error('Ingrese un valor válido.', 'Error',
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
        if ( pricePerHour === '' || parseFloat(pricePerHour) === 0 )
        {
            toastr.error('Ingrese un precio válido.', 'Error',
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
        if ( description === '' )
        {
            toastr.error('Ingrese una descripción correcta.', 'Error',
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

        $(this).parent().prev().children().children().next().val(0);
        $(this).parent().prev().prev().children().children().next().val(0);
        $(this).parent().prev().prev().prev().children().children().next().next().val(0);
        $(this).parent().prev().prev().prev().prev().children().children().next().next().val('');

        //console.log(descripcion);
        var render = $(this).parent().parent().next().next().next();
        var total = parseFloat(pricePerHour)*parseFloat(hoursPerPerson)*parseFloat(quantityPerson);
        renderTemplateDia(render, description, pricePerHour, hoursPerPerson, quantityPerson, total.toFixed(2));
    } else {
        var pricePerHour2 = 0;
        var hoursPerPerson2 = $(this).parent().prev().children().children().next().val();
        var quantityPerson2 = $(this).parent().prev().prev().children().children().next().val();
        var description2 = $(this).parent().prev().prev().prev().children().children().next().val();

        if ( quantityPerson2 === '' || parseFloat(quantityPerson2) === 0 )
        {
            toastr.error('Ingrese un valor correcto.', 'Error',
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
        if ( hoursPerPerson2 === '' || parseFloat(hoursPerPerson2) === 0 )
        {
            toastr.error('Ingrese un valor válido.', 'Error',
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
        if ( description2 === '' )
        {
            toastr.error('Ingrese una descripción correcta.', 'Error',
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

        //$(this).parent().prev().children().children().next().val(0);
        $(this).parent().prev().prev().prev().children().children().next().val('');
        $(this).parent().prev().prev().children().children().next().val(0);
        $(this).parent().prev().children().children().next().val(0);
        //console.log(descripcion);
        var render2 = $(this).parent().parent().next().next().next();
        console.log(render2);
        var total2 = 0;
        renderTemplateDia(render2, description2, pricePerHour2, hoursPerPerson2, quantityPerson2, total2);
    }

}

function addTorno() {

    var precio = $(this).parent().prev().children().children().next().val();
    var cantidad = $(this).parent().prev().prev().children().children().next().val();
    var descripcion = $(this).parent().prev().prev().prev().children().children().next().val();

    if ( descripcion === '' )
    {
        toastr.error('Escriba una descripción adecuada.', 'Error',
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
    if ( cantidad === '' || parseFloat(cantidad) === 0 )
    {
        toastr.error('Agregue una cantidad válida.', 'Error',
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
    if ( precio === '' || parseFloat(precio) === 0 )
    {
        toastr.error('Agregue un precio válido.', 'Error',
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

    $(this).parent().prev().prev().prev().children().children().next().val('');
    $(this).parent().prev().prev().children().children().next().val(0);
    $(this).parent().prev().children().children().next().val(0);
    //console.log(descripcion);
    var render = $(this).parent().parent().next().next();
    renderTemplateTorno(render, descripcion, cantidad, precio);

}

function confirmEquipment() {
    var button = $(this);
    $.confirm({
        icon: 'fas fa-smile',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: 'Confirmar Equipo',
        content: 'Debe confirmar para almacenar el equipo en memoria',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    //var cantidad = button.parent().parent().next().children().children().children().next();
                    //console.log($(this));
                    /*$equipmentStatus = true;*/
                    // Quitamos el boton
                    button.hide();
                    //$items.push({ 'id': $items.length+1, 'material': $material, 'material_quantity': material_quantity, 'material_price':total});
                    //console.log(button);
                    button.next().show();
                    button.next().next().show();

                    var quantity = button.parent().parent().next().children().children().children().next().val();


                    var nameequipment =         button.parent().parent().next().children().children().next().children().next().val();

                    var largeequipment =        button.parent().parent().next().children().children().next().next().children().next().val();
                    var widthequipment =        button.parent().parent().next().children().children().next().next().next().children().next().val();
                    var highequipment =         button.parent().parent().next().children().children().next().next().next().next().children().next().val();
                    var categoryequipment =     button.parent().parent().next().children().children().next().next().next().next().next().children().next().val();
                    var categoryequipmentid =   button.parent().parent().next().children().children().next().next().next().next().next().next().next().next().next().children().next().val();

                    // TODO: Obtencion de los porcentages
                    var utility = button.parent().parent().next().children().children().children().next().next().val();
                    var rent = button.parent().parent().next().children().children().children().next().next().next().val();
                    var letter = button.parent().parent().next().children().children().children().next().next().next().next().val();

                    var description = button.parent().parent().next().children().children().next().next().next().next().next().next().children().next().val();
                    var detail = button.parent().parent().next().children().children().next().next().next().next().next().next().next().next().children().next().val();
                    var materials = button.parent().parent().next().children().next().children().next().children().next().next().next();
                    var consumables = button.parent().parent().next().children().next().next().children().next().children().next().next();
                    var electrics = button.parent().parent().next().children().next().next().next().children().next().children().next().next();
                    var workforces = button.parent().parent().next().children().next().next().next().children().next().children().next().next();
                    var tornos = button.parent().parent().next().children().next().next().next().children().next().children().next().next().next().next().children().next().children().next().next();
                    var dias = button.parent().parent().next().children().next().next().next().next().children().next().children().next().next().next();

                    var materialsDescription = [];
                    var materialsUnit = [];
                    var materialsLargo = [];
                    var materialsAncho = [];
                    var materialsQuantity = [];
                    var materialsPrice = [];
                    var materialsTotal = [];

                    materials.each(function(e){
                        $(this).find('[data-materialDescription]').each(function(){
                            materialsDescription.push($(this).val());
                        });
                        $(this).find('[data-materialUnit]').each(function(){
                            materialsUnit.push($(this).val());
                        });
                        $(this).find('[data-materialLargo]').each(function(){
                            materialsLargo.push($(this).val());
                        });
                        $(this).find('[data-materialAncho]').each(function(){
                            materialsAncho.push($(this).val());
                        });
                        $(this).find('[data-materialQuantity]').each(function(){
                            materialsQuantity.push($(this).val());
                        });
                        $(this).find('[data-materialPrice]').each(function(){
                            materialsPrice.push($(this).val());
                        });
                        $(this).find('[data-materialTotal]').each(function(){
                            materialsTotal.push($(this).val());
                        });
                    });

                    var materialsArray = [];

                    for (let i = 0; i < materialsDescription.length; i++) {
                        var materialSelected = $materials.find( mat=>mat.full_name === materialsDescription[i] );
                        materialsArray.push({'id':materialSelected.id,'material':materialSelected, 'description':materialsDescription[i], 'unit':materialsUnit[i], 'length':materialsLargo[i], 'width':materialsAncho[i], 'quantity':materialsQuantity[i], 'price': materialsPrice[i], 'total': materialsTotal[i]});
                    }

                    var diasDescription = [];
                    var diasCantidad = [];
                    var diasHoras = [];
                    var diasPrecio = [];
                    var diasTotal = [];

                    dias.each(function(e){
                        $(this).find('[data-description]').each(function(){
                            diasDescription.push($(this).val());
                        });
                        $(this).find('[data-cantidad]').each(function(){
                            diasCantidad.push($(this).val());
                        });
                        $(this).find('[data-horas]').each(function(){
                            diasHoras.push($(this).val());
                        });
                        $(this).find('[data-precio]').each(function(){
                            diasPrecio.push($(this).val());
                        });
                        $(this).find('[data-total]').each(function(){
                            diasTotal.push($(this).val());
                        });
                    });

                    var diasArray = [];

                    for (let i = 0; i < diasCantidad.length; i++) {
                        diasArray.push({'description':diasDescription[i], 'quantity':diasCantidad[i], 'hours':diasHoras[i], 'price':diasPrecio[i], 'total': diasTotal[i]});
                    }

                    var consumablesDescription = [];
                    var consumablesIds = [];
                    var consumablesUnit = [];
                    var consumablesQuantity = [];
                    var consumablesPrice = [];
                    var consumablesTotal = [];

                    consumables.each(function(e){
                        $(this).find('[data-consumableDescription]').each(function(){
                            consumablesDescription.push($(this).val());
                        });
                        $(this).find('[data-consumableId]').each(function(){
                            consumablesIds.push($(this).attr('data-consumableid'));
                        });
                        $(this).find('[data-consumableUnit]').each(function(){
                            consumablesUnit.push($(this).val());
                        });
                        $(this).find('[data-consumableQuantity]').each(function(){
                            consumablesQuantity.push($(this).val());
                        });
                        $(this).find('[data-consumablePrice]').each(function(){
                            consumablesPrice.push($(this).val());
                        });
                        $(this).find('[data-consumableTotal]').each(function(){
                            consumablesTotal.push($(this).val());
                        });
                    });

                    var consumablesArray = [];

                    for (let i = 0; i < consumablesDescription.length; i++) {
                        consumablesArray.push({'id':consumablesIds[i], 'description':consumablesDescription[i], 'unit':consumablesUnit[i], 'quantity':consumablesQuantity[i], 'price': consumablesPrice[i], 'total': consumablesTotal[i]});
                    }

                    var electricsDescription = [];
                    var electricsIds = [];
                    var electricsUnit = [];
                    var electricsQuantity = [];
                    var electricsPrice = [];
                    var electricsTotal = [];

                    electrics.each(function(e){
                        $(this).find('[data-electricDescription]').each(function(){
                            electricsDescription.push($(this).val());
                        });
                        $(this).find('[data-electricId]').each(function(){
                            electricsIds.push($(this).attr('data-electricid'));
                        });
                        $(this).find('[data-electricUnit]').each(function(){
                            electricsUnit.push($(this).val());
                        });
                        $(this).find('[data-electricQuantity]').each(function(){
                            electricsQuantity.push($(this).val());
                        });
                        $(this).find('[data-electricPrice]').each(function(){
                            electricsPrice.push($(this).val());
                        });
                        $(this).find('[data-electricTotal]').each(function(){
                            electricsTotal.push($(this).val());
                        });
                    });

                    var electricsArray = [];

                    for (let i = 0; i < electricsDescription.length; i++) {
                        electricsArray.push({'id':electricsIds[i], 'description':electricsDescription[i], 'unit':electricsUnit[i], 'quantity':electricsQuantity[i], 'price': electricsPrice[i], 'total': electricsTotal[i]});
                    }

                    var manosDescription = [];
                    var manosIds = [];
                    var manosUnit = [];
                    var manosQuantity = [];
                    var manosPrice = [];
                    var manosTotal = [];

                    workforces.each(function(e){
                        $(this).find('[data-manoDescription]').each(function(){
                            manosDescription.push($(this).val());
                        });
                        $(this).find('[data-manoId]').each(function(){
                            manosIds.push($(this).val());
                        });
                        $(this).find('[data-manoUnit]').each(function(){
                            manosUnit.push($(this).val());
                        });
                        $(this).find('[data-manoQuantity]').each(function(){
                            manosQuantity.push($(this).val());
                        });
                        $(this).find('[data-manoPrice]').each(function(){
                            manosPrice.push($(this).val());
                        });
                        $(this).find('[data-manoTotal]').each(function(){
                            manosTotal.push($(this).val());
                        });
                    });

                    var manosArray = [];

                    for (let i = 0; i < manosDescription.length; i++) {
                        manosArray.push({'id':manosIds[i], 'description':manosDescription[i], 'unit':manosUnit[i], 'quantity':manosQuantity[i], 'price':manosPrice[i], 'total': manosTotal[i]});
                    }

                    var tornosDescription = [];
                    var tornosQuantity = [];
                    var tornosPrice = [];
                    var tornosTotal = [];

                    tornos.each(function(e){
                        $(this).find('[data-tornoDescription]').each(function(){
                            tornosDescription.push($(this).val());
                        });
                        $(this).find('[data-tornoQuantity]').each(function(){
                            tornosQuantity.push($(this).val());
                        });
                        $(this).find('[data-tornoPrice]').each(function(){
                            tornosPrice.push($(this).val());
                        });
                        $(this).find('[data-tornoTotal]').each(function(){
                            tornosTotal.push($(this).val());
                        });
                    });

                    var tornosArray = [];

                    for (let i = 0; i < tornosDescription.length; i++) {
                        tornosArray.push({'description':tornosDescription[i], 'quantity':tornosQuantity[i], 'price':tornosPrice[i], 'total': tornosTotal[i]});
                    }

                    var totalEquipment = 0;
                    var totalEquipmentU = 0;
                    var totalEquipmentL = 0;
                    var totalEquipmentR = 0;
                    var totalEquipmentUtility = 0;
                    var totalDias = 0;
                    for (let i = 0; i < materialsTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(materialsTotal[i]);
                    }
                    for (let i = 0; i < tornosTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(tornosTotal[i]);
                    }
                    for (let i = 0; i < manosTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(manosTotal[i]);
                    }
                    for (let i = 0; i < consumablesTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(consumablesTotal[i]);
                    }
                    for (let i = 0; i < diasTotal.length; i++) {
                        totalEquipment = parseFloat(totalEquipment) + parseFloat(diasTotal[i]);
                    }
                    totalEquipment = parseFloat((totalEquipment * quantity)/*+totalDias*/).toFixed(2);

                    totalEquipmentU = totalEquipment*((utility/100)+1);
                    totalEquipmentL = totalEquipmentU*((letter/100)+1);
                    totalEquipmentR = totalEquipmentL*((rent/100)+1);
                    totalEquipmentUtility = totalEquipmentR.toFixed(2);

                    $total = parseFloat($total) + parseFloat(totalEquipment);
                    $totalUtility = parseFloat($totalUtility) + parseFloat(totalEquipmentUtility);

                    $('#subtotal').html('USD '+ ($total/1.18).toFixed(2));
                    $('#total').html('USD '+$total.toFixed(2));
                    $('#subtotal_utility').html('USD '+ ($totalUtility/1.18).toFixed(2));
                    $('#total_utility').html('USD '+$totalUtility.toFixed(2));
                    //calculateMargen2($('#utility').val());
                    //calculateLetter2($('#letter').val());
                    //calculateRent2($('#taxes').val());

                    button.next().attr('data-saveEquipment', $equipments.length);
                    button.next().next().attr('data-deleteEquipment', $equipments.length);
                    $equipments.push({'id':$equipments.length, 'nameequipment':nameequipment,'largeequipment':largeequipment,'widthequipment':widthequipment,'highequipment':highequipment,'categoryequipment':categoryequipment,'categoryequipmentid':categoryequipmentid,'quantity':quantity, 'utility':utility, 'rent':rent, 'letter':letter, 'total':totalEquipment, 'description':description, 'detail':detail, 'materials': materialsArray, 'consumables':consumablesArray, 'electrics':electricsArray, 'workforces':manosArray, 'tornos':tornosArray, 'dias':diasArray});
                    updateTableTotalsEquipment(button, {'id':$equipments.length, 'nameequipment':nameequipment,'largeequipment':largeequipment,'widthequipment':widthequipment,'highequipment':highequipment,'categoryequipment':categoryequipment,'categoryequipmentid':categoryequipmentid,'quantity':quantity, 'utility':utility, 'rent':rent, 'letter':letter, 'total':totalEquipment, 'description':description, 'detail':detail, 'materials': materialsArray, 'consumables':consumablesArray, 'electrics':electricsArray, 'workforces':manosArray, 'tornos':tornosArray, 'dias':diasArray});
                    var card = button.parent().parent().parent();
                    card.removeClass('card-gray-dark');
                    card.addClass('card-success');

                    $items = [];
                    $.alert("Equipo confirmado!");

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $equipmentStatus = false;
                    $.alert("Confirmación cancelada.");
                },
            },
        },
    });

}

function updateTableTotalsEquipment(button, data) {
    var quantity = data.quantity;
    var materiales = data.materials;
    var consumibles = data.consumables;
    var electrics = data.electrics;
    var serviciosVarios = data.workforces;
    var serviciosAdicionales = data.tornos;
    var diasTrabajo = data.dias;

    var totalMaterials = 0;

    for (let i = 0; i < materiales.length; i++) {
        totalMaterials += parseFloat(materiales[i].total);
    }

    var totalConsumables = 0;

    for (let j = 0; j < consumibles.length; j++) {
        totalConsumables += parseFloat(consumibles[j].total);
    }

    var totalElectrics = 0;

    for (let e = 0; e < electrics.length; e++) {
        totalElectrics += parseFloat(electrics[e].total);
    }

    var totalWorkforces = 0;

    for (let k = 0; k < serviciosVarios.length; k++) {
        totalWorkforces += parseFloat(serviciosVarios[k].total);
    }

    var totalTornos = 0;

    for (let l = 0; l < serviciosAdicionales.length; l++) {
        totalTornos += parseFloat(serviciosAdicionales[l].total);
    }

    var totalDias = 0;

    for (let m = 0; m < diasTrabajo.length; m++) {
        totalDias += parseFloat(diasTrabajo[m].total);
    }

    var table = button.parent().parent().next().children().next().next().next().next().next().children().next().children();

    var totalMaterialsElement = table.find('[data-total_materials]');
    totalMaterialsElement.html((totalMaterials*quantity).toFixed(2));
    totalMaterialsElement.css('text-align', 'right');

    var totalConsumablesElement = table.find('[data-total_consumables]');
    totalConsumablesElement.html((totalConsumables*quantity).toFixed(2));
    totalConsumablesElement.css('text-align', 'right');

    var totalElectricsElement = table.find('[data-total_electrics]');
    //totalConsumablesElement.html((totalConsumables*quantity).toFixed(2));
    totalElectricsElement.html((totalElectrics).toFixed(2));
    totalElectricsElement.css('text-align', 'right');

    var totalWorkforcesElement = table.find('[data-total_workforces]');
    totalWorkforcesElement.html((totalWorkforces*quantity).toFixed(2));
    totalWorkforcesElement.css('text-align', 'right');

    var totalTornosElement = table.find('[data-total_tornos]');
    totalTornosElement.html((totalTornos*quantity).toFixed(2));
    totalTornosElement.css('text-align', 'right');

    var totalDiasElement = table.find('[data-total_dias]');
    //totalDiasElement.html((totalDias*quantity).toFixed(2));
    totalDiasElement.html((totalDias).toFixed(2));
    totalDiasElement.css('text-align', 'right');
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function calculateMargen(e) {
    var margen = e.value;

    var letter = $('#letter').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#subtotal2').html('USD '+$subtotal);
    $('#subtotal3').html('USD '+$subtotal2);
    $('#total').html('USD '+$subtotal3);

}

function calculateLetter(e) {
    var letter = e.value;

    var margen = $('#utility').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);
    $('#subtotal3').html('USD '+$subtotal2);
    $('#total').html('USD '+$subtotal3);

}

function calculateRent(e) {
    var rent = e.value;

    var margen = $('#utility').val();
    var letter = $('#letter').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#total').html('USD '+$subtotal3);

}

function calculateMargen2(margen) {
    var letter = $('#letter').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#subtotal2').html('USD '+$subtotal);
    $('#subtotal3').html('USD '+$subtotal2);
    $('#total').html('USD '+$subtotal3);

}

function calculateLetter2(letter) {
    var margen = $('#utility').val() ;
    var rent = $('#taxes').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);
    $('#subtotal3').html('USD '+$subtotal2);
    $('#total').html('USD '+$subtotal3);

}

function calculateRent2(rent) {
    var margen = $('#utility').val();
    var letter = $('#letter').val() ;

    $subtotal = ($total * ((parseFloat(margen)/100)+1)).toFixed(2);
    $subtotal2 = ($subtotal * ((parseFloat(letter)/100)+1)).toFixed(2);
    $subtotal3 = ($subtotal2 * ((parseFloat(rent)/100)+1)).toFixed(0);

    $('#total').html('USD '+$subtotal3);
}

function calculateTotalC(e) {
    var cantidad = e.value;
    var precio = e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value;
    // CON IGV
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);
    // SIN IGV
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = ((parseFloat(cantidad)*parseFloat(precio))/1.18).toFixed(2);

}

function calculateTotalE(e) {
    var cantidad = e.value;
    var precio = e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value;
    // CON IGV
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);
    // SIN IGV
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = ((parseFloat(cantidad)*parseFloat(precio))/1.18).toFixed(2);

}

function calculateTotal(e) {
    var cantidad = e.value;
    var precio = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);

}

function calculateTotal2(e) {
    var precio = e.value;
    var cantidad = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(precio)).toFixed(2);

}

function calculateTotalQuatity(e) {
    var cantidad = e.value;
    var hour = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    var price = e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value;

    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(hour)*parseFloat(price)).toFixed(2);

}

function calculateTotalHour(e) {
    var cantidad = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    var hour = e.value;
    var price = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(hour)*parseFloat(price)).toFixed(2);

}

function calculateTotalPrice(e) {
    var cantidad = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;
    var hour = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    var price = e.value;
    console.log(cantidad);
    console.log(hour);
    console.log(price);
    e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = (parseFloat(cantidad)*parseFloat(hour)*parseFloat(price)).toFixed(2);
    console.log(e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value);
}

function addEquipment() {

    //var result = document.querySelectorAll('[data-equip]');
    //console.log(result);
    /*for (var index in result){
        if (result.hasOwnProperty(index)){
            if(result[index].getAttribute('style')!==null){
                //console.log(result[index].getAttribute('style'));
                $equipmentStatus=true;
            }
        }
    }*/
    //var equipmentStat = confirmEquipment.css('display') === 'none';
    //console.log(confirmEquipment);
    /*if ( !$equipmentStatus )
    {
        toastr.error('Confirme el equipo antes de agregar otro.', 'Error',
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
    }*/

    renderTemplateEquipment();
    $('.materialTypeahead').typeahead('destroy');

    $('.materialTypeahead').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
            minLength: 1 /* Specify minimum characters required for showing suggestions */
        },
        {
            limit: 12,
            source: substringMatcher($materialsTypeahead)
        });

    $('.consumable_search').select2({
        placeholder: 'Selecciona un consumible',
        ajax: {
            url: '/dashboard/select/consumables',
            dataType: 'json',
            type: 'GET',
            processResults(data) {
                //console.log(data);
                return {
                    results: $.map(data, function (item) {
                        //console.log(item.full_description);
                        return {
                            text: item.full_description,
                            id: item.id,
                        }
                    })
                }
            }
        }
    });
    //$equipmentStatus = false;

    $('.electric_search').select2({
        placeholder: 'Selecciona un material',
        ajax: {
            url: '/dashboard/select/consumables',
            dataType: 'json',
            type: 'GET',
            processResults(data) {
                //console.log(data);
                return {
                    results: $.map(data, function (item) {
                        //console.log(item.full_description);
                        return {
                            text: item.full_description,
                            id: item.id,
                        }
                    })
                }
            }
        }
    });

    $('.textarea_edit').summernote({
        lang: 'es-ES',
        placeholder: 'Ingrese los detalles',
        tabsize: 2,
        height: 120,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview', 'help']]
        ]
    });
}

function deleteItem() {
    //console.log($(this).parent().parent().parent());
    var card = $(this).parent().parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');

    $(this).parent().parent().remove();
    var itemId = $(this).data('delete');
    //$items = $items.filter(item => item.id !== itemId);
}

function calculatePercentage() {
    if( $('#material_length_entered').val().trim() === '' && $("#quantity_entered_material").css('display') === 'none' )
    {
        toastr.error('Debe ingresar la longitud del material', 'Error',
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
    if( $('#material_width_entered').val().trim() === '' && $("#quantity_entered_material").css('display') === 'none' && $("#width_entered_material").css('display') !== 'none' )
    {
        toastr.error('Debe ingresar el ancho del material', 'Error',
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
    if( $('#material_quantity_entered').val().trim() === '' && $("#quantity_entered_material").attr('style') === '' )
    {
        toastr.error('Debe ingresar la cantidad del material', 'Error',
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

    if ($('#material_length_entered').val().trim() !== '' && $('#material_width_entered').val().trim() !== '')
    {
        var price_material = parseFloat($('#material_price').val());
        var length_material = parseFloat($('#material_length').val());
        var width_material = parseFloat($('#material_width').val());
        var length = parseFloat($('#material_length_entered').val());
        var width = parseFloat($('#material_width_entered').val());
        var areaTotal = length_material*width_material;
        var areaNueva = length*width;
        var percentage = parseFloat(areaNueva/areaTotal).toFixed(2);
        var new_price = parseFloat(percentage*price_material).toFixed(2);
        $('#material_percentage_entered').val(percentage);
        $('#material_price_entered').val(new_price);
    }

    if ($('#material_length_entered').val().trim() !== '' && $("#width_entered_material").css('display') === 'none' )
    {
        var price_material2 = parseFloat($('#material_price').val());
        var length_material2 = parseFloat($('#material_length').val());

        var length2 = parseFloat($('#material_length_entered').val());

        var percentage2 = parseFloat(length2/length_material2).toFixed(2);
        var new_price2 = parseFloat(percentage2*price_material2).toFixed(2);
        $('#material_percentage_entered').val(percentage2);
        $('#material_price_entered').val(new_price2);
    }

    if ( $('#material_quantity_entered').val().trim() !== '' )
    {
        var price_material3 = parseFloat($('#material_price').val());
        var quantity_entered = parseFloat($('#material_quantity_entered').val());
        var new_price3 = parseFloat(quantity_entered*price_material3).toFixed(2);
        $('#material_percentage_entered').val(quantity_entered);
        $('#material_price_entered').val(new_price3);

    }
}

function addTableMaterials() {
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        if( $('#material_length_entered').val().trim() === '' && $("#length_entered_material").attr('style') === '' )
        {
            toastr.error('Debe ingresar la longitud del material', 'Error',
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
        if( $('#material_width_entered').val().trim() === '' && $("#width_entered_material").attr('style') === '' )
        {
            toastr.error('Debe ingresar el ancho del material', 'Error',
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
        if( $("#material_quantity_entered").css('display') === '' && $('#material_quantity_entered').val().trim() === '' )
        {
            toastr.error('Debe ingresar la cantidad del material', 'Error',
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
        if( $('#material_percentage_entered').val().trim() === '' )
        {
            toastr.error('Debe hacer click en calcular', 'Error',
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
        if( $('#material_price_entered').val().trim() === '' )
        {
            toastr.error('Debe hacer click en calcular', 'Error',
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

        var material_quantity = ($("#material_quantity_entered").css('display') === '') ? $("#material_quantity_entered").val(): $("#material_percentage_entered").val();
        var total = $("#material_price_entered").val();
        var length = $('#material_length_entered').val();
        var witdh = $('#material_width_entered').val();

        //$items.push({ 'id': $items.length+1, 'material': $material, 'material_quantity': material_quantity, 'material_price':total, 'material_length':length, 'material_width':witdh});
        //console.log($renderMaterial);
        renderTemplateMaterial($material.code, $material.full_name, material_quantity, $material.unit_measure.name, $material.unit_price, total, $renderMaterial, length, witdh, $material);

        $('#material_length_entered').val('');
        $('#material_width_entered').val('');
        $('#material_percentage_entered').val('');
        $('#material_price_entered').val('');
        $('#material_quantity_entered').val('');
        $('#material_search').val(null).trigger('change');
        $modalAddMaterial.modal('hide');
    } else {
        if( $('#material_length_entered').val().trim() === '' && $("#length_entered_material").attr('style') === '' )
        {
            toastr.error('Debe ingresar la longitud del material', 'Error',
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
        if( $('#material_width_entered').val().trim() === '' && $("#width_entered_material").attr('style') === '' )
        {
            toastr.error('Debe ingresar el ancho del material', 'Error',
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
        if( $("#material_quantity_entered").css('display') === '' && $('#material_quantity_entered').val().trim() === '' )
        {
            toastr.error('Debe ingresar la cantidad del material', 'Error',
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
        if( $('#material_percentage_entered').val().trim() === '' )
        {
            toastr.error('Debe hacer click en calcular', 'Error',
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

        var material_quantity2 = ($("#material_quantity_entered").css('display') === '') ? $("#material_quantity_entered").val(): $("#material_percentage_entered").val();
        var length2 = $('#material_length_entered').val();
        var witdh2 = $('#material_width_entered').val();
        console.log($renderMaterial);
        //$items.push({ 'id': $items.length+1, 'material': $material, 'material_quantity': material_quantity2, 'material_price':0, 'material_length':length2, 'material_width':witdh2});
        renderTemplateMaterial($material.code, $material.full_name, material_quantity2, $material.unit_measure.name, $material.unit_price, 0, $renderMaterial, length2, witdh2, $material);

        $('#material_length_entered').val('');
        $('#material_width_entered').val('');
        $('#material_percentage_entered').val('');
        $('#material_quantity_entered').val('');
        $('#material_search').val(null).trigger('change');
        $modalAddMaterial.modal('hide');
    }

}

function addMaterial() {
    var select_material = $(this).parent().parent().children().children().children().next();
    // TODO: Tomar el texto no el val()
    var material_search = select_material.val();

    $material = $materials.find( mat=>mat.id === parseInt(material_search) );

    if( $material === undefined )
    {
        toastr.error('Debe seleccionar un material', 'Error',
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

    for (var i=0; i<$items.length; i++)
    {
        var mat = $items.find( mat=>mat.material.id === $material.id );
        if (mat !== undefined)
        {
            toastr.error('Este material ya esta seleccionado', 'Error',
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

    if ( $material.type_scrap === null )
    {
        $('#presentation').hide();
        $('#length_material').hide();
        $('#width_material').hide();
        $('#width_entered_material').hide();
        $('#length_entered_material').hide();
        $('#material_quantity').val($material.stock_current);
        $('#quantity_entered_material').show();
        $('#material_price').val($material.unit_price);

        $renderMaterial = $(this).parent().parent().next().next().next();

        $modalAddMaterial.modal('show');
    } else {
        switch($material.type_scrap.id) {
            case 1:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#width_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#material_width').val($material.type_scrap.width);
                $('#material_quantity').val($material.stock_current);
                $('#quantity_entered_material').hide();
                $('#material_price').val($material.unit_price);
                break;
            case 2:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#width_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#material_width').val($material.type_scrap.width);
                $('#quantity_entered_material').hide();
                $('#material_quantity').val($material.stock_current);
                $('#material_price').val($material.unit_price);
                break;
            case 3:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#width_material').hide();
                $('#width_entered_material').hide();
                $('#quantity_entered_material').hide();
                $('#material_quantity').val($material.stock_current);
                $('#material_price').val($material.unit_price);
                break;
            case 4:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#width_material').hide();
                $('#width_entered_material').hide();
                $('#quantity_entered_material').hide();
                $('#material_quantity').val($material.stock_current);
                $('#material_price').val($material.unit_price);
                break;
            case 5:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#width_material').hide();
                $('#width_entered_material').hide();
                $('#quantity_entered_material').hide();
                $('#material_quantity').val($material.stock_current);
                $('#material_price').val($material.unit_price);
                break;
            case 6:
                $('#presentation').show();
                $("#fraction").prop("checked", true);
                $('#length_entered_material').show();
                $('#width_entered_material').show();
                $('#material_length').val($material.type_scrap.length);
                $('#material_width').val($material.type_scrap.width);
                $('#material_quantity').val($material.stock_current);
                $('#quantity_entered_material').hide();
                $('#material_price').val($material.unit_price);
                break;
            default:
                $('#length_material').hide();
                $('#width_material').hide();
                $('#width_entered_material').hide();
                $('#length_entered_material').hide();
                $('#material_quantity').val($material.stock_current);
                $('#material_percentage_entered').hide();
                $('#material_price').val($material.unit_price);

        }
        //var idMaterial = $(this).select2('data').id;

        $renderMaterial = $(this).parent().parent().next().next().next();

        $modalAddMaterial.modal('show');
    }


}

function editedActive() {
    var flag = false;
    $(document).find('[data-equip]').each(function(){
        console.log($(this));
        if ($(this).hasClass('card-gray-dark'))
        {
            flag = true;
        }
    });

    return flag;
}

function imagesIncomplete() {
    var flag = false;
    var descripciones = $(document).find("[name='descplanos[]']").length;
    var planos = $("input[type='file'][name='planos[]']").filter(function (){
        return this.value
    }).length;
    console.log(descripciones);
    console.log(planos);
    if ( descripciones != planos )
    {
        flag = true;
    }

    return flag;
}

function storeQuote() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);
    console.log(imagesIncomplete());
    if ( imagesIncomplete() )
    {
        toastr.error('No se puede guardar porque faltan imagenes o descripciones.', 'Error',
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
        $("#btn-submit").attr("disabled", false);
        return;
    }

    if ( editedActive() )
    {
        toastr.error('No se puede guardar porque hay equipos no confirmados.', 'Error',
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
        $("#btn-submit").attr("disabled", false);
        return;
    }
    if( $equipments.length === 0 )
    {
        toastr.error('No se puede crear una cotización sin equipos.', 'Error',
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
        $("#btn-submit").attr("disabled", false);
        return;
    }
    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var equipos = JSON.stringify($equipments);
    var formulario = $('#formCreate')[0];
    //var formulario = $('#formCreate');
    var form = new FormData(formulario);
    //var form = formulario;
    //console.log($equipments);
    var nameequipment = $('[data-nameequipment]').val();
    form.append('nameequipment', nameequipment);

    var largeequipment = $('[data-largeequipment]').val();
    form.append('largeequipment', largeequipment);

    var widthequipment = $('[data-widthequipment]').val();
    form.append('widthequipment', widthequipment);

    var highequipment = $('[data-highequipment]').val();
    form.append('highequipment', highequipment);

    var categoryequipment = $('[data-categoryequipment]').val();
    form.append('categoryequipment', categoryequipment);

    var detailequipment = $('[data-detailequipment]').val();
    form.append('detailequipment', detailequipment);

    var categoryequipmentid = $('[data-categoryequipmentid]').val();
    form.append('categoryequipmentid', categoryequipmentid);

    form.append('equipments', equipos);
    console.log(form);
    $.ajax({
        url: createUrl,
        method: 'POST',
        data: form,
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
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
            $("#btn-submit").attr("disabled", false);

        },
    });
}

function calculateTotalMaterialQuantity(e) {
    var cantidad = e.value;
    var material_id = e.getAttribute('material_id');
    console.log(material_id);
    var igvRate = 0.18;

    var width = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    var length = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;

    var material = $materials.find( mat=>mat.id === parseInt(material_id) );

    if ( material.type_scrap == null )
    {
        var newPriceConIgv = parseFloat(cantidad*material.unit_price).toFixed(2);

        var newPriceSinIgv = parseFloat(newPriceConIgv / (1 + igvRate)).toFixed(2);

        var newPriceConIgvTotal = parseFloat(material.unit_price).toFixed(2);

        var newPriceSinIgvTotal = parseFloat(newPriceConIgvTotal / (1 + igvRate)).toFixed(2);

        //var priceSinIgv =
        e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotal;
        //var priceConIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotal;
        //var priceSinIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgv ;
        //var priceConIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgv ;

    } else {

        // TODO: Si es tubo
        if (material && material.type_scrap && (material.type_scrap.id === 3 || material.type_scrap.id === 4 || material.type_scrap.id === 5))
        {
            if ( length == null || length == '' )
            {
                // TODO: Solo colocaron cantidad
                var newPriceConIgvT = parseFloat(cantidad*material.unit_price).toFixed(2);

                var newPriceSinIgvT = parseFloat(newPriceConIgvT / (1 + igvRate)).toFixed(2);

                var newPriceConIgvTotalT = parseFloat(material.unit_price).toFixed(2);

                var newPriceSinIgvTotalT = parseFloat(newPriceConIgvTotalT / (1 + igvRate)).toFixed(2);

                //var priceSinIgv =
                e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalT;
                //var priceConIgv =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalT;
                //var priceSinIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvT ;
                //var priceConIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvT ;

            } else {
                // TODO: Solo colocaron largo
                var lengthOriginalMaterial = material.type_scrap.length;
                var newLength = parseFloat(cantidad*lengthOriginalMaterial).toFixed(2);

                // Actualizamos la cantidad automaticamente
                e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value = newLength;

                var newPriceConIgvT2 = parseFloat(cantidad*material.unit_price).toFixed(2);

                var newPriceSinIgvT2 = parseFloat(newPriceConIgvT2 / (1 + igvRate)).toFixed(2);

                var newPriceConIgvTotalT2 = parseFloat(material.unit_price).toFixed(2);

                var newPriceSinIgvTotalT2 = parseFloat(newPriceConIgvTotalT2 / (1 + igvRate)).toFixed(2);

                //var priceSinIgv =
                e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalT2;
                //var priceConIgv =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalT2;
                //var priceSinIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvT2 ;
                //var priceConIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvT2 ;

            }

        } else {

            // TODO: Si es plancha
            if ( length == "" || width == "" )
            {
                // TODO: Solo colocaron cantidad
                var newPriceConIgvP = parseFloat(cantidad*material.unit_price).toFixed(2);

                var newPriceSinIgvP = parseFloat(newPriceConIgvP / (1 + igvRate)).toFixed(2);

                var newPriceConIgvTotalP = parseFloat(material.unit_price).toFixed(2);

                var newPriceSinIgvTotalP = parseFloat(newPriceConIgvTotalP / (1 + igvRate)).toFixed(2);

                //var priceSinIgv =
                e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalP;
                //var priceConIgv =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalP;
                //var priceSinIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvP ;
                //var priceConIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvP ;

            } else {
                // TODO: Colocaron largo y ancho, no se puede asi que seteamos el largo y ancho a 0
                var newLengthP = 0;

                var newWidthP = 0;

                // Actualizamos la cantidad automaticamente

                e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value = newLengthP;
                e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value = newWidthP;

                var newPriceConIgvP2 = parseFloat(cantidad*material.unit_price).toFixed(2);

                var newPriceSinIgvP2 = parseFloat(newPriceConIgvP2 / (1 + igvRate)).toFixed(2);

                var newPriceConIgvTotalP2 = parseFloat(material.unit_price).toFixed(2);

                var newPriceSinIgvTotalP2 = parseFloat(newPriceConIgvTotalP2 / (1 + igvRate)).toFixed(2);

                //var priceSinIgv =
                e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalP2;
                //var priceConIgv =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalP2;
                //var priceSinIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvP2 ;
                //var priceConIgvTotal =
                e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvP2 ;

            }
        }
    }


}

function calculateTotalMaterialLargo(e) {
    var largo = e.value;
    var material_id = e.getAttribute('material_id');
    console.log(material_id);
    var igvRate = 0.18;

    var width = e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value;
    //var length = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;

    var material = $materials.find( mat=>mat.id === parseInt(material_id) );

    // TODO: Si es tubo
    if (material && material.type_scrap && (material.type_scrap.id === 3 || material.type_scrap.id === 4 || material.type_scrap.id === 5))
    {

        // TODO: Solo colocaron cantidad
        var lengthOriginalMaterial = material.type_scrap.length;
        var cantidad = parseFloat(largo/lengthOriginalMaterial).toFixed(2);

        var newPriceConIgvT = parseFloat(cantidad*material.unit_price).toFixed(2);

        var newPriceSinIgvT = parseFloat(newPriceConIgvT / (1 + igvRate)).toFixed(2);

        var newPriceConIgvTotalT = parseFloat(material.unit_price).toFixed(2);

        var newPriceSinIgvTotalT = parseFloat(newPriceConIgvTotalT / (1 + igvRate)).toFixed(2);

        //var cantidad =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = cantidad;
        //var priceSinIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalT;
        //var priceConIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalT;
        //var priceSinIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvT ;
        //var priceConIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvT ;

    } else {

        // TODO: Si es plancha falta
        var lengthOriginalMaterialP = material.type_scrap.length;
        var widthOriginalMaterialP = material.type_scrap.width;

        var areaOriginal = lengthOriginalMaterialP*widthOriginalMaterialP;

        var areaNew = largo*width;

        var cantidadP = parseFloat(areaNew/areaOriginal).toFixed(2);

        var newPriceConIgvP = parseFloat(cantidadP*material.unit_price).toFixed(2);

        var newPriceSinIgvP = parseFloat(newPriceConIgvP / (1 + igvRate)).toFixed(2);

        var newPriceConIgvTotalP = parseFloat(material.unit_price).toFixed(2);

        var newPriceSinIgvTotalP = parseFloat(newPriceConIgvTotalP / (1 + igvRate)).toFixed(2);

        //var cantidad =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = cantidadP;
        //var priceSinIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalP;
        //var priceConIgv =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalP;
        //var priceSinIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvP ;
        //var priceConIgvTotal =
        e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvP ;

    }
}

function calculateTotalMaterialAncho(e) {
    var ancho = e.value;
    var material_id = e.getAttribute('material_id');
    console.log(material_id);
    var igvRate = 0.18;

    var length = e.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.value;
    //var length = e.parentElement.parentElement.previousElementSibling.previousElementSibling.firstElementChild.firstElementChild.value;

    var material = $materials.find( mat=>mat.id === parseInt(material_id) );

    // TODO: Si es plancha falta
    var lengthOriginalMaterialP = material.type_scrap.length;
    var widthOriginalMaterialP = material.type_scrap.width;

    var areaOriginal = lengthOriginalMaterialP*widthOriginalMaterialP;

    var areaNew = length*ancho;

    var cantidadP = parseFloat(areaNew/areaOriginal).toFixed(2);

    var newPriceConIgvP = parseFloat(cantidadP*material.unit_price).toFixed(2);

    var newPriceSinIgvP = parseFloat(newPriceConIgvP / (1 + igvRate)).toFixed(2);

    var newPriceConIgvTotalP = parseFloat(material.unit_price).toFixed(2);

    var newPriceSinIgvTotalP = parseFloat(newPriceConIgvTotalP / (1 + igvRate)).toFixed(2);

    //var cantidad =
    e.parentElement.parentElement.nextElementSibling.firstElementChild.firstElementChild.value = cantidadP;
    //var priceSinIgv =
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvTotalP;
    //var priceConIgv =
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvTotalP;
    //var priceSinIgvTotal =
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceSinIgvP ;
    //var priceConIgvTotal =
    e.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.firstElementChild.value = newPriceConIgvP ;

}

function renderTemplateMaterial(code, description, quantity, unit, price, total, render, length, width, material) {
    var card = render.parent().parent().parent().parent();
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        var clone = activateTemplate('#materials-selected');

        if ( material.enable_status == 0 )
        {
            clone.querySelector("[data-materialDescription]").setAttribute('value', description);
            clone.querySelector("[data-materialDescription]").setAttribute("style", "color:purple;");

        } else {
            if ( material.stock_current == 0 )
            {
                clone.querySelector("[data-materialDescription]").setAttribute('value', description);
                clone.querySelector("[data-materialDescription]").setAttribute("style", "color:red;");
            } else {
                if ( material.update_price == 1 )
                {
                    clone.querySelector("[data-materialDescription]").setAttribute('value', description);
                    clone.querySelector("[data-materialDescription]").setAttribute("style", "color:blue;");
                } else {
                    clone.querySelector("[data-materialDescription]").setAttribute('value', description);
                }
                //clone.querySelector("[data-materialDescription]").setAttribute('value', description);
            }
        }

        clone.querySelector('[data-materialid]').value = material.id; // ✅ AQUÍ
        clone.querySelector("[data-materialUnit]").setAttribute('value', unit);
        if (material.type_scrap == null)
        {
            clone.querySelector("[data-materialLargo]").setAttribute('value', length);
            clone.querySelector("[data-materialAncho]").setAttribute('value', width);
            clone.querySelector("[data-materialLargo]").setAttribute('readonly', 'readonly');
            clone.querySelector("[data-materialAncho]").setAttribute('readonly', 'readonly');

            clone.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
            clone.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);

        } else {
            if (material && material.type_scrap && (material.type_scrap.id === 3 || material.type_scrap.id === 4 || material.type_scrap.id === 5))
            {
                if ( length == null || length == '' )
                {
                    clone.querySelector("[data-materialLargo]").setAttribute('value', length);
                    clone.querySelector("[data-materialLargo]").setAttribute('readonly', 'readonly');
                } else {
                    clone.querySelector("[data-materialLargo]").setAttribute('value', length);
                }

                clone.querySelector("[data-materialAncho]").setAttribute('readonly', 'readonly');

                clone.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
                clone.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);
            } else {
                if ( length == null || width == null )
                {
                    clone.querySelector("[data-materialLargo]").setAttribute('readonly', 'readonly');
                    clone.querySelector("[data-materialAncho]").setAttribute('readonly', 'readonly');
                } else {
                    clone.querySelector("[data-materialLargo]").setAttribute('value', length);
                    clone.querySelector("[data-materialAncho]").setAttribute('value', width);
                }
                clone.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
                clone.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);
            }
        }

        clone.querySelector("[data-materialQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-materialQuantity]").setAttribute('material_id', material.id);
        clone.querySelector("[data-materialPrice2]").setAttribute('value', (parseFloat(price)/1.18).toFixed(2));
        clone.querySelector("[data-materialPrice]").setAttribute('value', (parseFloat(price)).toFixed(2));
        clone.querySelector("[data-materialTotal2]").setAttribute( 'value', (parseFloat(total)/1.18).toFixed(2));
        clone.querySelector("[data-materialTotal]").setAttribute( 'value', (parseFloat(total)).toFixed(2));
        clone.querySelector("[data-delete]").setAttribute('data-delete', code);
        render.append(clone);
    } else {
        var clone2 = activateTemplate('#materials-selected');

        if ( material.enable_status == 0 )
        {
            clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
            clone2.querySelector("[data-materialDescription]").setAttribute("style", "color:purple;");

        } else {
            if ( material.stock_current == 0 )
            {
                clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
                clone2.querySelector("[data-materialDescription]").setAttribute("style", "color:red;");
            } else {

                if ( material.update_price == 1 )
                {
                    clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
                    clone2.querySelector("[data-materialDescription]").setAttribute("style", "color:blue;");
                } else {
                    clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
                }
                //clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
            }
        }

        //clone2.querySelector("[data-materialDescription]").setAttribute('value', description);
        clone2.querySelector('[data-materialid]').value = material.id; // ✅ AQUÍ
        clone2.querySelector("[data-materialUnit]").setAttribute('value', unit);
        if (material.type_scrap == null)
        {
            clone2.querySelector("[data-materialLargo]").setAttribute('value', length);
            clone2.querySelector("[data-materialAncho]").setAttribute('value', width);
            clone2.querySelector("[data-materialLargo]").setAttribute('readonly', 'readonly');
            clone2.querySelector("[data-materialAncho]").setAttribute('readonly', 'readonly');

            clone2.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
            clone2.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);

        } else {
            clone2.querySelector("[data-materialLargo]").setAttribute('value', length);
            clone2.querySelector("[data-materialAncho]").setAttribute('value', width);

            clone2.querySelector("[data-materialLargo]").setAttribute('material_id', material.id);
            clone2.querySelector("[data-materialAncho]").setAttribute('material_id', material.id);
        }
        clone2.querySelector("[data-materialQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone2.querySelector("[data-materialQuantity]").setAttribute('material_id', material.id);
        clone2.querySelector("[data-materialPrice]").setAttribute('value', (parseFloat(price)).toFixed(2));
        clone2.querySelector("[data-materialTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(price)).toFixed(2));
        clone2.querySelector("[data-materialPrice]").setAttribute("style","display:none;");
        clone2.querySelector("[data-materialTotal]").setAttribute("style","display:none;");

        clone2.querySelector("[data-materialPrice2]").setAttribute('value', (parseFloat(price)/1.18).toFixed(2));
        clone2.querySelector("[data-materialTotal2]").setAttribute( 'value', ((parseFloat(quantity)*parseFloat(price))/1.18).toFixed(2));
        clone2.querySelector("[data-materialPrice2]").setAttribute("style","display:none;");
        clone2.querySelector("[data-materialTotal2]").setAttribute("style","display:none;");


        clone2.querySelector("[data-delete]").setAttribute('data-delete', code);
        render.append(clone2);
    }
}

function toastOpts() {
    return {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "2000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };
}

function renderTemplateConsumable($render, consumable, quantity) {
    const card = $render.closest('.card');
    /*card.removeClass('card-success').addClass('card-gray-dark');*/

    const canSeePrices = $.inArray('showPrices_quote', $permissions) !== -1;

    const clone = activateTemplate('#template-consumable');

    const desc = consumable.full_name || '';
    const unitText =
        (consumable.unit_measure && (consumable.unit_measure.name || consumable.unit_measure.description)) ||
        consumable.unit ||
        '';

    // ✅ color reglas (igual que antes)
    const $descInput = $(clone).find('[data-consumableDescription]');
    $descInput.val(desc);

    if (parseInt(consumable.enable_status) === 0) {
        $descInput.css('color', 'purple');
    } else if (parseFloat(consumable.stock_current || 0) === 0) {
        $descInput.css('color', 'red');
    } else if (parseInt(consumable.state_update_price || consumable.update_price || 0) === 1) {
        $descInput.css('color', 'blue');
    }

    // ✅ hidden id
    $(clone).find('[data-consumableId]').val(consumable.id);

    // ✅ unit + qty
    $(clone).find('[data-consumableUnit]').val(unitText);
    $(clone).find('[data-consumableQuantity]').val((parseFloat(quantity) || 0).toFixed(2));

    // ✅ precios y totales
    const price = parseFloat(consumable.unit_price || 0);
    const total = price * parseFloat(quantity || 0);

    const $p = $(clone).find('[data-consumablePrice]');
    const $p2 = $(clone).find('[data-consumablePrice2]');
    const $t = $(clone).find('[data-consumableTotal]');
    const $t2 = $(clone).find('[data-consumableTotal2]');

    $p.val(price.toFixed(2));
    $p2.val((price / 1.18).toFixed(2));
    $t.val(total.toFixed(2));
    $t2.val((total / 1.18).toFixed(2));

    // ✅ si NO puede ver precios, escondemos columnas (como tu lógica)
    if (!canSeePrices) {
        $p.hide();  $t.hide();
        $p2.hide(); $t2.hide();
    }

    // ✅ delete
    $(clone).find('[data-deleteConsumable]').attr('data-deleteConsumable', consumable.id);

    $render.append(clone);
}

function renderTemplateElectric($render, electric, quantity) {
    var $card = $render.closest('.card');
    //$card.removeClass('card-success').addClass('card-gray-dark');

    var showPrices = $.inArray('showPrices_quote', $permissions) !== -1;

    var clone = activateTemplate('#template-electric');

    // descripción
    var desc = electric.full_name || electric.text || '';
    var $desc = $(clone).find('[data-electricDescription]');
    $desc.val(desc);

    // color por estado (si viene)
    if (electric.enable_status == 0) $desc.css('color','purple');
    else if (electric.stock_current == 0) $desc.css('color','red');
    else if (electric.state_update_price == 1) $desc.css('color','blue');

    // hidden id para duplicados
    $(clone).find('[data-electricId]').val(electric.id);

    // unidad (según lo que devuelvas)
    var unit =
        (electric.unit_measure && (electric.unit_measure.name || electric.unit_measure.description)) ?
            (electric.unit_measure.name || electric.unit_measure.description) :
            (electric.unit || '');

    $(clone).find('[data-electricUnit]').val(unit);

    // cálculos
    var price = parseFloat(electric.unit_price || 0);
    var qty   = parseFloat(quantity || 0);
    var total = price * qty;

    $(clone).find('[data-electricQuantity]').val(qty.toFixed(2));
    $(clone).find('[data-electricPrice]').val(price.toFixed(2));
    $(clone).find('[data-electricPrice2]').val((price/1.18).toFixed(2));
    $(clone).find('[data-electricTotal]').val(total.toFixed(2));
    $(clone).find('[data-electricTotal2]').val((total/1.18).toFixed(2));

    // esconder columnas si no tiene permiso
    if (!showPrices) {
        $(clone).find('[data-electricPrice],[data-electricTotal],[data-electricPrice2],[data-electricTotal2]').hide();
    }

    // botón eliminar
    $(clone).find('[data-deleteElectric]').attr('data-deleteElectric', electric.id);

    $render.append(clone);
}

function renderTemplateMano(render, description, unit, quantity, unitPrice) {
    var card = render.parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    var clone = activateTemplate('#template-mano');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        clone.querySelector("[data-manoDescription]").setAttribute('value', description);
        clone.querySelector("[data-manoUnit]").setAttribute('value', unit);
        clone.querySelector("[data-manoQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-manoPrice]").setAttribute('value', (parseFloat(unitPrice)).toFixed(2));
        clone.querySelector("[data-manoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));
    } else {
        clone.querySelector("[data-manoDescription]").setAttribute('value', description);
        clone.querySelector("[data-manoUnit]").setAttribute('value', unit);
        clone.querySelector("[data-manoQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
        clone.querySelector("[data-manoPrice]").setAttribute('value', (parseFloat(unitPrice)).toFixed(2));
        clone.querySelector("[data-manoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));
        clone.querySelector("[data-manoPrice]").setAttribute("style","display:none;");
        clone.querySelector("[data-manoTotal]").setAttribute("style","display:none;");

    }

    render.append(clone);
}

function renderTemplateTorno(render, description, quantity, unitPrice) {
    var card = render.parent().parent().parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    var clone = activateTemplate('#template-torno');

    clone.querySelector("[data-tornoDescription]").setAttribute('value', description);
    clone.querySelector("[data-tornoQuantity]").setAttribute('value', (parseFloat(quantity)).toFixed(2));
    clone.querySelector("[data-tornoPrice]").setAttribute('value', (parseFloat(unitPrice)).toFixed(2));
    clone.querySelector("[data-tornoTotal]").setAttribute( 'value', (parseFloat(quantity)*parseFloat(unitPrice)).toFixed(2));

    render.append(clone);
}

function renderTemplateDia(render, description, pricePerHour2, hoursPerPerson2, quantityPerson2, total2) {
    var card = render.parent().parent().parent().parent();
    card.removeClass('card-success');
    card.addClass('card-gray-dark');
    var clone = activateTemplate('#template-dia');
    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
        clone.querySelector("[data-description]").setAttribute('value', description);
        clone.querySelector("[data-cantidad]").setAttribute('value', (parseFloat(quantityPerson2)).toFixed(2));
        clone.querySelector("[data-horas]").setAttribute('value', (parseFloat(hoursPerPerson2)).toFixed(2));
        clone.querySelector("[data-precio]").setAttribute('value', (parseFloat(pricePerHour2)).toFixed(2));
        clone.querySelector("[data-total]").setAttribute( 'value', (parseFloat(total2)).toFixed(2));
    } else {
        clone.querySelector("[data-description]").setAttribute('value', description);
        clone.querySelector("[data-cantidad]").setAttribute('value', (parseFloat(quantityPerson2)).toFixed(2));
        clone.querySelector("[data-horas]").setAttribute('value', (parseFloat(hoursPerPerson2)).toFixed(2));
        clone.querySelector("[data-precio]").setAttribute('value', (parseFloat(pricePerHour2)).toFixed(2));
        clone.querySelector("[data-total]").setAttribute( 'value', (parseFloat(total2)).toFixed(2));
        clone.querySelector("[data-precio]").setAttribute("style","display:none;");
        clone.querySelector("[data-total]").setAttribute("style","display:none;");

    }

    render.append(clone);
}

function renderTemplateEquipment() {
    var clone = activateTemplate('#template-equipment');

    $('#body-equipment').append(clone);

    $('.unitMeasure').select2({
        placeholder: "Seleccione unidad",
    });
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}