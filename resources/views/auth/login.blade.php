@extends('layouts.appLanding2')

@section('title')
    Inicio de sesión
@endsection


@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Inicie sesión para ingresar</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Iniciar Sesión') }}</button>
                    </div>
                    <!-- /.col -->
                </div>

            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
<!-- Page de register y de login -->
    <!-- Content Page Start-->
    {{--<div class="services-area1 section-padding">
        <div class="container">
            <!-- section tittle -->
            <div class="row">
                <div class="col-lg-12">
                        <div class="section-tittle mb-30">
                        <div class="row front-text justify-content-center">
                            <h2 class="">Accede a tu cuenta</h2>
                        </div>
                        <span class="back-text">Accede a tu cuenta</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn">
                                            {{ __('Iniciar Sesión') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn" href="{{ route('password.request') }}">
                                                {{ __('Recuperar Contraseña') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
    <!-- Content Page End-->

@endsection
