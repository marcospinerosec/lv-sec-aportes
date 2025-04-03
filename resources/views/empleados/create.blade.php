@extends('layouts.app')

@section('content')

    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <div class="box box-primary">
            <p class="titulocuarentena" style="margin-right: 20px;" >
                Nuevo empleado
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

                <form role="form" action="{{ url('/empleados/save')}}" method="post">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="empresa" id="empresa" value="{{$empresa}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="cuil">CUIL</label>
                                    <input type="text" required maxlength="200" name="cuil" id="cuil" value="{{old('cuil')}}" class="form-control" placeholder="Cuil">
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" required maxlength="200" name="nombre" id="nombre" value="{{old('nombre')}}" class="form-control" placeholder="Nombre">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="ingreso">Ingreso</label>
                                    <input type="text" required maxlength="200" name="ingreso" id="ingreso" value="{{old('ingreso')}}" class="form-control" placeholder="Ingreso">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="afiliado">Afiliado</label>
                                    <select required class="form-control" id="afiliado" name="afiliado" onchange="cambiarAfiliado()">>

                                        <option value="0" {{ (old('afiliado') == 0) ? 'selected' : '' }}>
                                            NO
                                        </option>
                                        <option value="1" {{ (old('afiliado') == 1) ? 'selected' : '' }}>
                                            SI
                                        </option>


                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="importeArt100">Rem.Art.100</label>
                                    <input type="number" required maxlength="200" name="importeArt100" id="importeArt100" value="{{old('importeArt100')}}" class="form-control" placeholder="Rem.Art.100">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="novedades">Novedades</label>
                                    <select class="form-control js-example-basic-single" id="novedades" name="novedades" onchange="cambiarNovedad()">
                                        <option value=""/>Seleccionar...</option>
                                        @foreach($tiposNovedades as $novedad)
                                            <option value="{{$novedad['IdTipoNovedad']}}" {{ (old('novedades')) ? 'selected' : '' }}>
                                                {{$novedad['Novedad']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="categoria">Categoria</label>
                                    <select required class="form-control js-example-basic-single" id="categoria" name="categoria">
                                        <option value=""/>Seleccionar...</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{$categoria['IdCategoria']}}" {{ (old('categoria')) ? 'selected' : '' }}>
                                                {{$categoria['Descripcion']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group" id="divEgreso">
                                    <label for="egreso">Egreso</label>
                                    <input type="text" maxlength="200" name="egreso" id="egreso" value="{{old('egreso')}}" class="form-control" placeholder="Egreso">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group" id="divCuota">
                                    <label for="importeCuotaAfil">Rem.Cuota Afil.</label>
                                    <input type="number"  maxlength="200" name="importeCuotaAfil" id="importeCuotaAfil" value="{{old('importeCuotaAfil')}}" class="form-control" placeholder="Rem.Art.100">
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
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
            // Configura el selector de fecha en el campo txtFVencimiento
            $("#ingreso").datepicker({
                dateFormat: 'yy-mm-dd', // Formato de fecha deseado
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
            });
            $("#egreso").datepicker({
                dateFormat: 'yy-mm-dd', // Formato de fecha deseado
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
            });
        });
        function cambiarNovedad(){
            var novedad = $("#novedades").val();
            //console.log(novedad);
            if(novedad){
                $("#divEgreso").show();
            }
            else{
                $("#divEgreso").hide();
            }
        }
        function cambiarAfiliado(){
            var afiliado = $("#afiliado").val();
            //console.log(novedad);
            if(afiliado!=0){
                $("#divCuota").show();
            }
            else{
                $("#divCuota").hide();
            }
        }
        cambiarNovedad();
        cambiarAfiliado();
    </script>
@endsection
