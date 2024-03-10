@extends('layouts.app')

@section('content')
    @include('layouts.menu') <!-- Incluye el menú horizontal -->
    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <p class="titulocuarentena" style="margin-right: 20px;" >
            Empleados
        </p>

        <hr/>
        <div class="row" style="border: 1px solid; padding: 10px;">
            <div class="col-md-20 d-flex align-items-center">
                <form class="form-inline">
                <label for="empresa" class="mr-2">Empresa:</label>
                <select class="form-control" id="empresa" name="empresa" onchange="this.form.submit()">
                    <option value=""/>Seleccionar...</option>
                    @foreach($empresas as $empresa)
                        <option value="{{$empresa['IdEmpresa']}}" @if(isset($_GET['empresa']) && $empresa['IdEmpresa']==$_GET['empresa']) selected="selected" @endif>
                            {{$empresa['Codigo']}} - {{$empresa['NombreReal']}}
                        </option>

                    @endforeach
                </select>
                </form>
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


            <a class="btn btn-primary " style="float: left;" href="{{ url('/empleados/create?empresa='. (isset($_GET['empresa'])? $_GET['empresa'] : '')) }}"><i class="fa fa-plus"></i> Nuevo </a>
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

                <tr>

                    <td>{{$empleado['Cuil']}}</td>
                    <td>{{$empleado['Nombre']}}</td>
                    <td>{{$empleado['Categoria']}}</td>



                    <td>{{($empleado['Afiliado'])?'SI':'NO'}}</td>
                    <td>{{($empleado['FechaIngreso'])?date('d/m/Y', strtotime($empleado['FechaIngreso'])):''}}</td>

                    <td>{{$empleado['Novedad']}}</td>
                    <td>{{($empleado['FechaEgreso'])?date('d/m/Y', strtotime($empleado['FechaEgreso'])):''}}</td>
                    <td>{{ number_format($empleado['ImporteArt100'], 2, ',', '.') }}</td>
                    <td>{{ number_format($empleado['ImporteCuotaAfil'], 2, ',', '.') }}</td>

                    <td>

                        <!--<form role="form" action = "{{ url('/empleados/eliminar')}}/{{ $empleado['IdEmpleado']}}" method="post"  enctype="multipart/form-data">-->
                            {{method_field('DELETE')}}
                            {{ csrf_field() }}

                            <!--<a class="btn btn-sm btn-default"  href="{{ url('/empleados/detalle')}}/{{ $empleado['IdEmpleado']}}"><i class="fa fa fa-eye"></i></a>-->
                            <a class="btn btn-sm btn-default" href="{{ url('/empleados/edit')}}/{{ $empleado['IdEmpleado']}}"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-sm btn-default" href="{{ url('/empleados/eliminar')}}/{{ $_GET['empresa']}}"><i class="fa fa-trash"></i></a>
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
            $('#tEmpleados').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
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
