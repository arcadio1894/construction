@extends('layouts.appLanding')

@section('title')
    Contacto
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
        <h2>Contacto</h2>
        <nav aria-label="breadcrumb ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Contacto</a></li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<section class="contact-section">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <h2 class="contact-title">Escríbenos</h2>
            </div>
        
            <div class="col-lg-5">
                <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
                    <div class="row">
                               
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control valid" name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Escribe tu nombre'" placeholder="Escribe tu nombre">
                            </div>
                        </div>
                             
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control valid" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Escribe tu correo'" placeholder="Escribe tu correo">
                            </div>
                        </div>
                                
                        <div class="col-12">
                            <div class="form-group">
                                <input class="form-control" name="subject" id="subject" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Escribe el asunto'" placeholder="Escribe el asunto">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <textarea class="form-control w-100" name="message" id="message" cols="30" rows="7" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Escribe tu mensaje'" placeholder=" Escribe tu mensaje"></textarea>
                            </div>
                        </div>
                    </div>
                                
                    <div class="form-group mt-3">
                        <button type="submit" class="button button-contactForm boxed-btn">Enviar mensaje</button>
                    </div>
                </form>

                <div class="row">  
                    <div class="col-sm-6">              
                        <div class="media contact-info">
                            <span class="contact-info__icon"><i class="ti-tablet"></i></span>
                            <div class="media-body">
                                <h3>998396337</h3>
                                <h3>992555482</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="media contact-info">
                            <span class="contact-info__icon"><i class="ti-email"></i></span>
                            <div class="media-body">
                                <h3>jmauricio@sermeind.com</h3>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="col-lg-3 offset-lg-1">
                <div class="d-none d-sm-block mb-5 pb-4">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.4000469239418!2d-79.0135028859816!3d-8.162388494124894!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91ad18024bafdfad%3A0xa7dff3c2e9a3654f!2sSermeind!5e0!3m2!1ses!2spe!4v1465614735928" width="500" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
               
                

            </div>
        </div>
    </div>
</section>
@endsection