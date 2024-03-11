<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de archivo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 0.8em;
        }
        .header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            background-color: #fff;
            padding: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-bottom: 2px solid #ccc; /* Establece el color y el grosor del borde */
        }

        .logo img {
            max-width: 100%;
            max-height: 50px; /* Ajusta según sea necesario */
            height: auto;
        }

        .titulo {
            font-size: 1em;
            font-weight: bold;
            text-align: center;
            margin-top: -30px; /* Ajusta según sea necesario */
        }

        .columnas {
            display: flex;
            align-items: baseline;
        }

        .column {
            /*max-width: 50px;*/
            font-size: 0.5em;
            line-height: 1.5;
            text-align: right;
            /*margin-left: 20px; *//* Espaciado entre las columnas */
            margin-top: -30px; /* Ajusta según sea necesario */
        }
        .fecha {
            text-align: right;
            margin-top: 10px; /* Espaciado superior para separar la fecha del contenido superior */
            font-size: 0.5em;
        }
        .importar {
            margin-top: 20px; /* Espaciado superior para separar del contenido anterior */
            font-size: 0.9em;
        }
        .detalle-categorias {
            border-top: 2px solid #ccc; /* Borde superior */
            border-bottom: 2px solid #ccc; /* Borde inferior */
            padding: 10px; /* Espaciado interno */
            text-align: center;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="logo"><img src="{{ url('images/logosec.PNG') }}"></div>
    <div class="titulo">Sindicato de Empleados de Comercio La Plata</div>
    <div class="columnas">
        <div class="column">
            6 Nro. 682 - La Plata<br>
            Teléfono:(0221) 421-7440<br>
            EMail:aportes@seclaplata.org.ar<br>
            Sitio Web:www.seclaplata.org.ar
        </div>
    </div>
</div>

<div class="fecha">
    <?php
    setlocale(LC_TIME, 'es_ES.utf8'); // Establecer la configuración regional a español
    echo utf8_encode(strftime('La Plata, %d de %B de %Y'));
    ?>
</div>
<div class="importar">
    <p>Importar declaración:</p>
    <p>El sistema permite importar la lista de empleados desde sistemas de sueldos y otras aplicaciones que puedan generar la misma.</p>
    <p>El formato del archivo debe ser de texto plano con los campos delimitados por punto y coma (;)</p>
    <p>Diseño de la estructura:</p>
    <ol>
        <li>CUIL (nn-nnnnnnnn-n)</li>
        <li>NOMBRE</li>
        <li>CATEGORIA</li>
        <li>AFILIADO. Valores permitidos Só N</li>
        <li>FECHA DE INGRESO. (dd/mm/aaaa)</li>
        <li>FECHA DE EGRESO. (dd/mm/aaaa)</li>
        <li>NOVEDADES. Valores permitidos D (Despido), R (Renuncia), L (Licencia), F (Fallecimiento), J (Jubilación). En caso de corresponder.</li>
        <li>BASE CÁLCULO ART.100. Numérico sin símbolo decimal. (Ej.:1230.30 se debe informar 123030)</li>
        <li>BASE CÁLCULO CUOTA AFILIACIÓN. Numérico sin símbolo decimal. (Ej.:1230.30 se debe informar 123030)</li>
    </ol>
</div>
<div class="detalle-categorias">
    Detalle de categorías
</div>
<div>

    @foreach ($categorias as $categoria)
        <p>{{ $categoria['Descripcion'] }}</p>
    @endforeach
</div>
</body>
</html>
