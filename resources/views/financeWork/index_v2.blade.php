@extends('layouts.appAdmin2')

@section('openFinanceWorks')
    menu-open
@endsection

@section('activeFinanceWorks')
    active
@endsection

@section('activeListFinanceWorks')
    active
@endsection

@section('title')
    Trabajos Finanzas
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

@section('page-title')
    <h5 class="card-title">Listado de trabajos para Finanzas</h5>

    <button type="button" id="btn-export" class="btn btn-outline-success btn-sm float-right" > <i class="far fa-file-excel"></i> Descargar Excel </button>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('proforma.index') }}"><i class="fa fa-archive"></i> Pre Cotizaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <input type="hidden" id="rate" value="{{ $rate }}">
    <!--begin::Form-->
    <form action="#">
        <!--begin::Card-->
        <!--begin::Input group-->
        <div class="row">
            <div class="col-md-12">
                <!-- Barra de búsqueda -->
                <div class="input-group">
                    <input type="text" id="description" class="form-control" placeholder="Descripción del trabajo..." autocomplete="off">
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
                            <label for="year">Año del Trabajo:</label>
                            <select id="year" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arrayYears); $i++)
                                    <option value="{{ $arrayYears[$i] }}">{{ $arrayYears[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="code">Código del Trabajo:</label>
                            <input type="text" id="code" class="form-control form-control-sm" placeholder="412" autocomplete="off">

                        </div>
                        <div class="col-md-3">
                            <label for="customer">Cliente:</label>
                            <select id="customer" name="customer" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arrayCustomers); $i++)
                                    <option value="{{ $arrayCustomers[$i]['id'] }}">{{ $arrayCustomers[$i]['business_name'] }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="stateWork">Avance del Trabajo:</label>
                            <select id="stateWork" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($arrayStateWorks as $stateWork)
                                    <option value="{{ $stateWork['value'] }}">{{ $stateWork['display'] }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="year_factura">Año Facturación:</label>
                            <select id="year_factura" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach( $years as $year )
                                    <option value="{{ $year->year }}">{{ $year->year}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="month_factura">Mes Facturación:</label>
                            <select id="month_factura" class="form-control form-control-sm select2" style="width: 100%;">
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

                        <div class="col-md-3">
                            <label for="year_abono">Año Abono:</label>
                            <select id="year_abono" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach( $years as $year )
                                    <option value="{{ $year->year }}">{{ $year->year}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="month_abono">Mes Abono:</label>
                            <select id="month_abono" class="form-control form-control-sm select2" style="width: 100%;">
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

                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="state">Estado Factura:</label>
                            <select id="stateInvoiced" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($arrayStates as $state)
                                    <option value="{{ $state['value'] }}">{{ $state['display'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="campoExtra">Fechas de Cotización:</label>
                            <div class="col-md-12" id="sandbox-container">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="start" name="start" autocomplete="off">
                                    <span class="input-group-addon">&nbsp;&nbsp;&nbsp; al &nbsp;&nbsp;&nbsp; </span>
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="end" name="end" autocomplete="off">
                                </div>
                            </div>
                        </div>
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
            <h3 class="fw-bolder me-5 my-1"><span id="numberItems"></span> Ingresos Clientes encontrados
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
                    <th colspan="1" class="normal-title"></th>
                    <th colspan="3" class="cliente-title">INFORMACIÓN DEL CLIENTE</th>
                    <th colspan="7" class="trabajo-title">INFORMACIÓN DEL TRABAJO</th>
                    <th colspan="4" class="documentacion-title">DOCUMENTACIÓN</th>
                    <th colspan="10" class="importe-title">IMPORTE $</th>
                    <th colspan="9" class="facturacion-title">FACTURACIÓN</th>
                    <th colspan="5" class="abono-title">PAGO/ABONO</th>
                    <th colspan="3" class="normal-title"></th>
                </tr>

                <tr class="normal-title">
                    <th>Año</th>

                    <th>Cliente</th>
                    <th>Responsable</th>
                    <th>Área</th>

                    <th>N° Cotización</th>
                    <th>Tipo</th>
                    <th>N° O.C / O.S</th>
                    <th>Descripción</th>
                    <th>Inicio</th>
                    <th>Entrega</th>
                    <th>Estado del Trabajo</th>

                    <th>Acta Aceptacion</th>
                    <th>Estado Acta Aceptacion</th>
                    <th>Docier Calidad</th>
                    <th>H.E.S</th>

                    <th>Adelanto</th>
                    <th>Monto Adelanto</th>
                    <th>Moneda</th>
                    <th>Subtotal</th>
                    <th>I.G.V</th>
                    <th>Precio Total</th>
                    <th>S.P.O.T.</th>
                    <th>Monto S.P.O.T.</th>
                    <th>Dscto. Factoring</th>
                    <th>Monto A Abonar</th>

                    <th>Condición de Pago</th>
                    <th>Facturado</th>
                    <th>N° Factura</th>
                    <th>Año Fact.</th>
                    <th>Mes Fact.</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Ingreso</th>
                    <th>Días</th>
                    <th>Fecha Programado</th>

                    <th>Banco</th>
                    <th>Estado Factura</th>
                    <th>Año Abono</th>
                    <th>Mes Abono</th>
                    <th>Fecha Pago</th>

                    <th>Observación</th>
                    <th>Revisión / VB</th>
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

            <td data-customer></td>
            <td data-responsible></td>
            <td data-area></td>

            <td data-quote></td>
            <td data-type></td>
            <td data-order_customer></td>
            <td data-description></td>
            <td data-initiation></td>
            <td data-delivery></td>
            <td data-state_work></td>

            <td data-act_of_acceptance></td>
            <td data-state_act_of_acceptance></td>
            <td data-docier></td>
            <td data-hes></td>

            <td data-advancement></td>
            <td data-amount_advancement></td>
            <td data-currency></td>
            <td data-subtotal></td>
            <td data-igv></td>
            <td data-total></td>
            <td data-detraction></td>
            <td data-amount_detraction></td>
            <td data-discount_factoring></td>
            <td data-amount_include_detraction></td>

            <td data-pay_condition></td>
            <td data-invoiced></td>
            <td data-number_invoice></td>
            <td data-year_invoice></td>
            <td data-month_invoice></td>
            <td data-date_issue></td>
            <td data-date_admission></td>
            <td data-days></td>
            <td data-date_programmed></td>

            <td data-bank></td>
            <td data-state></td>
            <td data-year_paid></td>
            <td data-month_paid></td>
            <td data-date_paid></td>
            <td data-observation></td>
            <td data-revision></td>

            <td>
                <button data-formEditTrabajo="" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar Información Trabajo"><i class="fas fa-tools fa-lg"></i></button>
                <button data-formEditFacturacion="" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar Información Facturación"><i class="fas fa-donate fa-lg"></i></button>
            </td>
        </tr>
    </template>

    <template id="item-table-empty">
        <tr>
            <td colspan="41" align="center">No se ha encontrado ningún ingreso</td>
        </tr>
    </template>

    <div id="modalEditTrabajo" class="modal fade" tabindex="-1">
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

    <div id="modalEditFacturacion" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar información de facturación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEditFacturacion" data-url="{{ route('finance.work.edit.facturacion') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="financeWork_id" name="financeWork_id">
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="advancement">Adelanto:</label>
                                <select id="advancement" name="advancement" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="y">SI</option>
                                    <option value="n">NO</option>
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <label for="amount_advancement">Monto de Adelanto:</label>
                                <input type="number" name="amount_advancement" id="amount_advancement" step="0.01" min="0" class="form-control" >
                            </div>

                            <div class="col-sm-4">
                                <label for="discount_factoring">Dscto. Factoring:</label>
                                <input type="number" name="discount_factoring" id="discount_factoring" step="0.01" min="0" class="form-control" >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="invoiced">Facturado:</label>
                                <select id="invoiced" name="invoiced" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="y">FACTURADO</option>
                                    <option value="n">NO FACTURADO</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="number_invoice">N° de Factura:</label>
                                <input type="text" name="number_invoice" class="form-control" id="number_invoice">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label for="date_issue">Fecha de Emisión</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_issue" name="date_issue" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label for="month_invoice">Mes Facturación:</label>
                                <select id="month_invoice" name="month_invoice" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
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
                            <div class="col-sm-4">
                                <label for="year_invoice">Año Facturación:</label>
                                <select id="year_invoice" name="year_invoice" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach( $years as $year )
                                        <option value="{{ $year->year }}">{{ $year->year}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="date_admission">Fecha de Ingreso</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_admission" name="date_admission" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="bank_id">Banco:</label>
                                <select id="bank_id" name="bank_id" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach( $banks as $bank )
                                        <option value="{{ $bank->id }}">{{ $bank->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label for="state">Estado:</label>
                                <select id="state" name="state" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="pending">PENDIENTE DE ABONADO</option>
                                    <option value="canceled">ABONADO</option>
                                </select>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm-4">
                                <label for="date_paid">Fecha de Pago</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_paid" name="date_paid" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="month_paid">Mes Abono:</label>
                                <select id="month_paid" name="month_paid" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
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
                            <div class="col-sm-4">
                                <label for="year_paid">Año Abono:</label>
                                <select id="year_paid" name="year_paid" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach( $years as $year )
                                        <option value="{{ $year->year }}">{{ $year->year}}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="observation">Observación:</label>
                                <textarea name="observation" id="observation" class="form-control"></textarea>
                            </div>
                            <div class="col-sm-6">
                                <label for="revision">Revisión / VB:</label>
                                <select id="revision" name="revision" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="pending">PENDIENTE</option>
                                    <option value="revised">REVISADO</option>
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
            $('#date_issue').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#date_admission').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#date_paid').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#date_initiation').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            //$('#date_delivery').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            //Initialize Select2 Elements
            $('#year').select2({
                placeholder: "Selecione año",
                allowClear: true
            });

            $('#state_work').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#act_of_acceptance').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#state_act_of_acceptance').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#docier').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#detraction').select2({
                placeholder: "Selecione detracción",
                allowClear: true
            });

            $('#customer').select2({
                placeholder: "Selecione cliente",
                allowClear: true
            });

            $('#stateWork').select2({
                placeholder: "Selecione estado",
                allowClear: true
            });

            $('#state').select2({
                placeholder: "Selecione estado",
                allowClear: true
            });

            $('#stateInvoiced').select2({
                placeholder: "Selecione estado",
                allowClear: true
            });
            $('#year_factura').select2({
                placeholder: "Selecione año",
                allowClear: true
            });

            $('#month_factura').select2({
                placeholder: "Selecione mes",
                allowClear: true
            });

            $('#year_abono').select2({
                placeholder: "Selecione año",
                allowClear: true
            });

            $('#month_abono').select2({
                placeholder: "Selecione mes",
                allowClear: true
            });

            $('#advancement').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#invoiced').select2({
                placeholder: "Seleccione",
                allowClear: true,
            });

            $('#bank_id').select2({
                placeholder: "Seleccione",
                allowClear: true,
            });

            $('#month_invoice').select2({
                placeholder: "Seleccione",
                allowClear: true,
            });
            $('#year_invoice').select2({
                placeholder: "Seleccione",
                allowClear: true,
            });

            $('#month_paid').select2({
                placeholder: "Seleccione",
                allowClear: true,
            });
            $('#year_paid').select2({
                placeholder: "Seleccione",
                allowClear: true,
            });

            $('#revision').select2({
                placeholder: "Seleccione",
                allowClear: true,
            });
        })
    </script>
    <script src="{{ asset('js/financeWork/indexV2.js') }}"></script>

@endsection