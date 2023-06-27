@extends('layouts.appAdmin2')

@section('openAttendance')
    menu-open
@endsection

@section('activeAttendance')
    active
@endsection

@section('activeReportHoursDiary')
    active
@endsection

@section('title')
    Reporte Horas Diarias
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/MDtimepicker/css/mdtimepicker.css') }}">
    <!-- VDialog -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/vdialog/css/vdialog.css') }}">

    {{--<link href="{{ asset('admin/plugins/fixedheadertable/960.css') }}" rel="stylesheet"  />
    --}}<link href="{{ asset('admin/plugins/fixedheadertable/defaultTheme.css') }}" rel="stylesheet"  />
    <link href="{{ asset('admin/plugins/fixedheadertable/myTheme.css') }}" rel="stylesheet" />

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
        .table th:first-child,
        .table td:first-child {
            position: sticky;
            left: 0;
            background-color: #ad6c80;
            color: #373737;
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
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Reporte de Horas Diarias</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Horas Diarias</h5>
    <a href="{{ route('assistance.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Regresar al calendario</a>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Asistencias</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Horas Diarias</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <div class="row">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-success elevation-1">A</span>

                <div class="info-box-content">
                    <span class="info-box-number">ASISTIÓ</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-danger elevation-1">F</span>

                <div class="info-box-content">
                    <span class="info-box-number">FALTÓ</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-gray-dark elevation-1">S</span>

                <div class="info-box-content">
                    <span class="info-box-number">SUSPENSIÓN</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">M</span>

                <div class="info-box-content">
                    <span class="info-box-number">DESCANSO MÉDICO</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-warning elevation-1">J</span>

                <div class="info-box-content">
                    <span class="info-box-number">FALTA JUSTIFICADA</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-fuchsia elevation-1">V</span>

                <div class="info-box-content">
                    <span class="info-box-number">VACACIONES</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-blue elevation-1">P</span>

                <div class="info-box-content">
                    <span class="info-box-number">PERMISO</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-indigo elevation-1">T</span>

                <div class="info-box-content">
                    <span class="info-box-number">TARDANZA</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-navy elevation-1">H</span>

                <div class="info-box-content">
                    <span class="info-box-number">FERIADO</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon elevation-1" style="background-color: #42F1CC">L</span>

                <div class="info-box-content">
                    <span class="info-box-number">LICENCIA</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-1">
            <button type="button" id="btn-prev" class="btn btn-app">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <div class="col-md-10 text-center">
            <h1 data-year="{{$yearCurrent}}"> {{ $yearCurrent }} </h1>
        </div>
        <div class="col-md-1">
            <button type="button" id="btn-next" class="btn btn-app">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">ASISTENCIAS MENSUALES</h3>

                    <div class="card-tools">
                        <button type="button" id="btn-download" class="btn btn-sm btn-success btn-sm" > <i class="far fa-file-excel"></i> Descargar asistencias </button>

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="body-assistances">
                    <input type="hidden" id="yearCurrent" value="{{ $yearCurrent }}">
                    <input type="hidden" id="monthCurrent" value="{{ $monthCurrent }}">
                    <input type="hidden" id="nameMonth" value="{{ $nameMonth }}">
                    <div class="row">
                        <div class="col-md-12">
                            <nav>
                                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link {{ ($monthCurrent == 1) ? 'active':'' }}" id="nav-january-tab" data-tab="nav-january" data-month="1" data-toggle="tab" href="#nav-january" role="tab" >ENE</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 2) ? 'active':'' }}" id="nav-february-tab" data-tab="nav-february" data-month="2" data-toggle="tab" href="#nav-february" role="tab">FEB</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 3) ? 'active':'' }}" id="nav-march-tab" data-tab="nav-march" data-month="3" data-toggle="tab" href="#nav-march" role="tab" >MAR</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 4) ? 'active':'' }}" id="nav-april-tab" data-tab="nav-april" data-month="4" data-toggle="tab" href="#nav-april" role="tab" >ABR</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 5) ? 'active':'' }}" id="nav-may-tab" data-tab="nav-may" data-month="5" data-toggle="tab" href="#nav-may" role="tab" >MAY</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 6) ? 'active':'' }}" id="nav-june-tab" data-tab="nav-june" data-month="6" data-toggle="tab" href="#nav-june" role="tab" >JUN</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 7) ? 'active':'' }}" id="nav-july-tab" data-tab="nav-july" data-month="7" data-toggle="tab" href="#nav-july" role="tab" >JUL</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 8) ? 'active':'' }}" id="nav-august-tab" data-tab="nav-august" data-month="8" data-toggle="tab" href="#nav-august" role="tab" >AGO</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 9) ? 'active':'' }}" id="nav-september-tab" data-tab="nav-september" data-month="9" data-toggle="tab" href="#nav-september" role="tab" >SEP</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 10) ? 'active':'' }}" id="nav-october-tab" data-tab="nav-october" data-month="10" data-toggle="tab" href="#nav-october" role="tab" >OCT</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 11) ? 'active':'' }}" id="nav-november-tab" data-tab="nav-november" data-month="11" data-toggle="tab" href="#nav-november" role="tab" >NOV</a>
                                    <a class="nav-item nav-link {{ ($monthCurrent == 12) ? 'active':'' }}" id="nav-december-tab" data-tab="nav-december" data-month="12" data-toggle="tab" href="#nav-december" role="tab" >DIC</a>
                                </div>
                            </nav>
                            <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                                <div class="tab-pane fade {{ ($monthCurrent == 1) ? 'show active':'' }}" id="nav-january" role="tabpanel"  >
                                    @if ( $monthCurrent == 1 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 2) ? 'show active':'' }}" id="nav-february" role="tabpanel" >
                                    @if ( $monthCurrent == 2 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                    <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 3) ? 'show active':'' }}" id="nav-march" role="tabpanel">
                                    @if ( $monthCurrent == 3 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 4) ? 'show active':'' }}" id="nav-april" role="tabpanel">
                                    @if ( $monthCurrent == 4 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 5) ? 'show active':'' }}" id="nav-may" role="tabpanel">
                                    @if ( $monthCurrent == 5 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 6) ? 'show active':'' }}" id="nav-june" role="tabpanel">
                                    @if ( $monthCurrent == 6 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 7) ? 'show active':'' }}" id="nav-july" role="tabpanel">
                                    @if ( $monthCurrent == 7 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 8) ? 'show active':'' }}" id="nav-august" role="tabpanel">
                                    @if ( $monthCurrent == 8 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 9) ? 'show active':'' }}" id="nav-september" role="tabpanel">
                                    @if ( $monthCurrent == 9 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 10) ? 'show active':'' }}" id="nav-october" role="tabpanel">
                                    @if ( $monthCurrent == 10 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 11) ? 'show active':'' }}" id="nav-november" role="tabpanel">
                                    @if ( $monthCurrent == 11 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade {{ ($monthCurrent == 12) ? 'show active':'' }}" id="nav-december" role="tabpanel">
                                    @if ( $monthCurrent == 12 )
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 ">
                                                    <div class="table-responsive table-wrapper">
                                                        <table class="table table-hover table-bordered table-sm">
                                                            <thead class="sticky-top">
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                                                @endfor
                                                            </tr>
                                                            <tr>
                                                                <th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                                                    @endfor
                                                                @endfor
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                                                <tr>
                                                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                                                        @endfor
                                                                    @endfor
                                                                </tr>
                                                            @endfor
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <template id="template-complete">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="table-responsive table-wrapper">
                        <table class="table table-hover table-bordered table-sm">
                            <thead class="sticky-top">
                            <tr data-bodytitles>
                                {{--<th style="background-color:#203764; color: #ffffff;">DATOS</th>
                                @for($k=0 ; $k<count($arrayDays) ; $k++)
                                    <th colspan="{{ $arrayDays[$k]['colspan'] }}" style="background-color: {{ $arrayDays[$k]['color'] }}" >{{ $arrayDays[$k]['nameDay'] }}</th>
                                @endfor--}}
                            </tr>
                            <tr data-bodyheader>
                                {{--<th style="background-color:#203764; color: #ffffff;">APELLIDOS Y NOMBRES</th>
                                @for($i=0 ; $i<count($arrayHeaders) ; $i++)
                                    @for( $j=0; $j<count($arrayHeaders[$i])-1;$j++ )
                                        <th style="background-color:{{ ($j == count($arrayHeaders[$i])-2 ) ? '#FFC000': $arrayHeaders[$i][count($arrayHeaders[$i])-1] }}">{{ $arrayHeaders[$i][$j] }}</th>
                                    @endfor
                                @endfor--}}
                            </tr>
                            </thead>
                            <tbody data-bodyassists>
                            {{--@for($k=0 ; $k<count($arrayAssistances) ; $k++)
                                <tr>
                                    <td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
                                    @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                                        @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                                            <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                                        @endfor
                                    @endfor
                                </tr>
                            @endfor--}}
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="template-assistance">
        {{--<tr class="d-flex" data-bodyassistances>--}}
            {{--<td class="col-md-3" >
                {{ $arrayAssistances[$b]['worker'] }}
            </td>--}}
            {{--@for( $c=0 ; $c<count($arrayAssistances[$b]['assistances']) ; $c++ )
                <td style="width:35px; {{ ($arrayAssistances[$b]['assistances'][$c]['status'] == 'N') ? 'color:black':'color:white' }};background-color: {{ $arrayAssistances[$b]['assistances'][$c]['color'] }}">
                    {!! $arrayAssistances[$b]['assistances'][$c]['status'] !!}
                </td>
            @endfor--}}
        {{--</tr>--}}
        <tr data-bodyassistances>
            {{--<td style="background-color:#203764; color: #ffffff;">{{ $arrayAssistances[$k]['worker'] }}</td>
            @for($l=0 ; $l<count($arrayAssistances[$k]['assistances']) ; $l++)
                @for($m=0 ; $m<count($arrayAssistances[$k]['assistances'][$l])-1 ; $m++)
                    <td style="background-color:{{ ($m == count($arrayAssistances[$k]['assistances'][$l])-2) ? '#FFC000': $arrayAssistances[$k]['assistances'][$l][count($arrayAssistances[$k]['assistances'][$l])-1] }}">{{ $arrayAssistances[$k]['assistances'][$l][$m] }}</td>
                @endfor
            @endfor--}}
        </tr>

    </template>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>

    <script src="{{ asset('admin/plugins/MDtimepicker/js/mdtimepicker.js') }}"></script>
    <!-- Vdialog -->
    <script src="{{ asset('admin/plugins/vdialog/js/lib/vdialog.js') }}"></script>
    <!-- FixedHeaderTable -->
    @endsection

@section('scripts')
    <script src="{{ asset('admin/plugins/fixedheadertable/jquery.fixedheadertable.js') }}"></script>

    <script>
        $(function () {

            $('#myTable05').fixedHeaderTable({ altClass: 'odd', footer: false, fixedColumns: 1 });


            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });


        })
    </script>
    <script src="{{ asset('js/assistance/hourDiary.js') }}"></script>
@endsection




