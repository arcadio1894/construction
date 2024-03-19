@extends('layouts.appAdmin2')

@section('openAttendance')
    menu-open
@endsection

@section('activeAttendance')
    active
@endsection

@section('activeListAttendance')
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
    <h1 class="page-title">Asistencia del día {{ $assistance->date_assistance->locale('es_ES')->dayName }} {{ $assistance->date_assistance->format('d/m/Y') }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Registrar Asistencias</h5>
    <a href="{{ route('assistance.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Regresar al calendario</a>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('assistance.index') }}"><i class="fa fa-archive"></i> Asistencias</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Registrar</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <input type="hidden" id="assistance_id" value="{{ $assistance->id }}">
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
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-gradient-maroon elevation-1" style="background-color: #42F1CC">U</span>
                <div class="info-box-content">
                    <span class="info-box-number">LICENCIA SIN GOZO</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon elevation-1" style="background-color: #9cf210">PH</span>

                <div class="info-box-content">
                    <span class="info-box-number">PERMISO POR HORA</span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon elevation-1" style="background-color: #ff851b">TC</span>

                <div class="info-box-content">
                    <span class="info-box-number">TERMINO CONTRATO</span>
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
                        <div class="col-md-3">
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
                        <div class="col-md-1">
                            <div class="form-group">
                                <strong>H. DESC.</strong>
                            </div>
                        </div>
                        <div class="col-md-1">
                            &nbsp;
                        </div>
                    </div>
                    @for( $i = 0; $i < count($arrayAssistances); $i++ )
                    <div class="row">
                        <div class="col-md-3">
                            {{--<input type="text" style="font-size: 15px" readonly data-worker class="form-control form-control-sm" value="{{ $arrayAssistances[$i]['worker'] }}" >--}}
                            <textarea name="" style="font-size: 15px" data-worker cols="30" readonly class="form-control">{{ $arrayAssistances[$i]['worker'] }}</textarea>
                        </div>
                        <div class="col-md-2">
                            <select data-workingDay @cannot('register_assistance') disabled @endcannot class="workingDays form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                @foreach( $workingDays as $workingDay )
                                    <option value="{{ $workingDay->id }}" data-time_fin="{{ $workingDay->time_fin }}" data-time_start="{{ $workingDay->time_start }}" {{ ($workingDay->id == $arrayAssistances[$i]['working_day']) ? 'selected':'' }}>{{ $workingDay->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group date input-group-sm datestart" data-target-input="nearest">
                                <input type="text" @cannot('register_assistance') disabled @endcannot data-dateStart value="{{ $arrayAssistances[$i]['hour_entry'] }}" class="form-control timepicker" />
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group date input-group-sm dateend" data-target-input="nearest">
                                <input type="text" @cannot('register_assistance') disabled @endcannot data-dateEnd value="{{ $arrayAssistances[$i]['hour_out'] }}" class="form-control timepicker" />
                                <div class="input-group-append" >
                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <select data-status @cannot('register_assistance') disabled @endcannot class="state form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                <option value="A" {{ ($arrayAssistances[$i]['status'] == 'A') ? 'selected':'' }}>A</option>
                                <option value="F" {{ ($arrayAssistances[$i]['status'] == 'F') ? 'selected':'' }}>F</option>
                                <option value="S" {{ ($arrayAssistances[$i]['status'] == 'S') ? 'selected':'' }}>S</option>
                                <option value="M" {{ ($arrayAssistances[$i]['status'] == 'M') ? 'selected':'' }}>M</option>
                                <option value="J" {{ ($arrayAssistances[$i]['status'] == 'J') ? 'selected':'' }}>J</option>
                                <option value="V" {{ ($arrayAssistances[$i]['status'] == 'V') ? 'selected':'' }}>V</option>
                                <option value="P" {{ ($arrayAssistances[$i]['status'] == 'P') ? 'selected':'' }}>P</option>
                                <option value="T" {{ ($arrayAssistances[$i]['status'] == 'T') ? 'selected':'' }}>T</option>
                                <option value="H" {{ ($arrayAssistances[$i]['status'] == 'H') ? 'selected':'' }}>H</option>
                                <option value="L" {{ ($arrayAssistances[$i]['status'] == 'L') ? 'selected':'' }}>L</option>
                                <option value="U" {{ ($arrayAssistances[$i]['status'] == 'U') ? 'selected':'' }}>U</option>
                                <option value="PH" {{ ($arrayAssistances[$i]['status'] == 'PH') ? 'selected':'' }}>PH</option>
                                <option value="TC" {{ ($arrayAssistances[$i]['status'] == 'TC') ? 'selected':'' }}>TC</option>

                            </select>
                        </div>
                        <div class="col-md-1">
                            {{--<textarea name="" data-observacion cols="30" class="form-control form-control-sm">{{ $arrayAssistances[$i]['obs_justification'] }}</textarea>--}}
                            <input type="number" @cannot('register_assistance') readonly @endcannot name="hours_discount" id="hours_discount" data-hours_discount class="form-control form-control-sm" step="0.1" min="0" value="{{ $arrayAssistances[$i]['hours_discount'] }}">
                        </div>
                        <div class="col-md-1">
                            @can('register_assistance')
                                <button type="button" data-save data-worker="{{ $arrayAssistances[$i]['worker_id'] }}" data-assistancedetail="{{ $arrayAssistances[$i]['assistance_detail_id'] }}" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar asistencia"><i class="fas fa-save"></i> </button>
                                @if($arrayAssistances[$i]['assistance_detail_id'] != '')
                                <button type="button" data-delete data-worker="{{ $arrayAssistances[$i]['worker_id'] }}" data-assistancedetail="{{ $arrayAssistances[$i]['assistance_detail_id'] }}" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar asistencia"><i class="fas fa-trash"></i> </button>
                                @endif
                            @endcan
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
                timeFormat: 'hh:mm:ss.000',
                format:'hh:mm tt',
                theme:'blue',
                readOnly:true,
                hourPadding:true,
                clearBtn:false,
                is24hour: false,
            });

            //Initialize Select2 Elements
            $('.workingDays').select2({
                placeholder: "Elija jornada",
            });
            $('.state').select2({
                placeholder: "Estado",
            });


            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/assistance/register.js') }}"></script>
@endsection
