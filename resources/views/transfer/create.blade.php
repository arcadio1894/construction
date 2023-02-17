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
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/typehead/typeahead.css') }}">
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
        .modal-dialog {
            height: 100% !important;
        }

        .modal-content {
            height: auto;
            min-height: 100%;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Transferencias</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nueva transferencia</h5>
    <a href="{{ route('transfer.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Regresar </a>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('transfer.index') }}"><i class="fa fa-archive"></i> Transferencias</a>
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
                                <label for="shelf">Anaquel <span class="right badge badge-danger">(*)</span></label>
                                <select id="shelf" name="shelf_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="level">Nivel <span class="right badge badge-danger">(*)</span></label>
                                <select id="level" name="level_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="container">Contenedor <span class="right badge badge-danger">(*)</span></label>
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
                                    {{--<input type="text" id="material_search" class="form-control rounded-0 typeahead">--}}
                                    <select id="material_search" name="material_search" class="form-control select2" style="width: 100%;">
                                        <option></option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="btn-add"> &nbsp; </label>
                                <button type="button" id="btn-addItems" class="btn btn-block btn-outline-primary">Agregar <i class="fas fa-arrow-circle-right"></i></button>

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
                                                <th>Material</th>
                                                <th>Item</th>
                                                <th>Largo</th>
                                                <th>Ancho</th>
                                                <th>Ubicación</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody id="body-materials">


                                            </tbody>
                                            <template id="item-selected">
                                                <tr>
                                                    <td data-description></td>
                                                    <td data-item></td>
                                                    <td data-length></td>
                                                    <td data-width></td>
                                                    <td data-location></td>
                                                    <td data-state></td>
                                                    <td>
                                                        <button data-deleteItem="" class="btn btn-danger">Eliminar</button>
                                                    </td>
                                                </tr>
                                            </template>
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
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar transferencia</button>
            </div>
        </div>
        <!-- /.card-footer -->
    </form>

    <div id="modalAddItems" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Seleccionar items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="col-sm-12 control-label" for="material_selected"> Material </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_selected" name="material_selected" class="form-control" />
                                <input type="hidden" id="material_selected_id" name="material_selected_id" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="col-sm-12 control-label" for="material_selected_quantity"> Cantidad </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_selected_quantity" name="material_selected_quantity" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-2" id="show_btn_request_quantity">
                            <label class="col-sm-12 control-label" for="material_selected_quantity"> &nbsp;&nbsp;&nbsp; </label>

                            <div class="col-sm-12">
                                <button type="button" id="btn-request-quantity" class="btn btn-outline-primary">Pedir <i class="fas fa-arrow-circle-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="col-sm-12 control-label"> Items y ubicaciones </label>
                        </div>
                    </div>

                    <div id="body-items-load" class="table-responsive p-0" style="height: 300px;">
                        <table class="card-body table table-head-fixed text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Serie</th>
                                <th>Ubicación</th>
                                <th>Largo</th>
                                <th>Ancho</th>
                                <th>Selección</th>
                            </tr>
                            </thead>
                            <tbody id="body-items">


                            </tbody>
                            <template id="template-item">
                                <tr>
                                    <td data-id></td>
                                    <td data-serie></td>
                                    <td data-location></td>
                                    <td data-length></td>
                                    <td data-width></td>
                                    <td>
                                        <div class="icheck-success d-inline">
                                            <input type="checkbox" data-selected id="checkboxSuccess1">
                                            <label for="checkboxSuccess1" data-label></label>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-saveItems" class="btn btn-outline-primary">Agregar</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
    <script src="{{asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>

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
            });
            $('#material_search').select2({
                placeholder: "Selecione un material",
            })
        })
    </script>
    <script src="{{ asset('js/transfer/create.js') }}"></script>
@endsection
