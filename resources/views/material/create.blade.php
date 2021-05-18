@extends('layouts.appAdmin2')

@section('openMaterial')
    menu-open
@endsection

@section('activeMaterial')
    active
@endsection

@section('activeCreateMaterial')
    active
@endsection

@section('title')
    Materiales
@endsection

@section('styles-plugins')
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
    <h1 class="page-title">Materiales</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nuevo material</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('material.index') }}"><i class="fa fa-key"></i> Materiales</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('material.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Datos generales</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="description" name="description" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="measure">Medida <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="measure" name="measure" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="unit_measure">Unidad de medida <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="unit_measure" name="unit_measure" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="unit_price">Precio Unitario <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="unit_price" name="unit_price" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="image">Imagen <span class="right badge badge-danger">(*)</span></label>
                            <input type="file" id="image" name="image" class="form-control">
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Categoría y Stock</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="stock_max">Stock Máximo <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="stock_max" name="stock_max" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="stock_min">Stock Mínimo <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="stock_max" name="stock_max" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="material_type">Tipo de material <span class="right badge badge-danger">(*)</span></label>
                            <select id="material_type" name="material_type" class="form-control select2" style="width: 100%;">
                                @foreach( $materialTypes as $materialType )
                                    <option value="{{ $materialType->id }}">{{ $materialType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category">Categoría <span class="right badge badge-danger">(*)</span></label>
                            <select id="category" name="category" class="form-control select2" style="width: 100%;">
                                @foreach( $categories as $category )
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="submit" class="btn btn-outline-success float-right">Guardar material</button>
            </div>
        </div>
        <!-- /.card-footer -->
    </form>
@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#material_type').select2({
                placeholder: "Selecione tipo de material",
            })
            $('#category').select2({
                placeholder: "Selecione categoría",
            })
        })
    </script>
    <script src="{{ asset('js/material/create.js') }}"></script>
@endsection
