@extends('layouts.appAdmin2')

@section('openOutputRequest')
    menu-open
@endsection

@section('activeOutputRequest')
    active
@endsection

@section('activeReportMaterialAreaOutputSimple')
    active
@endsection

@section('title')
    Materials en solicitudes de área
@endsection

@section('styles-plugins')
    <!-- Datatables -->

    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

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
        .black-text {
            color: black !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Materiales en Solicitud por Área</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Visualización de materiales en solicitudes por área</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Visualizar materiales en salida por área</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="row">
        <div class="col-sm-4">
            <label for="area">Materiales en salidas por área <span class="right badge badge-danger">(*)</span></label>
            <select id="area" name="area" class="form-control select2" style="width: 100%;">
                <option></option>

            </select>

        </div>
        <div class="col-sm-2">
            <label for="startDate">Fecha de inicio<span class="right badge badge-danger">(*)</span></label>
            <input type="date" id="startDate" name="startDate" class="form-control">
        </div>
        <div class="col-sm-2">
            <label for="endDate">Fecha de fin<span class="right badge badge-danger">(*)</span></label>
            <input type="date" id="endDate" name="endDate" class="form-control">
        </div>
        <div class="col-sm-4">
            <label for="area">&nbsp;&nbsp; &nbsp;&nbsp;</label>
            <button type="button" id="btn-outputs" class="btn btn-block btn-outline-success">Ver salidas</button>

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Solicitudes por área </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="element_loader">
                        <table class="table table-hover" id="dynamic-table">
                            <thead>
                            <tr>
                                <th>Solicitud</th>
                                <th>Orden de ejecución</th>
                                <th>Descripción</th>
                                <th>Fecha de solicitud</th>
                                <th>Usuario Solicitante</th>
                                <th>Usuario Responsable</th>
                                <th>Indicador</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody id="body-outputs">

                            </tbody>
                            <template id="template-output">
                                <tr>
                                    <td data-output></td>
                                    <td data-order_execution></td>
                                    <td data-description></td>
                                    <td data-date></td>
                                    <td data-user_request></td>
                                    <td data-user_responsible></td>
                                    <td data-indicator></td>
                                    <td>
                                        <button class="btn btn-primary btn-view-items">Ver Items</button>
                                    </td>

                                </tr>
                            </template>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modalItems" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Listado de items</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body table-responsive" style="height: 300px;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Código</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody id="table-materiales">

                        </tbody>
                        <template id="template-materiale">
                            <tr>
                                <td data-i></td>
                                <td data-code></td>
                                <td data-material></td>
                                <td data-quantity></td>
                            </tr>
                        </template>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugins')
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

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
            $('#material').select2({
                placeholder: "Búsqueda del material",
            });

        })
    </script>
    <script src="{{ asset('js/output/reportMaterialOutputsAreaSimple.js') }}"></script>
@endsection
