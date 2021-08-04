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
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

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
    <h5 class="card-title">Modificar material {{ $material->code }}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('material.index') }}"><i class="fa fa-key"></i> Materiales</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('material.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="material_id" value="{{ $material->id }}">

        <input type="hidden" id="category_id" value="{{$material->category_id}}">
        <input type="hidden" id="subcategory_id" value="{{$material->subcategory_id}}">
        <input type="hidden" id="type_id" value="{{$material->material_type_id}}">
        <input type="hidden" id="subtype_id" value="{{$material->subtype_id}}">

        <input type="hidden" id="exampler_id" value="{{$material->exampler_id}}">
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
                            <div class="col-md-12">
                                <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" id="description" name="description" class="form-control" value="{{ $material->description }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="measure">Medida <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" id="measure" name="measure" class="form-control" value="{{ $material->measure }}">
                            </div>
                            <div class="col-md-6">
                                <label for="unit_measure">Unidad de medida <span class="right badge badge-danger">(*)</span></label>
                                <select id="unit_measure" name="unit_measure" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $unitMeasures as $unitMeasure )
                                        <option value="{{ $unitMeasure->id }}" {{ ($unitMeasure->id === $material->unit_measure_id) ? 'selected': ''}}>{{ $unitMeasure->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="category">Categorías <span class="right badge badge-danger">(*)</span></label>
                                <select id="category" name="category" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $categories as $category )
                                        <option value="{{ $category->id }}" {{ ($category->id === $material->category_id) ? 'selected': ''}}>{{ $category->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-6">
                                <label for="subcategory">Subcategorías <span class="right badge badge-danger">(*)</span></label>
                                <select id="subcategory" name="subcategory" class="form-control select2" style="width: 100%;">
                                    <option></option>

                                </select>

                            </div>
                        </div>
                        <div class="form-group row" id="feature-body" style="display: none">
                            <div class="col-md-3">
                                <label for="type">Tipo </label>
                                <select id="type" name="type" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="subtype">Subtipo </label>
                                <select id="subtype" name="subtype" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="warrant">Cédula </label>
                                <select id="warrant" name="warrant" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="" selected>Ninguno</option>
                                    @foreach( $warrants as $warrant )
                                        <option value="{{$warrant->id}}" {{ ($warrant->id === $material->warrant_id) ? 'selected': ''}}>{{ $warrant->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="quality">Calidad </label>
                                <select id="quality" name="quality" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="" selected>Ninguno</option>
                                    @foreach( $qualities as $quality )
                                        <option value="{{$quality->id}}" {{ ($quality->id === $material->quality_id) ? 'selected' : '' }}>{{ $quality->name }}</option>
                                    @endforeach
                                </select>

                            </div>
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
                            <label for="unit_price">Precio Unitario </label>
                            <input type="text" id="unit_price" name="unit_price" class="form-control" value="{{ $material->unit_price }}">
                        </div>
                        <div class="form-group">
                            <label for="priority">Prioridad <span class="right badge badge-danger">(*)</span></label>
                            <select id="priority" name="priority" class="form-control select2" style="width: 100%;">
                                <option value="Aceptable" {{ $material->priority === 'Aceptable' ? 'selected' : '' }}>Aceptable</option>
                                <option value="Agotado" {{ $material->priority === 'Agotado' ? 'selected' : '' }}>Agotado</option>
                                <option value="Completo" {{ $material->priority === 'Completo' ? 'selected' : '' }}>Completo</option>
                                <option value="Por agotarse" {{ $material->priority === 'Por agotarse' ? 'selected' : '' }}>Por agotarse</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagen </label>
                            <input type="file" id="image" name="image" class="form-control">
                            <img src="{{ asset('images/material/'.$material->image) }}" width="100px" height="100px" alt="{{ $material->description }}">
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
                            <input type="text" id="stock_max" name="stock_max" class="form-control" value="{{ $material->stock_max }}">
                        </div>
                        <div class="form-group">
                            <label for="stock_min">Stock Mínimo <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="stock_min" name="stock_min" class="form-control" value="{{ $material->stock_min }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="stock_current">Stock Actual <span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="stock_current" name="stock_current" class="form-control" value="{{ $material->stock_current }}">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="btn-grouped"> Retacería </label> <br>
                            </div>
                            <div class="col-md-4">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="typescrap0" name="typescrap" value="" {{ ($material->typescrap_id === null) ? 'checked' : '' }}>
                                    <label for="typescrap0">Ninguno
                                    </label>
                                </div>
                            </div>
                            @foreach( $typescraps as $typescrap )
                                <div class="col-md-4">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="typescrap{{$typescrap->id}}" name="typescrap" value="{{$typescrap->id}}" {{ ($typescrap->id === $material->typescrap_id) ? 'checked' : '' }}>
                                        <label for="typescrap{{$typescrap->id}}">{{$typescrap->name}}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="brand">Marca </label>
                            <select id="brand" name="brand" class="form-control select2" style="width: 100%;">
                                <option></option>
                                @foreach( $brands as $brand )
                                    <option value="{{ $brand->id }}" {{ ($brand->id === $material->brand_id) ? 'selected':'' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampler">Modelo </label>
                            <select id="exampler" name="exampler" class="form-control select2" style="width: 100%;">

                            </select>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            {{--<div class="col-md-12">
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
                                <label for="specification">Especificación </label>
                                <input type="text" id="specification" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <label for="content">Contenido </label>
                                <input type="text" id="content" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="btn-add"> &nbsp; </label>
                                <button type="button" id="btn-add" class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                            </div>

                        </div>
                        <hr>
                        <div id="body-specifications"></div>
                        @foreach( $specifications as $specification )
                        <div class="form-group row">
                            <div class="col-md-5">
                                <input type="text" data-name name="specifications[]" value="{{ $specification->name }}" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <input type="text" data-content name="contents[]" value="{{ $specification->content }}" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <button type="button" data-delete="btn-delete" class="btn btn-block btn-outline-danger">Quitar <i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        @endforeach
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
            </div>--}}
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
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
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
            $('#subcategory').select2({
                placeholder: "Selecione subcategoría",
            });
            $('#brand').select2({
                placeholder: "Selecione una marca",
            });
            $('#exampler').select2({
                placeholder: "Selecione un modelo",
            });
            $('#priority').select2({
                placeholder: "Selecione una prioridad",
            });
            $('#feature').select2({
                placeholder: "Seleccione característica",
            });
            $('#type').select2({
                placeholder: "Elija",
            });
            $('#subtype').select2({
                placeholder: "Elija",
            });
            $('#warrant').select2({
                placeholder: "Elija",
            });
            $('#quality').select2({
                placeholder: "Elija",
            });
            $('#unit_measure').select2({
                placeholder: "Elija",
            });
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/material/edit.js') }}"></script>
@endsection
