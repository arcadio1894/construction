$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('#btn-refresh').on('click', getAmountReport);
});

function getAmountReport() {
    $("#element_loader").LoadingOverlay("show", {
        background  : "rgba(61, 215, 239, 0.4)"
    });
    $.get( "/dashboard/report/amount/items", function( data ) {
        console.log( data );
        $('#amount_dollars').html(parseFloat(data.amount_dollars).toFixed(2));
        $('#amount_soles').html(parseFloat(data.amount_soles).toFixed(2));
        $('#quantity_items').html(parseFloat(data.quantity_items).toFixed(2));
        $("#element_loader").LoadingOverlay("hide", true);
    });
}