@extends('layouts.appAdmin2')

@section('openDiscountContribution')
    menu-open
@endsection

@section('activeDiscountContribution')
    active
@endsection

@section('openAlimony')
    menu-open
@endsection

@section('activeAlimony')
    active
@endsection

@section('title')
    Pensión de alimentos
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
    <h5 class="card-title">Listado de trabajadores</h5>
    <button type="button" id="btn-refresh" class="btn btn-outline-warning btn-sm float-right ml-2 mr-2" > <i class="fas fa-sync-alt font-20"></i> Refrescar  </button>

@endsection

@section('page-header')
    <h1 class="page-title">Trabajadores con pensión de alimentos</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('alimony.index') }}"><i class="fa fa-archive"></i> Pensión Alimentos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Trabajadores</li>
    </ol>
@endsection

@section('content')
    <div class="row" id="body-workers">

    </div>

    <template id="template-worker">
        <div class="col-md-4">
            <!-- Widget: user widget style 2 -->
            <div class="card card-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-warning">
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" data-image src="{{ asset('admin/dist/img/user7-128x128.jpg') }}" alt="User Avatar">
                    </div>
                    <!-- /.widget-user-image -->
                    <h4 class="widget-user-username" data-username></h4>
                    <h5 class="widget-user-desc" data-function></h5>
                </div>
                <div class="card-footer p-0">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link bg-gradient-warning">
                                Número de Registros <span class="float-right badge bg-primary" data-num_register>31</span>
                            </a>
                        </li>
                        <li class="nav-item bg-gradient-success">
                            <a data-register href="#" class="nav-link text-white">
                                Registrar <i class="fas fa-external-link-alt float-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.widget-user -->
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
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>

@endsection

@section('scripts')
    <script src="{{asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>
    <script>
        $(function () {

            $('#worker').select2({
                placeholder: "Seleccione un trabajador",
            });

        })
    </script>
    <script src="{{ asset('js/alimony/index.js') }}"></script>
@endsection