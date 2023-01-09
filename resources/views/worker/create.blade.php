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
    Registrar colaborador
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Colaboradores</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Registrar nuevo colaborador</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('worker.index') }}"><i class="fa fa-archive"></i> Colaboradores</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <form id="formCreate" class="form-horizontal" data-url="{{ route('worker.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Datos generales</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">

                            <div class="col-md-6">
                                <label for="first_name">Nombres </label>
                                <input type="text" id="first_name" name="first_name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="last_name">Apellidos </label>
                                <input type="text" id="last_name" name="last_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="dni">DNI/C.E. </label>
                                <input type="text" id="dni" name="dni" class="form-control" >
                            </div>

                            <div class="col-md-6">
                                <label for="work_function">Cargo </label>
                                <select id="work_function" name="work_function" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="0">NINGUNO</option>
                                    @foreach( $work_functions as $function )
                                        <option value="{{ $function->id }}">{{ $function->description }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="level_school">Nivel de instrucción</label>
                                <input type="text" id="level_school" name="level_school" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="gender">Género</label>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="gender1" name="gender" value="m" checked>
                                            <label for="gender1">Masculino
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="gender2" name="gender" value="f" >
                                            <label for="gender2">Femenino
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="admission_date">Fecha de ingreso </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="admission_date" name="admission_date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="termination_date">Fecha de Cese </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="termination_date" name="termination_date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Datos de Ubicación</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="personal_address">Dirección </label>
                                <textarea name="personal_address" cols="30" class="form-control" style="word-break: break-all;" placeholder="Ingrese dirección ...."></textarea>
                            </div>
                            <div class="col-sm-6">
                                <label for="email">Email </label>
                                <textarea name="email" cols="30" class="form-control" style="word-break: break-all;" placeholder="Ingrese email ...."></textarea>

                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="phone">Teléfono </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" id="phone" name="phone" class="form-control" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="birthplace">Fecha de nacimiento </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="birthplace" name="birthplace" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="civil_status">Estado civil </label>
                                <select id="civil_status" name="civil_status" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="0">NINGUNO</option>
                                    @foreach( $civil_statuses as $civil_status )
                                        <option value="{{ $civil_status->id }}">{{ $civil_status->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="num_children">N° de hijos </label>
                                <input type="number" id="num_children" name="num_children" class="form-control" placeholder="0" min="0" value="0" step="1">
                            </div>

                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Datos para RR.HH.</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">

                            <input type="hidden" id="value_assign_family" value="{{ $value_assign_family }}">

                            <div class="col-md-6">
                                <label for="daily_salary">Salario Diario </label>
                                <input type="number" id="daily_salary" name="daily_salary" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" >
                            </div>
                            <div class="col-md-6">
                                <label for="monthly_salary">Salario Mensual </label>
                                <input type="number" id="monthly_salary" name="monthly_salary" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="pension_system">Sistema Pensión </label>
                                <select id="pension_system" name="pension_system" class="form-control select2 pension_system" style="width: 100%;">
                                    <option></option>
                                    <option value="0">NINGUNO</option>
                                    @foreach( $pension_systems as $pension_system )
                                        <option value="{{ $pension_system->id }}" data-percentage="{{ $pension_system->percentage }}">{{ $pension_system->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="pension">Pension Alimentos </label>
                                <div class="input-group">
                                    <input type="number" id="pension" name="pension" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" >
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="five_category">Quinta Categoría </label>
                                <input type="number" id="five_category" name="five_category" class="form-control" placeholder="0.00" min="0" value="0" step="0.01" >
                            </div>
                            <div class="col-md-6">
                                <label for="observation">Observación </label>
                                <textarea name="observation" id="observation" cols="30" class="form-control" style="word-break: break-all;" placeholder="Ingrese observación ...."></textarea>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Contactos de Emergencia</h3>

                        <div class="card-tools">
                            <button type="button" id="newContact" class="btn btn-xs btn-warning" > <i class="fas fa-phone-volume"></i> Agregar Contacto </button>

                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>

                        </div>
                    </div>
                    <div class="card-body" id="body-contacts">
                        {{--<div class="callout callout-info">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="work_function">Nombre </label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                        </div>
                                        <input type="text" data-contactname name="contacts[]" class="form-control" placeholder="Nombre" value="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="work_function">Parentesco </label>
                                        <select name="relations[]" class="relation form-control select2" style="width: 100%;">
                                            <option></option>
                                            @foreach( $work_functions as $function )
                                                <option value="{{ $function->id }}">{{ $function->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="work_function">Teléfono </label>
                                        <input type="text" data-phone name="phones[]" class="form-control" placeholder="000000000" value="">

                                    </div>
                                </div>
                            </div>

                        </div>--}}

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar colaborador</button>
            </div>
        </div>
        <!-- /.card-footer -->
    </form>

    <template id="template-contact">
        <div class="callout callout-info">
            <div class="row">
                <div class="col-md-12">
                    <label for="work_function">Nombre </label>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <input type="text" data-contactname name="contacts[]" class="form-control" placeholder="Nombre" value="">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="work_function">Parentesco </label>
                        <select name="relations[]" class="relation form-control form-control-sm select2" style="width: 100%;">
                            <option></option>
                            @foreach( $relationships as $relationship )
                                <option value="{{ $relationship->id }}">{{ $relationship->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="work_function">Teléfono </label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" data-phone name="phones[]" class="form-control" placeholder="000000000" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 offset-4">
                    <button type="button" data-deletecontact class="btn btn-xs btn-outline-danger btn-block">Eliminar contacto</button>
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
    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(function () {
            //$('#datemask').inputmask()
            $('#admission_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#birthplace').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });

            $('#termination_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            //$('#phone').inputmask('(99) 999-999-999', { 'placeholder': '(99) 999-999-999' });
            //$('#dni').inputmask('99999999', { 'placeholder': '99999999' });

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
            $('.relation').select2({
                placeholder: "Selecione un parentesco",
            });

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/worker/create.js') }}"></script>
@endsection
