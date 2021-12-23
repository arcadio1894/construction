$(document).ready(function () {
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    $.get( "/dashboard/report/amount/items", function( data ) {
        console.log( data );
        $('#amount_dollars').html(parseFloat(data.amount_dollars).toFixed(2));
        $('#amount_soles').html(parseFloat(data.amount_soles).toFixed(2));
        $('#quantity_items').html(parseFloat(data.quantity_items).toFixed(2));
    });
});