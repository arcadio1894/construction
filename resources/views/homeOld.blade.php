<!-- Despues de iniciar sesion -->
@extends('layouts.appLanding')

@section('title')
    Inicio
@endsection

@section('data-background')
    {{ asset('landing/img/hero/about2.jpg') }}
@endsection

@section('header-page')
    <div class="hero-cap pt-100">
        <h2>Bienvenido</h2>
        <nav aria-label="breadcrumb ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')

<!-- Page de register y de login -->
    <!-- Content Page Start-->
    <div class="services-area1 section-padding">
        <div class="container">
            <!-- section tittle -->
            <div class="row">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">{{ __('Sermeind te da la bienvenida') }}</div>

                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    Hola {{ Auth::user()->name }}. Bienvenido a la intranet, puedes acceder a diferentes características en el menú superior.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Page End-->

@endsection
