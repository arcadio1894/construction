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
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.css') }}">

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
    <h5 class="card-title">Modificar cotización</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('quote.index') }}"><i class="fa fa-key"></i> Cotizaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <form id="formAdjust" class="form-horizontal" data-url="{{ route('quote.adjust') }}" enctype="multipart/form-data">
        @csrf
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
                                <input type="hidden" id="quote_id" name="quote_id" value="{{ $quote->id }}">
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
                                <input type="text" id="paymentQuote" onkeyup="mayus(this);" name="way_to_pay" class="form-control form-control-sm" value="{{ ($quote->deadline !== null) ? $quote->deadline->description : 'No tiene forma de pago' }} " readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="timeQuote">Tiempo de entrega <span class="right badge badge-danger">(*)</span></label>
                                <div class="input-group input-group-sm mb-3">
                                    <input type="number" id="timeQuote" step="1" min="0" name="delivery_time" class="form-control form-control-sm" value="{{ $quote->time_delivery }}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2"> DIAS</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="customer_id">Cliente <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm" value="{{ ($quote->customer !== null) ? $quote->customer->business_name : 'No tiene cliente'}}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="customer_id">Contacto <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm" value="{{ ($quote->contact !== null) ? $quote->contact->name : 'No tiene contacto'}}" readonly>
                            </div>
                            <div class="col-md-8">
                                <label for="observations">Observaciones </label>
                                <div>{!! nl2br($quote->observations) !!}</div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div class="row">
            @foreach( $quote->equipments as $equipment )
                <div class="col-md-12">
                    <div class="card card-success collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">EQUIPO: {{ $equipment->description }}</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
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
                                    {!! nl2br($equipment->detail) !!}
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
                                        <table class="table table-head-fixed">
                                            <thead>
                                            <tr>
                                                <th>Codigo</th>
                                                <th>Material</th>
                                                <th>Unidad</th>
                                                <th>Cantidad</th>
                                                <th>Precio S/IGV</th>
                                                <th>Precio C/IGV</th>
                                                <th>Importe S/IGV</th>
                                                <th>Importe C/IGV</th>
                                            </tr>
                                            </thead>
                                            <tbody data-bodyMaterials>
                                            @foreach( $equipment->materials as $material )
                                                <tr>
                                                    <td data-code>{{ $material->material->code }}</td>
                                                    <td data-description>{{ $material->material->full_description }}</td>
                                                    <td data-unit>{{ $material->material->unitMeasure->name }}</td>
                                                    <td data-quantity>{{ $material->percentage }}</td>
                                                    <td @cannot('showPrices_quote')style="display: none" @endcannot data-price2>{{ round($material->price/1.18,2) }}</td>
                                                    <td @cannot('showPrices_quote')style="display: none" @endcannot data-price>{{ $material->price }}</td>
                                                    <td @cannot('showPrices_quote')style="display: none" @endcannot data-total2>{{ round($material->total/1.18,2) }}</td>
                                                    <td @cannot('showPrices_quote')style="display: none" @endcannot data-total>{{ $material->total }}</td>
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
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <strong>Descripción</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <strong>Unidad</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <strong>Cantidad</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <strong>Precio S/IGV</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <strong>Precio C/IGV</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <strong>Total S/IGV</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <strong>Total C/IGV</strong>
                                                </div>
                                            </div>
                                        </div>
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
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote')style="display: none" @endcannot value="{{ round($consumable->price/1.18,2) }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote')style="display: none" @endcannot value="{{ $consumable->price }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumableTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote')style="display: none" @endcannot value="{{ round($consumable->total/1.18,2) }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumableTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote')style="display: none" @endcannot value="{{ $consumable->total }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="card card-gray collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">SERVICIOS VARIOS</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div data-bodyMano>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <strong>Descripción</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <strong>Unidad</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <strong>Cantidad</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <strong>Precio C/IGV</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <strong>Total</strong>
                                                </div>
                                            </div>
                                        </div>
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
                                            " @cannot('showPrices_quote')style="display: none" @endcannot value="{{ $workforce->price }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" data-manoTotal min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                            " @cannot('showPrices_quote')style="display: none" @endcannot value="{{ $workforce->total }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="card card-lightblue collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title">SERVICIOS ADICIONALES <span class="right badge badge-danger">(Opcional)</span></h3>

                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div data-bodyTorno>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <strong>Descripción</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <strong>Cantidad</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <strong>Precio C/IGV</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <strong>Total</strong>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                            " @cannot('showPrices_quote')style="display: none" @endcannot value="{{ $turnstile->price }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" data-manoTotal min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                            " @cannot('showPrices_quote')style="display: none" @endcannot value="{{ $turnstile->total }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-orange collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title">DIAS DE TRABAJO</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <strong>Cantidad de personas</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <strong>Días por persona</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <strong>Precio C/IGV por día</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <strong>Total</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-bodyDia>
                                        @foreach( $equipment->workdays as $workday )
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" onkeyup="calculateTotalQuatity(this);" value="{{ $workday->quantityPerson }}" placeholder="0.00" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                    " readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" onkeyup="calculateTotalHour(this);" value="{{ $workday->hoursPerPerson }}" placeholder="0.00" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                    " readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" onkeyup="calculateTotalPrice(this);" value="{{ $workday->pricePerHour }}" placeholder="0.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                    " @cannot('showPrices_quote')style="display: none" @endcannot readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" value="{{ $workday->total }}" placeholder="0.00" min="0" data-total step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                    " @cannot('showPrices_quote')style="display: none" @endcannot readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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

        @can('showPrices_quote')
            {{--<div class="row">
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
                                <td id="subtotal"> {{ $quote->currency_invoice }} {{ ($quote->currency_invoice === 'PEN') ? $quote->total_soles: $quote->total }}</td>
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
                                        ">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="width:50%">Subtotal: </th>
                                <td id="subtotal2">{{ $quote->currency_invoice }} {{ $quote->subtotal_utility }}</td>
                            </tr>
                            <tr>
                                <th>Letra: </th>
                                <td >
                                    <div class="input-group input-group-sm">
                                        <input type="number" onkeyup="calculateLetter(this);" class="form-control form-control-sm" name="letter" id="letter" placeholder="0.00" min="0" value="{{ $quote->letter }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        ">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th style="width:50%">Subtotal: </th>
                                <td id="subtotal3">{{ $quote->currency_invoice }} {{ $quote->subtotal_letter }}</td>
                            </tr>
                            <tr>
                                <th>Renta: </th>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" onkeyup="calculateRent(this);" class="form-control form-control-sm" name="taxes" id="taxes" placeholder="0.00" min="0" value="{{ $quote->rent }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        ">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Total: </th>
                                <td id="total">{{ $quote->currency_invoice }} {{ $quote->subtotal_rent }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>--}}
            <p class="lead">Resumen de Cotización</p>
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
                                        <th style="background-color: #9BC2E6">Letra+Renta</th>
                                        <th style="background-color: #9BC2E6">Precio Unit. S/Igv</th>
                                        <th style="background-color: #ED7D31">Total S/Igv</th>
                                        <th >Cambiar</th>
                                    </tr>
                                    </thead>
                                    <tbody id="body-summary">
                                    @foreach( $quote->equipments as $equipment )
                                        <tr>
                                            <td data-nEquipment>{{ $equipment->description }}</td>
                                            <td data-qEquipment>{{ $equipment->quantity }}</td>
                                            <td data-pEquipment>{{ round(($equipment->total/$equipment->quantity)/1.18, 2) }}</td>
                                            <td data-uEquipment>{{ $equipment->utility }}</td>
                                            <td data-rlEquipment>{{ $equipment->rent + $equipment->letter }}</td>
                                            <td data-uPEquipment>{{ round(($equipment->subtotal_percentage/1.18)/$equipment->quantity, 2) }}</td>
                                            <td data-tEquipment>{{ round($equipment->subtotal_percentage/1.18, 2) }}</td>
                                            <td data-acEquipment>
                                                <button type="button" class="btn btn-sm btn-outline-warning" data-acEdit="{{ $quote->id }}" data-acEquipment="{{ $equipment->id }}" data-utility="{{$equipment->utility}}" data-rent="{{$equipment->rent}}" data-letter="{{$equipment->letter}}"><i class="fas fa-edit"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <template id="template-summary">
                                        <tr>
                                            <td data-nEquipment></td>
                                            <td data-qEquipment></td>
                                            <td data-pEquipment></td>
                                            <td data-uEquipment></td>
                                            <td data-rlEquipment></td>
                                            <td data-uPEquipment></td>
                                            <td data-tEquipment></td>
                                            <td data-acEquipment>
                                                <button type="button" class="btn btn-sm btn-outline-warning" data-acEdit="" data-acEquipment="" data-utility="" data-rent="" data-letter=""><i class="fas fa-edit"></i></button>
                                            </td>
                                        </tr>
                                    </template>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-sm-7">

                </div>
                <!-- /.col -->
                <div class="col-sm-5">
                    <p class="lead">Resumen de Cotización</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Total S/IGV: </th>
                                <td id="subtotal"> USD {{ round(($quote->total_equipments)/1.18, 2) }}</td>
                                <input type="hidden" name="quote_total" id="quote_total" value="{{ $quote->total }}">
                                <input type="hidden" name="quote_subtotal_utility" id="quote_subtotal_utility" value="{{ $quote->subtotal_utility }}">
                                <input type="hidden" name="quote_subtotal_letter" id="quote_subtotal_letter" value="{{ $quote->subtotal_letter }}">
                                <input type="hidden" name="quote_subtotal_rent" id="quote_subtotal_rent" value="{{ $quote->subtotal_rent }}">

                            </tr>
                            <tr>
                                <th style="width:50%">Total C/IGV: </th>
                                <td id="total"> USD {{ round($quote->total_equipments, 2) }}</td>
                            </tr>
                            <tr>
                                <th style="width:50%">Total+Utilidad S/IGV: </th>
                                <td id="subtotal_utility">USD {{ round(($quote->total_quote)/1.18, 2) }}</td>
                            </tr>
                            <tr>
                                <th style="width:50%">Total+Utilidad C/IGV: </th>
                                <td id="total_utility">USD {{ round($quote->total_quote, 2) }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
        @endcan

        {{--<div class="row">
            <div class="col-12">
                <a href="{{ route('quote.raise') }}" class="btn btn-outline-secondary">Regresar</a>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Ajustar porcentajes</button>
            </div>
        </div>--}}
        <!-- /.card-footer -->
    </form>

    <div id="modalChangePercentages" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cambiar los porcentages de ganancia</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="quote_percentage" id="quote_percentage">
                    <input type="hidden" name="equipment_percentage" id="equipment_percentage">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Importante!</strong> Se recargará automaticamente la página para que hagan efecto los cambios.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-sm-12 control-label" for="percentage_utility"> Utilidad </label>

                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control form-control-sm" name="percentage_utility" id="percentage_utility" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        ">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="col-sm-12 control-label" for="percentage_letter"> Letra </label>

                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control form-control-sm" name="percentage_letter" id="percentage_letter" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        ">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <label class="col-sm-12 control-label" for="percentage_rent"> Renta </label>

                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control form-control-sm" name="percentage_rent" id="percentage_rent" placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        ">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-changePercentage" class="btn btn-outline-primary">Guardar porcentajes</button>
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
    <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/lang/summernote-es-ES.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.textarea_edit').summernote({
                lang: 'es-ES',
                placeholder: 'Ingrese los detalles',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview', 'help']]
                ]
            });

            $('#customer_id').select2({
                placeholder: "Selecione cliente",
            });

            $('.unitMeasure').select2({
                placeholder: "Seleccione unidad",
            });

            $('.material_search').select2({
                placeholder: 'Selecciona un material',
                ajax: {
                    url: '/dashboard/select/materials',
                    dataType: 'json',
                    type: 'GET',
                    processResults(data) {
                        //console.log(data);
                        return {
                            results: $.map(data, function (item) {
                                //console.log(item.full_description);
                                return {
                                    text: item.full_description,
                                    id: item.id,
                                }
                            })
                        }
                    }
                }
            });

            $('.consumable_search').select2({
                placeholder: 'Selecciona un consumible',
                ajax: {
                    url: '/dashboard/select/consumables',
                    dataType: 'json',
                    type: 'GET',
                    processResults(data) {
                        //console.log(data);
                        return {
                            results: $.map(data, function (item) {
                                //console.log(item.full_description);
                                return {
                                    text: item.full_description,
                                    id: item.id,
                                }
                            })
                        }
                    }
                }
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

    <script src="{{ asset('js/quote/adjust.js') }}"></script>
@endsection
