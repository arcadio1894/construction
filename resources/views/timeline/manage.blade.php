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
    Gestionar Cronograma
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Control de Horas del día {{ $timeline->date->format('d/m/Y') }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Gestionar Cronograma</h5>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('index.timelines') }}"><i class="fa fa-archive"></i> Cronogramas</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">DATOS GENERALES</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="descriptionQuote">Descripción general de cotización </label>
                            <input type="text" id="descriptionQuote" onkeyup="mayus(this);" name="code_description" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="description">Código de cotización </label>
                            <input type="text" id="codeQuote" readonly value="{{ $timeline->id }}" onkeyup="mayus(this);" name="code_quote" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-4" id="sandbox-container">
                            <label for="date_quote">Fecha de cotización </label>
                            <div class="input-daterange" id="datepicker">
                                <input type="text" class="form-control form-control-sm date-range-filter" id="date_quote" name="date_quote">
                            </div>
                        </div>
                        <div class="col-md-4" id="sandbox-container">
                            <label for="date_end">Válido hasta </label>
                            <div class="input-daterange" id="datepicker2">
                                <input type="text" class="form-control form-control-sm date-range-filter" id="date_validate" name="date_validate">
                            </div>
                        </div>

                        {{--@hasanyrole('logistic|admin|principal')
                        <div class="col-md-4">
                            <label for="paymentQuote">Forma de pago </label>
                            --}}{{--<input type="hidden" onkeyup="mayus(this);" name="way_to_pay" class="form-control form-control-sm">--}}{{--
                            <select id="paymentQuote" name="payment_deadline" class="form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                @foreach( $paymentDeadlines as $paymentDeadline )
                                    <option value="{{ $paymentDeadline->id }}">{{ $paymentDeadline->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endhasanyrole--}}
                        <div class="col-md-4">
                            <label for="description">Tiempo de entrega </label>
                            <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm">
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">ACTIVIDADES</h3>

                    <div class="card-tools">
                        <button id="newActivity" class="btn btn-xs btn-warning btn-sm float-left" > <i class="far fa-clock"></i> Agregar Actividad </button>

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title"></h3>

                                <div class="card-tools">
                                    <button type="button" data-imagedelete class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Quitar" ><i class="fas fa-trash"></i></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                </div>
                                <!-- /.card-tools -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                    <textarea class="form-control" data-img name="descplanos[]" rows="2" placeholder="Enter ..."></textarea>

                                </div>
                                <div class="form-group">
                                    <label for="description">Orden presentación <span class="right badge badge-danger">(*)</span></label>
                                    <input type="number" name="orderplanos[]" step="1" min="1" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label for="description">Imagen <span class="right badge badge-danger">(*)</span></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="planos[]" accept="image/*" class="form-control" onchange="previewFile(this)">
                                        </div>
                                    </div>
                                    <img height="100px" width="100%" />

                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
@endsection

@section('scripts')
    <script src="{{ asset('js/timeline/manage.js') }}"></script>
@endsection
