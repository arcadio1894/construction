@extends('layouts.appAdmin2')

@section('openFinanceWorks')
    menu-open
@endsection

@section('activeFinanceWorks')
    active
@endsection

@section('activeListExpensesSupplier')
    active
@endsection

@section('title')
    Egresos Proveedores
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
            font-size: 14px; /* Tamaño de fuente 11 */
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
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Egresos Proveedores</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de Egresos Proveedores</h5>

    @can('export_expenseSupplier')
    <button type="button" id="btn-export" class="btn btn-outline-success btn-sm float-right" > <i class="far fa-file-excel"></i> Descargar Excel </button>
    @endcan
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('expenses.supplier.index') }}"><i class="fa fa-archive"></i> Egresos proveedores</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <!--begin::Form-->
    <form action="#">
        <!--begin::Card-->
        <!--begin::Input group-->
        <div class="row">
            <div class="col-md-12">
                <!-- Barra de búsqueda -->
                <div class="input-group">
                    <input type="text" id="number_order" class="form-control" placeholder="Orden de compra/servicio..." autocomplete="off">
                    <div class="input-group-append ">
                        <button class="btn btn-primary" type="button" id="btn-search">Buscar</button>
                        <a href="#" id="btnBusquedaAvanzada" class="vertical-center ml-3 mt-2">Búsqueda Avanzada</a>
                    </div>
                </div>

                <!-- Sección de búsqueda avanzada (inicialmente oculta) -->
                <div class="mt-3 busqueda-avanzada">
                    <!-- Aquí coloca más campos de búsqueda avanzada -->
                    <div class="row">

                        <div class="col-md-3">
                            <label for="supplier">Proveedor:</label>
                            <select id="supplier" name="supplier" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arraySuppliers); $i++)
                                    <option value="{{ $arraySuppliers[$i]['id'] }}">{{ $arraySuppliers[$i]['business_name'] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="date_due">Fecha de vencimiento:</label>
                            <div class="col-md-12" id="sandbox-container1">
                                <div class="input-daterange input-group" id="datepicker1">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="date_due" name="date_due" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="stateCredit">Estado de Crédito:</label>
                            <select id="stateCredit" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($arrayStateCredits as $stateCredit)
                                    <option value="{{ $stateCredit['value'] }}">{{ $stateCredit['display'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="statePaid">Estado de Pago:</label>
                            <select id="statePaid" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($arrayStatePaids as $statePaid)
                                    <option value="{{ $statePaid['value'] }}">{{ $statePaid['display'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-2">
                            <label for="year">Año de la orden:</label>
                            <select id="year" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arrayYears); $i++)
                                    <option value="{{ $arrayYears[$i] }}">{{ $arrayYears[$i] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="month_order">Mes de la orden:</label>
                            <select id="month_order" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="campoExtra">Fechas de Ordenes:</label>
                            <div class="col-md-12" id="sandbox-container">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="start" name="start" autocomplete="off">
                                    <span class="input-group-addon">&nbsp;&nbsp;&nbsp; al &nbsp;&nbsp;&nbsp; </span>
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="end" name="end" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="credito">Crédito / Contado:</label>
                            <select id="credito" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($arrayStateDeadlines as $arrayStateDeadline)
                                    <option value="{{ $arrayStateDeadline['value'] }}">{{ $arrayStateDeadline['display'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br>

                    <div class="row">

                    </div>

                    <!-- Añade más campos según lo necesario -->
                </div>
            </div>
        </div>
        <!--end::Input group-->
        <!--begin:Action-->
        {{--<div class="col-md-1">
            <label for="btn-search">&nbsp;</label><br>
            <button type="button" id="btn-search" class="btn btn-primary me-5">Buscar</button>
        </div>--}}

    </form>
    <!--end::Form-->

    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack pb-7">
        <!--begin::Title-->
        <div class="d-flex flex-wrap align-items-center my-1">
            <h3 class="fw-bolder me-5 my-1"><span id="numberItems"></span> Egresos proveedores encontrados
                <span class="text-gray-400 fs-6">por fecha de creación ↓ </span>
            </h3>
        </div>
        <!--end::Title-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Tab Content-->
    <div class="tab-content">
        <!--begin::Tab pane-->
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered letraTabla table-hover table-sm">
                <thead>
                <tr>
                    <th colspan="5" class="cliente-title">INFORMACIÓN DE LA ORDEN</th>
                    <th colspan="2" class="importe-title">IMPORTE</th>
                    <th colspan="7" class="facturacion-title">FACTURACIÓN</th>
                    <th colspan="1" class="normal-title"></th>
                </tr>

                <tr class="normal-title">

                    <th>Año</th>
                    <th>Mes</th>
                    <th>Fecha Orden</th>
                    <th>Proveedor</th>
                    <th>OC / OS</th>

                    <th>Soles</th>
                    <th>Dólares</th>

                    <th>Condición de Pago</th>
                    <th>N° Factura</th>
                    <th>Fecha Emisión</th>
                    <th>Crédito en Días</th>
                    <th>Fecha Vencimiento</th>
                    <th>Estado Crédito</th>
                    <th>Estado Pago</th>

                    <th></th>
                </tr>
                </thead>
                <tbody id="body-table">

                </tbody>
            </table>
        </div>
        <!--end::Tab pane-->
        <!--begin::Pagination-->
        <div class="d-flex flex-stack flex-wrap pt-1">
            <div class="fs-6 fw-bold text-gray-700" id="textPagination"></div>
            <!--begin::Pages-->
            <ul class="pagination" style="margin-left: auto;" id="pagination">

            </ul>
            <!--end::Pages-->
        </div>
        <!--end::Pagination-->
    </div>
    <!--end::Tab Content-->

    <template id="previous-page">
        <li class="page-item previous">
            <a href="#" class="page-link" data-item>
                <!--<i class="previous"></i>-->
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    </template>

    <template id="item-page">
        <li class="page-item" data-active>
            <a href="#" class="page-link" data-item="">5</a>
        </li>
    </template>

    <template id="next-page">
        <li class="page-item next">
            <a href="#" class="page-link" data-item>
                <!--<i class="next"></i>-->
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    </template>

    <template id="disabled-page">
        <li class="page-item disabled">
            <span class="page-link">...</span>
        </li>
    </template>

    <template id="item-table">
        <tr>
            <td data-year></td>
            <td data-month></td>
            <td data-date_order></td>
            <td data-supplier></td>
            <td data-order></td>

            <td data-soles></td>
            <td data-dolares></td>

            <td data-deadline></td>
            <td data-invoice></td>
            <td data-date_invoice></td>
            <td data-days></td>
            <td data-due_date></td>
            <td data-state_credit></td>
            <td data-state_paid></td>

            <td>
                <button data-formEditFacturacion="" data-type="" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar Información Facturación"><i class="far fa-edit"></i></button>
            </td>
        </tr>
    </template>

    <template id="item-table-empty">
        <tr>
            <td colspan="41" align="center">No se ha encontrado ningún egreso</td>
        </tr>
    </template>

    {{--<div id="modalEditTrabajo" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar información del trabajo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEditTrabajo" data-url="{{ route('finance.work.edit.trabajo') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="financeWork_id" name="financeWork_id">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="customer_id">Cliente:</label>
                                <select id="customer_id" name="customer_id" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    @for ($i=0; $i<count($arrayCustomers); $i++)
                                        <option value="{{ $arrayCustomers[$i]['id'] }}">{{ $arrayCustomers[$i]['business_name'] }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="contact_id">Contacto:</label>
                                <select id="contact_id" name="contact_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="date_initiation">Fecha Inicio:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_initiation" name="date_initiation" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="date_delivery">Fecha Entrega:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" readonly id="date_delivery" name="date_delivery" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="detraction">Detracción:</label>
                                <select id="detraction" name="detraction" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">Ninguno</option>
                                    <option value="oc">Orden de Compra</option>
                                    <option value="os">Orden de Servicio</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="state_work">Estado del Trabajo:</label>
                                <select id="state_work" name="state_work" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">NINGUNO</option>
                                    <option value="to_start">POR INICIAR</option>
                                    <option value="in_progress">EN PROCESO</option>
                                    <option value="finished">TERMINADO</option>
                                    <option value="stopped">PAUSADO</option>
                                    <option value="canceles">CANCELADO</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="act_of_acceptance">Acta de Aceptación:</label>
                                <select id="act_of_acceptance" name="act_of_acceptance" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">Ninguno</option>
                                    <option value="pending">Pendiente</option>
                                    <option value="generate">Generada</option>
                                    <option value="not_generate">No generada</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="state_act_of_acceptance">Estado Acta Aceptación:</label>
                                <select id="state_act_of_acceptance" name="state_act_of_acceptance" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">Ninguno</option>
                                    <option value="pending_signature">Pendiente de Firma</option>
                                    <option value="signed">Firmada</option>
                                    <option value="not_signed">No se firmará</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="docier">Docier de Calidad:</label>
                                <select id="docier" name="docier" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">Ninguno</option>
                                    <option value="pending">Pendiente de Firmar</option>
                                    <option value="signed">Firmada</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="hes">H.E.S:</label>
                                <input type="text" id="hes" name="hes" class="form-control">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnSubmitFormEditTrabajo" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
--}}
    <div id="modalEditFacturacion" class="modal fade" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar información de facturación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEditFacturacion" data-url="{{ route('expense.supplier.edit.facturacion') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="invoice_id" name="invoice_id">
                        <input type="hidden" id="type" name="type">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="state">Estado de pago:</label>
                                <select id="state" name="state" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="pending">PENDIENTE DE ABONAR</option>
                                    <option value="paid">ABONADO</option>
                                </select>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnSubmitFormEditFacturacion" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

            $('#supplier').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#stateCredit').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#statePaid').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            /*$('#year_order').select2({
                placeholder: "Selecione",
                allowClear: true
            });*/

            $('#month_order').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#state').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#credito').select2({
                placeholder: "Selecione",
                allowClear: true
            });

        })
    </script>
    <script src="{{ asset('js/expenseSupplier/indexV2.js') }}"></script>

@endsection