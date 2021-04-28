@extends('layouts.appAdmin2')

@section('title')
    Dashboard
@endsection

@section('page-header')
    <h1 class="page-title">Dashboard</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
    </ol>
@endsection

@section('page-title')
    <h5 class="card-title">TITULO DASHBOARD</h5>
@endsection

@section('content')
    <h5 class="card-title">Card title</h5><p></p>
@endsection
