@extends('layouts.appAdmin2')

@section('openConfig')
    menu-open
@endsection

@section('activeConfig')
    active
@endsection

@section('openBrand')
    menu-open
@endsection

@section('activeBrand')

@endsection

@section('activeListBrand')
    active
@endsection

@section('title')
    Modelo
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
    <h1 class="page-title">Modelos</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Editar modelo {{$exampler->name}}</h5>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('exampler.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" name="exampler_id" value="{{$exampler->id}}">

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Modelo <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Ejm: Modelo" value="{{$exampler->name}}">
                </div>
            </div>

            <div class="col-md-6">
                <label for="brand_id" class="col-12 col-form-label">Seleccione Marca <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <select id="brand_id" name="brand_id" class="form-control select2" style="width: 100%;">
                        <option></option>
                        @foreach( $brands as $brand )
                            <option value="{{ $brand->id }}" {{ $brand->id === $exampler->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Comentario</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="comment" placeholder="Ejm: Descripción" value="{{$exampler->comment}}">
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
    <script src="{{ asset('js/exampler/edit.js') }}"></script>
@endsection
