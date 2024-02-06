@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">

            <div style="width: 100%; border: 1px solid #C00; padding: 8px;">
                <div style="width: 100%; text-align: center;">
                    <strong><big>ATENCIÓN</big></strong>
                    <hr color="#FF7878">
                </div>
                <div style="width: 100%; text-align: justify;">
                    <small>
                        Se informa a los Sres. Empleadores que <u>a partir del 01/09/2017</u> las boletas de aportes se generarán con <u>fecha de vencimiento impresa</u>.
                        <br><br>
                        Una vez <u>vencida la misma</u>, la boleta <u>carece de valor</u> para ser cancelada ante los medios de pago habilitados.
                        <br><br>
                        En dicho caso <u>se podrán generar las "rectificativas"</u> que sean necesarias, otorgando la posibilidad de establecer una <u>nueva fecha de vencimiento</u> y el sistema automáticamente <u>calculará los intereses respectivos</u>.
                    </small>
                </div>
            </div>
        <br>

        <div style="width: 100%; border: 1px solid #C00; padding: 8px;">
            <div style="width: 100%; text-align: justify;">
                <strong>RECORDAMOS A UD. QUE EL <u>VENCIMIENTO PARA INGRESAR LOS APORTES SINDICALES RETENIDOS</u> OPERAN CONJUNTAMENTE CON LOS VENCIMIENTOS DEFINIDOS POR AFIP</strong>
            </div>
        </div>



                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div style="width: 100%;">
                            <div class="titulogrande2">
                                <strong>Ingreso Sistema On-Line</strong>
                            </div>

                                <hr>

                            <div style="display: flex; align-items: center; justify-content: space-between; width: 50%">
                                <div style="width: 20%; text-align: right" class="titulogrande2" valign="middle">
                                    Usuario:
                                </div>
                                <div style="width: 10%;">
                                    <input id="email" type="text" size="5" maxlength="5" class="obligatorio @error('email') is-invalid @enderror" style="text-align: right;" name="email" value="{{ old('email') }}" oninput="this.value = this.value.replace(/[^0-9]/g, ''); " required autocomplete="email" autofocus>


                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div style="width: 20%;text-align: right" class="titulogrande2" valign="middle">
                                    Clave:
                                </div>
                                <div style="width: 10%;">
                                    <input id="password" type="password" class="obligatorio @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" size="5" maxlength="5" style="text-align: right;">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div style="width: 20%;" valign="middle">

                                    <button type="submit" class="Boton" style="background-color: rgb(134, 181, 217);">
                                        Iniciar Sesión
                                    </button>
                                </div>
                            </div>

                                <hr>

                            <div colspan="5" class="titulogrande2" valign="middle">
                                Si desea suscribirse al Sistema de DDJJ de empleados On-Line click en: <input type="button" value="Solicitar Usuario" id="BtSolicitud" name="BtSolicitud" class="Boton" onclick="SolicitudUsuario()" onkeydown="if (event.keyCode==13) {SolicitudUsuario();}" style="background-color: rgb(134, 181, 217);">
                            </div>




                                <hr>

                            <div colspan="5" class="titulogrande2" valign="middle">
                                Ante cualquier inconveniente o para realizar consultas sobre el sistema comuníquese a los teléfonos (0221) 427-1767 / 427-1040 / 427-1125 interno 20 (sector APORTES) o a la cuenta de correo electrónico aportesonline@seclaplata.org.ar <input type="button" value="Instructivo del sistema" id="BtInstructivo" name="BtInstructivo" class="Boton" onclick="Instructivo()" onkeydown="if (event.keyCode==13) {Instructivo();}" style="background-color: rgb(134, 181, 217);">
                            </div>

                                <hr>


                        </div>






                    </form>



    </div>

@endsection
