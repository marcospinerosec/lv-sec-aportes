<?php

namespace App\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use PDF;
use App\Traits\SanitizesInput;

class EmpleadoController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $mes=($request->session()->get('filtro_mes'))?$request->session()->get('filtro_mes'):null;
        $year=($request->session()->get('filtro_year'))?$request->session()->get('filtro_year'):null;

        $empresas=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);
        /*$client = new Client();

        $response = $client->get(\Constants\Constants::API_URL.'/empresa-usuario/' . auth()->user()->IdUsuario);

        $result = json_decode($response->getBody(), true);*/

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
        if(($request->query('empresa'))&&($mes)&&($year)) {
            $empresa_id = $request->query('empresa');

            /*$response = $client->get(\Constants\Constants::API_URL.'/importe-minimo-por-empresa/' . $empresa_id.'/'.$mes.'/'.$year);

            $minimo = json_decode($response->getBody(), true);*/



            $minimo = DB::select(DB::raw("exec DDJJ_ImporteMinimoPorEmpresa :Param1, :Param2, :Param3"), [
                ':Param1' => $empresa_id,
                ':Param2' => $mes,
                ':Param3' => $year,
            ]);

            //log::info(print_r($minimo, true));

        }
        //dd($empleados);
        //return view('home',compact('empresas'));

        return view('empleados.index', ['empresas' => $empresas,'empleados' => $empleados,'minimo' => $minimo[0]->ImporteMinimo]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // Verifica si el parámetro está presente en la URL
        if (!$request->has('empresa') || empty($request->input('empresa'))) {
            // Redirige al índice con un mensaje de error
            return redirect()->route('empleados.index')->with('error', 'Debe seleccionar una empresa.');
        }

        // Obtiene el valor del parámetro desde la solicitud
        $empresa = $request->input('empresa');

        $client = new Client();



        $response = $client->get(\Constants\Constants::API_URL.'/categorias/');

        $categorias = json_decode($response->getBody(), true);

        $response = $client->get(\Constants\Constants::API_URL.'/tipos-novedades/');

        $tiposNovedades = json_decode($response->getBody(), true);

        /*$exceptuadas['result']=array();
        if (!empty($result['result'])) {
            $firstResult = $result['result'][0];
            $empresa=$firstResult['IdEmpresa'];
            $response = $client->get(\Constants\Constants::API_URL . '/empresas-exceptuadas-validacion-minimo-traer-por-empresa/' .$empresa );

            $exceptuadas = json_decode($response->getBody(), true);
        }

        $response = $client->get(\Constants\Constants::API_URL.'/empresas-importe-minimo/');

        $importeMinimo = json_decode($response->getBody(), true);*/

        //dd($result['result']);


        return view('empleados.create', ['categorias' => $categorias['result'],'tiposNovedades' => $tiposNovedades['result'],'empresa'=>$empresa]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$jugador=Jugador::findOrFail($id);
        return view('empleados.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*$client = new Client();

        $response = $client->get(\Constants\Constants::API_URL.'/empleado/' . $id);

        $result = json_decode($response->getBody(), true);*/

        $empleado=DB::select(DB::raw("exec DDJJ_EmpleadosTraerPorId :Param1"),[
            ':Param1' => $id,
        ]);

        /*$response = $client->get(\Constants\Constants::API_URL.'/categorias/');

        $categorias = json_decode($response->getBody(), true);*/

         $categorias = DB::select(DB::raw("exec DDJJ_CategoriasTraer"), [

        ]);

        /*$response = $client->get(\Constants\Constants::API_URL.'/tipos-novedades/');

        $tiposNovedades = json_decode($response->getBody(), true);*/

        $tiposNovedades = DB::select(DB::raw("exec DDJJ_TiposNovedadesTraer"), [

        ]);
        //$exceptuadas['result']=array();
        if (!empty($empleado)) {
            $firstResult = $empleado[0];
            $empresa=$firstResult->IdEmpresa;
            /*$response = $client->get(\Constants\Constants::API_URL . '/empresas-exceptuadas-validacion-minimo-traer-por-empresa/' .$empresa );

            $exceptuadas = json_decode($response->getBody(), true);*/
        }

        /*$response = $client->get(\Constants\Constants::API_URL.'/empresas-importe-minimo/');

        $importeMinimo = json_decode($response->getBody(), true);*/

        //dd($result['result']);


        return view('empleados.edit', ['empleado' => $empleado,'categorias' => $categorias,'tiposNovedades' => $tiposNovedades,'empresa'=>$empresa]);
    }





    public function ValidarCuitNueva($cuit) {
        // Eliminar caracteres no numéricos
        $cuit = preg_replace('/[^\d]/', '', $cuit);

        // Verificar longitud del CUIT
        if (strlen($cuit) != 11) {
            return false;
        }

        $rv = false;
        $resultado = 0;
        $cuit_nro = str_replace("-", "", $cuit);

        $codes = "6789456789";
        $cuit_long = intVal($cuit_nro);
        $verificador = intVal($cuit_nro[strlen($cuit_nro)-1]);

        $x = 0;

        while ($x < 10)
        {
            $digitoValidador = intVal(substr($codes, $x, 1));
            $digito = intVal(substr($cuit_nro, $x, 1));
            $digitoValidacion = $digitoValidador * $digito;
            $resultado += $digitoValidacion;
            $x++;
        }
        $resultado = intVal($resultado) % 11;

        /*Log::debug('resultado: '.$resultado);
        Log::debug('verificador: '.$verificador);*/

        $rv = $resultado == $verificador;
        return $rv;



    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        //dd($request);


        // Ensure that empty values are set to null

        $cuil = empty(request('cuil')) ? null : $this->sanitizeInput(request('cuil'));
        $hCuil = empty(request('hCuil')) ? null : $this->sanitizeInput(request('hCuil'));
        $nombre = empty(request('nombre')) ? null : $this->sanitizeInput(request('nombre'));
        $idCategoria = empty(request('categoria')) ? null : $this->sanitizeInput(request('categoria'));
        $afiliado = empty(request('afiliado')) ? null : $this->sanitizeInput(request('afiliado'));
        $hAfiliado = empty(request('hAfiliado')) ? null : $this->sanitizeInput(request('hAfiliado'));
        $ingreso = empty(request('ingreso')) ? null : $this->sanitizeInput(request('ingreso'));
        $idNovedad = empty(request('novedades')) ? null : $this->sanitizeInput(request('novedades'));
        $egreso = empty(request('egreso')) ? null : $this->sanitizeInput(request('egreso'));
        $ia100 = empty(request('importeArt100')) ? null : $this->sanitizeInput(request('importeArt100'));
        $ica = empty(request('importeCuotaAfil')) ? null : $this->sanitizeInput(request('importeCuotaAfil'));
        $empresa = empty(request('empresa')) ? null : $this->sanitizeInput(request('empresa'));

        /*Log::debug('cuil: '.$cuil);
        Log::debug('nombre: '.$nombre);
        Log::debug('idCategoria: '.$idCategoria);
        Log::debug('afiliado: '.$afiliado);
        Log::debug('ingreso: '.$ingreso);
        Log::debug('idNovedad: '.$idNovedad);
        Log::debug('egreso: '.$egreso);
        Log::debug('ia100: '.$ia100);
        Log::debug('ica: '.$ica);*/




        $arrayValidation = [ 'cuil'=>'required','nombre'=>'required', 'ingreso'=>'required|date','afiliado'=>'required','importeArt100' => 'required|numeric|min:0','categoria'=>'required'];

        if ($idNovedad){
            $arrayValidation['egreso']='required|date';
        }
        else{
            $egreso=null;
        }
        if ($afiliado==1){
            $arrayValidation['importeCuotaAfil']='required|numeric|min:0';
        }

        $this->validate($request,$arrayValidation);

        // Additional logic for CUIL validation (if needed)
        if (!$this->ValidarCuitNueva($cuil)) {
            return redirect()->back()->withInput()->withErrors(['cuil' => 'El CUIL no es válido.']); // Adjust the error message as needed
        }

        $client = new Client();

            //Log::debug('CUILs distintos');
            $response = $client->get(\Constants\Constants::API_URL . '/empleados-traer-por-cuil/'.$cuil.'/' .$empresa );

            $result = json_decode($response->getBody(), true);
            //Log::debug(print_r($result));
            if (!empty($result['result'])) {
                //Log::debug('CUILs distinto');
                return redirect()->back()->withInput()->withErrors(['cuil' => 'El nuevo cuil ya existe en la empresa, por favor verifique']); // Adjust the error message as needed
            }





        // Construye la URL directamente
        /*$url = \Constants\Constants::API_URL . '/empleados-agregar/' . rawurlencode($empresa) . '/' . rawurlencode($cuil) . '/' . rawurlencode($nombre) . '/' . rawurlencode($idCategoria) . '/' . ($afiliado ? rawurlencode($afiliado) : '0') . '/' . rawurlencode($ingreso) . '/' . ($idNovedad !== null ? rawurlencode($idNovedad) : 'null') . '/' . ($egreso !== null ? rawurlencode($egreso) : 'null') . '/' . rawurlencode($ia100) . '/' . ($ica !== null ? rawurlencode($ica) : 'null') . '/' . rawurlencode(auth()->user()->IdUsuario);


        Log::debug('URL: '.$url);*/


        // Datos a enviar en el cuerpo de la solicitud PUT
        $data = [
            'idEmpresa' => $empresa,
            'cuil' => $cuil,
            'nombre' => $nombre,
            'idCategoria' => $idCategoria,
            'afiliado' => $afiliado,
            'ingreso' => $ingreso,
            'idNovedad' => $idNovedad !== null ? $idNovedad : 'null',
            'egreso' => $egreso !== null ? $egreso : 'null',
            'ia100' => $ia100,
            'ica' => $ica,
            'idUsuario' => auth()->user()->IdUsuario
        ];

// Construye la URL directamente sin los parámetros
        $url = \Constants\Constants::API_URL . '/empleados-agregar';

        //Log::debug('URL: ' . $url);

// Realiza la solicitud PUT enviando el cuerpo de la solicitud como JSON
        $response = $client->put($url, [
            'json' => $data // Usamos 'json' para enviar los datos en el cuerpo de la solicitud
        ]);


// Realiza la solicitud PUT
        //$response = $client->put($url);

        $result = json_decode($response->getBody(), true);



        if (isset($result['message'])){




            $respuestaID='success';
            $respuestaMSJ='Empleado agregado satisfactoriamente';


        }
        if (isset($result['error'])){

            $respuestaID='error';
            $respuestaMSJ=$result['error'];
        }



        return redirect()->route('empleados.index',  array('empresa' => $empresa))->with($respuestaID,$respuestaMSJ);

    }

    public function convertirMayusculasEspeciales($str) {
        //$str = mb_strtoupper($str, 'UTF-8');
        Log::debug('Antes: '.$str);
        $str = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
            ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'],
            $str
        );
        Log::debug('Despues: '.$str);
        return $str;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request);

        $idEmpleado=$id;
        // Ensure that empty values are set to null
        $cuil = empty(request('cuil')) ? null : $this->sanitizeInput(request('cuil'));
        $hCuil = empty(request('hCuil')) ? null : $this->sanitizeInput(request('hCuil'));
        $nombre = empty(request('nombre')) ? null : $this->sanitizeInput(request('nombre'));
        $idCategoria = empty(request('categoria')) ? null : $this->sanitizeInput(request('categoria'));
        $afiliado = empty(request('afiliado')) ? null : $this->sanitizeInput(request('afiliado'));
        $hAfiliado = empty(request('hAfiliado')) ? null : $this->sanitizeInput(request('hAfiliado'));
        $ingreso = empty(request('ingreso')) ? null : $this->sanitizeInput(request('ingreso'));
        $idNovedad = empty(request('novedades')) ? null : $this->sanitizeInput(request('novedades'));
        $egreso = empty(request('egreso')) ? null : $this->sanitizeInput(request('egreso'));
        $ia100 = empty(request('importeArt100')) ? null : $this->sanitizeInput(request('importeArt100'));
        $ica = empty(request('importeCuotaAfil')) ? null : $this->sanitizeInput(request('importeCuotaAfil'));
        $empresa = empty(request('empresa')) ? null : $this->sanitizeInput(request('empresa'));

        /*Log::debug('cuil: '.$cuil);
        Log::debug('nombre: '.$nombre);
        Log::debug('idCategoria: '.$idCategoria);
        Log::debug('afiliado: '.$afiliado);
        Log::debug('ingreso: '.$ingreso);
        Log::debug('idNovedad: '.$idNovedad);
        Log::debug('egreso: '.$egreso);
        Log::debug('ia100: '.$ia100);
        Log::debug('ica: '.$ica);*/




        $arrayValidation = [ 'cuil'=>'required','nombre'=>'required', 'ingreso'=>'required|date','afiliado'=>'required','importeArt100'=>'required|numeric|min:0','categoria'=>'required'];

        if ($idNovedad){
            $arrayValidation['egreso']='required|date';
        }
        else{
            $egreso=null;
        }
        if ($afiliado==1){
            $arrayValidation['importeCuotaAfil']='required|numeric|min:0';
        }

        $this->validate($request,$arrayValidation);

        // Additional logic for CUIL validation (if needed)
        if (!$this->ValidarCuitNueva($cuil)) {
            return redirect()->back()->withInput()->withErrors(['cuil' => 'El CUIL no es válido.']); // Adjust the error message as needed
        }

        $client = new Client();
        if($cuil!=$hCuil){
            //Log::debug('CUILs distintos');
            $response = $client->get(\Constants\Constants::API_URL . '/empleados-traer-por-cuil/'.$cuil.'/' .$empresa );

            $result = json_decode($response->getBody(), true);
            //Log::debug(print_r($result));
            if (!empty($result['result'])) {
                //Log::debug('CUILs distinto');
                return redirect()->back()->withInput()->withErrors(['cuil' => 'El nuevo cuil ya existe en la empresa, por favor verifique']); // Adjust the error message as needed
            }
        }




        // Construye la URL directamente

        /*$url = \Constants\Constants::API_URL . '/empleados-actualizar/' . rawurlencode($idEmpleado) . '/' . rawurlencode($cuil) . '/' . rawurlencode($this->convertirMayusculasEspeciales($nombre)) . '/' . rawurlencode($idCategoria) . '/' . ($afiliado ? rawurlencode($afiliado) : '0') . '/' . rawurlencode($ingreso) . '/' . ($idNovedad !== null ? rawurlencode($idNovedad) : 'null') . '/' . ($egreso !== null ? rawurlencode($egreso) : 'null') . '/' . rawurlencode($ia100) . '/' . ($ica !== null ? rawurlencode($ica) : 'null') . '/' . rawurlencode(auth()->user()->IdUsuario);

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
        ];
        $response = $client->put($url, ['headers' => $headers]);*/

        Log::debug('Codificación antes de convertir: ' . mb_detect_encoding($nombre));

        if ($nombre && !mb_check_encoding($nombre, 'UTF-8')) {
            $nombre = utf8_encode($nombre);  // Codificar a UTF-8
        }

        Log::debug('Nombre después de la conversión: ' . $nombre);

        /*$response = $client->put(\Constants\Constants::API_URL . '/empleados-actualizar/' . $idEmpleado, [
            'form_params' => [
                'cuil' => $cuil,
                'nombre' => $this->convertirMayusculasEspeciales($nombre),
                'idCategoria' => $idCategoria,
                'afiliado' => $afiliado ?? 0,
                'ingreso' => $ingreso,
                'idNovedad' => $idNovedad ?? 'null',
                'egreso' => $egreso ?? 'null',
                'ia100' => $ia100,
                'ica' => $ica ?? 'null',
                'idUsuario' => auth()->user()->IdUsuario
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
            ]
        ]);



        $result = json_decode($response->getBody(), true);*/

        $ingreso = ($ingreso=='null')?null:date('Y-m-d', strtotime($ingreso));
        $egreso = ($egreso=='null')?null:date('Y-m-d', strtotime($egreso));
        $idEmpleado = intval($idEmpleado);
        $idCategoria = intval($idCategoria);
        $idNovedad = ($idNovedad=='null')?null:intval($idNovedad);
        $ia100 = floatval($ia100);
        $ica = floatval($ica);
        $afiliado = intval($afiliado);

        $idUsuario = intval(auth()->user()->IdUsuario);



        $error='';

        try {
            DB::enableQueryLog();


            DB::statement("exec DDJJ_EmpleadosActualizar ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?", [
                $idEmpleado,
                $cuil,
                strtoupper(urldecode(utf8_decode($nombre))),
                $idCategoria,
                $afiliado,
                $ingreso,
                $idNovedad,
                $egreso,
                $ia100,
                $ica,
                $idUsuario
            ]);

            // Tu lógica de actualización aquí

            //return response()->json(['message' => 'Datos actualizados con éxito']);

        } catch (QueryException $e) {
            // Aquí manejas la excepción
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();

            // Obtén los parámetros utilizados en la llamada al procedimiento almacenado
            /*$parametros = [
                'empleado' => $idEmpleado,
                'cuil' => $cuil,
                'nombre' => $nombre,
                'categoria' => $idCategoria,
                'afiliado' => $afiliado,
                'ingreso' => $ingreso,
                'novedad' => $idNovedad,
                'egreso' => $egreso,
                'ia100' => $ia100,
                'ica' => $ica,
                'idUsuario' => $idUsuario,
            ];

            // Log de la excepción o cualquier otro manejo que necesites

            \Log::error("Error al ejecutar el procedimiento almacenado en " . now() . ": $errorMessage (Code: $errorCode). Parámetros: " . print_r($parametros, true));

            Log::debug('SQL Queries: '.json_encode(DB::getQueryLog()));*/

            // Devuelve una respuesta indicando que ha ocurrido un error
            //return response()->json(['error' => 'Ha ocurrido un error al procesar la solicitud'], 500);
            $error= 'Ha ocurrido un error al procesar la solicitud';
        }



        if (!$error){




            $respuestaID='success';
            $respuestaMSJ='Empleado actualizado satisfactoriamente';

            if($afiliado!=$hAfiliado){
                $respuestaMSJ .='<br>Al informar la baja del AFILIADO, debe acompañar la solicitud firmada por el mismo al email beneficios@seclaplata.org.ar  o al whatsapp 2216809844 / Art 11 del Estatuto Social. Art 4 Ley 23551.';
            }
        }
        if ($error){

            $respuestaID='error';
            $respuestaMSJ=$error;
        }



        return redirect()->route('empleados.index',  array('empresa' => $empresa))->with($respuestaID,$respuestaMSJ);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($empresa)
    {



        return view('empleados.eliminar',['empresa'=>$empresa]);
    }


    public function importar($empresa)
    {



        return view('empleados.importar',['empresa'=>$empresa]);
    }

    public function formatoArchivo()
    {
        /*$client = new Client();



        $response = $client->get(\Constants\Constants::API_URL.'/categorias/');

        $categorias = json_decode($response->getBody(), true);*/

        $categorias = DB::select(DB::raw("exec DDJJ_CategoriasTraer"), [

        ]);

        $html = view('empleados/importarpdf', ['categorias' => $categorias])->render();
        $pdf = PDF::loadHtml($html);

        return $pdf->download('formato_archivo.pdf');
    }


    public function procesar_old(Request $request)
    {

        set_time_limit(0);

        $empresa = empty(request('empresa')) ? null : request('empresa');

        $file = $request->file('archivo');

        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();


        // Valid File Extensions
        $valid_extension = array("csv","txt");

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if(in_array(strtolower($extension),$valid_extension)){

            // Check file size
            if($fileSize <= $maxFileSize){

                // File upload location
                $location = 'uploads';

                // Upload file
                $file->move($location,$filename);

                // Import CSV to Database
                $filepath = public_path($location."/".$filename);

                // Reading file
                $file = fopen($filepath,"r");

                $importData_arr = array();
                $i = 0;

                while (($filedata = fgetcsv($file, 1000, ";")) !== FALSE) {
                    $num = count($filedata );

                    // Skip first row (Remove below comment if you want to skip the first row)
                    /*if($i == 0){
                       $i++;
                       continue;
                    }*/
                    for ($c=0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata [$c];
                    }
                    $i++;
                }
                fclose($file);
                //print_r($importData_arr);
                // Insert to MySQL database
                DB::beginTransaction();
                $ok=1;
                foreach($importData_arr as $importData){
                    Log::debug('CUIL: '.$importData[0]);
                    Log::debug('Nombre: '.$importData[1]);
                    Log::debug('Categoria: '.$importData[2]);
                    Log::debug('Afiliado: '.$importData[3]);
                    Log::debug('Ingreso: '.$importData[4]);
                    Log::debug('Egreso: '.$importData[5]);
                    Log::debug('Novedad: '.$importData[6]);
                    Log::debug('Art 100: '.$importData[7]);
                    Log::debug('Cuota: '.$importData[8]);
                }




            }else{


                $error='Archivo demasiado grande. El archivo debe ser menor que 2MB.';
                $ok=0;

            }

        }else{

            $error='Extensión de archivo no válida.';
            $ok=0;

        }

        if ($ok){



            DB::commit();
            $respuestaID='success';
            $respuestaMSJ='Importación exitosa.';
        }
        else{
            DB::rollback();
            $respuestaID='error';
            $respuestaMSJ=$error;
        }

        //
        return redirect()->route('empleados.index', array('empresa' => $empresa))->with($respuestaID,$respuestaMSJ);
    }

    public function procesar(Request $request)
    {
        set_time_limit(0);

        $empresa = empty(request('empresa')) ? null : $this->sanitizeInput(request('empresa'));
        $idUsuario = auth()->user()->IdUsuario; // Suponiendo que el usuario está autenticado y se obtiene el ID del usuario

        $file = $request->file('archivo');

        // Validar archivo
        /*$valid_extension = ["csv", "txt"];
        $maxFileSize = 2097152; // 2MB en Bytes

        if (!$file) {
            return redirect()->back()->withErrors(['error' => 'Archivo no proporcionado.']);
        }

        $extension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();

        if (!in_array(strtolower($extension), $valid_extension)) {
            return redirect()->back()->withErrors(['error' => 'Extensión de archivo no válida.']);
        }

        if ($fileSize > $maxFileSize) {
            return redirect()->back()->withErrors(['error' => 'Archivo demasiado grande. El archivo debe ser menor que 2MB.']);
        }*/

        $this->validate($request, [
            'archivo' => 'required|file|mimes:csv,txt|max:2048', // 2048KB = 2MB
            'empresa'   => 'required|integer',
        ]);


        try {
            // Preparar el cliente HTTP
            /*$client = new Client();
            $url = \Constants\Constants::API_URL . '/importar-empleados';

            // Configurar los headers
            $headers = [
                'Content-Type' => 'multipart/form-data',
            ];

            // Preparar los datos del formulario para la solicitud
            $multipart = [
                [
                    'name'     => 'file',
                    'contents' => fopen($file->getRealPath(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ],
                [
                    'name'     => 'idEmpresa',
                    'contents' => $empresa,
                ],
                [
                    'name'     => 'idUsuario',
                    'contents' => $idUsuario,
                ],
            ];
            //Log::debug('Multipart Data: ', $multipart);
            // Realizar la solicitud POST
            $response = $client->post($url, [
                //'headers' => $headers,
                'multipart' => $multipart,
            ]);

            // Manejar la respuesta
            $result = json_decode($response->getBody(), true);*/

            /*$request->validate([
                'file' => 'required|file|mimes:csv,txt',
                'idEmpresa' => 'required|integer',
                'idUsuario' => 'required|integer',
            ]);*/

            // Retrieve the file and parameters

            // Definir la ruta de destino donde se guardará el archivo
            $destinationPath = public_path('files');

            // Asegurarse de que el directorio 'files' exista
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Mover el archivo al directorio 'files' con un nombre único
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $destinationPath . '/' . $fileName;
            $file->move($destinationPath, $fileName);

            $error='';
            try {

                // Call the stored procedure with the line data
                $result = DB::statement('exec DDJJ_EmpleadosImportarArchivo ?, ?, ?', [$filePath,$empresa, $idUsuario]);


                //return response()->json(['success' => true, 'message' => 'Archivo guardado y enviado para procesamiento.']);

            } catch (\Exception $e) {
                Log::error('Error al procesar el archivo: ' . $e->getMessage());
                //return response()->json(['error' => 'Error al procesar el archivo en el SP.'], 500);
                $error = 'Error al procesar el archivo en el SP.';
            }


            if (!$error) {

                return redirect()->route('empleados.index', ['empresa' => $empresa])->with('success', 'Importación exitosa.');
            } else {
                $errorMessage = isset($error) ? $error : 'Error desconocido.';
                return redirect()->route('empleados.index', ['empresa' => $empresa])->with(['error' => $errorMessage]);
            }
        } catch (\Exception $e) {
            Log::error('Error en la importación de empleados: ' . $e->getMessage());
            return redirect()->route('empleados.index', ['empresa' => $empresa])->with(['error' => 'Error en la importación de empleados.']);
        }
    }

}
