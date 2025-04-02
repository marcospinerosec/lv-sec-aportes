<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SEC La Plata</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('css/styles_dashboard.css') }}" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <!-- Bootstrap JS (with Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/scripts.js') }}"></script>
</head>
<body class="sb-nav-fixed">
@php
    $current_path = request()->path();
@endphp
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand" href="#page-top"><img class="img-fluid" src="{{ asset('assets/img/LogoSEC4.jpg')}}" alt="Pricipal" /></a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

    <div STYLE="font-family: Arial; color: #ffffff; font-weight: bold;  width: 100%; text-align: center;"> SINDICATO DE EMPLEADOS DE COMERCIO - LA PLATA</div>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                <li><a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">    </div>
                    <a class="nav-link {{ $current_path === 'ddjjs/ddjj' ? 'active' : '' }}" href="{{ url('/ddjjs/ddjj') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        GENERACIÓN DDJJ
                    </a>

                    <div class="sb-sidenav-menu-heading">    </div>
                    <a class="nav-link" href="charts.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        BOLETA DE PAGO SIN DDJJ
                    </a>
                    <a class="nav-link" href="tables.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        BOLETA DE PAGO DE ACTAS
                    </a>
                    <a class="nav-link" href="tables.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        CARGA DE FORMULARIO 931
                    </a>
                    <a class="nav-link" href="tables.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        BOLETA DE PAGO DE ACTAS
                    </a>


                    <div class="sb-sidenav-menu-heading">    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        CONSULTAS
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="layout-static.html">PRESENTACIONES ANTERIORES</a>
                            <a class="nav-link" href="layout-sidenav-light.html">Otra ?</a>
                        </nav>
                    </div>

                    <div class="sb-sidenav-menu-heading">    </div>
                    <a class="nav-link" href="tables.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        DATOS DE USUARIO
                    </a>

                </div>
            </div>


        </nav>
    </div>


    <div id="layoutSidenav_content">
        <main>
            @yield('content')
        </main>
    </div>
</div>


<footer class="bg-light text-center text-lg-start">

    <!--<div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                Copyright &copy; 2024-{{ Carbon\carbon::now()->year }} <a href="http://www.seclaplata.org.ar" target="_blank">Sindicato de Empleados de Comercio.</a> Todos los derechos reservados.
            </div>-->
    <!-- Copyright Section-->
    <div class="copyright py-4 text-center">
        <!--<div class="container"><small>Desarrollado por: <a href="http://www.ti54.com.ar" target="_Blank">www.ti54.com.ar</a></small></div>-->
        Copyright &copy; 2024-{{ Carbon\carbon::now()->year }} <a href="http://www.seclaplata.org.ar" target="_blank">Sindicato de Empleados de Comercio.</a> Todos los derechos reservados.
    </div>
</footer>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
</body>
</html>
