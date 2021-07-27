@extends('layouts.appAdmin2')

@section('openConfig')
    menu-open
@endsection

@section('activeConfig')
    active
@endsection

@section('openQuality')
    menu-open
@endsection

@section('activeQuality')

@endsection

@section('activeListQuality')
    active
@endsection

@section('title')
    Calidades
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
    <h1 class="page-title">Calidades</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Editar calidad {{$quality->name}}</h5>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('quality.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" name="quality_id" value="{{$quality->id}}">

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Calidad <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Ejm: Calidad" value="{{$quality->name}}">
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Descripción</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" placeholder="Ejm: Descripción" value="{{$quality->description}}">
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
    <script src="{{ asset('js/quality/edit.js') }}"></script>
@endsection
