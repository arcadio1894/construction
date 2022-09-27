@extends('layouts.appAdmin2')

@section('openOrderService')
    menu-open
@endsection

@section('activeOrderService')
    active
@endsection

@section('activeListOrderService')
    active
@endsection

@section('title')
    Ordenes de servicios
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
    </style>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de Ordenes de Servicios</h5>
    @can('create_orderPurchaseNormal')
    <a href="{{ route('order.service.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nueva orden de servicio </a>
    @endcan
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('order.service.index')}}"><i class="fa fa-archive"></i> Ordenes de servicio</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Fecha Orden</th>
                <th>Fecha Entrega</th>
                <th>Observación</th>
                <th>Proveedor</th>
                <th>Aprobado por</th>
                <th>Moneda</th>
                <th>Total</th>
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
    <script src="{{ asset('js/orderService/indexOrderService.js') }}"></script>
@endsection