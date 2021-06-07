@extends('layouts.appLanding')

@section('title')
    Fabricación
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('landing/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/animate.min.css') }}">
@endsection

@section('data-background')
    {{ asset('landing/img/hero/about2.jpg') }}
@endsection

@section('header-page')
    <div class="hero-cap pt-100">
        <h2>Fabricación</h2>
        <nav aria-label="breadcrumb ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Fabricación</a></li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<!-- Project Area Start -->
<section class="project-area  section-padding30">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <!-- Nav Card -->
                <div class="tab-content active" id="nav-tabContent">
                    <!-- card ONE -->
                    <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">           
                        <div class="project-caption">
                            <div class="row">
                                   
                                <div class="col-lg-4 col-md-6">
                                    <div class="single-project mb-30">
                                        <div class="project-img">
                                            <img src="{{ asset('landing/img/gallery/project1.png') }}" alt="">
                                        </div>
                                        <div class="project-cap">
                                            <a href="#"> 
                                                <h4>Marmitas (pailas)</h4>
                                                <h5>para la preparación de líquido de conserva.</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-md-6">
                                    <div class="single-project mb-30">
                                        <div class="project-img">
                                            <img src="{{ asset('landing/img/gallery/project1.png') }}" alt="">
                                        </div>
                                        <div class="project-cap">
                                            <a href="#"> 
                                                <h4>Sistemas de enfriamiento</h4>
                                                <h5>Fabricación e instalación</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="single-project mb-30">
                                        <div class="project-img">
                                            <img src="{{ asset('landing/img/gallery/project1.png') }}" alt="">
                                        </div>
                                        <div class="project-cap">
                                            <a href="#"> 
                                                <h4>Sistemas de Protección Eléctricos</h4>
                                                <h5>(Pozo Tierra, Pararrayos).  </h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="single-project mb-30">
                                        <div class="project-img">
                                            <img src="{{ asset('landing/img/gallery/project1.png') }}" alt="">
                                        </div>
                                        <div class="project-cap">
                                            <a href="#"> 
                                                <h4>Transportadores, elevadores, hornos, etc.</h4>
                                                <h5>(Faja sanitaria, bandas modulares y cadenas en inoxidable).</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="single-project mb-30">
                                        <div class="project-img">
                                            <img src="{{ asset('landing/img/gallery/project1.png') }}" alt="">
                                        </div>
                                        <div class="project-cap">
                                            <a href="#"> 
                                                <h4>Tableros de maquinaria electromecánico</h4>
                                                <h5>Para el control de funcionamiento óptimo </h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="single-project mb-30">
                                        <div class="project-img">
                                            <img src="{{ asset('landing/img/gallery/project1.png') }}" alt="">
                                        </div>
                                        <div class="project-cap">
                                            <a href="#"> 
                                                <h4>Centro de control de motores con variadores de frecuencia</h4>
                                                <h5>Potencias : 50, 100, 150, 200, 250,300 y 400 Hp.</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                              
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Nav Card -->
            </div>
        </div>
    </div>
</section>
<!-- Project Area End -->
@endsection