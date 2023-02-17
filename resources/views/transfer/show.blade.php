@extends('layouts.appAdmin2')

@section('openTransfer')
    menu-open
@endsection

@section('activeTransfer')
    active
@endsection

@section('activeListTransfer')
    active
@endsection

@section('title')
    Transferencias
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
        td.details-control {
            background: url('/admin/plugins/datatables/resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.details td.details-control {
            background: url('/admin/plugins/datatables/resources/details_close.png') no-repeat center center;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Transferencias</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Detalle de la transferencia {{ $transfer->code }}</h5>
    <a href="{{ route('transfer.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Regresar </a>

    <input type="hidden" id="transfer_id" value="{{ $transfer->id }}">
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('transfer.index') }}"><i class="fa fa-archive"></i> Transferencias</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Detalle </li>
    </ol>
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="dynamic-table">
            <thead>
            <tr>
                <th>Material</th>
                <th>Código</th>
                <th>Largo</th>
                <th>Ancho</th>
                <th>Porcentaje</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Estado</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    {{--@can('destroy_transfer')
    <div id="modalDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('material.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="material_id" name="material_id">
                        <p>¿Está seguro de eliminar este material?</p>
                        <p id="descriptionDelete"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan--}}
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
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('js/transfer/show.js') }}"></script>
@endsection
