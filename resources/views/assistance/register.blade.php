@extends('layouts.appAdmin2')

@section('openWorker')
    menu-open
@endsection

@section('activeWorker')
    active
@endsection

@section('activeCreateWorker')
    active
@endsection

@section('title')
    Registrar Asistencias
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/MDtimepicker/css/mdtimepicker.css') }}">
    <!-- VDialog -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/vdialog/css/vdialog.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Asistencia del día {{ $assistance->date_assistance->format('d/m/Y') }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Registrar Asistencias</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Asistencias</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Registrar</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <input type="hidden" id="assistance_id" value="{{ $assistance->id }}">
    <div class="row">
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-success elevation-1">A</span>

                    <div class="info-box-content">
                        <span class="info-box-number">ASISTIÓ</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-danger elevation-1">F</span>

                    <div class="info-box-content">
                        <span class="info-box-number">FALTÓ</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-gray-dark elevation-1">S</span>

                    <div class="info-box-content">
                        <span class="info-box-number">SUSPENSIÓN</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1">DM</span>

                    <div class="info-box-content">
                        <span class="info-box-number">DESCANSO MÉDICO</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-warning elevation-1">FJ</span>

                    <div class="info-box-content">
                        <span class="info-box-number">FALTA JUSTIFICADA</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-box">
                    <span class="info-box-icon bg-gradient-fuchsia elevation-1">V</span>

                    <div class="info-box-content">
                        <span class="info-box-number">VACACIONES</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">TRABAJADORES REGISTRADOS</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="body-assistances">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <strong>TRABAJADOR</strong>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <strong>JORNADA</strong>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <strong>HORA ENTRADA</strong>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <strong>HORA SALIDA</strong>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <strong>ESTADO</strong>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <strong>OBSERV. JUSTIF.</strong>
                            </div>
                        </div>
                        <div class="col-md-1">
                            &nbsp;
                        </div>
                    </div>
                    @for( $i = 0; $i < count($arrayAssistances); $i++ )
                    <div class="row">
                        <div class="col-md-2">
                            <textarea name="" style="font-size: 15px" data-worker cols="30" readonly class="form-control">{{ $arrayAssistances[$i]['worker'] }}</textarea>
                        </div>
                        <div class="col-md-2">
                            <select data-workingDay class="workingDays form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                @foreach( $workingDays as $workingDay )
                                    <option value="{{ $workingDay->id }}" {{ ($workingDay->id == $arrayAssistances[$i]['working_day']) ? 'selected':'' }}>{{ $workingDay->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group date input-group-sm" id="timepicker" data-target-input="nearest">
                                <input type="text" data-dateStart value="{{ $arrayAssistances[$i]['hour_entry'] }}" class="form-control timepicker" />
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group date input-group-sm" id="timepicker2" data-target-input="nearest">
                                <input type="text" data-dateEnd value="{{ $arrayAssistances[$i]['hour_out'] }}" class="form-control timepicker" />
                                <div class="input-group-append" >
                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <select data-status class="state form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                <option value="A" {{ ($arrayAssistances[$i]['status'] == 'A') ? 'selected':'' }}>A</option>
                                <option value="F" {{ ($arrayAssistances[$i]['status'] == 'F') ? 'selected':'' }}>F</option>
                                <option value="S" {{ ($arrayAssistances[$i]['status'] == 'S') ? 'selected':'' }}>S</option>
                                <option value="DM" {{ ($arrayAssistances[$i]['status'] == 'DM') ? 'selected':'' }}>DM</option>
                                <option value="FJ" {{ ($arrayAssistances[$i]['status'] == 'FJ') ? 'selected':'' }}>FJ</option>
                                <option value="V" {{ ($arrayAssistances[$i]['status'] == 'V') ? 'selected':'' }}>V</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <textarea name="" data-observacion cols="30" class="form-control form-control-sm">
                                {{ $arrayAssistances[$i]['obs_justification'] }}
                            </textarea>

                        </div>
                        <div class="col-md-1">
                            <button type="button" data-save data-worker="{{ $arrayAssistances[$i]['worker_id'] }}" data-assistancedetail="{{ $arrayAssistances[$i]['assistance_detail_id'] }}" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar asistencia"><i class="fas fa-save"></i> </button>
                        </div>
                    </div>
                    <hr>
                    @endfor
                </div>
            </div>
        </div>
    </div>

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
@endsection

@section('scripts')
    <script>
        $(function () {

            $('.timepicker').mdtimepicker({
                format:'h:mm tt',
                theme:'blue',
                readOnly:true,
                hourPadding:false,
                clearBtn:false

            });

            //Initialize Select2 Elements
            $('.workingDays').select2({
                placeholder: "Selecione una jornada",
            });
            $('.state').select2({
                placeholder: "Selecione un estado",
            });


            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/assistance/register.js') }}"></script>
@endsection
