@extends('layouts.app')

@section('content')

    <div style="float: left; border-color: #999999; margin: 0 2%;">
        <h1 class="mt-4">
            Consultas - DDJJ anteriores
        </h1>


        <div class="row" style="border: 1px solid; padding: 10px;background:#0275D8;display: flex;">

                <form class="form-inline" style="display: flex; align-items: center; gap: 10px;">
                    <font style="color: #ffffff; font-size: 1.25rem; font-family: sans-serif; margin-right: 10px;">Empresa:</font>
                    <select class="form-control" id="empresa" name="empresa" style="width:300px;">
                        <option value="">Seleccionar...</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->IdEmpresa }}"
                                {{ request()->get('empresa') == $empresa->IdEmpresa ? 'selected' : '' }}>
                                {{ $empresa->Codigo }} - {{ $empresa->NombreReal }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Select Año --}}
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label for="year" style="color: #ffffff; font-size: 1.25rem; font-family: sans-serif;">Año:</label>
                        <select class="form-control" id="year" name="year" style="width: 120px;" required>
                            <option value="">Seleccionar...</option>
                            @for ($y = now()->year; $y >= 2010; $y--)
                                <option value="{{ $y }}"
                                    {{ request()->get('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div style=" padding-left: 10px; position: relative;  float: left;    height: auto;">
                        <button type="submit" class="btn btn-secondary" id="continuarBtn">
                            Consultar
                        </button>
                    </div>
                </form>





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

                <div style="float: left; font-weight: bold; padding-top: 6px;">

                    Las boletas de pago poseen el número de envío en 0.<br>
                    Los historiales importados del sistema anterior poseen el número de envío en -1.
                </div>


        </div>
        <br><br>
        <div class="box-body responsive-table">

        <div id="lista_item_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                <div class="col-sm-12">
                    <table id="tAnteriores" class="display" cellspacing="0" width="100%">
                        <thead>
                        <!--<tr>
                            <td colspan="3" bgcolor="white">
                                &nbsp;
                            </td>
                            <td colspan="2" align="center">
                                Art.100
                            </td>
                            <td colspan="2" align="center">
                                Afil.
                            </td>
                            <td colspan="6" bgcolor="white">
                                &nbsp;
                            </td>
                        </tr>-->
                        <tr>
                            <th >
                                Mes
                            </th>
                            <th >
                                Envío
                            </th>
                            <th>
                                Fecha Generación
                            </th>
                            <th >
                                Cant.
                            </th>
                            <th >
                                Imp.
                            </th>
                            <th >
                                Cant.
                            </th>
                            <th >
                                Imp.
                            </th>
                            <th >
                                O.Conceptos
                            </th>
                            <th >
                                Intereses Pago F.Término
                            </th>
                            <th >
                                Total
                            </th>
                            <th >
                                Vencimiento
                            </th>
                            <th>Acción</th>

                        </tr>
                        </thead>
                        <tbody>
            @foreach($anteriores as $anterior)


                <tr>

                    <td>{{$anterior->Mes}}</td>
                    <td>{{$anterior->NumeroEnvio}}</td>
                    <td>{{($anterior->FechaGenerada)?date('d/m/Y', strtotime($anterior->FechaGenerada)):''}}</td>
                    <td>{{$anterior->CantArt100}}</td>
                    <td>{{ number_format($anterior->ImporteArt100, 2, ',', '.') }}</td>
                    <td>{{$anterior->CantAfi}}</td>
                    <td>{{ number_format($anterior->ImporteCuotaAfi, 2, ',', '.') }}</td>
                    <td>{{ number_format($anterior->Intereses, 2, ',', '.') }}</td>
                    <td>{{ number_format($anterior->InteresesPagoFueraTermino, 2, ',', '.') }}</td>
                    <td>{{ number_format($anterior->ImporteArt100+$anterior->ImporteCuotaAfi+$anterior->Intereses+$anterior->InteresesPagoFueraTermino, 2, ',', '.') }}</td>
                    <td> @if (!empty($anterior->anterioresAnt))
                            @foreach ($anterior->anterioresAnt as $ant)
                                {{ $ant->FechaVencimiento ? date('d/m/Y', strtotime($ant->FechaVencimiento)) : '' }}
                                <br><br><br>
                            @endforeach
                        @endif

                        {{-- Mostrar la fecha de vencimiento principal --}}
                        {{ $anterior->FechaVencimiento ? date('d/m/Y', strtotime($anterior->FechaVencimiento)) : '' }}</td>




                    <td >

                        {{-- 🔍 Ver DDJJ --}}
                        @if ($anterior->NumeroEnvio != 0)
                            <button class="btn btn-sm btn-default" title="Ver DDJJ"
                                    onclick="verDDJJ({{ $anterior->Mes }}, {{ $anterior->NumeroEnvio }})">
                                <i class="fa fa-eye"></i>
                            </button>
                        @else
                            &nbsp;
                        @endif

                        {{-- 🖨️ Reimprimir Boletas anteriores --}}
                        @if (!empty($anterior->anterioresAnt))
                            @foreach ($anterior->anterioresAnt as $ant)
                                <br>
                                <button class="btn btn-sm btn-default mt-1" title="Reimprimir boleta anterior"
                                        onclick="reimprimirBoleta(
                        {{ $anterior->Mes }},
                        {{ $anterior->NumeroEnvio }},
                        1
                    )">
                                    <i class="fa fa-print"></i>
                                </button>
                            @endforeach
                        @endif

                        {{-- 🖨️ Reimprimir Boleta actual --}}
                        <button class="btn btn-sm btn-default mt-1" title="Reimprimir boleta actual"
                                onclick="reimprimirBoleta(
                {{ $anterior->Mes }},
                {{ $anterior->NumeroEnvio }},
                0
            )">
                            <i class="fa fa-print"></i>
                        </button>

                        {{-- 🧾 Generar nueva boleta --}}
                        @if ($anterior->NumeroEnvio != -1 && strtotime($anterior->FechaGenerada) > strtotime('2017-10-20'))
                            @php
                                $tot = $anterior->ImporteArt100 + $anterior->ImporteCuotaAfi + $anterior->Intereses;
                            @endphp
                            <button class="btn btn-sm btn-default mt-1" title="Generar nueva boleta"
                                    onclick="generarBoleta(
                    {{ $anterior->Mes }},
                    {{ $anterior->NumeroEnvio }},
                    '{{ $anterior->AnioFG }}',
                    '{{ $anterior->MesFG }}',
                    '{{ $anterior->diaFG }}',
                    '{{ $anterior->HoraFG }}',
                    '{{ $anterior->MinutoFG }}',
                    '{{ $anterior->SegundoFG }}',
                    '{{ $anterior->FechaVencimientoOriginal }}',
                    '{{ $anterior->FechaVencimiento }}',
                    '{{ $tot }}'
                )">
                                <i class="fa fa-file-invoice-dollar"></i>
                            </button>
                        @endif

                    </td>




                </tr>
            @endforeach
                        </tbody>
                    </table>



    </div>
            </div>
        </div>
    </div>
    </div>
    {{-- Modal dinámico para Ver DDJJ --}}
    <div id="fondo" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none" style="z-index:1050;"></div>

    <div id="DDJJEmpleados" class="position-fixed bg-white rounded shadow-lg p-3 d-none"
         style="z-index:1100; width:80%; height:80%; top:10%; left:10%; overflow:auto;">
        <button type="button" class="btn btn-sm btn-secondary position-absolute top-0 end-0 m-2" onclick="cerrarModal()">
            <i class="fa fa-times"></i>
        </button>
        <div id="DDJJEmpleadosContent" class="p-3 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var spanishTranslation = {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            };
            var table = $('#tAnteriores').DataTable({
                "language": spanishTranslation
            });
            $('[data-toggle="tooltip"]').tooltip();

            table.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
        function confirmDel(url){
//var agree = confirm("¿Realmente desea eliminarlo?");
            if (confirm("¿Realmente deseas eliminar estos datos?"))
                window.location.href = url;
            else
                return false ;
        }


        function verDDJJ(mes, envio) {
            const idEmpresa = document.getElementById('empresa').value;
            const anio = document.getElementById('year').value;

            if (!idEmpresa || !anio) {
                alert('Debe seleccionar una empresa y un año antes de continuar.');
                return;
            }

            // Mostrar modal y fondo
            $('#fondo').removeClass('d-none');
            $('#DDJJEmpleados').removeClass('d-none');
            $('#DDJJEmpleadosContent').html(`
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                            </div>
                        `);

            // URL Laravel que devuelve la vista parcial con la DDJJ
            const url = `{{ url('/ddjjs/ver') }}/${idEmpresa}/${anio}/${mes}/${envio}`;

            // Cargar contenido dinámicamente en el modal
            $('#DDJJEmpleadosContent').load(url, function(response, status) {
                if (status === 'error') {
                    $('#DDJJEmpleadosContent').html('<div class="alert alert-danger">Error al cargar la DDJJ.</div>');
                }
            });
        }

        function cerrarModal() {
            $('#fondo, #DDJJEmpleados').addClass('d-none');
            $('#DDJJEmpleadosContent').html('');
        }

        function reimprimirBoleta(mes, numeroEnvio, flag) {
            const idEmpresa = document.getElementById('empresa').value;
            const anio = document.getElementById('year').value;

            if (!idEmpresa || !anio) {
                alert('Debe seleccionar una empresa y un año antes de continuar.');
                return;
            }

            // Podés adaptar esta URL según tu ruta Laravel
            const url = `/reimprimir-boleta/${idEmpresa}/${anio}/${mes}/${numeroEnvio}/${flag}`;

            window.open(url, '_blank'); // abre en una nueva pestaña
        }

        function generarBoleta(mes, numeroEnvio, flag) {
            const idEmpresa = document.getElementById('empresa').value;
            const anio = document.getElementById('year').value;

            if (!idEmpresa || !anio) {
                alert('Debe seleccionar una empresa y un año antes de continuar.');
                return;
            }

            const url = `/generar-boleta/${idEmpresa}/${anio}/${mes}/${numeroEnvio}/${flag}`;
            window.open(url, '_blank');
        }

    </script>
@endsection
