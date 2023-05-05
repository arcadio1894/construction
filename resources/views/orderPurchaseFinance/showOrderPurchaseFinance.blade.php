@extends('layouts.appAdmin2')

@section('openOrderPurchaseGeneral')
    menu-open
@endsection

@section('activeOrderPurchaseGeneral')
    active
@endsection

@section('activeCreateOrderPurchaseFinance')
    active
@endsection

@section('title')
    Orden de Compras finanzas
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
    <h1 class="page-title">Visualizar orden de compra finanzas</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Orden de compra finanzas</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('order.purchase.finance.index')}}"><i class="fa fa-key"></i> Órdenes de Compra Finanzas</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Visualizar</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="" enctype="multipart/form-data">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <label for="purchase_order">Orden de Compra</label>
                                    <input type="text" id="purchase_order" name="purchase_order" class="form-control" value="{{ $order->code }}" readonly>
                                </div>
                                <div class="form-group " id="sandbox-container">
                                    <label for="date_order">Fecha de Orden</label>
                                    <div class="input-daterange" id="datepicker">
                                        <input type="text" class="form-control date-range-filter" id="date_order" name="date_order" value="{{ \Carbon\Carbon::parse($order->date_order)->format('d/m/Y') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group " id="sandbox-container">
                                    <label for="date_arrival">Fecha de Entrega</label>
                                    <div class="input-daterange" id="datepicker">
                                        <input type="text" class="form-control date-range-filter" id="date_arrival" name="date_arrival" value="{{ \Carbon\Carbon::parse($order->date_delivery)->format('d/m/Y')}}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="observation">Observación </label>
                                    <textarea readonly name="observation" cols="30" class="form-control" style="word-break: break-all;" placeholder="Ingrese observación ....">{{ $order->observation }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="quote_supplier">Cotización de proveedeor : </label>
                                    <input type="text" id="quote_supplier" name="quote_supplier" class="form-control" value="{{ $order->quote_supplier }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier">Proveedor </label>
                                    <input type="text" id="approved_by" name="purchase_condition" class="form-control" value="{{ $order->supplier->business_name }}" readonly>

                                </div>
                                <div class="form-group">
                                    <label for="approved_by">Aprobado por: </label>
                                    <input type="text" id="approved_by" name="purchase_condition" class="form-control" value="{{ $order->approved_user->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="purchase_condition">Forma de pago </label>
                                    <input type="text" id="purchase_condition" name="purchase_condition" class="form-control" value="{{ ($order->deadline != null) ? $order->deadline->description:'No tiene plazo' }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="btn-currency"> Moneda </label> <br>
                                    <input id="btn-currency" name="currency_order" class="form-control" value="{{ ($order->currency_order === 'PEN') ? 'SOLES':'DOLARES' }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="btn-currency"> Regularización </label> <br>
                                    <input id="btn-currency" name="currency_order" class="form-control" value="{{ ($order->regularize === 'nr') ? 'No se ha regularizado':'Regularizado' }}" readonly>
                                </div>
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
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Detalles de orden de compra de finanzas</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body " id="element_loader">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Concepto</strong>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Unidad</strong>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <strong>Cantidad</strong>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <strong>Igv</strong>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Subtotal</strong>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <strong>Total</strong>
                                </div>
                            </div>

                        </div>
                        <div id="body-materials">
                            @foreach( $details as $detail )
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-id value="{{ $detail->material }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" data-code value="{{ $detail->unit }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-sm" onkeyup="calculateTotal(this);" placeholder="0.00" min="0" value="{{ $detail->quantity }}" data-quantity="{{$detail->id}}" step="0.01" readonly>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-sm" onkeyup="calculateTotal(this);" placeholder="0.00" min="0" value="{{ $detail->igv }}" data-quantity="{{$detail->id}}" step="0.01" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-sm" onkeyup="calculateTotal2(this);" placeholder="0.00" min="0" value="{{ ($detail->quantity*$detail->price) - $detail->igv }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-total step="0.01" value="{{ $detail->quantity*$detail->price }}" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                        this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                        " readonly>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.card-footer -->
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
                            <td ><span class="moneda">{{ $order->currency_order }}</span> <span id="subtotal">{{ $order->total - $order->igv }}</span> </td>
                        </tr>
                        <tr>
                            <th>Igv: </th>
                            <td ><span class="moneda">{{ $order->currency_order }}</span> <span id="taxes">{{ $order->igv }}</span> </td>
                        </tr>
                        <tr>
                            <th>Total: </th>
                            <td ><span class="moneda">{{ $order->currency_order }}</span> <span id="total">{{ $order->total }}</span> </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
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
    <script src="{{asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
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


        })
    </script>
@endsection
