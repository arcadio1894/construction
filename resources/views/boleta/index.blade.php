@extends('layouts.appAdmin2')

@section('openPaySlips')
    menu-open
@endsection

@section('activePaySlips')
    active
@endsection

@section('activeListPaySlip')
    active
@endsection

@section('title')
    Boletas
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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

@section('page-title')
    <h5 class="card-title">Listado de los colaboradores</h5>

    <button id="btn-generate" class="btn btn-outline-primary btn-sm float-right " > <i class="fas fa-redo-alt"></i> Generar boletas </button>
    <button id="btn-download" class="btn btn-outline-success btn-sm float-right mr-1" > <i class="fa fa-file-excel font-20"></i> Descargar Reporte Haberes </button>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('paySlip.index') }}"><i class="fa fa-archive"></i> Colaboradores</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <input type="hidden" id="currentYear" value="{{ $currentYear }}">
    <input type="hidden" id="currentMonth" value="{{ $currentMonth }}">

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm" id="dynamic-table">
            <thead>
            <tr>
                <th>Código</th>
                <th>DNI</th>
                <th>Apellidos y Nombres</th>
                <th>Cargo</th>
                <th>Área</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div id="modalHaberes" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Seleccionar los filtros para realizar la descarga</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="year" class="col-12 col-form-label">Año del Reporte <span class="right badge badge-danger">(*)</span></label>
                            <div class="col-sm-12">
                                <select id="year" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option value="">TODOS</option>
                                    {{--@for ($i=0; $i<count($arrayYears); $i++)
                                        <option value="{{ $arrayYears[$i] }}" {{ ($arrayYears[$i] == $currentYear) ? 'selected': '' }}>{{ $arrayYears[$i] }}</option>
                                    @endfor--}}
                                    @foreach( $arrayYears as $year )
                                        <option value="{{ $year->year }}" {{ ($year->year == $currentYear) ? 'selected': '' }}>{{ $year->year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="month" class="col-12 col-form-label">Mes del Reporte <span class="right badge badge-danger">(*)</span></label>
                            <select id="month" name="stateQuote" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($arrayMonths as $month)
                                    <option value="{{ $month['value'] }}" {{ ($month['value'] == $currentMonth) ? 'selected':'' }}>{{ $month['display'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-submitExport" class="btn btn-primary" >Descargar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" >Cancelar</button>
                </div>

            </div>
        </div>
    </div>

    <div id="modalGenerate" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Seleccionar los filtros para realizar la generación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Importante!</strong> Verifique que las horas y montos esten correctos porque la generación va a guardar y descargar el listado de boletas. <br>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <label for="yearG" class="col-12 col-form-label">Año <span class="right badge badge-danger">(*)</span></label>
                            <div class="col-sm-12">
                                <select id="yearG" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option value="">TODOS</option>
                                    {{--@for ($i=0; $i<count($arrayYears); $i++)
                                        <option value="{{ $arrayYears[$i] }}" {{ ($arrayYears[$i] == $currentYear) ? 'selected': '' }}>{{ $arrayYears[$i] }}</option>
                                    @endfor--}}
                                    @foreach( $arrayYears as $year )
                                        <option value="{{ $year->year }}" {{ ($year->year == $currentYear) ? 'selected': '' }}>{{ $year->year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="monthG" class="col-12 col-form-label">Mes <span class="right badge badge-danger">(*)</span></label>
                            <select id="monthG" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="weekG" class="col-12 col-form-label">Semana <span class="right badge badge-danger">(*)</span></label>
                            <select id="weekG" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-submitGenerate" class="btn btn-primary" >Generar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" >Cancelar</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('plugins')
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Vdialog -->
    <script src="{{ asset('admin/plugins/vdialog/js/lib/vdialog.js') }}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#year').select2({
                placeholder: "Selecione un año",
            });
            $('#month').select2({
                placeholder: "Selecione un mes",
                allowClear: true
            });

            $('#yearG').select2({
                placeholder: "Selecione un año",
                allowClear: true
            });
            $('#monthG').select2({
                placeholder: "Selecione un mes",
                allowClear: true
            });
            $('#weekG').select2({
                placeholder: "Selecione una semana",
                allowClear: true
            });
        })
    </script>
    <script src="{{ asset('js/boleta/index.js') }}?v={{ time() }}"></script>
@endsection