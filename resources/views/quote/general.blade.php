@extends('layouts.appAdmin2')

@section('openQuote')
    menu-open
@endsection

@section('activeQuote')
    active
@endsection

@section('activeGeneralQuote')
    active
@endsection

@section('title')
    Cotizaciones
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-title')
    <h5 class="card-title">Listado General de cotizaciones</h5>
    @can('create_quote')
    <a href="{{ route('quote.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nueva cotización </a>
    @endcan
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado General</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <div class="row">
        <div class="col-md-2">
            <strong> Seleccione fechas: </strong>
        </div>
        <div class="col-md-4" id="sandbox-container">
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="form-control form-control-sm date-range-filter" id="start" name="start">
                <span class="input-group-addon">&nbsp;&nbsp;&nbsp; al &nbsp;&nbsp;&nbsp; </span>
                <input type="text" class="form-control form-control-sm date-range-filter" id="end" name="end">
            </div>
        </div>
        @hasanyrole('admin|principal')
        <div class="col-md-4">
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                    <input type="radio" name="typeQuote" checked="" id="allQuotes" value="all">
                    <label for="allQuotes">Todas
                    </label>
                </div>
                <div class="icheck-success d-inline">
                    <input type="radio" name="typeQuote" id="raisedQuotes" value="raised">
                    <label for="raisedQuotes">Elevadas
                    </label>
                </div>
                <div class="icheck-danger d-inline">
                    <input type="radio" name="typeQuote" id="finishedQuotes" value="finished">
                    <label for="finishedQuotes">Finalizadas
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <button type="button" id="btn-export" class="btn btn-block btn-sm btn-outline-success"> <i class="fas fa-file-excel"></i> Exportar</button>
        </div>
        @endhasanyrole
        <br><br>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm" id="dynamic-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Fecha Cotización</th>
                <th>Fecha Válida</th>
                <th>Forma Pago</th>
                <th>Tiempo Entrega</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Total Sin IGV</th>
                <th>Moneda</th>
                <th>Estado</th>
                <th>Fecha Creación</th>
                <th>Creador</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    @can('destroy_quote')
    <div id="modalDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('subcategory.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subcategory_id" name="subcategory_id">
                        <strong>¿Está seguro de eliminar esta subcategoría?</strong>
                        <p id="name"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
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
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('js/quote/general.js') }}"></script>
@endsection