@extends('layouts.app')

@section('content')
    @include('layouts.menu') <!-- Incluye el menú horizontal -->

    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <p class="titulocuarentena" style="margin-right: 20px;" >
            Generación de DDJJ de aportes y boleta de pago
        </p>
        <div id="errorContainer" style="color: red"></div>
        <div class="container mt-5">
            <div class="row" style="border: 1px solid; padding: 10px;">
                <div class="col-md-4 d-flex align-items-center">
                    <label for="empresa" class="mr-2">Empresa:</label>
                    <select class="form-control" id="empresa" name="empresa">
                        <option value=""/>Seleccionar...</option>
                        @foreach($empresas as $empresa)
                            <option value="{{$empresa['IdEmpresa']}}" {{ session('filtro_empresa') == $empresa['IdEmpresa'] ? 'selected' : '' }}>{{$empresa['Codigo']}} - {{$empresa['NombreReal']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <label for="mes" class="mr-2">Mes:</label>
                    <input type="number" class="form-control" id="mes" name="mes" placeholder="mes" value="{{session('filtro_mes')}}">
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <label for="year" class="mr-2">Año:</label>
                    <input type="number" class="form-control" id="year" name="year" placeholder="año" value="{{session('filtro_year')}}">
                </div>
                <div class="col-md-3">

                    <button type="button" class="btn btn-primary btn-block" id="continuarBtn">Continuar</button>
                </div>

            </div>
        </div>
        <div class="container mt-5">

        <div class="row" style="border: 1px solid; padding: 10px;">
            <div class="col-md-8 d-flex align-items-center" id="ListaEmpleadosActual">

        </div>
            <div class="col-md-3 d-flex flex-column align-items-center">
                <div class="mb-2">
                <!--<button type="button" class="btn btn-primary btn-block">Seleccionar otra DDJJ</button>-->
                </div>
                    <div class="mb-2">
                    <button type="button" class="btn btn-primary btn-block" id="btnEditarEmpleados">Editar nómina de empleados y/o remuneraciones</button>
                    </div>
            </div>
        </div>
        </div>
        <div class="container mt-5">
            <div class="row" style="border: 1px solid; padding: 10px;">

                <div class="col-md-8">
                    <div class="d-flex" style="margin-bottom: 2%;">
                        <label for="txtFOriginal" class="mr-2">Vencimiento original:</label>
                        <input type="text" class="form-control" id="txtFOriginal" name="txtFOriginal" disabled style="width: 100px;margin-left: 5%;">
                    </div>
                    <div class="d-flex">
                        <label for="txtFVencimiento" class="mr-2">Fecha estimada de pago:</label>
                        <input type="text" class="form-control" id="txtFVencimiento" name="txtFVencimiento" style="width: 100px;margin-left: 0.5%;">
                    </div>

                </div>

                <div class="col-md-3">

                    <button type="button" class="btn btn-primary btn-block" id="continuarVencimiento">Continuar</button>
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
                    <span style="font-size: 30px"> Total a pagar: <span id="spanTotal"></span></span>
                </div>

                <div class="col-md-3 d-flex flex-column align-items-center">
                    <div class="mb-2">
                        <input type="hidden" class="form-control" id="existeDeclaracion" name="existeDeclaracion">
                        <button type="button" class="btn btn-primary btn-block" id="generarBtn">Generar Declaración Jurada</button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function formatDate(dateString) {
                // Asegurarse de que la fecha sea en formato 'yyyy-mm-dd' sin hora
                let date = new Date(dateString + 'T00:00:00'); // Añadir una hora para evitar que el tiempo cambie la fecha
                let day = String(date.getDate()).padStart(2, '0');
                let month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses van de 0 a 11
                let year = String(date.getFullYear()).slice(-4); // Obtener los últimos 2 dígitos del año

                return `${day}/${month}/${year}`;
            }
            function parseFecha(fechaStr) {
                var partes = fechaStr.split("/");
                return new Date(partes[2], partes[1] - 1, partes[0], 23, 59, 59); // Año, Mes (0-11), Día
            }
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
                        $("#txtFOriginal").val(formatDate(response.original));
                        $("#txtFVencimiento").val(formatDate(response.vencimiento));
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
                dateFormat: 'dd/mm/yy', // Formato de fecha deseado
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
            });

            $("#btnEditarEmpleados").click(function() {
                // Obtener el valor del parámetro empresa (puedes cambiar esto según tus necesidades)
                var empresa = $("#empresa").val();

                if(empresa){
                    // Construir la URL con el parámetro empresa
                    var nuevaURL = "{{ route('empleados.index') }}?empresa=" + encodeURIComponent(empresa);

                    // Redirigir a la nueva URL
                    window.location.href = nuevaURL;
                }
                else{
                    alert('Debe seleccionar una empresa');
                }


            });

            $("#continuarVencimiento").click(function() {
                //var txtVencimiento = $("#txtVencimiento").val();
                if ($("#txtFVencimiento").val()!="") {
                    var dtFechaActual = new Date();
                    var fecha = $("#txtFVencimiento").val();
                    var fechao = $("#txtFOriginal").val();


                    var fechaVencimiento = parseFecha(fecha);
                    var fechaOriginal = parseFecha(fechao);
                    //console.log(fechaVencimiento + ' --- ' + fechaOriginal+' ---- '+dtFechaActual);
                    if (fechaVencimiento >= dtFechaActual && fechaVencimiento >= fechaOriginal) {
                        var empresa = $("#empresa").val();
                        var mes = $("#mes").val();
                        var year = $("#year").val();
                        var txtVenc = $("#txtFVencimiento").val();
                        var partes = txtVenc.split("/"); // Se divide en [día, mes, año]
                        var venc = partes[2].slice(-4) + "-" + partes[1] + "-" + partes[0]; // Formato yy-mm-dd

                       // console.log(venc); // Mostrará la fecha en formato yyyy-mm-dd
                        $("#continuarVencimiento").text('Cargando...');
                        $.ajax({
                            type: 'POST',
                            url: '{{ url('/procesar') }}',
                            data: {
                                empresa: empresa,
                                mes: mes,
                                year: year,
                                venc: venc,
                                _token: '{{ csrf_token() }}' // Agrega el token CSRF para protección
                            },
                            success: function(response) {
                                // Manejar la respuesta del servidor, si es necesario
                                //console.log('Respuesta del servidor:', response);

                                // Actualizar la tabla con la respuesta HTML
                                $("#ListaEmpleadosActual").html(response.tabla);
                                $("#txtFOriginal").val(formatDate(response.original));
                                $("#txtFVencimiento").val(formatDate(response.vencimiento));
                                $("#txtIntereses").val(response.intereses);
                                $("#spanIntereses").html(response.intereses);
                                $("#txtTotal").val(response.total);
                                $("#spanTotal").html(response.total);
                                $("#existeDeclaracion").val(response.existeDeclaracion);
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
                                $("#continuarVencimiento").text('Continuar');
                            }
                        });
                    }
                    else {
                        alert("Fecha de Pago incorrecta, por favor verifique");
                    }
                }

                // Cambiar el texto del botón al inicio de la solicitud

            });


            $("#generarBtn").click(function() {
                //var txtVencimiento = $("#txtVencimiento").val();
                if ($("#txtFVencimiento").val()!="") {


                    var dtFechaActual = new Date();
                    var fecha = $("#txtFVencimiento").val();
                    var fechao = $("#txtFOriginal").val();



                    var fechaVencimiento = parseFecha(fecha);
                    var fechaOriginal = parseFecha(fechao);
                    //console.log(fechaVencimiento + ' --- ' + fechaOriginal+' ---- '+dtFechaActual);
                    if (fechaVencimiento >= dtFechaActual && fechaVencimiento >= fechaOriginal) {
                        var empresa = $("#empresa").val();
                        var mes = $("#mes").val();
                        var year = $("#year").val();

                        var txtVenc = $("#txtFVencimiento").val();
                        var partes = txtVenc.split("/"); // Se divide en [día, mes, año]
                        var venc = partes[2].slice(-4) + "-" + partes[1] + "-" + partes[0]; // Formato yy-mm-dd

                        var txtVencO = $("#txtFOriginal").val();
                        var partes = txtVencO.split("/"); // Se divide en [día, mes, año]
                        var vencOri = partes[2].slice(-4) + "-" + partes[1] + "-" + partes[0]; // Formato yy-mm-dd

                        var intereses = $("#txtIntereses").val();
                        var existeDeclaracion = $("#existeDeclaracion").val();
                        var continua = 1;
                        if (existeDeclaracion!=0){
                            if (!confirm("¿Ya existe una declaración jurada para la empresa y periodo seleccionado, es una rectificación de la misma?")){
                                return false;
                            }

                        }

                        hoy = new Date();
                        anio = hoy.getFullYear();
                        mesActual = hoy.getMonth() + 1;
                        if ($("#mes").val()<1 || $("#mes").val()>12){
                            alert("Mes incorrecto");
                            return;
                        }
                        //if ($("#txtAnio").val()> anio || $("#txtAnio").val()<= (anio - 2))
                        if ($("#year").val()> anio){
                            alert("Año incorrecto");
                            return;
                        }

                        pi=parseFloat($("#year").val()) * 100 + parseFloat($("#mes").val());
                        pa=parseFloat(anio) * 100 + parseFloat(mesActual);

                        if (pa<pi){
                            alert("Período incorrecto, por favor verifique");
                            return;
                        }

                        $("#generarBtn").text('Cargando...');
                        $.ajax({
                            type: 'POST',
                            url: '{{ url('/generar') }}',
                            data: {
                                empresa: empresa,
                                mes: mes,
                                year: year,
                                venc: venc,
                                vencOri: vencOri,
                                intereses: intereses,
                                _token: '{{ csrf_token() }}' // Agrega el token CSRF para protección
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Obtener la URL del PDF
                                    var pdfUrl = response.pdf_url;

                                    // Abrir el PDF en una nueva ventana
                                    window.open(pdfUrl);
                                } else {
                                    console.error('Error al obtener el PDF:', response.message);
                                }
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
                                $("#generarBtn").text('Generar Declaración Jurada');
                            }
                        });
                    }
                    else {
                        alert("Fecha de Pago incorrecta, por favor verifique");
                    }
                }

                // Cambiar el texto del botón al inicio de la solicitud

            });

            $("#BtVerListaEmpleados").click(function() {
                alert("Mostrar lista de empleados");
            });
            $("#continuarBtn").trigger("click");
        });

    </script>
@endsection
