@extends('layouts.appAdmin2')

@section('openConfigRH')
    menu-open
@endsection

@section('activeConfigRH')
    active
@endsection

@section('openWorkFunction')
    menu-open
@endsection

@section('activeCreateWorkFunction')
    active
@endsection

@section('title')
    Cargos
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
    <h1 class="page-title">Cargos / Funciones</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear cargo</h5>
    <a href="{{ route('workFunctions.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de Cargos</a>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('workFunctions.index') }}"><i class="fa fa-archive"></i> Cargos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('workFunctions.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-keyboard"></i></span>
                    </div>
                    <input id="description" type="text" class="form-control" name="description" >
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="button" id="btn-submit" class="btn btn-outline-success">Guardar</button>
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
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>

@endsection

@section('scripts')
    <script src="{{ asset('js/workFunction/create.js') }}"></script>
@endsection
