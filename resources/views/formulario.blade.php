@extends('layouts.app')

@section('content')
    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <p><span class="Estilo12"><br>Formulario </span><br>
        <span class="titulonegro"><br>
    Descargue el formulario de   inscripción, complete los datos de su empresa y preséntelo en la sede del   Sindicato de empleados de Comercio, calle 6 nº 682 &nbsp;4to piso, Sector   Fiscalización y Aportes, firmado por el titular con la documentación que a   continuación se detalla:</span></p>
        <p class="titulonegro">Para empresas con Titular Único o   Sociedad de hecho fotocopia de documento/s y domicilio/s real/es de titular/es   responsable/s.</p>
        <p><span class="titulonegro">En los casos de tratarse de Razón   Social, se deberá obligatoriamente acompañar fotocopia de CONTRATO   SOCIAL</span>.</p>
        <p class="titulonegro">En todos los casos, se deberá   presentar fotocopias de habilitación municipal (ley 24642), formulario de   inscripción en A.F.I.P., formulario 931 de A.F.I.P., formulario de jornada legal (ley 11544) y registro de   altas de empleados en A.F.I.P. (Altas tempranas).
        </p><p><a href="DeclaracionJuradaAportesV2.xls" class="tituloboldorg"><img src="{{ url('images/formu.jpg') }}" width="45" height="41" border="0" align="middle"> Descargue aquí  el formulario de inscripción</a>
        </p>
        <br class="Estilo9" align="right">
    </div>

@endsection
