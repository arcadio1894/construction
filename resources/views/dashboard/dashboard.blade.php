@extends('layouts.appAdmin2')

@section('title')
    Dashboard
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">

@endsection

@section('page-header')
    <h1 class="page-title">Dashboard</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
    </ol>
@endsection

@section('page-title')
    <h5 class="card-title">PANEL PRINCIPAL</h5>
@endsection

@section('content')
    <div class="row">
        @can('list_customer')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $customerCount }}</h3>

                    <p>Clientes</p>
                </div>
                <div class="icon">
                    <i class="ion ion-briefcase"></i>
                </div>
                <a href="{{ route('customer.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_contactName')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $contactNameCount }}</h3>

                    <p>Contactos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-clipboard"></i>
                </div>
                <a href="{{ route('contactName.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_supplier')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $supplierCount }}</h3>

                    <p>Proveedores</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-home-outline"></i>
                </div>
                <a href="{{ route('supplier.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_material')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $materialCount }}</h3>

                    <p>Materiales</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-box"></i>
                </div>
                <a href="{{ route('material.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_entryPurchase')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $entriesCount }}</h3>

                    <p>Entradas a almacén</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-cart"></i>
                </div>
                <a href="{{ route('entry.purchase.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_invoice')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-fuchsia">
                <div class="inner">
                    <h3>{{ $invoiceCount }}</h3>

                    <p>Facturas</p>
                </div>
                <div class="icon">
                    <i class="ion ion-card"></i>
                </div>
                <a href="{{ route('invoice.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_request')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $outputCount }}</h3>

                    <p>Salidas de almacén</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-exit"></i>
                </div>
                <a href="{{ route('output.request.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
    </div>

@endsection

@section('content-report')
   {{-- <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Valor de existencias en almacén</h3>
                    <div class="card-tools">
                        <a href="{{ route('report.excel.amount') }}" class="btn btn-sm btn-tool" data-toggle="tooltip" data-placement="top" title="Descargar excel">
                            <i class="fas fa-download text-success"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-success text-xl">
                            <i class="fas fa-dollar-sign"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                        <span class="font-weight-bold" id="amount_dollars">

                        </span>
                            <span class="text-muted">MONTO EN DÓLARES</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-warning text-xl bold">
                            S/.
                        </p>
                        <p class="d-flex flex-column text-right">
                        <span class="font-weight-bold" id="amount_soles">

                        </span>
                            <span class="text-muted">MONTO EN SOLES</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-danger text-xl">
                            <i class="fas fa-boxes"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                        <span class="font-weight-bold" id="quantity_items">

                        </span>
                            <span class="text-muted">CANTIDAD DE EXISTENCIAS</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <a href="{{ route('report.excel.materials') }}">
                        <i class="fas fa-database"></i>
                    </a>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">BASE DE DATOS MATERIALES</span>
                    <a href="{{ route('report.excel.materials') }}">
                        <span class="info-box-number">
                            Descargar <i class="fas fa-cloud-download-alt"></i>
                        </span>
                    </a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Cotizaciones elevadas en dólares</h3>
                        <a href="#" id="report_dollars_quote">Ver reporte detallado</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg" id="total_dollars">$0.00</span>
                            <span>Total de cotizaciones</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> <span id="percentage_dollars">0.00%</span>
                    </span>
                            <span class="text-muted">Cantidad 7 meses</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="sales-chart" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                          <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> Total en dólares
                          </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Cotizaciones elevadas en soles</h3>
                        <a href="#" id="report_soles_quote">Ver reporte detallado</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg" id="total_soles">S/. 0.00</span>
                            <span>Total de cotizaciones</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> <span id="percentage_soles">0.00%</span>
                            </span>
                            <span class="text-muted">Cantidad 7 meses</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="sales-chart2" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span>
                            <i class="fas fa-square text-gray"></i> Total en soles
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Egresos VS Ingresos en Dólares</h3>
                        <a href="#" id="report_expenses_income_dollars">Ver reporte detallado</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">820</span>
                            <span>Visitors Over Time</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 12.5%
                    </span>
                            <span class="text-muted">Since last week</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="visitors-chart" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> This Week
                  </span>

                        <span>
                    <i class="fas fa-square text-gray"></i> Last Week
                  </span>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>

    <div id="modalViewReportDollars" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ver reporte detallado en dólares</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong> Seleccione un rango de fechas: </strong>
                        </div>
                        <div class="col-md-12" id="sandbox-container">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control form-control-sm date-range-filter" id="start" name="start">
                                <span class="input-group-addon">&nbsp;&nbsp;&nbsp; al &nbsp;&nbsp;&nbsp; </span>
                                <input type="text" class="form-control form-control-sm date-range-filter" id="end" name="end">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4 offset-4">
                            <button type="button" id="btnViewReportDollarsQuote" class="btn btn-outline-success btn-block">Ver grafico</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-10 offset-1">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Cotizaciones elevadas en dólares</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <p class="d-flex flex-column">
                                            <span class="text-bold text-lg" id="total_dollars_view_d">S/. 0.00</span>
                                            <span>Total de cotizaciones</span>
                                        </p>
                                        <p class="ml-auto d-flex flex-column text-right">
                                            <span class="text-success">
                                                <i class="fas fa-arrow-up"></i> <span id="percentage_dollars_view_d">0.00%</span>
                                            </span>
                                        </p>
                                    </div>
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                        <canvas id="sales-chart3" height="200"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                        <span>
                                            <i class="fas fa-square text-primary"></i> Total en dolares
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-saveGroupItems" class="btn btn-outline-primary">Agregar</button>
                </div>

            </div>
        </div>
    </div>

    <div id="modalViewReportSoles" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ver reporte detallado en soles</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong> Seleccione un rango de fechas: </strong>
                        </div>
                        <div class="col-md-12" id="sandbox-container">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="form-control form-control-sm date-range-filter" id="start_s" name="start">
                                <span class="input-group-addon">&nbsp;&nbsp;&nbsp; al &nbsp;&nbsp;&nbsp; </span>
                                <input type="text" class="form-control form-control-sm date-range-filter" id="end_s" name="end">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4 offset-4">
                            <button type="button" id="btnViewReportSolesQuote" class="btn btn-outline-success btn-block">Ver gráfico</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-10 offset-1">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Cotizaciones elevadas en soles</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <p class="d-flex flex-column">
                                            <span class="text-bold text-lg" id="total_soles_view_s">S/. 0.00</span>
                                            <span>Total de cotizaciones</span>
                                        </p>
                                        <p class="ml-auto d-flex flex-column text-right">
                                            <span class="text-success">
                                                <i class="fas fa-arrow-up"></i> <span id="percentage_soles_view_s">0.00%</span>
                                            </span>
                                        </p>
                                    </div>
                                    <!-- /.d-flex -->

                                    <div class="position-relative mb-4">
                                        <canvas id="sales-chart4" height="200"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-end">
                                        <span>
                                            <i class="fas fa-square text-gray"></i> Total en soles
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>--}}
@endsection

@section('scripts')
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>

    <script src="{{ asset('admin/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/report/reportAmount.js') }}"></script>
    <script src="{{ asset('js/report/viewReport.js') }}"></script>
    <script src="{{ asset('js/report/charts.js') }}"></script>
@endsection
