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
    Categoria de Equipos
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
    <h1 class="page-title">Categoria de Equipos</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de categorias de equipos</h5>
    <a href="#" class="btn btn-outline-success btn-sm float-right" data-toggle="modal" data-target="#createModal"> <i class="fa fa-plus font-20"></i> Nueva Categoria </a>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#"><i class="fa fa-archive"></i> Categorías de Equipos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Listado</li>
    </ol>
@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">
    <form action="#">
        <div class="card card-primary mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                            <input type="text" class="form-control" id="inputNameCategoryEquipment" name="search" value="" placeholder="Nombre de la categoría del equipo">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="btn-search" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="d-flex flex-wrap align-items-center mb-4">
        <h5 class="fw-bold me-5 my-1"><span id="numberItems"></span> Categorías de Equipo encontradas</h5>
    </div>

    <div class="container">
        <div class="row" id="body-card"></div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="fs-6 fw-bold text-gray-700" id="textPagination"></div>
            <ul class="pagination" id="pagination"></ul>
        </div>
    </div>



    <template id="item-card">
        <div class="col-12 col-sm-6 col-md-3 d-flex align-items-stretch">
            <div class="card bg-light">
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="hidden" data-id></div>
                            <h2 class="lead text-center"><b data-description></b></h2>
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                <li class="small"><span class="fa-li"><i class="fas fa-wrench"></i></span>Numero de Equipos: <span data-number></span></li>
                            </ul>
                        </div>
                        <div class="col-md-8 offset-2 text-center mt-3">
                            <img data-image src="" alt="" class="img-circle img-fluidcpt-3">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <a data-edit="" data-description="" data-image=""  href="#" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editModal">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a data-delete="" data-description="" data-image="" href="#" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash"></i> Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>


    <template id="previous-page">
        <li class="page-item previous">
            <a href="#" class="page-link" data-item><
                <i class="previous"></i>
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
            <a href="#" class="page-link" data-item>>
                <i class="next"></i>
            </a>
        </li>
    </template>

    <template id="disabled-page">
        <li class="page-item disabled">
            <span class="page-link">...</span>
        </li>
    </template>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Eliminar Categoría de Equipo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que quieres eliminar esta categoría?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-outline-primary" id="btn-delete-confirm">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Categoría de Equipo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm">
                        <div class="form-group">
                            <label for="editDescription">Descripción<span class="right badge badge-danger">(*)</span></label>
                            <input type="text" id="editDescription" class="form-control" placeholder="Nueva descripción">
                        </div>
                        <div class="col-md-12">
                            <label for="file">Archivo IMG/PDF </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-file-archive"></i></span>
                                </div>
                                <input type="file" id="file" name="file" class="form-control" >
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn-edit-confirm">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('js/categoryEquipment/index.js') }}"></script>
    <script src="{{ asset('js/categoryEquipment/delete.js') }}"></script>
@endsection