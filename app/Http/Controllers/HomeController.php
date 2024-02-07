<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;


class HomeController extends Controller
{
    const API_URL = 'http://localhost/lv-sec-digitalizar/public/api';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*$empresas=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);
        //dd($empresas);*/



        $client = new Client();

        $response = $client->get(self::API_URL.'/empresa-usuario/' . auth()->user()->IdUsuario);

        $result = json_decode($response->getBody(), true);



        //return view('home',compact('empresas'));

        return view('home', ['empresas' => $result['result']]);
    }

    public function procesar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'empresa' => 'required',
            'mes' => 'required|numeric',
            'year' => 'required|numeric',
        ], [
            'empresa.required' => 'El campo empresa es obligatorio.',
            'mes.required' => 'El campo mes es obligatorio.',
            'mes.numeric' => 'El campo mes debe ser un valor numérico.',
            'year.required' => 'El campo año es obligatorio.',
            'year.numeric' => 'El campo año debe ser un valor numérico.',
        ]);

        // Verificar si hay errores de validación
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }



        // Obtener los datos del formulario
        $empresa = $request->input('empresa');
        $mes = $request->input('mes');
        $year = $request->input('year');

        // Realizar el procesamiento necesario aquí

        if (intval($year) * 100 + intval($mes) >= 201911) {
            /*$verifica = DB::select(DB::raw("exec DDJJ_VerificaEmpleadosPorDebajoMinimo :Param1, :Param2, :Param3"), [
                ':Param1' => $empresa,
                ':Param2' => $mes,
                ':Param3' => $year,
            ]);*/

            $client = new Client();

            $response = $client->get(self::API_URL.'/verifica-empleado-debajo-minimo/' . $empresa.'/'.$mes.'/'.$year);

            $result = json_decode($response->getBody(), true);

            //dd($result);

            // Verificar si la consulta tiene resultados
            if (!empty($result['result'])) {
                $error = "Alguno de los empleados tiene el importe para la base de la cuota de afiliación menor al permitido, por favor verifique";
                return response()->json(['errors' => $error], 422);
            }
        }

        /*$rsEmpresa=DB::select(DB::raw("exec DDJJ_EmpresaPorId :Param1"),[
            ':Param1' => $empresa,
        ]);*/

        $response = $client->get(self::API_URL.'/empresa/' . $empresa);

        $result = json_decode($response->getBody(), true);

        //dd($result);

        // Verificar si se obtuvieron resultados
        if (!empty($result['result'])) {
            $firstResult = $result['result'][0];
            // Obtener el CUIT de la empresa desde los resultados
            $cuit = trim($firstResult['Cuit']);

            // Calcular el dígito verificador del CUIT
            if (strlen($cuit) === 13) {
                $dv = (int)substr($cuit, 12, 1);
            } else {
                $dv = 10;
            }

            // Ahora $dv contiene el dígito verificador del CUIT
            // Puedes usar $dv según tus necesidades
        } else {
            // La consulta no devolvió resultados, manejar según tu lógica
            return response()->json(['errors' => 'No se encontró la empresa.'], 422);
        }


        $mes2 = intval($mes) + 1;
        if ($mes2 == 13) {
            $mes2 = 1;
            $anio = intval($year) + 1;
        } else {
            $anio = intval($year);
            //$mes2 = $mes;
        }

        Log::info('Mes: ' . $mes2, []);

        /*$vencimiento=DB::select(DB::raw("exec DDJJ_VencimientoTraer :Param1, :Param2, :Param3"),[
            ':Param1' => $mes,
            ':Param2' => $year,
            ':Param3' => $dv,
        ]);*/

        $response = $client->get(self::API_URL.'/vencimiento-traer/' . $mes.'/'.$year.'/'.$dv);

        $result = json_decode($response->getBody(), true);

        //dd($result);

