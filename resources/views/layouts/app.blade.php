<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/stile_sec.css') }}" rel="stylesheet">
    <!-- Agrega este enlace para incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


    <link media="all" type="text/css" rel="stylesheet" href="http://www.seclaplata.org.ar/css/animate.css">
    <link media="all" type="text/css" rel="stylesheet" href="http://www.seclaplata.org.ar/css/menu-20191120.css">
    <link media="all" type="text/css" rel="stylesheet" href="http://www.seclaplata.org.ar/css/style-20190606.css">

</head>
<body>
    <div id="app">
        <nav id="nav-menu" class="navbar navbar-expand-md navbar-light shadow-sm z-depth-0" style="margin-bottom: 20px;">
            <div class="container">


                <div class="collapse navbar-collapse nav-wrapper" id="navbarSupportedContent">
                    <div class="container-menu">
                        <div id="contenido-menu">
                            <img id="logo-sec" src="http://www.seclaplata.org.ar/assets/img/sys/logo-sec.png" alt="">
                            <hr id="menu-linea-vertical-blanca" class="z-depth-0">
                            <h6 id="menu-titulo-1" style="color: #fff; font-weight: 400">SINDICATO DE</h6>
                            <h6 id="menu-titulo-2" style="color: #fff; font-weight: 400">EMPLEADOS DE COMERCIO</h6>

                            <!-- Left Side Of Navbar -->
                            <ul class="nav navbar-nav navbar-left">

                                <li class="dropdown">


                                </li>
                            </ul>


                </div>
                    </div>
                    <div>
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}" style="color: #fff">{{ __('Login') }}</a>
                                    </li> -->
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color: #fff">
                                        {{ utf8_encode(Auth::user()->UsuarioNT) }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <!-- Área específica del dashboard -->
            <div class="container-fluid">
                <div class="row">
                    <!-- Sidebar del dashboard -->
                    <!-- Sidebar -->
                    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                        <div class="position-sticky">
                            <!-- Aquí puedes agregar elementos del menú lateral -->
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/empresa') }}" style="display: flex; align-items: center;">
                                        <img src="{{ url('images/mensaje.jpg') }}" height="49" width="37" style="margin-right: 10px;">
                                        Información importante para los empleadores
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/formulario') }}" style="display: flex; align-items: center;">
                                        <img src="{{ url('images/formu.jpg') }}" height="41" width="45" style="margin-right: 10px;">
                                        Formulario de inscripción al S.E.C.
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/login') }}" style="display: flex; align-items: center;">
                                        <img src="{{ url('images/icon_net.png') }}" height="41" width="45" style="margin-right: 10px;">
                                        Sistema de DDJJ de empleados e impresión de boletas de pago On-Line
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/pago') }}" style="display: flex; align-items: center;">
                                        <img src="{{ url('images/pago.jpg') }}" height="35" width="45" style="margin-right: 10px;">
                                        Lugares de pago
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/calendario') }}" style="display: flex; align-items: center;">
                                        <img src="{{ url('images/calendario.jpg') }}" height="48" width="37" style="margin-right: 10px;">
                                        Calendario de ingreso de aportes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="mailto:contaduria@seclaplata.org.ar" style="display: flex; align-items: center;">
                                        <img src="{{ url('images/mail.jpg') }}" height="30" width="39" style="margin-right: 10px;">
                                        Consultas
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>


                    <!-- Contenido principal del dashboard -->
                    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                        @yield('content')
                    </main>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
