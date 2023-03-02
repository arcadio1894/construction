@extends('layouts.appAdmin2')

@section('openDiscountContribution')
    menu-open
@endsection

@section('activeDiscountContribution')
    active
@endsection

@section('openLoans')
    menu-open
@endsection

@section('activeCreateLoan')
    active
@endsection

@section('title')
    Préstamos
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

@section('page-header')
    <h1 class="page-title">Nuevo préstamo</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nuevo préstamo</h5>
    <a href="{{ route('loan.index') }}" class="btn btn-outline-primary btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de Préstamos</a>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('loan.index') }}"><i class="fa fa-archive"></i> Préstamos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')

    <form id="formCreate" class="form-horizontal" data-url="{{ route('loan.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <div class="col-md-4">
                <label for="worker_id">Trabajador: </label>
                <select id="worker_id" name="worker_id" class="form-control select2" style="width: 100%;">
                    <option></option>
                    @foreach( $workers as $worker )
                        <option value="{{ $worker->id }}" data-supervisor="{{  $worker->first_name . ' ' . $worker->last_name }}">{{ $worker->first_name . ' ' . $worker->last_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="reason">Motivo </label>

                <textarea name="reason" id="reason" class="form-control"></textarea>

            </div>

            <div class="col-md-4">
                <label for="date">Fecha</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" id="date" name="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
                <label for="amount">Monto</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" id="amount" min="0" step="0.01" name="amount" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <label for="num_dues">Cuotas</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                    </div>
                    <input type="number" id="num_dues" min="1" step="1" name="num_dues" class="form-control" value="1">
                </div>
            </div>
            <div class="col-md-3">
                <label for="time_pay">Intervalo pago (días)</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                    </div>
                    <input type="number" id="time_pay" min="1" step="1" name="time_pay" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <label for="rate">Tasa Interés</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                    </div>
                    <input type="number" id="rate" min="0" step="0.01" name="rate" class="form-control" value="0">
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="button" id="btn-submit" class="btn btn-outline-success">Guardar</button>
            <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
        </div>
        <!-- /.card-footer -->
    </form>
@endsection

@section('plugins')
    <!-- Datatables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            //$('#datemask').inputmask()
            $('#date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#worker_id').select2({
                placeholder: "Selecione trabajador",
            });

        })
    </script>
    <script src="{{ asset('js/loan/create.js') }}"></script>
@endsection