// Comprobar si $vencimiento no está vacío
        if (!empty($result['result'])) {
            $firstResult = $result['result'][0];
            $fechavencimiento = $firstResult['Vencimiento'];
        } else {
            // CALCULA VENCIMIENTOS
            $fecha1o = date_create("$anio-$mes2-7");
            $fecha1 = date_create("$anio-$mes2-7");

            $fecha1String = date_format($fecha1, 'Y-m-d');

            $nohabilini = true;
            while ($nohabilini) {


                // Tu lógica para validar si la fecha es hábil
                /*$dia=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha1String,
                ]);*/
                //dd($dia);

                $response = $client->get(self::API_URL.'/valida-dia/' . $fecha1String);

                $result = json_decode($response->getBody(), true);

                //dd($result);


                if (empty($result['result'])) {
                    $nohabilini = false;
                } else {
                    $fecha1->modify('+1 day');
                    $fecha1String = date_format($fecha1, 'Y-m-d');
                }
            }

            $dias1 = date_diff($fecha1o, $fecha1)->format('%a');

            $fecha2o = date_create("$anio-$mes2-".(8 + $dias1));
            $fecha2 = date_create("$anio-$mes2-".(8 + $dias1));

            $fecha2String = date_format($fecha2, 'Y-m-d');

            $nohabilini = true;
            while ($nohabilini) {
                //Log::info('Fecha2: ' . $fecha2, []);
                //Log::info('String2: ' . $fecha2String, []);
                // Tu lógica para validar si la fecha es hábil
                $response = $client->get(self::API_URL.'/valida-dia/' . $fecha2String);

                $result = json_decode($response->getBody(), true);

                //dd($result);


                if (empty($result['result'])) {
                    $nohabilini = false;
                } else {
                    $fecha2->modify('+1 day');
                    $fecha2String = date_format($fecha2, 'Y-m-d');
                }
            }

            $dias2 = date_diff($fecha2o, $fecha2)->format('%a');

            $fecha3o = date_create("$anio-$mes2-".(9 + $dias1 + $dias2));
            $fecha3 = date_create("$anio-$mes2-".(9 + $dias1 + $dias2));

            $fecha3String = date_format($fecha3, 'Y-m-d');

            $nohabilini = true;
            while ($nohabilini) {
                //Log::info('Fecha3: ' . $fecha3, []);
                //Log::info('String3: ' . $fecha3String, []);
                // Tu lógica para validar si la fecha es hábil
                $response = $client->get(self::API_URL.'/valida-dia/' . $fecha3String);

                $result = json_decode($response->getBody(), true);

                //dd($result);


                if (empty($result['result'])) {
                    $nohabilini = false;
                } else {

                    $fecha3->modify('+1 day');
                    $fecha3String = date_format($fecha3, 'Y-m-d');

                }
            }

            $dias3 = date_diff($fecha3o, $fecha3)->format('%a');


            $fecha4o = date_create("$anio-$mes2-".(10 + $dias1 + $dias2 + $dias3));
            $fecha4 = date_create("$anio-$mes2-".(10 + $dias1 + $dias2 + $dias3));

            $fecha4String = date_format($fecha4, 'Y-m-d');

            $nohabilini = true;
            while ($nohabilini) {
                //Log::info('Fecha4: ' . $fecha4, []);
                //Log::info('String4: ' . $fecha4String, []);
                // Tu lógica para validar si la fecha es hábil
                $response = $client->get(self::API_URL.'/valida-dia/' . $fecha4String);

                $result = json_decode($response->getBody(), true);

                //dd($result);


                if (empty($result['result'])) {
                    $nohabilini = false;
                } else {
                    $fecha4->modify('+1 day');
                    $fecha4String = date_format($fecha4, 'Y-m-d');
                }
            }

            $dias4 = date_diff($fecha4o, $fecha4)->format('%a');


            $fecha5o = date_create("$anio-$mes2-".(11 + $dias1 + $dias2 + $dias3 + $dias4));
            $fecha5 = date_create("$anio-$mes2-".(11 + $dias1 + $dias2 + $dias3 + $dias4));

            $fecha5String = date_format($fecha5, 'Y-m-d');

            $nohabilini = true;
            while ($nohabilini) {
                //Log::info('Fecha5: ' . $fecha5, []);
                //Log::info('String5: ' . $fecha5String, []);
                // Tu lógica para validar si la fecha es hábil
                $response = $client->get(self::API_URL.'/valida-dia/' . $fecha5String);

                $result = json_decode($response->getBody(), true);

                //dd($result);


                if (empty($result['result'])) {
                    $nohabilini = false;
                } else {
                    $fecha5->modify('+1 day');
                    $fecha5String = date_format($fecha5, 'Y-m-d');
                }
            }



            // FIN CALCULO VENCIMIENTOS

            if ($dv <= 1) {
                $fechavencimiento = $fecha1->format('Y-m-d');
            } elseif ($dv > 1 && $dv <= 3) {
                $fechavencimiento = $fecha2->format('Y-m-d');
            } elseif ($dv > 3 && $dv <= 5) {
                $fechavencimiento = $fecha3->format('Y-m-d');
            } elseif ($dv > 5 && $dv <= 7) {
                $fechavencimiento = $fecha4->format('Y-m-d');
            } elseif ($dv > 7 && $dv <= 10) {
                $fechavencimiento = $fecha5->format('Y-m-d');
            }
        }

        $venc = ($request->input('venc'))?$request->input('venc'):$fechavencimiento;
        //$venc = $fechavencimiento;

        $vencinicial = $fechavencimiento;

        //$fechaVencInicialString = date_format($vencinicial, 'Y-m-d');

        $nohabilini = true;

        while ($nohabilini) {

            /*$dia=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                ':Param1' => $vencinicial,
            ]);*/

            $response = $client->get(self::API_URL.'/valida-dia/' . $vencinicial);

            $result = json_decode($response->getBody(), true);

            //dd($result);


            if (empty($result['result'])) {
                $nohabilini = false;
            } else {
                $vencinicial->modify('+1 day');
            }
        }


        $periodoboleta = intval($year) * 100 + intval($mes);
        $periodo = $anio * 100 + $mes2;
        $periodohoy = date('Y') * 100 + date('m');


        if ($periodo >= $periodohoy) {
            $nohabil = true;
            while ($nohabil) {
                //$fechaVencString = date_format($venc, 'Y-m-d');
                /*$dia=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $venc,
                ]);*/

                $response = $client->get(self::API_URL.'/valida-dia/' . $venc);

                $result = json_decode($response->getBody(), true);

                //dd($result);


                if (empty($result['result'])) {
                    $nohabil = false;
                } else {
                    $venc = strtotime('+1 day', $venc);
                }
            }

            if (time() > $venc) {
                $ingresafecha = true;
            } else {
                $ingresafecha = false;
            }
        } else {
            $ingresafecha = true;
        }



        /*$rsTotales2 = DB::select(DB::raw("exec DDJJ_BoletaPagoImpresion :Param1, :Param2, :Param3"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
        ]);*/

        $response = $client->get(self::API_URL.'/boleta-pago-impresion/' . $empresa.'/'. $mes.'/'.$year);

        $result = json_decode($response->getBody(), true);

        //dd($result);


        if (!empty($result['result'])) {
            $firstResult = $result['result'][0];
            $cantArt100 = $firstResult['CantArt100'];
            $cantAfi = $firstResult['CantAfi'];
            $importeArt100 = $firstResult['ImporteArt100'];
            $importeArt100Total = $firstResult['ImporteArt100Total'];
            $importeCuotaAfi = $firstResult['ImporteCuotaAfi'];
            $importeCuotaAfiTotal = $firstResult['ImporteCuotaAfiTotal'];

            $tot = doubleval($importeArt100) + doubleval($importeCuotaAfi);
            $tot2 = doubleval($importeArt100Total) + doubleval($importeCuotaAfiTotal);
        }

        /*$rsNumero = DB::select(DB::raw("exec DDJJ_NumeroMensual :Param1, :Param2, :Param3"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
        ]);*/

        $response = $client->get(self::API_URL.'/numero-mensual/' . $empresa.'/'. $mes.'/'.$year);

        $result = json_decode($response->getBody(), true);

        //dd($result);


        if (!empty($result['result'])) {
            $firstResult = $result['result'][0];
        }

        /*$rsPorcentaje = DB::select(DB::raw("exec DDJJ_PorcentajeInteresTraer"), [

        ]);*/

        $response = $client->get(self::API_URL.'/porcentaje-interes-traer');

        $result = json_decode($response->getBody(), true);

        //dd($result);


        if (!empty($result['result'])) {
            $firstResult = $result['result'][0];
            $porcentaje = $firstResult['Porcentaje'];
        }



        $vencini = date_create($vencinicial);
        $venc = date_create($venc);
        $vencinic = date_create($vencinicial);
        $dias2 = 0;

        if ($vencini < $venc) {
            if ($vencini >= date_create("19/03/2020") && $vencini < date_create("30/04/2020")) {
                $vencinic = date_create("30/04/2020");
            }
            if ($vencini < date_create("19/03/2020")) {
                $venc2 = date_create("19/03/2020");
                $dias2 = date_diff($vencini, $venc2)->days;
                $vencinic = date_create("30/04/2020");
            }

            $dias = date_diff($vencinic, $venc)->days + $dias2;
        } else {
            $dias = 0;
        }


        Log::info('Total: ' . $tot.' porcentaje: '.$porcentaje.' dias: '.$dias, []);

        $intereses = (doubleval($tot) * doubleval($porcentaje) / 100) * doubleval($dias);





        // Ejemplo: Crear la tabla HTML
        $tablaHtml = '<table style="width: 100%;">
