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

    $modalViewIncomeExpenseDollars = $('#modalViewReportIncomeExpenseDollars');
    $modalViewIncomeExpenseSoles = $('#modalViewReportIncomeExpenseSoles');
    $modalViewIncomeExpenseMix = $('#modalViewReportIncomeExpenseMix');

    $modalViewUtilitiesDollars = $('#modalViewReportUtilitiesDollars');
    $modalViewUtilitiesSoles = $('#modalViewReportUtilitiesSoles');
    $modalViewUtilitiesMix = $('#modalViewReportUtilitiesMix');

    $salesChart3 = $('#sales-chart3');
    $salesChart4 = $('#sales-chart4');

    $(document).on('click', '#report_dollars_quote', viewReportDollarsQuote);
    $(document).on('click', '#btnViewReportDollarsQuote', getReportDollarsQuote);
    $(document).on('click', '#report_soles_quote', viewReportSolesQuote);
    $(document).on('click', '#btnViewReportSolesQuote', getReportSolesQuote);

    $(document).on('click', '#report_expenses_income_dollars', viewReportIncomeExpenseDollars);
    $(document).on('click', '#btnViewReportIncomeExpenseDollars', getReportIncomeExpenseDollars);

    $(document).on('click', '#report_expenses_income_soles', viewReportIncomeExpenseSoles);
    $(document).on('click', '#btnViewReportIncomeExpenseSoles', getReportIncomeExpenseSoles);

    $(document).on('click', '#report_expenses_income_mix', viewReportIncomeExpenseMix);
    $(document).on('click', '#btnViewReportIncomeExpenseMix', getReportIncomeExpenseMix);



    $(document).on('click', '#report_utilities_dollars', viewReportUtilitiesDollars);
    $(document).on('click', '#btnViewReportUtilitiesDollars', getReportUtilitiesDollars);

    $(document).on('click', '#report_utilities_soles', viewReportUtilitiesSoles);
    $(document).on('click', '#btnViewReportUtilitiesSoles', getReportUtilitiesSoles);

    $(document).on('click', '#report_utilities_mix', viewReportUtilitiesMix);
    $(document).on('click', '#btnViewReportUtilitiesMix', getReportUtilitiesMix);

});

var $modalViewReportDollars;
var $modalViewReportSoles;
var $modalViewIncomeExpenseDollars;
var $modalViewIncomeExpenseSoles;
var $modalViewIncomeExpenseMix;
var $modalViewUtilitiesDollars;
var $modalViewUtilitiesSoles;
var $modalViewUtilitiesMix;

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

function viewReportIncomeExpenseDollars() {
    event.preventDefault();

    $('#expenses-income-view-dollars').html('');

    $modalViewIncomeExpenseDollars.modal('show');
}

