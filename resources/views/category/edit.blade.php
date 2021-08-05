@extends('layouts.appAdmin2')

@section('openConfig')
    menu-open
@endsection

@section('activeConfig')
    active
@endsection

@section('openCategory')
    menu-open
@endsection

@section('activeCategory')

@endsection

@section('activeListCategory')
    active
@endsection

@section('title')
    Categorias
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

@section('page-header')
    <h1 class="page-title">Categorias</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Editar categoria {{$category->name}}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('category.index') }}"><i class="fa fa-archive"></i> Categorías</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('category.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" name="category_id" value="{{$category->id}}">

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Nombre <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Ejm: Categoría" value="{{$category->name}}">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Descripcion</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" placeholder="Ejm: Descripción" value="{{$category->description}}">
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-outline-success">Guardar Cambios</button>
            <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
        </div>
        <!-- /.card-footer -->
    </form>
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
    <script src="{{ asset('js/category/edit.js') }}"></script>
@endsection
