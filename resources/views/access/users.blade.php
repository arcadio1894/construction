@extends('layouts.appAdmin')

@section('openAccess')
    active
@endsection

@section('activeUser')
    active
@endsection

@section('title')
    Usuarios
@endsection

@section('header-page')
    <h1 class="page-title">Accesos</h1>
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-users"></i> Usuarios</li>
    </ul>
@endsection

@section('content')
<div class="ibox">
    <div class="ibox-head">

    </div>
    <div class="ibox-body">

    </div>
</div>
@endsection
