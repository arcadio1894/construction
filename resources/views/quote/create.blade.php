@extends('layouts.appAdmin2')

@section('openQuote')
    menu-open
@endsection

@section('activeQuote')
    active
@endsection

@section('activeCreateQuote')
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
    <!-- Images -->
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
    <h5 class="card-title">Crear nuevo cotización</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('quote.index') }}"><i class="fa fa-key"></i> Cotizaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <form id="formCreate" class="form-horizontal" data-url="{{ route('quote.store') }}" enctype="multipart/form-data">
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
                                <input type="text" id="descriptionQuote" onkeyup="mayus(this);" name="code_description" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="description">Código de cotización </label>
                                <input type="text" id="codeQuote" readonly value="{{ $codeQuote }}" onkeyup="mayus(this);" name="code_quote" class="form-control form-control-sm">
                            </div>
                            @hasanyrole('logistic|admin|principal|quote_single')
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
                            @endhasanyrole

                            @hasanyrole('logistic|admin|principal|quote_single')
                            <div class="col-md-4">
                                <label for="paymentQuote">Forma de pago </label>
                                {{--<input type="hidden" onkeyup="mayus(this);" name="way_to_pay" class="form-control form-control-sm">--}}
                                <select id="paymentQuote" name="payment_deadline" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $paymentDeadlines as $paymentDeadline )
                                        <option value="{{ $paymentDeadline->id }}">{{ $paymentDeadline->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endhasanyrole
                            <div class="col-md-4">
                                <label for="description">Tiempo de entrega </label>
                                <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm">
                            </div>
                            {{--@hasanyrole('logistic|admin')--}}
                            <div class="col-md-4">
                                <label for="customer_id">Cliente </label>
                                <select id="customer_id" name="customer_id" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $customers as $customer )
                                        <option value="{{ $customer->id }}">{{ $customer->business_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="contact_id">Contacto </label>
                                <select id="contact_id" name="contact_id" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                            {{--@endhasanyrole--}}
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 offset-md-4 col-sm-4 offset-sm-4">
                <button type="button" id="btn-addEquipment" class="btn btn-block bg-gradient-primary">Nuevo equipo <i class="fas fa-plus-circle"></i></button>
                <br>
            </div>
        </div>

        <div class="row" id="body-equipment">
            <div class="col-md-12">
                <div class="card card-success" data-equip="asd">
                    <div class="card-header">
                        <h3 class="card-title">EQUIPOS</h3>

                        <div class="card-tools">
                            <a data-confirm class="btn btn-primary btn-sm" data-toggle="tooltip" title="Confirmar" style="">
                                <i class="fas fa-check-square"></i> Confirmar equipo
                            </a>
                            <a class="btn btn-warning btn-sm" data-saveEquipment="" style="display:none" data-toggle="tooltip" title="Guardar cambios">
                                <i class="fas fa-check-square"></i> Guardar cambios
                            </a>
                            <a class="btn btn-danger btn-sm" data-deleteEquipment="" style="display:none" data-toggle="tooltip" title="Quitar">
                                <i class="fas fa-check-square"></i> Eliminar equipo
                            </a>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="description">Cantidad de equipo <span class="right badge badge-danger">(*)</span></label>
                                <input type="number" data-quantityequipment class="form-control" placeholder="1" min="0" value="1" step="1" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">

                                <input type="hidden" name="" data-utilityEquipment value="{{ $utility->value }}">
                                <input type="hidden" name="" data-rentEquipment value="{{ $rent->value }}">
                                <input type="hidden" name="" data-letterEquipment value="{{ $letter->value }}">

                            </div>
                            <div class="col-md-9">
                                <label for="description"> <span class="right badge badge-danger">Importante</span></label>
                                <p>Todos los costos se multiplicarán por esta cantidad. Ingrese cantidades para un equipo. </p>
                            </div>
                            <div class="col-md-12">
                                <label for="description">Descripción de equipo <span class="right badge badge-danger">(*)</span></label>
                                <textarea name="" data-descriptionequipment onkeyup="mayus(this);" cols="30" class="form-control" placeholder="Ingrese detalles ...."></textarea>
                            </div>
                            {{--<div class="col-md-12">
                                <label for="description">Detalles de equipo <span class="right badge badge-danger">(*)</span></label>
                                <textarea name=""  onkeyup="mayus(this);" cols="30" class="form-control" style="word-break: break-all;" placeholder="Ingrese detalles ...."></textarea>
                            </div>--}}
                            <div class="col-md-12">
                                <label for="description">Detalles de equipo <span class="right badge badge-danger">(*)</span></label>
                                <textarea class="textarea_edit" data-detailequipment placeholder="Place some text here"
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
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
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="material_search">Buscar material <span class="right badge badge-danger">(*)</span></label>
                                            <input type="text" id="material_search" class="form-control rounded-0 typeahead materialTypeahead">

                                            {{--<label>Seleccionar material <span class="right badge badge-danger">(*)</span></label>
                                            <select class="form-control material_search" style="width:100%" name="material_search"></select>
                                        --}}
                                        </div>
                                    </div>
                                    {{--<div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-add class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>--}}
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <strong>Material</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Unidad</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Largo</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Ancho</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
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
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Acción</strong>
                                        </div>
                                    </div>
                                </div>
                                <div data-bodyMaterials>

                                </div>
                                {{--<div class="row">
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Material</th>
                                            <th>Unidad</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Importe</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody data-bodyMaterials>

                                        </tbody>

                                    </table>
                                </div>--}}
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
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Seleccionar consumible <span class="right badge badge-danger">(*)</span></label>
                                            <select class="form-control consumable_search" data-consumable style="width:100%" name="consumable_search"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity">Cantidad <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-cantidad class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-addConsumable class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>
                                </div>
                                <hr>
                                <div data-bodyConsumable>
                                    <div class="row">
                                        <div class="col-md-3">
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
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <strong>Acción</strong>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach( $consumables as $consumable )
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $consumable->full_description }}" data-consumableDescription readonly>
                                                <input type="hidden" data-consumableId="{{ $consumable->id }}" value="{{ $consumable->id }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $consumable->unitMeasure->description }}" data-consumableUnit readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" oninput="calculateTotalC(this);" placeholder="0.00" data-consumableQuantity min="0" value="0.00" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input type="number" value="{{ number_format($consumable->unit_price/1.18, 2) }}" class="form-control form-control-sm" data-consumablePrice2 placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input type="number" value="{{ $consumable->unit_price }}" class="form-control form-control-sm" data-consumablePrice placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" data-consumableTotal2 value="0" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" data-consumableTotal value="0" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteConsumable class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
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
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="material_search">Descripción <span class="right badge badge-danger">(*)</span></label>
                                            <input type="text" id="material_search" onkeyup="mayus(this);" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label >Unidad <span class="right badge badge-danger">(*)</span></label>
                                            <select class="form-control select2 unitMeasure" style="width: 100%;">
                                                <option></option>
                                                @foreach( $unitMeasures as $unitMeasure )
                                                    <option value="{{ $unitMeasure->id }}">{{ $unitMeasure->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity">Cantidad <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" id="quantity" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    @can('showPrices_quote')
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="price">Precio C/IGV <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" id="price" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    @endcan
                                    <div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-addMano class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
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
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Acción</strong>
                                        </div>
                                    </div>
                                </div>
                                <div data-bodyMano>

                                    @foreach( $workforces as $workforce )
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $workforce->description }}" data-manoDescription>
                                                    <input type="hidden" data-manoId value="{{ $workforce->id }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $workforce->unitMeasure->name }}" data-manoUnit readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="number" class="form-control form-control-sm" oninput="calculateTotal(this);" placeholder="0.00" data-manoQuantity min="0" value="1.00" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="number" value="{{ $workforce->unit_price }}" oninput="calculateTotal2(this);" class="form-control form-control-sm" data-manoPrice placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') style="display: none" @endcannot >
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="number" class="form-control form-control-sm" placeholder="0.00" data-manoTotal value="{{ $workforce->unit_price }}" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" data-deleteMano class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
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
                                                    <label for="price">Precio C/IGV <span class="right badge badge-danger">(*)</span></label>
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
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="text" value="SERVICIO DE CORTE Y DOBLEZ" onkeyup="mayus(this);" class="form-control form-control-sm" data-tornoDescription>
                                                        <input type="hidden" data-tornoId>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" value="0.00" oninput="calculateTotal(this);" data-tornoQuantity step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                        ">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" value="0.00" oninput="calculateTotal2(this);" data-tornoPrice step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                        " >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" value="0.00" data-tornoTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                        " readonly >
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" data-deleteTorno class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                                </div>
                                            </div>
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                            <input type="text" data-description  onkeyup="mayus(this);" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity">N° de personas <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-cantidad class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="hours">Días por persona <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-horas class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    @can('showPrices_quote')
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="price">Precio C/IGV por día <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-precio class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    @endcan
                                    <div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-addDia class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <strong>Descripción</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>N° de personas</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Días por persona</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Precio C/IGV por día</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Total</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Acción</strong>
                                        </div>
                                    </div>
                                </div>
                                <div data-bodyDia>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="PERSONAL PARA FABRICACIÓN" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" value="0" oninput="calculateTotalQuatity(this);" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" value="0" oninput="calculateTotalHour(this);" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" value="55.00" oninput="calculateTotalPrice(this);" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" value="0" min="0" data-total step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="PERSONAL PARA INSTALACIÓN" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" value="0" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" value="0" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" value="55.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="PERSONAL PARA DOSSIER DE CALIDAD" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" value="0" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" value="0" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" value="55.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="PREVENCIONISTA" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" value="0" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" value="0" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" value="120.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="COSTO DE PROGRAMACIÓN" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" value="0" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" value="0" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" value="0.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @can('showPrices_quote')
                        <div class="card col-md-6">
                            <div class="card-header">
                                <h3 class="card-title">Resumen de totales</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Concepto</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>MATERIALES</td>
                                        <td data-total_materials></td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>CONSUMIBLES</td>
                                        <td data-total_consumables></td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>SERVICIOS VARIOS</td>
                                        <td data-total_workforces></td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>SERVICIOS ADICIONALES</td>
                                        <td data-total_tornos></td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td>DÍAS DE TRABAJO</td>
                                        <td data-total_dias></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        @endcan
                        <!-- /.card -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>



        {{--<div class="row" >
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">IMAGENES DE PLANOS</h3>

                        <div class="card-tools">
                            <a id="addImage" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Agregar imagen">
                                <i class="far fa-images"></i> Agregar Imagen
                            </a>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body" >
                        <div class="row" id="body-images">
                            --}}{{--<div class="col-md-4">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Imagen Seleccionada</h3>

                                        <div class="card-tools">
                                            <button type="button" data-imagedelete class="btn btn-sm btn-outline-danger" ><i class="fas fa-trash"></i></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                            <textarea class="form-control" name="descplanos[]" rows="2" placeholder="Enter ..."></textarea>

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
                            </div>--}}{{--
                        </div>

                        <template id="template-image">
                            <div class="col-md-4">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Imagen Seleccionada</h3>

                                        <div class="card-tools">
                                            <button type="button" data-imagedelete class="btn btn-sm btn-outline-danger" ><i class="fas fa-trash"></i></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                            <textarea class="form-control" name="descplanos[]" rows="2" placeholder="Enter ..."></textarea>

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
                        </template>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>--}}

        @can('showPrices_quote')
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
                            <td id="subtotal">USD 0.00</td>
                        </tr>
                        <tr>
                            <th>Total C/IGV: </th>
                            <td id="total">USD 0.00</td>
                        </tr>
                        <tr>
                            <th style="width:50%">Total+Utilidad S/IGV: </th>
                            <td id="subtotal_utility">USD 0.00</td>
                        </tr>
                        <tr>
                            <th style="width:50%">Total+Utilidad C/IGV: </th>
                            <td id="total_utility">USD 0.00</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        @endcan

        <template id="materials-selected">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-group">
                            <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-materialDescription readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <div class="form-group">
                            <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-materialUnit readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalMaterialLargo(this);" min="0" data-materialLargo step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            ">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalMaterialAncho(this);" min="0" data-materialAncho step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            ">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalMaterialQuantity(this);" min="0" data-materialQuantity step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            ">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-materialPrice2 step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-materialPrice step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-materialTotal2 step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-materialTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" data-delete class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                </div>
            </div>
        </template>

        <template id="template-consumable">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-consumableDescription readonly>
                        <input type="hidden" data-consumableId>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="form-group">
                            <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-consumableUnit readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" oninput="calculateTotalC(this);" data-consumableQuantity step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            ">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumablePrice2 step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumablePrice step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumableTotal2 step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-consumableTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" data-deleteConsumable class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                </div>
            </div>
        </template>

        <template id="template-dia">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-description >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " @cannot('showPrices_quote') readonly @endcannot>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                </div>
            </div>
        </template>

        <template id="template-mano">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-manoDescription>
                        <input type="hidden" data-manoId>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="form-group">
                            <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-manoUnit readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" oninput="calculateTotal(this);" data-manoQuantity step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            ">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" oninput="calculateTotal2(this);" data-manoPrice step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " @cannot('showPrices_quote') readonly @endcannot >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-manoTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" data-deleteMano class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                </div>
            </div>
        </template>

        <template id="template-torno">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-tornoDescription>
                        <input type="hidden" data-tornoId>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" oninput="calculateTotal(this);" data-tornoQuantity step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            ">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" oninput="calculateTotal2(this);" data-tornoPrice step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-tornoTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" data-deleteTorno class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                </div>
            </div>
        </template>

        <template id="template-equipment">
            <div class="col-md-12">
                <div class="card card-success" data-equip>
                    <div class="card-header">
                        <h3 class="card-title">EQUIPOS</h3>

                        <div class="card-tools">
                            <a data-confirm class="btn btn-primary btn-sm" data-toggle="tooltip" title="Confirmar" >
                                <i class="fas fa-check-square"></i> Confirmar equipo
                            </a>
                            <a class="btn btn-warning btn-sm" data-saveEquipment="" style="display:none" data-toggle="tooltip" title="Guardar cambios">
                                <i class="fas fa-check-square"></i> Guardar cambios
                            </a>
                            <a class="btn btn-danger btn-sm" data-deleteEquipment="" style="display:none" data-toggle="tooltip" title="Quitar">
                                <i class="fas fa-check-square"></i> Eliminar equipo
                            </a>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="description">Cantidad de equipo <span class="right badge badge-danger">(*)</span></label>
                                <input type="number" data-quantityEquipment class="form-control" placeholder="1" min="0" value="1" step="1" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                                <input type="hidden" name="" data-utilityEquipment value="{{ $utility->value }}">
                                <input type="hidden" name="" data-rentEquipment value="{{ $rent->value }}">
                                <input type="hidden" name="" data-letterEquipment value="{{ $letter->value }}">

                            </div>
                            <div class="col-md-9">
                                <label for="description"> <span class="right badge badge-danger">Importante</span></label>
                                <p>Todos los costos se multiplicarán por esta cantidad. Ingrese cantidades para un equipo. </p>
                            </div>
                            <div class="col-md-12">
                                <label for="description">Descripción de equipo <span class="right badge badge-danger">(*)</span></label>
                                <textarea name="" data-descriptionEquipment onkeyup="mayus(this);" cols="30" class="form-control" placeholder="Ingrese detalles ...."></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="description">Detalles de equipo <span class="right badge badge-danger">(*)</span></label>
                                <textarea class="textarea_edit" data-detailequipment placeholder="Place some text here"
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
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
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="material_search">Buscar material <span class="right badge badge-danger">(*)</span></label>
                                            <input type="text" class="form-control rounded-0 typeahead materialTypeahead">

                                            {{--<label>Seleccionar material <span class="right badge badge-danger">(*)</span></label>
                                            <select class="form-control material_search" style="width:100%" name="material_search"></select>
                                        --}}
                                        </div>
                                    </div>
                                    {{--<div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-add class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>--}}
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <strong>Material</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Unidad</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Largo</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Ancho</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
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
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Acción</strong>
                                        </div>
                                    </div>
                                </div>
                                <div data-bodyMaterials>

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
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Seleccionar consumible <span class="right badge badge-danger">(*)</span></label>
                                            <select class="form-control consumable_search" data-consumable style="width:100%" name="consumable_search"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity">Cantidad <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-cantidad class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-addConsumable class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>
                                </div>
                                <hr>
                                <div data-bodyConsumable>
                                    <div class="row">
                                        <div class="col-md-3">
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
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <strong>Acción</strong>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach( $consumables as $consumable )
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $consumable->full_description }}" data-consumableDescription readonly>
                                                    <input type="hidden" data-consumableId="{{ $consumable->id }}" value="{{ $consumable->id }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $consumable->unitMeasure->description }}" data-consumableUnit readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="number" class="form-control form-control-sm" oninput="calculateTotalC(this);" placeholder="0.00" data-consumableQuantity min="0" value="0.00" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <input type="number" value="{{ round($consumable->unit_price/1.18,2) }}" class="form-control form-control-sm" data-consumablePrice2 placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <input type="number" value="{{ $consumable->unit_price }}" class="form-control form-control-sm" data-consumablePrice placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <input type="number" class="form-control form-control-sm" placeholder="0.00" data-consumableTotal2 value="0" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <input type="number" class="form-control form-control-sm" placeholder="0.00" data-consumableTotal value="0" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" data-deleteConsumable class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
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
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="material_search">Descripción <span class="right badge badge-danger">(*)</span></label>
                                            <input type="text" id="material_search" onkeyup="mayus(this);" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label >Unidad <span class="right badge badge-danger">(*)</span></label>
                                            <select class="form-control select2 unitMeasure" style="width: 100%;">
                                                <option></option>
                                                @foreach( $unitMeasures as $unitMeasure )
                                                    <option value="{{ $unitMeasure->id }}">{{ $unitMeasure->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity">Cantidad <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" id="quantity" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    @can('showPrices_quote')
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="price">Precio C/IGV <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" id="price" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    @endcan
                                    <div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-addMano class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
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
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Acción</strong>
                                        </div>
                                    </div>
                                </div>
                                <div data-bodyMano>
                                    @foreach( $workforces as $workforce )
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $workforce->description }}" data-manoDescription>
                                                    <input type="hidden" data-manoId value="{{ $workforce->id }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="{{ $workforce->unitMeasure->name }}" data-manoUnit readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="number" class="form-control form-control-sm" oninput="calculateTotal(this);" placeholder="0.00" data-manoQuantity min="0" value="1.00" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="number" value="{{ $workforce->unit_price }}" oninput="calculateTotal2(this);" class="form-control form-control-sm" data-manoPrice placeholder="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') style="display: none" @endcannot>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="number" class="form-control form-control-sm" placeholder="0.00" data-manoTotal value="{{ $workforce->unit_price }}" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly @cannot('showPrices_quote') style="display: none" @endcannot>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" data-deleteMano class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
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
                                                    <label for="price">Precio C/IGV <span class="right badge badge-danger">(*)</span></label>
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
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="text" value="SERVICIO DE CORTE Y DOBLEZ" onkeyup="mayus(this);" class="form-control form-control-sm" data-tornoDescription>
                                                        <input type="hidden" data-tornoId>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" value="0.00" oninput="calculateTotal(this);" data-tornoQuantity step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                        ">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" value="0.00" oninput="calculateTotal2(this);" data-tornoPrice step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                        " >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" value="0.00" data-tornoTotal step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                        " readonly >
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" data-deleteTorno class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                                </div>
                                            </div>
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                            <input type="text" data-description onkeyup="mayus(this);" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity">N° de personas <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-cantidad class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="hours">Días por persona <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-horas class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                        </div>
                                    </div>
                                    @can('showPrices_quote')
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="price">Precio C/IGV por día <span class="right badge badge-danger">(*)</span></label>
                                                <input type="number" data-precio class="form-control" placeholder="0.00" min="0" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                ">
                                            </div>
                                        </div>
                                    @endcan
                                    <div class="col-md-2">
                                        <label for="btn-add"> &nbsp; </label>
                                        <button type="button" data-addDia class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <strong>Descripción</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>N° de Personas</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Días por persona</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Precio C/IGV por día</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <strong>Total</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <strong>Acción</strong>
                                        </div>
                                    </div>
                                </div>
                                <div data-bodyDia>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="PERSONAL PARA FABRICACIÓN" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" value="0" oninput="calculateTotalQuatity(this);" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" value="0" oninput="calculateTotalHour(this);" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" value="55.00" oninput="calculateTotalPrice(this);" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" value="0" min="0" data-total step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="PERSONAL PARA INSTALACIÓN" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" value="0" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" value="0" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" value="55.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="PERSONAL PARA DOSSIER DE CALIDAD" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" value="0" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" value="0" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" value="55.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="PREVENCIONISTA" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" value="0" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" value="0" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" value="120.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" value="COSTO DE PROGRAMACIÓN" data-description >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalQuatity(this);" value="0" min="0" data-cantidad step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalHour(this);" value="0" min="0" data-horas step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" oninput="calculateTotalPrice(this);" value="0.00" min="0" data-precio step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " @cannot('showPrices_quote') readonly @endcannot>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                                this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                                " readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" data-deleteDia class="btn btn-block btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @can('showPrices_quote')
                        <div class="card col-md-6">
                            <div class="card-header">
                                <h3 class="card-title">Resumen de totales</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Concepto</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>MATERIALES</td>
                                        <td data-total_materials></td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>CONSUMIBLES</td>
                                        <td data-total_consumables></td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>SERVICIOS VARIOS</td>
                                        <td data-total_workforces></td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>SERVICIOS ADICIONALES</td>
                                        <td data-total_tornos></td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td>DÍAS DE TRABAJO</td>
                                        <td data-total_dias></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        @endcan
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </template>

        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar cotización</button>
            </div>
        </div>
        <!-- /.card-footer -->
    </form>

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
                        @can('showPrices_quote')
                        <div class="col-md-3" id="price_material">
                            <label class="col-sm-12 control-label" for="material_price"> Precio C/IGV </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_price" name="material_price" class="form-control" readonly />
                            </div>
                        </div>
                        @endcan
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
                        @can('showPrices_quote')
                        <div class="col-md-2" id="price_entered_material">
                            <label class="col-sm-12 control-label" for="material_price_entered"> Total </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_price_entered" name="material_price_entered" class="form-control" readonly />
                            </div>
                        </div>
                        @endcan
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
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/lang/summernote-es-ES.js')}}"></script>
    <script>
        $(function () {
            $('.textarea_edit').summernote({
                lang: 'es-ES',
                placeholder: 'Ingrese los detalles',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ]
            });
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

            $('.unitMeasure').select2({
                placeholder: "Seleccione unidad",
            });

            $('#date_quote').attr("value", moment().format('DD/MM/YYYY'));

            $('#date_validate').attr("value", moment().add(5, 'days').format('DD/MM/YYYY'));

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
