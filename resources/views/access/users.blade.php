@extends('layouts.appAdmin')

@section('openAccess')
    active
@endsection

@section('activeUser')
    active
@endsection

@section('title')
    Usuarios
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
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home font-14"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-users font-14"></i> Usuarios</li>
    </ul>
@endsection

@section('content')
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Listado de usuarios</div>
            <button id="newUser" class="btn btn-outline-success" > <i class="fa fa-plus font-20"></i> Nuevo usuario </button>
        </div>
        <div class="ibox-body">
            <table class="table" id="dynamic-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Image</th>
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
                    <h4 class="modal-title">Nuevo usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formCreate" class="form-horizontal" data-url="{{ route('user.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="name"> Nombre </label>

                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Ejm: Jorge Gonzales" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="description"> Email </label>

                            <div class="col-sm-12">
                                <input type="email" id="email" name="email" class="form-control" placeholder="Ejm: user@construction.com" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="image"> Imagen </label>

                            <div class="col-sm-12">
                                <input type="file" id="image" name="image" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="roles"> Roles </label>

                            <div class="col-sm-12">
                                <select multiple="" name="roles[]" class="select2 form-control" style="width: 100%"  id="roles" >
                                    @foreach( $roles as $role )
                                        <option value="{{$role->name}}">{{ $role->description }}</option>
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
                    <h4 class="modal-title">Modificar usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" class="form-horizontal" data-url="{{ route('user.update') }}" >
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nameE"> Nombre </label>

                            <div class="col-sm-12">
                                <input type="text" id="nameE" name="name" class="form-control" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="emailE"> Correo electrónico </label>

                            <div class="col-sm-12">
                                <input type="email" id="emailE" name="email" class="form-control" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="imageE"> Imagen </label>

                            <div class="col-sm-12">
                                <input type="file" id="imageE" name="image" class="form-control" />
                                <img src="" id="image-preview" width="100px" height="100px" alt="Imagen previsualizacion">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="rolesE"> Roles </label>

                            <div class="col-sm-12">
                                <select multiple="" name="roles[]" class="select2 form-control" style="width: 100%" id="rolesE" >

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
                <form id="formDelete" data-url="{{ route('user.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="user_id">
                        <p id="nameDelete"></p>
                        <p id="emailDelete"></p>
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
    <script src="{{ asset('js/user/index.js') }}"></script>
@endsection