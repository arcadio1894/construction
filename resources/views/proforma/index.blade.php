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
    Pre - Cotizaciones
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
    <h5 class="card-title">Listado de Pre Cotizaciones</h5>

    <a href="{{ route('proforma.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nueva Pre-Cotización </a>

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

    <!--begin::Form-->
    <form action="#">
        <!--begin::Card-->
        <!--begin::Input group-->
        <div class="row">
            <div class="col-md-12">
                <!-- Barra de búsqueda -->
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Descripción de la pre cotización..." autocomplete="off">
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
                            <label for="code">Código:</label>
                            <input type="text" class="form-control form-control-sm" id="code" placeholder="PCOT-00001" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label for="deadline">Condición de pago:</label>
                            <select id="deadline" name="payment_deadline" class="form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                @foreach( $paymentDeadlines as $paymentDeadline )
                                    <option value="{{ $paymentDeadline->id }}">{{ $paymentDeadline->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="customer">Cliente:</label>
                            <select id="customer" name="customer" class="form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                @foreach( $customers as $customer )
                                    <option value="{{ $customer->id }}">{{ $customer->business_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="campoExtra">Fechas de cotización:</label>
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
            <h3 class="fw-bolder me-5 my-1"><span id="numberItems"></span> Pre Cotizaciones encontradas
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
            <table class="table table-bordered table-hover table-sm">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Fecha Cotización</th>
                    <th>Fecha Válida</th>
                    <th>Forma Pago</th>
                    <th>Tiempo Entrega</th>
                    <th>Cliente</th>
                    <th>Total Con IGV</th>
                    <th>Total Sin IGV</th>
                    <th>Moneda</th>
                    <th>Estado</th>
                    <th>Fecha Creación</th>
                    <th>Creador</th>
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
                <i class="bi bi-chevron-left"></i>
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
                <i class="bi bi-chevron-right"></i>
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
            <td data-id>ID</td>
            <td data-code>Código</td>
            <td data-description>Descripción</td>
            <td data-date_quote>Fecha Cotización</td>
            <td data-date_validate>Fecha Válida</td>
            <td data-deadline>Forma Pago</td>
            <td data-delivery_time>Tiempo Entrega</td>
            <td data-customer>Cliente</td>
            <td data-total_con_igv>Total Con IGV</td>
            <td data-total_sin_igv>Total Sin IGV</td>
            <td data-currency>Moneda</td>
            <td data-state>Estado</td>
            <td data-created_at>Fecha Creación</td>
            <td data-creator>Creador</td>
            <td>
                <a href="#" data-show class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Detalles"><i class="fa fa-eye"></i></a>
                <a target="_blank" href="" data-print class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a>
                <a href="#" data-edit class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-edit"></i></a>
                <button data-confirm="" data-description="" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Confirmar"><i class="fa fa-check"></i></button>
                <button data-delete="" data-description="" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    </template>

    <template id="item-table-empty">
        <tr>
            <td colspan="15" align="center">No se ha encontrado ninguna Pre Cotización</td>
        </tr>
    </template>

    <div id="modalDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('refund.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="refund_id" name="refund_id">
                        <strong> ¿Desea eliminar este reembolso? </strong>
                        <p id="code"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submit" class="btn btn-danger">Eliminar</button>
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


@endsection

@section('scripts')
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#customer').select2({
                placeholder: "Selecione cliente",
                allowClear: true
            });
            $('#deadline').select2({
                placeholder: "Selecione forma de pago",
                allowClear: true
            });


        })
    </script>
    <script src="{{ asset('js/proforma/index.js') }}"></script>

@endsection