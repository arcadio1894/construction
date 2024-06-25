@extends('layouts.appAdmin2')

@section('openInventory')
    menu-open
@endsection

@section('activeInventory')
    active
@endsection

@section('activeListInventory')
    active
@endsection

@section('title')
    Inventario Fisico
@endsection

@section('styles-plugins')

@endsection

@section('page-header')
    <h1 class="page-title">Inventario Físico</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-lock"></i> Inventario Fisico</li>
    </ol>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de Inventario Fisico</h5>

@endsection

@section('content')
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <!--begin::Form-->
    <form action="#">
        <!--begin::Card-->
        <!--begin::Input group-->
        <div class="row">
            <div class="col-md-8">
                <!-- Barra de búsqueda -->
                <div class="input-group">
                    <input type="text" id="full_name" class="form-control" placeholder="Nombre del material..." autocomplete="off">
                    <div class="input-group-append ">
                        <button class="btn btn-primary" type="button" id="btn-search">Buscar</button>
                    </div>
                </div>

            </div>
            <div class="col-md-2">
                <button class="btn btn-success btn-block" type="button" id="btn-save"> <i class="far fa-save"></i> Guardar datos</button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-warning btn-block" type="button" id="btn-export"> <i class="far fa-file-excel"></i> Exportar Excel</button>
            </div>
        </div>
        <!--end::Input group-->
        <!--begin:Action-->
        {{--<div class="col-md-1">
            <label for="btn-search">&nbsp;</label><br>
            <button type="button" id="btn-search" class="btn btn-primary me-5">Buscar</button>
        </div>--}}

    </form>
    <!--end::Form-->

    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack pb-7">
        <!--begin::Title-->
        <div class="d-flex flex-wrap align-items-center my-1">
            <h3 class="fw-bolder me-5 my-1"><span id="numberItems"></span> Materiales encontrados
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
            <table class="table table-bordered letraTabla table-hover table-sm">
                <thead>
                <tr class="normal-title">
                    <th>Código</th>
                    <th>Material</th>
                    <th>Stock Sistema</th>
                    <th>Stock Fisico</th>
                    <th>Ubicación</th>
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

    <template id="item-table">
        <tr>
            <td data-code></td>
            <td data-full_name></td>
            <td data-stock></td>
            <td>
                <div class="input-group">
                    <input class="form-control form-control-sm" data-inventory data-id type="number" step="0.01" min="0">
                    <div class="input-group-append ">
                        <button data-add_scraps data-length data-width data-id data-material class="btn btn-outline-primary btn-sm">Retazos</button>
                    </div>
                </div>

            </td>
            <td data-location></td>
        </tr>
    </template>

    <template id="item-table-empty">
        <tr>
            <td colspan="5" align="center">No se ha encontrado ningún dato</td>
        </tr>
    </template>

    <div id="modalScraps" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ingresar retazo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body table-responsive">

                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Importante!</strong> Ingrese las medidas correctas. Para hallar el porcentaje correcto.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-8" >
                            <label class="col-sm-12 control-label" for="material"> Material </label>
                            <div class="col-sm-12">
                                <input type="text" id="material" name="material" class="form-control form-control-sm" readonly />
                                <input type="hidden" id="material_id">
                                <input type="hidden" id="material_typescrap">
                                <input type="hidden" id="material_length">
                                <input type="hidden" id="material_width">
                            </div>
                        </div>
                        <div class="col-md-2" id="length_title">
                            <label class="col-sm-12 control-label" for="length"> Largo (mm) </label>
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text" id="length" name="length" class="form-control form-control-sm" readonly >

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2" id="width_title">
                            <label class="col-sm-12 control-label" for="width"> Ancho (mm) </label>

                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text" id="width" name="width" class="form-control form-control-sm" readonly >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" >
                            <label class="col-sm-12 control-label"> Ingrese las nuevas longitudes </label>
                        </div>
                        <div class="col-md-3" id="length_new_title">
                            <label class="col-sm-12 control-label" for="length_new"> Largo (mm) </label>

                            <div class="col-sm-12">
                                <input type="number" id="length_new" min="0" name="length_new" value="0" class="form-control form-control-sm" />
                            </div>
                        </div>
                        <div class="col-md-3" id="width_new_title">
                            <label class="col-sm-12 control-label" for="width_new"> Ancho (mm) </label>

                            <div class="col-sm-12">
                                <input type="number" id="width_new" min="0" name="width_new" value="0" class="form-control form-control-sm" />
                            </div>
                        </div>
                        <div class="col-md-3" >
                            <label class="col-sm-12 control-label" for="percentage_new"> Porcentaje </label>

                            <div class="col-sm-12">
                                <input type="number" id="percentage_new" min="0" name="percentage_new" class="form-control form-control-sm" readonly/>
                            </div>
                        </div>
                        <div class="col-md-3" >
                            <label class="col-sm-12 control-label" for="width_new"> &nbsp; </label>

                            <div class="col-sm-12">
                                <button type="button" id="btn-submit-new" class="btn btn-success btn-sm btn-block">Agregar retazo</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugins')

@endsection

@section('scripts')
    <script src="{{ asset('js/inventory/list.js') }}?v={{ time() }}"></script>
@endsection
