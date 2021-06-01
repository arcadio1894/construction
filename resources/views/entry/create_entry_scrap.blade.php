@extends('layouts.appAdmin2')

@section('openEntryScrap')
    menu-open
@endsection

@section('activeEntryScrap')
    active
@endsection

@section('activeCreateEntryScrap')
    active
@endsection

@section('title')
    Entrada por Retacería
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/typehead/typeahead.css') }}">
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Entrada por retacería</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nueva entrada por retacería</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('entry.scrap.index') }}"><i class="fa fa-key"></i> Entradas por retacería</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nueva entrada</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('entry.scrap.store') }}" enctype="multipart/form-data">
        @csrf
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
    <div id="modalAddItems" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Seleccionar item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-sm-12 control-label" for="material_selected"> Material </label>

                            <div class="col-sm-12">
                                <input type="text" id="material_selected" name="material_selected" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="col-sm-12 control-label" for="length"> Largo </label>

                            <div class="col-sm-12">
                                <input type="text" id="length" name="length" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="col-sm-12 control-label" for="width"> Ancho </label>

                            <div class="col-sm-12">
                                <input type="text" id="width" name="width" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="col-sm-12 control-label" for="weight"> Peso </label>

                            <div class="col-sm-12">
                                <input type="text" id="weight" name="weight" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="col-sm-12 control-label"> Buscar item </label>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                    <div class="col-sm-6 offset-3">
                        <div class="col-md-12">
                            <input type="text" id="item_selected" data-series class="form-control items" />
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-saveItems" class="btn btn-outline-primary">Agregar</button>
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

    <script src="{{ asset('js/entry/entry_scrap.js') }}"></script>
@endsection
