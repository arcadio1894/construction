@extends('layouts.appAdmin2')

@section('openAttendance')
    menu-open
@endsection

@section('activeAttendance')
    active
@endsection

@section('activeListRegime')
    active
@endsection

@section('title')
    Registrar Régimen de trabajo
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
        .modal-body {
            max-height: calc(100vh - 212px);
            overflow-y: auto;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Regímenes de Trabajo</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Registrar Regímenes de Trabajo</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Regímenes de Trabajo</a>
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
                    <h3 class="card-title">REGÍMENES REGISTRADOS</h3>

                    <div class="card-tools">
                        <button type="button" id="newRegime" class="btn btn-warning btn-sm " > <i class="far fa-clock"></i> Agregar Régimen </button>

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="body-regime">
                    @foreach( $regimes as $regime )
                        <div class="callout callout-info">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Nombre</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                        </div>
                                        <input type="text" onkeyup="mayus(this);" data-name class="form-control" placeholder="Nombre" value="{{ $regime->name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Descripción corta</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                        </div>
                                        <textarea data-description onkeyup="mayus(this);" class="form-control">{{ $regime->description }}</textarea>
                                        {{--<input type="text" onkeyup="mayus(this);" data-description class="form-control" placeholder="Descripción corta" value="{{ $regime->description }}">--}}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Estado</label><br>
                                        <input data-active class="form-control checkbox" type="checkbox" {{ ($regime->active) ? 'checked':'' }} name="active" data-bootstrap-switch data-off-color="danger" data-on-text=" ACTIVO " data-off-text="INACTIVO" data-on-color="success">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="exampleInputEmail1">Acciones</label>
                                    <br>
                                    <button type="button" data-save="{{ $regime->id }}" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar cambios"><i class="fas fa-save"></i> </button>
                                    <button type="button" data-edit="{{ $regime->id }}" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar horarios"><i class="fas fa-pen"></i> </button>
                                    <button type="button" data-delete="{{ $regime->id }}" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar régimen"><i class="fas fa-trash"></i> </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Horarios asociados al régimen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit">
                    @csrf
                    <input type="hidden" id="id_regime" name="id_regime">
                    <div class="modal-body" id="body-details">




                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-saveDetails" class="btn btn-success">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <template id="template-detail">
        <div class="form-group row">
            <div class="col-md-6">
                <label for="day">Día de la semana</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="hidden" data-detailid  name="detailIds[]" class="form-control">
                    <input type="hidden" data-daynum  name="dayNums[]" class="form-control">
                    <input type="text" data-day name="days[]" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-6">
                <label for="regime">Horario</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                    </div>
                    <select data-workingday name="workingDays[]" class="form-control form-control-sm select2 workingday" style="width: 100%;">
                        <option value="0" disabled selected>Seleccione</option>
                        @foreach( $workingDays as $workingDay )
                            <option value="{{ $workingDay->id }}" >{{ $workingDay->description}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </template>

    <template id="template-regime">
        <div class="callout callout-info">
            <div class="row">
                <div class="col-md-3">
                    <label for="exampleInputEmail1">Nombre</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <input type="text" onkeyup="mayus(this);" data-name class="form-control" placeholder="Nombre" >
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="exampleInputEmail1">Descripción corta</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <textarea data-description onkeyup="mayus(this);" class="form-control"></textarea>
                        {{--<input type="text"  data-description class="form-control" placeholder="Descripción corta" >--}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Estado</label><br>
                        <input data-active class="form-control checkbox" type="checkbox" name="active" data-bootstrap-switch data-off-color="danger" data-on-text=" ACTIVO " data-off-text="INACTIVO" data-on-color="success">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="exampleInputEmail1">Acciones</label>
                    <br>
                    <button type="button" data-save="" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Guardar cambios"><i class="fas fa-save"></i> </button>
                    <button type="button" data-edit="" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar régimen"><i class="fas fa-pen"></i> </button>
                    <button type="button" data-delete="" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar régimen"><i class="fas fa-trash"></i> </button>
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

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/regime/create.js') }}"></script>
@endsection
