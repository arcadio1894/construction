@extends('layouts.appLanding')

@section('title')
    Nosotros
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
        <h2>Nosotros</h2>
        <nav aria-label="breadcrumb ">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Nosotros</a></li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<!-- About Area Start -->
<section class="support-company-area fix pt-10 section-padding30">
    <div class="support-wrapper align-items-end">
        <div class="left-content">
        
        <!-- section tittle -->
            <div class="section-tittle section-tittle2 mb-55">
                <div class="front-text">
                    <h2 class="">¿Quienes somos?</h2>
                </div>
            </div>
            
            <div class="support-caption" style="text-align: justify;">
                <p><b>SERMEIND FABRICACIONES INSUTRIALES S.A.C.</b> es una empresa con amplia experiencia en soldadura, fabricación y mantenimiento de todo tipo de maquinarias para la industria Alimentaria. Cabe indicar que nuestro equipo de trabajo está conformado por un grupo multidisciplinario de profesionales con amplia experiencia</p>
                <p><b>SERMEIND FABRICACIONES INSUTRIALES S.A.C.</b> cuenta con un personal altamente calificado, quienes se encuentran a su disposición para resolver, orientar, y atender todas las consultas técnicas que requieran. Así también, nuestro departamento de mantenimiento cuenta con la capacidad para atender todos los requerimientos desde soluciones puntuales para mantenimiento, hasta grandes proyectos; bajo los más estrictos controles de calidad, cortos plazos de entrega, y precios competitivos. para  el mercado de la industria alimentaria.</p>
                <!--<a href="about.html" class="btn red-btn2">read more</a>-->
            </div>
    	</div>
    
	    <div class="right-content">
	    <!-- img -->
	        <div class="right-img">
	            <img src="{{ asset('landing/img/gallery/safe_in1.png') }}" alt="">
	        </div>
	        <div class="support-img-cap text-center">
	            <span>2013</span>
	            <p>Desde</p>
	        </div>
	    </div>
	</div>
</section>
<!-- About Area End -->

<!-- Testimonial Start -->
<div class="testimonial-area t-bg testimonial-padding">
    <div class="container ">
       
        <div class="row">
            <div class="col-xl-10 col-lg-11 col-md-10 offset-xl-1">
                <div class="h1-testimonial-active">
        		    <!-- Single Testimonial -->
                    <div class="single-testimonial">
                        <!-- Testimonial Content -->
                        <div class="testimonial-caption ">
                            <div class="testimonial-top-cap">
                	            <!-- SVG icon -->

                	            <div class="section-tittle section-tittle6 mb-50">
	                                <div class="front-text">
	                        			<h2 class="">Misión</h2>
	                    			</div>
                                </div>
                                <p>Somos una empresa dedicada a brindar soluciones para la industria y suministros especiales, comprometidos con la satisfacción de nuestros clientes por medio de una política interna de mejora continua y un ambiente de trabajo agradable y eficiente fomentando el trabajo en equipo y el compromiso de nuestros recursos humanos con los objetivos institucionales</p>
                            </div>
                        </div>
                	</div>

                	<!-- Single Testimonial -->
                    <div class="single-testimonial">
                        <!-- Testimonial Content -->
                        <div class="testimonial-caption ">
                            <div class="testimonial-top-cap">
                	            <!-- SVG icon -->

                	            <div class="section-tittle section-tittle6 mb-50">
	                                <div class="front-text">
	                        			<h2 class="">Visión</h2>
	                    			</div>
                                </div>
                                <p>Somos una empresa dedicada a brindar soluciones para la industria y suministros especiales, comprometidos con la satisfacción de nuestros clientes por medio de una política interna de mejora continua y un ambiente de trabajo agradable y eficiente fomentando el trabajo en equipo y el compromiso de nuestros recursos humanos con los objetivos institucionales</p>
                            </div>
                        </div>
                	</div>

                	<!-- Single Testimonial -->
                    <div class="single-testimonial">
                        <!-- Testimonial Content -->
                        <div class="testimonial-caption ">
                            <div class="testimonial-top-cap">
                	            <!-- SVG icon -->

                	            <div class="section-tittle section-tittle6 mb-50">
	                                <div class="front-text">
	                        			<h2 class="">Valores</h2>
	                    			</div>
                                </div>
                                <ul class="unordered-list">
									<li><p>Servicio a la calidad</p></li>
									<li><p>Compromiso</p></li>
									<li><p>Honestidad</p></li>
									<li><p>Responsabilidad</p></li>
								</ul>
                            </div>
                        </div>
                	</div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonial End -->

<!-- Team Start -->
<div class="team-area section-padding30">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
            <!-- Section Tittle -->
                <div class="section-tittle section-tittle5 mb-50">
                    <div class="front-text">
                        <h2 class="">NUESTRO EQUIPO</h2>
                    </div>
                    <!--<span class="back-text">exparts</span>-->
	            </div>
    	    </div>
        </div>
        
        <div class="row">
        <!-- single Tem -->
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-">
                <div class="single-team mb-30">
        	        <div class="team-img">
                        <img src="{{ asset('landing/img/gallery/team1.png') }}" alt="">
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-">
                <div class="single-team mb-30">
                    <div class="team-img">
                        <img src="{{ asset('landing/img/gallery/team2.png') }}" alt="">
            	    </div>
                </div>
                
            </div>
            
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-">
                <div class="single-team mb-30">
                    <div class="team-img">
                        <img src="{{ asset('landing/img/gallery/team3.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Team End -->
@endsection