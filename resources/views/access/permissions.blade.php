@extends('layouts.appAdmin')

@section('openAccess')
    active
@endsection

@section('activePermissions')
    active
@endsection

@section('title')
    Permisos
@endsection

@section('styles')
    <link href="{{ asset('admin/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('header-page')
    <h1 class="page-title">Accesos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home font-20"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-lock font-20"></i> Permisos</li>
    </ol>
@endsection

@section('content')
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Listado de permisos</div>
        </div>
        <div class="ibox-body">
            <table class="table" id="dynamic-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('admin/vendors/DataTables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/permission/index.js') }}"></script>
@endsection
