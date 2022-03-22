@extends('layouts.appAdmin2')

@section('openEntryScrap')
    menu-open
@endsection

@section('activeEntryScrap')
    active
@endsection

@section('activeCreateScrap')
    active
@endsection

@section('title')
    Items del material
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
    <h1 class="page-title">Items del material {{ $material->code }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de items</h5>
    {{--@can('create_entryScrap')
    <a href="{{ route('entry.scrap.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo ingreso </a>
    @endcan--}}
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('entry.create.scrap') }}"><i class="fa fa-box-open"></i> Materiales en retacería</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-boxes"></i> Items del material </li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="material_id" value="{{$material->id}}">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
            <thead>
            <tr>
                <th>Código</th>
                <th>Material</th>
                <th>Largo (mm)</th>
                <th>Ancho (mm)</th>
                <th>Precio Unit.</th>
                <th>Porcentaje</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div id="modalCreateScrap" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Creación de retazos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <form id="formScrap" data-url="{{ route('scrap.store') }}">
                    @csrf
                    <div class="modal-body table-responsive">
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Importante!</strong> Ingrese las medidas correctas. El item seleccionado se quitará y se crearán dos nuevos.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-9" id="name_material">
                                <input type="hidden" id="material_id" name="material_id">
                                <label class="col-sm-12 control-label" for="material"> Material </label>

                                <div class="col-sm-12">
                                    <input type="text" id="material" name="material" class="form-control form-control-sm" readonly />
                                </div>
                            </div>
                            <div class="col-md-3" id="price_material">
                                <label class="col-sm-12 control-label" for="price"> Precio en BD </label>

                                <div class="col-sm-12">
                                    <input type="text" id="price" name="price" class="form-control form-control-sm" readonly />
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <input type="hidden" id="idItem" name="idItem">
                            <input type="hidden" id="typescrap" name="typescrap">
                            <div class="col-md-4" id="code_item">
                                <label class="col-sm-12 control-label" for="code"> Código </label>

                                <div class="col-sm-12">
                                    <input type="text" id="code" name="code" class="form-control form-control-sm" readonly />
                                </div>
                            </div>
                            <div class="col-md-4" id="length_item">
                                <label class="col-sm-12 control-label" for="length"> Largo (mm) </label>

                                <div class="col-sm-12">
                                    {{--<input type="text" id="length" name="length" class="form-control" readonly />--}}
                                    <div class="input-group">
                                        <input type="text" id="length" name="length" class="form-control form-control-sm" readonly >
                                        <div class="input-group-append" id="show-block-length">
                                            <button data-toggle="tooltip" data-placement="top" title="Desbloqueado" class="btn btn-success btn-sm" type="button" id="btb-block-length"><i class="fa fa-lock-open"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="width_item">
                                <label class="col-sm-12 control-label" for="width"> Ancho (mm) </label>

                                <div class="col-sm-12">
                                    {{--<input type="text" id="width" name="width" class="form-control" readonly />--}}
                                    <div class="input-group">
                                        <input type="text" id="width" name="width" class="form-control form-control-sm" readonly >
                                        <div class="input-group-append" id="show-block-width">
                                            <button data-toggle="tooltip" data-placement="top" title="Desbloqueado" class="btn btn-success btn-sm" type="button" id="btb-block-width"><i class="fa fa-lock-open"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" >
                                <label class="col-sm-12 control-label"> Ingrese las nuevas longitudes </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" id="length_new_item">
                                <label class="col-sm-12 control-label" for="length_new"> Largo (mm) </label>

                                <div class="col-sm-12">
                                    <input type="number" id="length_new" min="0" name="length_new" class="form-control form-control-sm" />
                                </div>
                            </div>
                            <div class="col-md-3" id="width_new_item">
                                <label class="col-sm-12 control-label" for="width_new"> Ancho (mm) </label>

                                <div class="col-sm-12">
                                    <input type="number" id="width_new" min="0" name="width_new" class="form-control form-control-sm" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="col-sm-12 control-label"> Ubicación</label>

                                <div class="col-sm-12">
                                    <select name="location" class="form-control form-control-sm location select2" data-location style="width: 100%;">
                                        <option></option>

                                    </select>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <label class="col-sm-12 control-label"> Estado </label>

                                <div class="col-sm-12">
                                    <select name="state" class="form-control form-control-sm state select2" data-state style="width: 100%;">
                                        <option value="good" selected> Buen estado </option>
                                        <option value="bad"> Mal estado </option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" id="btn-submit" class="btn btn-success">Guardar retazos</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{--<div id="modalDelete" class="modal fade" tabindex="-1">
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
    </div>--}}
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
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment-timezone.js') }}"></script>
    <script src="{{ asset('js/scraps/index_items_material.js') }}"></script>
    <script>
        $('.location').select2({
            placeholder: "Seleccione",
        });
        $('.state').select2({
            placeholder: "Seleccione",
        });
    </script>
@endsection
