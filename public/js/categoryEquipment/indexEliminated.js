$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());

    getDataCategoryEquipmentEliminated(1);

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSeach);
});

var $permissions;

function showDataSeach() {
    getDataCategoryEquipmentEliminated(1);
}

function showData() {
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataCategoryEquipmentEliminated(numberPage);
}

function getDataCategoryEquipmentEliminated($numberPage) {
    var nameCategoryEquipment = $('#inputNameCategoryEquipment').val();
    console.log(nameCategoryEquipment);
    $.get('/dashboard/get/data/category/equipmentseliminated/' + $numberPage, {
        name_category_equipment: nameCategoryEquipment
    }, function (data) {
        renderDataCategoryEquipments(data);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // Función de error
        console.error(textStatus, errorThrown);
    });
}

function renderDataCategoryEquipments(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;

    $("#body-card").html('');
    $("#pagination").html('');
    $("#textPagination").html('Mostrando ' + pagination.startRecord + ' a ' + pagination.endRecord + ' de ' + pagination.totalFilteredRecords + ' operaciones');
    $('#numberItems').html(pagination.totalFilteredRecords);

    for (let j = 0; j < dataAccounting.length; j++) {
        renderCategoryEquipmentCard(dataAccounting[j]);
    }

    // Actualizamos la paginación
    renderPagination(pagination);
}

function renderCategoryEquipmentCard(data) {
    var clone = activateTemplate('#item-card');
    clone.querySelector("[data-description]").innerHTML = data.description;
    clone.querySelector("[data-number]").innerHTML = data.number;
    var imageElement = clone.querySelector("[data-image]");
    imageElement.setAttribute('src', document.location.origin + '/images/categoryEquipment/' + data.image);
    imageElement.style.width = '100px';
    imageElement.style.height = 'auto';

    var restoreButton = clone.querySelector("[data-restore]");
    restoreButton.setAttribute('data-restore', data.id);
    restoreButton.setAttribute('data-description', data.description);
    restoreButton.setAttribute('data-image', data.image);

    $("#body-card").append(clone);
    $('[data-toggle="tooltip"]').tooltip();
}

function renderPagination(pagination) {
    $("#pagination").html('');

    if (pagination.totalPages > 1) {
        renderPageButton(pagination.currentPage - 1, 'previous', '<<');
        if (pagination.currentPage > 3) {
            renderPageButton(1, 'item', 1);
            if (pagination.currentPage > 4) {
                renderDisabledPage();
            }
        }

        for (var i = Math.max(1, pagination.currentPage - 2); i <= Math.min(pagination.totalPages, pagination.currentPage + 2); i++) {
            renderPageButton(i, 'item', i);
        }

        if (pagination.currentPage < pagination.totalPages - 2) {
            if (pagination.currentPage < pagination.totalPages - 3) {
                renderDisabledPage();
            }
            renderPageButton(pagination.totalPages, 'item', pagination.totalPages);
        }
    }

    if (pagination.currentPage < pagination.totalPages) {
        renderPageButton(pagination.currentPage + 1, 'next', '>>');
    }
}

function renderPageButton(number, type, label) {
    var clone;
    if (type === 'item') {
        clone = activateTemplate('#item-page');
        clone.querySelector("[data-item]").setAttribute('data-item', number);
        clone.querySelector("[data-item]").innerHTML = number;
    } else if (type === 'previous') {
        clone = activateTemplate('#previous-page');
        clone.querySelector("[data-item]").setAttribute('data-item', number);
        clone.querySelector("[i.previous]").innerHTML = label;
    } else if (type === 'next') {
        clone = activateTemplate('#next-page');
        clone.querySelector("[data-item]").setAttribute('data-item', number);
        clone.querySelector("[i.next]").innerHTML = label;
    }
    $("#pagination").append(clone);
}

function renderDisabledPage() {
    var clone = activateTemplate('#disabled-page');
    $("#pagination").append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}






