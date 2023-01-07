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
    Revisar Avance Cronograma
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

    <!-- VDialog -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/vdialog/css/vdialog.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
        .card-header {
            padding: 0.65rem 1rem !important;
        }
        .menu .accordion-heading {  position: relative; }
        .menu .accordion-heading .edit {
            position: absolute;
            top: 5px;
            right: 5px;
        }
        .menu .area { border-left: 4px solid #f38787; }
        .menu .equipamento { border-left: 4px solid #65c465; }
        .menu .ponto { border-left: 4px solid #98b3fa; }
        .menu .collapse.in { overflow: visible; }


        .accordion{margin-bottom:20px;}
        .accordion-group{margin-bottom:2px;border:1px solid #e5e5e5;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}
        .accordion-heading{border-bottom:0;}
        .accordion-heading .accordion-toggle{display:block;padding:8px 15px;}
        .accordion-toggle{cursor:pointer;}
        .accordion-inner{padding:9px 15px;border-top:1px solid #e5e5e5;}

        .class-edit {
            border-left: 4px solid #0a0f17;
            border-bottom: 4px solid #0a0f17;
            border-top: 4px solid #0a0f17;
            border-right: 4px solid #0a0f17;
        }

    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Programación de actividades para el día {{ $timeline->date->format('d/m/Y') }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Revisar Avance Cronograma</h5>
    {{--<button type="button" id="newWork" class="btn btn-outline-success btn-sm float-right" > <i class="fas fa-tools"></i> Agregar Trabajo </button>
--}}
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

    <div class="row" id="body-works">

        @foreach( $timeline->works as $work )
            <div class="col-md-12">
                <div class="menu">
                    <div class="accordion">
                        <!-- Works -->
                        <div class="accordion-group">
                            <!-- Work -->
                            <div class="accordion-heading area">
                                <a class="accordion-toggle" data-idwork="{{ $work->id }}" data-quoteid="{{ $work->quote_id }}" data-description="{{ $work->description_quote }}" data-toggle="collapse" href="#work{{$work->id}}">{{ ($work->description_quote == null || $work->description_quote == '') ? 'Trabajo #':$work->description_quote }} {{' | '}} {{ ($work->supervisor_id == null) ? '' : ($work->supervisor->first_name.' '.$work->supervisor->last_name) }}</a>
                                {{--<div class="dropdown dropleft edit">
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-addphasework="{{$work->id}}"><i class="far fa-plus-square text-success"></i> Agregar Etapa</a>
                                        <a class="dropdown-item" href="#" data-editwork="{{$work->id}}"><i class="far fa-edit text-orange"></i> Editar Trabajo</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" data-deletework="{{$work->id}}"><i class="far fa-window-close text-danger"></i> Eliminar Trabajo</a>
                                    </div>
                                </div>--}}
                            </div>
                            <!-- /Work -->

                            <div class="accordion-body collapse" data-idaccordion id="work{{$work->id}}">

                                <div class="accordion-inner">
                                    @foreach( $work->phases as $phase )
                                        <div class="accordion" data-phaseid="{{ $phase->id }}" >

                                            <div class="accordion-group">
                                                <div class="accordion-heading equipamento">
                                                    <a class="accordion-toggle" data-idphase="{{ $phase->id }}" data-phase="{{ $phase->id }}" data-workid="{{ $phase->work_id }}" data-description="{{ $phase->description }}" data-toggle="collapse" href="#phase{{$phase->id}}">{{ ($phase->description == null || $phase->description == '') ? 'Etapa #':$phase->description }}</a>
                                                    {{--<div class="dropdown dropleft edit">
                                                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#" data-addtaskphase="{{ $phase->id }}"><i class="far fa-plus-square text-success"></i> Agregar Tarea</a>
                                                            <a class="dropdown-item" href="#" data-editphase="{{ $phase->id }}" data-editdescriptionphase="{{ $phase->description }}"><i class="far fa-edit text-orange"></i> Editar Etapa</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="#" data-deletephase="{{ $phase->id }}"><i class="far fa-window-close text-danger"></i> Eliminar Etapa</a>

                                                        </div>
                                                    </div>--}}
                                                </div>

                                                <div class="accordion-body collapse" data-idaccordion id="phase{{$phase->id}}">
                                                    <div class="accordion-inner">
                                                        @foreach( $phase->tasks as $task )
                                                            <div class="accordion" data-taskid="{{$task->id}}">
                                                                <div class="accordion-group">
                                                                    <div class="accordion-heading ponto">
                                                                        <a class="accordion-toggle" data-task="{{ $task->id }}" data-toggle="collapse" href="#task{{$task->id}}">{{ ($task->activity == null || $task->activity == '') ? 'Tarea #':$task->activity }}</a>
                                                                        <div class="dropdown dropleft edit">
                                                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="#" data-savetask="{{ $task->id }}" ><i class="far fa-edit text-orange"></i> Guardar Avance</a>
                                                                                {{--<div class="dropdown-divider"></div>--}}
                                                                                {{--<a class="dropdown-item" href="#" data-deletetask="{{ $task->id }}"><i class="far fa-window-close text-danger"></i> Eliminar Tarea</a>--}}
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="accordion-body collapse" data-idaccordion id="task{{$task->id}}">
                                                                        <div class="accordion-inner">
                                                                            <div class="row">
                                                                                <div class="col-sm-4">
                                                                                    <label for="activity">Actividad: </label>
                                                                                    <textarea name="" readonly data-activity cols="30" class="form-control form-control-sm">{{$task->activity}}</textarea>

                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <label for="activity">Responsable: </label>
                                                                                    {{--<input type="text" class="form-control form-control-sm" value="{{ ( $task->performer_id == null ) ? 'Ninguno' : $task->performer->first_name . ' ' . $task->performer->last_name}}" readonly>--}}
                                                                                    <select data-performer disabled class="performers form-control form-control-sm select2" style="width: 100%;">
                                                                                        <option></option>
                                                                                        @foreach( $workers as $worker )
                                                                                            <option value="{{ $worker->id }}" {{ ($worker->id == $task->performer_id) ? 'selected':'' }}>{{ $worker->first_name . ' ' . $worker->last_name}}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <label for="activity">Avance: </label>
                                                                                    <div class="input-group input-group-sm mb-3">
                                                                                        <input type="number" readonly min="0" step="0.1" value="{{ $task->progress }}" data-progress class="form-control form-control-sm">
                                                                                        <div class="input-group-append">
                                                                                            <span class="input-group-text">%</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-12">

                                                                                    <label for="activity">Ejecutantes:
                                                                                        {{--<button type="button" data-taskworker class="btn btn-xs btn-outline-success float-right" data-toggle="tooltip" data-placement="top" title="Agregar ejecutante" ><i class="fas fa-plus"></i>
                                                                                        </button>--}}
                                                                                    </label>

                                                                                    <div id="body-workers">
                                                                                        @foreach( $task->task_workers as $task_worker )
                                                                                            <div class="row">
                                                                                                <div class="col-sm-4">
                                                                                                    <select data-worker disabled class="workers form-control form-control-sm select2" style="width: 100%;">
                                                                                                        <option></option>
                                                                                                        <option value="0">Ninguno</option>
                                                                                                        @foreach( $workers as $worker )
                                                                                                            <option value="{{ $worker->id }}" {{ ($worker->id == $task_worker->worker_id) ? 'selected':'' }}>{{ $worker->first_name . ' ' . $worker->last_name}}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>

                                                                                                <div class="col-sm-2">
                                                                                                    <div class="form-group row">
                                                                                                        <label class="col-sm-7 col-form-label">H-H Plan: </label>
                                                                                                        <div class="col-sm-5">
                                                                                                            <input type="number" readonly min="0" step="0.1" value="{{ $task_worker->hours_plan }}" data-hoursplan name="hours_plan" class="form-control form-control-sm ">
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="col-sm-2">
                                                                                                    <div class="form-group row">
                                                                                                        <label class="col-sm-7 col-form-label">H-H Real: </label>
                                                                                                        <div class="col-sm-5">
                                                                                                            <input type="number" min="0" step="0.1" value="{{ $task_worker->hours_real }}" data-hoursreal name="hours_real" class="form-control form-control-sm ">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-sm-2">
                                                                                                    <div class="form-group row">
                                                                                                        <label class="col-sm-7 col-form-label">Cant. Plan: </label>
                                                                                                        <div class="col-sm-5">
                                                                                                            <input type="number" readonly min="0" step="0.1" value="{{ $task_worker->quantity_plan }}" data-quantityplan name="quantity_plan" class="form-control form-control-sm ">
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="col-sm-2">
                                                                                                    <div class="form-group row">
                                                                                                        <label class="col-sm-7 col-form-label">Cant. Real: </label>
                                                                                                        <div class="col-sm-5">
                                                                                                            <input type="number" min="0" step="0.1" value="{{ $task_worker->quantity_real }}" data-quantityreal name="quantity_real" class="form-control form-control-sm ">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>

                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

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

    <div id="modalQuotes" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cotizaciones Elevadas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <input type="hidden" id="work_id">
                <div class="modal-body table-responsive">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="quote">Cotización: </label>
                            <select data-quote_description id="quote_description" class="quote_description form-control form-control-sm select2" style="width: 100%;">
                                <option></option>
                                <option value="0">Ninguna</option>
                                @foreach( $quotes as $quote )
                                    <option value="{{ $quote->id }}" data-quote="{{  $quote->order_execution . '-' . $quote->description_quote }}">{{ $quote->order_execution . '-' . $quote->description_quote}}</option>
                                @endforeach
                            </select>


                        </div>
                        <div class="col-sm-6">
                            <label for="descriptionQuote">Descripción: </label>
                            <textarea name="" id="descriptionQuote" data-descriptionQuote cols="30" class="form-control form-control-sm"></textarea>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-quote" class="btn btn-primary">Guardar cotización</button>

                </div>
            </div>
        </div>
    </div>

    <div id="modalPhases" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificación de Etapa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <input type="hidden" id="phase_id">
                <div class="modal-body table-responsive">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="descriptionPhase">Etapa: </label>
                            <textarea name="" id="descriptionPhase" data-descriptionPhase cols="30" class="form-control form-control-sm"></textarea>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-savePhase" class="btn btn-primary">Guardar Etapa</button>

                </div>
            </div>
        </div>
    </div>

    <template id="template-work">
        <div class="col-md-12">
            <div class="menu">
                <div class="accordion">
                    <!-- Works -->
                    <div class="accordion-group">
                        <!-- Work -->
                        <div class="accordion-heading area">
                            <a class="accordion-toggle" data-idwork data-quoteid data-description data-toggle="collapse" href="#area2">Trabajo #</a>
                            <div class="dropdown dropleft edit">
                                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-addphasework><i class="far fa-plus-square text-success"></i> Agregar Etapa</a>
                                    <a class="dropdown-item" href="#" data-editwork><i class="far fa-edit text-orange"></i> Editar Trabajo</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-deletework><i class="far fa-window-close text-danger"></i> Eliminar Trabajo</a>
                                </div>
                            </div>
                        </div>
                        <!-- /Work -->

                        <div class="accordion-body collapse" data-idaccordion id="area2">

                            <div class="accordion-inner">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="template-phase">
        <div class="accordion" data-phaseid >

            <div class="accordion-group">
                <div class="accordion-heading equipamento">
                    <a class="accordion-toggle" data-idphase data-phase data-workid data-description data-toggle="collapse" href="#ponto2-1">Etapa #</a>
                    <div class="dropdown dropleft edit">
                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-edit"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" data-addtaskphase=""><i class="far fa-plus-square text-success"></i> Agregar Tarea</a>
                            <a class="dropdown-item" href="#" data-editphase=""><i class="far fa-edit text-orange"></i> Editar Etapa</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-deletephase=""><i class="far fa-window-close text-danger"></i> Eliminar Etapa</a>

                        </div>
                    </div>
                </div>

                <div class="accordion-body collapse" data-idaccordion id="ponto2-1">
                    <div class="accordion-inner">


                    </div>
                </div>
            </div>

        </div>
    </template>

    <template id="template-task">
        <div class="accordion" data-taskid>
            <div class="accordion-group">
                <div class="accordion-heading ponto">
                    <a class="accordion-toggle" data-task data-toggle="collapse" href="#servico11">Tarea #</a>
                    <div class="dropdown dropleft edit">
                        <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-edit"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" data-savetask="" ><i class="far fa-edit text-orange"></i> Guardar Tarea</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-deletetask=""><i class="far fa-window-close text-danger"></i> Eliminar Tarea</a>

                        </div>
                    </div>
                </div>

                <div class="accordion-body collapse" data-idaccordion id="servico11">
                    <div class="accordion-inner">
                        <div class="row">
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
                                <div class="input-group mb-3 input-group-sm">
                                    <input type="number" min="0" step="0.1" readonly data-progress class="form-control ">
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
                </div>
            </div>
        </div>
    </template>

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
                    <button type="button" data-taskworkerdelete class="btn btn-sm btn-outline-danger btn-block" data-toggle="tooltip" data-placement="top" title="Quitar" ><i class="fas fa-trash"></i>
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
                                {{--<button type="button" data-activityworker class="btn btn-xs btn-outline-success float-right" data-toggle="tooltip" data-placement="top" title="Agregar ejecutante" ><i class="fas fa-plus"></i>
                                </button>--}}
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

    <!-- Vdialog -->
    <script src="{{ asset('admin/plugins/vdialog/js/lib/vdialog.js') }}"></script>

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
    <script src="{{ asset('js/timeline/saveProgress.js') }}"></script>

@endsection
