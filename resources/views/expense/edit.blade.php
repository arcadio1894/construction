@extends('layouts.appAdmin2')

@section('openDiscountContribution')
    menu-open
@endsection

@section('activeDiscountContribution')
    active
@endsection

@section('openExpense')
    menu-open
@endsection

@section('activeListExpense')
    active
@endsection

@section('title')
    Rendición de Gastos
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
    <h1 class="page-title">Gasto de {{ $expense->worker->first_name .' '.$expense->worker->last_name }}</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Modificar Gasto</h5>
    <a href="{{ route('expense.index') }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-arrow-left font-20"></i> Listado de Rendición de Gastos</a>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('expense.index') }}"><i class="fa fa-archive"></i> Rendición de gastos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Modificar</li>
    </ol>
@endsection

@section('content')

    <form id="formCreate" class="form-horizontal" data-url="{{ route('expense.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="expense_id" value="{{ $expense->id }}">

        <div class="form-group row">
            <div class="col-md-6">
                <label for="date_expense">Fecha</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" id="date_expense" value="{{ ($expense->date_expense == null) ? '': $expense->date_expense->format('d/m/Y') }}" name="date_expense" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>

                </div>
            </div>
            <div class="col-md-6">
                <label for="total">Monto</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" id="total" min="0" step="0.01" name="total" class="form-control" value="{{ $expense->total }}">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="bill_id">Tipo de Gastos </label>

                <select id="bill_id" name="bill_id" class="form-control select2" style="width: 100%;">
                    <option></option>
                    @foreach( $bills as $bill )
                        <option value="{{ $bill->id }}" {{ ($expense->bill_id == $bill->id) ? 'selected':'' }} >{{ $bill->description}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="text-center">
            <button type="button" id="btn-submit" class="btn btn-outline-success">Guardar</button>
            <a href="{{ route('expense.index') }}" class="btn btn-outline-secondary">Cancelar</a>
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
            $('#date_expense').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#bill_id').select2({
                placeholder: "Selecione tipo de gasto",
            });
        })
    </script>
    <script src="{{ asset('js/expense/edit.js') }}"></script>
@endsection