<tbody><tr>
<th>
&nbsp;
</th>
<th style="border: 1px solid; text-align: center">
<b>Nro Aportantes</b>
</th>
<th style="border: 1px solid; text-align: center">
<b>Base para cálculo</b>
</th>
<th style="border: 1px solid; text-align: center">
<b>Importe a pagar</b>
</th>
</tr>
<tr>
<td style="border: 1px solid">
<b>Artículo 100</b>
</td>
<td style="border: 1px solid; text-align: right">';

        $tablaHtml .=$cantArt100;
        $tablaHtml .='
</td>
<td style="border: 1px solid; text-align: right">';
        $tablaHtml .=number_format($importeArt100Total,2,',','.');
        $tablaHtml .='
</td>
<td style="border: 1px solid; text-align: right">';
        $tablaHtml .=number_format($importeArt100,2,',','.');
        $tablaHtml .='

</td>
</tr>
<tr>
<td style="border: 1px solid">
<b>Afiliados</b>
</td>
<td style="border: 1px solid; text-align: right">';

        $tablaHtml .=$cantAfi;
        $tablaHtml .='
</td>
<td style="border: 1px solid; text-align: right">';
        $tablaHtml .=number_format($importeCuotaAfiTotal,2,',','.');
        $tablaHtml .='
