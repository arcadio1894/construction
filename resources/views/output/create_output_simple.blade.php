@extends('layouts.appAdmin2')

@section('openOutputSimple')
    menu-open
@endsection

@section('activeOutputSimple')
    active
@endsection

@section('activeCreateOutputSimple')
    active
@endsection

@section('title')
    Solicitud de área
@endsection

@section('styles-plugins')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/daterangepicker/daterangepicker.css') }}">
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
            height: 90% !important;
        }

        .modal-content {
            height: auto;
            min-height: 100%;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Solicitud de área</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nueva solicitud de área</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        @can('list_requestSimple')
        <li class="breadcrumb-item">
            <a href="{{ route('output.simple.index') }}"><i class="fa fa-archive"></i> Solitudes general</a>
        </li>
        @endcan
        @can('myRequest_requestSimple')
        <li class="breadcrumb-item">
            <a href="{{ route('output.simple.my.index') }}"><i class="fa fa-archive"></i> Mis Solicitudes</a>
        </li>
        @endcan
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nueva solicitud</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <form id="formCreate" class="form-horizontal" data-url="{{ route('output.simple.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Datos generales</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="indicator" value="ors">
                                <div class="form-group">
                                    <label for="request_date">Fecha de Solicitud <span class="right badge badge-danger">(*)</span></label>
                                    <input type="text" id="request_date" name="request_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="requesting_user">Usuario solicitante <span class="right badge badge-danger">(*)</span></label>
                                    <input type="text" id="requesting_user" name="requesting_user" value="{{ Auth::user()->name }}" class="form-control" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="responsible_user">Usuario responsable <span class="right badge badge-danger">(*)</span></label>
                                    <select id="responsible_user" name="responsible_user" class="form-control select2" style="width: 100%;">
                                        <option></option>
                                        @foreach( $users as $user )
                                            <option value="{{ $user->id }}" {{ ($user->id == Auth::user()->id) ? 'selected':'' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Materiales</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body" id="element_loader">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="material_search">Buscar material <span class="right badge badge-danger">(*)</span></label>
                                    <input type="text" id="material_search" class="form-control rounded-0 typeahead">

                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="btn-add"> &nbsp; </label><br>
                                <button type="button" id="btn-add" class="btn btn-outline-primary">Seleccionar materiales <i class="fas fa-arrow-circle-right"></i></button>
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
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="body-materials">
                                                <template id="materials-selected">
                                                    <tr>
                                                        <td data-description></td>
                                                        <td data-item></td>
                                                        <td data-state></td>
                                                        <td>
                                                            <button type="button" data-delete="" class="btn btn-danger"><i class="fas fa-trash"></i></button>
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
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar solicitud de salida</button>
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
                        <table class="card-body table table-head-fixed">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Codigo</th>
                                <th>Selección</th>
                            </tr>
                            </thead>
                            <tbody id="body-items">

                            </tbody>
                            <template id="template-item">
                                <tr>
                                    <td data-id></td>
                                    <td data-serie></td>
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
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
    <script>
        $(function () {
            $('#responsible_user').select2({
                placeholder: "Seleccione un usuario",
            });
        })
    </script>
    <script src="{{ asset('js/output/output_simple.js') }}"></script>
@endsection
