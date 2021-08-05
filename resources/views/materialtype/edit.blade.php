@extends('layouts.appAdmin2')

@section('openConfig')
    menu-open
@endsection

@section('activeConfig')
    active
@endsection

@section('openMaterialType')
    menu-open
@endsection

@section('activeMaterialType')

@endsection

@section('activeListMaterialType')
    active
@endsection

@section('title')
    Tipos de Materiales
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
    <h1 class="page-title">Tipo</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Editar Tipo de Material: {{$materialtype->name}}</h5>
    <a href="{{ route('materialtype.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de Tipo de Material </a>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('materialtype.index') }}"><i class="fa fa-archive"></i> Tipo de materiales</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('materialtype.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" name="materialtype_id" value="{{$materialtype->id}}">
        
        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Nombre <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Ejm: Tipo" value="{{$materialtype->name}}">
                </div>
            </div>
            <div class="col-md-6">
                <label for="subcategory_id" class="col-12 col-form-label">Seleccione Marca <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <select id="subcategory_id" name="subcategory_id" class="form-control select2" style="width: 100%;">
                        <option></option>
                        @foreach( $subcategories as $subcategory )
                            <option value="{{ $subcategory->id }}"  {{ $subcategory->id === $materialtype->subcategory_id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Descripción</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" placeholder="Descripcion" value="{{$materialtype->name}}">
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
    <script src="{{ asset('js/materialtype/edit.js') }}"></script>
@endsection
