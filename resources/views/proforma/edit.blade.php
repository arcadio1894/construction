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
    <h5 class="card-title">Editar Pre Cotización {{ $proforma->code }}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('proforma.index') }}"><i class="fa fa-key"></i> Pre Cotizaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <form id="formCreate" class="form-horizontal" data-url="{{ route('proforma.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">DATOS GENERALES</h3>
                        <input type="hidden" id="customer_proforma_id" value="{{ $proforma->customer_id }}">
                        <input type="hidden" id="contact_proforma_id" value="{{ $proforma->contact_id }}">
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="descriptionQuote">Descripción general de cotización </label>
                                <input type="text" id="descriptionQuote" onkeyup="mayus(this);" name="code_description" class="form-control form-control-sm" value="{{ $proforma->description_quote }}">
                                <input type="hidden" name="proforma_id" value="{{ $proforma->id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="codeQuote">Código de Pre Cotización </label>
                                <input type="text" id="codeQuote" readonly value="{{ $proforma->code }}" onkeyup="mayus(this);" name="code_quote" class="form-control form-control-sm">
                            </div>
                            @hasanyrole('logistic|admin|principal|quote_single')
                            <div class="col-md-4" id="sandbox-container">
                                <label for="date_quote">Fecha de cotización </label>
                                <div class="input-daterange" id="datepicker">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="date_quote" name="date_quote" value="{{ date('d/m/Y', strtotime($proforma->date_quote)) }}">
                                </div>
                            </div>
                            <div class="col-md-4" id="sandbox-container">
                                <label for="date_validate">Válido hasta </label>
                                <div class="input-daterange" id="datepicker2">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="date_validate" name="date_validate" value="{{ date('d/m/Y', strtotime($proforma->date_validate)) }}">
                                </div>
                            </div>
                            @endhasanyrole

                            @hasanyrole('logistic|admin|principal|quote_single')
                            <div class="col-md-4">
                                <label for="paymentQuote">Forma de pago </label>
                                <select id="paymentQuote" name="payment_deadline" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $paymentDeadlines as $paymentDeadline )
                                        <option value="{{ $paymentDeadline->id }}" {{ ($paymentDeadline->id == $proforma->payment_deadline_id) ? 'selected':'' }}>{{ $paymentDeadline->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endhasanyrole
                            <div class="col-md-4">
                                <label for="timeQuote">Tiempo de entrega </label>
                                <input type="text" id="timeQuote" onkeyup="mayus(this);" name="delivery_time" class="form-control form-control-sm" value="{{ $proforma->delivery_time }}">
                            </div>
                            {{--@hasanyrole('logistic|admin')--}}
                            <div class="col-md-4">
                                <label for="customer_id">Cliente </label>
                                <select id="customer_id" name="customer_id" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $customers as $customer )
                                        <option value="{{ $customer->id }}" {{ ($customer->id == $proforma->customer_id) ? 'selected':'' }}>{{ $customer->business_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="contact_id">Contacto </label>
                                <select id="contact_id" name="contact_id" class="form-control form-control-sm select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="observations">Observaciones </label>
                                <textarea class="textarea_edit" id="observations" name="observations" data-detailequipment placeholder="Place some text here"
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $proforma->observations }}</textarea>
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
                                        <th >Acciones</th>
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
                                            <td>
                                                <button data-percentage type="button" class="btn btn-sm btn-outline-danger" data-acEquipment="{{ $equipment->id }}" data-utility="{{$equipment->utility}}" data-rent="{{$equipment->rent}}" data-letter="{{$equipment->letter}}" ><i class="fas fa-"></i></button>
                                                <a href="#" type="button" class="btn btn-sm btn-outline-danger" data-acEquipment="{{ $equipment->id }}" ><i class="fas fa-trash-alt"></i></a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-acDelete data-acEquipment="{{ $equipment->id }}" ><i class="fas fa-trash-alt"></i></button>
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
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-acDelete="" data-acEquipment="" ><i class="fas fa-trash-alt"></i></button>
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

        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar cotización</button>
            </div>
        </div>
        <!-- /.card-footer -->
    </form>

    <div id="modalAddEquipment" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Buscador de equipos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Barra de búsqueda -->
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="category">Categoría:</label>
                                    <select id="category" name="category" class="form-control select2" style="width: 100%;">
                                        <option></option>
                                        @foreach( $categories as $category )
                                            <option value="{{ $category->id }}">{{ $category->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-9">
                                    <label for="category">Equipos:</label>
                                    <div class="input-group">
                                        <input type="text" id="nameEquipment" class="form-control" placeholder="Nombre del equipo..." autocomplete="off">
                                        <div class="input-group-append ">
                                            <button class="btn btn-primary" type="button" id="btn-search">Buscar</button>
                                            <a href="#" id="btnBusquedaAvanzada" class="vertical-center ml-3 mt-2">Búsqueda Avanzada</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de búsqueda avanzada (inicialmente oculta) -->
                            <div class="mt-3 busqueda-avanzada">
                                <!-- Aquí coloca más campos de búsqueda avanzada -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="length">Largo:</label>
                                        <input type="number" class="form-control form-control-sm" id="length" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="width">Ancho:</label>
                                        <input type="number" class="form-control form-control-sm" id="width" autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="high">Alto:</label>
                                        <input type="number" class="form-control form-control-sm" id="high" autocomplete="off">
                                    </div>
                                </div>

                                <!-- Añade más campos según lo necesario -->
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" >
                        <div id="body-items-load" class="table-responsive p-0" style="height: 300px;">
                            <table class="card-body table table-head-fixed ">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Equipo</th>
                                    <th>Ancho</th>
                                    <th>Largo</th>
                                    <th>Alto</th>
                                    <th>Selección</th>
                                </tr>
                                </thead>
                                <tbody id="body-equipments">


                                </tbody>
                                <template id="template-equipment">
                                    <tr>
                                        <td data-id></td>
                                        <td data-equipo></td>
                                        <td data-ancho></td>
                                        <td data-largo></td>
                                        <td data-alto></td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" data-selected id="checkboxSuccess1">
                                                <label for="checkboxSuccess1" data-label></label>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template id="item-table-empty">
                                    <tr>
                                        <td colspan="6" align="center">No se ha encontrado ningún equipo.</td>
                                    </tr>
                                </template>
                            </table>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-saveItems" class="btn btn-outline-primary">Agregar</button>
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
                placeholder: 'Ingrese las observaciones',
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

            $('#category').select2({
                placeholder: "Seleccione categoria",
                allowClear: true
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

    <script src="{{ asset('js/proforma/edit.js') }}"></script>
@endsection
