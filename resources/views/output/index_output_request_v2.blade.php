@extends('layouts.appAdmin2')

@section('openOutputRequest')
    menu-open
@endsection

@section('activeOutputRequest')
    active
@endsection

@section('activeListOutputRequest')
    active
@endsection

@section('title')
    Solicitud de salida
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
    <h1 class="page-title">Listado General de Solicitudes</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de Solicitudes de salidas</h5>
    @can('create_request')
        <a href="{{ route('output.request.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nueva solicitud Regular</a>
    @endcan
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-archive"></i> Solicitudes de salida </li>
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
                    <input type="text" id="code" class="form-control" placeholder="Código de la solicitud..." autocomplete="off">
                    <div class="input-group-append ">
                        <button class="btn btn-primary" type="button" id="btn-search">Buscar</button>
                        <a href="#" id="btnBusquedaAvanzada" class="vertical-center ml-3 mt-2">Búsqueda Avanzada</a>
                    </div>
                </div>

                <!-- Sección de búsqueda avanzada (inicialmente oculta) -->
                <div class="mt-3 busqueda-avanzada">
                    <!-- Aquí coloca más campos de búsqueda avanzada -->
                    <div class="row">

                        <div class="col-md-2">
                            <label for="year">Año de la solicitud:</label>
                            <select id="year" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arrayYears); $i++)
                                    <option value="{{ $arrayYears[$i] }}">{{ $arrayYears[$i] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="quote">Cotizaciones:</label>
                            <select id="quote" name="quote" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arrayQuotes); $i++)
                                    <option value="{{ $arrayQuotes[$i]['id'] }}">{{ $arrayQuotes[$i]['code']."-".$arrayQuotes[$i]['description_quote'] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="execution_order">Orden de Ejecución:</label>
                            <input type="text" id="execution_order" class="form-control form-control-sm" placeholder="791-" autocomplete="off">

                        </div>
                        <div class="col-md-3">
                            <label for="code_quote">Código de Cotización:</label>
                            <input type="text" id="code_quote" class="form-control form-control-sm" placeholder="791-" autocomplete="off">

                        </div>


                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="description_quote">Descripción de Cotización:</label>
                            <input type="text" id="description_quote" class="form-control form-control-sm" placeholder="791-" autocomplete="off">

                        </div>

                        <div class="col-md-4">
                            <label for="type">Tipo de Solicitud:</label>
                            <select id="type" name="type" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @foreach ($arrayTypes as $type)
                                    <option value="{{ $type['value'] }}">{{ $type['display'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="state">Estado de la solicitud:</label>
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
                            <label for="requesting_user">Usuario Solicitante:</label>
                            <select id="requesting_user" name="requesting_user" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arrayUsers); $i++)
                                    <option value="{{ $arrayUsers[$i]['id'] }}">{{ $arrayUsers[$i]['name'] }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="responsible_user">Usuario Responsable:</label>
                            <select id="responsible_user" name="responsible_user" class="form-control form-control-sm select2" style="width: 100%;">
                                <option value="">TODOS</option>
                                @for ($i=0; $i<count($arrayUsers); $i++)
                                    <option value="{{ $arrayUsers[$i]['id'] }}">{{ $arrayUsers[$i]['name'] }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="campoExtra">Fechas de solicitud:</label>
                            <div class="col-md-12" id="sandbox-container">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="start" name="start" autocomplete="off">
                                    <span class="input-group-addon">&nbsp;&nbsp;&nbsp; al &nbsp;&nbsp;&nbsp; </span>
                                    <input type="text" class="form-control form-control-sm date-range-filter" id="end" name="end" autocomplete="off">
                                </div>
                            </div>
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
            <h3 class="fw-bolder me-5 my-1"><span id="numberItems"></span> Solicitudes de salida
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
                    <th>Orden de ejecución</th>
                    <th>Cotización</th>
                    <th>Descripción</th>
                    <th>Fecha de solicitud</th>
                    <th>Usuario solicitante</th>
                    <th>Usuario responsable</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
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
            <td data-execution_order></td>
            <td data-quote></td>
            <td data-description></td>
            <td data-request_date></td>
            <td data-requesting_user></td>
            <td data-responsible_user></td>
            <td data-typeText></td>
            <td data-stateText></td>
            <td data-buttons></td>
        </tr>
    </template>

    <template id="item-table-empty">
        <tr>
            <td colspan="10" align="center">No se ha encontrado ningún dato</td>
        </tr>
    </template>

    <template id="template-attended">
        <button data-materiales_cotizacion data-toggle="tooltip" data-placement="top" title="Materiales en la cotización" data-materials="{{--'+item.execution_order+'--}}" class="btn btn-outline-info btn-sm"><i class="fas fa-hammer"></i> </button>
        <button data-ver_materiales_pedidos data-toggle="tooltip" data-placement="top" title="Ver materiales pedidos" data-details="{{--'+item.id+'--}}" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus-square"></i> </button>
        <button data-editar_orden_ejecucion data-toggle="tooltip" data-placement="top" title="Editar orden de ejecución" data-edit="{{--' + item.id + '--}}" data-execution_order="' + item.execution_order + '" class="btn btn-outline-secondary btn-sm"><i class="fas fa-edit"></i> </button>
    </template>

    <template id="template-created">
        <button data-materiales_cotizacion data-toggle="tooltip" data-placement="top" title="Materiales en la cotización" data-materials="{{--'+item.execution_order+'--}}" class="btn btn-outline-info btn-sm"><i class="fas fa-hammer"></i> </button>
        <button data-ver_materiales_pedidos data-toggle="tooltip" data-placement="top" title="Ver materiales pedidos" data-details="{{--'+item.id+'--}}" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus-square"></i> </button>
        <button data-anular_total data-toggle="tooltip" data-placement="top" title="Anular total" data-deleteTotal="{{--'+item.id+'--}}" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i> </button>
        <button data-anular_parcial data-toggle="tooltip" data-placement="top" title="Anular parcial" data-deletePartial="{{--'+item.id+'--}}" class="btn btn-outline-warning btn-sm"><i class="fa fa-trash"></i> </button>
        <button data-anular_cantidad data-toggle="tooltip" data-placement="top" title="Anular por Cantidad" data-deleteQuantity="{{--'+item.id+'--}}" class="btn bg-orange color-palette btn-sm"><i class="fa fa-trash"></i> </button>
        <button data-atender data-toggle="tooltip" data-placement="top" title="Atender" data-attend="{{--' + item.id + '--}}" class="btn btn-outline-success btn-sm"><i class="fa fa-check-square"></i> </button>
        <button data-editar_orden_ejecucion data-toggle="tooltip" data-placement="top" title="Editar orden de ejecución" data-edit="{{--' + item.id + '--}}" data-execution_order="{{--' + item.execution_order + '--}}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-edit"></i> </button>
    </template>

    <div id="modalDeleteTotal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar eliminación total</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDeleteTotal" data-url="{{ route('output.request.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="output_id" name="output_id">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Importante!</strong> Se van a eliminar permanentemente todos los items solicitados y la solicitud será eliminada.
                            </div>
                        </div>
                        <h5>¿Está seguro de eliminar esta salida?</h5>
                        <h5 id="descriptionDeleteTotal"></h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalDeletePartial" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Quitar items de la solicitud</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Importante!</strong> Al hacer click en eliminar se eliminará en la base de datos.
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Código</th>
                            <th>Material</th>
                            <th>Largo</th>
                            <th>Ancho</th>
                            <th>Porcentaje</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table-itemsDelete">

                        </tbody>
                        <template id="template-itemDelete">
                            <tr>
                                <td data-i></td>
                                <td data-code></td>
                                <td data-material></td>
                                <td data-length></td>
                                <td data-width></td>
                                <td data-percentage></td>
                                <td >
                                    <button type="button" data-itemDelete data-output class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Quitar</button>
                                </td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalDeleteQuantity" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Quitar items de la solicitud</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Importante!</strong> Agregue la cantidad a anular.
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Código</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th>Anular</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table-itemsDeleteQuantity">

                        </tbody>
                        <template id="template-itemDeleteQuantity">
                            <tr>
                                <td data-i></td>
                                <td data-code></td>
                                <td data-material></td>
                                <td data-quantity></td>
                                <td >
                                    <input type="text" data-anular class="form-control">
                                </td>
                                <td >
                                    <button type="button" data-itemDeleteQuantity data-output class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Quitar</button>
                                </td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalReturnMaterials" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Retornar items de la solicitud</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Importante!</strong> Al hacer click en retornar se modificará la base de datos.
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Código</th>
                            <th>Material</th>
                            <th>Largo</th>
                            <th>Ancho</th>
                            <th>Porcentaje</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table-itemsReturn">

                        </tbody>
                        <template id="template-itemReturn">
                            <tr>
                                <td data-i></td>
                                <td data-code></td>
                                <td data-material></td>
                                <td data-length></td>
                                <td data-width></td>
                                <td data-percentage></td>
                                <td >
                                    <button type="button" data-itemReturn data-output class="btn btn-sm btn-success"><i class="fa fa-trash"></i> Devolver</button>
                                </td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @can('attend_request')
        <div id="modalAttend" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Atender solicitud</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="formAttend" data-url="{{ route('output.attend') }}">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="output_id" name="output_id">
                            <strong>
                                ¿Está seguro de atender esta solicitud de salida?
                            </strong>
                            <p id="descriptionAttend"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-submit" class="btn btn-primary" >Atender</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" >Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <div id="modalItems" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Listado de items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body table-responsive" style="height: 300px;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Material</th>
                            <th>Código</th>
                            <th>Crear Item</th>
                            <th>Largo</th>
                            <th>Ancho</th>
                            <th>Precio</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody id="table-items">

                        </tbody>
                        <template id="template-item">
                            <tr>
                                <td data-i></td>
                                <td data-material></td>
                                <td data-code></td>
                                <td>
                                    <button type="button" data-itemCustom class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Crear</button>
                                </td>
                                <td data-length></td>
                                <td data-width><span class="badge bg-danger">55%</span></td>
                                <td data-price></td>
                                <td data-location></td>
                                <td data-state></td>
                            </tr>
                        </template>
                    </table>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Código</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody id="table-materiales">

                        </tbody>
                        <template id="template-materiale">
                            <tr>
                                <td data-i></td>
                                <td data-code></td>
                                <td data-material></td>
                                <td data-quantity></td>
                            </tr>
                        </template>
                    </table>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Código</th>
                            <th>Consumible</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody id="table-consumables">

                        </tbody>
                        <template id="template-consumable">
                            <tr>
                                <td data-i></td>
                                <td data-code></td>
                                <td data-material></td>
                                <td data-quantity></td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalItemsMaterials" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Listado de materiales <span id="code_quote"></span> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body table-responsive" style="height: 300px;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Código</th>
                            <th>Material</th>
                            <th>Largo</th>
                            <th>Ancho</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody id="table-items-quote">

                        </tbody>
                        <template id="template-item-quote">
                            <tr>
                                <td data-i></td>
                                <td data-code></td>
                                <td data-material></td>
                                <td data-length></td>
                                <td data-width></td>
                                <td data-quantity></td>

                            </tr>
                        </template>
                    </table>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Código</th>
                            <th>Consumible</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody id="table-consumables-quote">

                        </tbody>
                        <template id="template-consumable-quote">
                            <tr>
                                <td data-i></td>
                                <td data-code></td>
                                <td data-material></td>
                                <td data-quantity></td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar orden de ejecución</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" data-url="{{ route('output.edit.execution') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="output_id" name="output_id">
                        <label for="execution_order">Orden de ejecución <span class="right badge badge-danger">(*)</span></label>
                        <input type="text" id="execution_order" name="execution_order" value="" class="form-control">

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn-submitEdit" class="btn btn-primary" >Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" >Cancelar</button>
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

            $('#quote').select2({
                placeholder: "Selecione Cotización",
                allowClear: true
            });

            $('#type').select2({
                placeholder: "Selecione Tipo",
                allowClear: true
            });

            $('#state').select2({
                placeholder: "Seleccione Estado",
                allowClear: true
            });

            $('#requesting_user').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

            $('#responsible_user').select2({
                placeholder: "Seleccione",
                allowClear: true
            });

        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>

    <script src="{{ asset('js/output/index_output_request_v2.js') }}?v={{ time() }}"></script>

@endsection