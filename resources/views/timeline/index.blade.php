@extends('layouts.appAdmin2')

@section('openTimelines')
    menu-open
@endsection

@section('activeTimelines')
    active
@endsection

@section('activeShowTimelines')
    active
@endsection

@section('title')
    Cronogramas
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fullcalendar/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fullcalendar-daygrid/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fullcalendar-timegrid/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fullcalendar-bootstrap/main.min.css') }}">
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-users"></i> Cronogramas</li>
    </ol>
@endsection

@section('page-title')
    <h5 class="card-title">Calendario mensual</h5>
    @can('create_timeline')
    <button id="newTimeline" class="btn btn-outline-success btn-sm float-right" > <i class="far fa-clock"></i> Nuevo Cronograma para Mañana </button>
    @endcan
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <input type="hidden" id="events" value="{{ json_encode($events) }}">

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('plugins')
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/fullcalendar/main.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/fullcalendar-daygrid/main.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/fullcalendar-timegrid/main.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/fullcalendar-interaction/main.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/fullcalendar/locales-all.js') }}"></script>
@endsection

@section('scripts')
    <!-- Page specific script -->
    <script>
        $(function () {

            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex        : 1070,
                        revert        : true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    })

                })
            }

            ini_events($('#external-events div.external-event'))

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear()

            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendarInteraction.Draggable;

            var containerEl = document.getElementById('external-events');
            var checkbox = document.getElementById('drop-remove');
            var calendarEl = document.getElementById('calendar');

            // initialize the external events
            // -----------------------------------------------------------------

            /*new Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function(eventEl) {
                    console.log(eventEl);
                    return {
                        title: eventEl.innerText,
                        backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
                        borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
                        textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
                    };
                }
            });*/

            var events =  JSON.parse($('#events').val());
            console.log(events);
            var calendar = new Calendar(calendarEl, {
                plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
                header    : {
                    left  : 'prev,next today',
                    center: 'title',
                    right : ''
                },
                'themeSystem': 'bootstrap',
                locale: 'es',
                defaultView: 'dayGridMonth',
                weekNumbers: true,
                //Random default events
                dateClick: function(info) {
                    $.ajax({
                        url: "/dashboard/check/timeline/for/create/"+info.dateStr,
                        type: 'GET',
                        dataType: 'json',
                        success: function (json) {
                            
                            switch ( json.res ) {
                                case 1:
                                    // Si es 1 redireccionamos al manage
                                    toastr.success(json.message, 'Éxito',
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
                                        location.href = json.url;
                                    }, 2000 );
                                    break;
                                case 2:
                                    $.confirm({
                                        icon: 'far fa-clock',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'zoom',
                                        type: 'green',
                                        columnClass: 'medium',
                                        title: json.message,
                                        content: 'Se va a crear un cronograma con la fecha seleccionada.',
                                        buttons: {
                                            confirm: {
                                                text: 'CONFIRMAR',
                                                btnClass: 'btn-blue',
                                                action: function () {
                                                    $.ajax({
                                                        url: "/dashboard/get/timeline/forget/"+info.dateStr,
                                                        type: 'GET',
                                                        dataType: 'json',
                                                        success: function (json) {
                                                            toastr.success(json.message, 'Éxito',
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
                                                                location.href = json.url;
                                                            }, 2000 );

                                                        }
                                                    });
                                                    //$.alert('Your name is ' + name);
                                                }
                                            },
                                            cancel: {
                                                text: 'CANCELAR',
                                                action: function (e) {
                                                    $.alert("Cronograma no creado.");
                                                },
                                            },
                                        }
                                    });
                                    break;
                                case 3:
                                    toastr.success(json.message, 'Éxito',
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
                                        location.href = json.url;
                                    }, 2000 );
                                    break;
                                case 4:
                                    $.confirm({
                                        icon: 'far fa-clock',
                                        theme: 'modern',
                                        closeIcon: true,
                                        animation: 'zoom',
                                        type: 'green',
                                        columnClass: 'medium',
                                        title: json.message,
                                        content: 'Se va a crear un cronograma con la fecha seleccionada.',
                                        buttons: {
                                            confirm: {
                                                text: 'CONFIRMAR',
                                                btnClass: 'btn-blue',
                                                action: function () {
                                                    $.ajax({
                                                        url: "/dashboard/get/timeline/forget/"+info.dateStr,
                                                        type: 'GET',
                                                        dataType: 'json',
                                                        success: function (json) {
                                                            toastr.success(json.message, 'Éxito',
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
                                                                location.href = json.url;
                                                            }, 2000 );

                                                        }
                                                    });
                                                    //$.alert('Your name is ' + name);
                                                }
                                            },
                                            cancel: {
                                                text: 'CANCELAR',
                                                action: function (e) {
                                                    $.alert("Cronograma no creado.");
                                                },
                                            },
                                        }
                                    });
                                    break;
                                case 5:
                                    toastr.success(json.message, 'Éxito',
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
                                        location.href = json.url;
                                    }, 2000 );
                                    break;
                                case 6:
                                    toastr.error(json.message, 'Error',
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
                                    break;
                                case 7:
                                    toastr.error(json.message, 'Error',
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
                                    break;
                                default:
                                    toastr.error('Ocurrió un error inesperado.', 'Error',
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
                                    break;
                                // code block
                            }
                            
                            /*if ( json.error == 1 )
                            {
                                toastr.error(json.message, 'Error',
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

                            } else {
                                toastr.success(json.message, 'Éxito',
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
                                    location.href = json.url;
                                }, 2000 )
                            }*/

                        }
                    });
                    //alert('Date: ' + info.dateStr);
                    //alert('Resource ID: ' + info.resource.id);
                },
                events: events,
                /*events    : [
                    {
                        title          : '' ,
                        start          : new Date(y, m, 1),
                        backgroundColor: '#f56954', //red
                        borderColor    : '#f56954', //red
                        allDay         : true
                    },
                    {
                        title          : '' ,
                        start          : new Date(y, m, 2),
                        backgroundColor: '#f56954', //red
                        borderColor    : '#f56954', //red
                        allDay         : true
                    },
                    /!*{
                        title          : 'Long Event',
                        start          : new Date(y, m, d - 5),
                        end            : new Date(y, m, d - 2),
                        backgroundColor: '#f39c12', //yellow
                        borderColor    : '#f39c12' //yellow
                    },
                    {
                        title          : 'Meeting',
                        start          : new Date(y, m, d, 10, 30),
                        allDay         : false,
                        backgroundColor: '#0073b7', //Blue
                        borderColor    : '#0073b7' //Blue
                    },
                    {
                        title          : 'Lunch',
                        start          : new Date(y, m, d, 12, 0),
                        end            : new Date(y, m, d, 14, 0),
                        allDay         : false,
                        backgroundColor: '#00c0ef', //Info (aqua)
                        borderColor    : '#00c0ef' //Info (aqua)
                    },
                    {
                        title          : 'Birthday Party',
                        start          : new Date(y, m, d + 1, 19, 0),
                        end            : new Date(y, m, d + 1, 22, 30),
                        allDay         : false,
                        backgroundColor: '#00a65a', //Success (green)
                        borderColor    : '#00a65a' //Success (green)
                    },
                    {
                        title          : 'Click for Google',
                        start          : new Date(y, m, 28),
                        end            : new Date(y, m, 29),
                        url            : 'http://google.com/',
                        backgroundColor: '#3c8dbc', //Primary (light-blue)
                        borderColor    : '#3c8dbc' //Primary (light-blue)
                    }*!/
                ],*/
                editable  : false,
                droppable : false, // this allows things to be dropped onto the calendar !!!
                drop      : function(info) {
                    // is the "remove after drop" checkbox checked?
                    if (checkbox.checked) {
                        // if so, remove the element from the "Draggable Events" list
                        info.draggedEl.parentNode.removeChild(info.draggedEl);
                    }
                }
            });

            calendar.render();
            // $('#calendar').fullCalendar()

            /* ADDING EVENTS */
            var currColor = '#3c8dbc' //Red by default
            //Color chooser button
            var colorChooser = $('#color-chooser-btn')
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault()
                //Save color
                currColor = $(this).css('color')
                //Add color effect to button
                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color'    : currColor
                })
            })
            $('#add-new-event').click(function (e) {
                e.preventDefault()
                //Get value and make sure it is not null
                var val = $('#new-event').val()
                if (val.length == 0) {
                    return
                }

                //Create events
                var event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color'    : currColor,
                    'color'           : '#fff'
                }).addClass('external-event')
                event.html(val)
                $('#external-events').prepend(event)

                //Add draggable funtionality
                ini_events(event)

                //Remove event from text input
                $('#new-event').val('')
            })
        })
    </script>
    <script src="{{ asset('js/timeline/index.js') }}"></script>
@endsection