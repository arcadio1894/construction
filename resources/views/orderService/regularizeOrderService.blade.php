@extends('layouts.appAdmin2')

@section('openOrderService')
    menu-open
@endsection

@section('activeOrderService')
    active
@endsection

@section('activeListOrderService')
    active
@endsection

@section('title')
    Orden de Servicio
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
    <h1 class="page-title">Entrada por orden de servicio</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Regularizar orden de servicio</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('order.service.index') }}"><i class="fa fa-archive"></i> Ordenes de servicio</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nueva regularización</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('order.service.regularize') }}" enctype="multipart/form-data">
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
                            <input type="hidden" name="service_order_id" value="{{ $order->id }}">
                            <div class="col-md-6">
                                <div class="form-group " id="sandbox-container">
                                    <label for="date_invoice">Fecha de Factura</label>
                                    <div class="input-daterange" id="datepicker">
                                        <input type="text" class="form-control date-range-filter" id="date_invoice" value="{{ ($order->date_invoice === null) ? '':\Carbon\Carbon::parse($order->date_invoice)->format('d/m/Y') }}" name="date_invoice" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="referral_guide">Guía de remisión</label>
                                    <input type="text" id="referral_guide" name="referral_guide" class="form-control" value="{{ $order->referral_guide }}" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="purchase_order">Orden de Servicio</label>
                                    <input type="text" id="purchase_order" name="service_order" value="{{ $order->code }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="supplier_id">Proveedor </label>
                                    <input type="text" id="supplier_id" name="supplier_id" value="{{ $order->supplier->business_name }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <label for="invoice">Factura <span class="right badge badge-danger">(*)</span></label>
                                        <input type="text" id="invoice" name="invoice" class="form-control" value="{{ $order->invoice }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="btn-grouped"> Diferido <span class="right badge badge-danger">(*)</span></label> <br>
                                        <input id="btn-grouped" type="checkbox" name="deferred" data-bootstrap-switch data-off-color="danger" data-on-text="SI" data-off-text="NO" {{ ($order->deferred_invoice === 'on') ? 'checked':''}} data-on-color="success">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image">Imagen Factura </label>
                                    <input type="file" id="image" name="image" class="form-control">
                                    <img data-image src="{{ asset('images/orderServices/'.$order->image_invoice) }}" alt="{{$order->invoice}}" width="100px" height="100px">

                                </div>
                                <div class="form-group">
                                    <label for="currency"> Moneda <span class="right badge badge-danger">(*)</span></label> <br>
                                    <input type="text" id="currency" value="{{ ($order->currency_order) === 'USD' ? 'DOLARES':'SOLES' }}" name="currency_order" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="observation">Observación </label>
                                    <textarea name="observation" cols="30" class="form-control" style="word-break: break-all;" placeholder="Ingrese observación ....">{{ $order->observation }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imageOb">Imagen Observación</label>
                                    <input type="file" id="imageOb" name="imageOb" class="form-control">
                                    <img data-image src="{{ asset('images/orderServices/observations/'.$order->image_observation) }}" alt="{{$order->invoice}}" width="100px" height="100px">

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
                        <h3 class="card-title">Detalles del servicio</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Servicio</strong>
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
                                                <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-id value="{{ $detail->service }}" readonly>
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
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="submit" id="btn-submit" class="btn btn-outline-success float-right">Guardar regularización</button>
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
        if ($('#date_invoice').attr('value') === '' || $('#date_invoice').attr('value') === null ){
            $('#date_invoice').attr("value", moment().format('DD/MM/YYYY'));
        }

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
    <script src="{{ asset('js/orderService/regularizeOrderService.js') }}"></script>
@endsection
