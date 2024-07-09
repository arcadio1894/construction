@extends('layouts.appAdmin2')

@section('openReferralGuide')
    menu-open
@endsection

@section('activeReferralGuide')
    active
@endsection

@section('activeListReferralGuide')
    active
@endsection

@section('title')
    Guías de Remisión
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
    <h1 class="page-title">Guías de remisión</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de guías de remisión</h5>

    @can('create_referralGuide')
        <a href="{{ route('referral.guide.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nueva Guía de remisión </a>
    @endcan
    @can('download_referralGuide')
        <button type="button" id="btn-download" class="btn btn-outline-success btn-sm float-right mr-2" > <i class="fas fa-download"></i> Exportar guías</button>
    @endcan
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('referral.guide.index') }}"><i class="fa fa-archive"></i> Guías de Remisión</a>
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
                    <input type="text" id="code" class="form-control" placeholder="Código de la guía de remisión..." autocomplete="off">
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
                            <label for="year">Año de registro:</label>
                            <select id="year" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arrayYears); $i++)
                                    <option value="{{ $arrayYears[$i] }}">{{ $arrayYears[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="reason">Motivo de traslado:</label>
                            <select id="reason" name="reason" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($reasons as $reason)
                                    <option value="{{ $reason->id }}">{{ $reason->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="responsible">Responsable:</label>
                            <select id="responsible" name="responsible" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($responsibles as $responsible)
                                    <option value="{{ $responsible->id }}">{{ $responsible->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="state">Estado de la guía:</label>
                            <select id="state" name="state" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($arrayStates as $state)
                                    <option value="{{ $state['value'] }}">{{ $state['display'] }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="customer">Cliente:</label>
                            <select id="customer" name="customer" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->business_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="supplier">Proveedor:</label>
                            <select id="supplier" name="supplier" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->business_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="receiver">Destinatario:</label>
                            <input type="text" id="receiver" class="form-control form-control-sm" placeholder="Ingrese el nombre" autocomplete="off">

                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="campoExtra">Fechas de la Guía:</label>
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

    </form>
    <!--end::Form-->

    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack pb-7">
        <!--begin::Title-->
        <div class="d-flex flex-wrap align-items-center my-1">
            <h3 class="fw-bolder me-5 my-1"><span id="numberItems"></span> Guías de remisión encontradas
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
                <tr class="normal-title">
                    {{--<th>ID</th>--}}
                    <th>Código</th>
                    <th>Fecha de traslado</th>
                    <th>Motivo de traslado</th>
                    <th>Destinatario</th>
                    <th>RUC/DNI</th>
                    <th>Punto de Llegada</th>
                    <th>Placa</th>
                    <th>Conductor</th>
                    <th>Licencia</th>
                    <th>Responsable</th>
                    <th>Estado</th>
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
            {{--<td data-id></td>--}}
            <td data-code></td>
            <td data-date_transfer></td>
            <td data-reason></td>

            <td data-destination></td>
            <td data-document></td>

            <td data-puntoLlegada></td>
            <td data-placa></td>
            <td data-conductor></td>
            <td data-licencia></td>
            <td data-responsable></td>
            <td data-state></td>
            <td data-buttons></td>
        </tr>
    </template>

    <template id="item-table-empty">
        <tr>
            <td colspan="12" align="center">No se ha encontrado ningún dato</td>
        </tr>
    </template>

    <template id="template-active">
        <a data-ver_guide href="{{--'+document.location.origin+ '/dashboard/ver/cotizacion/'+item.id+'--}}" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Detalles"><i class="fa fa-eye"></i></a>
        <a data-imprimir_guide target="_blank" href="{{--' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +'--}}" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a>
        <a data-editar href="{{--'+document.location.origin+ '/dashboard/editar/cotizacion/'+item.id+'--}}" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pen"></i></a>
        <button data-anular data-delete="{{--'+item.id+'--}}" data-name="{{--'+item.description_quote+'--}}" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-trash"></i></button>
    </template>

    <template id="template-inactive">
        <a data-ver_guide href="{{--'+document.location.origin+ '/dashboard/ver/cotizacion/'+item.id+'--}}" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Detalles"><i class="fa fa-eye"></i></a>
        <a data-imprimir_guide target="_blank" href="{{--' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +'--}}" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a>
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

            $('#customer').select2({
                placeholder: "Selecione",
                allowClear: true
            });
            $('#reason').select2({
                placeholder: "Selecione",
                allowClear: true
            });
            $('#responsible').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#state').select2({
                placeholder: "Selecione",
                allowClear: true
            });

            $('#supplier').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

        })
    </script>
    <script src="{{ asset('js/referralGuide/index.js') }}?v={{ time() }}"></script>

@endsection