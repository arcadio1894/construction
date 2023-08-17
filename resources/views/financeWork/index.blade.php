@extends('layouts.appAdmin2')

@section('openFinanceWorks')
    menu-open
@endsection

@section('activeFinanceWorks')
    active
@endsection

@section('activeListFinanceWorks')
    active
@endsection

@section('title')
    Trabajos Finanzas
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
        .letraTabla {
            font-family: "Calibri", Arial, sans-serif; /* Utiliza Calibri si está instalado, de lo contrario, usa Arial o una fuente sans-serif similar */
            font-size: 14px; /* Tamaño de fuente 11 */
        }
        .normal-title {
            background-color: #203764; /* Color deseado para el fondo */
            color: #fff; /* Color deseado para el texto */
            text-align: center;
        }
        .cliente-title {
            background-color: #9BC2E6; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .trabajo-title {
            background-color: #5B9BD5; /* Color deseado para el fondo */
            color: #000; /* Color deseado para el texto */
            text-align: center;
        }
        .facturacion-title {
            background-color: #305496; /* Color deseado para el fondo */
            color: #fff; /* Color deseado para el texto */
            text-align: center;
        }
    </style>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de trabajos para Finanzas</h5>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('finance.works.index') }}"><i class="fa fa-archive"></i> Trabajos Finanzas</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    {{--<div class="row">
        <div class="col-sm-3">
            <label for="filterYear">Filtrar por Año:</label>
            <select id="filterYear" class="form-control select2" style="width: 100%;">
                <option value="">TODOS</option>
                @for ($i=0; $i<count($arrayYears); $i++)
                    <option value="{{ $arrayYears[$i] }}">{{ $arrayYears[$i] }}</option>
                @endfor
            </select>
        </div>

        <div class="col-sm-3">
            <label for="filterCustomer">Filtrar por Cliente:</label>
            <select id="filterCustomer" class="form-control select2" style="width: 100%;">
                <option value="">TODOS</option>
                @for ($i=0; $i<count($arrayCustomers); $i++)
                    <option value="{{ $arrayCustomers[$i]['business_name'] }}">{{ $arrayCustomers[$i]['business_name'] }}</option>
                @endfor
            </select>
        </div>

        <div class="col-sm-3">
            <label for="filterStateWork">Filtrar por Avance de trabajo:</label>
            <select id="filterStateWork" class="form-control select2" style="width: 100%;">
                <option value="">TODOS</option>
                @for ($i=0; $i<count($arrayStateWorks); $i++)
                    <option value="{{ $arrayStateWorks[$i] }}">{{ $arrayStateWorks[$i] }}</option>
                @endfor
            </select>
        </div>

        <div class="col-sm-3">
            <label for="filterState">Filtrar por Estado:</label>
            <select id="filterState" class="form-control select2" style="width: 100%;">
                <option value="">TODOS</option>
                @for ($i=0; $i<count($arrayStates); $i++)
                    <option value="{{ $arrayStates[$i] }}">{{ $arrayStates[$i] }}</option>
                @endfor
            </select>
        </div>
    </div>

    <hr>--}}

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm letraTabla" style="" id="dynamic-table">
            <thead>
            <tr>
                <th colspan="1" class="normal-title"></th>
                <th colspan="2" class="cliente-title">INFORMACIÓN DEL CLIENTE</th>
                <th colspan="8" class="trabajo-title">INFORMACIÓN DEL TRABAJO</th>
                <th colspan="19" class="facturacion-title">FACTURACIÓN</th>
                <th colspan="2" class="normal-title"></th>
            </tr>
            <tr class="normal-title">
                <th>Año</th>
                <th>Cliente</th>
                <th>Responsable</th>
                <th>Inicio</th>
                <th>Entrega</th>
                <th>N° Cotización</th>
                <th>N° O.S.</th>
                <th>Descripción</th>
                <th>Estado del Trabajo</th>
                <th>Acta Aceptacion</th>
                <th>Estado Acta Aceptacion</th>
                <th>Condición de Pago</th>
                <th>Adelanto</th>
                <th>Monto Adelanto</th>
                <th>Subtotal</th>
                <th>I.G.V</th>
                <th>Precio Total</th>
                <th>Detracción</th>
                <th>Monto detracción</th>
                <th>Monto A Abonar</th>
                <th>Facturado</th>
                <th>N° Factura</th>
                <th>Mes Fact.</th>
                <th>Fecha Emisión</th>
                <th>Fecha Ingreso</th>
                <th>Días</th>
                <th>Fecha Programado</th>
                <th>Banco</th>
                <th>Estado</th>
                <th>Fecha Pago</th>
                <th>Observación</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div id="modalEditTrabajo" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar información del trabajo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEditTrabajo" data-url="{{ route('finance.work.edit.trabajo') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="financeWork_id" name="financeWork_id">

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="customer_id">Cliente:</label>
                                <select id="customer_id" name="customer_id" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    @for ($i=0; $i<count($arrayCustomers); $i++)
                                        <option value="{{ $arrayCustomers[$i]['id'] }}">{{ $arrayCustomers[$i]['business_name'] }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="contact_id">Contacto:</label>
                                <select id="contact_id" name="contact_id" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="date_initiation">Fecha Inicio:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_initiation" name="date_initiation" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="date_delivery">Fecha Entrega:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_delivery" name="date_delivery" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="detraction">Detracción:</label>
                                <select id="detraction" name="detraction" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">Ninguno</option>
                                    <option value="oc">Orden de Compra</option>
                                    <option value="os">Orden de Servicio</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="state_work">Estado del Trabajo:</label>
                                <select id="state_work" name="state_work" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">NINGUNO</option>
                                    <option value="to_start">POR INICIAR</option>
                                    <option value="in_progress">EN PROCESO</option>
                                    <option value="finished">TERMINADO</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="act_of_acceptance">Acta de Aceptación:</label>
                                <select id="act_of_acceptance" name="act_of_acceptance" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">Ninguno</option>
                                    <option value="pending">Pendiente</option>
                                    <option value="generate">Generada</option>
                                    <option value="not_generate">No generada</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="state_act_of_acceptance">Estado Acta Aceptación:</label>
                                <select id="state_act_of_acceptance" name="state_act_of_acceptance" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="nn">Ninguno</option>
                                    <option value="pending_signature">Pendiente de Firma</option>
                                    <option value="signed">Firmada</option>
                                    <option value="not_signed">No se firmará</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnSubmitFormEditTrabajo" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEditFacturacion" class="modal fade" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar información de facturación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="formEditFacturacion" data-url="{{ route('finance.work.edit.facturacion') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="financeWork_id" name="financeWork_id">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="advancement">Adelanto:</label>
                                <select id="advancement" name="advancement" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="y">SI</option>
                                    <option value="n">NO</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <label for="amount_advancement">Monto de Adelanto:</label>
                                <input type="number" name="amount_advancement" id="amount_advancement" step="0.01" min="0" class="form-control" >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label for="invoiced">Facturado:</label>
                                <select id="invoiced" name="invoiced" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="y">SI</option>
                                    <option value="n">NO</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="number_invoice">N° de Factura:</label>
                                <input type="text" name="number_invoice" class="form-control" id="number_invoice">
                            </div>
                            <div class="col-sm-6">
                                <label for="month_invoice">Mes Facturación:</label>
                                <select id="month_invoice" name="month_invoice" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="date_issue">Fecha de Emisión</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_issue" name="date_issue" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="date_admission">Fecha de Ingreso</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_admission" name="date_admission" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="bank_id">Banco:</label>
                                <select id="bank_id" name="bank_id" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach( $banks as $bank )
                                    <option value="{{ $bank->id }}">{{ $bank->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="state">Estado:</label>
                                <select id="state" name="state" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    <option value="pending">PENDIENTE</option>
                                    <option value="canceled">CANCELADO</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="date_paid">Fecha de Pago</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_paid" name="date_paid" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="observation">Observación:</label>
                                <textarea name="observation" id="observation" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnSubmitFormEditFacturacion" class="btn btn-success">Guardar</button>
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
    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            $('#filterYear').select2({});
            $('#filterCustomer').select2({});
            $('#filterStateWork').select2({});
            $('#filterState').select2({});
            $('#act_of_acceptance').select2({
                placeholder: "Seleccione"
            });
            $('#state_act_of_acceptance').select2({
                placeholder: "Seleccione"
            });
            $('#date_issue').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#date_admission').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#date_paid').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#date_initiation').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#date_delivery').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#advancement').select2({
                placeholder: "Seleccione"
            });
            $('#invoiced').select2({
                placeholder: "Seleccione"
            });
            $('#bank_id').select2({
                placeholder: "Seleccione"
            });
            $('#state').select2({
                placeholder: "Seleccione"
            });
            $('#month_invoice').select2({
                placeholder: "Seleccione"
            });
            $('#detraction').select2({
                placeholder: "Seleccione"
            });
            $('#customer_id').select2({
                placeholder: "Seleccione"
            });
            $('#contact_id').select2({
                placeholder: "Seleccione"
            });
            $('#state_work').select2({
                placeholder: "Seleccione"
            });

        })
    </script>
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('js/financeWork/index.js') }}"></script>

@endsection