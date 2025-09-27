<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>DDJJ - Pago de Aportes</title>
    <style>
        @page { size: A4; margin: 5mm; }
        body { font-family: Arial, sans-serif; font-size: 8pt; margin: 0; padding: 0; }
        .page { width: 100%; max-width: 210mm; margin: auto; }

        table { border-collapse: collapse; width: 100%; }
        .border { border: 1px solid #000; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .small { font-size: 8pt; }
        .title { font-size: 8pt; font-weight: bold; }

        .box-title {
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #000;
            padding: 3px 0;
        }
        .box-content {
            padding: 2mm;
        }
        .total {
            border: 1px solid #000;
            padding: 2mm;
            font-weight: bold;
            margin-top: 2mm;
        }
        .barcode { text-align: center; margin-top: 2mm; }
        .talon-copy { text-align: right; margin-top: 5mm; font-size: 7pt; }
    </style>
</head>
<body>
<div class="page">

    @foreach([['title' => '1 - PARA EL RECAUDADOR'], ['title' => '2 - PARA EL DEPOSITANTE']] as $copy)
        <div class="talon">

            <!-- Header -->
            <table style="margin-bottom: 6px; border-bottom:1px solid #000; padding-bottom:4px;">
                <tr>
                    <td style="width:40mm;">
                        <img src="{{ public_path('assets/img/logosec.PNG') }}" style="max-width:40mm;">
                    </td>
                    <td class="center title">
                        Sindicato de Empleados de Comercio La Plata
                    </td>
                    <td class="right small" style="width:50mm;">
                        <div>6 Nro. 682 - La Plata</div>
                        <div>Tel: (0221) 421-7440</div>
                        <div>Email: aportes@seclaplata.org.ar</div>
                        <div>www.seclaplata.org.ar</div>
                    </td>
                </tr>
            </table>

            <!-- Lugar y fecha -->
            <div class="right small" style="margin-bottom:10px;">
                La Plata, {{ now()->day }} de {{ now()->translatedFormat('F') }} de {{ now()->year }}
            </div>

            <!-- Título -->
            <div class="center border" style="padding:4px; margin-bottom:8px; font-size:8pt;">
                PAGO DE APORTES SINDICALES
            </div>

            <!-- Datos empresa -->
            <table style="margin-bottom: 12px; font-size:8pt;">
                <tr>
                    <td class="bold" style="width:50%; vertical-align: top;">
                        <div>Empresa: {{ $empresaCodigo }} - {{ $empresaNombre }}</div>
                        <div>Periodo: {{ $mes }} - {{ $year }}</div>
                    </td>
                    <td class="right" style="width:50%; vertical-align: top;">
                        <div>Vencimiento original: {{ date('d/m/Y', strtotime($vencori)) }}</div>
                        <div class="bold">Vencimiento para pago: {{ date('d/m/Y', strtotime($venc)) }}</div>
                    </td>
                </tr>
            </table>

            <!-- Cajas de importes -->
            <table style="width:100%; border-collapse:separate; border-spacing:15mm 2mm;">
                <tr>
                    <td class="border" style="width:50%; vertical-align:top;" colspan="2">
                        <div class="box-title">2% Art.100</div>
                        <div class="box-content">
                            <div>Nro. Aportantes: {{ $cart100 }}</div>
                            <div>Importe: {{ number_format(floatval($iart100 ?? 0),2,',','.') }}</div>
                        </div>
                    </td>
                    <td class="border" style="width:50%; vertical-align:top;" colspan="2">
                        <div class="box-title">2% Cuota Afiliación</div>
                        <div class="box-content">
                            <div>Nro. Aportantes: {{ $cafil }}</div>
                            <div>Importe: {{ number_format(floatval($iafil ?? 0),2,',','.') }}</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="border" style="width:50%; vertical-align:top;"colspan="2">
                        <div class="box-title">Otros Conceptos</div>
                        <div class="box-content">
                            <div>Importe: {{ number_format(floatval($Intereses ?? 0),2,',','.') }}</div>
                        </div>
                    </td>
                    <td class="border" style="width:50%; vertical-align:top;" colspan="2">
                        <div class="box-title">Intereses fuera de término</div>
                        <div class="box-content">
                            <div>Importe: {{ number_format(floatval($InteresesFPT ?? 0),2,',','.') }}</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">
                        <div style="margin-bottom: 4mm;font-weight: bold;">Comprobante Nro.: {{ $nrocomprobante }}</div>
                        <div class="box-title" style="border: 1px solid #000;font-weight: normal;">Centros de pago autorizados</div>
                        <div class="box-content" style="border: 1px solid #000;">
                            <div>Bco.Prov.de Buenos Aires</div>
                            <div>Provincia NET pagos</div>
                            <div>Link pagos</div>
                        </div>
                    </td>
                    <td style="vertical-align:top;font-weight: bold;" colspan="2">
                        <div>Código Link: {{ $codigoLink }}</div>
                        @php $ref = substr((string)($nrocomprobante ?? ''), -6); @endphp
                        <div>Cuota: {{ substr($ref,0,3) }}/{{ substr($ref,3) }}</div>
                    </td>
                    <!-- Total -->
                    @php
                        $total = floatval($iart100 ?? 0) + floatval($iafil ?? 0) + floatval($Intereses ?? 0) + floatval($InteresesFPT ?? 0);
                    @endphp
                    <td style="vertical-align:top;">
                        <div class="box-title" style="border: 1px solid #000;">Total a Pagar</div>
                        <div class="box-content" style="border: 1px solid #000;">
                            <div style="text-align: center;font-weight: bold">{{ number_format($total,2,',','.') }}</div>
                        </div>
                    </td>

                </tr>

            </table>

            <!-- Código de barras -->
            <div class="barcode">
                <div style="display:inline-block; max-width:500px; overflow:hidden;">
                    {!! $barcode !!}
                </div>
                <div style="font-size:8pt; margin-top:5px;">{{ $barras }}</div>
            </div>

            <!-- Copia -->
            <div class="talon-copy">{{ $copy['title'] }}</div>
        </div>

        <hr style="margin:5mm 0; border:none; border-top:1px dashed #000;">
    @endforeach

</div>
</body>
</html>
