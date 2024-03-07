@extends('layouts.app')

@section('content')
    @include('layouts.menu') <!-- Incluye el menú horizontal -->
    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <div class="box box-primary">
        <p class="titulocuarentena" style="margin-right: 20px;" >
            Editar empleado
        </p>
            <div class="row" style="border: 1px solid; padding: 10px;">
        <form role="form" action = "{{ url('/empleados/edit')}}/{{$empleado[0]['IdEmpleado']}}" method="post">
            {{method_field('PUT')}}
            {{ csrf_field() }}

            <div class="box-body">
                <div class="form-group">
                    <label for="cuil">CUIL</label>
                    <input type="text" required  maxlength="200" name="cuil" id="cuil"  value="{{$empleado[0]['Cuil']}}" class="form-control" placeholder="Cuil">
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" required  maxlength="200" name="nombre" id="nombre"  value="{{$empleado[0]['Nombre']}}" class="form-control" placeholder="Nombre">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categoria">Categoria</label>
                            <select class="form-control js-example-basic-single" id="categoria" name="categoria">
                                <option value=""/>Seleccionar...</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{$categoria['IdCategoria']}}" @if($categoria['IdCategoria']==$empleado[0]['IdCategoria']) selected="selected" @endif>
                                        {{$categoria['Descripcion']}}
                                    </option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('empleados.index')}}" class="btn btn-success">Volver</a>
            </div>
        </form>
            </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });


    </script>
@endsection
