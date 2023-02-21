@extends('layouts.appAdmin2')

@section('openAttendance')
    menu-open
@endsection

@section('activeAttendance')
    active
@endsection

@section('activeListWorkingDay')
    active
@endsection

@section('title')
    Registrar Jornadas
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/daterangepicker/daterangepicker.css') }}">
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
    <h1 class="page-title">Jornadas de Trabajo</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Registrar Jornadas de Trabajo</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Jornadas de Trabajo</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">HORARIOS REGISTRADOS</h3>

                    <div class="card-tools">
                        <button type="button" id="newWorkingDay" class="btn btn-warning btn-sm " > <i class="far fa-clock"></i> Agregar Jornada </button>

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="body-workingDay">
                    @foreach( $workingDays as $workingDay )
                        <div class="callout callout-info">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                        </div>
                                        <input type="text" onkeyup="mayus(this);" data-description class="form-control" placeholder="Descripción" value="{{ $workingDay->description }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group date datestart" data-target-input="nearest">
                                        <input type="text" data-dateStart class="form-control timepicker " value="{{$workingDay->time_start}}" data-time2="{{$workingDay->time_start}}" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group date dateend" data-target-input="nearest">
                                        <input type="text" data-dateEnd class="form-control timepicker " value="{{ $workingDay->time_fin }}" data-time="12:00:00.000" />
                                        <div class="input-group-append" >
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input data-enable class="form-control checkbox" type="checkbox" {{ ($workingDay->enable) ? 'checked':'' }} name="enable" data-bootstrap-switch data-off-color="danger" data-on-text=" ACTIVO " data-off-text="INACTIVO" data-on-color="success">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" data-save="{{ $workingDay->id }}" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar jornada"><i class="fas fa-save"></i> </button>
                                    <button type="button" data-delete="{{ $workingDay->id }}" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar jornada"><i class="fas fa-trash"></i> </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <template id="template-workingday">
        <div class="callout callout-info">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <input type="text" onkeyup="mayus(this);" data-description class="form-control" placeholder="Descripción">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group date datestart" data-target-input="nearest">
                        <input type="text" data-dateStart class="form-control timepicker" />
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group date dateend" data-target-input="nearest">
                        <input type="text" data-dateEnd class="form-control timepicker"/>
                        <div class="input-group-append" >
                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input data-enable class="form-control" checked type="checkbox" name="enable" data-bootstrap-switch data-off-color="danger" data-on-text=" ACTIVO " data-off-text="INACTIVO" data-on-color="success">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" data-save class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar jornada"><i class="fas fa-save"></i> </button>
                    <button type="button" data-delete class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar jornada"><i class="fas fa-trash"></i> </button>
                </div>
            </div>
        </div>
    </template>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin/plugins/MDtimepicker/js/mdtimepicker.js') }}"></script>
    <!-- Vdialog -->
    <script src="{{ asset('admin/plugins/vdialog/js/lib/vdialog.js') }}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            //$('#datemask').inputmask()
            $('.timepicker').mdtimepicker({
                timeFormat: 'hh:mm:ss.000',
                format:'hh:mm tt',
                theme:'blue',
                readOnly:true,
                hourPadding:true,
                clearBtn:false,
                is24hour: false,
            });

            $('#admission_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#birthplace').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });

            $('#termination_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#phone').inputmask('(99) 999-999-999', { 'placeholder': '(99) 999-999-999' });
            $('#dni').inputmask('99999999', { 'placeholder': '99999999' });

            //Initialize Select2 Elements
            $('#work_function').select2({
                placeholder: "Selecione un cargo",
            });
            $('#pension_system').select2({
                placeholder: "Selecione un sistema",
            });
            $('#civil_status').select2({
                placeholder: "Selecione un estado civill",
            });
            $('#contract').select2({
                placeholder: "Selecione un contrato",
            });

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/workingDay/create.js') }}"></script>
@endsection
