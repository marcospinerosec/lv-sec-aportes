@extends('layouts.app')

@section('content')
    <div style="float: left; margin-right: 20px; border-color: #999999; margin-top: 20px;">
        <p align="center">
        <span class="Estilo12Cuarentena"><strong><br>
        NUEVO CANAL DE PAGO<br>POR CBU
        </strong></span>
        </p>
        <hr style="width: 300px; border-color:#EC7063;">
        <strong>
        </strong>
        <p></p>
        <p class="titulocuarentena" style="margin-right: 20px;" align="justify">
            Atento las consultas recibidas acerca de las dificultades que se han presentado para efectuar el pago de los aportes sindicales, se ha tomado la decisión de abrir como canal alternativo de pago, y a modo de excepción mientras dure la cuarentena, la transferencia bancaria. A los efectos de poder imputar correctamente el pago, solicitamos a las empresas tengan a bien adjuntar a través de nuestro e-mail "contaduria@seclaplata.org.ar", la boleta generada por el sistema del Sindicato y el comprobante de la transferencia efectuada.
        </p>
        <br>
        <p class="titulocuarentenadatos">
            Detalle de CBU Banco Provincia
        </p>
        <p style="text-indent: 50px;" class="titulocuarentenadatos">
            Titular: SIN DE EMP DE COMERCIO
        </p>
        <p style="text-indent: 50px;" class="titulocuarentenadatos">
            Número de Cuenta: 5200-50123/7
        </p>
        <p style="text-indent: 50px;" class="titulocuarentenadatos">
            CUIL/CUIT: 30-53068231-1
        </p>
        <p style="text-indent: 50px;" class="titulocuarentenadatos">
            CBU: 0140188801520005012372
        </p>
        <p style="text-indent: 50px;" class="titulocuarentenadatos">
            CBU Alias: seclaplata
        </p>
        <br>
        <p class="titulocuarentena">
            Saludos cordiales.
        </p>
        <p class="titulocuarentena">
            Departamento de Aportes y Fiscalización
        </p>
        <p class="titulocuarentena">
            Sindicato de Empleados de Comercio de la Plata
        </p>
        <p class="Estilo12">
        </p>
        <br>
        <br>
        <hr>

        <p>
        <span class="Estilo12"><strong><br>
        Nuevo Medio de Pago <img src="{{ url('images/link.jpg') }}" width="60px" height="60px"></strong></span><strong><br>
            </strong>
        </p>
        <p class="titulonegro">
            <span class="textonegro">Se encuentra habilitada la realización de los pagos de aportes a través de Red Link.</span>
        </p>
        <p class="titulonegro">
            <span class="textonegro">A fin de adherir al servicio deberá seleccionar las opciones, tomando como ejemplo Banca internet del Banco Provincia, que en la siguiente imagen se muestran:</span>
        </p>
        <img src="{{ url('images/nuevomediopago.png') }}">
        <p class="titulonegro">
            <span class="textonegro">En Código Link Pagos: se debe ingresar el CÓDIGO DE EMPRESA utilizando siempre 9 (digitos) dígitos.</span>
        </p>
        <p class="titulonegro">
            <span class="textonegro">Ejemplos:</span>
        </p>
        <p class="titulonegro">
            <span class="textonegro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.Si su Código de empresa es 10.123 se debe ingresar "000010123".</span>
        </p>
        <p class="titulonegro">
            <span class="textonegro">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.Si su Código de empresa es 5.362 se debe ingresar "000005362".</span>
        </p>
        <p class="titulonegro">
            <strong><a style="cursor:pointer" onclick="MM_openBrWindow('InstructivoRedLink.pdf','','scrollbars=yes,width=429,height=450')">VER MAS...</a> </strong>
        </p>

        <br>
        <span class="Estilo12"><br>
    Novedades</span>
        <p></p>
        <blockquote>
            <p class="Estilo12"><strong><a href="#" class="tituloboldosecac" onclick="MM_openBrWindow('pop1.html','','scrollbars=yes,width=429,height=450')">Aportes sindicales obligatorios <br>
                        (Art. 100 C.C.T. 130/75)</a><br>
                </strong><span class="titulonegro">CONVENIO COLECTIVO 130/75 ARTICULO 100º <br>
        HOMOLOGACION DEL ACUERDO: DISP. (D. N. R. T.) <br>
        4803 DE FECHA 4/7/91<br>
        <br>
    </span><strong><a href="#" class="tituloboldosecac" onclick="MM_openBrWindow('pop2.html','','scrollbars=yes,width=429,height=450')">Cuota sindical por afiliación </a></strong><span class="titulonegro"><br>
        RESOLUCIÓN D. N. A. S. Nº 22/89<br>
        <br>
    </span><strong><a href="#" class="tituloboldosecac" onclick="MM_openBrWindow('pop3.html','','scrollbars=yes,width=429,height=450')">Inscripción en el sindicato / requisitos </a></strong></p>
        </blockquote>
        <blockquote>
            <strong><a href="#" class="tituloboldosecac" onclick="MM_openBrWindow('pop4.html','','scrollbars=yes,width=429,height=450')">Agente de retención</a> <br>
            </strong><span class="titulonegro">LEY 24642 - Sancionada: 8/5/96 - Promulgada: <br>
        28/5/1996 Publicada BO: 30/5/1996</span>
        </blockquote>
        <blockquote>
            <p><strong><a href="#" class="tituloboldosecac" onclick="MM_openBrWindow('pop5.html','','scrollbars=yes,width=429,height=450')">Obligatoriedad de entrega de certificados laborales</a> </strong></p>
        </blockquote>
        <blockquote>
            <p class="Estilo12"><strong><a href="#" class="tituloboldosecac" onclick="MM_openBrWindow('pop6.html','','scrollbars=yes,width=429,height=450')">Sanciones por empleo no registrado</a> </strong> </p>
        </blockquote>
        <p class="titulonegro"><a href="empresa.html#up" class="tituloboldorg"><span class="Estilo12">//////////////////////////////////////////////////////////////////////////////////////////////////////<br>
    </span></a><br>
            <br>
        </p>
    </div>

@endsection
