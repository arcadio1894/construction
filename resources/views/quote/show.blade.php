@extends('layouts.appAdmin2')

@section('openQuote')
    menu-open
@endsection

@section('activeQuote')
    active
@endsection

@section('activeListQuote')
    active
@endsection

@section('title')
    Cotizaciones
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

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Cotizaciones</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Ver cotización {{ $quote->code }}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('quote.index') }}"><i class="fa fa-key"></i> Cotizaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Visualizar</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Datos generales</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="descriptionQuote">Descripción general de cotización <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="descriptionQuote" onkeyup="mayus(this);" name="code_description" class="form-control form-control-sm" value="{{ $quote->description_quote }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="description">Código de cotización <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="codeQuote" onkeyup="mayus(this);" name="code_quote" class="form-control form-control-sm" value="{{ $quote->code }}" readonly>
                        </div>
                        <div class="col-md-4" id="sandbox-container">
                            <label for="date_quote">Fecha de cotización <span class="right badge badge-danger">(*)</span></label>
                            <div class="input-daterange" id="datepicker">
                                <input type="text" class="form-control form-control-sm date-range-filter" id="date_quote" name="date_quote" value="{{ date('d/m/Y', strtotime($quote->date_quote)) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4" id="sandbox-container">
                            <label for="date_end">Válido hasta <span class="right badge badge-danger">(*)</span></label>
                            <div class="input-daterange" id="datepicker2">
                                <input type="text" class="form-control form-control-sm date-range-filter" id="date_validate" name="date_validate" value="{{ date('d/m/Y', strtotime($quote->date_validate)) }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="description">Forma de pago <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="paymentQuote" onkeyup="mayus(this);" name="way_to_pay" class="form-control form-control-sm" value="{{ $quote->way_to_pay }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="description">Tiempo de entrega <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm" value="{{ $quote->delivery_time }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="customer_id">Cliente <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm" value="{{ ($quote->customer !== null) ? $quote->customer->business_name : 'No tiene cliente'}}" readonly>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row" id="body-equipment">
        @foreach( $quote->equipments as $equipment )
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">EQUIPOS</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="description">Cantidad de equipo <span class="right badge badge-danger">(*)</span></label>
                            <input type="number" data-quantityequipment class="form-control" placeholder="1" min="0" step="1" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                " value="{{ $equipment->quantity }}" readonly>
                        </div>
                        <div class="col-md-9">
                            <label for="description"> <span class="right badge badge-danger">Importante</span></label>
                            <p>Todos los costos se multiplicarán por esta cantidad. Ingrese cantidades para un equipo. </p>
                        </div>
                        <div class="col-md-12">
                            <label for="description">Descripción de equipo <span class="right badge badge-danger">(*)</span></label>
                            <textarea name="" data-descriptionequipment onkeyup="mayus(this);" cols="30" class="form-control" placeholder="Ingrese detalles ...." readonly>{{ $equipment->description }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="description">Detalles de equipo <span class="right badge badge-danger">(*)</span></label>
                            <textarea name="" data-detailequipment onkeyup="mayus(this);" cols="30" class="form-control" placeholder="Ingrese detalles ...." readonly>{{ $equipment->detail }}</textarea>
                        </div>
                    </div>

                    <div class="card card-cyan collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">MATERIALES</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Material</th>
                                        <th>Unidad</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Importe</th>
                                    </tr>
                                    </thead>
                                    <tbody data-bodyMaterials>
                                        @foreach( $equipment->materials as $material )
                                            <tr>
                                                <td data-code>{{ $material->material->code }}</td>
                                                <td data-description>{{ $material->material->full_description }}</td>
                                                <td data-unit>{{ $material->material->unitMeasure->name }}</td>
                                                <td data-quantity>{{ $material->percentage }}</td>
                                                <td data-price>{{ $material->price }}</td>
                                                <td data-total>{{ $material->total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card card-warning collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">CONSUMIBLES</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div data-bodyConsumable>
                                @foreach( $equipment->consumables as $consumable )
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $consumable->material->full_description }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $consumable->material->unitMeasure->name }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " value="{{ $consumable->quantity }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " value="{{ $consumable->price }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumableTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " value="{{ $consumable->price }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card card-gray collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">MANO DE OBRA</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div data-bodyMano>
                                @foreach( $equipment->workforces as $workforce )
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $workforce->description }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $workforce->unit }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                            " value="{{ $workforce->quantity }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                            " value="{{ $workforce->price }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" data-manoTotal min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                            " value="{{ $workforce->total }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="card card-lightblue collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">SERVICIO DE TORNO <span class="right badge badge-danger">(Opcional)</span></h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Descripción <span class="right badge badge-danger">(*)</span></label>
                                                <input type="text" onkeyup="mayus(this);" class="form-control">

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="quantity">Cantidad <span class="right badge badge-danger">(*)</span></label>
                                                <input type="number" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="price">Precio <span class="right badge badge-danger">(*)</span></label>
                                                <input type="number" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="btn-add"> &nbsp; </label>
                                            <button type="button" data-addTorno class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                        </div>

                                    </div>
                                    <hr>
                                    <div data-bodyTorno>
                                        @foreach( $equipment->turnstiles as $turnstile )
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $turnstile->description }}" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                            " value="{{ $turnstile->quantity }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                            " value="{{ $turnstile->price }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" data-manoTotal min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                            " value="{{ $turnstile->total }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        @endforeach
    </div>
    <!-- /.card-footer -->
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
                        <th style="width:50%">Subtotal: </th>
                        <td id="subtotal">S/. {{ $quote->total }}</td>
                        <input type="hidden" name="quote_total" id="quote_total" value="{{ $quote->total }}">
                        <input type="hidden" name="quote_subtotal_utility" id="quote_subtotal_utility" value="{{ $quote->subtotal_utility }}">
                        <input type="hidden" name="quote_subtotal_letter" id="quote_subtotal_letter" value="{{ $quote->subtotal_letter }}">
                        <input type="hidden" name="quote_subtotal_rent" id="quote_subtotal_rent" value="{{ $quote->subtotal_rent }}">

                    </tr>
                    <tr>
                        <th>Margen Utilidad: </th>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" onkeyup="calculateMargen(this);" value="{{ $quote->utility }}" class="form-control form-control-sm" name="utility" id="utility" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        " readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th style="width:50%">Subtotal: </th>
                        <td id="subtotal2">S/. {{ $quote->subtotal_utility }}</td>
                    </tr>
                    <tr>
                        <th>Letra: </th>
                        <td >
                            <div class="input-group input-group-sm">
                                <input type="number" onkeyup="calculateLetter(this);" class="form-control form-control-sm" name="letter" id="letter" placeholder="0.00" min="0" value="{{ $quote->letter }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        " readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th style="width:50%">Subtotal: </th>
                        <td id="subtotal3">S/. {{ $quote->subtotal_letter }}</td>
                    </tr>
                    <tr>
                        <th>Renta: </th>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" onkeyup="calculateRent(this);" class="form-control form-control-sm" name="taxes" id="taxes" placeholder="0.00" min="0" value="{{ $quote->rent }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        " readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Total: </th>
                        <td id="total">S/. {{ $quote->subtotal_rent }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <div id="modalAddMaterial" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ingresar dimensiones o cantidad</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3" id="length_material">
                            <label class="col-sm-12 control-label" for="material_length"> Largo </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_length" name="material_length" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="col-md-3" id="width_material">
                            <label class="col-sm-12 control-label" for="material_width"> Ancho </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_width" name="material_width" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="col-md-3" id="quantity_material">
                            <label class="col-sm-12 control-label" for="material_quantity"> Cantidad </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_quantity" name="material_quantity" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="col-md-3" id="price_material">
                            <label class="col-sm-12 control-label" for="material_price"> Precio </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_price" name="material_price" class="form-control" readonly />
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row" id="presentation">

                        <div class="col-md-3">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="fraction" checked name="presentation" value="fraction">
                                <label for="fraction">Fraccionada
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="icheck-success d-inline">
                                <input type="radio" id="complete" name="presentation" value="complete">
                                <label for="complete">Completa
                                </label>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3" id="length_entered_material">
                            <label class="col-sm-12 control-label" for="material_length_entered"> Ingresar largo </label>

                            <div class="col-sm-12">
                                <input type="number" id="material_length_entered" name="material_length_entered" class="form-control" placeholder="0.00" min="0" value="" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                            </div>
                        </div>
                        <div class="col-md-3" id="width_entered_material">
                            <label class="col-sm-12 control-label" for="material_width_entered"> Ingresar ancho </label>

                            <div class="col-sm-12">
                                <input type="number" id="material_width_entered" name="material_width_entered" class="form-control" placeholder="0.00" min="0" value="" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                            </div>
                        </div>
                        <div class="col-md-3" id="quantity_entered_material">
                            <label class="col-sm-12 control-label" for="material_quantity_entered"> Ingresar cantidad </label>

                            <div class="col-sm-12">
                                <input type="number" id="material_quantity_entered" name="material_quantity_entered" class="form-control" placeholder="0.00" min="0" value="" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="btnCalculate"> &nbsp; </label>
                            <button type="button" id="btnCalculate" class="btn btn-block btn-outline-primary">Calcular <i class="fas fa-arrow-circle-right"></i></button>
                        </div>
                        <div class="col-md-2" id="percentage_entered_material">
                            <label class="col-sm-12 control-label" for="material_percentage_entered"> Porcentaje </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_percentage_entered" name="material_percentage_entered" class="form-control" readonly />
                            </div>
                        </div>
                        <div class="col-md-2" id="price_entered_material">
                            <label class="col-sm-12 control-label" for="material_price_entered"> Total </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_price_entered" name="material_price_entered" class="form-control" readonly />
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-addMaterial" class="btn btn-outline-primary">Agregar</button>
                </div>

            </div>
        </div>
    </div>

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
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#customer_id').select2({
                placeholder: "Selecione cliente",
            });

            $('.unitMeasure').select2({
                placeholder: "Seleccione unidad",
            });

            $('#sandbox-container .input-daterange').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                language: "es",
                multidate: false,
                autoclose: true
            });
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>

    <script src="{{ asset('js/quote/create.js') }}"></script>
@endsection
