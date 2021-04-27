<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('landing/img/favicon.ico') }}">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ asset('admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="{{ asset('admin/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="{{ asset('admin/css/main.min.css') }}" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <link href="{{ asset('toast/jquery.toast.min.css') }}" rel="stylesheet" />
    <!-- CSS inside -->
    @yield('styles')
</head>

<body class="fixed-navbar">
<div class="page-wrapper">
    <!-- START HEADER-->
    <header class="header">
        <div class="page-brand">
            <a class="link" href="{{ url('/') }}">
                    <span class="brand">Construc
                        <span class="brand-tip">TION</span>
                    </span>
                <span class="brand-mini">CO</span>
            </a>
        </div>
        <div class="flexbox flex-1">
            <!-- START TOP-LEFT TOOLBAR-->
            <ul class="nav navbar-toolbar">
                <li>
                    <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
                </li>
                <li>
                    <form class="navbar-search" >
                        <div class="rel">
                            <span class="search-icon"><i class="ti-search"></i></span>
                            <input class="form-control" placeholder="Search here...">
                        </div>
                    </form>
                </li>
            </ul>
            <!-- END TOP-LEFT TOOLBAR-->
            <!-- START TOP-RIGHT TOOLBAR-->
            <ul class="nav navbar-toolbar">
                <li class="dropdown dropdown-inbox">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope-o"></i>
                        <span class="badge badge-primary envelope-badge">9</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media">
                        <li class="dropdown-menu-header">
                            <div>
                                <span><strong>9 New</strong> Messages</span>
                                <a class="pull-right" href="#">view all</a>
                            </div>
                        </li>
                        <li class="list-group list-group-divider scroller" data-height="240px" data-color="#71808f">
                            <div>
                                <a class="list-group-item">
                                    <div class="media">
                                        <div class="media-img">
                                            <img src="{{ asset('admin/img/users/u1.jpg') }}" />
                                        </div>
                                        <div class="media-body">
                                            <div class="font-strong"> </div>Jeanne Gonzalez<small class="text-muted float-right">Just now</small>
                                            <div class="font-13">Your proposal interested me.</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown dropdown-notification">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell-o rel"><span class="notify-signal"></span></i></a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media">
                        <li class="dropdown-menu-header">
                            <div>
                                <span><strong>5 New</strong> Notifications</span>
                                <a class="pull-right" href="javascript:;">view all</a>
                            </div>
                        </li>
                        <li class="list-group list-group-divider scroller" data-height="240px" data-color="#71808f">
                            <div>
                                <a class="list-group-item">
                                    <div class="media">
                                        <div class="media-img">
                                            <span class="badge badge-success badge-big"><i class="fa fa-check"></i></span>
                                        </div>
                                        <div class="media-body">
                                            <div class="font-13">4 task compiled</div><small class="text-muted">22 mins</small></div>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                        <img src="{{asset('images/users/'.Auth::user()->image)}}" />
                        <span></span>{{ Auth::user()->name }}<i class="fa fa-angle-down m-l-5"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"><i class="fa fa-user"></i>Profile</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-cog"></i>Settings</a>
                        <li class="dropdown-divider"></li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"></i>
                            Cerrar sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </li>
            </ul>
            <!-- END TOP-RIGHT TOOLBAR-->
        </div>
    </header>
    <!-- END HEADER-->
    <!-- START SIDEBAR-->
    <nav class="page-sidebar" id="sidebar">
        <div id="sidebar-collapse">
            <div class="admin-block d-flex">
                <div>
                    <img src="{{asset('images/users/'.Auth::user()->image)}}" width="45px" />
                </div>
                <div class="admin-info">
                    <div class="font-strong">{{ Auth::user()->name }}</div><small>Administrator</small></div>
            </div>
            <ul class="side-menu metismenu">
                <li>
                    <a class="active" href="{{ route('dashboard.principal') }}"><i class="sidebar-item-icon fa fa-th-large"></i>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>

                @can('access_permission')
                <li class="heading">ADMINISTRADOR</li>


                <li class="@yield('openAccess')">
                    <a href="#"><i class="sidebar-item-icon fa fa-bookmark"></i>
                        <span class="nav-label">Accesos</span><i class="fa fa-angle-left arrow"></i>
                    </a>
                    <ul class="nav-2-level collapse">
                        @can('list_permission')
                        <li>
                            <a class="@yield('activePermissions')" href="{{ route('permission.index') }}">Permisos</a>
                        </li>
                        @endcan
                        @can('list_role')
                            <li>
                                <a class="@yield('activeRoles')" href="{{ route('role.index') }}">Roles</a>
                            </li>
                        @endcan
                        @can('list_user')
                        <li>
                            <a class="@yield('activeUser')" href="{{ route('user.index') }}">Usuarios</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <li>
                    <a href="#"><i class="sidebar-item-icon fa fa-smile-o"></i>
                        <span class="nav-label">Icons</span>
                    </a>
                </li>

                <li class="heading">PAGES</li>

                <li>
                    <a href="#"><i class="sidebar-item-icon fa fa-envelope"></i>
                        <span class="nav-label">Mailbox</span><i class="fa fa-angle-left arrow"></i>
                    </a>
                    <ul class="nav-2-level collapse">
                        <li>
                            <a href="mailbox.html">Inbox</a>
                        </li>
                        <li>
                            <a href="mail_view.html">Mail view</a>
                        </li>
                        <li>
                            <a href="mail_compose.html">Compose mail</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#"><i class="sidebar-item-icon fa fa-calendar"></i>
                        <span class="nav-label">Calendar</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END SIDEBAR-->
    <div class="content-wrapper">
        <!-- START PAGE CONTENT-->
        <div class="page-heading">
            @yield('header-page')
            {{--<h1 class="page-title">Basic Form</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="index.html"><i class="la la-home font-20"></i></a>
                </li>
                <li class="breadcrumb-item">Basic Form</li>
            </ol>--}}
        </div>
        <div class="page-content fade-in-up">

                @yield('content')

        </div>
        <!-- END PAGE CONTENT-->
        <footer class="page-footer">
            <div class="font-13">Copyright &copy;<script>document.write(new Date().getFullYear());</script> Todos los derechos reservados por <b><a href="https://www.edesce.com/" target="_blank">EDESCE</a></b></div>
            <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
        </footer>
    </div>
</div>

<!-- BEGIN PAGA BACKDROPS-->
<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
    <div class="page-preloader">Loading</div>
</div>
<!-- END PAGA BACKDROPS-->

<!-- CORE PLUGINS-->
<script src="{{ asset('admin/vendors/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/vendors/popper.js/dist/umd/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/vendors/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/vendors/metisMenu/dist/metisMenu.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<!-- PAGE LEVEL PLUGINS-->
<script src="{{ asset('admin/vendors/chart.js/dist/Chart.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/vendors/jvectormap/jquery-jvectormap-us-aea-en.js') }}" type="text/javascript"></script>
<!-- CORE SCRIPTS-->
<script src="{{ asset('admin/js/app.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('toast/jquery.toast.min.js') }}" type="text/javascript"></script>

@yield('scripts')
</body>

</html>