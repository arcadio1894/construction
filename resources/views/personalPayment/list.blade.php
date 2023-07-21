@extends('layouts.appAdmin2')

@section('openPersonalPayments')
    menu-open
@endsection

@section('activePersonalPayments')
    active
@endsection

@section('activeListPersonalPayments')
    active
@endsection

@section('title')
    Pago al personal
@endsection

@section('styles-plugins')
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
        .totales {
            background-color: #D9E1F2; /* Color de fondo para la primera fila */
        }
        .titleHeader {
            background-color: #4472C4;
            color: #ffffff;
        }
        .titleTotal {
            background-color: #FFC000;
            font-weight: bold;
        }
        .totalWorker {
            background-color: #FFF2CC;
        }
        .celdas {
            background-color: #D9D9D9;
        }
        .letraTabla {
            font-family: "Calibri", Arial, sans-serif; /* Utiliza Calibri si está instalado, de lo contrario, usa Arial o una fuente sans-serif similar */
            font-size: 13px; /* Tamaño de fuente 11 */
        }
        .letraTablaGrande {
            font-family: "Calibri", Arial, sans-serif; /* Utiliza Calibri si está instalado, de lo contrario, usa Arial o una fuente sans-serif similar */
            font-size: 16px; /* Tamaño de fuente 11 */
        }

    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Reporte de pagos al personal</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Pagos al Personal</h5>

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Pagos al personal</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> reporte</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="year">Seleccione un año <span class="right badge badge-danger">(*)</span></label>
                <select id="year" name="year" class="form-control select2" style="width: 100%;">
                    <option></option>
                    @foreach( $years as $year )
                        <option value="{{ $year->year }}" {{ ($year->year == $currentYear) ? 'selected':'' }}>{{ $year->year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3" id="sandbox-container">
            <label for="weekStart">Seleccione un mes <span class="right badge badge-danger">(*)</span></label>

            <select id="month" name="month" class="form-control select2" style="width: 100%;">
                <option></option>
                @foreach( $months as $month )
                    <option value="{{ $month->month}}" {{ ($month->month == $currentMonth) ? 'selected':'' }}>{{ $month->month_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="btn-outputs">&nbsp;</label><br>
            <button type="button" id="btn-pays" class="btn  btn-outline-success btn-block"> Buscar</button>
        </div>

    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-navy">
                <div class="card-header">
                    <h3 class="card-title" id="titleCard"><strong>PAGO DE PERSONAL</strong></h3>

                    <div class="card-tools">

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="total-pays-load">
                    <div class="table-responsive" id="tablaContainer" >

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-navy">
                <div class="card-header">
                    <h3 class="card-title" id="titleCard2"><strong>PROYECCIÓN PARA EL MES</strong></h3>

                    <div class="card-tools">

                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" >
                    <div class="table-responsive" id="tablaContainer2" >

                    </div>
                </div>
            </div>
        </div>

    </div>

    <template id="template-totalPays">
        <div class="col-lg-6">
            <div class="card card-orange border-warning">
        <div class="card-header border-0">
            <h3 class="card-title" data-semana>Semana</h3>

        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Trabajador</th>
                    <th>Monto</th>
                </tr>
                </thead>
                <tbody data-pays>
                {{--<tr>
                    <td data-codigo>Some Product</td>
                    <td data-trabajador>$13 USD</td>
                    <td data-monto>12,000 Sold</td>
                </tr>--}}
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </template>

    <template id="template-pays">
        <tr>
            <td data-codigo>Some Product</td>
            <td data-trabajador>$13 USD</td>
            <td data-monto>12,000 Sold</td>
        </tr>
    </template>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <!-- Vdialog -->
    <script src="{{ asset('admin/plugins/vdialog/js/lib/vdialog.js') }}"></script>

    <script src="{{asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            $('#year').select2({
                placeholder: "Seleccione año",
            });
            $('#month').select2({
                placeholder: "Seleccione mes",
            });

        })
    </script>
    <script src="{{ asset('js/personalPayment/list.js') }}"></script>
@endsection




