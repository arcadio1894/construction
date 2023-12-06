@extends('layouts.appAdmin2')

@section('openProforma')
    menu-open
@endsection

@section('activeProforma')
    active
@endsection

@section('activeListProforma')
    active
@endsection

@section('title')
    Pre Cotizaciones
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/typehead/typeahead.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.css') }}">
    <!-- Images -->
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
        .busqueda-avanzada {
            display: none;
        }

        #btnBusquedaAvanzada {
            display: inline-block;
            text-decoration: none;
            color: #007bff;
            border-bottom: 1px solid transparent;
            transition: border-bottom 0.3s ease;
        }
        #btnBusquedaAvanzada:hover {
            border-bottom: 2px solid #007bff;
        }
        .vertical-center {
            display: flex;
            align-items: center;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Cotizaciones</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Ver Pre Cotización</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('proforma.index') }}"><i class="fa fa-key"></i> Pre Cotizaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')

    <form id="formCreate" class="form-horizontal" data-url="{{ route('proforma.store') }}" enctype="multipart/form-data">
        @csrf
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
                                <input type="text" id="descriptionQuote" readonly onkeyup="mayus(this);" name="code_description" class="form-control form-control-sm" value="{{ $proforma->description_quote }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="description">Código de Pre Cotización </label>
                                <input type="text" id="codeQuote" readonly value="{{ $proforma->code }}" onkeyup="mayus(this);" name="code_quote" class="form-control form-control-sm">
                            </div>
                            @hasanyrole('logistic|admin|principal|quote_single')
                            <div class="col-md-4" id="sandbox-container">
                                <label for="date_quote">Fecha de cotización </label>
                                <div class="input-daterange" id="datepicker">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="date_quote" name="date_quote" value="{{ date('d/m/Y', strtotime($proforma->date_quote)) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4" id="sandbox-container">
                                <label for="date_end">Válido hasta </label>
                                <div class="input-daterange" id="datepicker2">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="date_validate" name="date_validate" value="{{ date('d/m/Y', strtotime($proforma->date_validate)) }}" readonly>
                                </div>
                            </div>
                            @endhasanyrole

                            @hasanyrole('logistic|admin|principal|quote_single')
                            <div class="col-md-4">
                                <label for="paymentProforma">Forma de pago </label>
                                <input type="text" id="paymentProforma" readonly value="{{ ($proforma->payment_deadline_id == null) ? 'No tiene':$proforma->deadline->description }}" onkeyup="mayus(this);" class="form-control form-control-sm">

                            </div>
                            @endhasanyrole
                            <div class="col-md-4">
                                <label for="timeQuote">Tiempo de entrega </label>
                                <input type="text" id="timeQuote" readonly onkeyup="mayus(this);" value="{{ $proforma->delivery_time }}" class="form-control form-control-sm">
                            </div>
                            {{--@hasanyrole('logistic|admin')--}}
                            <div class="col-md-4">
                                <label for="customer">Cliente </label>
                                <input type="text" id="customer" readonly onkeyup="mayus(this);" value="{{ ($proforma->customer_id == null) ? 'No tiene':$proforma->customer->business_name }}" class="form-control form-control-sm">

                            </div>
                            <div class="col-md-4">
                                <label for="contact">Contacto </label>
                                <input type="text" id="contact" readonly onkeyup="mayus(this);" value="{{ ($proforma->contact_id == null) ? 'No tiene':$proforma->contact->name }}" class="form-control form-control-sm">

                            </div>
                            <div class="col-md-8">
                                <label for="observations">Observaciones </label>
                                <div>{!! nl2br($proforma->observations) !!}</div>
                            </div>
                            {{--@endhasanyrole--}}
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        @can('showPrices_quote')
            <div class="row">
                <!-- accepted payments column -->
                <div class="col-sm-12">
                    <div class="card card-lightblue" >
                        <div class="card-header  border-transparent" >
                            <h3 class="card-title">Equipos de cotización</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th >Equipo</th>
                                        <th style="background-color: #92D050">Cantidad</th>
                                        <th style="background-color: #9BC2E6">Subtotal S/Igv</th>
                                        <th style="background-color: #9BC2E6">Utilidad</th>
                                        <th style="background-color: #9BC2E6">Gastos Varios</th>
                                        <th style="background-color: #9BC2E6">Precio Unit. S/Igv</th>
                                        <th style="background-color: #ED7D31">Total S/Igv</th>
                                    </tr>
                                    </thead>
                                    <tbody id="body-summary">
                                    @foreach( $proforma->equipments as $equipment )
                                        <tr>
                                            <td data-nEquipment>{{ $equipment->description }}</td>
                                            <td data-qEquipment>{{ $equipment->quantity }}</td>
                                            <td data-pEquipment>{{ round(($equipment->total/$equipment->quantity)/1.18, 2) }}</td>
                                            <td data-uEquipment>{{ $equipment->utility }}</td>
                                            <td data-rlEquipment>{{ $equipment->rent + $equipment->letter }}</td>
                                            <td data-uPEquipment>{{ round(($equipment->subtotal_percentage/1.18)/$equipment->quantity, 2) }}</td>
                                            <td data-tEquipment>{{ round($equipment->subtotal_percentage/1.18, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">

                </div>
                <!-- /.col -->
                <div class="col-6">
                    <p class="lead">Resumen de Cotización</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Total S/IGV: </th>
                                <td id="subtotal"> {{ $proforma->currency }} {{ round(($proforma->total_equipments/1.18), 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total C/IGV: </th>
                                <td id="total">{{ $proforma->currency }} {{ round($proforma->total_equipments, 2) }}</td>
                            </tr>
                            <tr>
                                <th style="width:50%">Total+Utilidad S/IGV: </th>
                                <td id="subtotal_utility">{{ $proforma->currency }} {{ round(($proforma->total_proforma)/1.18, 2) }}</td>
                            </tr>
                            <tr>
                                <th style="width:50%">Total+Utilidad C/IGV: </th>
                                <td id="total_utility">{{ $proforma->currency }} {{ round($proforma->total_proforma, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
        @endcan

        <div class="row">
            <div class="col-12">
                <a href="{{ route('proforma.index') }}" class="btn btn-outline-secondary">Regresar</a>
            </div>
        </div>
        <!-- /.card-footer -->
    </form>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
@endsection

@section('scripts')
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/lang/summernote-es-ES.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#customer_id').select2({
                placeholder: "Selecione cliente",
            });
            $('#contact_id').select2({
                placeholder: "Selecione contacto",
            });
            $('#paymentQuote').select2({
                placeholder: "Selecione forma de pago",
            });

            $('#category').select2({
                placeholder: "Seleccione categoria",
                allowClear: true
            });

            $('#date_quote').attr("value", moment().format('DD/MM/YYYY'));

            $('#date_validate').attr("value", moment().add(5, 'days').format('DD/MM/YYYY'));

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>

    <script src="{{ asset('js/proforma/create.js') }}"></script>
@endsection
