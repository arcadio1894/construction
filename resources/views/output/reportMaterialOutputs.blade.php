@extends('layouts.appAdmin2')

@section('openOutputRequest')
    menu-open
@endsection

@section('activeOutputRequest')
    active
@endsection

@section('activeReportMaterialOutput')
    active
@endsection

@section('title')
    Materials en solicitudes
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
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Materiales en Solicitud de salida</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Visualización de materiales en solicitudes</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Visualizar materiales en salida</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="row">
        <div class="col-sm-8">
            <label for="material">Materiales en salidas <span class="right badge badge-danger">(*)</span></label>
            <select id="material" name="material" class="form-control select2" style="width: 100%;">
                <option></option>

            </select>

        </div>
        <div class="col-sm-4">
            <label for="material">&nbsp;&nbsp; &nbsp;&nbsp;</label>
            <button type="button" id="btn-outputs" class="btn btn-block btn-outline-success">Ver salidas</button>

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Solicitudes del material</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dynamic-table">
                            <thead>
                            <tr>
                                <th>Solicitud</th>
                                <th>Orden de ejecución</th>
                                <th>Descripción</th>
                                <th>Fecha de solicitud</th>
                                <th>Cantidad</th>
                                <th>Usuario Solicitante</th>
                                <th>Usuario Responsable</th>
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
                                    <td data-quantity></td>
                                    <td data-user_request></td>
                                    <td data-user_responsible></td>
                                </tr>
                            </template>
                        </table>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">

        </div>
        <!-- /.col -->
        <div class="col-6">

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Total de salidas: </th>
                        <td id="total-outputs"> </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
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
    <script src="{{ asset('js/output/reportMaterialOutputs.js') }}"></script>
@endsection
