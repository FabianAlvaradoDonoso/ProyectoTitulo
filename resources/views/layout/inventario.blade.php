<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gestion de Archivos | UTEM</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('Adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('Adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('Adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('Adminlte/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('Adminlte/dist/css/skins/_all-skins.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('Adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
        href="{{ asset('Adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('Adminlte/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('animate/animate.css') }}">
    <link rel="icon"
        href="https://lh3.googleusercontent.com/proxy/6Rz-5n2Rq1yvt443cZ6_QR6X1WIzxhnAWiUKePszyUebVDDPt_Fcc3mUoVjPNmZBTCXh_0RPJWWiDfKGqgpmWuDp9MJkQJmNZ-xneyExwP0Dlsu6bUlYvlMV0Euw--jhOR1nNOjYdqIhfgwN">

    {{-- Webpack Mix --}}
    {{-- <link rel="stylesheet" href="{{asset('css/app.css')}}"> --}}

    <!-- Select2 -->
    {{-- <link rel="stylesheet" href="{{asset('Adminlte/bower_components/select2/dist/css/select2.min.css')}}"> --}}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>

    </style>
</head>

<body class="hold-transition skin-green sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>A</b>U</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Archivos </b>UTEM</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Esconder</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            @if (Auth::check())
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ asset('Adminlte/dist/img/user.png') }}" class="user-image"
                                        alt="User Image">
                                    <span class="hidden-xs">{{ auth()->user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="{{ asset('Adminlte/dist/img/user.png') }}" class="img-circle"
                                            alt="User Image">

                                        <p>
                                            {{ auth()->user()->email }}
                                            <small>Miembro desde {{ session('date') }}</small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="{{ route('edit_user') }}"
                                                class="btn btn-default btn-flat">Perfil</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Cerrar
                                                Sesión</a>
                                        </div>
                                    </li>
                                </ul>
                            @else
                                <a href="{{ route('login') }}" class="dropdown-toggle">
                                    <span class="hidden-xs">Acceder</span>
                                </a>
                                {{-- <span class="hidden-xs">No login</span> --}}
                            @endif
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MENU</li>

                    @if (Auth::check() && auth()->user()->is_admin == 1)
                        <li>
                            <a href="{{ route('admin.home') }}">
                                <i class="fa fa-dashboard"></i> <span>Inicio</span>
                            </a>
                        </li>
                    @else
                        @if (Auth::check() && auth()->user()->is_admin == 0)
                            <li>
                                <a href="{{ route('home') }}">
                                    <i class="fa fa-dashboard"></i> <span>Inicio</span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i class="fa fa-dashboard"></i> <span>Inicio</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    <li>
                        <a href="{{ url('/career') }}">
                            <i class="fa fa-university"></i> <span>Carreras</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/course') }}">
                            <i class="fa fa-book"></i> <span>Ramos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/document') }}">
                            <i class="fa fa-archive"></i> <span>Documentos</span>
                        </a>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            @yield('content')

        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; 2021 <a href="https://www.utem.cl/">
                    <font color=green>UTEM</font>
                </a>.</strong> Todos los derechos reservados.
            <div class="pull-right hidden-xs">
                <a class="btn btn-sm btn-social-icon btn-github" href="https://github.com/FabianAlvaradoDonoso/ProyectoTitulo" target="_blank"><i class="fa fa-github"></i></a>
                <a class="btn btn-sm btn-social-icon btn-google" href="mailto:fabian.alvaradod@utem.cl" target="_blank"><i class="fa fa-envelope"></i></a>
                <b>Version</b> 1.0.0
            </div>
        </footer>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{ asset('Adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('Adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('Adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script
        src="{{ asset('Adminlte/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js') }}"
        charset="UTF-8"></script>
    <!-- DataTables -->
    <script src="{{ asset('Adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('Adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('Adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('Adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('Adminlte/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('Adminlte/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('Adminlte/dist/js/demo.js') }}"></script>

    <script src="{{ asset('bootstrap-notify/bootstrap-notify.js') }}"></script>

    <!-- Select2 -->
    {{-- <script src="{{asset('Adminlte/bower_components/select2/dist/js/select2.full.min.js')}}"></script> --}}

    {{-- Webpack Mix --}}
    {{-- <script src="{{asset('js/app.js')}}"></script> --}}


    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree()
        })
        $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
            $("#success-alert").slideUp(500);
        });
        $("#error-alert").fadeTo(2000, 500).slideUp(500, function() {
            $("#error-alert").slideUp(500);
        });
    </script>
    @yield('scripts')
</body>

</html>
