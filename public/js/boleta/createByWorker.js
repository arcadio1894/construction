$(document).ready(function () {

    $selectType = $('#type');
    $selectYear = $('#year');
    $selectMonth = $('#month');
    $selectWeek = $('#week');

    $selectWorker = $('#worker');

    $selectType.change(function () {
        var type =  $selectType.val();
        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');
        $selectMonth.empty();
        $selectMonth.val('');
        $selectMonth.trigger('change');
        $selectYear.empty();
        $selectYear.val('');
        $selectYear.trigger('change');

        if ( type == 1 )
        {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $.get( "/dashboard/get/years/of/system/", function( data ) {
            $selectYear.append($("<option>", {
                value: '',
                text: ''
            }));
            for ( var i=0; i<data.length; i++ )
            {
                $selectYear.append($("<option>", {
                    value: data[i].year,
                    text: data[i].year
                }));
            }
        });

    });

    $selectYear.change(function () {
        var type =  $selectType.val();
        if ( type == 1 ) {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $selectMonth.empty();
        $selectMonth.val('');
        $selectMonth.trigger('change');
        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');

        let year =  $selectYear.val();
        console.log(year);
        if ( year != null || year != undefined )
        {
            $.get( "/dashboard/get/months/of/year/"+year, function( data ) {
                $selectMonth.append($("<option>", {
                    value: '',
                    text: ''
                }));
                for ( var i=0; i<data.length; i++ )
                {
                    $selectMonth.append($("<option>", {
                        value: data[i].month,
                        text: data[i].month_name
                    }));
                }
            });
        }

    });

    $selectMonth.change(function () {
        var type =  $selectType.val();
        if ( type == 1 ) {
            $('#cboWeeks').show();
        } else {
            $('#cboWeeks').hide();
        }

        $selectWeek.empty();
        $selectWeek.val('');
        $selectWeek.trigger('change');

        let year =  $selectYear.val();
        let month =  $selectMonth.val();

        console.log(year);
        console.log(month);

        if ( (year != null || year != undefined) && (month != null || month != undefined) )
        {
            $.get( "/dashboard/get/weeks/of/month/"+month+"/year/"+year, function( data ) {
                $selectWeek.append($("<option>", {
                    value: '',
                    text: ''
                }));
                for ( var i=0; i<data.length; i++ )
                {
                    $selectWeek.append($("<option>", {
                        value: data[i].week,
                        text: data[i].week
                    }));
                }
            });
        }

    });
    
    $('#btn-generate').on('click', generateBoletaWorker);
});

let $selectType;
let $selectYear;
let $selectMonth;
let $selectWeek;
let $selectWorker;

function generateBoletaWorker() {

    let worker = $selectWorker.val();
    let type = $selectType.val();
    let year = $selectYear.val();
    let month = $selectMonth.val();
    let week = $selectWeek.val();

    if ( type == 1 )
    {
        // Si es semanal
        var query = {
            worker: worker,
            type: type,
            year: year,
            month: month,
            week: week
        };

        $.get( "/dashboard/generate/boleta/worker?" + $.param(query), function( data ) {
            console.log( data );
        }).done(function(data) {
            console.log( data );
        }).fail(function(data) {
            console.log( data );
        });
    } else {
        // Si es mensual
        var query2 = {
            worker: worker,
            type: type,
            year: year,
            month: month,
            week: week
        };

        $.get( "/dashboard/generate/boleta/worker?" + $.param(query2), function( data ) {
            console.log( data );
        }).done(function(data) {
            console.log( data );
        }).fail(function(data) {
            console.log( data );
        });
    }
}
