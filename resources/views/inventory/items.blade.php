@extends('layouts.appAdmin2')

@section('openInventory')
    menu-open
@endsection

@section('activeInventory')
    active
@endsection

@section('activeLocations')
    active
@endsection

@section('title')
    Items
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
    <h1 class="page-title">Items de la ubicación AR:{{ $location->area->name }}|AL:{{ $location->warehouse->name }}|ES:{{ $location->shelf->name }}|F:{{ $location->level->name }}|C:{{ $location->container->name }}|P:{{ $location->position->name }}</h1>
    <input type="hidden" id="id-location" value="{{$location->id}}">
@endsection

@section('page-title')
    <h5 class="card-title">Listado de items en la ubicación</h5>
    <a href="{{ route('location.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Regresar </a>
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
            <thead>
            <tr>
                <th></th>
                <th>Material</th>
                <th>Código</th>
                <th>Largo</th>
                <th>Ancho</th>
                <th>Peso</th>
                <th>Precio</th>
                <th>Porcentaje</th>
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
    <script src="{{ asset('js/location/items.js') }}"></script>
@endsection
