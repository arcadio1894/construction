<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Sermeind') }} | @yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('landing/img/favicon.ico') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/toastr/toastr.min.css') }}">

    @yield('styles-plugins')

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">

    @yield('styles')

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>

        <!-- SEARCH FORM -->
        <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="{{ asset('admin/dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Brad Diesel
                                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">Call me whenever you can...</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
            </li>
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{asset('images/users/'.Auth::user()->image)}}" class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline">{{Auth::user()->name}}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <img src="{{asset('images/users/'.Auth::user()->image)}}" class="img-circle elevation-2" alt="User Image">

                        <p>
                            {{ Auth::user()->name }}
                            <small>Member since Nov. 2012</small>
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <li class="user-body">
                        <div class="row">
                            <div class="col-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </div>
                        <!-- /.row -->
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">Perfil</a>
                        <a class="btn btn-default btn-flat float-right" href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"></i>
                            Cerrar sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ url('/') }}" class="brand-link">
            <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">Sermeind</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{asset('images/users/'.Auth::user()->image)}}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    @can('access_permission')
                    <li class="nav-header">ADMINISTRADOR</li>
                    <li class="nav-item has-treeview @yield('openAccess')">

                        <a href="#" class="nav-link @yield('activeAccess')">
                            <i class="nav-icon fas fa-eye-slash"></i>
                            <p>
                                Accesos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('list_permission')
                            <li class="nav-item">
                                <a href="{{ route('permission.index') }}" class="nav-link @yield('activePermissions')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Permisos</p>
                                </a>
                            </li>
                            @endcan
                            @can('list_role')
                            <li class="nav-item">
                                <a href="{{ route('role.index') }}" class="nav-link @yield('activeRoles')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Roles</p>
                                </a>
                            </li>
                            @endcan
                            @can('list_user')
                                <li class="nav-item">
                                    <a href="{{ route('user.index') }}" class="nav-link @yield('activeUser')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Usuarios</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    <li class="nav-header">MANTENEDORES</li>
                    <li class="nav-item has-treeview @yield('openCustomer')">

                        <a href="#" class="nav-link @yield('activeCustomer')">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>
                                Clientes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('list_customer')
                            <li class="nav-item">
                                <a href="{{ route('customer.index') }}" class="nav-link @yield('activeListCustomer')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listar clientes</p>
                                </a>
                            </li>
                            @endcan
                            @can('create_customer')
                            <li class="nav-item">
                                <a href="{{ route('customer.create') }}" class="nav-link @yield('activeCreateCustomer')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear clientes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer.indexrestore') }}" class="nav-link @yield('activeRestoreCustomer')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Restaurar clientes</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>

                    <li class="nav-item has-treeview @yield('openContactName')">
                        <a href="#" class="nav-link @yield('activeContactName')">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>
                                Contactos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('list_customer')
                                <li class="nav-item">
                                    <a href="{{ route('contactName.index') }}" class="nav-link @yield('activeListContactName')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listar contactos</p>
                                    </a>
                                </li>
                            @endcan
                            @can('create_customer')
                                <li class="nav-item">
                                    <a href="{{ route('contactName.create') }}" class="nav-link @yield('activeCreateContactName')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear contacto</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('contactName.indexrestore') }}" class="nav-link @yield('activeRestoreContactName')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Restaurar contactos</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>

                    <li class="nav-item has-treeview @yield('openSupplier')">
                        <a href="#" class="nav-link @yield('activeSupplier')">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>
                                Proveedores
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('list_supplier')
                            <li class="nav-item">
                                <a href="{{ route('supplier.index') }}" class="nav-link @yield('activeListSupplier')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listar proveedores</p>
                                </a>
                            </li>
                            @endcan
                            @can('create_supplier')
                            <li class="nav-item">
                                <a href="{{ route('supplier.create') }}" class="nav-link @yield('activeCreateSupplier')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear proveedores</p>
                                </a>
                            </li>
                            @endcan
                            @can('destroy_supplier')
                                <li class="nav-item">
                                    <a href="{{ route('supplier.indexrestore') }}" class="nav-link @yield('activeRestoreSupplier')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Restaurar proveedores</p>
                                    </a>
                                </li>
                            @endcan
                            {{--@can('assign_supplier')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Proveedores y materiales</p>
                                </a>
                            </li>
                            @endcan--}}
                        </ul>
                    </li>

                    <li class="nav-header">MATERIALES</li>
                    <li class="nav-item has-treeview @yield('openConfig')">
                        <a href="#" class="nav-link @yield('activeConfig')">
                            <i class="fas fa-tools nav-icon"></i>
                            <p>
                                Configuraciones
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('list_unitMeasure')
                                <li class="nav-item has-treeview @yield('openUnitMeasure')">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p>
                                            Unidad de Medida
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('list_unitMeasure')
                                        <li class="nav-item">
                                            <a href="{{ route('unitmeasure.index') }}" class="nav-link @yield('activeListUnitMeasure')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Listar unidades</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('create_unitMeasure')
                                        <li class="nav-item">
                                            <a href="{{ route('unitmeasure.create') }}" class="nav-link @yield('activeCreateUnitMeasure')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Crear unidades</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan

                            @can('list_typeScrap')
                                <li class="nav-item has-treeview @yield('openTypeScrap')">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p>
                                            Tipo de retacería
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('list_typeScrap')
                                            <li class="nav-item">
                                                <a href="{{ route('typescrap.index') }}" class="nav-link @yield('activeListTypeScrap')">
                                                    <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                    <p>Listar Tipo retacería</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('create_typeScrap')
                                            <li class="nav-item">
                                                <a href="{{ route('typescrap.create') }}" class="nav-link @yield('activeCreateTypeScrap')">
                                                    <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                    <p>Crear Tipo retacería</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan

                            @can('list_category')
                            <li class="nav-item has-treeview @yield('openCategory')">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon text-success"></i>
                                    <p>
                                        Categorías
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('list_category')
                                    <li class="nav-item">
                                        <a href="{{ route('category.index') }}" class="nav-link @yield('activeListCategory')">
                                            <i class="far fa-dot-circle nav-icon text-warning"></i>
                                            <p>Listar categorias</p>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('create_category')
                                    <li class="nav-item">
                                        <a href="{{ route('category.create') }}" class="nav-link @yield('activeCreateCategory')">
                                            <i class="far fa-dot-circle nav-icon text-warning"></i>
                                            <p>Crear categorias</p>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                            @endcan

                            @can('list_subcategory')
                                <li class="nav-item has-treeview @yield('openSubcategory')">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p>
                                            Subcategorías
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('list_subcategory')
                                        <li class="nav-item">
                                            <a href="{{ route('subcategory.index') }}" class="nav-link @yield('activeListSubcategory')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Listar subcategorias</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('create_subcategory')
                                        <li class="nav-item">
                                            <a href="{{ route('subcategory.create') }}" class="nav-link @yield('activeCreateSubcategory')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Crear subcategorias</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan

                            @can('list_materialType')
                                <li class="nav-item has-treeview @yield('openMaterialType')">
                                    <a href="#" class="nav-link @yield('activeMaterialType')">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p>
                                            Tipo Materiales
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('list_materialType')
                                        <li class="nav-item">
                                            <a href="{{ route('materialtype.index') }}" class="nav-link @yield('activeListMaterialType')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Listar tipos</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('create_materialType')
                                        <li class="nav-item">
                                            <a href="{{ route('materialtype.create') }}" class="nav-link @yield('activeCreateMaterialType')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Crear tipos</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan

                            @can('list_subType')
                                <li class="nav-item has-treeview @yield('openSubType')">
                                    <a href="#" class="nav-link @yield('activeSubType')">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p>
                                            SubTipos
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('list_subType')
                                        <li class="nav-item">
                                            <a href="{{ route('subtype.index') }}" class="nav-link @yield('activeListSubType')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Listar Subtipos</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('create_subType')
                                        <li class="nav-item">
                                            <a href="{{ route('subtype.create') }}" class="nav-link @yield('activeCreateSubType')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Crear Subtipos</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan

                            @can('list_warrant')
                                <li class="nav-item has-treeview @yield('openWarrant')">
                                    <a href="#" class="nav-link @yield('activeWarrant')">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p>
                                            Cédulas
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('list_warrant')
                                        <li class="nav-item">
                                            <a href="{{ route('warrant.index') }}" class="nav-link @yield('activeListWarrant')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Listar cédulas</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('create_warrant')
                                        <li class="nav-item">
                                            <a href="{{ route('warrant.create') }}" class="nav-link @yield('activeCreateWarrant')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Crear cédulas</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan

                            @can('list_quality')
                                <li class="nav-item has-treeview @yield('openQuality')">
                                    <a href="#" class="nav-link @yield('activeQuality')">
                                        <i class="far fa-circle nav-icon text-success"></i>
                                        <p>
                                            Calidades
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('list_quality')
                                        <li class="nav-item">
                                            <a href="{{ route('quality.index') }}" class="nav-link @yield('activeListQuality')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Listar calidades</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('create_quality')
                                        <li class="nav-item">
                                            <a href="{{ route('quality.create') }}" class="nav-link @yield('activeCreateQuality')">
                                                <i class="far fa-dot-circle nav-icon text-warning"></i>
                                                <p>Crear calidades</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan

                            @can('list_brand')
                            <li class="nav-item has-treeview @yield('openBrand')">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon text-success"></i>
                                    <p>
                                        Marcas
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('list_brand')
                                    <li class="nav-item">
                                        <a href="{{ route('brand.index') }}" class="nav-link @yield('activeListBrand')">
                                            <i class="far fa-dot-circle nav-icon text-warning"></i>
                                            <p>Listar marcas</p>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('create_brand')
                                    <li class="nav-item">
                                        <a href="{{ route('brand.create') }}" class="nav-link @yield('activeCreateBrand')">
                                            <i class="far fa-dot-circle nav-icon text-warning"></i>
                                            <p>Crear marcas</p>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                            @endcan

                            @can('list_exampler')
                            <li class="nav-item has-treeview @yield('openExampler')">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon text-success"></i>
                                    <p>
                                        Modelos
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('list_exampler')
                                    <li class="nav-item">
                                        <a href="{{ route('exampler.index') }}" class="nav-link @yield('activeListExampler')">
                                            <i class="far fa-dot-circle nav-icon text-warning"></i>
                                            <p>Listar modelos</p>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('create_exampler')
                                    <li class="nav-item">
                                        <a href="{{ route('exampler.create') }}" class="nav-link @yield('activeCreateExampler')">
                                            <i class="far fa-dot-circle nav-icon text-warning"></i>
                                            <p>Crear modelos</p>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                            @endcan


                        </ul>
                    </li>
                    @can('list_material')
                        <li class="nav-item has-treeview @yield('openMaterial')">
                            <a href="#" class="nav-link @yield('activeMaterial')">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>
                                    Materiales
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('list_material')
                                <li class="nav-item">
                                    <a href="{{route('material.index')}}" class="nav-link @yield('activeListMaterial')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listar materiales</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create_material')
                                <li class="nav-item">
                                    <a href="{{ route('material.create') }}" class="nav-link @yield('activeCreateMaterial')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear materiales</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('list_quotes')
                        <li class="nav-header">COTIZACIONES</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link @yield('activeListQuotes')">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Listar cotizaciones
                                    <span class="right badge badge-danger">New</span>
                                </p>
                            </a>
                        </li>
                        {{--@can('create_quotes')
                        <li class="nav-item">
                            <a href="#" class="nav-link @yield('activeCreateQuotes')">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Crear cotizaciones
                                    <span class="right badge badge-danger">New</span>
                                </p>
                            </a>
                        </li>
                        @endcan--}}
                    @endcan
                    @can('list_area')
                    <li class="nav-header">INVENTARIO</li>
                    <li class="nav-item has-treeview @yield('openInventory')">
                        <a href="#" class="nav-link @yield('activeInventory')">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>
                                Inventario Físico
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('list_area')
                            <li class="nav-item">
                                <a href="{{ route('area.index') }}" class="nav-link @yield('activeAreas')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Áreas</p>
                                </a>
                            </li>
                            @endcan
                            @can('list_location')
                            <li class="nav-item">
                                <a href="{{ route('location.index') }}" class="nav-link @yield('activeLocations')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ubicaciones</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan

                    <li class="nav-header">TRANSFERENCIAS</li>
                    <li class="nav-item has-treeview @yield('openTransfer')">
                        <a href="#" class="nav-link @yield('activeTransfer')">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>
                                Transferencias
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('transfer.index') }}" class="nav-link @yield('activeListTransfer')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listar traslados</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('transfer.create') }}" class="nav-link @yield('activeCreateTransfer')">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear traslado</p>
                                    </a>
                                </li>

                        </ul>
                    </li>

                    <li class="nav-header">ENTRADAS</li>
                    <li class="nav-item has-treeview @yield('openEntryPurchase')">
                        <a href="#" class="nav-link @yield('activeEntryPurchase')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Por compra
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('entry.purchase.index') }}" class="nav-link @yield('activeListEntryPurchase')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listar entradas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('entry.purchase.create') }}" class="nav-link @yield('activeCreateEntryPurchase')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear entrada</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview @yield('openEntryScrap')">
                        <a href="#" class="nav-link @yield('activeEntryScrap')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Por retazos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('entry.scrap.index') }}" class="nav-link @yield('activeListEntryScrap')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listar entradas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('entry.scrap.create') }}" class="nav-link @yield('activeCreateEntryScrap')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear entrada</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">SALIDAS</li>
                    <li class="nav-item has-treeview @yield('openOutputRequest')">
                        <a href="#" class="nav-link @yield('activeOutputRequest')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Solicitudes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('output.request.index') }}" class="nav-link @yield('activeListOutputRequest')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listar solicitudes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('output.request.create') }}" class="nav-link @yield('activeCreateOutputRequest')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Crear solicitudes</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview @yield('openOutputs')">
                        <a href="#" class="nav-link @yield('activeOutputs')">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Salidas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('output.confirm') }}" class="nav-link @yield('activeListOutput')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listar salidas</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    {{--<li class="nav-header">NAVBAR HEADER</li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                EJEMPLO TREEVIEW
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Active Page</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Inactive Page</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Level 1
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Level 2</p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Level 2
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Level 3</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Level 3</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Level 3</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Level 2</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-header">NAVBAR HEADER</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                EXAMPLE SIMPLE
                                <span class="right badge badge-danger">New</span>
                            </p>
                        </a>
                    </li>--}}
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        @yield('page-header')
                        {{--<h1 class="m-0 text-dark">Starter Page</h1>--}}

                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        @yield('page-breadcrumb')
                        {{--<ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Starter Page</li>
                        </ol>--}}

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    @yield('page-title')
                    {{--<h5 class="card-title">Card header</h5>--}}
                </div>
                <div class="card-body">
                    @yield('content')
                    {{--<h5 class="card-title">Card title</h5>--}}
                </div>
                {{--<div class="card-footer text-muted">
                    <a href="#" class="btn btn-primary">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>--}}
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- Default to the left -->
        <strong>Copyright &copy; <script>document.write(new Date().getFullYear());</script> <a href="https://www.edesce.com/">EDESCE</a>.</strong> Todos los derechos reservados.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('admin/plugins/toastr/toastr.min.js') }}"></script>

@yield('plugins')

<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>

@yield('scripts')

</body>
</html>
