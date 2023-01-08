@extends('layouts.appAdmin2')

@section('openConfigRH')
    menu-open
@endsection

@section('activeConfigRH')
    active
@endsection

@section('openContract')
    menu-open
@endsection

@section('activeListContract')
    active
@endsection

@section('title')
    Contratos
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
    <h1 class="page-title">Contrato {{ $contract->code }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Modificar contrato</h5>
    <a href="{{ route('contract.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de Contratos</a>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('contract.index') }}"><i class="fa fa-archive"></i> Contratos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('contract.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="contract_id" value="{{ $contract->id }}">
        <div class="form-group row">
            <div class="col-md-6">
                <label for="code">Código <span class="right badge badge-danger">(*)</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-keyboard"></i></span>
                    </div>
                    <input id="code" type="text" class="form-control" value="{{ $contract->code }}" name="code" >
                </div>
            </div>

            <div class="col-md-6">
                <label for="file">Archivo IMG/PDF </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-file-archive"></i></span>
                    </div>
                    <input type="file" id="file" name="file" class="form-control" >
                </div>
                @if ( substr($contract->file,-3) == 'pdf' )
                    <a href="{{ asset('images/contracts/'.$contract->file) }}" target="_blank" class="btn btn-outline-success float-right">Ver PDF</a>
                @else
                    <img data-image src="{{ asset('images/contracts/'.$contract->file) }}" alt="{{$contract->code}}" width="100px" height="100px">
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="date_start">Fecha Inicio</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" id="date_start" value="{{ ($contract->date_start == null) ? '': $contract->date_start->format('d/m/Y') }}" name="date_start" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>

                </div>
            </div>
            <div class="col-md-6">
                <label for="date_fin">Fecha Fin</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" id="date_fin" name="date_fin" value="{{ ($contract->date_fin == null) ? '': $contract->date_fin->format('d/m/Y') }}" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="button" id="btn-submit" class="btn btn-outline-success">Guardar</button>
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
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            //$('#datemask').inputmask()
            $('#date_start').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#date_fin').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });

        })
    </script>
    <script src="{{ asset('js/contract/edit.js') }}"></script>
@endsection
