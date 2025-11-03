@extends('layouts.app')

@section('content')

    <div style="float: left; border-color: #999999; margin: 0 2%;">

            <h1 class="mt-4">
                FORMULARIOS PRESENTADOS
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

        <!-- Mostrar errores flash (por ejemplo período inválido) -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div id="float" style="font-size:12px;
	 	margin:20px;
		padding:25px 25px 25px;
		border:1px solid #666;
		border-radius:10px;
		box-shadow:0 0 10px #666;
		background:#0275D8; display: flex;">

            @if(count($archivos) > 0)
                <table width="800px" class="tablesorter" align="center" style="color: #ffffff;
  font-size: 1.25rem;
  font-family: sans-serif;">
                    <thead>
                    <tr>
                        <th>Detalle</th>
                        <th>Fecha</th>
                        <th>Ver Documento</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($archivos as $form)
                        <tr>
                            <td>{{ $form->DETALLE }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->FECHAALTA)->format('d/m/Y H:i:s') }}</td>

                            <td style="cursor:pointer; text-align: center" onclick="window.open('{{ route('formularios.verArchivo', ['nombre' => trim($form->NOMBRE)]) }}', '_blank')">
                                <i class="fa fa-eye"></i>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>No hay formularios presentados.</p>
            @endif



        </div>

    </div>

@endsection
