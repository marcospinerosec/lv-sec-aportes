@extends('layouts.app')

@section('content')
    @include('layouts.menu') <!-- Incluye el menú horizontal -->
    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <div class="box box-primary">
            <p class="titulocuarentena" style="margin-right: 20px;" >
                Importar empleados
            </p>
            <!-- if validation in the controller fails, show the errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row" style="border: 1px solid; padding: 10px;">

                <form role="form" action="{{ url('/empleados/procesar')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="empresa" id="empresa" value="{{$empresa}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{ route('empleados.formato')}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Formato de importación y categorías</a>
                                </div>
                            </div>


                        </div>



                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="erchivo">Archivo</label>
                                    <input type="file" id="archivo" name="archivo" class="form-control" placeholder="">
                                </div>
                            </div>


                        </div>



                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('empleados.index',  array('empresa' => $empresa))}}" class="btn btn-success">Volver</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
