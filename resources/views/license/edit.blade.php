@extends('layouts.appAdmin2')

@section('openHourSpecial')
    menu-open
@endsection

@section('activeHourSpecial')
    active
@endsection

@section('openLicenses')
    menu-open
@endsection

@section('activeListLicense')
    active
@endsection

@section('title')
    Licencias
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
    <h1 class="page-title">Licencia de {{ $license->worker->first_name .' '.$license->worker->last_name }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Modificar licencia</h5>
    <a href="{{ route('license.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de Licencias</a>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('license.index') }}"><i class="fa fa-archive"></i> Licencias</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Importante!</strong> Al modificar las fechas, debe tener en cuenta que se modificarán las asistencias pero debe revisar las asistencias de los días modificados.
                <br>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <br>

    <form id="formCreate" class="form-horizontal" data-url="{{ route('license.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="license_id" value="{{ $license->id }}">

        <div class="form-group row">
            <div class="col-md-6">
                <label for="reason">Motivo </label>

                <textarea name="reason" id="reason" class="form-control">{{$license->reason}}</textarea>

            </div>
            <div class="col-md-6">
                <label for="file">Archivo IMG/PDF </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-file-archive"></i></span>
                    </div>
                    <input type="file" id="file" name="file" class="form-control" >
                </div>
                @if ( $license->file != null )
                    @if ( substr($license->file,-3) == 'pdf' )
                        <a href="{{ asset('images/license/'.$license->file) }}" target="_blank" class="btn btn-outline-success float-right">Ver PDF</a>
                    @else
                        <img data-image src="{{ asset('images/license/'.$license->file) }}" alt="{{$medicalRest->id}}" width="100px" height="100px">
                    @endif
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
                    <input type="text" id="date_start" value="{{ ($license->date_start == null) ? '': $license->date_start->format('d/m/Y') }}" name="date_start" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>

                </div>
            </div>
            <div class="col-md-6">
                <label for="date_end">Fecha Fin</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" id="date_end" name="date_end" value="{{ ($license->date_end == null) ? '': $license->date_end->format('d/m/Y') }}" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
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
            $('#date_end').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });

        })
    </script>
    <script src="{{ asset('js/license/edit.js') }}"></script>
@endsection
