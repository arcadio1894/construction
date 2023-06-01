@extends('layouts.appAdmin2')

@section('openDiscountContribution')
    menu-open
@endsection

@section('activeDiscountContribution')
    active
@endsection

@section('openGratifications')
    menu-open
@endsection

@section('activeListGratification')
    active
@endsection

@section('title')
    Gratificaciones
@endsection

@section('styles-plugins')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
        .liga {
            cursor: pointer;
        }
    </style>
@endsection

@section('page-title')
    <h5 class="card-title">Listado</h5>
    @can('create_gratification')
    <button type="button" id="btn-newPeriod" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo Periodo </button>
    <button type="button" id="btn-refresh" class="btn btn-outline-warning btn-sm float-right ml-2 mr-2" > <i class="fas fa-sync-alt font-20"></i> Refrescar  </button>
    @endcan
@endsection

@section('page-header')
    <h1 class="page-title">Periodos de Gratificaciones</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('gratification.index') }}"><i class="fa fa-archive"></i> Periodos de Gratificaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <div class="row" id="body-periods">

    </div>

    <template id="template-period">
        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h4><strong data-description>{{--{{ $period->description }}--}}</strong></h4>
                    <span class="info-box-text" data-registered>{{--Registrados: {{ $period->gratifications->count() }}--}}</span>
                    <div class="progress progress-xs">
                        <div data-percentage class="progress-bar bg-danger progress-bar-danger progress-bar-striped" role="progressbar"
                             aria-valuenow="{{--{{ $period->gratifications->count()/$numWorkers }}--}}" aria-valuemin="0" aria-valuemax="100" style="">
                            <span class="sr-only"></span>
                        </div>
                    </div>
                    <span class="info-box-text" data-workers>{{--Num. Trabajadores: {{ $numWorkers }}--}}</span>

                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a data-link href="#" class="bg-gradient-success small-box-footer">
                    Registrar <i class="fas fa-external-link-alt"></i>
                </a>
                <a data-edit data-description data-month data-year class="bg-gradient-orange small-box-footer liga">
                    Editar <i class="fas fa-external-link-alt"></i>
                </a>
                <a data-delete data-description class="bg-gradient-danger small-box-footer liga">
                    Eliminar <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
    </template>

    <div id="modalCreate" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registrar periodo de gratificación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formCreate" data-url="{{ route('gratification.period.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="month"> Mes <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <select id="month" name="month" class="form-control select2" style="width: 100%;">
                                            <option></option>
                                            <option value="7">JULIO</option>
                                            <option value="12">DICIEMBRE</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="year"> Año <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="text" id="year" name="year" class="form-control" required />
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar periodo <strong id="description_edit"></strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEdit" data-url="{{ route('gratification.period.update') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="period_id_edit" name="period_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="month"> Mes <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <select id="month_edit" name="month" class="form-control select2" style="width: 100%;">
                                            <option></option>
                                            <option value="7">JULIO</option>
                                            <option value="12">DICIEMBRE</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label" for="year"> Año <span class="right badge badge-danger">(*)</span></label>

                                    <div class="col-sm-12">
                                        <input type="text" id="year_edit" name="year" class="form-control" required />
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar eliminación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formDelete" data-url="{{ route('gratification.period.destroy') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="period_id_delete" name="period_id">
                        <strong> ¿Desea eliminar este periodo? </strong>
                        <p id="descriptionDelete"></p>
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
    <script>
        $(function () {

            $('#month').select2({
                placeholder: "Selecione un mes",
            });

            $('#month_edit').select2({
                placeholder: "Selecione un mes",
            });


        })
    </script>
    <script src="{{ asset('js/gratification/index.js') }}"></script>
@endsection