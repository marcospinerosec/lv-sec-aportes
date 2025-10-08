@extends('layouts.app')

@section('content')
    <div style="float: left; border-color: #999999; margin: 0 2%;">
        <h1 class="mt-4">
            Solicitud de modificación de los datos del usuario/Modificar Clave
        </h1>

        <div class="box box-primary">

            <!-- Mostrar errores de validación -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="float" style="font-size:12px; margin:20px; padding:25px; border:1px solid #666; border-radius:10px; box-shadow:0 0 10px #666; background:#0275D8; display: flex;">

                <!-- Formulario principal -->
                <form role="form" action="{{ url('/users/editar')}}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" required maxlength="200" name="nombre" id="nombre" value="{{ old('nombre', $user[0]->Nombre) }}" class="form-control" placeholder="Nombre">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" required maxlength="200" name="telefono" id="telefono" value="{{ old('telefono', $user[0]->Telefono) }}" class="form-control" placeholder="Teléfono">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="text" required maxlength="200" name="email" id="email" value="{{ old('email', $user[0]->EMail) }}" class="form-control" placeholder="E-mail">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label style="width: 100%">Ingrese el código del sec, el cuit y el nombre de las empresas que desea asociar y/o desasociar</label>

                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">

                                    <textarea rows="6" cols="80" id="txtEmpresas" name="txtEmpresas" class="form-control">{{ old('txtEmpresas') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea rows="3" cols="50" id="txtObservaciones" name="txtObservaciones" class="form-control">{{ old('txtObservaciones') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-secondary">Enviar</button>
                        <a href="{{ url('/ddjjs/ddjj')}}" class="btn btn-secondary">Volver</a>
                        <button type="button" class="btn btn-warning" onclick="abrirModal()">Modificar Clave</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de cambio de clave -->
    <div id="modalClave" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
        <div class="modal-content" style="background:#fff; margin:10% auto; padding:20px; width:400px; border-radius:8px; position:relative;">
            <h3>Modificar Clave</h3>
            <form id="formClave">
                @csrf
                <div class="form-group">
                    <label>Nueva clave</label>
                    <input type="password" name="clave" id="clave" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirmar clave</label>
                    <input type="password" name="clave_confirmation" id="clave_confirmation" class="form-control">
                </div>
                <div style="margin-top:15px;">
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal() {
            document.getElementById('modalClave').style.display = 'block';
        }
        function cerrarModal() {
            document.getElementById('modalClave').style.display = 'none';
        }

        document.getElementById('formClave').addEventListener('submit', function(e) {
            e.preventDefault();
            let clave = document.getElementById('clave').value;
            let clave2 = document.getElementById('clave_confirmation').value;

            if (!clave || !clave2 || clave !== clave2) {
                alert('Clave incorrecta, por favor verifique');
                return;
            }

            fetch("{{ route('users.cambiar-clave') }}", {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}','Content-Type':'application/json','Accept': 'application/json'},
                body: JSON.stringify({clave: clave, clave_confirmation: clave2})
            })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                    cerrarModal();
                    // Limpiar campos
                    document.getElementById('clave').value = '';
                    document.getElementById('clave_confirmation').value = '';
                })
                .catch(err => console.error(err));
        });
    </script>
@endsection
