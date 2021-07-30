@extends('layouts.appAdmin2')

@section('openConfig')
    menu-open
@endsection

@section('activeConfig')
    active
@endsection

@section('openTypeScrap')
    menu-open
@endsection

@section('activeTypeScrap')

@endsection

@section('activeListTypeScrap')
    active
@endsection

@section('title')
    Tipos de Retacería
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
    <h1 class="page-title">Tipo de Retacería</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Editar Tipo de Retacería: {{$typeScrap->name}}</h5>
    <a href="{{ route('typescrap.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de Tipo de Material </a>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('typescrap.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" name="typeScrap_id" value="{{$typeScrap->id}}">
        
        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Ejm: Tipo de retacería" value="{{$typeScrap->name}}">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-4">
                <label for="inputEmail3" class="col-12 col-form-label">Largo</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="length" min="0" placeholder="Ejm: 0,00" step="0.01" value="{{$typeScrap->length}}">
                </div>
            </div>

            <div class="col-md-4">
                <label for="inputEmail3" class="col-12 col-form-label">Ancho</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="width" min="0" placeholder="Ejm: 0,00" step="0.01" value="{{$typeScrap->width}}">
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
    <script src="{{ asset('js/typescrap/edit.js') }}"></script>
@endsection
