@extends('layouts.appAdmin')

@section('openAccess')
    active
@endsection

@section('activeRoles')
    active
@endsection

@section('title')
    Roles
@endsection

@section('header-page')
    <h1 class="page-title">Accesos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-home font-20"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <i class="fa fa-shield font-20"></i> Roles
        </li>
    </ol>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    AQUI SE PUEDE COLOCAR OTRA COSA
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