</td>
<td style="border: 1px solid; text-align: right">';
        $tablaHtml .=number_format($importeCuotaAfi,2,',','.');
        $tablaHtml .='

</td>
</tr>
<!--<tr>
<td style="border: 1px solid">
<b>Otros Conceptos</b>
</td>
<td style="border: 1px solid">
&nbsp;
</td>
<td style="border: 1px solid">
&nbsp;
</td>
<td style="border: 1px solid">
<input type="text" id="txtIntereses" name="txtIntereses" size="12" maxlength="12" class="noobligatorio" style="text-align:right;" onkeypress="return SoloNumeroDecimal(event.keyCode)" onblur="this.value=formateaNumeroConComa(this.value, 12, 9, 2);CambiaImporte();">
</td>
</tr>
<tr>
<td style="border: 1px solid">
<b>Intereses pago fuera de término:</b>
</td>
<td style="border: 1px solid">
&nbsp;
</td>
<td style="border: 1px solid">
&nbsp;
</td>
<td style="border: 1px solid">
<input type="text" id="txtInteresesPFT" name="txtInteresesPFT" size="12" maxlength="12" class="noobligatorio" style="text-align:right;" disabled="" value="2.378,80">
</td>
</tr>
<tr>-->
<td style="border: 2px solid; font-weight: bold" colspan="2">
<strong>Totales</strong>
</td>

<td style="border: 2px solid;text-align: right; font-weight: bold">';
        $tablaHtml .='<strong>'.number_format($tot2,2,',','.').'</strong>';
        $tablaHtml .='
</td>
<td style="border: 2px solid; text-align: right; font-weight: bold">';
        $tablaHtml .='<strong>'.number_format($tot,2,',','.').'</strong>';
        $tablaHtml .='

</td>
</tr>

</tbody></table>';
        // ... (Agrega el contenido de la tabla aquí)
        //$tablaHtml .= '</table>';

        // Devolver la tabla HTML como respuesta
        return response()->json(['tabla' => $tablaHtml, 'original' => date_format($vencini, 'Y-m-d'), 'vencimiento' => date_format($venc, 'Y-m-d'),'intereses'=>number_format($intereses,2,',','.'),'total'=>number_format($tot+$intereses,2,',','.')]);
    }



}
