@extends('layouts.appAdmin2')

@section('openDiscountContribution')
    menu-open
@endsection

@section('activeDiscountContribution')
    active
@endsection

@section('openFifthCategory')
    menu-open
@endsection

@section('activeFifthCategory')
    active
@endsection

@section('title')
    Renta de Quinta Categoría
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-title')
    <h5 class="card-title">{{ $worker->first_name.' '.$worker->last_name }}</h5>

    <a href="{{ route('fifthCategory.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-alt-circle-left font-20"></i> Regresar a los trabajadores </a>

@endsection

@section('page-header')
    <h1 class="page-title">Registro de pagos</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('fifthCategory.index') }}"><i class="fa fa-archive"></i> Trabajadores en quinta categoría</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Pagos registrados</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="worker_id" value="{{ $worker->id }}">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Pagos de renta de quinta categoría</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        @can('edit_fifthCategory')
                        <button type="button" id="btn-new" data-worker_id="{{ $worker->id }}" data-worker_name="{{ $worker->first_name.' '.$worker->last_name }}" class="btn btn-success btn-sm float-left">Nuevo pago</button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm" id="fifthCategory-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Trabajador</th>
                                    <th>Fecha Pago</th>
                                    <th>Monto</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody id="body-gratifications">
                                @foreach( $fifthCategories as $fifthCategory )
                                    <tr>
                                        <td data-id>{{ $worker->id }}</td>
                                        <td data-worker>{{ $worker->first_name.' '.$worker->last_name }}</td>
                                        <td data-date>{{ $fifthCategory->date->format('d/m/Y') }}</td>
                                        <td data-amount>{{ $fifthCategory->amount }}</td>
                                        <td>
                                            @can('edit_fifthCategory')
                                            <button type="button" data-edit data-fifthCategory_id="{{ $fifthCategory->id }}" data-date="{{ $fifthCategory->date->format('d/m/Y') }}" data-amount="{{ $fifthCategory->amount }}" data-worker_id="{{ $fifthCategory->worker_id }}" data-worker="{{ $worker->first_name.' '.$worker->last_name }}" class="btn btn-outline-warning btn-sm"><i class="fas fa-pen"></i> </button>
                                            @endcan
                                            @can('destroy_fifthCategory')
                                            <button type="button" data-delete data-fifthCategory_id="{{ $fifthCategory->id }}" data-date="{{ $fifthCategory->date->format('d/m/Y') }}" data-amount="{{ $fifthCategory->amount }}" data-worker_id="{{ $fifthCategory->worker_id }}" data-worker="{{ $worker->first_name.' '.$worker->last_name }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i> </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>


    <div id="modalCreate" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registrar pago</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formCreate" data-url="{{ route('fifthCategory.worker.store') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="worker_id" name="worker_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="name_worker"> Trabajador <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="text" readonly id="name_worker" name="name_worker" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="amount"> Monto <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="number" id="amount" name="amount" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="date"> Fecha a pagar <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="text" id="date" name="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar pago</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" data-url="{{ route('fifthCategory.worker.update') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="fifthCategory_id" name="fifthCategory_id">
                        <input type="hidden" id="worker_id" name="worker_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="name_worker"> Trabajador <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="text" readonly id="name_worker" name="name_worker" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="amount"> Monto <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="number" id="amount" name="amount" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="date"> Fecha a pagar <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="text" id="dateEdit" name="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submitEdit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar pago</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('fifthCategory.worker.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="fifthCategory_id" name="fifthCategory_id">
                        <input type="hidden" id="worker_id" name="worker_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="name_worker"> Trabajador <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="text" readonly id="name_worker" name="name_worker" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="amount"> Monto <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="number" readonly id="amount" name="amount" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="date"> Fecha a pagar <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="text" readonly id="dateDelete" name="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-submitDelete" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

@endsection

@section('scripts')
    <script src="{{asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>
    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>

    <script>
        $(function () {

            $('#date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#dateEdit').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#dateDelete').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });

            $('#sandbox-container .input-daterange').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                language: "es",
                multidate: false,
                autoclose: true,
                todayHighlight: true,
                defaultViewDate: moment().format('L')
            });

            $('#fifthCategory-table').DataTable( {
                bAutoWidth: false,
                "aaSorting": [],

                select: {
                    style: 'single'
                },
                language: {
                    "processing": "Procesando...",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron resultados",
                    "emptyTable": "Ningún dato disponible en esta tabla",
                    "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "search": "Buscar:",
                    "infoThousands": ",",
                    "loadingRecords": "Cargando...",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    "buttons": {
                        "copy": "Copiar",
                        "colvis": "Visibilidad",
                        "collection": "Colección",
                        "colvisRestore": "Restaurar visibilidad",
                        "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                        "copySuccess": {
                            "1": "Copiada 1 fila al portapapeles",
                            "_": "Copiadas %d fila al portapapeles"
                        },
                        "copyTitle": "Copiar al portapapeles",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Mostrar todas las filas",
                            "1": "Mostrar 1 fila",
                            "_": "Mostrar %d filas"
                        },
                        "pdf": "PDF",
                        "print": "Imprimir"
                    },
                    "autoFill": {
                        "cancel": "Cancelar",
                        "fill": "Rellene todas las celdas con <i>%d<\/i>",
                        "fillHorizontal": "Rellenar celdas horizontalmente",
                        "fillVertical": "Rellenar celdas verticalmentemente"
                    },
                    "decimal": ",",
                    "searchBuilder": {
                        "add": "Añadir condición",
                        "button": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "clearAll": "Borrar todo",
                        "condition": "Condición",
                        "conditions": {
                            "date": {
                                "after": "Despues",
                                "before": "Antes",
                                "between": "Entre",
                                "empty": "Vacío",
                                "equals": "Igual a",
                                "not": "No",
                                "notBetween": "No entre",
                                "notEmpty": "No Vacio"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vacio",
                                "equals": "Igual a",
                                "gt": "Mayor a",
                                "gte": "Mayor o igual a",
                                "lt": "Menor que",
                                "lte": "Menor o igual que",
                                "not": "No",
                                "notBetween": "No entre",
                                "notEmpty": "No vacío"
                            },
                            "string": {
                                "contains": "Contiene",
                                "empty": "Vacío",
                                "endsWith": "Termina en",
                                "equals": "Igual a",
                                "not": "No",
                                "notEmpty": "No Vacio",
                                "startsWith": "Empieza con"
                            }
                        },
                        "data": "Data",
                        "deleteTitle": "Eliminar regla de filtrado",
                        "leftTitle": "Criterios anulados",
                        "logicAnd": "Y",
                        "logicOr": "O",
                        "rightTitle": "Criterios de sangría",
                        "title": {
                            "0": "Constructor de búsqueda",
                            "_": "Constructor de búsqueda (%d)"
                        },
                        "value": "Valor"
                    },
                    "searchPanes": {
                        "clearMessage": "Borrar todo",
                        "collapse": {
                            "0": "Paneles de búsqueda",
                            "_": "Paneles de búsqueda (%d)"
                        },
                        "count": "{total}",
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Sin paneles de búsqueda",
                        "loadMessage": "Cargando paneles de búsqueda",
                        "title": "Filtros Activos - %d"
                    },
                    "select": {
                        "1": "%d fila seleccionada",
                        "_": "%d filas seleccionadas",
                        "cells": {
                            "1": "1 celda seleccionada",
                            "_": "$d celdas seleccionadas"
                        },
                        "columns": {
                            "1": "1 columna seleccionada",
                            "_": "%d columnas seleccionadas"
                        }
                    },
                    "thousands": ".",
                    "datetime": {
                        "previous": "Anterior",
                        "next": "Proximo",
                        "hours": "Horas"
                    }
                }

            } );

        })
    </script>
    <script src="{{ asset('js/fifthCategory/create.js') }}"></script>
@endsection