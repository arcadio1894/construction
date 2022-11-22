@extends('layouts.appAdmin2')

@section('openWorker')
    menu-open
@endsection

@section('activeWorker')
    active
@endsection

@section('activeListWorker')
    active
@endsection

@section('title')
    Colaboradores
@endsection

@section('styles-plugins')
    <!-- Datatables -->
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
    <h5 class="card-title">Listado de los colaboradores</h5>

    <a href="{{ route('worker.create') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo colaborador </a>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('worker.index') }}"><i class="fa fa-archive"></i> Colaboradores</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div>
        <div class="row">
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="0" class="custom-control-input" id="customSwitch1">
                <label class="custom-control-label" for="customSwitch1">Código</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="1" class="custom-control-input" id="customSwitch2">
                <label class="custom-control-label" for="customSwitch2">DNI</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="2" class="custom-control-input" id="customSwitch3">
                <label class="custom-control-label" for="customSwitch3">Nombres</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="3" class="custom-control-input" id="customSwitch4">
                <label class="custom-control-label" for="customSwitch4">Apellidos</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="4" class="custom-control-input" id="customSwitch5">
                <label class="custom-control-label" for="customSwitch5">Dirección</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="5" class="custom-control-input" id="customSwitch6">
                <label class="custom-control-label" for="customSwitch6">Teléfono</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="6" class="custom-control-input" id="customSwitch7">
                <label class="custom-control-label" for="customSwitch7">Email</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" checked data-column="7" class="custom-control-input" id="customSwitch8">
                <label class="custom-control-label" for="customSwitch8">Cargo</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="8" class="custom-control-input" id="customSwitch9">
                <label class="custom-control-label" for="customSwitch9">Género</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="9" class="custom-control-input" id="customSwitch10">
                <label class="custom-control-label" for="customSwitch10">Fecha Nac.</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="10" class="custom-control-input" id="customSwitch11">
                <label class="custom-control-label" for="customSwitch11">Edad</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="11" class="custom-control-input" id="customSwitch12">
                <label class="custom-control-label" for="customSwitch12">Nivel Estudios</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="12" class="custom-control-input" id="customSwitch13">
                <label class="custom-control-label" for="customSwitch13">N° de Hijos</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="13" class="custom-control-input" id="customSwitch14">
                <label class="custom-control-label" for="customSwitch14">Fecha Ingreso</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="14" class="custom-control-input" id="customSwitch15">
                <label class="custom-control-label" for="customSwitch15">Fecha Cese</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="15" class="custom-control-input" id="customSwitch16">
                <label class="custom-control-label" for="customSwitch16">Salario Diario</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="16" class="custom-control-input" id="customSwitch17">
                <label class="custom-control-label" for="customSwitch17">Salario Mensual</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="17" class="custom-control-input" id="customSwitch18">
                <label class="custom-control-label" for="customSwitch18">Pension Alimentos</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="18" class="custom-control-input" id="customSwitch19">
                <label class="custom-control-label" for="customSwitch19">ESSALUD</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="19" class="custom-control-input" id="customSwitch20">
                <label class="custom-control-label" for="customSwitch20">Asignación Familiar</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="20" class="custom-control-input" id="customSwitch21">
                <label class="custom-control-label" for="customSwitch21">Quinta Categoría</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="21" class="custom-control-input" id="customSwitch22">
                <label class="custom-control-label" for="customSwitch22">Contrato</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="22" class="custom-control-input" id="customSwitch23">
                <label class="custom-control-label" for="customSwitch23">Estado Civil</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="23" class="custom-control-input" id="customSwitch24">
                <label class="custom-control-label" for="customSwitch24">Sistema Pensión</label>
            </div>
            <div class="col-md-2 custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" data-column="24" class="custom-control-input" id="customSwitch25">
                <label class="custom-control-label" for="customSwitch25">Observación</label>
            </div>
        </div>

    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm" id="dynamic-table">
            <thead>
            <tr>
                <th>Código</th>
                <th>DNI</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Género</th>
                <th>Fecha Nac.</th>
                <th>Edad</th>
                <th>Nivel Estudios</th>
                <th>N° de Hijos</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Cese</th>
                <th>Salario Diario</th>
                <th>Salario Mensual</th>
                <th>Pension Alimentos</th>
                <th>ESSALUD</th>
                <th>Asignación Familiar</th>
                <th>Quinta Categoría</th>
                <th>Contrato</th>
                <th>Estado Civil</th>
                <th>Sistema Pensión</th>
                <th>Observación</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    {{--@can('destroy_categoryInvoice')--}}
    {{--<div id="modalDelete" class="modal fade" tabindex="-1">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<h4 class="modal-title">Confirmar eliminación</h4>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                {{--</div>--}}
                {{--<form id="formDelete" data-url="{{ route('categoryInvoice.destroy') }}">--}}
                    {{--@csrf--}}
                    {{--<div class="modal-body">--}}
                        {{--<input type="hidden" id="category_id" name="category_id">--}}
                        {{--<p id="name"></p>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>--}}
                        {{--<button type="submit" class="btn btn-danger">Eliminar</button>--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--@endcan--}}

@endsection

@section('plugins')
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('js/worker/index.js') }}"></script>
@endsection