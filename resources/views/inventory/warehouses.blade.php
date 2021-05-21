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
    Almacenes
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
        <li class="breadcrumb-item"><i class="fa fa-lock"></i> Almacenes</li>
    </ol>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de almacenes</h5>
    <button id="newArea" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo almacén </button>
@endsection

@section('content')
    <input type="hidden" id="id_area" value="{{$area->id}}">
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
                    <h4 class="modal-title">Nuevo almacén</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formCreate" class="form-horizontal" data-url="{{ route('warehouse.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="area_id" id="area_id" value="{{$area->id}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="name"> Almacén </label>

                            <div class="col-sm-12">
                                <input type="text" id="name" name="name" class="form-control" placeholder="Ejm: Principal" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="comment"> Comentario </label>

                            <div class="col-sm-12">
                                <input type="text" id="comment" name="comment" class="form-control" placeholder="Ejm: Almacén prinicipal" />
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
                    <h4 class="modal-title">Modificar almacén</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" class="form-horizontal" data-url="{{ route('warehouse.update') }}" >
                    @csrf
                    <input type="hidden" name="warehouse_id" id="warehouse_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label " for="nameE"> Almacén </label>

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
                    <h4 class="modal-title">¿Desea eliminar el almacén?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('warehouse.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="warehouse_id" name="warehouse_id">
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
    <script src="{{ asset('js/warehouse/index.js') }}"></script>
@endsection
