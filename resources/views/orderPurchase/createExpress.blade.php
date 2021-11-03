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
    Orden de compra
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
    <h1 class="page-title">Crear rrden de compra express</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Orden de compra express</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href=""><i class="fa fa-key"></i> Ordenes de compra</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Crear</li>
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
                            <input type="text" id="descriptionQuote" onkeyup="mayus(this);" name="code_description" class="form-control form-control-sm" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="description">Código de cotización <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="codeQuote" onkeyup="mayus(this);" name="code_quote" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-4" id="sandbox-container">
                            <label for="date_quote">Fecha de cotización <span class="right badge badge-danger">(*)</span></label>
                            <div class="input-daterange" id="datepicker">
                                <input type="text" class="form-control form-control-sm date-range-filter" id="date_quote" name="date_quote" >
                            </div>
                        </div>
                        <div class="col-md-4" id="sandbox-container">
                            <label for="date_end">Válido hasta <span class="right badge badge-danger">(*)</span></label>
                            <div class="input-daterange" id="datepicker2">
                                <input type="text" class="form-control form-control-sm date-range-filter" id="date_validate" name="date_validate" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="description">Forma de pago <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="paymentQuote" onkeyup="mayus(this);" name="way_to_pay" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-4">
                            <label for="description">Tiempo de entrega <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm" >
                        </div>
                        <div class="col-md-4">
                            <label for="customer_id">Proveedor <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm" >
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
                    <h3 class="card-title">Materiales faltantes</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th>Und</th>
                            <th>Precio Unit.</th>
                            <th>Total sin Imp.</th>
                            <th>Total Imp.</th>
                            <th>Importe</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < $array_materials; $i++)
                            <tr>
                                <td data-code>{{ $array_materials[$key]->material_complete->code }}</td>
                                <td data-description>{{ $material[$key]->material }}</td>
                                <td data-quantity>John Doe</td>
                                <td data-unit>11-7-2014</td>
                                <td data-price>11-7-2014</td>
                                <td data-subtotal>11-7-2014</td>
                                <td data-taxes>11-7-2014</td>
                                <td data-total>11-7-2014</td>
                                <td>
                                    <button type="button" data-delete="" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
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
                    <h3 class="card-title">Detalles de compra</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <strong>Material</strong>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                                <strong>Precio</strong>
                            </div>
                        </div>
                        <div class="col-md-1">
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
                    <div data-bodyMaterials>

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
                        <td ><span class="moneda">PEN</span> <span id="subtotal">0.00</span> </td>
                    </tr>
                    <tr>
                        <th>Igv: </th>
                        <td ><span class="moneda">PEN</span> <span id="taxes">0.00</span> </td>
                    </tr>
                    <tr>
                        <th>Total: </th>
                        <td ><span class="moneda">PEN</span> <span id="total">0.00</span> </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>

    <template id="materials-selected">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <div class="form-group">
                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-materialDescription readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <div class="form-group">
                        <input type="text" onkeyup="mayus(this);" class="form-control form-control-sm" data-materialUnit readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-materialLargo step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-materialAncho step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                            this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                            " readonly>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm" placeholder="0.00" min="0" data-materialQuantity step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
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
