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
    Cuentas Bancarias
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- VDialog -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/vdialog/css/vdialog.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            display: none;
        }
    </style>
@endsection

@section('page-title')
    <input type="hidden" name="worker_id" id="worker_id" value="{{ $worker->id }}">
    <h5 class="card-title">Listado de los cuentas bancarias de {{ $worker->first_name . " " . $worker->last_name }}</h5>
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


    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Cuentas Bancarias</h3>

                    <div class="card-tools">
                        @can('create_workerAccount')
                        <button type="button" id="newAccount" data-url="{{ route('worker.account.store', $worker->id) }}" class="btn btn-sm btn-warning" > <i class="far fa-credit-card"></i> Agregar Cuenta </button>
                        @endcan
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>

                </div>
                <div class="card-body" id="body-accounts">
                    @foreach( $accounts as $account )
                        <div class="callout callout-info">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="numberAccounts">Número de Cuenta </label>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                        </div>
                                        <input type="number" data-number_account class="form-control" placeholder="Número de Cuenta" value="{{ $account->number_account }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="banks">Banco </label>
                                        <select name="banks[]" data-bank class="bank form-control form-control-sm select2" style="width: 100%;">
                                            <option></option>
                                            @foreach( $banks as $bank )
                                                <option value="{{ $bank->id }}" {{ ($bank->id == $account->bank_id) ? 'selected':'' }}>{{ $bank->short_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="currency">Moneda </label>
                                        <select name="currency[]" data-currency class="currency form-control form-control-sm select2" style="width: 100%;">
                                            <option></option>
                                            <option value="PEN" {{ ('PEN' == $account->currency) ? 'selected':'' }} >Soles</option>
                                            <option value="USD" {{ ('USD' == $account->currency) ? 'selected':'' }} >Dólares</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="currency">Acciones </label> <br>
                                        <button type="button" data-updateAccount="{{ $account->id }}" data-number="{{ $account->number_account }}" class="btn btn-sm btn-outline-warning ">Editar cuenta</button>
                                        <button type="button" data-deleteAccount="{{ $account->id }}" data-number="{{ $account->number_account }}" class="btn btn-sm btn-outline-danger ">Eliminar cuenta</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <template id="template-account">
        <div class="callout callout-info">
            <div class="row">
                <div class="col-md-3">
                    <label for="numberAccounts">Número de Cuenta </label>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                        </div>
                        <input type="number" data-number_account name="numberAccounts[]" class="form-control" placeholder="Número de Cuenta" value="">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="banks">Banco </label>
                        <select name="banks[]" data-bank class="bank form-control form-control-sm select2" style="width: 100%;">
                            <option></option>
                            @foreach( $banks as $bank )
                                <option value="{{ $bank->id }}" {{ ($bank->id == 1) ? 'selected':'' }}>{{ $bank->short_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="currency">Moneda </label>
                        <select name="currency[]" data-currency class="currency form-control form-control-sm select2" style="width: 100%;">
                            <option></option>
                            <option value="PEN" selected >Soles</option>
                            <option value="USD">Dólares</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="currency">Acciones </label> <br>
                        <button type="button" data-updateAccount data-number class="btn btn-sm btn-outline-warning ">Editar cuenta</button>
                        <button type="button" data-deleteAccount data-number class="btn btn-sm btn-outline-danger ">Eliminar cuenta</button>
                    </div>
                </div>
            </div>

        </div>
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
    <!-- Vdialog -->
    <script src="{{ asset('admin/plugins/vdialog/js/lib/vdialog.js') }}"></script>

@endsection

@section('scripts')
    <script>
        $('.bank').select2({
            placeholder: "Seleccione un banco",
        });
        $('.currency').select2({
            placeholder: "Seleccione una moneda",
        })
    </script>
    <script src="{{ asset('js/workerAccount/create.js') }}"></script>
@endsection