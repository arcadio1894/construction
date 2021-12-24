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
});
