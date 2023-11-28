<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <!-- Aquí puedes agregar elementos del menú lateral -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                Opción 1
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                Opción 2
                            </a>
                        </li>
                        <!-- Agrega más opciones según sea necesario -->
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Cabecera -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <!-- Puedes agregar elementos adicionales en la cabecera -->
                    </div>
                </div>

                <!-- Contenido del panel de control -->
                <div class="card">
                    <div class="card-header">Panel de Control</div>

                    <div class="card-body">
                        @guest
                            <!-- Formulario de inicio de sesión -->
                            @include('auth.login')
                        @else
                            <!-- Contenido para usuarios autenticados -->
                            ¡Bienvenido al panel de control, {{ auth()->user()->name }}!
                        @endguest
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
