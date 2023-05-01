@extends('layouts.appAdmin2')

@section('openPaySlips')
    menu-open
@endsection

@section('activePaySlips')
    active
@endsection

@section('activeListPaySlip')
    active
@endsection

@section('title')
    Boletas de pago
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">
    <!-- VDialog -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/vdialog/css/vdialog.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }

        nav > .nav.nav-tabs{
            border: none;
            color:#fff;
            background:#001028;
            border-radius:0;
        }
        nav > div a.nav-item.nav-link
        {
            border: none;
            color:#fff;
            background:#001028;
            border-radius:0;
        }

        nav > div a.nav-item.nav-link.active:after
        {
            content: "";
            position: relative;
            bottom: -60px;
            left: -10%;
            border: 15px solid transparent;
            border-top-color: #fcbc23;
        }
        .tab-content{
            background: #fdfdfd;
            line-height: 25px;
            border: 1px solid #ddd;
            border-top:5px solid #fcbc23;
            border-bottom:5px solid #fcbc23;
            padding:30px 25px;
        }

        nav > div a.nav-item.nav-link:hover,
        nav > div a.nav-item.nav-link:focus,
        nav > div a.nav-item.nav-link.active
        {
            border: none;
            background: #fcbc23;
            color:#000000;
            border-radius:0;
            transition:background 0.20s linear;
        }
        .table {
            border-radius: 0.2rem;
            width: 100%;
            padding-bottom: 1rem;
            color: #212529;
            margin-bottom: 0;
        }
        .table td {
            white-space: nowrap;
        }
        .table-wrapper {
            max-height: 500px;
            overflow: auto;
            width: 100%;
        }
        table,
        thead,
        tr,
        tbody,
        th,
        td {
            text-align: center;
        }

        .table td {
            text-align: center;
        }
        .datepicker {
            z-index: 10000 !important;
        }
        .tg  {
            border-collapse: separate;
            border-spacing:0;
        }
        .tg td{
            border-color:black;
            border-style:solid;
            border-width:0.5px;
            font-family:Arial, sans-serif;
            font-size:13px;
            padding:5px 5px;
            word-break:normal;
        }
        .tg th{
            border-color:black;
            border-style:solid;
            border-width:1px;
            font-family:Arial, sans-serif;
            font-size:13px;
            font-weight:normal;
            padding:5px 5px;
            word-break:normal;
            border-collapse: separate;
        }
        .tg .tg-0pky{
            text-align:left;
            vertical-align:top;
            border-collapse: separate;
        }
        .center {
            margin-left: auto;
            margin-right: auto;
        }
        .numbers {
            text-align:right !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Ver Boleta de Pago</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Boletas de pago</h5>
    <a href="{{ route('paySlip.boletas.semanales', $worker->id) }}" class="btn btn-outline-primary btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de boletas</a>&nbsp;

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('paySlip.boletas.semanales', $worker->id) }}"><i class="fa fa-archive"></i> Boletas de pago</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Ver boleta</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="row justify-content-center">
        <div class="table-responsive align-center">
            <table class="tg center" style="table-layout: auto; width: 764px">
                <colgroup>
                    <col style="width: 121px">
                    <col style="width: 95px">
                    <col style="width: 101px">
                    <col style="width: 22px">
                    <col style="width: 101px">
                    <col style="width: 101px">
                    <col style="width: 21px">
                    <col style="width: 101px">
                    <col style="width: 101px">
                </colgroup>
                <tbody>
                <tr>
                    <td class="tg-0pky" colspan="6"></td>
                    <td class="tg-0pky" colspan="3" rowspan="4"><br><img src="{{ asset('admin/dist/img/Logo.svg') }}" alt=""></td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="6" id="empresa">Empresa: {{ $boleta->empresa }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="6" id="ruc">RUC: {{ $boleta->ruc }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="6"></td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="3" id="codigo">Código: {{ $boleta->codigo }}</td>
                    <td class="tg-0pky" colspan="6" id="semana">Semana: {{ $boleta->semana }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="3" id="nombre">Nombre: {{ $boleta->nombre }}</td>
                    <td class="tg-0pky" colspan="6" id="fecha">Fecha: {{ $boleta->fecha }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="3" id="cargo">Cargo: {{ $boleta->cargo }}</td>
                    <td class="tg-0pky" colspan="6"></td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="3"></td>
                    <td class="tg-0pky" colspan="6" style="border-bottom-color: #ffffff"></td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="3">INGRESOS</td>
                    <td class="tg-0pky" rowspan="7" style="border-color: #ffffff"></td>
                    <td class="tg-0pky">DESCUENTOS</td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky" rowspan="2" style="border-color: #ffffff"></td>
                    <td class="tg-0pky">APORTE</td>
                    <td class="tg-0pky"></td>
                </tr>
                <tr>
                    <td class="tg-0pky">PAGO x DIA</td>
                    <td class="tg-0pky numbers" id="pagoxdia">{{ $boleta->pagoxdia }}</td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky" id="sistemaPension">{{ $boleta->sistemaPension }}</td>
                    <td class="tg-0pky numbers" id="montoSistemaPension">{{ $boleta->montoSistemaPension }}</td>
                    <td class="tg-0pky">ESSALUD</td>
                    <td class="tg-0pky numbers" id="essalud">{{ $boleta->essalud }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky">PAGO x HORA</td>
                    <td class="tg-0pky numbers" id="pagoXHora">{{ $boleta->pagoXHora }}</td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky">RENTA 5° CAT</td>
                    <td class="tg-0pky numbers" id="rentaQuintaCat">{{ $boleta->rentaQuintaCat }}</td>
                    <td class="tg-0pky" colspan="3" rowspan="5" style="border-bottom-color: #ffffff;border-top-color: #ffffff;border-left-color: #ffffff"></td>
                </tr>
                <tr>
                    <td class="tg-0pky">DIAS TRAB.</td>
                    <td class="tg-0pky numbers" id="diasTrabajados">{{ $boleta->diasTrabajados }}</td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky">PENSION</td>
                    <td class="tg-0pky numbers" id="pensionDeAlimentos">{{ $boleta->pensionDeAlimentos }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky">ASIG. FAMILIAR</td>
                    <td class="tg-0pky numbers" id="asignacionFamiliarDiaria">{{ $boleta->asignacionFamiliarDiaria }}</td>
                    <td class="tg-0pky numbers" id="asignacionFamiliarSemanal">{{ $boleta->asignacionFamiliarSemanal }}</td>
                    <td class="tg-0pky">PRÉSTAMOS</td>
                    <td class="tg-0pky numbers" id="prestamo">{{ $boleta->prestamo }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky">H. ORDINAR</td>
                    <td class="tg-0pky numbers" id="horasOrdinarias">{{ $boleta->horasOrdinarias }}</td>
                    <td class="tg-0pky numbers" id="montoHorasOrdinarias">{{ $boleta->montoHorasOrdinarias }}</td>
                    <td class="tg-0pky">OTROS</td>
                    <td class="tg-0pky numbers" id="otros">{{ $boleta->otros }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky">H. AL 25%</td>
                    <td class="tg-0pky numbers" id="horasAl25">{{ $boleta->horasAl25 }}</td>
                    <td class="tg-0pky numbers" id="montoHorasAl25">{{ $boleta->montoHorasAl25 }}</td>
                    <td class="tg-0pky">TOTAL DESC</td>
                    <td class="tg-0pky numbers" id="totalDescuentos">{{ $boleta->totalDescuentos }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky">H. AL 35%</td>
                    <td class="tg-0pky numbers" id="horasAl35">{{ $boleta->horasAl35 }}</td>
                    <td class="tg-0pky numbers" id="montoHorasAl35">{{ $boleta->montoHorasAl35 }}</td>
                    <td class="tg-0pky" colspan="6" rowspan="2" style="border-top-color: #ffffff;border-left-color: #ffffff;border-bottom-color: #ffffff"></td>
                </tr>
                <tr>
                    <td class="tg-0pky">H. AL 100%</td>
                    <td class="tg-0pky numbers" id="horasAl100">{{ $boleta->horasAl100 }}</td>
                    <td class="tg-0pky numbers" id="montoHorasAl100">{{ $boleta->montoHorasAl100 }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky">DOMINICAL</td>
                    <td class="tg-0pky numbers" id="dominical">{{ $boleta->dominical }}</td>
                    <td class="tg-0pky numbers" id="montoDominical">{{ $boleta->montoDominical }}</td>
                    <td class="tg-0pky" rowspan="8" style="border-color: #ffffff"></td>
                    <td class="tg-0pky" colspan="4" style="border: 1px solid black;">RESUMEN</td>
                    <td class="tg-0pky" rowspan="8" style="border-top-color: #ffffff;border-bottom-color: #ffffff;border-left-color: #ffffff"></td>
                </tr>
                <tr>
                    <td class="tg-0pky">VACACIONES</td>
                    <td class="tg-0pky numbers" id="vacaciones">{{ $boleta->vacaciones }}</td>
                    <td class="tg-0pky numbers" id="montoVacaciones">{{ $boleta->montoVacaciones }}</td>
                    <td class="tg-0pky" colspan="2">TOTAL INGRESOS</td>
                    <td class="tg-0pky numbers" colspan="2" id="totalIngresos1">{{ $boleta->totalIngresos }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky">REINTEGRO</td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky numbers" id="reintegro">{{ $boleta->reintegro }}</td>
                    <td class="tg-0pky" colspan="2">TOTAL DESCUENTOS</td>
                    <td class="tg-0pky numbers" colspan="2" id="totalDescuentos1">{{ $boleta->totalDescuentos }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky">GRATIFICACIÓN</td>
                    <td class="tg-0pky"></td>
                    <td class="tg-0pky numbers" id="gratificaciones">{{ $boleta->gratificaciones }}</td>
                    <td class="tg-0pky" colspan="4" rowspan="4"></td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="3"></td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="3" id="totalIngresos">TOTAL INGRESOS: {{ $boleta->totalIngresos }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="3" rowspan="2"></td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="2">NETO A  PAGAR</td>
                    <td class="tg-0pky numbers" colspan="2" id="totalNetoPagar">{{ $boleta->totalNetoPagar }}</td>
                </tr>
                <tr>
                    <td class="tg-0pky" colspan="9" rowspan="11" style="border-top-color: #ffffff"></td>
                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                <tr>

                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <!-- Vdialog -->
    <script src="{{ asset('admin/plugins/vdialog/js/lib/vdialog.js') }}"></script>

    <script src="{{asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>

@endsection

@section('scripts')
    {{--<script>
        $(function () {
            $('#worker').select2({
                placeholder: "Trabajador",
            });
            $('#type').select2({
                placeholder: "Tipo",
            });
            $('#year').select2({
                placeholder: "Año",
            });
            $('#month').select2({
                placeholder: "Mes",
            });
            $('#week').select2({
                placeholder: "Semana",
            });
        })
    </script>--}}
    {{--<script src="{{ asset('js/boleta/createByWorker.js') }}"></script>--}}
@endsection




