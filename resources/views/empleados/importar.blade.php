@extends('layouts.app')

@section('content')

    <div style="float: left; border-color: #999999; margin: 0 2%;">

            <h1 class="mt-4">
                Importar empleados
            </h1>
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
        <div id="float" style="font-size:12px;
	 	margin:20px;
		padding:25px 25px 25px;
		border:1px solid #666;
		border-radius:10px;
		box-shadow:0 0 10px #666;
		background:#0275D8; display: flex;">

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
                        <button type="submit" class="btn btn-secondary">Guardar</button>
                        <a href="{{ route('empleados.index',  array('empresa' => $empresa))}}" class="btn btn-secondary">Volver</a>
                    </div>
                </form>

            </div>

    </div>

@endsection
