@extends('layouts.appAdmin2')

@section('openAccess')
    menu-open
@endsection

@section('activeAccess')
    active
@endsection

@section('activeRoles')
    active
@endsection

@section('title')
    Roles
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
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Accesos</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Editar rol</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('role.index') }}"><i class="fa fa-key"></i> Roles</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-edit"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('role.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="role_id" value="{{ $role->id }}">
        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Código del Rol <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Ejm: admin" value="{{ $role->name }}">
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Descripción del Rol <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" placeholder="Ejm: Administrador" value="{{ $role->description }}">
                </div>
            </div>

        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="inputEmail3" class="col-sm-12 col-form-label">Permisos</label>
            </div>
        </div>
        <div class="form-group row">
            @foreach( $groups as $group )
            <div class="col-12 col-sm-6 col-md-4">
                <!-- checkbox -->

                <div class="form-group">
                    <p class="mt-2 mb-1">{{ $group['name'] }}</p>
                    @foreach( $permissions as $permission )
                        @if ( substr($permission->name, strpos($permission->name, '_')+1) === $group['group'] )
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" id="permission{{ $permission->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->name, $permissionsSelected) ? 'checked' : '' }}>
                                <label for="permission{{ $permission->id }}" class="custom-control-label">{{ $permission->description }}</label>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
            @endforeach
        </div>

        <div class="text-center">
            @role('admin')
            <button type="submit" class="btn btn-outline-success">Guardar</button>
            @endrole
            <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
        </div>
        <!-- /.card-footer -->
    </form>
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
    <script src="{{ asset('js/role/edit.js') }}"></script>
@endsection
