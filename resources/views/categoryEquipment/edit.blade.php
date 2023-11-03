@extends('layouts.appAdmin2')

@section('openDefaultEquipment')
    menu-open
@endsection

@section('activeDefaultEquipment')
    active
@endsection

@section('activeCategoryEquipment')
    active
@endsection

@section('title')
    Categoria de Equipos
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
    <h5 class="card-title">Edicion de categorias de equipos </h5>
    {{--<a href="{{ route('category.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nueva Categoria </a>--}}
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Categorías de Equipos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Edicion</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <div class="col-12 col-sm-6 col-md-3 d-flex align-items-stretch">
        <div class="card bg-light">
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-12">
                        <h2 class="lead text-center"><b data-description>ACCESORIOS</b></h2>
                        <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"><i class="fas fa-newspaper"></i></span>Numero de equipos: <span data-number>15</span></li>
                        </ul>
                    </div>
                    <div class="col-md-8 offset-2 text-center mt-3">
                        <img data-image src="" alt="" class="img-circle img-fluidcpt-3">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <a data-edit="1" data-descritpion="ACCESORIOS" href="#" class="btn btn-sm btn-success">
                        <i class="fas fa-history  "> </i>  Restaurar
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('scripts')

@endsection