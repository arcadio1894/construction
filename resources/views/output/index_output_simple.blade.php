@extends('layouts.appAdmin2')

@section('openOutputSimple')
    menu-open
@endsection

@section('activeOutputSimple')
    active
@endsection

@section('activeListOutputSimple')
    active
@endsection

@section('title')
    Solicitud de salida
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
        td.details-control {
            background: url('/admin/plugins/datatables/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.details td.details-control {
            background: url('/admin/plugins/datatables/resources/details_close.png') no-repeat center center;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Solicitudes de área</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de solicitudes de área</h5>
    @can('create_requestSimple')
    <a href="{{ route('output.simple.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nueva solicitud de área </a>
    @endcan

    @can('confirm_requestSimple')
        <button id="btn-allconfirm" data-url="{{ route('output.simple.confirm.all') }}" class="btn btn-outline-warning btn-sm float-right" > <i class="fas fa-check-double"></i> Confirmar solicitudes atendidas </button>
    @endcan
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-archive"></i> Solicitudes de área </li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
            <thead>
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>Fecha de solicitud</th>
                <th>Usuario solicitante</th>
                <th>Usuario responsable</th>
                <th>Área solicitante</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div id="modalDeleteTotal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar eliminación total</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDeleteTotal" data-url="{{ route('output.simple.destroy') }}">
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
                        <button type="button" id="btn-totalDelete" class="btn btn-danger">Eliminar</button>
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
                    <h4 class="modal-title">Editar descripción de solicitud</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" data-url="{{ route('output.simple.edit.description') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="output_id" name="output_id">
                        <label for="description">Orden de ejecución <span class="right badge badge-danger">(*)</span></label>
                        <input type="text" id="description" name="description" value="" class="form-control">

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
@endsection

@section('scripts')
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('js/output/index_output_simple.js') }}"></script>
@endsection
