@extends('layouts.appAdmin2')

@section('openReferralGuide')
    menu-open
@endsection

@section('activeReferralGuide')
    active
@endsection

@section('activeCreateReferralGuide')
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
    <h5 class="card-title">Crear nueva guía de remisión {{ $code }}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('referral.guide.index') }}"><i class="fa fa-users"></i> Guía de Remisión</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')

    <div class="form-group row">
        <div class="col-md-6">
            <div class="col-md-12" id="sandbox-container">
                <label for="date_transfer" class="col-md-12 col-form-label">Fecha de Traslado <span class="right badge badge-danger">(*)</span></label>
                <div class="col-md-12 input-daterange" id="datepicker">
                    <input type="text" class="form-control date-range-filter" id="date_transfer" name="date_transfer">
                </div>
            </div>
            <div class="col-md-12">
                <label for="reason_id" class="col-md-12 col-form-label">Motivo de Traslado <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <select id="reason_id" class="form-control select2" name="reason_id" style="width: 100%;">
                        <option></option>
                        @foreach( $reasons as $reason )
                            <option value="{{ $reason->id }}">{{ $reason->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="row ml-1 mr-1">
                    <label for="destination" class="col-md-8 col-form-label">Destinatario <span class="right badge badge-danger">(*)</span></label>
                    <div class="col-md-4 mb-2">
                        <select id="destination" class="form-control select2" style="width: 100%;">
                            <option></option>
                            <option value="Cliente">Cliente</option>
                            <option value="Proveedor">Proveedor</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div id="div_customer_id" style="display: none;">
                        <select id="customer_id" class="form-control select2" name="customer_id" style="width: 100%">
                            <option></option>
                            @foreach( $customers as $customer )
                                <option value="{{ $customer->id }}" data-ruc="{{ $customer->RUC }}" data-address="{{ $customer->address }}">{{ $customer->business_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="div_supplier_id"  style="display: none;">
                        <select id="supplier_id" class="form-control select2" name="supplier_id" style="width: 100%">
                            <option></option>
                            @foreach( $suppliers as $supplier )
                                <option value="{{ $supplier->id }}"  data-ruc="{{ $supplier->RUC }}" data-address="{{ $supplier->address }}">{{ $supplier->business_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="div_receiver"  style="display: none;">
                        <input type="text" class="form-control" id="receiver" >
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="col-md-12">
                <label for="puntoLlegada" class="col-md-12 col-form-label">Punto de llegada <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <textarea id="puntoLlegada" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <label for="document" class="col-md-12 col-form-label">RUC/DNI <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="document">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">

                <div style="display: flex; align-items: center;">
                    <h3 class="card-title" style="margin-right: 10px; width: 300px;">Bienes por transportar</h3>
                    <select id="type" class="form-control form-control-sm select2" style="width: 100%;">
                        <option></option>
                        <option value="Materiales">Materiales</option>
                        <option value="Cotizaciones">Cotizaciones</option>
                    </select>
                </div>
                <div class="card-tools" style="margin-left: auto;">
                    <button type="button" class="btn btn-tool float-right" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="material_search">Buscar material <span class="right badge badge-danger">(*)</span></label>
                            <div id="div_material_id" style="display: none;">
                                <select id="material_id" class="form-control select2" name="material_id" style="width: 100%">
                                    <option></option>
                                    @foreach( $materials as $material )
                                        <option value="{{ $material->id }}" data-id="{{$material->id}}" data-code="{{ $material->code }}" data-description="{{ $material->full_name }}" data-unit="{{ ($material->unit_measure_id == null) ? "No tiene":$material->unitMeasure->description }}">{{ $material->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="div_quote_id"  style="display: none;">
                                <select id="quote_id" class="form-control select2" name="quote_id" style="width: 100%">
                                    <option></option>
                                    @foreach( $quotes as $quote )
                                        <option value="{{ $quote->id }}" data-id="{{$quote->id}}" data-code="{{ $quote->code }}" data-unit="UNIDAD" data-description="{{ $quote->description_quote }}" >{{ $quote->description_quote }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="quantity">Cantidad <span class="right badge badge-danger">(*)</span></label>
                            <input type="number" id="quantity" class="form-control">
                        </div>
                    </div>

                    {{--<div class="col-md-2">
                        <label for="btn-add"> &nbsp; </label>
                        <button type="button" id="btn-add" class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                    </div>--}}

                </div>

                <hr>

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
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody id="body-rows">

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
            <input type="text" id="placa" class="form-control">
        </div>
    </div>

    <div class="col-md-6">
        <h5>Datos del Conductor</h5>
        <label for="driver" class="col-md-6 col-form-label">Conductor <span class="right badge badge-danger">(*)</span></label>
        <div class="col-sm-10">
            <input type="text" id="driver" class="form-control">
        </div>
        <label for="driver_licence" class="col-md-6 col-form-label">Licencia de conducir <span class="right badge badge-danger">(*)</span></label>
        <div class="col-sm-6">
            <input type="text" id="driver_licence" class="form-control">
        </div>
        <label for="responsible_id" class="col-md-6 col-form-label">Responsable <span class="right badge badge-danger">(*)</span></label>
        <div class="col-sm-6">
            <select id="responsible_id" class="form-control select2" name="customer_id" style="width: 100%">
                <option></option>
                @foreach( $shippingManagers as $shippingManager )
                    <option value="{{ $shippingManager->id }}" >{{ $shippingManager->user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="text-center">
        <button type="button" id="btn-submit" data-url="{{ route('referral.guide.store') }}" class="btn btn-outline-success">Guardar</button>
        <a href="{{ route('referral.guide.index') }}" class="btn btn-outline-secondary">Regresar al listado</a>
    </div>
    <!-- /.card-footer -->
    <template id="template-row">
        <tr data-row_selected>
            <td data-code></td>
            <td data-description></td>
            <td data-unit></td>
            <td data-quantity></td>
            <td>
                <button type="button" data-delete="" data-type="" class="btn btn-danger"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    </template>
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
            $('#date_transfer').attr("value", moment().format('DD/MM/YYYY'));
            //Initialize Select2 Elements
            $('#reason_id').select2({
                placeholder: "Selecione motivo de traslado",
            });

            $('#destination').select2({
                placeholder: "Selecione Tipo",
                allowClear: true
            });

            $('#type').select2({
                placeholder: "Selecione Tipo",
                allowClear: true
            });

            $('#responsible_id').select2({
                placeholder: "Selecione responsable",
                allowClear: true
            });

            $('#sandbox-container .input-daterange').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                language: "es",
                multidate: false,
                autoclose: true,
                todayHighlight: true,
                defaultViewDate: moment().format('L')
            });

        })
    </script>
    <script src="{{ asset('js/referralGuide/create.js') }}?v={{ time() }}"></script>
@endsection
