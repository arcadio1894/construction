$(function () {
    'use strict';

    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode      = 'index';
    var intersect = true;

    var $salesChart = $('#sales-chart');
    var $salesChart2 = $('#sales-chart2');
    var $expensesChart = $('#expenses-income');
    var $expensesChart2 = $('#expenses-income2');
    var $expensesChart3 = $('#expenses-income3');
    var $utilitiesChart = $('#utilities_d');
    var $utilitiesChart2 = $('#utilities_s');
    var $utilitiesChart3 = $('#utilities_m');

    $.get( "/dashboard/report/chart/quote/raised", function( data ) {
        console.log(data);

        var salesChart  = new Chart($salesChart, {
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
        var salesChart2  = new Chart($salesChart2, {
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

        $('#total_dollars').html('$ '+data.sum_dollars);
        $('#total_soles').html('S/ '+data.sum_soles);
        $('#percentage_dollars').html(data.percentage_dollars + '%');
        $('#percentage_soles').html(data.percentage_soles + '%');
    });

    $.get( "/dashboard/report/chart/expense/income", function( data ) {
        console.log(data);

        // Dolares
        var exp_inc_chart  = new Chart($expensesChart, {
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
        // Soles
        var exp_inc_chart2  = new Chart($expensesChart2, {
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
        // Mixta
        var exp_inc_chart3  = new Chart($expensesChart3, {
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

        $('#balance_general_dollars').html('$ '+data.percentage_dollars);
        if ( parseFloat(data.percentage_dollars) < 0 )
        {
            $('#arrow_balance_general_dollars').removeClass('fas fa-arrow-up');
            $('#arrow_balance_general_dollars').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_balance_general_dollars').removeClass('fas fa-arrow-down');
            $('#arrow_balance_general_dollars').addClass('fas fa-arrow-up');
        }

        $('#balance_general_soles').html('S/. '+data.percentage_soles);
        if ( parseFloat(data.percentage_soles) < 0 )
        {
            $('#arrow_balance_general_soles').removeClass('fas fa-arrow-up');
            $('#arrow_balance_general_soles').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_balance_general_soles').removeClass('fas fa-arrow-down');
            $('#arrow_balance_general_soles').addClass('fas fa-arrow-up');
        }

        $('#balance_general_mix').html('$ '+data.percentage_mix);
        if ( parseFloat(data.percentage_mix) < 0 )
        {
            $('#arrow_balance_general_mix').removeClass('fas fa-arrow-up');
            $('#arrow_balance_general_mix').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_balance_general_mix').removeClass('fas fa-arrow-down');
            $('#arrow_balance_general_mix').addClass('fas fa-arrow-up');
        }


    });

    $.get( "/dashboard/report/chart/utilities", function( data ) {
        console.log(data);

        // Dolares
        var exp_uti_chart  = new Chart($utilitiesChart, {
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
        // Soles
        var exp_uti_chart2  = new Chart($utilitiesChart2, {
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
        // Mixta
        var exp_uti_chart3  = new Chart($utilitiesChart3, {
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

        $('#utilities_dollars').html('$ '+data.sum_utilities_dollars);
        if ( parseFloat(data.sum_utilities_dollars) < 0 )
        {
            $('#arrow_utilities_dollars').removeClass('fas fa-arrow-up');
            $('#arrow_utilities_dollars').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_utilities_dollars').removeClass('fas fa-arrow-down');
            $('#arrow_utilities_dollars').addClass('fas fa-arrow-up');
        }

        $('#utilities_soles').html('S/. '+data.sum_utilities_soles);
        if ( parseFloat(data.sum_utilities_soles) < 0 )
        {
            $('#arrow_utilities_soles').removeClass('fas fa-arrow-up');
            $('#arrow_utilities_soles').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_utilities_soles').removeClass('fas fa-arrow-down');
            $('#arrow_utilities_soles').addClass('fas fa-arrow-up');
        }

        $('#utilities_mix').html('$ '+data.sum_utilities_mix);
        if ( parseFloat(data.sum_utilities_mix) < 0 )
        {
            $('#arrow_utilities_mix').removeClass('fas fa-arrow-up');
            $('#arrow_utilities_mix').addClass('fas fa-arrow-down');
        } else {
            $('#arrow_utilities_mix').removeClass('fas fa-arrow-down');
            $('#arrow_utilities_mix').addClass('fas fa-arrow-up');
        }


    });
});
