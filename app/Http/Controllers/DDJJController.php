<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Milon\Barcode\DNS1D;
use App\Traits\SanitizesInput;
use Carbon\Carbon;
class DDJJController extends Controller
{
    use SanitizesInput;

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
        $empresas=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);

        return view('ddjjs.ddjj', ['empresas' => $empresas]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anteriores(Request $request)
    {


        $year=($request->query('year'))?$request->query('year'):null;

        $empresas=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);


        $anteriores=array();
        if($request->query('empresa')) {
            $empresa = $request->query('empresa');

            $anteriores=DB::select(DB::raw("exec DDJJ_ConsultaHistorialTotales :Param1,:Param2"),[
                ':Param1' => $empresa,
                ':Param2' => $year,
            ]);
            foreach ($anteriores as &$a) {
                $a->anterioresAnt = DB::select(
                    DB::raw("EXEC DDJJ_ConsultaHistorialTotalesVencAnteriores :Param1,:Param2,:Param3,:Param4"),[
                    ':Param1' => $empresa,
                    ':Param2' => $year,
                    ':Param3' => $a->Mes,
                    ':Param4' => $a->NumeroEnvio,
                ]);

            }

        }


        return view('ddjjs.anteriores', ['empresas' => $empresas,'anteriores' => $anteriores]);

    }

    public function ver($empresa, $anio, $mes, $envio)
    {


        $empleados = DB::select(DB::raw("EXEC DDJJ_ConsultaHistorial :Param1,:Param2,:Param3,:Param4"),[
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $anio,
            ':Param4' => $envio,
        ]);

        $ddjjTotales = DB::select(DB::raw("EXEC DDJJ_ConsultaHistorialTotalesDDJJ :Param1,:Param2,:Param3,:Param4"),[
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $anio,
            ':Param4' => $envio,
        ]);



        //dd($ddjjTotales);
        return view('ddjjs.ver', compact('empleados','ddjjTotales', 'empresa', 'anio', 'mes', 'envio'));
    }


    public function procesar(Request $request)
    {

        // Obtener los datos del formulario
        $empresa = $this->sanitizeInput($request->input('empresa'));
        $request->session()->put('filtro_empresa', $empresa);
        $mes = $this->sanitizeInput($request->input('mes'));
        $request->session()->put('filtro_mes', $mes);
        $year = $this->sanitizeInput($request->input('year'));
        $request->session()->put('filtro_year', $year);

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
            //return response()->json(['errors' => $validator->errors()->all()], 422);
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }





        //$client = new Client();
        // Realizar el procesamiento necesario aquí

        if (intval($year) * 100 + intval($mes) >= 201911) {
            $verifica = DB::select(DB::raw("exec DDJJ_VerificaEmpleadosPorDebajoMinimo :Param1, :Param2, :Param3"), [
                ':Param1' => $empresa,
                ':Param2' => $mes,
                ':Param3' => $year,
            ]);



            /*$response = $client->get(\Constants\Constants::API_URL.'/verifica-empleado-debajo-minimo/' . $empresa.'/'.$mes.'/'.$year);

            $result = json_decode($response->getBody(), true);*/

            //dd($verifica);

            // Verificar si la consulta tiene resultados
            if (!empty($verifica)) {
                $error = "Alguno de los empleados tiene el importe para la base de la cuota de afiliación menor al permitido, por favor verifique";
                //return response()->json(['errors' => $error], 422);
                return response()->json(['errors' => ['debajo_minimo' => [$error]]], 422);
            }
        }

        $rsEmpresa=DB::select(DB::raw("exec DDJJ_EmpresaPorId :Param1"),[
            ':Param1' => $empresa,
        ]);

        /*$response = $client->get(\Constants\Constants::API_URL.'/empresa/' . $empresa);

        $result = json_decode($response->getBody(), true);*/

        //dd($rsEmpresa);

        // Verificar si se obtuvieron resultados
        if (!empty($rsEmpresa)) {
            $firstResult = $rsEmpresa[0];
            // Obtener el CUIT de la empresa desde los resultados
            $cuit = trim($firstResult->Cuit);

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

        //Log::info('Mes: ' . $mes2, []);

        /*$vencimiento=DB::select(DB::raw("exec DDJJ_VencimientoTraer :Param1, :Param2, :Param3"),[
            ':Param1' => $mes,
            ':Param2' => $year,
            ':Param3' => $dv,
        ]);*/

        /*$response = $client->get(\Constants\Constants::API_URL.'/vencimiento-traer/' . $mes.'/'.$year.'/'.$dv);

        $result = json_decode($response->getBody(), true);*/

        $result=DB::select(DB::raw("exec DDJJ_VencimientoTraer :Param1, :Param2, :Param3"),[
            ':Param1' => $mes,
            ':Param2' => $year,
            ':Param3' => $dv,
        ]);

        //dd($result);

// Comprobar si $vencimiento no está vacío
        if (!empty($result)) {
            $firstResult = $result[0];
            $fechavencimiento = $firstResult->Vencimiento;
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

                /*$response = $client->get(\Constants\Constants::API_URL.'/valida-dia/' . $fecha1String);

                $result = json_decode($response->getBody(), true);*/
                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha1String,
                ]);

                //dd($result);


                if (empty($results)) {
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
                /*$response = $client->get(\Constants\Constants::API_URL.'/valida-dia/' . $fecha2String);

                $result = json_decode($response->getBody(), true);*/
                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha2String,
                ]);
                //dd($result);


                if (empty($results)) {
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
                /*$response = $client->get(\Constants\Constants::API_URL.'/valida-dia/' . $fecha3String);

                $result = json_decode($response->getBody(), true);*/

                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha3String,
                ]);

                //dd($result);


                if (empty($results)) {
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
                /*$response = $client->get(\Constants\Constants::API_URL.'/valida-dia/' . $fecha4String);

                $result = json_decode($response->getBody(), true);*/

                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha4String,
                ]);

                //dd($result);


                if (empty($results)) {
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
                /*$response = $client->get(\Constants\Constants::API_URL.'/valida-dia/' . $fecha5String);

                $result = json_decode($response->getBody(), true);*/

                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha5String,
                ]);

                //dd($result);


                if (empty($results)) {
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
        Log::info('Vencimiento: ' . $request->input('venc').' venc: '.$fechavencimiento, []);
        $venc = ($request->input('venc'))?$this->sanitizeInput($request->input('venc')):$fechavencimiento;

        Log::info('Vencimiento final: ' .$venc , []);

        //$venc = $fechavencimiento;

        $vencinicial = $fechavencimiento;

        //$fechaVencInicialString = date_format($vencinicial, 'Y-m-d');

        $nohabilini = true;

        while ($nohabilini) {

            $dia=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                ':Param1' => $vencinicial,
            ]);

            /*$response = $client->get(\Constants\Constants::API_URL.'/valida-dia/' . $vencinicial);

            $result = json_decode($response->getBody(), true);*/

            //dd($result);


            if (empty($dia)) {
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
                $dia=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $venc,
                ]);

                /*$response = $client->get(\Constants\Constants::API_URL.'/valida-dia/' . $venc);

                $result = json_decode($response->getBody(), true);*/

                //dd($result);


                if (empty($dia)) {
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
        $rsUltimoPeriodo = DB::select(DB::raw("exec DDJJ_UltimoPeriodoPagoSEC :Param1"), [
            ':Param1' => $empresa
        ]);
        $tablaHtmlAnterior = '';
        //dd(trim($rsUltimoPeriodo[0]->Anio));
        if (!empty($rsUltimoPeriodo)&&trim($rsUltimoPeriodo[0]->Anio)!="0") {
            $tablaHtmlAnterior .= '<div style="width: 50%; padding: 0 2%;"><span style="font-size: 1.25rem"> Último Periodo Pago Registrado en el SEC</span>';


            $firstResult = $rsUltimoPeriodo[0];
            if ($firstResult->Mes) {
                $mesAnterior = $firstResult->Mes;
                $yearAnterior = $firstResult->Anio;
                $cantArt100 = $firstResult->CantArt100;
                $cantAfi = $firstResult->CantAfi;
                $importeArt100 = $firstResult->ImporteArt100;
                //$importeArt100Total = $firstResult->ImporteArt100Total;
                $importeCuotaAfi = $firstResult->ImporteCuotaAfi;
                $tot = $firstResult->Importe_Total;
                //$importeCuotaAfiTotal = $firstResult->ImporteCuotaAfiTotal;

                /*$tot = doubleval($importeArt100) + doubleval($importeCuotaAfi);
                $tot2 = doubleval($importeArt100Total) + doubleval($importeCuotaAfiTotal);*/
                $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Período '.$mesAnterior.'-'.$yearAnterior.'</span>
                                    <table id="tablePeriodo" style="width: 100%; font-size: 20px;">
                                    <tbody><tr>
                                    <th>
                                    &nbsp;
                                    </th>
                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Nro Aportantes</b>
                                    </th>

                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Importe</b>
                                    </th>
                                    </tr>
                                    <tr>
                                    <td style="border: 1px solid black">
                                    <b>Artículo 100</b>
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';

                $tablaHtmlAnterior .=$cantArt100;
                $tablaHtmlAnterior .='
                                    </td>

                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeArt100,2,',','.');
                $tablaHtmlAnterior .='

                                                </td>
                                    </tr>
                                    <tr>
                                    <td style="border: 1px solid black">
                                    <b>Afiliados</b>
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';

                $tablaHtmlAnterior .=$cantAfi;
                $tablaHtmlAnterior .='
                                    </td>

                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeCuotaAfi,2,',','.');
                $tablaHtmlAnterior .='

                                                </td>
                                    </tr>

                                    <td style="border: 2px solid black; font-weight: bold" colspan="2">
                                    <strong>Totales</strong>
                                    </td>


                                    <td style="border: 2px solid black; text-align: right; font-weight: bold">';
                $tablaHtmlAnterior .='<strong>'.number_format($tot,2,',','.').'</strong>';
                $tablaHtmlAnterior .='

                                    </td>
                                    </tr>

                                    </tbody></table>';
            }
            else{
                $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Sin Información Registrada en el SEC</span>';
            }

        }

        $tablaHtmlAnterior .= '</div>';

        $rsUltimaPresentada = DB::select(DB::raw("exec DDJJ_UltimaPresentadaSEC :Param1"), [
            ':Param1' => $empresa
        ]);
        $tablaHtmlAnterior .= '<div style="width: 50%"><span style="font-size: 1.25rem">Última DDJJ Registrada en el SEC</span>';
        if (!empty($rsUltimaPresentada)) {

            $firstResult = $rsUltimaPresentada[0];
            if ($firstResult->Mes) {
                $mesAnterior = $firstResult->Mes;
                $yearAnterior = $firstResult->Anio;
                $cantArt100 = $firstResult->CantArt100;
                $cantAfi = $firstResult->CantAfi;
                $importeArt100 = $firstResult->ImporteArt100;
                $importeArt100Total = $firstResult->ImporteArt100Total;
                $importeCuotaAfi = $firstResult->ImporteCuotaAfi;
                $importeCuotaAfiTotal = $firstResult->ImporteCuotaAfiTotal;

                $tot = doubleval($importeArt100) + doubleval($importeCuotaAfi);
                $tot2 = doubleval($importeArt100Total) + doubleval($importeCuotaAfiTotal);
                $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Período '.$mesAnterior.'-'.$yearAnterior.'</span>
                                    <table id="tableAnterior" style="width: 100%; font-size: 20px;">
                                    <tbody><tr>
                                    <th>
                                    &nbsp;
                                    </th>
                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Nro Aportantes</b>
                                    </th>
                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Base para cálculo</b>
                                    </th>
                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Importe a pagar</b>
                                    </th>
                                    </tr>
                                    <tr>
                                    <td style="border: 1px solid black">
                                    <b>Artículo 100</b>
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';

                $tablaHtmlAnterior .=$cantArt100;
                $tablaHtmlAnterior .='
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeArt100Total,2,',','.');
                $tablaHtmlAnterior .='
                                                </td>
                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeArt100,2,',','.');
                $tablaHtmlAnterior .='

                                                </td>
                                    </tr>
                                    <tr>
                                    <td style="border: 1px solid black">
                                    <b>Afiliados</b>
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';

                $tablaHtmlAnterior .=$cantAfi;
                $tablaHtmlAnterior .='
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeCuotaAfiTotal,2,',','.');
                $tablaHtmlAnterior .='
                                                </td>
                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeCuotaAfi,2,',','.');
                $tablaHtmlAnterior .='

                                                </td>
                                    </tr>

                                    <td style="border: 2px solid black; font-weight: bold" colspan="2">
                                    <strong>Totales</strong>
                                    </td>

                                    <td style="border: 2px solid black;text-align: right; font-weight: bold">';
                $tablaHtmlAnterior .='<strong>'.number_format($tot2,2,',','.').'</strong>';
                $tablaHtmlAnterior .='
                                    </td>
                                    <td style="border: 2px solid black; text-align: right; font-weight: bold">';
                $tablaHtmlAnterior .='<strong>'.number_format($tot,2,',','.').'</strong>';
                $tablaHtmlAnterior .='

                                    </td>
                                    </tr>

                                    </tbody></table>';
            }
            else{
                $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Sin Información Registrada en el SEC</span>';
            }

        }
        else{
            $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Sin Información Registrada en el SEC</span>';
        }

        $tablaHtmlAnterior .= '</div>';




        $rsTotales2 = DB::select(DB::raw("exec DDJJ_BoletaPagoImpresion :Param1, :Param2, :Param3"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
        ]);




        if (!empty($rsTotales2)) {
            $firstResult = $rsTotales2[0];
            $cantArt100 = $firstResult->CantArt100;
            $cantAfi = $firstResult->CantAfi;
            $importeArt100 = $firstResult->ImporteArt100;
            $importeArt100Total = $firstResult->ImporteArt100Total;
            $importeCuotaAfi = $firstResult->ImporteCuotaAfi;
            $importeCuotaAfiTotal = $firstResult->ImporteCuotaAfiTotal;

            $tot = doubleval($importeArt100) + doubleval($importeCuotaAfi);
            $tot2 = doubleval($importeArt100Total) + doubleval($importeCuotaAfiTotal);
        }

        $rsNumero = DB::select(DB::raw("exec DDJJ_NumeroMensual :Param1, :Param2, :Param3"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
        ]);




        if (!empty($rsNumero)) {
            $firstResult = $rsNumero[0];
            $existeDeclaracion = $firstResult->Numero;
        }

        $rsPorcentaje = DB::select(DB::raw("exec DDJJ_PorcentajeInteresTraer"), [

        ]);




        if (!empty($rsPorcentaje)) {
            $firstResult = $rsPorcentaje[0];
            $porcentaje = $firstResult->Porcentaje;
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
        $tablaHtml = '<table id="tableEmpleados" style="width: 100%; color: white;font-size: 20px;">
<tbody><tr>
<th>
&nbsp;
</th>
<th style="border: 1px solid black; text-align: center">
<b>Nro Aportantes</b>
</th>
<th style="border: 1px solid black; text-align: center">
<b>Base para cálculo</b>
</th>
<th style="border: 1px solid black; text-align: center">
<b>Importe a pagar</b>
</th>
</tr>
<tr>
<td style="border: 1px solid black">
<b>Artículo 100</b>
</td>
<td style="border: 1px solid black; text-align: right">';

        $tablaHtml .=$cantArt100;
        $tablaHtml .='
</td>
<td style="border: 1px solid black; text-align: right">';
        $tablaHtml .=number_format($importeArt100Total,2,',','.');
        $tablaHtml .='
</td>
<td style="border: 1px solid black; text-align: right">';
        $tablaHtml .=number_format($importeArt100,2,',','.');
        $tablaHtml .='

</td>
</tr>
<tr>
<td style="border: 1px solid black">
<b>Afiliados</b>
</td>
<td style="border: 1px solid black; text-align: right">';

        $tablaHtml .=$cantAfi;
        $tablaHtml .='
</td>
<td style="border: 1px solid black; text-align: right">';
        $tablaHtml .=number_format($importeCuotaAfiTotal,2,',','.');
        $tablaHtml .='
</td>
<td style="border: 1px solid black; text-align: right">';
        $tablaHtml .=number_format($importeCuotaAfi,2,',','.');
        $tablaHtml .='

</td>
</tr>
<!--<tr>
<td style="border: 1px solid black">
<b>Otros Conceptos</b>
</td>
<td style="border: 1px solid black">
&nbsp;
</td>
<td style="border: 1px solid black">
&nbsp;
</td>
<td style="border: 1px solid black">
<input type="text" id="txtIntereses" name="txtIntereses" size="12" maxlength="12" class="noobligatorio" style="text-align:right;" onkeypress="return SoloNumeroDecimal(event.keyCode)" onblur="this.value=formateaNumeroConComa(this.value, 12, 9, 2);CambiaImporte();">
</td>
</tr>
<tr>
<td style="border: 1px solid black">
<b>Intereses pago fuera de término:</b>
</td>
<td style="border: 1px solid black">
&nbsp;
</td>
<td style="border: 1px solid black">
&nbsp;
</td>
<td style="border: 1px solid black">
<input type="text" id="txtInteresesPFT" name="txtInteresesPFT" size="12" maxlength="12" class="noobligatorio" style="text-align:right;" disabled="" value="2.378,80">
</td>
</tr>
<tr>-->
<td style="border: 2px solid black; font-weight: bold" colspan="2">
<strong>Totales</strong>
</td>

<td style="border: 2px solid black;text-align: right; font-weight: bold">';
        $tablaHtml .='<strong>'.number_format($tot2,2,',','.').'</strong>';
        $tablaHtml .='
</td>
<td style="border: 2px solid black; text-align: right; font-weight: bold">';
        $tablaHtml .='<strong>'.number_format($tot,2,',','.').'</strong>';
        $tablaHtml .='

</td>
</tr>

</tbody></table>';
        // ... (Agrega el contenido de la tabla aquí)
        //$tablaHtml .= '</table>';
        //Log::info('Vencimiento pasado: ' .date_format($venc, 'Y-m-d') , []);
        // Devolver la tabla HTML como respuesta
        return response()->json(['tabla' => $tablaHtml, 'tablaAnterior' => $tablaHtmlAnterior, 'original' => date_format($vencini, 'Y-m-d'), 'vencimiento' => date_format($venc, 'Y-m-d'),'intereses'=>number_format($intereses,2,',','.'),'total'=>number_format($tot+$intereses,2,',','.'),'existeDeclaracion' => $existeDeclaracion]);
    }



    public function generar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'empresa' => 'required',
            'mes' => 'required|numeric',
            'year' => 'required|numeric',
            'venc' => 'required|date',
        ], [
            'empresa.required' => 'El campo empresa es obligatorio.',
            'mes.required' => 'El campo mes es obligatorio.',
            'mes.numeric' => 'El campo mes debe ser un valor numérico.',
            'year.required' => 'El campo año es obligatorio.',
            'year.numeric' => 'El campo año debe ser un valor numérico.',
            'venc.required' => 'El campo estimada de pago es obligatorio.',
            'venc.date' => 'El campo estimada de pago debe ser una fecha válida.',
        ]);

        // Verificar si hay errores de validación
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }


        // Obtener los datos del formulario
        $empresa = $this->sanitizeInput($request->input('empresa'));
        $mes = $this->sanitizeInput($request->input('mes'));
        $year = $this->sanitizeInput($request->input('year'));
        $venc = $this->sanitizeInput($request->input('venc'));
        $intereses = $this->sanitizeInput($request->input('intereses'));
        $vencimientoOriginal = $this->sanitizeInput($request->input('vencOri'));



        $results = DB::select(DB::raw("exec DDJJ_BoletaPagoImpresion :Param1, :Param2, :Param3"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
        ]);

        //dd($result);


        if (!empty($results)) {
            $firstResult = $results[0];
            $carti100 = $firstResult->CantArt100;
            //Log::info('CantArt100: ' . $carti100, []);
            $cart100 = str_pad(strval($carti100), 4, "0", STR_PAD_LEFT);
            $iarti100 = $firstResult->ImporteArt100;
            //Log::info('ImporteArt100: ' . $iarti100, []);
            $iart100 = str_pad(strval($iarti100), 8, "0", STR_PAD_LEFT);
            $cafil = $firstResult->CantAfi;
            //Log::info('CantAfi: ' . $cafil, []);
            $cafi = str_pad(strval($cafil), 4, "0", STR_PAD_LEFT);
            $iafil = $firstResult->ImporteCuotaAfi;
            //Log::info('ImporteCuotaAfi: ' . $iafil, []);
            $iafi = str_pad(strval($iafil), 8, "0", STR_PAD_LEFT);

            $iafi = floatval($iafil);
            /*Log::info('ImporteCuotaAfi: ' . $iafil, []);
            Log::info('Intereses: ' . $intereses, []);*/

            $intereses = str_replace('.', '', $intereses);
            $intereses = str_replace(',', '.', $intereses);

            // Convertir la cadena a un valor decimal
            $intereses = floatval($intereses);




            $total = number_format($iarti100 + $iafi + $intereses, 2, '.', '');

            $tot = strval(number_format(floatval($total) * 100, 0, '.', ''));
            $tot = str_pad($tot, 9, "0", STR_PAD_LEFT);
        }


        if (strlen($carti100) > 4) {
            return response()->json(['errors' => array('Valores incorrectos, por favor verifique')], 422);
        }

        if (strlen($cafil) > 4) {
            return response()->json(['errors' => array('Valores incorrectos, por favor verifique')], 422);
        }



        $results=DB::select(DB::raw("exec DDJJ_EmpresaPorId :Param1"),[
            ':Param1' => $empresa,
        ]);

        // Verificar si se obtuvieron resultados
        if (!empty($results)) {
            $firstResult = $results[0];
            $produccion = ($firstResult->Codigo == 4189) ? 0 : 1;
            $empresaCodigo = strval($firstResult->Codigo);

            $empresaNombre = strval($firstResult->NombreReal);

            $empresaCodigo = str_pad(strval($empresaCodigo), 5, "0", STR_PAD_LEFT);

            $empresa2 = str_pad(strval($empresaCodigo), 9, "0", STR_PAD_LEFT);

            $nombre = $empresaCodigo . substr(strval($year), 2, 2) . $mes;
        }

        /*$response = $client->get(\Constants\Constants::API_URL . '/numero-mensual/' . $empresa . '/' . $mes . '/' . $year);

        $result = json_decode($response->getBody(), true);*/

        //dd($result);
        $results = DB::select(DB::raw("exec DDJJ_NumeroMensual :Param1, :Param2, :Param3"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
        ]);

        $numero = 0;
        if (!empty($results)) {
            $firstResult = $results[0];
            $numeroActual = $firstResult->Numero;
            $numero = $firstResult->Numero;
        }

        $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);

        $numero++;

        /*$response = $client->put(\Constants\Constants::API_URL . '/guardar-ddjj/' . $empresa . '/' . $mes . '/' . $year . '/0/' . $numero . '/' . $intereses . '/' . $venc . '/' . $vencimientoOriginal . '/0/' . auth()->user()->IdUsuario);*/

        $data = [
            'empresa' => $empresa,
            'mes' => $mes,
            'year' => $year,
            'intereses' => 0,
            'numero' => $numero,
            'interesesPFT' => $intereses,
            'vencimiento' => $venc,
            'vencimientoOriginal' => $vencimientoOriginal,
            'mp' => 0,  // Si no se usa, puedes dejarlo en 0 o eliminarlo
            'idUsuario' => auth()->user()->IdUsuario
        ];

        /*$response = $client->put(\Constants\Constants::API_URL . '/guardar-ddjj', [
            'json' => $data
        ]);

        $result = json_decode($response->getBody(), true);*/

        $vencimiento = date('Y-m-d', strtotime($venc));
        $vencimientoOriginal = date('Y-m-d', strtotime($vencimientoOriginal));
        $empresa = intval($empresa);
        $mes = intval($mes);
        $year = intval($year);
        $numero = intval($numero);

        $idUsuario = intval(auth()->user()->IdUsuario);
        $error='';

        try {
            DB::enableQueryLog();


            DB::statement("exec DDJJ_GuardarDDJJ ?, ?, ?, ?, ?, ?, ?, ?, ?, ?", [
                $empresa,
                $mes,
                $year,
                0,
                $numero,
                $intereses,
                $vencimiento,
                $vencimientoOriginal,
                null,
                $idUsuario
            ]);

            // Tu lógica de actualización aquí

            //return response()->json(['message' => 'Datos actualizados con éxito']);
        } catch (QueryException $e) {
            // Aquí manejas la excepción
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();

            // Obtén los parámetros utilizados en la llamada al procedimiento almacenado
            $parametros = [
                'empresa' => $empresa,
                'mes' => $mes,
                'year' => $year,
                'intereses' => 0,
                'numero' => $numero,
                'interesesPFT' => $intereses,
                'vencimiento' => $vencimiento,
                'vencimientoOriginal' => $vencimientoOriginal,
                'mp' => null,
                'idUsuario' => $idUsuario,
            ];

            // Log de la excepción o cualquier otro manejo que necesites



            //Log::debug('SQL Queries: '.json_encode(DB::getQueryLog()));

            // Devuelve una respuesta indicando que ha ocurrido un error
            //return response()->json(['error' => 'Ha ocurrido un error al procesar la solicitud'], 500);
            $error='Ha ocurrido un error al procesar la solicitud';
        }



        /*$response = $client->put(\Constants\Constants::API_URL . '/guardar-ddjj-comprobante/' . $empresa . '/' . $mes . '/' . $year . '/' . $numero);

        $result = json_decode($response->getBody(), true);*/

        $results = DB::select(DB::raw("exec DDJJ_GuardarDDJJTraerComprobante :Param1, :Param2, :Param3, :Param4"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
            ':Param4' => $numero

        ]);

        if (!empty($results)) {
            $firstResult = $results[0];
            $nroComprobante = $firstResult->NroComprobante;


            $nroComprobante = str_pad($nroComprobante, 10, "0", STR_PAD_LEFT);
            Log::info('Comprobante: ' . $nroComprobante, []);
        }

        $fechaPartes = explode("-", $venc);
        $vencimiento = $fechaPartes[2] . $fechaPartes[1] . $fechaPartes[0];

        $barras = "2861" . $empresaCodigo . $nroComprobante . "1" . $vencimiento . $tot;

        Log::info('Barras: ' . $barras, []);

        $sumete = doubleval(substr($barras, 0, 1));
        $ponderador = 3;

        for ($i = 1; $i < strlen($barras); $i++) {
            $sumete += doubleval(substr($barras, $i, 1)) * $ponderador;
            $ponderador += 2;

            if ($ponderador == 11) {
                $ponderador = 3;
            }
        }

        $sumete1 = $sumete / 2;
        $sumete2 = intval($sumete1) / 10;
        $dv = ltrim(strval(intval(round(($sumete2 - intval($sumete2)) * 10))));
        $barras .= $dv;

        $empresaStr = $empresaCodigo . ' - ' . $empresaNombre;


        $proceso = $mes . "-" . $year;

        /*$response = $client->get(\Constants\Constants::API_URL . '/central-pagos/' . $produccion);

        $result = json_decode($response->getBody(), true);*/

        //dd($result);

        $results = DB::select(DB::raw("exec ADM_CentralPagosDatosTraer :Param1"), [
            ':Param1' => $produccion,
        ]);

        if (!empty($results)) {
            $firstResult = $results[0];
            $token = $firstResult->Token;
            Log::info('Token: ' . $token, []);
        }

        $ml = (date("m") < 10) ? "0" . date("m") : date("m");
        $dl = (date("d") < 10) ? "0" . date("d") : date("d");
        $hl = (date("H") < 10) ? "0" . date("H") : date("H");
        $mil = (date("i") < 10) ? "0" . date("i") : date("i");
        $sl = (date("s") < 10) ? "0" . date("s") : date("s");

        $fechalote = date("Y") . "-" . $ml . "-" . $dl . "T" . $hl . ":" . $mil . ":" . $sl . "Z";
        $vencimientocp = $venc . " 23:59:59";
        Log::info('Lote: ' . $fechalote, []);
        Log::info('Vencimiento: ' . $vencimientocp, []);

        $obs = "Periodo:" . $proceso;
        if ($numeroActual <> 1) {

            $obs = $obs . " R:" .$numeroActual;
        }

        Log::info('Obs: ' . $obs, []);

        $totcp = floatval($total) * 100;

        Log::info('totcp: ' . $totcp, []);

        $longi = strlen(strval($nroComprobante));
        $desde = $longi - 5 + 1;

        Log::info('desde: ' . $desde, []);

        $code = strval($nroComprobante);
        $alternative_code = strval($empresa2);
        $ccf_code = strval($empresa2);

        $first_name = $empresa;
        $last_name = "-";
        $importe = str_replace(",", ".", strval($total));

        $fechaActual = new \DateTime();
        $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');

        $cliente = array( "first_name"=> $first_name,
            "last_name"=> $last_name,
            "extra"=> []);
        $body = array("code"=> $code,"alternative_code"=> $alternative_code, "ccf_code"=> $ccf_code,"ccf_client_id"=> $code, "ccf_client_data"=> $cliente,"ccf_extra"=> [], "payment_methods"=> "all", "subdebts"=> [array(
            "unique_reference"=> trim($code),
            "amount"=> trim($importe),
            "due_date"=> $vencimientocp,
            "texts"=> [
                [
                    $obs
                ]
            ]
        )
        ]
        );


        /*$url = "https://core.sandbox.simp2.com/api/v1/debt";

        $client = new Client(self::getHttpHeaders());

        $response = $client->post($url, [

            'body' => json_encode($body),
        ]);*/

        //Log::info('paso', []);
        //var_dump($response);




        return self::generarCodigoBarras($barras,$empresaCodigo, $empresaNombre,$mes, $year, $carti100, $iart100, $cafil, $iafil, $intereses, $venc, $vencimientoOriginal, $nroComprobante);


        //return response()->json([]);
    }

    public static function getHttpHeaders(){

        $apikey = 'rDoyf7pwG5dmm7JobqCuht7TAAeGiDtX';
        $headers    =   [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-KEY' => $apikey,
            ],
        ];
        return $headers;
    }

    public function generarCodigoBarras($codigo,$empresaCodigo, $empresaNombre,$mes, $year, $cart100, $iart100, $cafil, $iafil, $interesesFPT, $venc, $vencimientoOriginal, $nroComprobante)
    {
        //Log::info('entra', []);
        // Generar el código de barras
        $barcode = new DNS1D();
        $barcode->setStorPath(storage_path('app/barcodes'));

        // El segundo parámetro indica el tipo de código de barras (puedes cambiarlo según tus necesidades)
        $barcodeData = $barcode->getBarcodeHTML($codigo, 'C39');

        $clink = (string) $codigo;

        if (strlen($clink) >= 9) {
            $clink = substr($clink, 0, 9);
        } else {
            $clink = str_pad($clink, 9, '0', STR_PAD_LEFT);
        }

        //Log::info('entra: '.$barcodeData, []);
        // Crear un array con los datos que quieras incluir en el PDF
        $data = [
            'codigo' => $codigo,
            'barcode' => $barcodeData,
            'empresaCodigo' => $empresaCodigo,
            'empresaNombre' => $empresaNombre,
            'mes' => $mes,
            'year' => $year,
            'cart100' => $cart100,
            'iart100' => $iart100,
            'cafil' => $cafil,
            'iafil' => $iafil,
            'Intereses' => 0,
            'InteresesFPT' => $interesesFPT,
            'venc' => $venc,
            'vencori' => $vencimientoOriginal,
            'nrocomprobante' => $nroComprobante,
            'barras' => $codigo,
            'codigoLink' => $clink,

        ];

        //return view('ddjjs.ddjjpdf', $data);

        // Renderizar la vista del PDF
       $pdf = \PDF::loadView('ddjjs/ddjjpdf', $data);

        $filePath = 'app/public/ddjj/'.$codigo.'.pdf';

        $pdf->save(storage_path($filePath));


        //$file = storage_path('app/public/archivo.pdf');

        // Renderizar la vista del PDF y obtener el contenido
        /*$pdfContent = \PDF::loadView('ddjjpdf', $data)->output();


        // Devolver el contenido del PDF como una respuesta JSON
        return response()->json([
            'success' => true,
            'pdf_content' => base64_encode($pdfContent),
        ]);*/

        // Devolver la URL del archivo generado
        return response()->json([
            'success' => true,
            'pdf_url' => asset('storage/ddjj/' . $codigo . '.pdf'),
        ]);

    }


    public function previewCodigoBarras($codigo)
    {
        $barcode = new DNS1D();
        $barcode->setStorPath(storage_path('app/barcodes'));
        $barcodeData = $barcode->getBarcodeHTML($codigo, 'C39');

        // mismo cálculo de clink
        $clink = (strlen($codigo) >= 9)
            ? substr($codigo, 0, 9)
            : str_pad($codigo, 9, '0', STR_PAD_LEFT);

        // ⚡️ Datos fake de prueba para poder ver el HTML
        $data = [
            'codigo' => $codigo,
            'barcode' => $barcodeData,
            'empresaCodigo' => '123',
            'empresaNombre' => 'Empresa de Ejemplo S.A.',
            'mes' => 'Septiembre',
            'year' => 2025,
            'cart100' => 1000.50,
            'iart100' => 50.25,
            'cafil' => 200,
            'iafil' => 20.15,
            'Intereses' => 0,
            'InteresesFPT' => 12.34,
            'venc' => '30/09/2025',
            'vencori' => '15/09/2025',
            'nrocomprobante' => 'ABC-001',
            'barras' => $codigo,
            'codigoLink' => $clink,
        ];

        return view('ddjjs.ddjjpdf', $data);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anteriores_old(Request $request)
    {


        /*$client = new Client();

        $response = $client->get(\Constants\Constants::API_URL.'/empresa-usuario/' . auth()->user()->IdUsuario);

        $result = json_decode($response->getBody(), true);*/

        $empresa=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);

        $empleados=array();
        if($request->query('empresa')) {
            $empresa_id = $request->query('empresa');
            /*$response = $client->get(\Constants\Constants::API_URL.'/empleados-por-empresa-sin-novedades/' . $empresa_id);

            $empleados = json_decode($response->getBody(), true);*/

            $empleados=DB::select(DB::raw("exec DDJJ_EmpleadosPorEmpresaSinNovedades :Param1"),[
                ':Param1' => $empresa_id,
            ]);
        }
        //dd($empleados);
        //return view('home',compact('empresas'));

        return view('ddjjs.anteriores', ['empresas' => $empresa,'empleados' => $empleados]);

    }

    public function listar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'empresa' => 'required',

            'year' => 'required|numeric',
        ], [
            'empresa.required' => 'El campo empresa es obligatorio.',

            'year.required' => 'El campo año es obligatorio.',
            'year.numeric' => 'El campo año debe ser un valor numérico.',
        ]);

        // Verificar si hay errores de validación
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        // Obtener los datos del formulario
        $empresa = $request->input('empresa');
        $year = $request->input('year');

        /*$client = new Client();
        $response = $client->get(\Constants\Constants::API_URL.'/ddjj-historial/' . $empresa. '/' . $year);

        $anteriores = json_decode($response->getBody(), true);*/

        $anteriores=DB::select(DB::raw("exec DDJJ_ConsultaHistorialTotales :Param1,:Param2"),[
            ':Param1' => $empresa,
            ':Param2' => $year,
        ]);

        //dd($anteriores);
        // Formatear los datos en un array asociativo
        $datosTabla = [];
        foreach ($anteriores as $ddjj) {


            $datosTabla[] = [
                'mes' => $ddjj->Mes,
                'envio' => $ddjj->NumeroEnvio,
                'generada' => ($ddjj->FechaGenerada)?date('d/m/Y', strtotime($ddjj->FechaGenerada)):'',
                'CantArt100' => $ddjj->CantArt100,
                'ImporteArt100' => number_format($ddjj->ImporteArt100, 2, ',', '.'),
                'CantAfi' => $ddjj->CantAfi,
                'ImporteCuotaAfi' => number_format($ddjj->ImporteCuotaAfi, 2, ',', '.'),
                'Intereses' => number_format($ddjj->Intereses, 2, ',', '.'),
                'InteresesPagoFueraTermino' => number_format($ddjj->InteresesPagoFueraTermino, 2, ',', '.'),
                'total' => number_format($ddjj->ImporteArt100+$ddjj->ImporteCuotaAfi+$ddjj->Intereses+$ddjj->InteresesPagoFueraTermino, 2, ',', '.'),
                'vencimientos' => $ddjj->Vencimientos
                    ? $ddjj->Vencimientos . '<br><br><br>' .
                    ($ddjj->FechaVencimiento
                        ? date('d/m/Y', strtotime($ddjj->FechaVencimiento))
                        : ''
                    )
                    : ($ddjj->FechaVencimiento
                        ? date('d/m/Y', strtotime($ddjj->FechaVencimiento))
                        : ''
                    ),



            ];
        }

        // Devolver los datos en formato JSON
        return response()->json($datosTabla);
    }

    public function reimprimirBoleta(Request $request)
    {
        $request->validate([
            'IdEmpresa' => 'required|integer',
            'Mes' => 'required|integer',
            'Anio' => 'required|integer',
            'NroEnvio' => 'required|integer',
            'EsVieja' => 'required|boolean',
            'AnioFG' => 'nullable|integer',
            'MesFG' => 'nullable|integer',
            'DiaFG' => 'nullable|integer',
            'HoraFG' => 'nullable|integer',
            'MinutoFG' => 'nullable|integer',
            'SegundoFG' => 'nullable|integer',
            'Vencimiento' => 'nullable|date_format:Y-m-d',
        ]);

        $params = [
            (int)$request->IdEmpresa,
            (int)$request->Mes,
            (int)$request->Anio,
            (int)$request->NroEnvio,
            (int)($request->AnioFG ?? 0),
            (int)($request->MesFG ?? 0),
            (int)($request->DiaFG ?? 0),
            (int)($request->HoraFG ?? 0),
            (int)($request->MinutoFG ?? 0),
            (int)($request->SegundoFG ?? 0),
        ];

        $rsEmpleados = !$request->EsVieja
            ? DB::select('EXEC DDJJ_BoletaPagoReImpresion ?,?,?,?,?,?,?,?,?,?', $params)
            : DB::select('EXEC DDJJ_BoletaPagoReImpresionAnteriores ?,?,?,?,?,?,?,?,?,?', $params);

        if (empty($rsEmpleados)) {
            return response()->json(['message' => 'No se encontraron datos para la boleta.'], 404);
        }

        $empleado = $rsEmpleados[0];

        $fechaActual = Carbon::now();
        $fechaOriginal = Carbon::parse($empleado->FechaVencimientoOriginal); // 🔹 necesario
        $fechaUsuario = $request->Vencimiento
            ? Carbon::parse($request->Vencimiento)
            : Carbon::parse($empleado->FechaVencimiento); // toma la fecha de la boleta

        /*\Log::info('Fecha actual:', ['fechaActual' => $fechaActual->toDateTimeString()]);
        \Log::info('Fecha ingresada por usuario:', ['fechaUsuario' => $fechaUsuario->toDateTimeString()]);
        \Log::info('Fecha original:', ['fechaOriginal' => $fechaOriginal->toDateTimeString()]);*/

// 1️⃣ Boleta vencida según fecha ingresada por usuario
        if ($fechaActual->gt($fechaUsuario)) {
            return response()->json([
                'success' => false,
                'message' => 'Boleta de Pago vencida. Ingrese una nueva fecha de pago.'
            ], 400);
        }

// 2️⃣ Fecha ingresada menor a la original
        if ($fechaUsuario->lt($fechaOriginal)) {
            // Llamada equivalente a DDJJ_ConsultasReimpresionBoletaPagoCambiaVencimiento
            $consulta = DB::select('EXEC DDJJ_ConsultasReimpresionBoletaPagoCambiaVencimiento ?,?,?,?,?,?,?,?,?,?,?',
                array_merge($params, [$fechaUsuario->toDateString()])
            );

            // 3️⃣ Validaciones tipo ASP
            if (empty($consulta)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El sistema no se encuentra disponible en este momento, por favor vuelva a intentarlo más tarde.'
                ], 500);
            }

            // Supongamos que la SP devuelve hiOk como campo
            $hiOk = $consulta[0]->hiOk ?? null;
            if ($hiOk != 201) {
                return response()->json([
                    'success' => false,
                    'message' => 'Existen inconvenientes con un servicio externo al Sindicato requerido para este proceso. Por favor, espere unos minutos y vuelva a intentarlo.'
                ], 500);
            }

            // Si todo OK, continuamos con la reimpresión
            // Esto reemplaza ReimprimirBoletaParte2 + Cerrar + Consultar del ASP
            // Solo calculamos intereses y generamos PDF abajo
        }

        // 🧾  Verificar boleta antes de generar PDF
        //$verificacion = DB::select('EXEC DDJJ_BoletaImpresionVerificaJson ?', [$empleado->NroComprobante ?? 0]);

        // Log completo de la respuesta del SP
        /*\Log::info('🧾 Resultado SP DDJJ_BoletaImpresionVerificaJson', [
            'NroComprobante' => $empleado->NroComprobante ?? 'null',
            'resultado' => $verificacion
        ]);


        $errorEnBoleta = false;
        if (empty($verificacion)) {
            $errorEnBoleta = true;
        } elseif (isset($verificacion[0]->Desde) && trim($verificacion[0]->Desde) === 'Boleta-Error') {
            $errorEnBoleta = true;
        }

        if ($errorEnBoleta) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió algún inconveniente cuando se generó la boleta, deberá volver a generarla.'
            ], 500);
        }*/


        // 4️⃣ Ajustes de intereses y vencimiento (igual que antes)
        $vencini = $fechaOriginal;
        $venc = $fechaUsuario;

        $dias2 = 0;
        $vencinic = clone $vencini;
        if ($vencini->between(Carbon::parse('2020-03-19'), Carbon::parse('2020-04-30'))) {
            $vencinic = Carbon::parse('2020-04-30');
        } elseif ($vencini->lt(Carbon::parse('2020-03-19'))) {
            $venc2 = Carbon::parse('2020-03-19');
            $dias2 = $vencini->diffInDays($venc2);
            $vencinic = Carbon::parse('2020-04-30');
        }

        $dias = $vencinic->diffInDays($venc) + $dias2;
        $tot = (float)$empleado->ImporteArt100 + (float)$empleado->ImporteCuotaAfi;
        $porcentaje = (float)(DB::select('EXEC DDJJ_PorcentajeInteresTraer')[0]->Porcentaje ?? 0);
        $intereses = ($tot * $porcentaje / 100) * $dias;

        // 5️⃣ Actualizar vencimiento e intereses
        $interesesSP = min(round($intereses, 2), 9999999.99);
        $params2 = array_merge($params, [$venc->toDateString(), $interesesSP]);
        DB::statement('EXEC DDJJ_BoletaPagoReImpresionCambiaVencimiento ?,?,?,?,?,?,?,?,?,?,?,?', $params2);

        // 6️⃣ Generar código de barras y PDF
        $empresa = str_pad((string)$empleado->Codigo, 5, '0', STR_PAD_LEFT);
        $nroComprobante = str_pad((string)$empleado->NroComprobante ?? 0, 10, '0', STR_PAD_LEFT);
        $vencCodigo = $venc->format('dmY');
        $totStr = str_pad((string)intval(round(($tot + $intereses) * 100)), 9, '0', STR_PAD_LEFT);
        $barras = "2861{$empresa}{$nroComprobante}1{$vencCodigo}{$totStr}";

        $pdfResponse = $this->generarCodigoBarras(
            $barras,
            $empresa,
            $empleado->NombreReal ?? '',
            $request->Mes,
            $request->Anio,
            $empleado->CantArt100 ?? 0,
            $empleado->ImporteArt100 ?? 0,
            $empleado->CantAfi ?? 0,
            $empleado->ImporteCuotaAfi ?? 0,
            $intereses,
            $venc->format('Y-m-d'),
            $empleado->FechaVencimientoOriginal ?? '',
            $nroComprobante
        );

        $pdfData = $pdfResponse->getData(true);

        return response()->json([
            'success' => true,
            'message' => 'Boleta recalculada correctamente.',
            'intereses' => number_format($intereses, 2, ',', '.'),
            'dias' => $dias,
            'total' => number_format($tot + $intereses, 2, ',', '.'),
            'pdf_url' => $pdfData['pdf_url'] ?? null
        ]);
    }


    public function boleta()
    {
        $empresas=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);

        return view('ddjjs.boleta', ['empresas' => $empresas]);
    }

    public function procesarBoleta(Request $request)
    {

        // Obtener los datos del formulario
        $empresa = $this->sanitizeInput($request->input('empresa'));
        //$request->session()->put('filtro_empresa', $empresa);
        $mes = $this->sanitizeInput($request->input('mes'));
        //$request->session()->put('filtro_mes', $mes);
        $year = $this->sanitizeInput($request->input('year'));
        //$request->session()->put('filtro_year', $year);
        $txtCantArt100 = $this->sanitizeInput($request->input('txtCantArt100'));
        $txtImporteArt100 = $this->sanitizeInput($request->input('txtImporteArt100'));
        $txtCantAfi = $this->sanitizeInput($request->input('txtCantAfi'));
        $txtImporteAfi = $this->sanitizeInput($request->input('txtImporteAfi'));

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
            //return response()->json(['errors' => $validator->errors()->all()], 422);
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $rsEmpresa=DB::select(DB::raw("exec DDJJ_EmpresaPorId :Param1"),[
            ':Param1' => $empresa,
        ]);



        // Verificar si se obtuvieron resultados
        if (!empty($rsEmpresa)) {
            $firstResult = $rsEmpresa[0];
            // Obtener el CUIT de la empresa desde los resultados
            $cuit = trim($firstResult->Cuit);

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



        $result=DB::select(DB::raw("exec DDJJ_VencimientoTraer :Param1, :Param2, :Param3"),[
            ':Param1' => $mes,
            ':Param2' => $year,
            ':Param3' => $dv,
        ]);

        //dd($result);

// Comprobar si $vencimiento no está vacío
        if (!empty($result)) {
            $firstResult = $result[0];
            $fechavencimiento = $firstResult->Vencimiento;
        } else {
            // CALCULA VENCIMIENTOS
            $fecha1o = date_create("$anio-$mes2-7");
            $fecha1 = date_create("$anio-$mes2-7");

            $fecha1String = date_format($fecha1, 'Y-m-d');

            $nohabilini = true;
            while ($nohabilini) {



                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha1String,
                ]);

                //dd($result);


                if (empty($results)) {
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

                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha2String,
                ]);
                //dd($result);


                if (empty($results)) {
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

                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha3String,
                ]);

                //dd($result);


                if (empty($results)) {
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


                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha4String,
                ]);

                //dd($result);


                if (empty($results)) {
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


                $results=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $fecha5String,
                ]);

                //dd($result);


                if (empty($results)) {
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
        Log::info('Vencimiento: ' . $request->input('venc').' venc: '.$fechavencimiento, []);
        $venc = ($request->input('venc'))?$this->sanitizeInput($request->input('venc')):$fechavencimiento;

        Log::info('Vencimiento final: ' .$venc , []);

        //$venc = $fechavencimiento;

        $vencinicial = $fechavencimiento;

        //$fechaVencInicialString = date_format($vencinicial, 'Y-m-d');

        $nohabilini = true;

        while ($nohabilini) {

            $dia=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                ':Param1' => $vencinicial,
            ]);



            if (empty($dia)) {
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
                $dia=DB::select(DB::raw("exec DDJJ_ValidaDia :Param1"),[
                    ':Param1' => $venc,
                ]);




                if (empty($dia)) {
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
        $rsUltimoPeriodo = DB::select(DB::raw("exec DDJJ_UltimoPeriodoPagoSEC :Param1"), [
            ':Param1' => $empresa
        ]);
        $tablaHtmlAnterior = '';
        //dd(trim($rsUltimoPeriodo[0]->Anio));
        if (!empty($rsUltimoPeriodo)&&trim($rsUltimoPeriodo[0]->Anio)!="0") {
            $tablaHtmlAnterior .= '<div style="width: 50%; padding: 0 2%;"><span style="font-size: 1.25rem"> Último Periodo Pago Registrado en el SEC</span>';


            $firstResult = $rsUltimoPeriodo[0];
            if ($firstResult->Mes) {
                $mesAnterior = $firstResult->Mes;
                $yearAnterior = $firstResult->Anio;
                $cantArt100 = $firstResult->CantArt100;
                $cantAfi = $firstResult->CantAfi;
                $importeArt100 = $firstResult->ImporteArt100;
                //$importeArt100Total = $firstResult->ImporteArt100Total;
                $importeCuotaAfi = $firstResult->ImporteCuotaAfi;
                $tot = $firstResult->Importe_Total;
                //$importeCuotaAfiTotal = $firstResult->ImporteCuotaAfiTotal;

                /*$tot = doubleval($importeArt100) + doubleval($importeCuotaAfi);
                $tot2 = doubleval($importeArt100Total) + doubleval($importeCuotaAfiTotal);*/
                $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Período '.$mesAnterior.'-'.$yearAnterior.'</span>
                                    <table id="tablePeriodo" style="width: 100%; font-size: 20px;">
                                    <tbody><tr>
                                    <th>
                                    &nbsp;
                                    </th>
                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Nro Aportantes</b>
                                    </th>

                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Importe</b>
                                    </th>
                                    </tr>
                                    <tr>
                                    <td style="border: 1px solid black">
                                    <b>Artículo 100</b>
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';

                $tablaHtmlAnterior .=$cantArt100;
                $tablaHtmlAnterior .='
                                    </td>

                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeArt100,2,',','.');
                $tablaHtmlAnterior .='

                                                </td>
                                    </tr>
                                    <tr>
                                    <td style="border: 1px solid black">
                                    <b>Afiliados</b>
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';

                $tablaHtmlAnterior .=$cantAfi;
                $tablaHtmlAnterior .='
                                    </td>

                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeCuotaAfi,2,',','.');
                $tablaHtmlAnterior .='

                                                </td>
                                    </tr>

                                    <td style="border: 2px solid black; font-weight: bold" colspan="2">
                                    <strong>Totales</strong>
                                    </td>


                                    <td style="border: 2px solid black; text-align: right; font-weight: bold">';
                $tablaHtmlAnterior .='<strong>'.number_format($tot,2,',','.').'</strong>';
                $tablaHtmlAnterior .='

                                    </td>
                                    </tr>

                                    </tbody></table>';
            }
            else{
                $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Sin Información Registrada en el SEC</span>';
            }

        }

        $tablaHtmlAnterior .= '</div>';

        $rsUltimaPresentada = DB::select(DB::raw("exec DDJJ_UltimaPresentadaSEC :Param1"), [
            ':Param1' => $empresa
        ]);
        $tablaHtmlAnterior .= '<div style="width: 50%"><span style="font-size: 1.25rem">Última DDJJ Registrada en el SEC</span>';
        if (!empty($rsUltimaPresentada)) {

            $firstResult = $rsUltimaPresentada[0];
            if ($firstResult->Mes) {
                $mesAnterior = $firstResult->Mes;
                $yearAnterior = $firstResult->Anio;
                $cantArt100 = $firstResult->CantArt100;
                $cantAfi = $firstResult->CantAfi;
                $importeArt100 = $firstResult->ImporteArt100;
                $importeArt100Total = $firstResult->ImporteArt100Total;
                $importeCuotaAfi = $firstResult->ImporteCuotaAfi;
                $importeCuotaAfiTotal = $firstResult->ImporteCuotaAfiTotal;

                $tot = doubleval($importeArt100) + doubleval($importeCuotaAfi);
                $tot2 = doubleval($importeArt100Total) + doubleval($importeCuotaAfiTotal);
                $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Período '.$mesAnterior.'-'.$yearAnterior.'</span>
                                    <table id="tableAnterior" style="width: 100%; font-size: 20px;">
                                    <tbody><tr>
                                    <th>
                                    &nbsp;
                                    </th>
                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Nro Aportantes</b>
                                    </th>
                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Base para cálculo</b>
                                    </th>
                                    <th style="border: 1px solid black; text-align: center">
                                    <b>Importe a pagar</b>
                                    </th>
                                    </tr>
                                    <tr>
                                    <td style="border: 1px solid black">
                                    <b>Artículo 100</b>
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';

                $tablaHtmlAnterior .=$cantArt100;
                $tablaHtmlAnterior .='
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeArt100Total,2,',','.');
                $tablaHtmlAnterior .='
                                                </td>
                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeArt100,2,',','.');
                $tablaHtmlAnterior .='

                                                </td>
                                    </tr>
                                    <tr>
                                    <td style="border: 1px solid black">
                                    <b>Afiliados</b>
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';

                $tablaHtmlAnterior .=$cantAfi;
                $tablaHtmlAnterior .='
                                    </td>
                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeCuotaAfiTotal,2,',','.');
                $tablaHtmlAnterior .='
                                                </td>
                                    <td style="border: 1px solid black; text-align: right">';
                $tablaHtmlAnterior .=number_format($importeCuotaAfi,2,',','.');
                $tablaHtmlAnterior .='

                                                </td>
                                    </tr>

                                    <td style="border: 2px solid black; font-weight: bold" colspan="2">
                                    <strong>Totales</strong>
                                    </td>

                                    <td style="border: 2px solid black;text-align: right; font-weight: bold">';
                $tablaHtmlAnterior .='<strong>'.number_format($tot2,2,',','.').'</strong>';
                $tablaHtmlAnterior .='
                                    </td>
                                    <td style="border: 2px solid black; text-align: right; font-weight: bold">';
                $tablaHtmlAnterior .='<strong>'.number_format($tot,2,',','.').'</strong>';
                $tablaHtmlAnterior .='

                                    </td>
                                    </tr>

                                    </tbody></table>';
            }
            else{
                $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Sin Información Registrada en el SEC</span>';
            }

        }
        else{
            $tablaHtmlAnterior .= '<br><span style="font-size: 1rem; font-weight: bold">Sin Información Registrada en el SEC</span>';
        }

        $tablaHtmlAnterior .= '</div>';




        /*$rsTotales2 = DB::select(DB::raw("exec DDJJ_BoletaPagoImpresion :Param1, :Param2, :Param3"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
        ]);




        if (!empty($rsTotales2)) {
            $firstResult = $rsTotales2[0];
            $cantArt100 = $firstResult->CantArt100;
            $cantAfi = $firstResult->CantAfi;
            $importeArt100 = $firstResult->ImporteArt100;
            $importeArt100Total = $firstResult->ImporteArt100Total;
            $importeCuotaAfi = $firstResult->ImporteCuotaAfi;
            $importeCuotaAfiTotal = $firstResult->ImporteCuotaAfiTotal;

            $tot = doubleval($importeArt100) + doubleval($importeCuotaAfi);
            $tot2 = doubleval($importeArt100Total) + doubleval($importeCuotaAfiTotal);
        }*/

        $tot = doubleval($txtImporteArt100) + doubleval($txtImporteAfi);


        $rsNumero = DB::select(DB::raw("exec DDJJ_NumeroMensual :Param1, :Param2, :Param3"), [
            ':Param1' => $empresa,
            ':Param2' => $mes,
            ':Param3' => $year,
        ]);




        if (!empty($rsNumero)) {
            $firstResult = $rsNumero[0];
            $existeDeclaracion = $firstResult->Numero;
        }

        $rsPorcentaje = DB::select(DB::raw("exec DDJJ_PorcentajeInteresTraer"), [

        ]);




        if (!empty($rsPorcentaje)) {
            $firstResult = $rsPorcentaje[0];
            $porcentaje = $firstResult->Porcentaje;
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





        $tablaHtml = '
<table id="tableEmpleados" style="width: 100%; color: white; font-size: 20px;">
    <tbody>
        <tr>
            <th>&nbsp;</th>
            <th style="border: 1px solid black; text-align: center"><b>Nro Aportantes</b></th>

            <th style="border: 1px solid black; text-align: center"><b>Importe a pagar</b></th>
        </tr>

        <!-- Artículo 100 -->
        <tr>
            <td style="border: 1px solid black"><b>Artículo 100</b></td>
            <td style="border: 1px solid black; text-align: right">
                <input type="text" id="txtCantArt100" name="txtCantArt100"
                    value="'.$txtCantArt100.'"
                    size="8" maxlength="8" style="text-align:right" onblur="CambiaImporte();">
            </td>

            <td style="border: 1px solid black; text-align: right">
                <input type="text" id="txtImporteArt100" name="txtImporteArt100"
                    value="'.$txtImporteArt100.'"
                    size="12" maxlength="12" style="text-align:right"

                    onblur="CambiaImporte();">
            </td>
        </tr>

        <!-- Afiliados -->
        <tr>
            <td style="border: 1px solid black"><b>Afiliados</b></td>
            <td style="border: 1px solid black; text-align: right">
                <input type="text" id="txtCantAfi" name="txtCantAfi"
                    value="'.$txtCantAfi.'"
                    size="8" maxlength="8" style="text-align:right"

                    onblur="CambiaImporte();">
            </td>

            <td style="border: 1px solid black; text-align: right">
                <input type="text" id="txtImporteAfi" name="txtImporteAfi"
                    value="'.$txtImporteAfi.'"
                    size="12" maxlength="12" style="text-align:right"

                    onblur="CambiaImporte();">
            </td>
        </tr>

        <!-- Totales -->
        <tr>
            <td style="border: 2px solid black; font-weight: bold" colspan="2"><strong>Totales</strong></td>

            <td style="border: 2px solid black; text-align: right; font-weight: bold">
                <input type="text" id="txtTotalImporte" name="txtTotalImporte"
                    value=""
                    size="12" maxlength="12" readonly
                    style="text-align:right; font-weight:bold;">
            </td>
        </tr>
    </tbody>
</table>';

        // ... (Agrega el contenido de la tabla aquí)
        //$tablaHtml .= '</table>';
        //Log::info('Vencimiento pasado: ' .date_format($venc, 'Y-m-d') , []);
        // Devolver la tabla HTML como respuesta
        return response()->json(['tabla' => $tablaHtml, 'tablaAnterior' => $tablaHtmlAnterior, 'original' => date_format($vencini, 'Y-m-d'), 'vencimiento' => date_format($venc, 'Y-m-d'), 'vencimiento' => date_format($venc, 'Y-m-d'),'intereses'=>number_format($intereses,2,',','.'),'total'=>number_format($tot+$intereses,2,',','.')]);
    }

}
