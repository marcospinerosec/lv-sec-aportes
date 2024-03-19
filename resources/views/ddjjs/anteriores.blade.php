@extends('layouts.app')

@section('content')
    @include('layouts.menu') <!-- Incluye el menú horizontal -->
    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <p class="titulocuarentena" style="margin-right: 20px;" >
            DDJJ anteriores
        </p>

        <hr/>
        <div id="errorContainer" style="color: red"></div>
        <div class="row" style="border: 1px solid; padding: 10px;">
            <div class="col-md-4 d-flex align-items-center">
                <label for="empresa" class="mr-2">Empresa:</label>
                <select class="form-control" id="empresa" name="empresa">
                    <option value=""/>Seleccionar...</option>
                    @foreach($empresas as $empresa)
                        <option value="{{$empresa['IdEmpresa']}}"/>{{$empresa['Codigo']}} - {{$empresa['NombreReal']}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-center">
                <label for="year" class="mr-2">Año:</label>
                <input type="number" class="form-control" id="year" name="year" placeholder="año">
            </div>
            <div class="col-md-3">

                <button type="button" class="btn btn-primary btn-block" id="continuarBtn">Continuar</button>
            </div>




        </div>

        @if (\Session::has('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{!! \Session::get('error') !!}</li>
                </ul>
            </div>
        @endif
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <ul>
                    <li>{!! \Session::get('success') !!}</li>
                </ul>
            </div>
        @endif

        <br>
        <div class="box-header with-border">

            <p>Las boletas de pago poseen el número de envío en 0.<br>
                Los historiales importados del sistema anterior poseen el número de envío en -1.</p>

            <!--<a class="btn btn-primary " style="float: left;margin-right: 5px;" href="{{ url('/empleados/create?empresa='. (isset($_GET['empresa'])? $_GET['empresa'] : '')) }}"><i class="fa fa-plus"></i> Nuevo </a>
            <a class="btn btn-info " style="float: left;" href="{{ url('/empleados/importar/'. (isset($_GET['empresa'])? $_GET['empresa'] : '')) }}"><i class="fa fa-upload"></i> Importar </a>-->
        </div>
        <br><br>
        <div class="box-body responsive-table">

        <div id="lista_item_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                <div class="col-sm-12">
                    <table id="tAnteriores" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>

                            <th>Mes</th>
                            <th>Envío</th>
                            <th>Generación</th>
                            <th>Cant.Art.100</th>
                            <th>Imp.Art.100</th>
                            <th>Cant.Afi</th>
                            <th>Imp.Cuota Afi</th>
                            <th>O.Conceptos</th>
                            <th>Intereses</th>
                            <th>Total</th>
                            <th>Vencimiento</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>



    </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#tAnteriores').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        });
        $("#continuarBtn").click(function() {
            var empresa = $("#empresa").val();

            var year = $("#year").val();
            // Cambiar el texto del botón al inicio de la solicitud
            $("#continuarBtn").text('Cargando...');
            // Realizar una solicitud AJAX al controlador de Laravel
            $.ajax({
                type: 'POST',
                url: '{{ url('/ddjjs/listar') }}',
                data: {
                    empresa: empresa,

                    year: year,
                    _token: '{{ csrf_token() }}' // Agrega el token CSRF para protección
                },
                success: function(response) {


                    // Limpiar mensajes de error anteriores
                    $('#errorContainer').html('');

                    // Limpiar el contenido anterior de la tabla
                    var dataTable = $('#tAnteriores').DataTable();
                    dataTable.clear().draw(); // Limpiar y redibujar la tabla

// Recorrer los datos recibidos del servidor
                    $.each(response, function(index, ddjj) {
                        // Agregar una nueva fila a la tabla con los datos del servidor
                        dataTable.row.add([
                            ddjj.mes,
                            ddjj.envio,
                            ddjj.generada,
                            ddjj.CantArt100,
                            ddjj.ImporteArt100,
                            ddjj.CantAfi,
                            ddjj.ImporteCuotaAfi,
                            ddjj.Intereses,
                            ddjj.InteresesPagoFueraTermino,
                            ddjj.total,
                            ddjj.vencimientos
                            // Agregar más columnas según los atributos que tengas
                        ]).draw(); // Redibujar la tabla después de agregar la fila
                    });
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

    </script>
@endsection