function getReportIncomeExpenseDollars() {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var date_start = moment($('#startIncomeExpenseDollars').val(),'DD/MM/YYYY').format('YYYY-MM-DD');
    var date_end = moment($('#endIncomeExpenseDollars').val(),'DD/MM/YYYY').format('YYYY-MM-DD');

    console.log($('#startIncomeExpenseDollars').val());
    console.log(date_start);
    console.log($('#endIncomeExpenseDollars').val());
    console.log(date_end);

    var expensesChart = $('#expenses-income-view-dollars');

    $.get( "/dashboard/report/chart/income/expense/view/" + date_start + "/" + date_end , function( data ) {
        console.log(data);

        var salesChart3  = new Chart(expensesChart, {
            data   : {
                labels  : data.monthsNames,
                datasets: [{
                    type                : 'line',
                    data                : data.income_dollars,
                    backgroundColor     : 'transparent',
                    borderColor         : '#007bff',
                    pointBorderColor    : '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill                : false
                    // pointHoverBackgroundColor: '#007bff',
                    // pointHoverBorderColor    : '#007bff'
                },
                    {
                        type                : 'line',
                        data                : data.expense_dollars,
                        backgroundColor     : 'tansparent',
                        borderColor         : '#ced4da',
                        pointBorderColor    : '#ced4da',
                        pointBackgroundColor: '#ced4da',
                        fill                : false
                        // pointHoverBackgroundColor: '#ced4da',
                        // pointHoverBorderColor    : '#ced4da'
                    }]
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
                            beginAtZero : true,
                            suggestedMax: 200
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

        $('#balance_general_view_dollars').html('$ '+data.percentage_dollars);
        if ( parseFloat(data.percentage_dollars) < 0 )
        {
            $('#arrow_balance_general_view_dollars').removeClass('fas fa-arrow-up');
            $('#arrow_balance_general_view_dollars').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_balance_general_view_dollars').removeClass('fas fa-arrow-down');
            $('#arrow_balance_general_view_dollars').addClass('fas fa-arrow-up');
        }
    });
}

function viewReportIncomeExpenseSoles() {
    event.preventDefault();

    $('#expenses-income-view-soles').html('');

    $modalViewIncomeExpenseSoles.modal('show');
}

function getReportIncomeExpenseSoles() {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var date_start = moment($('#startIncomeExpenseSoles').val(),'DD/MM/YYYY').format('YYYY-MM-DD');
    var date_end = moment($('#endIncomeExpenseSoles').val(),'DD/MM/YYYY').format('YYYY-MM-DD');

    console.log($('#startIncomeExpenseSoles').val());
    console.log(date_start);
    console.log($('#endIncomeExpenseSoles').val());
    console.log(date_end);

    var expensesChart = $('#expenses-income-view-soles');

    $.get( "/dashboard/report/chart/income/expense/view/" + date_start + "/" + date_end , function( data ) {
        console.log(data);

        var salesChart3  = new Chart(expensesChart, {
            data   : {
                labels  : data.monthsNames,
                datasets: [{
                    type                : 'line',
                    data                : data.income_soles,
                    backgroundColor     : 'transparent',
                    borderColor         : '#007bff',
                    pointBorderColor    : '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill                : false
                    // pointHoverBackgroundColor: '#007bff',
                    // pointHoverBorderColor    : '#007bff'
                },
                    {
                        type                : 'line',
                        data                : data.expense_soles,
                        backgroundColor     : 'tansparent',
                        borderColor         : '#ced4da',
                        pointBorderColor    : '#ced4da',
                        pointBackgroundColor: '#ced4da',
                        fill                : false
                        // pointHoverBackgroundColor: '#ced4da',
                        // pointHoverBorderColor    : '#ced4da'
                    }]
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
                            beginAtZero : true,
                            suggestedMax: 200
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

        $('#balance_general_view_soles').html('S/. '+data.percentage_soles);
        if ( parseFloat(data.percentage_soles) < 0 )
        {
            $('#arrow_balance_general_view_soles').removeClass('fas fa-arrow-up');
            $('#arrow_balance_general_view_soles').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_balance_general_view_soles').removeClass('fas fa-arrow-down');
            $('#arrow_balance_general_view_soles').addClass('fas fa-arrow-up');
        }
    });
}

function viewReportIncomeExpenseMix() {
    event.preventDefault();

    $('#expenses-income-view-mix').html('');

    $modalViewIncomeExpenseMix.modal('show');
}

function getReportIncomeExpenseMix() {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var date_start = moment($('#startIncomeExpenseMix').val(),'DD/MM/YYYY').format('YYYY-MM-DD');
    var date_end = moment($('#endIncomeExpenseMix').val(),'DD/MM/YYYY').format('YYYY-MM-DD');

    console.log($('#startIncomeExpenseMix').val());
    console.log(date_start);
    console.log($('#endIncomeExpenseMix').val());
    console.log(date_end);

    var expensesChart = $('#expenses-income-view-mix');

    $.get( "/dashboard/report/chart/income/expense/view/" + date_start + "/" + date_end , function( data ) {
        console.log(data);

        var salesChart3  = new Chart(expensesChart, {
            data   : {
                labels  : data.monthsNames,
                datasets: [{
                    type                : 'line',
                    data                : data.mix_income,
                    backgroundColor     : 'transparent',
                    borderColor         : '#007bff',
                    pointBorderColor    : '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill                : false
                    // pointHoverBackgroundColor: '#007bff',
                    // pointHoverBorderColor    : '#007bff'
                },
                    {
                        type                : 'line',
                        data                : data.mix_expense,
                        backgroundColor     : 'tansparent',
                        borderColor         : '#ced4da',
                        pointBorderColor    : '#ced4da',
                        pointBackgroundColor: '#ced4da',
                        fill                : false
                        // pointHoverBackgroundColor: '#ced4da',
                        // pointHoverBorderColor    : '#ced4da'
                    }]
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
                            beginAtZero : true,
                            suggestedMax: 200
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

        $('#balance_general_view_mix').html('S/. '+data.percentage_mix);
        if ( parseFloat(data.percentage_mix) < 0 )
        {
            $('#arrow_balance_general_view_mix').removeClass('fas fa-arrow-up');
            $('#arrow_balance_general_view_mix').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_balance_general_view_mix').removeClass('fas fa-arrow-down');
            $('#arrow_balance_general_view_mix').addClass('fas fa-arrow-up');
        }
    });
}

function viewReportUtilitiesDollars() {
    event.preventDefault();

    $('#utilities-view-dollars').html('');

    $modalViewUtilitiesDollars.modal('show');
}

function getReportUtilitiesDollars() {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var date_start = moment($('#startUtilitiesDollars').val(),'DD/MM/YYYY').format('YYYY-MM-DD');
    var date_end = moment($('#endUtilitiesDollars').val(),'DD/MM/YYYY').format('YYYY-MM-DD');

    console.log($('#startUtilitiesDollars').val());
    console.log(date_start);
    console.log($('#endUtilitiesDollars').val());
    console.log(date_end);

    var expensesChart = $('#utilities-view-dollars');

    $.get( "/dashboard/report/chart/utilities/view/" + date_start + "/" + date_end , function( data ) {
        console.log(data);

        var salesChart3  = new Chart(expensesChart, {
            data   : {
                labels  : data.monthsNames,
                datasets: [{
                    type                : 'line',
                    data                : data.utilities_dollars,
                    backgroundColor     : 'transparent',
                    borderColor         : '#007bff',
                    pointBorderColor    : '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill                : false
                    // pointHoverBackgroundColor: '#007bff',
                    // pointHoverBorderColor    : '#007bff'
                }]
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
                            beginAtZero : true,
                            suggestedMax: 200
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

        $('#utilities_view_dollars').html('$ '+data.sum_utilities_dollars);
        if ( parseFloat(data.sum_utilities_dollars) < 0 )
        {
            $('#arrow_utilities_view_dollars').removeClass('fas fa-arrow-up');
            $('#arrow_utilities_view_dollars').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_utilities_view_dollars').removeClass('fas fa-arrow-down');
            $('#arrow_utilities_view_dollars').addClass('fas fa-arrow-up');
        }
    });
}

function viewReportUtilitiesSoles() {
    event.preventDefault();

    $('#utilities-view-soles').html('');

    $modalViewUtilitiesSoles.modal('show');
}

function getReportUtilitiesSoles() {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var date_start = moment($('#startUtilitiesSoles').val(),'DD/MM/YYYY').format('YYYY-MM-DD');
    var date_end = moment($('#endUtilitiesSoles').val(),'DD/MM/YYYY').format('YYYY-MM-DD');

    console.log($('#startUtilitiesSoles').val());
    console.log(date_start);
    console.log($('#endUtilitiesSoles').val());
    console.log(date_end);

    var expensesChart = $('#utilities-view-soles');

    $.get( "/dashboard/report/chart/utilities/view/" + date_start + "/" + date_end , function( data ) {
        console.log(data);

        var salesChart3  = new Chart(expensesChart, {
            data   : {
                labels  : data.monthsNames,
                datasets: [
                    {
                        type                : 'line',
                        data                : data.utilities_soles,
                        backgroundColor     : 'tansparent',
                        borderColor         : '#ced4da',
                        pointBorderColor    : '#ced4da',
                        pointBackgroundColor: '#ced4da',
                        fill                : false
                        // pointHoverBackgroundColor: '#ced4da',
                        // pointHoverBorderColor    : '#ced4da'
                    }]
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
                            beginAtZero : true,
                            suggestedMax: 200
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

        $('#utilities_view_soles').html('S/. '+data.sum_utilities_soles);
        if ( parseFloat(data.sum_utilities_soles) < 0 )
        {
            $('#arrow_utilities_view_soles').removeClass('fas fa-arrow-up');
            $('#arrow_utilities_view_soles').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_utilities_view_soles').removeClass('fas fa-arrow-down');
            $('#arrow_utilities_view_soles').addClass('fas fa-arrow-up');
        }
    });
}

function viewReportUtilitiesMix() {
    event.preventDefault();

    $('#utilities-view-mix').html('');

    $modalViewUtilitiesMix.modal('show');
}

function getReportUtilitiesMix() {
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var date_start = moment($('#startUtilitiesMix').val(),'DD/MM/YYYY').format('YYYY-MM-DD');
    var date_end = moment($('#endUtilitiesMix').val(),'DD/MM/YYYY').format('YYYY-MM-DD');

    console.log($('#startUtilitiesMix').val());
    console.log(date_start);
    console.log($('#endUtilitiesMix').val());
    console.log(date_end);

    var expensesChart = $('#utilities-view-mix');

    $.get( "/dashboard/report/chart/utilities/view/" + date_start + "/" + date_end , function( data ) {
        console.log(data);

        var salesChart3  = new Chart(expensesChart, {
            data   : {
                labels  : data.monthsNames,
                datasets: [{
                    type                : 'line',
                    data                : data.utilities_mix,
                    backgroundColor     : 'transparent',
                    borderColor         : '#007bff',
                    pointBorderColor    : '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill                : false
                    // pointHoverBackgroundColor: '#007bff',
                    // pointHoverBorderColor    : '#007bff'
                }]
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
                            beginAtZero : true,
                            suggestedMax: 200
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

        $('#utilities_view_mix').html('$ '+data.sum_utilities_mix);
        if ( parseFloat(data.sum_utilities_mix) < 0 )
        {
            $('#arrow_utilities_view_mix').removeClass('fas fa-arrow-up');
            $('#arrow_utilities_view_mix').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_utilities_view_mix').removeClass('fas fa-arrow-down');
            $('#arrow_utilities_view_mix').addClass('fas fa-arrow-up');
        }
    });
}