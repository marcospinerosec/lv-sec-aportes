<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="Hernan Hourcade - TI54" />
    <title>EMPRESAS Y CONTADORES SEC</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



    <!-- Bootstrap JS (with Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/scripts.js') }}"></script>



    <style>
        /* El contenedor principal ocupa toda la altura disponible */
        #app {
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        /* El contenido principal ocupa el espacio restante */
        main {
            flex: 1;
        }

        /* El footer siempre estar谩 al final */
        footer {
            margin-top: auto;
        }

        .fa-btn {
            margin-right: 6px;
        }
        .load{
            position: fixed;
            z-index: 9999;
            width: 100%;
            height: 100%;
        }
        .load .in{
            width: 400px;
            text-align: center;
            margin-right: auto;
            margin-left: auto;
            margin-top: 10%;
        }
        .wrapper {
            filter: blur(3px);
        }
        img {
            vertical-align: middle;
        }
        img {
            border: 0;
        }

#navbarDropdown:focus,
#navbarDropdown:active {
    color: #000 !important;  /* Cambia el color a negro o cualquier color que prefieras */
    background-color: transparent !important;  /* Evita que el fondo se cambie */
}

.invalid-feedback {
    display: block; /* Aseg鷕ate de que el mensaje de error se muestre como bloque */
    color: red; /* Establece un color para los mensajes de error */
}


    </style>
