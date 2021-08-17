@extends('layouts.appAdmin2')

@section('openConfig')
    menu-open
@endsection

@section('activeConfig')
    active
@endsection

@section('openSubcategory')
    menu-open
@endsection

@section('activeSubcategory')

@endsection

@section('activeListSubcategory')
    active
@endsection

@section('title')
    Subcategorías
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
    <h1 class="page-title">Subcategorías</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Editar subcategoría {{$subcategory->name}}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('subcategory.index') }}"><i class="fa fa-archive"></i> Subcategorías</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('subcategory.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" name="subcategory_id" value="{{$subcategory->id}}">

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Subcategoría <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" onkeyup="mayus(this);" name="name" placeholder="Ejm: Subcategoría" value="{{$subcategory->name}}">
                </div>
            </div>

            <div class="col-md-6">
                <label for="category_id" class="col-12 col-form-label">Seleccione Categoría <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <select id="category_id" name="category_id" class="form-control select2" style="width: 100%;">
                        <option></option>
                        @foreach( $categories as $category )
                            <option value="{{ $category->id }}" {{ $category->id === $subcategory->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Descripción</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" onkeyup="mayus(this);" name="description" placeholder="Ejm: Descripción" value="{{$subcategory->description}}">
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
    <script src="{{ asset('js/subcategory/edit.js') }}"></script>
@endsection
