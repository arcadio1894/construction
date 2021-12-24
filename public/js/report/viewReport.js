$(document).ready(function () {
    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });

    $modalViewReportDollars = $('#modalViewReportDollars');
    $modalViewReportSoles = $('#modalViewReportSoles');

    $salesChart3 = $('#sales-chart3');
    $salesChart4 = $('#sales-chart4');

    $(document).on('click', '#report_dollars_quote', viewReportDollarsQuote);
    $(document).on('click', '#btnViewReportDollarsQuote', getReportDollarsQuote);
    $(document).on('click', '#report_soles_quote', viewReportSolesQuote);
    $(document).on('click', '#btnViewReportSolesQuote', getReportSolesQuote);
});

var $modalViewReportDollars;
var $modalViewReportSoles;

var $salesChart3;
var $salesChart4;

function getReportDollarsQuote() {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var date_start = moment($('#start').val(),'DD/MM/YYYY').format('YYYY-MM-DD');
    var date_end = moment($('#end').val(),'DD/MM/YYYY').format('YYYY-MM-DD');

    console.log($('#start').val());
    console.log(date_start);
    console.log($('#end').val());
    console.log(date_end);

    $.get( "/dashboard/report/chart/quote/view/" + date_start + "/" + date_end , function( data ) {
        console.log(data);

        var salesChart3  = new Chart($salesChart3, {
            type   : 'bar',
            data   : {
                labels  : data.monthsNames,
                datasets: [
                    {
                        backgroundColor: '#007bff',
                        borderColor    : '#007bff',
                        data           : data.$dollars
                    },
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips           : {
                    mode     : mode,
                    intersect: intersect
                },
                hover              : {
                    mode     : mode,
                    intersect: intersect
                },
                legend             : {
                    display: false
                },
                scales             : {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display      : true,
                            lineWidth    : '4px',
                            color        : 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks    : $.extend({
                            beginAtZero: true,

                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                if (value >= 1000) {
                                    value /= 1000;
                                    value += 'k';
                                }
                                return '$ ' + value;
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display  : true,
                        gridLines: {
                            display: false
                        },
                        ticks    : ticksStyle
                    }]
                }
            }
        });

        $('#total_dollars_view_d').html('$ '+data.sum_dollars);
        $('#percentage_dollars_view_d').html(data.percentage_dollars + '%');
    });
}

function getReportSolesQuote() {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var $salesChart3 = $('#sales-chart3');
    var $salesChart4 = $('#sales-chart4');

    var date_start = moment($('#start_s').val(),'DD/MM/YYYY').format('YYYY-MM-DD');
    var date_end = moment($('#end_s').val(),'DD/MM/YYYY').format('YYYY-MM-DD');

    console.log($('#start_s').val());
    console.log(date_start);
    console.log($('#end_s').val());
    console.log(date_end);

    $.get( "/dashboard/report/chart/quote/view/" + date_start + "/" + date_end , function( data ) {
        console.log(data);

        var salesChart4  = new Chart($salesChart4, {
            type   : 'bar',
            data   : {
                labels  : data.monthsNames,
                datasets: [
                    {
                        backgroundColor: '#ced4da',
                        borderColor    : '#ced4da',
                        data           : data.soles
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips           : {
                    mode     : mode,
                    intersect: intersect
                },
                hover              : {
                    mode     : mode,
                    intersect: intersect
                },
                legend             : {
                    display: false
                },
                scales             : {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display      : true,
                            lineWidth    : '4px',
                            color        : 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks    : $.extend({
                            beginAtZero: true,

                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                if (value >= 1000) {
                                    value /= 1000;
                                    value += 'k'
                                }
                                return 'S/. ' + value
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display  : true,
                        gridLines: {
                            display: false
                        },
                        ticks    : ticksStyle
                    }]
                }
            }
        });

        $('#total_soles_view_s').html('S/ '+data.sum_soles);
        $('#percentage_soles_view_s').html(data.percentage_soles + '%');
    });
}

function viewReportDollarsQuote() {
    event.preventDefault();

    $('#sales-chart3').html('');
    $('#sales-chart4').html('');

    $modalViewReportDollars.modal('show');
}

function viewReportSolesQuote() {
    event.preventDefault();

    $('#sales-chart3').html('');
    $('#sales-chart4').html('');

    $modalViewReportSoles.modal('show');
}