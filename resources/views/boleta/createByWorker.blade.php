@extends('layouts.appAdmin2')

@section('openAttendance')
    menu-open
@endsection

@section('activeAttendance')
    active
@endsection

@section('activeReportTotalHours')
    active
@endsection

@section('title')
    Boletas de pago
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">
    <!-- VDialog -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/vdialog/css/vdialog.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }

        nav > .nav.nav-tabs{
            border: none;
            color:#fff;
            background:#001028;
            border-radius:0;
        }
        nav > div a.nav-item.nav-link
        {
            border: none;
            color:#fff;
            background:#001028;
            border-radius:0;
        }

        nav > div a.nav-item.nav-link.active:after
        {
            content: "";
            position: relative;
            bottom: -60px;
            left: -10%;
            border: 15px solid transparent;
            border-top-color: #fcbc23;
        }
        .tab-content{
            background: #fdfdfd;
            line-height: 25px;
            border: 1px solid #ddd;
            border-top:5px solid #fcbc23;
            border-bottom:5px solid #fcbc23;
            padding:30px 25px;
        }

        nav > div a.nav-item.nav-link:hover,
        nav > div a.nav-item.nav-link:focus,
        nav > div a.nav-item.nav-link.active
        {
            border: none;
            background: #fcbc23;
            color:#000000;
            border-radius:0;
            transition:background 0.20s linear;
        }
        .table {
            border-radius: 0.2rem;
            width: 100%;
            padding-bottom: 1rem;
            color: #212529;
            margin-bottom: 0;
        }
        .table td {
            white-space: nowrap;
        }
        .table-wrapper {
            max-height: 500px;
            overflow: auto;
            width: 100%;
        }
        table,
        thead,
        tr,
        tbody,
        th,
        td {
            text-align: center;
        }

        .table td {
            text-align: center;
        }
        .datepicker {
            z-index: 10000 !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Generar Boletas de Pago</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Boletas de pago</h5>
    <a href="#" class="btn btn-outline-primary btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de boletas</a>&nbsp;

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Asistencias</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Total Horas</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="worker">Trabajador <span class="right badge badge-danger">(*)</span></label>
                <select id="worker" name="worker" class="form-control select2" style="width: 100%;">
                    <option></option>
                    @foreach( $workers as $worker )
                        <option value="{{ $worker->id }}">{{ $worker->first_name .' '.$worker->last_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <label for="type">Tipo de boleta <span class="right badge badge-danger">(*)</span></label>

            <select id="type" name="type" class="form-control select2" style="width: 100%;">
                <option></option>
                @foreach( $types as $type )
                    <option value="{{ $type['id'] }}" {{ ($type['id'] == 1) ? 'selected':'' }}>{{ $type['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2" id="cboYears">
            <label for="year">Año <span class="right badge badge-danger">(*)</span></label>

            <select id="year" name="year" class="form-control select2" style="width: 100%;">
                <option></option>
                @foreach( $years as $year )
                    <option value="{{ $year->year }}">{{ $year->year}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2" id="cboMonths">
            <label for="month">Mes <span class="right badge badge-danger">(*)</span></label>

            <select id="month" name="month" class="form-control select2" style="width: 100%;">
                <option></option>

            </select>
        </div>

        <div class="col-md-2" id="cboWeeks">
            <label for="week">Semana <span class="right badge badge-danger">(*)</span></label>

            <select id="week" name="week" class="form-control select2" style="width: 100%;">
                <option></option>

            </select>
        </div>

        <div class="col-md-1">
            <label for="btn-outputs">&nbsp;</label><br>
            <button type="button" id="btn-generate" class="btn  btn-outline-success btn-block"> <i class="fas fa-arrow-circle-right"></i></button>
        </div>

    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">REPORTE DE HORAS TOTALES</h3>

                    <div class="card-tools">

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="hours-total">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="table-responsive table-wrapper">
                                    <table class="table table-hover table-bordered table-sm table-striped">
                                        <thead class="sticky-top">
                                        <tr>
                                            <th style="background-color:#001028; color: #ffffff;">SEM.</th>
                                            <th style="background-color:#001028; color: #ffffff;">FECHA</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. ORD.</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. 25%</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. 35%</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. 100%</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. ESP</th>
                                        </tr>
                                        </thead>
                                        <tbody id="body-totalHours">


                                        </tbody>

                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">RESUMEN DE HORAS TOTALES</h3>

                    <div class="card-tools">

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="summary-hours">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="table-responsive table-wrapper">
                                    <table class="table table-hover table-bordered table-sm table-striped">
                                        <thead class="sticky-top">
                                        <tr>
                                            <th style="background-color:#001028; color: #ffffff;">SEM.</th>
                                            <th style="background-color:#001028; color: #ffffff;">MES</th>
                                            <th style="background-color:#001028; color: #ffffff;">FECHA</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. ORD.</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. 25%</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. 35%</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. 100%</th>
                                            <th style="background-color:#001028; color: #ffffff;">H. ESP</th>
                                        </tr>
                                        </thead>
                                        <tbody id="body-summaryHours">

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <template id="template-totalHours">
        <tr data-color>
            <td data-week></td>
            <td data-date></td>
            <td data-h_ord></td>
            <td data-h_25></td>
            <td data-h_35></td>
            <td data-h_100></td>
            <td data-h_esp></td>
        </tr>
    </template>

    <template id="template-summaryHours">
        <tr data-color>
            <td data-week></td>
            <td data-month></td>
            <td data-date></td>
            <td data-h_ord></td>
            <td data-h_25></td>
            <td data-h_35></td>
            <td data-h_100></td>
            <td data-h_esp></td>
        </tr>

    </template>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <!-- Vdialog -->
    <script src="{{ asset('admin/plugins/vdialog/js/lib/vdialog.js') }}"></script>

    <script src="{{asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            $('#worker').select2({
                placeholder: "Trabajador",
            });
            $('#type').select2({
                placeholder: "Tipo",
            });
            $('#year').select2({
                placeholder: "Año",
            });
            $('#month').select2({
                placeholder: "Mes",
            });
            $('#week').select2({
                placeholder: "Semana",
            });
        })
    </script>
    <script src="{{ asset('js/boleta/createByWorker.js') }}"></script>
@endsection




