@extends('layouts.appAdmin')

@section('openAccess')
    active
@endsection

@section('activePermissions')
    active
@endsection

@section('title')
    Permisos
@endsection

@section('styles')
    <link href="{{ asset('admin/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('header-page')
    <h1 class="page-title">Accesos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home font-14"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-lock font-14"></i> Permisos</li>
    </ol>
@endsection

@section('content')
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Listado de permisos</div>
            <button id="newPermission" class="btn btn-outline-success" > <i class="fa fa-plus font-20"></i> Nuevo permiso </button>
        </div>
        <div class="ibox-body">
            <table class="table" id="dynamic-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div id="modalCreate" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo permiso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formCreate" class="form-horizontal" data-url="{{ route('permission.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="name"> Permiso </label>

                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Ejm: product_list" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="description"> Descripción </label>

                            <div class="col-sm-12">
                                <input type="text" id="description" name="description" class="form-control" placeholder="Ejm: Listar productos" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar permiso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" class="form-horizontal" data-url="{{ route('permission.update') }}" >
                    @csrf
                    <input type="hidden" name="permission_id" id="permission_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label " for="nameE"> Permiso </label>

                            <div class="col-sm-12">
                                <input type="text" id="nameE" name="name" class="form-control" placeholder="Ejm: product_list" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="descriptionE"> Descripción </label>

                            <div class="col-sm-12">
                                <input type="text" id="descriptionE" name="description" class="form-control" placeholder="Ejm: Listar productos" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-primary">Guardar cambios</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div id="modalDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('permission.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="permission_id" name="permission_id">
                        <p id="nameDelete"></p>
                        <p id="descriptionDelete"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('admin/vendors/DataTables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/permission/index.js') }}"></script>
@endsection
