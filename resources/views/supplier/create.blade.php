@extends('layouts.appAdmin2')

@section('openSupplier')
    menu-open
@endsection

@section('activeSupplier')
    active
@endsection

@section('activeCreateSupplier')
    active
@endsection

@section('title')
    Proveedores
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
    <h1 class="page-title">Proveedores</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nuevo proveedor</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('supplier.index') }}"><i class="fa fa-key"></i> Proveedores</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('supplier.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">RUC</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="ruc" placeholder="Ejm: 1234678901">
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Razon Social</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="business_name" placeholder="Ejm: Edesce EIRL">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Direccion</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="address" placeholder="Ejm: Jr Union">
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Teléfono</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="phone" placeholder="Ejm: 947812345">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" placeholder="Ejm: aaaa@gmail.com">
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-outline-success">Guardar</button>
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
    <script src="{{ asset('js/supplier/create.js') }}"></script>
@endsection
