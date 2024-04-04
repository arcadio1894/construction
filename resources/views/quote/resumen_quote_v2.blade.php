@extends('layouts.appAdmin2')

@section('openQuote')
    menu-open
@endsection

@section('activeQuote')
    active
@endsection

@section('activeResumeQuote')
    active
@endsection

@section('title')
    Resumen Cotizaciones
@endsection

@section('styles-plugins')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
        .letraTabla {
            font-family: "Calibri", Arial, sans-serif; /* Utiliza Calibri si está instalado, de lo contrario, usa Arial o una fuente sans-serif similar */
            font-size: 15px; /* Tamaño de fuente 11 */
        }
        .normal-title {
            background-color: #203764; /* Color deseado para el fondo */
            color: #fff; /* Color deseado para el texto */
            text-align: center;
        }
        .cliente-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .trabajo-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .documentacion-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .importe-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .facturacion-title {
            background-color: #FFC000; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .abono-title {
            background-color: #00B050; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
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
        .datepicker-orient-top {
            top: 100px !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Resumen de Cotizaciones</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Resumen de Totales de Cotizaciones</h5>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-archive"></i> Resumen de Cotizaciones </li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <!--begin::Form-->
    <form action="#">
        <!--begin::Card-->
        <!--begin::Input group-->
        <div class="row">

            <div class="col-md-8">
                <label for="quote">Cotizaciones:</label>
                <select id="quote" name="quote" class="form-control form-control-sm select2" style="width: 100%;">
                    <option value="">TODOS</option>
                    @for ($i=0; $i<count($arrayQuotes); $i++)
                        <option value="{{ $arrayQuotes[$i]['id'] }}">{{ $arrayQuotes[$i]['code']."-".$arrayQuotes[$i]['description_quote'] }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <label for="quote">&nbsp;</label><br>
                <button class="btn btn-primary btn-sm btn-block" type="button" id="btn-search">Buscar</button>
            </div>
            <div class="col-md-2">
                <label for="btn-download">&nbsp;</label><br>
                <button class="btn btn-success btn-sm btn-block" type="button" id="btn-download">Descargar PDF</button>
            </div>

        </div>

    </form>
    <!--end::Form-->
    <hr>
    <!--begin::Tab Content-->
    <div class="tab-content">
        <p class="lead">Resumen de Cotización</p>
        <!--begin::Resumen de equipos-->
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

                                </tbody>

                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>
            </div>
        </div>
        <!--end::Resumen de equipos-->

        <!--begin::Resumen por equipos-->
        <p class="lead">Resumen por Equipos</p>
        <div class="row" id="body-equipments">

        </div>

        <!--end::Resumen por equipos-->

        <!--begin::Total de cotizacion-->
        <div class="row">
            <!-- accepted payments column -->
            <div class="col-sm-7">

            </div>
            <!-- /.col -->
            <div class="col-sm-5">
                <p class="lead">Total de Cotización</p>

                <div class="table-responsive" id="body-total">

                </div>
            </div>
            <!-- /.col -->
        </div>
        <!--end::Total de cotizacion-->
    </div>
    <!--end::Tab Content-->

    <template id="template-summary">
        <tr>
            <td data-equipo></td>
            <td data-cantidad></td>
            <td data-subtotal_sin_igv></td>
            <td data-utilidad></td>
            <td data-gastos_varios></td>
            <td data-precio_unit_sin_igv></td>
            <td data-total_sin_igv></td>
        </tr>
    </template>

    <template id="template-equipment">
        <div class="card col-md-6">
            <div class="card-header">
                <h3 class="card-title" data-equipment> XXXXX </h3>
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
                        <td data-total_materials> 000000.00 </td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>CONSUMIBLES</td>
                        <td data-total_consumables> 000000.00 </td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>SERVICIOS VARIOS</td>
                        <td data-total_workforces> 000000.00 </td>
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>SERVICIOS ADICIONALES</td>
                        <td data-total_tornos> 000000.00 </td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>DÍAS DE TRABAJO</td>
                        <td data-total_dias> 000000.00 </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </template>

    <template id="template-total">
        <table class="table">
            <tr>
                <th style="width:50%">Total S/IGV: </th>
                <td data-total_sin_igv> 000000.00 </td>
            </tr>
            <tr>
                <th style="width:50%">Total C/IGV: </th>
                <td data-total_con_igv> 000000.00 </td>
            </tr>
            <tr>
                <th style="width:50%">Total+Utilidad S/IGV: </th>
                <td data-total_utilidad_sin_igv> 000000.00 </td>
            </tr>
            <tr>
                <th style="width:50%">Total+Utilidad C/IGV: </th>
                <td data-total_utilidad_con_igv> 000000.00 </td>
            </tr>

        </table>
    </template>

@endsection

@section('plugins')
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>

    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#year').select2({
                placeholder: "Selecione año",
                allowClear: true
            });

            $('#quote').select2({
                placeholder: "Selecione Cotización",
                allowClear: true
            });

        })
    </script>
    <script src="{{ asset('js/quote/resume_quote.js') }}"></script>

@endsection