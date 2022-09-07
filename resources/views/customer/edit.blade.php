@extends('layouts.appAdmin2')

@section('openCustomer')
    menu-open
@endsection

@section('activeCustomer')
    active
@endsection

@section('activeListCustomer')
    active
@endsection

@section('title')
    Clientes
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Clientes</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Editar cliente {{$customer->code}}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('customer.index') }}"><i class="fa fa-key"></i> Clientes</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('customer.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" name="customer_id" value="{{$customer->id}}">

        <div class="form-group row">
            <div class="col-md-4">
                <label for="inputEmail3" class="col-12 col-form-label">RUC <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="ruc" placeholder="Ejm: 1234678901" value="{{$customer->RUC}}">
                </div>
            </div>
            <div class="col-md-2">
                <label for="btn-grouped" class="col-12 col-form-label">Extranjero <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input id="btn-grouped" type="checkbox" name="special" {{ ($customer->special == 1) ? 'checked':'' }} data-bootstrap-switch data-off-color="danger" data-on-text="SI" data-off-text="NO" data-on-color="success">
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Razon Social <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" onkeyup="mayus(this);" name="business_name" placeholder="Ejm: Edesce EIRL" value="{{$customer->business_name}}">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Direccion</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" onkeyup="mayus(this);" name="address" placeholder="Ejm: Jr Union" value="{{$customer->address}}">
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Ubicacion</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" onkeyup="mayus(this);" name="location" placeholder="Ejm: Moche" value="{{$customer->location}}">
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="button" id="btn-submit" class="btn btn-outline-success">Guardar Cambios</button>
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
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

@endsection

@section('scripts')
    <script src="{{ asset('js/customer/edit.js') }}"></script>
    <script>
        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch();
        });
    </script>
@endsection
