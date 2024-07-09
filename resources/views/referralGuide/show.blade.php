@extends('layouts.appAdmin2')

@section('openReferralGuide')
    menu-open
@endsection

@section('activeReferralGuide')
    active
@endsection

@section('activeListReferralGuide')
    active
@endsection

@section('title')
    Guías de Remisión
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">
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
    <h1 class="page-title">Guías de Remisión</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Guia de remisión {{ $arrayGuide[0]['code'] }}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('referral.guide.index') }}"><i class="fa fa-users"></i> Guía de Remisión</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Visualización</li>
    </ol>
@endsection

@section('content')

    <div class="form-group row">
        <div class="col-md-6">
            <div class="col-md-12" id="sandbox-container">
                <label for="date_transfer" class="col-md-12 col-form-label">Fecha de Traslado <span class="right badge badge-danger">(*)</span></label>
                <div class="col-md-12">
                    <input type="text" class="form-control" value="{{ $arrayGuide[0]['date_transfer'] }}" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <label for="reason_id" class="col-md-12 col-form-label">Motivo de Traslado <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $arrayGuide[0]['reason'] }}" readonly>

                </div>
            </div>
            <div class="col-md-12 mt-2">
                <label for="destination" class="col-md-8 col-form-label">Destinatario <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $arrayGuide[0]['destinatario'] }}" readonly>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="col-md-12">
                <label for="puntoLlegada" class="col-md-12 col-form-label">Punto de llegada <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <textarea id="puntoLlegada" class="form-control" rows="3" readonly>{{ $arrayGuide[0]['punto_llegada'] }}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <label for="document" class="col-md-12 col-form-label">RUC/DNI <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" value="{{ $arrayGuide[0]['documento'] }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                <h3 class="card-title">Bienes por transportar</h3>

                <div class="card-tools" style="margin-left: auto;">
                    <button type="button" class="btn btn-tool float-right" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height: 300px;">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descripción</th>
                                        <th>Unidad de medida</th>
                                        <th>Cantidad</th>
                                    </tr>
                                    </thead>
                                    <tbody id="body-rows">
                                    @foreach ($arrayGuide[0]['details'] as $detail)
                                        <tr>
                                            <td data-code>{{ $detail['code'] }}</td>
                                            <td data-description>{{ $detail['description'] }}</td>
                                            <td data-unit>{{ $detail['unit'] }}</td>
                                            <td data-quantity>{{ $detail['quantity'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <div class="col-md-6">
        <h5>Datos del Vehículo</h5>
        <label for="placa" class="col-md-6 col-form-label">Placa <span class="right badge badge-danger">(*)</span></label>
        <div class="col-sm-6">
            <input type="text" value="{{ $arrayGuide[0]['vehiculo'] }}" class="form-control" readonly>
        </div>
    </div>

    <div class="col-md-6">
        <h5>Datos del Conductor</h5>
        <label for="driver" class="col-md-6 col-form-label">Conductor <span class="right badge badge-danger">(*)</span></label>
        <div class="col-sm-10">
            <input type="text" value="{{ $arrayGuide[0]['driver'] }}" class="form-control" readonly>
        </div>
        <label for="driver_licence" class="col-md-6 col-form-label">Licencia de conducir <span class="right badge badge-danger">(*)</span></label>
        <div class="col-sm-6">
            <input type="text" value="{{ $arrayGuide[0]['driver_licence'] }}" class="form-control" readonly>
        </div>
        <label for="responsible_id" class="col-md-6 col-form-label">Responsable <span class="right badge badge-danger">(*)</span></label>
        <div class="col-sm-6">
            <input type="text" value="{{ $arrayGuide[0]['responsible'] }}" class="form-control" readonly>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('referral.guide.index') }}" class="btn btn-outline-secondary">Regresar al listado</a>
    </div>
    <!-- /.card-footer -->
@endsection

@section('plugins')
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(function () {

        })
    </script>

@endsection
