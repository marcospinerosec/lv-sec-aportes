@extends('layouts.app')

@section('content')

    <div style="float: left; border-color: #999999; margin: 0 2%;">
        <h1 class="mt-4">
            Empleados
        </h1>


        <div class="row" style="border: 1px solid; padding: 10px;background:#0275D8;display: flex;">

                <form class="form-inline" style="display: flex; align-items: center; gap: 10px;">
                    <font style="color: #ffffff; font-size: 1.25rem; font-family: sans-serif; margin-right: 10px;">Empresa:</font>
                <select class="form-control" id="empresa" name="empresa" onchange="this.form.submit()" style="width:300px;">
                    <option value=""/>Seleccionar...</option>
                    @foreach($empresas as $empresa)
                        <option value="{{$empresa->IdEmpresa}}" @if(isset($_GET['empresa']) && $empresa->IdEmpresa==$_GET['empresa']) selected="selected" @endif>
                            {{$empresa->Codigo}} - {{$empresa->NombreReal}}
                        </option>

                    @endforeach
                </select>
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


            <a class="btn btn-secondary " style="float: left;margin-right: 5px;" href="{{ url('/empleados/create?empresa='. (isset($_GET['empresa'])? $_GET['empresa'] : '')) }}"><i class="fa fa-plus"></i> Nuevo </a>
            <a class="btn btn-secondary " style="float: left;margin-right: 5px;" href="{{ url('/empleados/importar/'. (isset($_GET['empresa'])? $_GET['empresa'] : '')) }}"><i class="fa fa-upload"></i> Importar </a>
            <a class="btn btn-secondary" style="float: left;" href="{{ url('/ddjjs/ddjj') }}">
                <i class="fa fa-arrow-left"></i> Volver
            </a>
            @if($minimo)
                <div style="float: right; font-weight: bold; padding-top: 6px;">

                    Importe mínimo ${{number_format($minimo, 2, ',', '.') }}
                </div>
            @endif

        </div>
        <br><br>
        <div class="box-body responsive-table">

        <div id="lista_item_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                <div class="col-sm-12">
                    <table id="tEmpleados" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>

                            <th>CUIL</th>
                            <th>Nombre</th>
                            <th>Categoría</th>

                            <th>Afiliado</th>
                            <th>Ingreso</th>
                            <th>Novedad</th>
                            <th>Egreso</th>
                            <th>Rem.Art.100</th>
                            <th>Rem.Cuota Afil.</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
            @foreach($empleados as $empleado)


                <tr
                    @php
                        $year = session('filtro_year');
                        $mes = session('filtro_mes');
                        $anioMesIngreso = '';
                        if (!empty($empleado->FechaIngreso)) {
                            $anioMesIngreso = date('Y', strtotime($empleado->FechaIngreso)) * 100 + date('n', strtotime($empleado->FechaIngreso));
                        }
                        $anioMesActual = $year * 100 + $mes;
                    @endphp

                    @if(
                        $empleado->Afiliado == 1 &&
                        $empleado->ImporteCuotaAfil < $minimo &&

                        (!$empleado->Novedad) &&
                        ($anioMesIngreso != $anioMesActual)
                    )
                        style="background-color: #f8d7da;"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="El importe de la cuota afiliado es menor al mínimo (${{$minimo}})"
                    @endif
                >

                    <td>{{$empleado->Cuil}}</td>
                    <td>{{$empleado->Nombre}}</td>
                    <td>{{$empleado->Categoria}}</td>



                    <td>{{($empleado->Afiliado)?'SI':'NO'}}</td>
                    <td>{{($empleado->FechaIngreso)?date('d/m/Y', strtotime($empleado->FechaIngreso)):''}}</td>

                    <td>{{$empleado->Novedad}}</td>
                    <td>{{($empleado->FechaEgreso)?date('d/m/Y', strtotime($empleado->FechaEgreso)):''}}</td>
                    <td>{{ number_format($empleado->ImporteArt100, 2, ',', '.') }}</td>
                    <td>{{ number_format($empleado->ImporteCuotaAfil, 2, ',', '.') }}</td>

                    <td>

                        <!--<form role="form" action = "{{ url('/empleados/eliminar')}}/{{ $empleado->IdEmpleado}}" method="post"  enctype="multipart/form-data">-->
                            {{method_field('DELETE')}}
                            {{ csrf_field() }}

                            <!--<a class="btn btn-sm btn-default"  href="{{ url('/empleados/detalle')}}/{{ $empleado->IdEmpleado}}"><i class="fa fa fa-eye"></i></a>-->
                            <a class="btn btn-sm btn-default" href="{{ url('/empleados/edit')}}/{{ $empleado->IdEmpleado}}"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-sm btn-default" href="{{ url('/empleados/eliminar')}}/{{ $_GET['empresa']    }}"><i class="fa fa-trash"></i></a>
                            <!--<button onclick='if(confirmDel() == false){return false;}' class="btn btn-sm btn-default" type="submit"><i class="fa fa-trash"></i></button>-->
                        </form>

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
            var table = $('#tEmpleados').DataTable({
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




    </script>
@endsection
