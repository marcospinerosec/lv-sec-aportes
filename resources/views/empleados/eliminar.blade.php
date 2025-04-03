@extends('layouts.app')

@section('content')

    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <div class="box box-primary">
        <p class="titulocuarentena" style="margin-right: 20px;" >
            Eliminar empleado
        </p>
            <!-- if validation in the controller fails, show the errors -->

                <div class="alert alert-danger">
                    <ul>

                    <li>Si la causa de la baja se debe a despido, renuncia u otra establecida deberá informarlo dentro de las opciones disponibles indicando la fecha de egreso. </li>
                    <li>De ser necesaria la eliminación del empleado de la nómina por otra causa, deberá solicitarla  a través de correo electrónico a aportesonline@seclaplata.org.ar
                        indicando el motivo. De ser conducente el Sindicato de empleados de comercio de La Plata procesará dicha solicitud. </li>


                    </ul>
                </div>

    <div class="box-footer">

        <a href="{{ route('empleados.index',  array('empresa' => $empresa))}}" class="btn btn-success">Volver</a>
    </div>
    </div>
    </div>

@endsection
