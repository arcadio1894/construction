@extends('layouts.appAdmin')

@section('title')
    Dashboard
@endsection

@section('header-page')
    <h1 class="page-title">Dashboard</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#"><i class="la la-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Principal</li>
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
