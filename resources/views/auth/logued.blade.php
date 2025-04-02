@extends('layouts.app_login')

@section('content')


    <div class="row justify-content-center">


        <div class="container d-flex align-items-center flex-column">


            <!-- Masthead Subheading-->
            <p class="masthead-heading font-weight-light mb-0">Sindicato de empleados de comercio - La Plata</p>
            <!-- Icon Divider-->
            <div class="divider-custom divider-light">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
        </div>




        <!-- Portfolio Item 1-->
        <div class="col-md-6 col-lg-4 mb-5">
            <?php //print_r(Auth::user()) ?>
            <p class="masthead-subheading font-weight-light mb-0">Bienvenido/a: {{ Auth::user()->Nombre }}</p>
            <p class="masthead-subheading font-weight-light mb-0">Email: {{ Auth::user()->Email }}</p>
            <br><br>
            <p class="masthead-subheading font-weight-light mb-0"><small>Mantenga sus datos actualizados.<br>De haber cambios le pedimos que se comunique a sistemas@seclaplata.org.ar indicando los mismos.</small></p>
            <hr>
            <div class="text-center mt-4">

                <a class="btn btn-xl btn-outline-light" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    CERRAR SESIÓN
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a class="btn btn-xl btn-outline-light" href="{{ route('home') }}">
                    <!--<i class="fas fa-download me-2"></i>-->
                    Ingresar al sistema
                </a>
            </div>

        </div>




        <!-- Portfolio Item 2-->
        <div class="col-md-6 col-lg-1 mb-5">
            <!-- Separador-->
        </div>



        <!-- Portfolio Item 2-->
        <div class="col-md-6 col-lg-4 mb-5">
            <div class="portfolio-item mx-auto" >
                RECORDAMOS A UD. QUE EL VENCIMIENTO PARA INGRESAR LOS APORTES SINDICALES RETENIDOS OPERAN CONJUNTAMENTE CON LOS VENCIMIENTOS DEFINIDOS POR AFIP
                <hr>
                Ante cualquier inconveniente o para realizar consultas sobre el sistema comuníquese a los teléfonos <br>   (0221) 427-1767 / (221) 427-1040
                <br> (221) 427-1125 interno 20 (sector APORTES)  <br> o a aportesonline@seclaplata.org.ar
                <hr>
                <div class="text-center mt-4">

                    <a class="btn btn-xl btn-outline-light" href="">
                        <!--<i class="fas fa-download me-2"></i>-->
                        Instructivo del sistema
                    </a>
                </div>
            </div>
        </div>
    </div>

    <hr>










@endsection
