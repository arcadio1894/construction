@extends('layouts.appAdmin2')

@section('openInventory')
    menu-open
@endsection

@section('activeInventory')
    active
@endsection

@section('activeAreas')
    active
@endsection

@section('title')
    Anaqueles
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('page-header')
    <h1 class="page-title">Inventario Físico</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('area.index') }}"><i class="fa fa-home"></i> Área: {{ $area->name }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('warehouse.index', $area->id) }}"><i class="fa fa-home"></i> Almacén: {{ $warehouse->name }}</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-lock"></i> Anaqueles</li>
    </ol>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de anaqueles</h5>
    <button id="newShelf" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo anaquel </button>
@endsection

@section('content')
    <input type="hidden" id="id_area" value="{{$area->id}}">
    <input type="hidden" id="id_warehouse" value="{{$warehouse->id}}">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Comentario</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
    </div>
    <div id="modalCreate" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo anaquel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formCreate" class="form-horizontal" data-url="{{ route('shelf.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{$warehouse->id}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="name"> Estante <span class="right badge badge-danger">(*)</span></label>

                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Ejm: A" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="comment"> Comentario </label>

                            <div class="col-sm-12">
                                <input type="text" id="comment" name="comment" class="form-control" placeholder="Ejm: Anaquel A" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar anaquel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" class="form-horizontal" data-url="{{ route('shelf.update') }}" >
                    @csrf
                    <input type="hidden" name="shelf_id" id="shelf_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label " for="nameE"> Estante <span class="right badge badge-danger">(*)</span></label>

                            <div class="col-sm-12">
                                <input type="text" id="nameE" name="name" class="form-control" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="commentE"> Comentario </label>

                            <div class="col-sm-12">
                                <input type="text" id="commentE" name="comment" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-primary">Guardar cambios</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div id="modalDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">¿Desea eliminar el anaquel?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('shelf.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="shelf_id" name="shelf_id">
                        <p id="nameDelete"></p>
                        <p id="commentDelete"></p>
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
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('js/shelf/index.js') }}"></script>
@endsection
