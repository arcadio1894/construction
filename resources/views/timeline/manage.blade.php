@extends('layouts.appAdmin2')

@section('openTimelines')
    menu-open
@endsection

@section('activeTimelines')
    active
@endsection

@section('activeShowTimelines')
    active
@endsection

@section('title')
    Gestionar Cronograma
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
        .card-header {
            padding: 0.65rem 1rem !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Programación de actividades para el día {{ $timeline->date->format('d/m/Y') }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Gestionar Cronograma</h5>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('index.timelines') }}"><i class="fa fa-archive"></i> Cronogramas</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <input type="hidden" id="idtimeline" value="{{ $timeline->id }}">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">ACTIVIDADES</h3>

                    <div class="card-tools">
                        <button type="button" id="newActivity" class="btn btn-warning btn-sm " > <i class="far fa-clock"></i> Agregar Actividad </button>
                        <button type="button" id="lostActivity" class="btn btn-danger btn-sm " > <i class="fas fa-list-alt"></i> Actividades pendientes </button>

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="body-activities">
                    @foreach( $timeline->activities as $activity )
                        <div class="col-md-12">
                            <div data-checkactivity class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title"></h3>

                                    <div class="card-tools">
                                        <button type="button" data-activityedit="{{ $activity->id }}" class="btn btn-sm btn-outline-success" data-toggle="tooltip" data-placement="top" title="Guardar cambios" ><i class="fas fa-save"></i>
                                        </button>
                                        <button type="button" data-activitydelete="{{ $activity->id }}" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" ><i class="fas fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fas fa-minus text-success"></i></button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label for="quote">Cotización: </label>
                                            <select class="quote_description form-control form-control-sm select2" style="width: 100%;">
                                                <option></option>
                                                <option value="0">Ninguna</option>
                                                @foreach( $quotes as $quote )
                                                    <option value="{{ $quote->id }}" {{ ($quote->id == $activity->quote_id) ? 'selected':'' }} data-quote="{{ $quote->description_quote }}">{{ $quote->order_execution . '-' . $quote->description_quote}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-sm-4">
                                            <label for="descriptionQuote">Descripción: </label>
                                            <textarea name="" data-descriptionQuote cols="30" class="form-control form-control-sm">{{ $activity->description_quote }}</textarea>

                                        </div>
                                        <div class="col-sm-4">
                                            <label for="descriptionQuote">Etapa: </label>
                                            <textarea name="" data-phase cols="30" class="form-control form-control-sm">{{ $activity->phase }}</textarea>

                                        </div>
                                        <div class="col-sm-4">
                                            <label for="activity">Actividad: </label>
                                            <textarea name="" data-activity cols="30" class="form-control form-control-sm">{{ $activity->activity }}</textarea>

                                        </div>
                                        <div class="col-sm-4">
                                            <label for="activity">Responsable: </label>
                                            <select data-performer class="performers form-control form-control-sm select2" style="width: 100%;">
                                                <option></option>
                                                <option value="0">Ninguno</option>
                                                @foreach( $workers as $worker )
                                                    <option value="{{ $worker->id }}" {{ ($worker->id == $activity->performer) ? 'selected':'' }}>{{ $worker->first_name . ' ' . $worker->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="activity">Avance: </label>
                                            <div class="input-group mb-3">
                                                <input type="number" min="0" step="0.1" data-progress class="form-control " value="{{ $activity->progress }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <label for="activity">Colaboradores:
                                                <button type="button" data-activityworker="{{ $activity->id }}" class="btn btn-xs btn-outline-success float-right" data-toggle="tooltip" data-placement="top" title="Guardar cambios" ><i class="fas fa-plus"></i>
                                                </button>
                                            </label>

                                            <div id="body-workers">
                                                @foreach( $activity->activity_workers as $collaborator )
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <select data-worker class="workers form-control form-control-sm select2" style="width: 100%;">
                                                                <option></option>
                                                                <option value="0">Ninguno</option>
                                                                @foreach( $workers as $worker )
                                                                    <option value="{{ $worker->id }}" {{ ($worker->id == $collaborator->worker_id) ? 'selected':'' }}>{{ $worker->first_name . ' ' . $worker->last_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label">H-H Plan: </label>
                                                                <div class="col-sm-7">
                                                                    <input type="number" min="0" step="0.1" data-hoursplan name="hours_plan" value="{{ $collaborator->hours_plan }}" class="form-control form-control-sm ">
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group row">
                                                                <label class="col-sm-5 col-form-label">H-H Real: </label>
                                                                <div class="col-sm-7">
                                                                    <input type="number" min="0" step="0.1" data-hoursreal name="hours_real" value="{{ $collaborator->hours_real }}" class="form-control form-control-sm ">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <div class="form-group row">
                                                                <button type="button" data-activityworkerdelete class="btn btn-sm btn-outline-danger btn-block" data-toggle="tooltip" data-placement="top" title="Quitar" ><i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="modalActivities" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Actividades Incompletas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body table-responsive" style="height: 300px;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Cotización</th>
                            <th>Etapa</th>
                            <th>Actividad</th>
                            <th>Progreso</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody id="table-lost-activities">

                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                </div>

            </div>
        </div>
    </div>

    <template id="template-worker">

        <div class="row">
            <div class="col-sm-5">
                <select data-worker class="workers form-control form-control-sm select2" style="width: 100%;">
                    <option></option>
                    <option value="0">Ninguno</option>
                    @foreach( $workers as $worker )
                        <option value="{{ $worker->id }}">{{ $worker->first_name . ' ' . $worker->last_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-3">
                <div class="form-group row">
                    <label class="col-sm-5 col-form-label">H-H Plan: </label>
                    <div class="col-sm-7">
                        <input type="number" min="0" step="0.1" data-hoursplan name="hours_plan" class="form-control form-control-sm ">
                    </div>
                </div>

            </div>
            <div class="col-sm-3">
                <div class="form-group row">
                    <label class="col-sm-5 col-form-label">H-H Real: </label>
                    <div class="col-sm-7">
                        <input type="number" min="0" step="0.1" data-hoursreal name="hours_real" class="form-control form-control-sm ">
                    </div>
                </div>
            </div>
            <div class="col-sm-1">
                <div class="form-group row">
                    <button type="button" data-activityworkerdelete class="btn btn-sm btn-outline-danger btn-block" data-toggle="tooltip" data-placement="top" title="Quitar" ><i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

    </template>

    <template id="template-activity">
        <div class="col-md-12">
            <div data-checkactivity class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"></h3>

                    <div class="card-tools">
                        <button type="button" data-activityedit class="btn btn-sm btn-outline-success" data-toggle="tooltip" data-placement="top" title="Guardar cambios" ><i class="fas fa-save"></i>
                        </button>
                        <button type="button" data-activitydelete class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" ><i class="fas fa-trash"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus text-success"></i></button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="quote">Cotización: </label>
                            <select data-quote_description class="quote_description form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                <option value="0">Ninguna</option>
                                @foreach( $quotes as $quote )
                                    <option value="{{ $quote->id }}" data-quote="{{ $quote->description_quote }}">{{ $quote->order_execution . '-' . $quote->description_quote}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-sm-4">
                            <label for="descriptionQuote">Descripción: </label>
                            <textarea name="" data-descriptionQuote cols="30" class="form-control form-control-sm"></textarea>

                        </div>
                        <div class="col-sm-4">
                            <label for="descriptionQuote">Etapa: </label>
                            <textarea name="" data-phase cols="30" class="form-control form-control-sm"></textarea>

                        </div>
                        <div class="col-sm-4">
                            <label for="activity">Actividad: </label>
                            <textarea name="" data-activity cols="30" class="form-control form-control-sm"></textarea>

                        </div>
                        <div class="col-sm-4">
                            <label for="activity">Responsable: </label>
                            <select data-performer class="performers form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                @foreach( $workers as $worker )
                                    <option value="{{ $worker->id }}">{{ $worker->first_name . ' ' . $worker->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="activity">Avance: </label>
                            <div class="input-group mb-3">
                                <input type="number" min="0" step="0.1" data-progress class="form-control ">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">

                            <label for="activity">Ejecutantes:
                                <button type="button" data-activityworker class="btn btn-xs btn-outline-success float-right" data-toggle="tooltip" data-placement="top" title="Agregar ejecutante" ><i class="fas fa-plus"></i>
                                </button>
                            </label>

                            <div id="body-workers">

                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </template>

    <template id="template-lostActivity">
        <tr>
            <td data-i></td>
            <td data-quote></td>
            <td data-phase></td>
            <td data-activity></td>
            <td data-progress></td>
            <td>
                <button type="button" data-activitylostid class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Agregar</button>
            </td>
        </tr>
    </template>
@endsection

@section('plugins')
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            $('#sandbox-container .input-daterange').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                language: "es",
                multidate: false,
                autoclose: true
            });

            //Initialize Select2 Elements
            $('.quote_description').select2({
                placeholder: "Selecione cotización",
            });

            $('.workers').select2({
                placeholder: "Selecione colaborador",
            });

            $('.responsables').select2({
                placeholder: "Selecione responsable",
            });

            $('.performers').select2({
                placeholder: "Selecione responsable",
            });

            $('.areas').select2({
                placeholder: "Selecione una área",
            });
        })
    </script>
    <script src="{{ asset('js/timeline/manage.js') }}"></script>

@endsection
