@extends('layouts.appAdmin2')

@section('openEntryPurchase')
    menu-open
@endsection

@section('activeEntryPurchase')
    active
@endsection

@section('activeListOrdersInEntries')
    active
@endsection

@section('title')
    Entrada por Compras
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/typehead/typeahead.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }

        .modal-dialog {
            height: 100% !important;
        }

        .modal-content {
            height: auto;
            min-height: 100%;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Entrada por compra</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nueva entrada por compra</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('entry.purchase.index') }}"><i class="fa fa-archive"></i> Entradas por compra</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nueva entrada</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('entry.purchase.order.store') }}" enctype="multipart/form-data">
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
                        <div class="row">
                            <input type="hidden" name="purchase_order_id" value="{{ $orderPurchase->id }}">
                            <div class="col-md-6">
                                <div class="form-group " id="sandbox-container">
                                    <label for="date_invoice">Fecha de Factura</label>
                                    <div class="input-daterange" id="datepicker">
                                        <input type="text" class="form-control date-range-filter" id="date_invoice" name="date_invoice">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="referral_guide">Guía de remisión</label>
                                    <input type="text" id="referral_guide" name="referral_guide" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="purchase_order">Orden de Compra</label>
                                    <input type="text" id="purchase_order" name="purchase_order" value="{{ $orderPurchase->code }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="supplier_id">Proveedor </label>
                                    <input type="text" id="supplier_id" name="supplier_id" value="{{ ($orderPurchase->supplier == null) ? 'Falta proveedor': $orderPurchase->supplier->business_name }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <label for="invoice">Factura <span class="right badge badge-danger">(*)</span></label>
                                        <input type="text" id="invoice" name="invoice" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="btn-grouped"> Diferido <span class="right badge badge-danger">(*)</span></label> <br>
                                        <input id="btn-grouped" type="checkbox" name="deferred_invoice" data-bootstrap-switch data-off-color="danger" data-on-text="SI" data-off-text="NO" data-on-color="success">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="entry_type">Tipo de Ingreso <span class="right badge badge-danger">(*)</span></label>
                                    <input type="text" id="entry_type" value="Por compra" name="entry_type" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="image">Imagen/PDF Factura </label>
                                    <input type="file" id="image" name="image" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="currency"> Moneda <span class="right badge badge-danger">(*)</span></label> <br>
                                    <input type="text" id="currency" value="{{ ($orderPurchase->currency_order) === 'USD' ? 'DOLARES':'SOLES' }}" name="currency_invoice" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observation">Observación </label>
                                    <textarea name="observation" cols="30" class="form-control" style="word-break: break-all;" placeholder="Ingrese observación ...."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imageOb">Imagen/PDF Observación</label>
                                    <input type="file" id="imageOb" name="imageOb" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Materiales</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <strong>ID</strong>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <strong>Código</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Material</strong>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <strong>Cantidad</strong>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <strong>Precio</strong>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <strong>Ingreso</strong>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <strong>Ubicación</strong>
                                </div>
                            </div>

                        </div>
                        <div id="body-materials">
                            @foreach( $orderPurchase->details as $detail )
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-id value="{{ $detail->material->id }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-code value="{{ $detail->material->code }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-description value="{{ $detail->material->full_description }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" value="{{ $detail->quantity }}" data-quantity="{{$detail->id}}" step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-price="{{$detail->id}}" value="{{ $detail->price }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" value="{{ $detail->quantity }}" data-entered="{{$detail->id}}" step="0.01" >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="location" class="form-control form-control-sm location select2" data-location style="width: 100%;">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="row">
                    <!-- accepted payments column -->
                    <div class="col-6">

                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                        <p class="lead">Resumen de factura</p>

                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:50%">Subtotal: </th>
                                    <td ><span class="moneda">{{ $orderPurchase->currency_order }}</span> <span id="subtotal">{{ $orderPurchase->total - $orderPurchase->igv }}</span> </td>
                                </tr>
                                <tr>
                                    <th>Igv: </th>
                                    <td ><span class="moneda">{{ $orderPurchase->currency_order }}</span> <span id="taxes">{{$orderPurchase->igv }}</span> </td>
                                </tr>
                                <tr>
                                    <th>Total: </th>
                                    <td ><span class="moneda">{{ $orderPurchase->currency_order }}</span> <span id="total">{{ $orderPurchase->total }}</span> </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="submit" id="btn-submit" class="btn btn-outline-success float-right">Guardar factura</button>
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

@endsection

@section('scripts')
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
    <script>
        $('#date_invoice').attr("value", moment().format('DD/MM/YYYY'));

        $('#sandbox-container .input-daterange').datepicker({
            todayBtn: "linked",
            clearBtn: true,
            language: "es",
            multidate: false,
            autoclose: true,
            todayHighlight: true,
            defaultViewDate: moment().format('L')
        });

        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch();
        });
        $('.location').select2({
            placeholder: "Seleccione localización",
        });
        $('.arrived').select2({
            placeholder: "Seleccione",
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>

    <script src="{{ asset('js/entry/entry_purchase_order.js') }}?v={{ time() }}"></script>

@endsection
