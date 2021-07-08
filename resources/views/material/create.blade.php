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
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" id="description" name="description" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="feature">Característica <span class="right badge badge-danger">(*)</span></label>
                                <select id="feature" name="type" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="1" selected>Ninguno</option>
                                    <option value="2">Metálico</option>

                                </select>

                            </div>
                        </div>
                        <div class="form-group row" id="feature-body" style="display: none">
                            <div class="col-md-3">
                                <label for="type">Tipo </label>
                                <select id="type" name="type" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="1" selected>Ninguno</option>
                                    <option value="2">Roscable</option>
                                    <option value="3">Soldable</option>
                                    <option value="4">Acero</option>
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="material">Material </label>
                                <select id="material" name="material" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="1" selected>Ninguno</option>
                                    <option value="2">INOX</option>
                                    <option value="3">FEGA</option>
                                    <option value="4">FENE</option>
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="cedula">Cédula </label>
                                <select id="cedula" name="cedula" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="1" selected>Ninguno</option>
                                    <option value="2">SCH40</option>
                                    <option value="3">SCH10</option>
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="quality">Calidad </label>
                                <select id="quality" name="quality" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="1" selected>Ninguno</option>
                                    <option value="2">C-304</option>
                                    <option value="3">C-316</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="measure">Medida <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="measure" name="measure" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre completo</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control rounded-0" id="name" name="name">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-info btn-flat" id="btn-generate"> <i class="fa fa-redo"></i> Actualizar</button>
                                </span>
                            </div>
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
                        <!--<div class="form-group">
                            <label for="serie">N° de serie </label>
                            <input type="text" id="serie" name="serie" class="form-control">
                        </div>-->
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
                            <input type="text" id="stock_min" name="stock_min" class="form-control">
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
                                <option></option>
                                @foreach( $categories as $category )
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="brand">Marca <span class="right badge badge-danger">(*)</span></label>
                            <select id="brand" name="brand" class="form-control select2" style="width: 100%;">
                                <option></option>
                                @foreach( $brands as $brand )
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampler">Modelo <span class="right badge badge-danger">(*)</span></label>
                            <select id="exampler" name="exampler" class="form-control select2" style="width: 100%;">

                            </select>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Especificaciones (Opcional)</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-5">
                                <label for="specification">Especificación <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" id="specification" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <label for="content">Contenido <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" id="content" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="btn-add"> &nbsp; </label>
                                <button type="button" id="btn-add" class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                            </div>

                        </div>
                        <hr>
                        <div id="body-specifications"></div>
                        <template id="template-specification">
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <input type="text" data-name name="specifications[]" class="form-control">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" data-content name="contents[]" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" data-delete="btn-delete" class="btn btn-block btn-outline-danger">Quitar <i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </template>
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
            });
            $('#category').select2({
                placeholder: "Selecione categoría",
            });
            $('#brand').select2({
                placeholder: "Selecione una marca",
            });
            $('#feature').select2({
                placeholder: "Seleccione característica",
            });
            $('#type').select2({
                placeholder: "Elija",
            });
            $('#material').select2({
                placeholder: "Elija",
            });
            $('#cedula').select2({
                placeholder: "Elija",
            });
            $('#quality').select2({
                placeholder: "Elija",
            })
        })
    </script>
    <script src="{{ asset('js/material/create.js') }}"></script>
@endsection
