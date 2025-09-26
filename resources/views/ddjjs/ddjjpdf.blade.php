<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDJJ - Pago de Aportes</title>
    <style>
        @page { size: A4; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 10pt; margin: 0; padding: 0; }

        .page {
            width: 100%;
            max-width: 210mm;
            margin: auto;
            box-sizing: border-box;
        }

        /* Header con 3 columnas */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .header img { max-width: 40mm; height: auto; }
        .header .title { text-align: center; font-weight: bold; font-size: 12pt; flex: 1; }
        .header .contact { font-size: 8pt; line-height: 1.3; text-align: right; }

        .fecha { text-align: right; font-size: 9pt; margin-bottom: 10px; }

        /* Datos de empresa */
        .company {
            margin-bottom: 12px;
        }
        .company .bold { font-weight: bold; font-size: 11pt; }

        /* Contenedores en 2 columnas */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8mm;
            margin-bottom: 10px;
        }

        .box {
            border: 1px solid #000;
            padding-top: 4mm;
            padding-bottom: 4mm;
            padding-left: 0;
            padding-right: 0;
        }

        .box .bold { font-weight: bold; }

        /* Total destacado */
        .total {
            border: 1px solid #000;
            padding: 4mm;
            font-weight: bold;
            margin-top: 5mm;
        }

        /* Pie */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 15mm;
        }

        .centros {
            border: 1px solid #000;
            padding: 4mm;
            font-size: 9pt;
        }

        .barcode {
            text-align: center;
            margin-top: 10mm;
        }

        .talon-copy {
            text-align: right;
            margin-top: 10mm;
            font-size: 9pt;
        }
    </style>
</head>
<body>

<div class="page">

    @foreach([['title' => '1 - PARA EL RECAUDADOR'], ['title' => '2 - PARA EL DEPOSITANTE']] as $copy)
        <div class="talon">

            {{-- Header --}}
            <div class="header">
                <div><img src="{{ public_path('images/logosec.png') }}" alt="Logo"></div>
                <div class="title">Sindicato de Empleados de Comercio La Plata</div>
                <div class="contact">
                    <div>6 Nro. 682 - La Plata</div>
                    <div>Tel: (0221) 421-7440</div>
                    <div>Email: aportes@seclaplata.org.ar</div>
                    <div>www.seclaplata.org.ar</div>
                </div>
            </div>

            {{-- Lugar y fecha --}}
            <div class="fecha">
                La Plata, {{ now()->day }} de {{ now()->translatedFormat('F') }} de {{ now()->year }}
            </div>

            {{-- Datos empresa --}}
            <div class="company" style="margin-top:10px;">

                {{-- Título centrado con borde --}}
                <div style="text-align:center; font-weight:bold; border:1px solid #000; padding:4px; margin-bottom:8px; font-size:11pt;">
                    PAGO DE APORTES SINDICALES
                </div>

                {{-- Datos distribuidos en dos columnas --}}
                <div style="display:flex; justify-content:space-between; font-size:10pt;">

                    {{-- Columna izquierda --}}
                    <div style="font-weight: bold">
                        <div>Empresa: {{ $empresaCodigo }} - {{ $empresaNombre }}</div>
                        <div>Periodo: {{ $mes }} - {{ $year }}</div>

                    </div>

                    {{-- Columna derecha --}}
                    <div >
                        <div>Vencimiento original: {{ $vencori }}</div>
                        <div style="font-weight: bold">Vencimiento para pago: {{ $venc }}</div>
                    </div>

                </div>
            </div>


            {{-- Cajas de importes --}}
            <div class="grid-2">
                <div class="box" style="border:1px solid #000; width:75mm; padding-top:3px; padding-bottom:3px;">

                    <!-- Título centrado -->
                    <div style="text-align:center; margin-bottom:5px; border-bottom:1px solid #000; padding-bottom:3px; font-weight: bold">
                        2% Art.100
                    </div>

                    <!-- Contenido con padding -->
                    <div style="padding:2mm;">
                        <div>Nro. Aportantes: {{ $cart100 }}</div>
                        <div>Importe: {{ number_format(floatval($iart100 ?? 0),2,',','.') }}</div>
                    </div>
                </div>

                <div class="box" style="border:1px solid #000; width:75mm; padding-top:3px; padding-bottom:3px;">

                    <!-- Título centrado -->
                    <div style="text-align:center; margin-bottom:5px; border-bottom:1px solid #000; padding-bottom:3px; font-weight: bold">
                        2% Cuota Afiliación
                    </div>
                    <!-- Contenido con padding -->
                    <div style="padding:2mm;">
                        <div>Nro. Aportantes: {{ $cafil }}</div>
                        <div>Importe: {{ number_format(floatval($iafil ?? 0),2,',','.') }}</div>
                    </div>
                </div>

                <div class="box" style="border:1px solid #000; width:75mm; padding-top:3px; padding-bottom:3px;">

                    <!-- Título centrado -->
                    <div style="text-align:center; margin-bottom:5px; border-bottom:1px solid #000; padding-bottom:3px; font-weight: bold">
                        Otros Conceptos
                    </div>
                    <!-- Contenido con padding -->
                    <div style="padding:2mm;">
                        <div>Importe: {{ number_format(floatval($Intereses ?? 0),2,',','.') }}</div>
                    </div>
                </div>

                <div class="box" style="border:1px solid #000; width:75mm; padding-top:3px; padding-bottom:3px;">

                    <!-- Título centrado -->
                    <div style="text-align:center; margin-bottom:5px; border-bottom:1px solid #000; padding-bottom:3px; font-weight: bold">
                        Intereses fuera de término
                    </div>
                    <!-- Contenido con padding -->
                    <div style="padding:2mm;">
                        <div>Importe: {{ number_format(floatval($InteresesFPT ?? 0),2,',','.') }}</div>
                    </div>
                </div>

            </div>

            {{-- Total --}}
            @php
                $total = floatval($iart100 ?? 0) + floatval($iafil ?? 0) + floatval($Intereses ?? 0) + floatval($InteresesFPT ?? 0);
            @endphp
            <div class="total">Total a Pagar: {{ number_format($total,2,',','.') }}</div>

            {{-- Footer con centros + datos --}}
            <div class="footer">
                <div class="centros">
                    <div class="bold">Centros de pago autorizados</div>
                    <div>Bco.Prov.de Buenos Aires</div>
                    <div>Provincia NET pagos</div>
                    <div>Link pagos</div>
                </div>
                <div>
                    <div>Código Link: {{ $codigoLink }}</div>
                    <div>Comprobante Nro.: {{ $nrocomprobante }}</div>
                    @php $ref = substr((string)($nrocomprobante ?? ''), -6); @endphp
                    <div>Cuota: {{ substr($ref,0,3) }}/{{ substr($ref,3) }}</div>
                </div>
            </div>

            {{-- Código de barras --}}
            <div class="barcode" style="text-align:center;">
                <div style="display:inline-block; max-width:500px; overflow:hidden;">
                    {!! $barcode !!}
                </div>
                <div style="font-size:10pt; margin-top:5px;">{{ $barras }}</div>
            </div>

            {{-- Copia --}}
            <div class="talon-copy">{{ $copy['title'] }}</div>
        </div>

        <hr style="margin:20mm 0; border:none; border-top:1px dashed #000;">
    @endforeach

</div>

</body>
</html>
