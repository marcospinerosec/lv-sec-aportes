@extends('layouts.app')

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

            <p class="masthead-subheading font-weight-light mb-0">Ingreso Sistema On-Line</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
            <div class="input-group-text">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input required id="email" type="text" autofocus="autofocus" class="form-control" name="email" placeholder="Usuario" maxlength="50" class="obligatorio @error('email') is-invalid @enderror" value="{{ old('email') }}">
				@error('email')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
            <div class="row">
                <br>
            </div>


            <div class="input-group-text">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input required id="password" type="password" class="form-control" name="password" placeholder="Password" maxlength="50" class="obligatorio @error('password') is-invalid @enderror">
                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>


            <div class="text-center mt-4">
                <button type="submit" class="btn btn-xl btn-outline-light">
                    <!--<i class="fas fa-download me-2"></i>-->
                    Ingresar
                </button>
            </div>
            </form>




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

            </div>
        </div>
    </div>

    <hr>
    <div class="text-center mt-4">
        <a class="btn btn-xl btn-outline-light" href="">
            <!--<i class="fas fa-download me-2"></i>-->
            Solicitar usuario
        </a>
        <a class="btn btn-xl btn-outline-light" href="">
            <!--<i class="fas fa-download me-2"></i>-->
            Instructivo del sistema
        </a>
    </div>









@endsection
