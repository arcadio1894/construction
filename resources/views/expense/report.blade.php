@extends('layouts.appAdmin2')

@section('openDiscountContribution')
    menu-open
@endsection

@section('activeDiscountContribution')
    active
@endsection

@section('openExpense')
    menu-open
@endsection

@section('activeReportExpense')
    active
@endsection

@section('title')
    Rendición de Gastos
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

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
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Generar Reporte de Rendición de Gastos</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Reporte de rendición de gastos</h5>
    <a href="{{ route('expense.index') }}" class="btn btn-outline-primary btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de Rendición de Gastos</a>&nbsp;

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('expense.index') }}"><i class="fa fa-archive"></i> Rendición de gastos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Reporte de Rendición de gastos</li>
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
                    <option value="0">TODOS</option>
                    @foreach( $workers as $worker )
                        <option value="{{ $worker->id }}">{{ $worker->first_name .' '.$worker->last_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <label for="type">Tipo de reporte <span class="right badge badge-danger">(*)</span></label>

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
    <div id="report-expense" style="display: none">

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Reporte de gastos</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                    <a id="btn-download" class="btn btn-outline-success btn-sm float-left" > <i class="fas fa-file-excel fa-spin font-20 mr-2"></i>  Descargar Excel</a>&nbsp;

                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive" >
                    <table id="myTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Trabajador</th>
                            <th>Fecha</th>
                            <th>Tipo de gasto</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>

    </div>

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
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
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
    <script src="{{ asset('js/expense/report.js') }}"></script>
@endsection




