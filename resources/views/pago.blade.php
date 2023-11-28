@extends('layouts.app')

@section('content')
    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">


        <p><span class="Estilo12"><br>
      Lugares de pago  </span></p>
        <p class="titulonegro">Para una mayor comodidad de las empresas y/o estudios   contables, las boletas del SEC La   Plata se pueden abonar en :</p>
        <p>&nbsp;</p>
        <ul type="disc" class="titulonegro">
            <li>BANCO DE   LA PROVINCIA   DE BUENOS AIRES (Todas las sucursales) </li>
            <li>Provincia NET Pagos</li>
            <li>SEDE DEL SINDICATO   (calle 6 nº 682 3º piso Dto. tesorería de lunes a viernes de   09.00 a   17.00 horas). </li>
        </ul>

        <p align="right" class="Estilo9">
        </p>
        <br><p><span class="Estilo12">
      Nuevo Medio de Pago</span><strong><br>
            </strong></p>
        <p class="titulonegro"><span class="textonegro">Se encuetra habilitada la realización de los pagos de aportes a través de Red Link .</span></p>
        <p class="titulonegro"><span class="textonegro">A fin de adherir al servicio deberá seleccionar las opciones, tomando como ejemplo Banca internet del Banco Provicncia,  que en la siguiente imagen se muestran:</span></p>
        <img src="images/nuevomediopago.png">
        <p class="titulonegro"><span class="textonegro">En Código Link Pagos: se debe ingresar el CÓDIGO DE EMPRESA utilizando siempre 9 (nueve) dígitos.</span></p>
        <p class="titulonegro"><span class="textonegro">Ejemplos:</span></p>
        <p class="titulonegro"><span class="textonegro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.Si su Código de empresa es 10.123  se debe ingresar "000010123". </span></p>
        <p class="titulonegro"><span class="textonegro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.Si su Código de empresa es 5.362 se debe ingresar "000005362".  </span></p>

        <p class="titulonegro"><strong><a style="cursor:pointer" onclick="MM_openBrWindow('InstructivoRedLink.pdf','','scrollbars=yes,width=429,height=450')">VER MAS...</a> </strong></p>
    </div>


@endsection
