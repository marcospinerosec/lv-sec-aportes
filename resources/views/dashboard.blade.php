<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app_login')

@section('content')

    <div class="container-fluid">
        <div class="row">



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


                    <div class="card-body">
                        @guest
                            <!-- Formulario de inicio de sesión -->
                            @include('auth.login')
                        @else
                            @include('auth.logued')
                        @endguest
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
