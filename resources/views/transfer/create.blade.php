@extends('layouts.appAdmin2')

@section('openTransfer')
    menu-open
@endsection

@section('activeTransfer')
    active
@endsection

@section('activeCreateTransfer')
    active
@endsection

@section('title')
    Transferencias
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
    <h1 class="page-title">Transferencias</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nuevo transferencias</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('transfer.index') }}"><i class="fa fa-key"></i> Transferencias</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('transfer.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Ubicación de destino</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="area">Área <span class="right badge badge-danger">(*)</span></label>
                                <select id="area" name="area_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $areas as $area )
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="warehouse">Almacén <span class="right badge badge-danger">(*)</span></label>
                                <select id="warehouse" name="warehouse_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="shelf">Estante <span class="right badge badge-danger">(*)</span></label>
                                <select id="shelf" name="shelf_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="level">Fila <span class="right badge badge-danger">(*)</span></label>
                                <select id="level" name="level_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="container">Columna <span class="right badge badge-danger">(*)</span></label>
                                <select id="container" name="container_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="position">Posición <span class="right badge badge-danger">(*)</span></label>
                                <select id="position" name="position_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Materiales</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="material_search">Buscar material <span class="right badge badge-danger">(*)</span></label>
                                    <input type="text" id="material_search" class="form-control rounded-0 typeahead">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="btn-add"> &nbsp; </label>
                                <button type="button" id="btn-add" class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Materiales</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body table-responsive p-0" style="height: 300px;">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Material</th>
                                                <th>Item</th>
                                                <th>Ubicación</th>
                                                <th>Estado</th>
                                                <th>Precio</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody id="body-items">
                                            <template id="item-selected">
                                                <tr>
                                                    <td data-id>183</td>
                                                    <td data-material>John Doe</td>
                                                    <td data-item>John Doe</td>
                                                    <td data-location>John Doe</td>
                                                    <td data-state>John Doe</td>
                                                    <td data-price>John Doe</td>
                                                    <td>
                                                        <button data-deleteItem="" class="btn btn-danger">Eliminar</button>
                                                    </td>
                                                </tr>
                                            </template>

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
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
            $('#area').select2({
                placeholder: "Selecione una área",
            });
            $('#warehouse').select2({
                placeholder: "Selecione un almacén",
            });
            $('#shelf').select2({
                placeholder: "Selecione un estante",
            });
            $('#level').select2({
                placeholder: "Selecione una fila",
            });
            $('#container').select2({
                placeholder: "Selecione una columna",
            });
            $('#position').select2({
                placeholder: "Selecione una posición",
            })
        })
    </script>
    <script src="{{ asset('js/transfer/create.js') }}"></script>
@endsection
