@extends('layouts.appAdmin2')

@section('openQuote')
    menu-open
@endsection

@section('activeQuote')
    active
@endsection

@section('activeListQuote')
    active
@endsection

@section('title')
    Cotizaciones Planos
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/typehead/typeahead.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Planos de cotizaciones</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Modificar planos de cotización</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('quote.index') }}"><i class="fa fa-key"></i> Cotizaciones</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar planos</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Importante!</strong> Modifique la descripción y orden de imagenes antiguas y agregue nuevas imágenes.
                <br>
                <strong>Importante!</strong> Si desea cambiar una imagen entonces primero elimínela y vuelva a agregarla.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <br>
    <input type="hidden" id="permissions" value="{{ json_encode($permissions) }}">

    <form id="formEdit" class="form-horizontal" data-url="{{ route('save.planos.quote', $quote->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="row" >
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">IMAGENES DE PLANOS</h3>

                        <div class="card-tools">
                            <a id="addImage" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Agregar imagen">
                                <i class="far fa-images"></i> Agregar Imagen
                            </a>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body" >
                        <div class="row" id="body-images">
                            @foreach( $images as $image )
                            <div class="col-md-4">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Imagen Guardada</h3>

                                        <div class="card-tools">
                                            <button type="button" data-imageeditold class="btn btn-sm btn-outline-success" data-toggle="tooltip" data-placement="top" title="Guardar descripción y orden" ><i class="fas fa-save"></i></i>
                                            </button>
                                            <button type="button" data-imagedeleteold class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Quitar de la base de datos" ><i class="fas fa-trash"></i></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <input type="hidden" value="{{ $image->id }}">
                                        <div class="form-group">
                                            <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                            <textarea class="form-control" data-img data-imgold rows="2" placeholder="Enter ...">{{ $image->description }}</textarea>

                                        </div>
                                        <div class="form-group">
                                            <label for="description">Orden presentación <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" data-order step="1" min="1" class="form-control" value="{{ $image->order }}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Imagen <span class="right badge badge-danger">(*)</span></label>
                                            {{--<div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" accept="image/*" class="form-control" onchange="previewFile(this)">
                                                </div>
                                            </div>--}}
                                            <br>
                                            <img height="150px" class="center" src="{{ asset('images/planos/'.$image->image) }}" />

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            @endforeach
                        </div>

                        <template id="template-image">
                            <div class="col-md-4">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Imagen Seleccionada</h3>

                                        <div class="card-tools">
                                            <button type="button" data-imagedelete class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Quitar" ><i class="fas fa-trash"></i></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                            <textarea class="form-control" data-img name="descplanos[]" rows="2" placeholder="Enter ..."></textarea>

                                        </div>
                                        <div class="form-group">
                                            <label for="description">Orden presentación <span class="right badge badge-danger">(*)</span></label>
                                            <input type="number" name="orderplanos[]" step="1" min="1" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Imagen <span class="right badge badge-danger">(*)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="planos[]" accept="image/*" class="form-control" onchange="previewFile(this)">
                                                </div>
                                            </div>
                                            <img height="100px" />

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </template>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <a href="{{ route('quote.index') }}" class="btn btn-outline-secondary">Regresar</a>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar nuevas imágenes</button>
            </div>
        </div>

        @can('showPrices_quote')
            <p class="lead">Resumen de Cotización</p>
            <div class="row">
                <!-- accepted payments column -->
                <div class="col-sm-12">
                    <div class="card card-lightblue" >
                        <div class="card-header  border-transparent" >
                            <h3 class="card-title">Equipos de cotización</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th >Equipo</th>
                                        <th style="background-color: #92D050">Cantidad</th>
                                        <th style="background-color: #9BC2E6">Subtotal S/Igv</th>
                                        <th style="background-color: #9BC2E6">Utilidad</th>
                                        <th style="background-color: #9BC2E6">Letra+Renta</th>
                                        <th style="background-color: #9BC2E6">Precio Unit. S/Igv</th>
                                        <th style="background-color: #ED7D31">Total S/Igv</th>

                                    </tr>
                                    </thead>
                                    <tbody id="body-summary">
                                    @foreach( $quote->equipments as $equipment )
                                        <tr>
                                            <td data-nEquipment>{{ $equipment->description }}</td>
                                            <td data-qEquipment>{{ $equipment->quantity }}</td>
                                            <td data-pEquipment>{{ round(($equipment->total/$equipment->quantity)/1.18, 2) }}</td>
                                            <td data-uEquipment>{{ $equipment->utility }}</td>
                                            <td data-rlEquipment>{{ $equipment->rent + $equipment->letter }}</td>
                                            <td data-uPEquipment>{{ round(($equipment->subtotal_percentage/1.18)/$equipment->quantity, 2) }}</td>
                                            <td data-tEquipment>{{ round($equipment->subtotal_percentage/1.18, 2) }}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-sm-7">

                </div>
                <!-- /.col -->
                <div class="col-sm-5">
                    <p class="lead">Resumen de Cotización</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Total S/IGV: </th>
                                <td id="subtotal"> USD {{ round(($quote->total_equipments)/1.18, 2) }}</td>
                                <input type="hidden" name="quote_total" id="quote_total" value="{{ $quote->total }}">
                                <input type="hidden" name="quote_subtotal_utility" id="quote_subtotal_utility" value="{{ $quote->subtotal_utility }}">
                                <input type="hidden" name="quote_subtotal_letter" id="quote_subtotal_letter" value="{{ $quote->subtotal_letter }}">
                                <input type="hidden" name="quote_subtotal_rent" id="quote_subtotal_rent" value="{{ $quote->subtotal_rent }}">

                            </tr>
                            <tr>
                                <th style="width:50%">Total C/IGV: </th>
                                <td id="total"> USD {{ round($quote->total_equipments, 2) }}</td>
                            </tr>
                            <tr>
                                <th style="width:50%">Total+Utilidad S/IGV: </th>
                                <td id="subtotal_utility">USD {{ round(($quote->total_quote)/1.18, 2) }}</td>
                            </tr>
                            <tr>
                                <th style="width:50%">Total+Utilidad C/IGV: </th>
                                <td id="total_utility">USD {{ round($quote->total_quote, 2) }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
        @endcan

        {{--<div class="row">
            <div class="col-12">
                <a href="{{ route('quote.index') }}" class="btn btn-outline-secondary">Regresar</a>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar nuevos equipos</button>
            </div>
        </div>--}}
        <!-- /.card-footer -->
    </form>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{asset('admin/plugins/typehead/typeahead.bundle.js')}}"></script>
@endsection

@section('scripts')
    <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/lang/summernote-es-ES.js')}}"></script>
    <script src="{{ asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.textarea_edit').summernote({
                lang: 'es-ES',
                placeholder: 'Ingrese los detalles',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ]
            });

            $('#customer_id').select2({
                placeholder: "Selecione cliente",
            });
            $('#contact_id').select2({
                placeholder: "Selecione contacto",
            });

            $('#paymentQuote').select2({
                placeholder: "Selecione forma de pago",
            });

            $('.unitMeasure').select2({
                placeholder: "Seleccione unidad",
            });

            $('.material_search').select2({
                placeholder: 'Selecciona un material',
                ajax: {
                    url: '/dashboard/select/materials',
                    dataType: 'json',
                    type: 'GET',
                    processResults(data) {
                        //console.log(data);
                        return {
                            results: $.map(data, function (item) {
                                //console.log(item.full_description);
                                return {
                                    text: item.full_description,
                                    id: item.id,
                                }
                            })
                        }
                    }
                }
            });

            $('.consumable_search').select2({
                placeholder: 'Selecciona un consumible',
                ajax: {
                    url: '/dashboard/select/consumables',
                    dataType: 'json',
                    type: 'GET',
                    processResults(data) {
                        //console.log(data);
                        return {
                            results: $.map(data, function (item) {
                                //console.log(item.full_description);
                                return {
                                    text: item.full_description,
                                    id: item.id,
                                }
                            })
                        }
                    }
                }
            });


            $('#sandbox-container .input-daterange').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                language: "es",
                multidate: false,
                autoclose: true
            });
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>

    <script src="{{ asset('js/quote/editPlanos.js') }}"></script>
@endsection
