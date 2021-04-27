@extends('layouts.appAdmin')

@section('openAccess')
    active
@endsection

@section('activeRoles')
    active
@endsection

@section('title')
    Roles
@endsection

@section('styles')
    <link href="{{ asset('admin/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('header-page')
    <h1 class="page-title">Accesos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-home font-14"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <i class="fa fa-shield font-14"></i> Roles
        </li>
    </ol>
@endsection

@section('content')
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Listado de roles</div>
            <button id="newRole" class="btn btn-outline-success" > <i class="fa fa-plus font-20"></i> Nuevo rol </button>
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
                    <h4 class="modal-title">Nuevo role</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formCreate" class="form-horizontal" data-url="{{ route('role.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="name"> Role </label>

                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Ejm: admin" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="description"> Descripción </label>

                            <div class="col-sm-12">
                                <input type="text" id="description" name="description" class="form-control" placeholder="Ejm: Administrador" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="permissions"> Permisos </label>

                            <div class="col-sm-12">
                                <select multiple="" name="permissions[]" class="select2 form-control" style="width: 100%" id="permissions" >
                                    @foreach( $permissions as $permission )
                                        <option value="{{$permission->name}}">{{ $permission->description }}</option>
                                    @endforeach
                                </select>

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
                    <h4 class="modal-title">Modificar rol</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" class="form-horizontal" data-url="{{ route('role.update') }}" >
                    @csrf
                    <input type="hidden" name="role_id" id="role_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label " for="nameE"> Rol </label>

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
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="permissions"> Permisos </label>

                            <div class="col-sm-12">
                                <select multiple="" name="permissions[]" class="select2 form-control" style="width: 100%" id="permissionsE" >

                                </select>

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
                <form id="formDelete" data-url="{{ route('role.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="role_id" name="role_id">
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
    <script src="{{ asset('admin/vendors/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/vendors/DataTables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/role/index.js') }}"></script>
@endsection
