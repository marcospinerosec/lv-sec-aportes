<!-- resources/views/layouts/hmenu.blade.php -->
@php
    $current_path = request()->path();
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-light">


    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            @if(!$esAdmin)
                <li class="nav-item {{ (str_starts_with(request()->path(), 'empleados')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/empleados/index') }}">Empleados</a>
                </li>

                <li class="nav-item {{ $current_path === 'ddjjs/ddjj' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/ddjjs/ddjj') }}">Generación de DDJJ y Boleta de pago</a>
                </li>
                @if($imprimeBoleta)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Boleta de pago sin DDJJ</a>
                    </li>
                @endif
                @if($esActa)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Boleta de pago de actas</a>
                    </li>
                @endif
                <li class="nav-item {{ $current_path === 'ddjjs/anteriores' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/ddjjs/anteriores') }}">Consulta DDJJ anteriores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Importar historial</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Datos del usuario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Instructivo sistema</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Formato archivo importación empleados</a>
                </li>
                @if(auth()->user()->IdUsuario==3)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Fomulario 930</a>
                    </li>
                @endif
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Administración de usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Cronograma de vencimientos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Baja de empleados por excepción</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Empresas exceptuadas m&iacutenimo cuota afiliación</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Administración Importe m&iacutenimo cuota afiliación</a>
                </li>
            @endif
            <!-- Puedes agregar más elementos del menú aquí -->
        </ul>
    </div>
</nav>

