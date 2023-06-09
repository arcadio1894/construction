@extends('layouts.appAdmin2')

@section('openEntryPurchase')
    menu-open
@endsection

@section('activeEntryPurchase')
    active
@endsection

@section('activeListOrdersInEntries')
    active
@endsection

@section('title')
    Ordenes de compra
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

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de Ordenes de compras</h5>
    {{--@can('create_orderPurchaseExpress')
    <a href="{{ route('order.purchase.express.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nueva orden express </a>
    @endcan--}}
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        {{--<li class="breadcrumb-item">
            <a href="{{route('order.purchase.express.index')}}"><i class="fa fa-archive"></i> Ordenes de compra</a>
        </li>--}}
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <div class="row">
        <div class="col-md-3">
            <strong> Seleccione un rango de fechas: </strong>
        </div>
        <div class="col-md-6" id="sandbox-container">
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="form-control form-control-sm date-range-filter" id="start" name="start">
                <span class="input-group-addon">&nbsp;&nbsp;&nbsp; al &nbsp;&nbsp;&nbsp; </span>
                <input type="text" class="form-control form-control-sm date-range-filter" id="end" name="end">
            </div>
        </div>
        <div class="col-md-3">
            <button type="button" id="btn-export" class="btn btn-sm btn-outline-success"> <i class="fas fa-file-excel"></i> Exportar</button>
        </div>
        <br><br>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Fecha Orden</th>
                <th>Fecha Llegada</th>
                <th>Proveedor</th>
                <th>Aprobado por</th>
                <th>Moneda</th>
                <th>Total</th>
                <th>Estado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
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
@endsection

@section('scripts')
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

    <script src="{{ asset('js/entry/listOrders.js') }}"></script>
@endsection