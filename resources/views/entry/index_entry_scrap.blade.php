@extends('layouts.appAdmin2')

@section('openEntryScrap')
    menu-open
@endsection

@section('activeEntryScrap')
    active
@endsection

@section('activeListEntryScrap')
    active
@endsection

@section('title')
    Entradas por compras
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
    <h1 class="page-title">Entradas por retazos</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de entradas</h5>
    <a href="{{ route('entry.scrap.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo ingreso </a>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-key"></i> Entradas por retazos </li>
    </ol>
@endsection

@section('content')
    <div>
        <div class="row">
            {{--<div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="1" class="custom-control-input" id="customSwitch1">
                <label class="custom-control-label" for="customSwitch1">Código</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="2" class="custom-control-input" id="customSwitch2">
                <label class="custom-control-label" for="customSwitch2">Descripcion</label>
            </div>--}}
            {{--<div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="2" class="custom-control-input" id="customSwitch3">
                <label class="custom-control-label" for="customSwitch3">Medida</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="3" class="custom-control-input" id="customSwitch4">
                <label class="custom-control-label" for="customSwitch4">Unidad Medida</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="4" class="custom-control-input" id="customSwitch5">
                <label class="custom-control-label" for="customSwitch5">Stock Max</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="5" class="custom-control-input" id="customSwitch6">
                <label class="custom-control-label" for="customSwitch6">Stock Min</label>
            </div>--}}
            {{--<div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="3" class="custom-control-input" id="customSwitch7">
                <label class="custom-control-label" for="customSwitch7">Stock Actual</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="4" class="custom-control-input" id="customSwitch8">
                <label class="custom-control-label" for="customSwitch8">Prioridad</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="5" class="custom-control-input" id="customSwitch9">
                <label class="custom-control-label" for="customSwitch9">Precio</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="6" class="custom-control-input" id="customSwitch10">
                <label class="custom-control-label" for="customSwitch10">Imagen</label>
            </div>--}}
            {{--<div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="10" class="custom-control-input" id="customSwitch11">
                <label class="custom-control-label" for="customSwitch11">Categoría</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="11" class="custom-control-input" id="customSwitch12">
                <label class="custom-control-label" for="customSwitch12">Tipo material</label>
            </div>--}}

        </div>

    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
            <thead>
            <tr>
                <th></th>
                <th>Código</th>
                <th>Tipo de entrada</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('js/entry/index_entry_scrap.js') }}"></script>
@endsection
