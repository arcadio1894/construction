@extends('layouts.appAdmin2')

@section('openMaterial')
    menu-open
@endsection

@section('activeMaterial')
    active
@endsection

@section('activeListMaterial')
    active
@endsection

@section('title')
    Materiales
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
    <h1 class="page-title">Materiales</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de materiales</h5>
    @can('create_material')
    <a href="{{ route('material.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo material </a>
    @endcan
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-archive"></i> Materiales </li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div>
        <div class="row">
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="1" class="custom-control-input" id="customSwitch1">
                <label class="custom-control-label" for="customSwitch1">Código</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="2" class="custom-control-input" id="customSwitch2">
                <label class="custom-control-label" for="customSwitch2">Descripcion</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="3" class="custom-control-input" id="customSwitch3">
                <label class="custom-control-label" for="customSwitch3">Medida</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="4" class="custom-control-input" id="customSwitch4">
                <label class="custom-control-label" for="customSwitch4">Unidad Medida</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="5" class="custom-control-input" id="customSwitch5">
                <label class="custom-control-label" for="customSwitch5">Stock Max</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="6" class="custom-control-input" id="customSwitch6">
                <label class="custom-control-label" for="customSwitch6">Stock Min</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="7" class="custom-control-input" id="customSwitch7">
                <label class="custom-control-label" for="customSwitch7">Stock Actual</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="8" class="custom-control-input" id="customSwitch8">
                <label class="custom-control-label" for="customSwitch8">Prioridad</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="9" class="custom-control-input" id="customSwitch9">
                <label class="custom-control-label" for="customSwitch9">Precio</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="10" class="custom-control-input" id="customSwitch10">
                <label class="custom-control-label" for="customSwitch10">Imagen</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="11" class="custom-control-input" id="customSwitch11">
                <label class="custom-control-label" for="customSwitch11">Categoría</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="12" class="custom-control-input" id="customSwitch12">
                <label class="custom-control-label" for="customSwitch12">SubCategoría</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="13" class="custom-control-input" id="customSwitch13">
                <label class="custom-control-label" for="customSwitch13">Tipo</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="14" class="custom-control-input" id="customSwitch14">
                <label class="custom-control-label" for="customSwitch14">SubTipo</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="15" class="custom-control-input" id="customSwitch15">
                <label class="custom-control-label" for="customSwitch15">Cédula</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="16" class="custom-control-input" id="customSwitch16">
                <label class="custom-control-label" for="customSwitch16">Calidad</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="17" class="custom-control-input" id="customSwitch17">
                <label class="custom-control-label" for="customSwitch17">Marca</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="18" class="custom-control-input" id="customSwitch18">
                <label class="custom-control-label" for="customSwitch18">Modelo</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="19" class="custom-control-input" id="customSwitch19">
                <label class="custom-control-label" for="customSwitch19">Retacería</label>
            </div>
        </div>

    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
            <thead>
            <tr>
                <th></th>
                <th>Código</th>
                <th>Descripcion</th>
                <th>Medida</th>
                <th>Unidad Medida</th>
                <th>Stock Max</th>
                <th>Stock Min</th>
                <th>Stock Actual</th>
                <th>Prioridad</th>
                <th>Precio Unitario</th>
                <th>Imagen</th>
                <th>Categoría</th>
                <th>SubCategoría</th>
                <th>Tipo</th>
                <th>SubTipo</th>
                <th>Cédula</th>
                <th>Calidad</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Retacería</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @can('destroy_material')
    <div id="modalDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('material.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="material_id" name="material_id">
                        <p>¿Está seguro de eliminar este material?</p>
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
    @endcan
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
    <script src="{{ asset('js/material/index.js') }}"></script>
@endsection
