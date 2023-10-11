@extends('layouts.appAdmin2')

@section('openAttendance')
    menu-open
@endsection

@section('activeAttendance')
    active
@endsection

@section('activeReportTotalPaysAccounts')
    active
@endsection

@section('title')
    Total a Pagar
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
    <h1 class="page-title">Reporte de Total a Pagar</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Total a Pagar</h5>
    <a href="{{ route('assistance.index') }}" class="btn btn-outline-primary btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Regresar al calendario</a>&nbsp;

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Asistencias</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Total a Pagar</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="year">Seleccione un año <span class="right badge badge-danger">(*)</span></label>
                <select id="year" name="year" class="form-control select2" style="width: 100%;">
                    <option></option>
                    @foreach( $years as $year )
                        <option value="{{ $year->year }}" {{ ($year->year == $currentYear) ? 'selected':'' }}>{{ $year->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3" id="sandbox-container">
            <label for="weekStart">Semana Inicial <span class="right badge badge-danger">(*)</span></label>

            <select id="weekStart" name="weekStart" class="form-control select2" style="width: 100%;">
                <option></option>
                @for( $i=0; $i<count($weeks); $i++ )
                    <option value="{{ $weeks[$i]['week'] }}" {{ ($weeks[$i]['week'] == $currentWeek) ? 'selected':'' }}>{{ "Sem ".$weeks[$i]['week'] . "  |  Del " . $weeks[$i]['dateStart'] ." - al ".$weeks[$i]['dateEnd'] }}</option>
                @endfor
            </select>
        </div>

        <div class="col-md-3" id="sandbox-container">
            <label for="weekEnd">Semana Final <span class="right badge badge-danger">(*)</span></label>

            <select id="weekEnd" name="weekEnd" class="form-control select2" style="width: 100%;">
                <option></option>
                @for( $i=0; $i<count($weeks); $i++ )
                    <option value="{{ $weeks[$i]['week'] }}" {{ ($weeks[$i]['week'] == $currentWeek) ? 'selected':'' }}>{{ "Sem ".$weeks[$i]['week'] . "  |  Del " . $weeks[$i]['dateStart'] ." - al ".$weeks[$i]['dateEnd'] }}</option>
                @endfor
            </select>
        </div>

        <div class="col-md-3">
            <label for="btn-outputs">&nbsp;</label><br>
            <button type="button" id="btn-pays" class="btn  btn-outline-success btn-block"> Buscar</button>
        </div>

    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">REPORTE DE TOTAL A PAGAR</h3>

                    <div class="card-tools">

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="total-pays-load">
                    <div class="row" id="total-pays">

                    </div>

                </div>
            </div>
        </div>

    </div>

    <template id="template-totalPays">
        <div class="col-lg-6">
            <div class="card card-orange border-warning">
        <div class="card-header border-0">
            <h3 class="card-title" data-semana>Semana</h3>

        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Trabajador</th>
                    <th>Cuentas</th>
                    <th>Monto</th>
                </tr>
                </thead>
                <tbody data-pays>
                {{--<tr>
                    <td data-codigo>Some Product</td>
                    <td data-trabajador>$13 USD</td>
                    <td data-monto>12,000 Sold</td>
                </tr>--}}
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </template>

    <template id="template-pays">
        <tr>
            <td data-codigo>Some Product</td>
            <td data-trabajador>$13 USD</td>
            <td data-cuentas>$13 USD</td>
            <td data-monto>12,000 Sold</td>
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
            $('#year').select2({
                placeholder: "Seleccione año",
            });
            $('#weekStart').select2({
                placeholder: "Semana inicial",
            });
            $('#weekEnd').select2({
                placeholder: "Semana final",
            });
        })
    </script>
    <script src="{{ asset('js/assistance/totalPaysAccounts.js') }}"></script>
@endsection