</head>
<body id="page-top">
    <div id="app">

        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">


                <a class="navbar-brand" href="#page-top"><img class="img-fluid" src="{{ asset('assets/img/LogoSEC2.jpg')}}" alt="Pricipal" /></a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#portfolio">Información importante</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#about">Formulario de inscripción</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#contact">Contacto</a></li>

                    </ul>
                </div>


                </div>

        </nav>

        <header class="masthead bg-primary text-white text-center">




            @yield('content')

        </header>
        <!-- Portfolio Section-->
        <section class="page-section portfolio" id="portfolio">
            <div class="container">
                <!-- Portfolio Section Heading-->
                <center>
                    <h3HH >Información importante</h3HH>





                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>


                    <div style="width:950px">
                        <fieldset id="float">

                            <div align="left">
                                <br>
                                Debido de reiterados inconvenientes técnicos en proveedores de Servicios de internet del Sindicato de Empleados de Comercio pueden presentarse cortes o fallas temporales en el funcionamiento normal de esta plataforma.
                                <BR>
                                Ante tal situación le sugerimos intentar nuevamente luego de un tiempo prudencial de espera y/o informar la falla a través de correo electrónico a sistemas@seclaplata.org.ar .
                                <BR>
                                Esperamos sepa disculpar las molestias ocasionadas.
                                <BR>
                                Muchas gracias.			<br><br>
                            </div>
                        </fieldset>
                    </div>
                    <br>


                    <!-- Portfolio Grid Items-->
                    <div class="row justify-content-center">
                        <!-- Portfolio Item 1-->
                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal" data-bs-target="#portfolioModal1">
                                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <hr>
                                Aportes sindicales obligatorios<br>
                                (Art. 100 C.C.T. 130/75)
                                <hr>
                            </div>
                        </div>


                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal" data-bs-target="#portfolioModal2">
                                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <hr>
                                Cuota sindical por afiliación<br>
                                RESOLUCIÓN D. N. A. S. Nº  22/89
                                <hr>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal" data-bs-target="#portfolioModal3">
                                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <hr>
                                Inscripción en el sindicato<br>Requisitos
                                <hr>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal" data-bs-target="#portfolioModal4">
                                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <hr>
                                Agente de retención<br>LEY 24642
                                <hr>
                            </div>
                        </div>


                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal" data-bs-target="#portfolioModal5">
                                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <hr>
                                Obligatoriedad de entrega de<br>certificados laborales
                                <hr>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal" data-bs-target="#portfolioModal6">
                                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <hr>
                                Sanciones por empleo<br>no registrado
                                <hr>
                            </div>
                        </div>




                        <!-- Portfolio Item 2-->
                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal" data-bs-target="#portfolioModalA">
                                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                CANALES DE PAGOS <br> LISTA DE CANALES DE PAGO (NO MODAL)
                                <!-- <img class="img-fluid" src="assets/img/portfolio/cake.png" alt="..." /> -->
                            </div>
                        </div>
                        <!-- Portfolio Item 3-->
                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal" data-bs-target="#portfolioModalB">
                                <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                CALENDARIO DE PAGO DE APORTES
                            </div>
                        </div>


                    </div>

            </div>

        </section>
        <!-- About Section-->
        <section class="page-section bg-primary text-white mb-0" id="about">
            <div class="container">
                <!-- About Section Heading-->
                <center>
                    <h3hh >Formulario de inscripción</h3hh>
                    <!-- Icon Divider-->
                    <div class="divider-custom divider-light">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                    <!-- About Section Content-->
                    <div class="row">
                        <div class="col-lg-4 ms-auto"><p class="lead">Acá se me ocurre desarrollar un formulario como el excel actual para inscripciones de empresas</p></div>
                    </div>
            </div>
        </section>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container">

                <center>
                    <h3HH>Contacto</h3HH>
                    <!-- Icon Divider-->
                    <div class="divider-custom">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                    <!-- Contact Section Form-->
                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-xl-7">
                            <!-- * * * * * * * * * * * * * * *-->
                            <!-- * * SB Forms Contact Form * *-->
                            <!-- * * * * * * * * * * * * * * *-->
                            <!-- This form is pre-integrated with SB Forms.-->
                            <!-- To make this form functional, sign up at-->
                            <!-- https://startbootstrap.com/solution/contact-forms-->
                            <!-- to get an API token!-->
                            <form id="contactForm" data-sb-form-api-token="API_TOKEN">
                                <!-- Name input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="name" type="text" placeholder="Enter your name..." data-sb-validations="required" />
                                    <label style="font-size: 1rem;" for="name">Nombre</label>
                                    <div class="invalid-feedback" data-sb-feedback="name:required">A name is required.</div>
                                </div>
                                <!-- Email address input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="email" type="email" placeholder="name@example.com" data-sb-validations="required,email" />
                                    <label style="font-size: 1rem;" for="email">Correo electrónico</label>
                                    <div class="invalid-feedback" data-sb-feedback="email:required">An email is required.</div>
                                    <div class="invalid-feedback" data-sb-feedback="email:email">Email is not valid.</div>
                                </div>
                                <!-- Phone number input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="phone" type="tel" placeholder="(123) 456-7890" data-sb-validations="required" />
                                    <label style="font-size: 1rem;" for="phone">Teléfono</label>
                                    <div class="invalid-feedback" data-sb-feedback="phone:required">A phone number is required.</div>
                                </div>
                                <!-- Message input-->
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="message" type="text" placeholder="Enter your message here..." style="height: 10rem" data-sb-validations="required"></textarea>
                                    <label style="font-size: 1rem;" for="message">Mensaje</label>
                                    <div class="invalid-feedback" data-sb-feedback="message:required">A message is required.</div>
                                </div>
                                <!-- Submit success message-->
                                <!-- Submit error message-->
                                <!---->
                                <!-- This is what your users will see when there is-->
                                <!-- an error submitting the form-->
                                <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">Error!</div></div>
                                <!-- Submit Button-->
                                <button class="btn btn-primary btn-xl disabled" id="submitButton" type="submit">Enviar</button>
                            </form>
                        </div>
                    </div>
            </div>
        </section>
        <!-- Footer -->
        <footer class="bg-light text-center text-lg-start">

            <!--<div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                Copyright &copy; 2024-{{ Carbon\carbon::now()->year }} <a href="http://www.seclaplata.org.ar" target="_blank">Sindicato de Empleados de Comercio.</a> Todos los derechos reservados.
            </div>-->
            <!-- Copyright Section-->
            <div class="copyright py-4 text-center text-white">
                <!--<div class="container"><small>Desarrollado por: <a href="http://www.ti54.com.ar" target="_Blank">www.ti54.com.ar</a></small></div>-->
                Copyright &copy; 2024-{{ Carbon\carbon::now()->year }} <a href="http://www.seclaplata.org.ar" target="_blank">Sindicato de Empleados de Comercio.</a> Todos los derechos reservados.
            </div>
        </footer>





        <!-- Portfolio Modals-->
        <!-- Portfolio Modal 1-->
        <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" aria-labelledby="portfolioModal1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header border-0"><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body text-center pb-5">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <p class="portfolio-modal-title text-primary text-uppercase mb-0">
                                        APORTES SINDICALES OBLIGATORIOS<br>(Art. 100 C.C.T. 130/75)</p>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <div>
                                        <div class="row justify-content-center">
                                            <p align="left">
                                                <b>CONVENIO COLECTIVO 130/75 ARTICULO 100º
                                                    <br>
                                                    HOMOLOGACION DEL ACUERDO: DISP. (D. N. R. T.) 4803 DE FECHA 4/7/91</b>
                                                <br><br>En la Ciudad de Buenos Aires, a los 21 días del mes de junio de 1991 entre los representantes de la Cámara Argentina de Comercio, la Coordinadora de Actividades Mercantiles Empresarias, la Unión de Entidades Comerciales Argentinas, todos en nombre del sector empresario por una parte, y por la otra los representantes de la Federación Argentina de Empleados de Comercio y Servicios (FAECYS), en el expediente 829.222/88 se conviene lo siguiente:
                                                <br>PRIMERO: Establécese, con el carácter de contribución solidaria a cargo de la totalidad de los trabajadores comprendidos en la presente convención colectiva y en los términos del artículo 9º de la ley 14250, un aporte correspondiente al 2,5% (dos y medio por ciento) de la remuneración total que, por todo concepto perciba mensualmente cada trabajador, a partir del primer mes posterior a aquél en que se produzca la homologación y hasta que entre en vigencia la convención que en el futuro lo sustituya.
                                                <br>Las sumas indicadas serán retenidas por los empleadores y depositadas por éstos en el mismo plazo fijado para el depósito de los aportes de obra social, utilizando las boletas y en las cuentas bancarias que suministrarán las asociaciones sindicales receptoras, de conformidad con lo dispuesto en los párrafos siguientes:
                                                <br>Las cantidades mensualmente resultantes de lo establecido en el párrafo primero de este artículo, deberán depositarse:
                                                <br>1) Las que correspondan al 2% (dos por ciento) de las remuneraciones, a la orden de la asociación sindical de primer grado signataria en el ámbito de cada empleador, adherida a la Federación, en las cuentas que a tal efecto abrirán expresamente las mismas.
                                                <br>2) Las que correspondan al 0,5% (medio por ciento) de las remuneraciones, a favor de la Federación Argentina de Empleados de Comercio y Servicios en la cuenta corriente de la misma y a través de las boletas correspondientes que ésta distribuirá.
                                                <br>Las partes acuerdan asimismo que son aplicables a los empleadores todas las obligaciones y consecuencias jurídicas inherentes a la condición de agente de retención.
                                                <br>La obligación de retener quedará fehacientemente notificada a todos los empleadores comprendidos por la presente convención colectiva mediante publicación del acto administrativo de homologación que deberá efectuar la FAECYS por dos días consecutivos, en un diario de la Capital Federal de circulación nacional.
                                            </p>
                                            <button class="btn btn-primary" data-bs-dismiss="modal">
                                                <i class="fas fa-xmark fa-fw"></i>
                                                Close Window
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio Modal 2-->
        <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" aria-labelledby="portfolioModal2" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header border-0"><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body text-center pb-5">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <p class="portfolio-modal-title text-primary text-uppercase mb-0">
                                        CUOTA SINDICAL POR AFILIACION<br>RESOLUCIÓN D. N. A. S. Nº  22/89</p>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <div>
                                        <div class="row justify-content-center">
                                            <p align="left">
                                                <b>BUENOS AIRES, 3 DE ABRIL DE 1989.</b>
                                                <br><br>Visto la presentación efectuada a fs. 1 por el SINDICATO DE EMPLEADOS DE COMERCIO DE LA PLATA,  en el Expte. Nº 223.386/89, y:
                                                <br><br><b>CONSIDERANDO:</b>
                                                <br>Que la entidad peticionante goza de Personería Gremial Nº 215 otorgada por Resolución Nº 38 del 19/02/1953.
                                                <br>Que de acuerdo con lo que dispone el Art. 38º de la Ley Nº 23.551/88, los empleadores actuarán como agentes da retención de las importes que en concepto de cuotas o contribuciones deben abanar los trabajadores a los sindicatos con personaría gremial.
                                                <br>Que en el artículo 20º inciso c) de la citada Ley se expresa que será privativo de las asambleas o congresos fijar el monto de las cuotas de afiliados y de las contribuciones da los mismos.
                                                <br>Que la retención fue aprobada por Asamblea General Extraordinaria realizada el 20 de diciembre de 1988. según consta en el presente expediente.
                                                <br>Que a fs. 13 el Departamento Técnico Legal ha emitido opinión favorable respecto al dictado de la resolución solicitada.Las partes acuerdan asimismo que son aplicables a los empleadores todas las obligaciones y consecuencias jurídicas inherentes a la condición de agente de retención.
                                                <br><br><b>Por ello EL DIRECTOR NACIONAL DE ASOCIACIONES SINDICALES RESUELVE:</b>
                                                <br>ARTICULO 1º: Los empleadores que ocupen personal afiliado al SINDICATO DE EMPLEADOS DE COMERCIO DE LA PLATA, deberán retener a los mismos sobre el total de haberes, un importe equivalente al DOS (2%) por ciento en concepto de "cuota sindical". Las retenciones citadas deben extenderse también a las retribuciones en concepto de sueldo anual complementario.
                                                <br>Para aquellos trabajadores de jornada inferior a las ocho (8) horas diarias, o tiempo semanal reducido, en situación similar a lo establecido por la Ley 23.660. Art. 18º las retenciones planteadas se efectuarán sobre el básico de la categoría para jornada completa.
                                                <br>ARTICULO 2º: La retención a que se refiera el artículo anterior se deberá practicar sobre las remuneraciones que se devenguen a partir del mes de notificación, de la presente resolución y los importes resultantes deberán ser depositados dentro de los quince (15) días de efectuado el descuento en la Institución Bancaria habilitada al efecto, a la orden de la entidad Sindical.
                                                <br>ARTICULO 3º: Reconocer como único medio de pago válido el establecido precedentemente.
                                                <br>ARTICULO 4º: Practicar el descuento dispuesto en el artículo 1º de la presente resolución, en base a la planilla que la entidad sindical deberá remitir al empleador con la nómina de trabajadores afiliados y el monto de las cuotas que deben retener con una antelación no menor a diez (10) días, al primer pago al que resulta aplicable, con una copia autenticada de la presente resolución, de acuerdo con lo dispuesto en el Art. 24º Decreto Nº 467/88.
                                                <br>ARTICULO 5º: Registrar, comunicar, remitir copia autenticada al Departamento Publicaciones y Biblioteca, archivar.
                                                <br><br>Firmado. Dr. Guillermo Enrique Mayo
                                                <br>Director Nacional de Asociaciones Sindicales
                                            </p>
                                            <button class="btn btn-primary" data-bs-dismiss="modal">
                                                <i class="fas fa-xmark fa-fw"></i>
                                                Close Window
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio Modal 3-->
        <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" aria-labelledby="portfolioModal3" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header border-0"><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body text-center pb-5">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <p class="portfolio-modal-title text-primary text-uppercase mb-0">
                                        INSCRIPCIÓN EN EL SINDICATO</p>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <div>
                                        <div class="row justify-content-center">
                                            <p align="left">
                                                Toda empresa comprendida en C.C.T. 130/75 esta obligada a registrarse como empleador mercantil en el sindicato de su jurisdicción, para lo cuál le será asignado un número de inscripción sobre el que será acreditado todo aporte con destino gremial y/o sindical.
                                                <br>Se deberá notificar periódicamente nómina de personal ocupado, sus remuneraciones, contribuciones y aportes efectuados, altas y bajas. Ley 24.642 Art. 6º.
                                            </p>
                                        </div>
                                    </div>
                                    <br><br>
                                    <p class="portfolio-modal-title text-primary text-uppercase mb-0">
                                        REQUISITOS PARA EMPADRONAR LA EMPRESA</p>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <div>
                                        <div class="row justify-content-center">
                                            <p align="left">
                                                Formulario de Declaración Jurada (firmado por el titular).
                                                <br><br>Para empresas con Titular Único o Sociedad de hecho fotocopia de documento/s y domicilio/s real/es de titular/es responsable/s.
                                                <br><br>En los casos de tratarse de Razón Social, se deberá obligatoriamente acompañar fotocopia de CONTRATO SOCIAL.
                                                <br><br>En todos los casos, se deberá presentar fotocopias de habilitación municipal (ley 24642), formulario de inscripción en A.F.I.P., formulario de jornada legal (ley 11544) y registro de altas de empleados en A.F.I.P. (Altas tempranas).
                                            </p>


                                            <button class="btn btn-primary" data-bs-dismiss="modal">
                                                <i class="fas fa-xmark fa-fw"></i>
                                                Close Window
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio Modal 4-->
        <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" aria-labelledby="portfolioModal4" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header border-0"><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body text-center pb-5">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <p class="portfolio-modal-title text-primary text-uppercase mb-0">

                                        AGENTE DE RETENCION LEY 24642
                                        <br>Sancionada: 8/5/96<br>Promulgada: 28/5/96<br>Publicada BO: 30/5/1996</p>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <div>
                                        <div class="row justify-content-center">
                                            <p align="left">
                                                Procedimiento de cobro al que estarán sujetos los créditos de las asociaciones sindicales originados en la obligación del empleador de actuar como agente de retención de las cuotas y contribuciones que deben abonar los trabajadores afiliados a las mismas
                                                <br>
                                                <br>ARTÍCULO 1º
                                                <br>- Los créditos de las asociaciones sindicales de trabajadores originados en la obligación del empleador de actuar como agente de retención de las cuotas y contribuciones que deben abonar los trabajadores afiliados a las mismas, estarán sujetos al procedimiento de cobro que se establece por la presente ley.
                                                <br><br>ARTÍCULO 2º
                                                <br>- Los empleadores deberán depositar a la orden de la asociación sindical respectiva las cuotas a cargo de los afiliados, en la misma fecha que los aportes y contribuciones al sistema de seguridad social, siendo responsables directos del importe de las retenciones que no hubieran sido efectuadas.

                                                <br><br>ARTÍCULO 3º
                                                <br>- La falta de pago en término de los créditos mencionados en el artículo anterior hará incurrir en mora a los responsables sin necesidad de interpelación alguna.

                                                <br><br>ARTÍCULO 4º
                                                <br>- La obligación de abonar el capital e intereses subsistirá no obstante la falta de reserva por parte de la asociación sindical de trabajadores. En los casos en que no se abonaren totalmente los créditos con más sus accesorios, el pago en primer término se imputará a intereses, y una vez satisfechos éstos, el remanente se imputará al capital adeudado.

                                                <br><br>ARTÍCULO 5º
                                                <br>- El cobro judicial de los créditos previstos en la presente ley se hará por la vía de apremio o de ejecución fiscal prescriptos en los códigos procesales civiles y comerciales de cada jurisdicción, sirviendo de suficiente título ejecutivo el certificado de deuda expedido por la asociación sindical respectiva. La acción prevista en el párrafo anterior podrá ejercerse para el cobro de los créditos originados con anterioridad a la presente ley cuando el procedimiento para la determinación de la deuda se haya sustanciado con posterioridad a la promulgación de la misma. En la Capital Federal
                                                las asociaciones sindicales de trabajadores podrán optar por la justicia nacional con competencia en lo laboral o por los juzgados con competencia en lo civil o comercial. En las Provincias la opción será entre la justicia en lo federal o la civil y comercial de cada jurisdicción.

                                                <br><br>ARTÍCULO 6º
                                                <br>- Los empleadores deberán requerir a los trabajadores que manifiesten si se encuentran afiliados a la asociación sindical respectiva y comunicar mensualmente a la misma la nómina del personal afiliado, sus remuneraciones, las altas y bajas que se hayan producido durante el período respectivo, y las cuotas y contribuciones que correspondan a cada trabajador.

                                                <br><br>ARTÍCULO 7º
                                                <br>- En todo lo que sea compatible se aplicarán a estos créditos y certificados de deuda las normas y procedimientos relativos al cobro de aportes y contribuciones a las obras sociales.

                                                <br><br>ARTÍCULO 8º
                                                <br>- Derógase la ley 23540 y toda otra norma que se oponga a la presente ley.

                                                <br><br>ARTÍCULO 9º
                                                <br>- De forma.

                                                <br><br>LEY 23.551 ARTÍCULO 38º (Reglamentado por Art. 24º Decreto 467/88)
                                                <br>- Los empleadores estarán obligados a actuar como ''agentes de retención'' de los importes que, en concepto de cuotas de afiliación u otros aportes deban tributar los trabajadores a las asociaciones sindicales de trabajadores con personería gremial. Para que la obligación indicada sea exigible, deberá mediar una resolución del Ministerio de Trabajo y Seguridad Social de la Nación, disponiendo la retención. Esta resolución se adoptará a solicitud de la asociación sindical interesada. El ministerio citado deberá pronunciarse dentro de los 30 (treinta) días de recibida la misma. Si así no lo hiciera, se tendrá por tácitamente dispuesta la retención. El incumplimiento por parte del empleador de la obligación de obrar como ''agente de retención'' o -en su caso- de efectuar en tiempo propio el pago de lo retenido, tornará a aquél en deudor directo. La mora en tal caso se producirá de pleno derecho.
                                            </p>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary" data-bs-dismiss="modal">
                                        <i class="fas fa-xmark fa-fw"></i>
                                        Close Window
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Portfolio Modal 6-->
        <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" aria-labelledby="portfolioModal6" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header border-0"><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body text-center pb-5">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <p class="portfolio-modal-title text-primary text-uppercase mb-0">

                                        Sanciones por empleo no registrado</p>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <div>
                                        <div class="row justify-content-center">
                                            <p align="left">
                                                LEY 25.345 ARTÍCULO 44º.
                                                <br>Agrégase como segundo párrafo del artículo 15 de la Ley de Contrato de Trabajo, el siguiente texto: Sin perjuicio de ello, si una o ambas partes pretendieren que no se encuentran alcanzadas por las normas que establecen la obligación de pagar o retener los aportes con destino a los organismos de la seguridad social, o si de las constancias disponibles surgieren indicios de que el trabajador afectado no se encuentra regularmente registrado o de que ha sido registrado tardíamente o con indicación de una remuneración inferior a la realmente percibida o de que no se han ingresado parcial o totalmente aquellos aportes y contribuciones, la autoridad administrativa o judicial interviniente deberá remitir las actuaciones a la Administración Federal de Ingresos Públicos con el objeto de que la misma establezca si existen obligaciones omitidas y proceda en su consecuencia. .

                                                <br><br>LEY 24.013 ARTÍCULO 8º -
                                                <br>El empleador que no registrare una relación laboral abonará al trabajador afectado una indemnización equivalente a una cuarta parte de las remuneraciones devengadas desde el comienzo de la vinculación, computadas a valores reajustados de acuerdo a la normativa vigente. En ningún caso esta indemnización podrá ser inferior a tres veces el importe mensual del salario que resulte de la aplicación del artículo 245 de la ley de contrato de trabajo (t.o. 1976).

                                                <br><br>ARTÍCULO 9º
                                                <br>El empleador que consignare en la documentación laboral una fecha de ingreso posterior a la real, abonará al trabajador afectado una indemnización equivalente a la cuarta parte del importe de las remuneraciones devengadas desde la fecha de ingreso hasta la fecha falsamente consignada, computadas a valores reajustados de acuerdo a la normativa vigente.

                                                <br><br>ARTÍCULO 10º
                                                <br>El empleador que consignare en la documentación laboral una remuneración menor que la percibida por el trabajador, abonará a éste una indemnización equivalente a la cuarta parte del importe de las remuneraciones devengadas y no registradas, debidamente reajustadas desde la fecha en que comenzó a consignarse indebidamente el monto de la remuneración.

                                                <br><br>ARTÍCULO 11º
                                                <br>Las indemnizaciones previstas en los artículos 8º, 9º y 10 procederán cuando el trabajador o la asociación sindical que lo represente cumplimente en forma fehaciente las siguientes acciones:

                                                <br>a) intime al empleador a fin de que proceda a la inscripción, establezca la fecha real de ingreso o el verdadero monto de las remuneraciones, y

                                                <br>b) proceda de inmediato y, en todo caso, no después de las 24 horas hábiles siguientes, a remitir a la Administración Federal de Ingresos Públicos copia del requerimiento previsto en el inciso anterior. Con la intimación el trabajador deberá indicar la real fecha de ingreso y las circunstancias verídicas que permitan calificar a la inscripción como defectuosa. Si el empleador contestare y diere total cumplimiento a la intimación dentro del plazo de los treinta días, quedará eximido del pago de las indemnizaciones antes indicadas.

                                                <br>A los efectos de lo dispuesto en los artículos 8º, 9º y 10 de esta ley, sólo se computarán remuneraciones devengadas hasta los dos años anteriores a la fecha de su entrada en vigencia.
                                            </p>


                                            <button class="btn btn-primary" data-bs-dismiss="modal">
                                                <i class="fas fa-xmark fa-fw"></i>
                                                Close Window
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Portfolio Modal 5-->
        <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" aria-labelledby="portfolioModal5" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header border-0"><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    <div class="modal-body text-center pb-5">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <p class="portfolio-modal-title text-primary text-uppercase mb-0">


                                        Usted legalmente debe dar cumplimiento<br>
                                        a la entrega de los siguientes certificados laborales</p>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <div>
                                        <div class="row justify-content-center">
                                            <p align="left">
                                                CONVENIO COLECTIVO 130/75 ARTÍCULO 107º
                                                <br>La empresa estará obligada a otorgar un certificado de trabajo dentro de los 3 días de la incorporación del empleado, en el que se hará constar la fecha de ingreso, la categoría, el nombre de la razón social, el domicilio de la misma y el sueldo convenido si fuera superior al correspondiente a su categoría.

                                                <br><br>LEY 25.345 ARTÍCULO 43º.
                                                <br>Agrégase como artículo 132 bis de la Ley 20.744 (t.o. Dcto. 390/76) el siguiente: Artículo 132 bis: Si el empleador hubiere retenido aportes del trabajador con destino a los organismos de la seguridad social, o cuotas, aportes periódicos o contribuciones a que estuviesen obligados los trabajadores en virtud de normas legales o provenientes de las convenciones colectivas de trabajo, o que resulten de su carácter de afiliados a asociaciones profesionales de trabajadores con personería gremial, o de miembros de sociedades mutuales o cooperativas, o por servicios y demás prestaciones que otorguen dichas entidades, y al momento de producirse la extinción del contrato de trabajo por cualquier causa no hubiere ingresado total o parcialmente esos importes a favor de los organismos, entidades o instituciones a los que estuvieren destinados, deberá a partir de ese momento pagar al trabajador afectado una sanción conminatoria mensual equivalente a la remuneración que se devengaba mensualmente a favor de este último al momento de operarse la extinción del contrato de trabajo, importe que se devengará con igual periodicidad a la del salario hasta que el empleador acreditare de modo fehaciente haber hecho efectivo el ingreso de los fondos retenidos. La imposición de la sanción conminatoria prevista en este artículo no enerva la aplicación de las penas que procedieren en la hipótesis de que hubiere quedado configurado un delito del derecho penal.


                                                <br><br>ARTÍCULO 45. Agrégase como último párrafo del artículo 80 de la L. C. T. (t.o. Dcto. 390/76), el que sigue:

                                                <br><br>Si el empleador no hiciera entrega de la constancia o del certificado previstos respectivamente en los apartados segundo y tercero de este artículo dentro de los dos (2) días hábiles computados a partir del día siguiente al de la recepción del requerimiento que a tal efecto le formulare el trabajador de modo fehaciente, será sancionado con una indemnización a favor de este último que será equivalente a tres veces la mejor remuneración mensual, normal y habitual percibida por el trabajador durante el último año o durante el tiempo de prestación de servicios, si éste fuere menor. Esta indemnización se devengará sin perjuicio de las sanciones conminatorias que para hacer cesar esa conducta omisiva pudiere imponer la autoridad judicial competente.											</p>


                                            <button class="btn btn-primary" data-bs-dismiss="modal">
                                                <i class="fas fa-xmark fa-fw"></i>
                                                Close Window
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FIN PORTFOLIO MODALS-->





    </div>
</body>

</html>
