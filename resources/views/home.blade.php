@extends('layouts.app')

@section('content')

    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <p class="titulocuarentena" style="margin-right: 20px;" >
            Generación de DDJJ de aportes y boleta de pago
        </p>
        <div class="container mt-5">
            <div class="row" style="border: 1px solid; padding: 10px;">
                <div class="col-md-4 d-flex align-items-center">
                    <label for="empresa" class="mr-2">Empresa:</label>
                    <select class="form-control" id="empresa" name="empresa">
                        <option value=""/>Seleccionar...</option>
                        @foreach($empresas as $empresa)
                            <option value="{{$empresa->IdEmpresa}}"/>{{$empresa->Codigo}} - {{$empresa->NombreReal}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <label for="mes" class="mr-2">Mes:</label>
                    <input type="number" class="form-control" id="mes" name="mes" placeholder="mes">
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <label for="year" class="mr-2">Año:</label>
                    <input type="number" class="form-control" id="year" name="year" placeholder="año">
                </div>
                <div class="col-md-3">

                    <button type="button" class="btn btn-primary btn-block" id="continuarBtn">Continuar</button>
                </div>
                <div id="errorContainer" style="color: red"></div>
            </div>
        </div>
        <div class="container mt-5">

        <div class="row" style="border: 1px solid; padding: 10px;">
            <div class="col-md-8 d-flex align-items-center" id="ListaEmpleadosActual">

        </div>
            <div class="col-md-3 d-flex flex-column align-items-center">
                <div class="mb-2">
                <button type="button" class="btn btn-primary btn-block">Seleccionar otra DDJJ</button>
                </div>
                    <div class="mb-2">
                    <button type="button" class="btn btn-primary btn-block">Editar nómina de empleados y/o remuneraciones</button>
                    </div>
            </div>
        </div>
        </div>
        <div class="container mt-5">
            <div class="row" style="border: 1px solid; padding: 10px;">

                <div class="col-md-8">
                    <div class="d-flex">
                        <label for="txtFOriginal" class="mr-2">Vencimiento original:</label>
                        <input type="text" class="form-control" id="txtFOriginal" name="txtFOriginal" disabled style="width: 100px;margin-left: 25px;">
                    </div>
                    <div class="d-flex">
                        <label for="txtFVencimiento" class="mr-2">Fecha estimada de pago:</label>
                        <input type="text" class="form-control" id="txtFVencimiento" name="txtFVencimiento" style="width: 100px;">
                    </div>

                </div>

                <div class="col-md-3">

                    <button type="button" class="btn btn-primary btn-block" id="continuarBtn">Continuar</button>
                </div>

            </div>
        </div>

        <div class="container mt-5">
            <div class="row" style="border: 1px solid; padding: 10px;">

                <div class="col-md-8 d-flex align-items-center">
                    <table style="width: 100%;">
                        <tbody><tr>

                            <th style="border: 2px solid;width: 400px;">
                                <b>Intereses</b>
                            </th>
                            <th style="border: 2px solid;text-align: right;">
                                <b><input type="hidden" class="form-control" id="txtIntereses" name="txtIntereses">
                                    <span id="spanIntereses"></span>
                                </b>
                            </th>

                        </tr>


                        </tbody></table>
                </div>

                <div class="col-md-3">


                </div>

            </div>
        </div>
        <div class="container mt-5">
            <div class="row" style="border: 1px solid; padding: 10px;background-color: grey">

                <div class="col-md-8 d-flex align-items-center">
                    <input type="hidden" class="form-control" id="txtTotal" name="txtTotal">
                    <span style="font-size: 30px"> Total a pagar:<span id="spanTotal"></span></span>
                </div>

                <div class="col-md-3 d-flex flex-column align-items-center">
                    <div class="mb-2">
                        <button type="button" class="btn btn-primary btn-block">Generar Declaración Jurada</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#continuarBtn").click(function() {
                var empresa = $("#empresa").val();
                var mes = $("#mes").val();
                var year = $("#year").val();
                // Cambiar el texto del botón al inicio de la solicitud
                $("#continuarBtn").text('Cargando...');
                // Realizar una solicitud AJAX al controlador de Laravel
                $.ajax({
                    type: 'POST',
                    url: '{{ url('/procesar') }}',
                    data: {
                        empresa: empresa,
                        mes: mes,
                        year: year,
                        _token: '{{ csrf_token() }}' // Agrega el token CSRF para protección
                    },
                    success: function(response) {
                        // Manejar la respuesta del servidor, si es necesario
                        //console.log('Respuesta del servidor:', response);

                        // Actualizar la tabla con la respuesta HTML
                        $("#ListaEmpleadosActual").html(response.tabla);
                        $("#txtFOriginal").val(response.original);
                        $("#txtFVencimiento").val(response.original);
                        $("#txtIntereses").val(response.intereses);
                        $("#spanIntereses").html(response.intereses);
                        $("#txtTotal").val(response.total);
                        $("#spanTotal").html(response.total);
                        // Limpiar mensajes de error anteriores
                        $('#errorContainer').html('');
                    },
                    error: function(error) {
                        // Manejar los mensajes de error y mostrarlos
                        if (error.responseJSON && error.responseJSON.errors) {
                            var errors = error.responseJSON.errors;
                            var errorMessage = '<ul>';
                            $.each(errors, function (index, value) {
                                errorMessage += '<li>' + value + '</li>';
                            });
                            errorMessage += '</ul>';
                            $('#errorContainer').html(errorMessage);
                        } else {
                            $('#errorContainer').html('Error desconocido. Consulta la consola para obtener más detalles.');
                            //console.log('Error en la solicitud AJAX:', error);
                        }
                    },
                    complete: function() {
                        // Restaurar el texto del botón al finalizar la solicitud
                        $("#continuarBtn").text('Continuar');
                    }
                });
            });

            // Configura el selector de fecha en el campo txtFVencimiento
            $("#txtFVencimiento").datepicker({
                dateFormat: 'yy-mm-dd', // Formato de fecha deseado
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
            });

            // Otros scripts o funciones aquí...

            $("#BtVerListaEmpleados").click(function() {
                alert("Mostrar lista de empleados");
            });
        });
    </script>
@endsection
