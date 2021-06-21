@extends('layouts.appLanding')

@section('title')
    Servicios
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
        <h2>Servicios</h2>
        <nav aria-label="breadcrumb ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Servicios</a></li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<!-- Services Area Start -->
<div class="services-area1 section-padding30">
    <div class="container">
        <!-- section tittle -->
        <div class="row">
            <div class="col-lg-12">
                <div class="section-tittle mb-55">
                    <div class="front-text">
                        <h2 class="">Nuestros Servicios</h2>
                    </div>
                    <span class="back-text">Servicios</span>
                </div>
            </div>
        </div>
        
        <div class="row">
            
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess1.png') }}" alt="">

                    </div>
        
                    <div class="service-cap">
                        <a href="#">
                            <h4>Mantenimiento y reparación general de Maquinarias</h4>
                            <!--<h5>para proceso de conservas alimentarias y azucareras.</h5>-->
                        </a>
                    </div>
        
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess4.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Montaje y desmontaje de tuberías inoxidable</h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess5.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Montaje y desmontaje de acero al Carbono SCH 40, 80</h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess6.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Montaje y desmontaje de calderos (Vapor, Agua y Aire)</h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess7.png') }}" alt="">
                    </div>
                
                    <div class="service-cap">
                        <a href="#">
                            <h4>Soldadura en General</h4>
                            <h5>(Eléctrica y Tig)</h5>
                        </a>
                    </div>
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess8.png') }}" alt="">
                    </div>
                
                    <div class="service-cap">
                        <a href="#">
                            <h4>Bombas Centrifuga y Moto reductores</h4>
                        </a>
                    </div>
                
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess9.png') }}" alt="">
                    </div>
                
                    <div class="service-cap">
                        <a href="#">
                            <h4>Acoplamiento Mecánicos y Electromecánico.</h4>
                        </a>
                    </div>
                
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess10.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Fabricación de elevadores, hornos, etc. </h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess11.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Fabricación de marmitas (pailas) </h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess12.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Fabricación e instalación de sistemas de enfriamiento.</h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess2.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Asesoramiento Técnico de maquinarias.</h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess3.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Diseño y cableado eléctrico de plantas industriales.</h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <!--

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess1.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Fabricación de tableros</h4>
                            <h5>Maquinaria electromecánico.</h5>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess1.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Protección Eléctricos</h4>
                            <h5>(Pozo Tierra, Pararrayos)</h5>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="single-service-cap mb-30">
                    <div class="service-img">
                        <img src="{{ asset('landing/img/service/servicess1.png') }}" alt="">
                    </div>
            
                    <div class="service-cap">
                        <a href="#">
                            <h4>Control de motores con variadores de frecuencia.</h4>
                        </a>
                    </div>
            
                    <div class="service-icon">
                        <img src="{{ asset('landing/img/icon/services_icon1.png') }}" alt="">
                    </div>
                </div>
            </div>

            -->

        </div>
    </div>
</div>
        <!-- Services Area End -->

@endsection