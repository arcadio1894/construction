@extends('layouts.appAdmin2')

@section('openDefaultEquipment')
    menu-open
@endsection

@section('activeDefaultEquipment')
    active
@endsection

@section('activeCategoryEquipment')
    active
@endsection

@section('title')
    Catálogo de Equipos
@endsection

@section('styles-plugins')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>

    <style>
        .my-custom-card {
            min-height: 280px; /* Establece la altura mínima deseada */
            max-height: 300px; /* Establece la altura máxima deseada */
            overflow: auto; /* Agrega barras de desplazamiento si el contenido supera la altura máxima */
        }        
    </style>
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    -->
@endsection

@section('page-title')
    <h5 class="card-title">Listado de equipos de la categoría {{ $category->description }}</h5>
    
    @can('create_defaultEquipment')
        <a href="{{ route('defaultEquipment.create',$category->id) }}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo Equipo </a>
    @endcan

@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('categoryEquipment.index') }}"><i class="fa fa-archive"></i> Categorias: {{ $category->description }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('defaultEquipment.index', $category->id) }}"><i class="fa fa-archive"></i> Equipos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
        <!--begin::Card-->
@can('list_defaultEquipment')
    <!--begin::Form-->
    <form action="#">
        <!--begin::Card-->
        <div class="card mb-7">
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Compact form-->
                <div class="form-group row">
                    <!--begin::Input group-->
                    <div class="position-relative w-md-400px me-md-2" hidden>
                        <input type="number" class="form-control form-control-solid ps-10" id="inputCategoryEquipmentid" name="search" value="{{$category -> id }}"  hidden />
                        
                    </div>

                    <div class="col-md-5">
                        <label for="inputDescription">Descripción <span class="right badge badge-danger">(*)</span></label>
                        <input type="text" class="form-control form-control-solid ps-10" id="inputDescription" name="search" value="" placeholder="Descripción del equipo" />
                    </div>

                    <div class="col-md-2">
                        <label for="inputLarge">Largo </label>
                        <input type="number" class="form-control form-control-solid ps-10" id="inputLarge" name="search" value="" placeholder="Largo"  min="0" step="0.01"/>
                    </div>
                    <div class="col-md-2">
                        <label for="inputWidth">Ancho </label>
                        <input type="number" class="form-control form-control-solid ps-10" id="inputWidth" name="search" value="" placeholder="Ancho" min="0" step="0.01"/>
                    </div>
                    <div class="col-md-2">
                        <label for="inputHigh">Alto </label>
                        <input type="number" class="form-control form-control-solid ps-10" id="inputHigh" name="search" value="" placeholder="Alto" min="0" step="0.01"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin:Action-->
                    <div class="col-md-1">
                        <label for="btn-search">&nbsp;</label><br>
                        <button type="button" id="btn-search" class="btn btn-primary me-5">Buscar</button>
                    </div>

                    <!--
                    <div class="col-md-1">
                        <a href="{{-- route('defaultEquipment.create',$category->id) --}}" class="btn btn-outline-success btn-sm float-right" > <i class="fa fa-plus font-20"></i> Nuevo Equipo </a>
                    </div>
                    -->
                    <!--end:Action-->
                </div>
                <!--end::Compact form-->

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </form>
    <!--end::Form-->


    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack pb-7">
        <!--begin::Title-->
        <div class="d-flex flex-wrap align-items-center my-1">
            <h3 class="fw-bolder me-5 my-1"><span id="numberItems"></span> Equipos encontrados
                <span class="text-gray-400 fs-6">por fecha de creación ↓ </span>
            </h3>
        </div>
        <!--end::Title-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Tab Content-->
    <div class="tab-content">
        <!--begin::Tab pane-->
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Largo</th>
                    <th>Ancho</th>
                    <th>Alto</th>
                    <th>Precio S/IGV S/Uti</th>
                    <th>Precio C/IGV S/Uti</th>
                    <th>Precio S/IGV C/Uti</th>
                    <th>Precio C/IGV C/Uti</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="body-table">

                </tbody>
            </table>
        </div>
        <!--end::Tab pane-->
        <!--begin::Pagination-->
        <div class="d-flex flex-stack flex-wrap pt-1">
            <div class="fs-6 fw-bold text-gray-700" id="textPagination"></div>
            <!--begin::Pages-->
            <ul class="pagination" style="margin-left: auto;" id="pagination">

            </ul>
            <!--end::Pages-->
        </div>
        <!--end::Pagination-->
    </div>
    <!--end::Tab Content-->
@endcan

    <template id="previous-page">
        <li class="page-item previous">
            <a href="#" class="page-link" data-item>
                <!--<i class="previous"></i>-->
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>
    </template>

    <template id="item-page">
        <li class="page-item" data-active>
            <a href="#" class="page-link" data-item="">5</a>
        </li>
    </template>

    <template id="next-page">
        <li class="page-item next">
            <a href="#" class="page-link" data-item>
                <!--<i class="next"></i>-->
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    </template>

    <template id="disabled-page">
        <li class="page-item disabled">
            <span class="page-link">...</span>
        </li>
    </template>


    <template id="item-card">
        {{--<div class="col-md-3 col-xxl-3">

            <div class="card bg-light my-custom-card">
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="lead text-center"><b data-description></b></h2>
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                <li class="small" hidden><span class="fa-li"><i class="fas fa-wrench"></i></span>Detalles: <span data-details></span></li>
                                <li class="small"><span class="fa-li"><i class="fas fa-wrench"></i></span>Largo: <span data-large></span></li>
                                <li class="small"><span class="fa-li"><i class="fas fa-wrench"></i></span>Ancho: <span data-width></span></li>
                                <li class="small"><span class="fa-li"><i class="fas fa-wrench"></i></span>Alto: <span data-high></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <a data-edit="--}}{{-- $category->id --}}{{--" data-descritpion="--}}{{-- $category->description --}}{{--" href="#" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil-square"></i> Editar 
                        </a>
                        <a data-delete="" data-descritpion="" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </div>
                </div>
            </div>

        </div>--}}
        <tr>
            <td data-id></td>
            <td data-description></td>
            <td data-large></td>
            <td data-width></td>
            <td data-high></td>
            <td data-priceSIGV></td>
            <td data-priceIGV></td>
            <td data-priceSIGVUtility></td>
            <td data-priceIGVUtility></td>
            <td data-created_at></td>
            <td>
                <a data-edit="" data-descritpion="" href="#" class="btn btn-sm btn-outline-warning">
                    <i class="far fa-edit"></i> Editar
                </a>
                <a data-delete="" data-descritpion="" class="btn btn-sm btn-outline-danger">
                    <i class="far fa-trash-alt"></i> Eliminar
                </a>
            </td>
        </tr>
    </template>





@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('js/defaultEquipment/index.js') }}"></script>
@endsection