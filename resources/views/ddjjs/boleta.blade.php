@extends('layouts.app')

@section('content')
     <!-- Incluye el menú horizontal -->


             <div class="container-fluid px-4">
                 <h1 class="mt-4">Generación e Impresión de Boleta de Pago</h1>
                 <ol class="breadcrumb mb-4">
                     <li class="breadcrumb-item active">Complete el formulario</li>
                 </ol>
                 <div id="errorContainer" style="color: red"></div>
                 <div id="float" style="font-size:12px;
	 	margin:20px;
		padding:25px 25px 25px;
		border:1px solid #666;
		border-radius:10px;
		box-shadow:0 0 10px #666;
		background:#0275D8;">
                     <div style="display: flex;">
                     <div style=" float: left;  position: relative;     height: auto;">
                         <img src="{{ asset('assets/img/Boton1.png')}} ">
                     </div>
                     <div style="display: flex; align-items: center; padding-top: 11px; padding-left: 10px; margin-left: 30px;">
                         <font style="color: #ffffff; font-size: 1.25rem; font-family: sans-serif; margin-right: 10px;">Empresa:</font>
                         <select class="form-control" id="empresa" name="empresa" style="width:300px;" required>
                             <option value="">Seleccionar...</option>
                             @foreach($empresas as $empresa)
                                 <option value="{{ $empresa->IdEmpresa }}" {{ session('filtro_empresa') == $empresa->IdEmpresa ? 'selected' : '' }}>
                                     {{ $empresa->Codigo }} - {{ $empresa->NombreReal }}
                                 </option>
                             @endforeach
                         </select>

                         {{-- Select Mes --}}
                         <div style="display: flex; align-items: center; gap: 10px; margin: 0 20px;">
                             <label for="mes" style="color: #ffffff; font-size: 1.25rem; font-family: sans-serif;">Mes:</label>
                             <select class="form-control" id="mes" name="mes" style="width: 120px;" required>
                                 <option value="">Seleccionar...</option>
                                 @php
                                     $meses = [
                                         1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
                                         4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                                         7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
                                         10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                     ];
                                     $mesActual = session('filtro_mes') ?? now()->month;
                                 @endphp
                                 @foreach($meses as $num => $nombre)
                                     <option value="{{ $num }}" {{ $mesActual == $num ? 'selected' : '' }}>
                                         {{ $nombre }}
                                     </option>
                                 @endforeach
                             </select>
                         </div>

                         {{-- Select Año --}}
                         <div style="display: flex; align-items: center; gap: 10px;">
                             <label for="year" style="color: #ffffff; font-size: 1.25rem; font-family: sans-serif;">Año:</label>
                             <select class="form-control" id="year" name="year" style="width: 120px;" required>
                                 <option value="">Seleccionar...</option>
                                 @php
                                     $yearActual = session('filtro_year') ?? now()->year;
                                 @endphp
                                 @for ($y = now()->year; $y >= 2010; $y--)
                                     <option value="{{ $y }}" {{ $yearActual == $y ? 'selected' : '' }}>
                                         {{ $y }}
                                     </option>
                                 @endfor
                             </select>
                         </div>
                     </div>


                     <div style=" padding-top: 16px; padding-left: 10px;  margin-left: 30px;  position: relative;  float: left;    height: auto;">
                         <button class="btn btn-secondary" id="continuarBtn">
                             Aceptar
                         </button>
                     </div>
                     </div>
                     <div id="divAnterior" style="margin-top: 20px; display: none">

                     </div>
                 </div>
                 <div id="paso2" style="font-size:12px;
	 	margin:20px;
		padding:25px 25px 25px;
		border:1px solid #666;
		border-radius:10px;
		box-shadow:0 0 10px #666;
		background:#0275D8; display: none;">
                     <div style=" float: left;  position: relative;     height: auto;">
                         <img src="{{ asset('assets/img/Boton2.png')}}" >
                     </div>
                     <div id="ListaEmpleadosActual" style=" padding-top: 11px; padding-left: 10px;  margin-left: 30px;  position: relative;  float: left;    height: auto;">

                     </div>
                     <div style=" padding-top: 11px; padding-left: 10px;  margin-left: 30px;  position: relative;  float: left;    height: auto;">

                         <div style=" padding-top: 11px; padding-left: 10px;  margin-left: 30px;  position: relative;  float: left;    height: auto;">

                             <button class="btn btn-secondary" id="btnContinuar2">
                                 CONTINUAR
                             </button><br><br><br>
                             <button class="btn btn-secondary" id="btnCancelar">
                                 CANCELAR
                             </button>
                         </div>

                     </div>
                 </div>
                 <div id="paso3" style="font-size:12px;
	 	margin:20px;
		padding:25px 25px 25px;
		border:1px solid #666;
		border-radius:10px;
		box-shadow:0 0 10px #666;
		background:#0275D8; display: none;">
                     <div style=" float: left;  position: relative;     height: auto;">
                         <img src="{{ asset('assets/img//Boton3.png')}}" >
                     </div>
                     <div id="divFechas" style="padding-top: 11px; padding-left: 10px; margin-left: 30px; position: relative; float: left; height: auto;">
                         <div style="display: flex; align-items: center; margin-bottom: 10px;">
                             <font style="color: #ffffff; font-size: 1.25rem; font-family: Arial; margin-right: 50px;">Vencimiento original:</font>
                             <input class="form-control" type="text" style="width:150px" id="txtFOriginal" name="txtFOriginal" disabled>
                         </div>
                         <div style="display: flex; align-items: center; margin-bottom: 10px;">
                             <font style="color: #ffffff; font-size: 1.25rem; font-family: Arial; margin-right: 10px;">Fecha estimada de pago:</font>
                             <input class="form-control" type="text" style="width:150px" id="txtFVencimiento" name="txtFVencimiento">
                         </div>
                     </div>


                     <div style=" padding-top: 11px; padding-left: 10px;  margin-left: 30px;  position: relative;  float: left;    height: auto;">

                         <button class="btn btn-secondary" id="continuarVencimiento">
                             CONTINUAR
                         </button>
                     </div>
                     <div style=" padding-top: 11px; padding-left: 10px;  margin-left: 30px;  position: relative;  float: left;    height: auto;">

                         <button class="btn btn-secondary" id="btnCancelar3">
                             CANCELAR
                         </button>
                     </div>

                 </div>
                 <div id="paso4" style="font-size:12px;
	 	margin:20px;
		padding:25px 25px 25px;
		border:1px solid #666;
		border-radius:10px;
		box-shadow:0 0 10px #666;
		background:#0275D8; display: none;">
                     <div style=" float: left;  position: relative;     height: auto;">
                         <img src="{{ asset('assets/img/Boton4.png')}}" >
                     </div>
                     <div id="divIntereses" style=" padding-top: 11px; padding-left: 10px;  margin-left: 30px;  position: relative;  float: left;    height: auto;">
                     <table id="tableIntereses" style="width: 100%;color: white;font-size: 20px;">
                         <tbody><tr>

                             <th style="border: 2px solid black;width: 400px;">
                                 <b>Intereses</b>
                             </th>
                             <th style="border: 2px solid black;text-align: right;">
                                 <b><input type="hidden" class="form-control" id="txtIntereses" name="txtIntereses">
                                     <span id="spanIntereses"></span>
                                 </b>
                             </th>

                         </tr>


                         </tbody></table>
                         <table id="tableTotal" style="width: 100%;color: white;font-size: 20px; padding: 8px 12px;">
                             <tbody><tr>

                                 <th style="border: 2px solid black;width: 400px;">
                                     <b>Total a pagar</b>
                                 </th>
                                 <th style="border: 2px solid black;text-align: right;">
                                     <b><input type="hidden" class="form-control" id="txtTotal" name="txtTotal">
                                         <span id="spanTotal"></span>
                                     </b>
                                 </th>

                             </tr>


                             </tbody></table>
                     </div>
                     <div style=" padding-top: 11px; padding-left: 10px;  margin-left: 30px;  position: relative;  float: left;    height: auto;">
                         <button class="btn btn-secondary" id="generarBtn">
                             <input type="hidden" class="form-control" id="existeDeclaracion" name="existeDeclaracion">
                             IMPRIMIR
                         </button>
                     </div>

                 </div>
             </div>



     <script>
         $(document).ready(function() {
            // 🔒 Handler global para sesión expirada
             $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
                 if (jqxhr.status === 401 || jqxhr.status === 419) {
                     window.location.href = "{{ route('login') }}";
                 }
             });

             function formatDate(dateString) {
                 // Asegurarse de que la fecha sea en formato 'yyyy-mm-dd' sin hora
                 let date = new Date(dateString + 'T00:00:00'); // Añadir una hora para evitar que el tiempo cambie la fecha
                 let day = String(date.getDate()).padStart(2, '0');
                 let month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses van de 0 a 11
                 let year = String(date.getFullYear()).slice(-4); // Obtener los últimos 2 dígitos del año

                 return `${day}/${month}/${year}`;
             }
             function parseFecha(fechaStr) {
                 var partes = fechaStr.split("/");
                 return new Date(partes[2], partes[1] - 1, partes[0], 23, 59, 59); // Año, Mes (0-11), Día
             }
             $("#btnCancelar").click(function() {
                 $("#txtCantArt100").val('');
                 $("#txtImporteArt100").val('');
                 $("#txtCantAfi").val('');
                 $("#txtImporteAfi").val('');
                 $("#float").css("background", "#0275D8"); // Nuevo color de fondo
                 $("#float img").attr("src", "{{ asset('assets/img/Boton1.png') }}"); // Nueva imagen
                 $("#continuarBtn").show();
                 $("#divAnterior").css("display", "none");
                 $("#paso2").css("display","none");
                 $("#paso3").css("display","none");
                 $("#paso4").css("display","none");
                 $("#float font").css("color", "#FFFFFF");
                 $("#float label").css("color", "#FFFFFF");
             });

             $("#btnCancelar3").click(function() {
                 $("#paso3").css("display","none");
                 $("#paso4").css("display","none");
                 $("#continuarBtn").click();
             });
             $("#btnContinuar2").click(function() {
                 // 🔹 Validar que haya valores cargados en los campos numéricos
                 let impArt100 = $("#txtImporteArt100").val().trim();
                 let impAfi = $("#txtImporteAfi").val().trim();

                 if (impArt100 === "" && impAfi === "") {
                     alert("Debe ingresar al menos un importe antes de continuar.");
                     return; // Detener ejecución
                 }

                 // 🔹 Validar que los valores sean números válidos
                 let numArt100 = parseFloat(impArt100.replace(".", "").replace(",", "."));
                 let numAfi = parseFloat(impAfi.replace(".", "").replace(",", "."));

                 if ((impArt100 !== "" && isNaN(numArt100)) || (impAfi !== "" && isNaN(numAfi))) {
                     alert("Por favor ingrese importes válidos (solo números y comas).");
                     return;
                 }
                 $("#paso2").css("background", "#FFFFFF"); // Nuevo color de fondo

                 // Cambiar la imagen dentro de #float
                 $("#paso2 img").attr("src", "{{ asset('assets/img/Boton2off.png') }}"); // Nueva imagen
                 $("#tableEmpleados").css("color", "#000000"); // Nuevo color de fondo
                 $("#paso3").css("display","flex");
                 $("#btnCancelar").hide();
                 $("#btnOtroPeridodo").hide();

                 $("#btnContinuar2").hide();

                 $("#paso3").css("background", "#0275D8"); // Nuevo color de fondo

                 // Cambiar la imagen dentro de #float
                 $("#paso3 img").attr("src", "{{ asset('assets/img/Boton3.png') }}"); // Nueva imagen
                 $("#divFechas font").css("color", "#FFFFFF");

                 $("#btnCancelar2").show();
                 $("#continuarVencimiento").show();
             });

             $("#continuarBtn").click(function(event) {
                 event.preventDefault();  // Evitar que el formulario se envíe automáticamente

                 // Limpiar errores previos
                 $('.error-message').remove();

                 // Validar los campos usando la validación HTML5
                 var formIsValid = true;

                 // Validación del campo 'empresa'
                 var empresa = $("#empresa")[0];
                 if (!empresa.checkValidity()) {
                     formIsValid = false;
                     // Mostrar borde rojo y permitir que el navegador muestre el mensaje
                     $("#empresa").addClass('is-invalid');
                     empresa.reportValidity(); // Esto hace que el navegador muestre el mensaje de error
                 } else {
                     $("#empresa").removeClass('is-invalid');
                 }

                 // Validación del campo 'mes'
                 var mes = $("#mes")[0];
                 if (!mes.checkValidity()) {
                     formIsValid = false;
                     $("#mes").addClass('is-invalid');
                     mes.reportValidity(); // Mostrar el mensaje de error del navegador
                 } else {
                     $("#mes").removeClass('is-invalid');
                 }

                 // Validación del campo 'year'
                 var year = $("#year")[0];
                 if (!year.checkValidity()) {
                     formIsValid = false;
                     $("#year").addClass('is-invalid');
                     year.reportValidity(); // Mostrar el mensaje de error del navegador
                 } else {
                     $("#year").removeClass('is-invalid');
                 }

                 // Si todos los campos son válidos, enviar la solicitud AJAX
                 if (formIsValid) {
                     var empresa = $("#empresa").val();
                     var mes = $("#mes").val();
                     var year = $("#year").val();
                     var txtCantArt100 = $("#txtCantArt100").val();
                     var txtImporteArt100 = $("#txtImporteArt100").val();
                     var txtCantAfi = $("#txtCantAfi").val();
                     var txtImporteAfi = $("#txtImporteAfi").val();


                     _token: '{{ csrf_token() }}'
                     // Cambiar el texto del botón al inicio de la solicitud
                     $("#continuarBtn").text('Cargando...');
                     // Realizar una solicitud AJAX al controlador de Laravel
                     $.ajax({
                         type: 'POST',
                         url: '{{ url('/procesarBoleta') }}',
                         data: {
                             empresa: empresa,
                             mes: mes,
                             year: year,
                             txtCantArt100: txtCantArt100,
                             txtImporteArt100: txtImporteArt100,
                             txtCantAfi: txtCantAfi,
                             txtImporteAfi: txtImporteAfi,
                             _token: '{{ csrf_token() }}' // Agrega el token CSRF para protección
                         },
                         success: function (response) {
                             $('.error-message').remove();
                             // Manejar la respuesta del servidor, si es necesario
                             //console.log('Respuesta del servidor:', response);
                             // Cambiar el background de #float
                             $("#float").css("background", "#FFFFFF"); // Nuevo color de fondo
                             $("#float font").css("color", "#000000");
                             $("#float label").css("color", "#000000");
                             // Cambiar la imagen dentro de #float
                             $("#float img").attr("src", "{{ asset('assets/img/Boton1off.png') }}"); // Nueva imagen
                             $("#paso2").css("background", "#0275D8"); // Nuevo color de fondo

                             // Cambiar la imagen dentro de #float
                             $("#paso2 img").attr("src", "{{ asset('assets/img/Boton2.png') }}"); // Nueva imagen
                             $("#tableEmpleados").css("color", "#FFFFFF"); // Nuevo color de fondo

                             $("#btnCancelar").show();
                             $("#btnOtroPeridodo").show();

                             $("#btnContinuar2").show();


                             // Actualizar la tabla con la respuesta HTML
                             $("#ListaEmpleadosActual").html(response.tabla);
                             $("#divAnterior").html(response.tablaAnterior);
                             $("#divAnterior").css("display", "flex");
                             $("#txtFOriginal").val(formatDate(response.original));
                             $("#txtFVencimiento").val(formatDate(response.vencimiento));
                             CambiaImporte();
                             // Limpiar mensajes de error anteriores
                             $('#errorContainer').html('');
                             $("#paso2").css("display", "flex");
                             $("#continuarBtn").hide();
                             // Ocultar el botón
                             //$(this).hide();
                         },
                         error: function (error) {
                             // Limpiar errores anteriores
                             $('.error-message').remove();

                             if (error.responseJSON && error.responseJSON.errors) {
                                 var errors = error.responseJSON.errors;

                                 $.each(errors, function (field, messages) {
                                     // messages es un array, pueden ser varios errores para un mismo campo
                                     messages.forEach(function(message) {
                                         if (field === 'debajo_minimo') {
                                             if (confirm(message + "\n¿Desea modificar los empleados?")) {
                                                 // Redirigir a la URL de edición
                                                 window.location.href = "{{ route('empleados.index') }}?empresa=" + encodeURIComponent(empresa);;
                                             }
                                         } else {
                                             jalert(message); // O el alert que uses normalmente
                                         }
                                     });

                                 });
                             } else {
                                 $('#errorContainer').html('Error. Intente nuevamente más tarde');
                                 //console.log('Error en la solicitud AJAX:', error);
                             }
                         },
                         complete: function () {
                             // Restaurar el texto del botón al finalizar la solicitud
                             $("#continuarBtn").text('Aceptar');

                         }
                     });
                 }
             });

             // Configurar el calendario en español
             $.datepicker.setDefaults($.datepicker.regional["es"]);

             // Inicializar el datepicker en español
             $("#txtFVencimiento").datepicker({
                 dateFormat: "dd/mm/yy", // Formato de fecha
                 dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                 dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                 monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                 monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                 firstDay: 0,
                 showButtonPanel: false,
                 changeMonth: true,
                 changeYear: true,
             });



             $("#continuarVencimiento").click(function() {
                 //var txtVencimiento = $("#txtVencimiento").val();
                 if ($("#txtFVencimiento").val()!="") {
                     var dtFechaActual = new Date();
                     var fecha = $("#txtFVencimiento").val();
                     var fechao = $("#txtFOriginal").val();


                     var fechaVencimiento = parseFecha(fecha);
                     var fechaOriginal = parseFecha(fechao);
                     //console.log(fechaVencimiento + ' --- ' + fechaOriginal+' ---- '+dtFechaActual);
                     if (fechaVencimiento >= dtFechaActual && fechaVencimiento >= fechaOriginal) {
                         var empresa = $("#empresa").val();
                         var mes = $("#mes").val();
                         var year = $("#year").val();
                         var txtVenc = $("#txtFVencimiento").val();
                         var txtCantArt100 = $("#txtCantArt100").val();
                         var txtImporteArt100 = $("#txtImporteArt100").val();
                         var txtCantAfi = $("#txtCantAfi").val();
                         var txtImporteAfi = $("#txtImporteAfi").val();
                         var partes = txtVenc.split("/"); // Se divide en [día, mes, año]
                         var venc = partes[2].slice(-4) + "-" + partes[1] + "-" + partes[0]; // Formato yy-mm-dd

                         // console.log(venc); // Mostrará la fecha en formato yyyy-mm-dd
                         $("#continuarVencimiento").text('Cargando...');
                         $.ajax({
                             type: 'POST',
                             url: '{{ url('/procesarBoleta') }}',
                             data: {
                                 empresa: empresa,
                                 mes: mes,
                                 year: year,
                                 venc: venc,
                                 txtCantArt100: txtCantArt100,
                                 txtImporteArt100: txtImporteArt100,
                                 txtCantAfi: txtCantAfi,
                                 txtImporteAfi: txtImporteAfi,
                                 _token: '{{ csrf_token() }}' // Agrega el token CSRF para protección
                             },
                             success: function(response) {
                                 // Manejar la respuesta del servidor, si es necesario
                                 //console.log('Respuesta del servidor:', response);





                                 $("#paso3").css("background", "#FFFFFF"); // Nuevo color de fondo

                                 // Cambiar la imagen dentro de #float
                                 $("#paso3 img").attr("src", "{{ asset('assets/img/Boton3off.png') }}"); // Nueva imagen

                                 $("#paso4").css("display","flex");

                                 /*$("#btnCancelar2").hide();

                                 $("#btnContinuar3").hide();*/

                                 $("#divFechas font").css("color", "#000000");

                                 // Actualizar la tabla con la respuesta HTML
                                 $("#ListaEmpleadosActual").html(response.tabla);
                                 CambiaImporte();
                                 $("#txtFOriginal").val(formatDate(response.original));
                                 $("#txtFVencimiento").val(formatDate(response.vencimiento));
                                 $("#txtIntereses").val(response.intereses);
                                 $("#spanIntereses").html(response.intereses);
                                 $("#txtTotal").val(response.total);
                                 $("#spanTotal").html(response.total);
                                 $("#existeDeclaracion").val(response.existeDeclaracion);
                                 // Limpiar mensajes de error anteriores
                                 $('#errorContainer').html('');
                                 $("#tableEmpleados").css("color", "#000000"); // Nuevo color de fondo
                                 $("#tableIntereses").css("color", "#FFFFFF"); // Nuevo color de fondo
                             },
                             error: function(error) {
                                 // Manejar los mensajes de error y mostrarlos
                                 if (error.responseJSON && error.responseJSON.errors) {
                                     var errors = error.responseJSON.errors;
                                     var errorMessage = '<ul>';
                                     $.each(errors, function (index, value) {
                                         errorMessage += '<li>' + value + '</li>';
                                     });
                                     errorMessage += '</ul>';
                                     $('#errorContainer').html(errorMessage);
                                 } else {
                                     $('#errorContainer').html('Error. Intente nuevamente más tarde');
                                     //console.log('Error en la solicitud AJAX:', error);
                                 }
                             },
                             complete: function() {
                                 // Restaurar el texto del botón al finalizar la solicitud
                                 $("#continuarVencimiento").text('CONTINUAR');
                             }
                         });
                     }
                     else {
                         alert("Fecha de Pago incorrecta, por favor verifique");
                     }
                 }

                 // Cambiar el texto del botón al inicio de la solicitud

             });


             $("#generarBtn").click(function() {
                 //var txtVencimiento = $("#txtVencimiento").val();
                 if ($("#txtFVencimiento").val()!="") {


                     var dtFechaActual = new Date();
                     var fecha = $("#txtFVencimiento").val();
                     var fechao = $("#txtFOriginal").val();



                     var fechaVencimiento = parseFecha(fecha);
                     var fechaOriginal = parseFecha(fechao);
                     //console.log(fechaVencimiento + ' --- ' + fechaOriginal+' ---- '+dtFechaActual);
                     if (fechaVencimiento >= dtFechaActual && fechaVencimiento >= fechaOriginal) {
                         var empresa = $("#empresa").val();
                         var mes = $("#mes").val();
                         var year = $("#year").val();
                         var txtCantArt100 = $("#txtCantArt100").val();
                         var txtImporteArt100 = $("#txtImporteArt100").val();
                         var txtCantAfi = $("#txtCantAfi").val();
                         var txtImporteAfi = $("#txtImporteAfi").val();

                         var txtVenc = $("#txtFVencimiento").val();
                         var partes = txtVenc.split("/"); // Se divide en [día, mes, año]
                         var venc = partes[2].slice(-4) + "-" + partes[1] + "-" + partes[0]; // Formato yy-mm-dd

                         var txtVencO = $("#txtFOriginal").val();
                         var partes = txtVencO.split("/"); // Se divide en [día, mes, año]
                         var vencOri = partes[2].slice(-4) + "-" + partes[1] + "-" + partes[0]; // Formato yy-mm-dd

                         var intereses = $("#txtIntereses").val();
                         var existeDeclaracion = $("#existeDeclaracion").val();
                         var continua = 1;
                         /*if (existeDeclaracion!=0){
                             if (!confirm("¿Ya existe una declaración jurada para la empresa y periodo seleccionado, es una rectificación de la misma?")){
                                 return false;
                             }

                         }*/

                         hoy = new Date();
                         anio = hoy.getFullYear();
                         mesActual = hoy.getMonth() + 1;
                         if ($("#mes").val()<1 || $("#mes").val()>12){
                             alert("Mes incorrecto");
                             return;
                         }
                         //if ($("#txtAnio").val()> anio || $("#txtAnio").val()<= (anio - 2))
                         if ($("#year").val()> anio){
                             alert("Año incorrecto");
                             return;
                         }

                         pi=parseFloat($("#year").val()) * 100 + parseFloat($("#mes").val());
                         pa=parseFloat(anio) * 100 + parseFloat(mesActual);

                         if (pa<pi){
                             alert("Período incorrecto, por favor verifique");
                             return;
                         }

                         $("#generarBtn").text('Cargando...');
                         $.ajax({
                             type: 'POST',
                             url: '{{ url('/generar') }}',
                             data: {
                                 empresa: empresa,
                                 mes: mes,
                                 year: year,
                                 venc: venc,
                                 vencOri: vencOri,
                                 intereses: intereses,
                                 txtCantArt100: txtCantArt100,
                                 txtImporteArt100: txtImporteArt100,
                                 txtCantAfi: txtCantAfi,
                                 txtImporteAfi: txtImporteAfi,
                                 _token: '{{ csrf_token() }}' // Agrega el token CSRF para protección
                             },
                             success: function(response) {
                                 if (response.success) {
                                     // Obtener la URL del PDF
                                     var pdfUrl = response.pdf_url;

                                     // Abrir el PDF en una nueva ventana
                                     window.open(pdfUrl);
                                 } else {
                                     console.error('Error al obtener el PDF:', response.message);
                                 }
                             },
                             error: function(error) {
                                 // Manejar los mensajes de error y mostrarlos
                                 if (error.responseJSON && error.responseJSON.errors) {
                                     var errors = error.responseJSON.errors;
                                     var errorMessage = '<ul>';
                                     $.each(errors, function (index, value) {
                                         errorMessage += '<li>' + value + '</li>';
                                     });
                                     errorMessage += '</ul>';
                                     $('#errorContainer').html(errorMessage);
                                 } else {
                                     $('#errorContainer').html('Error. Intente nuevamente más tarde');
                                     //console.log('Error en la solicitud AJAX:', error);
                                 }
                             },
                             complete: function() {
                                 // Restaurar el texto del botón al finalizar la solicitud
                                 $("#generarBtn").text('IMPRIMIR');
                             }
                         });
                     }
                     else {
                         alert("Fecha de Pago incorrecta, por favor verifique");
                     }
                 }

                 // Cambiar el texto del botón al inicio de la solicitud

             });

             $("#BtVerListaEmpleados").click(function() {
                 alert("Mostrar lista de empleados");
             });
             $("#continuarBtn").click();

         });
         function CambiaImporte() {


             const imp100 = parseFloat($('#txtImporteArt100').val().replace('.', '').replace(',', '.')) || 0;
             const impAfi = parseFloat($('#txtImporteAfi').val().replace('.', '').replace(',', '.')) || 0;


             const totalImporte = imp100 + impAfi;


             $('#txtTotalImporte').val(totalImporte.toLocaleString('es-AR', { minimumFractionDigits: 2 }));
         }

     </script>
@endsection
