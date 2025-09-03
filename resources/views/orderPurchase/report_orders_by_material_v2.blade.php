@extends('layouts.appAdmin2')

@section('openOrderPurchaseReports')
    menu-open
@endsection

@section('activeOrderPurchaseReports')
    active
@endsection

@section('activeReportOrderPurchaseByMaterial')
    active
@endsection

@section('title')
    Reportes de Ordenes de compra
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
    <h1 class="page-title">Reportes de Órdenes de Compra</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Reportes de Órdenes por Material</h5>

    @hasanyrole('admin|principal|cordinator_operations')
    <button type="button" id="btn-export" class="btn btn-outline-primary btn-sm float-right mr-2" > <i class="far fa-file-excel"></i> Descargar Excel </button>
    @endhasanyrole
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-archive"></i> Reporte de Órdenes de Compra </li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <!--begin::Form-->
    <form action="#">
        <!--begin::Card-->
        <!--begin::Input group-->
        <div class="row">

            <div class="col-md-2">
                <label for="year">Año de la Orden:</label>
                <select id="year" class="form-control select2" style="width: 100%;">
                    <option value="">TODOS</option>
                    @for ($i=0; $i<count($arrayYears); $i++)
                        <option value="{{ $arrayYears[$i] }}">{{ $arrayYears[$i] }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-8">
                <label for="material">Materiales:</label>
                <select id="material" name="material" class="form-control select2" style="width: 100%;">
                    <option value="">TODOS</option>
                    @for ($i=0; $i<count($arrayMaterials); $i++)
                        <option value="{{ $arrayMaterials[$i]['id'] }}">{{ $arrayMaterials[$i]['code']." ".$arrayMaterials[$i]['full_name'] }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <label for="quote">&nbsp;</label><br>
                <button class="btn btn-primary btn-block" type="button" id="btn-search">Buscar</button>
            </div>

        </div>

        <div class="row">
            <div class="col-md-4">
                <label for="campoExtra">Fechas de Orden:</label>
                <div class="col-md-12" id="sandbox-container">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="form-control form-control-sm date-range-filter" id="start" name="start" autocomplete="off">
                        <span class="input-group-addon">&nbsp;&nbsp;&nbsp; al &nbsp;&nbsp;&nbsp; </span>
                        <input type="text" class="form-control form-control-sm date-range-filter" id="end" name="end" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

    </form>
    <!--end::Form-->

    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack pb-7">
        <!--begin::Title-->
        <div class="d-flex flex-wrap align-items-center my-1">
            <h3 class="fw-bolder me-5 my-1"><span id="numberItems"></span> Órdenes de Compra
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
            <table class="table table-bordered letraTabla table-hover table-sm mb-5">
                <thead>
                <tr class="normal-title">
                    <th>N°</th>
                    <th>Año</th>
                    <th>Observación</th>
                    <th>Fecha de orden</th>
                    <th>Fecha Llegada</th>
                    <th>Proveedor</th>
                    <th>Aprovado Por</th>
                    <th>Tipo / Estado</th>
                    <th>Moneda</th>
                    {{--<th>Material</th>--}}
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
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
            <td data-code></td>
            <td data-year></td>
            <td data-observation></td>
            <td data-date_order></td>
            <td data-date_arrival></td>
            <td data-supplier></td>
            <td data-approved_user></td>
            <td data-state></td>
            <td data-currency></td>
            {{--<td data-material></td>--}}
            <td data-quantity></td>
            <td data-price></td>
            <td data-total></td>
        </tr>
    </template>

    <template id="item-table-empty">
        <tr>
            <td colspan="12" align="center">No se ha encontrado ningún dato</td>
        </tr>
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

            $('#material').select2({
                placeholder: "Selecione Material",
                allowClear: true
            });

        })
    </script>
    <script src="{{ asset('js/orderPurchase/report_orders_by_material_v2.js') }}?v={{ time() }}"></script>

@endsection