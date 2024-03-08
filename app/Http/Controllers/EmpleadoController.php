<?php

namespace App\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use DB;
use GuzzleHttp\Client;
use Carbon\Carbon;


class EmpleadoController extends Controller
{
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


        $client = new Client();

        $response = $client->get(\Constants\Constants::API_URL.'/empresa-usuario/' . auth()->user()->IdUsuario);

        $result = json_decode($response->getBody(), true);

        $empleados['result']=array();
        if($request->query('empresa')) {
            $empresa_id = $request->query('empresa');
            $response = $client->get(\Constants\Constants::API_URL.'/empleados-por-empresa-sin-novedades/' . $empresa_id);

            $empleados = json_decode($response->getBody(), true);
        }
        //dd($empleados);
        //return view('home',compact('empresas'));

        return view('empleados.index', ['empresas' => $result['result'],'empleados' => $empleados['result']]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if ($request->get('plantillaId')){
            $plantilla_id = $request->get('plantillaId');
            $vista =view('jugadores.create', compact('plantilla_id'));
        }
        elseif($request->get('torneoId')){
            $torneo_id = $request->get('torneoId');
            $vista =view('jugadores.create', compact('torneo_id'));
        }
        else {
            $vista =view('jugadores.create');
        }

        return $vista;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //Log::info(print_r($request->file(), true));

        $this->validate($request,[ 'tipoJugador'=>'required','nombre'=>'required', 'apellido'=>'required','foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);


        if ($files = $request->file('foto')) {
            $image = $request->file('foto');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);


            /*$destinationPath = 'public/image/'; // upload path
            $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
            Log::info($profileImage);
            $files->move($destinationPath, $profileImage);*/
            $insert['foto'] = "$name";
        }

        $insert['nombre'] = $request->get('nombre');
        $insert['apellido'] = $request->get('apellido');
        $insert['email'] = $request->get('email');
        $insert['telefono'] = $request->get('telefono');
        $insert['ciudad'] = $request->get('ciudad');
        $insert['nacionalidad'] = $request->get('nacionalidad');
        $insert['altura'] = $request->get('altura');
        $insert['peso'] = $request->get('peso');
        $insert['observaciones'] = $request->get('observaciones');
        $insert['tipoDocumento'] = $request->get('tipoDocumento');
        $insert['documento'] = $request->get('documento');
        $insert['nacimiento'] = $request->get('nacimiento');
        $insert['fallecimiento'] = $request->get('fallecimiento');

        $insert['tipoJugador'] = $request->get('tipoJugador');

        $insert['pie'] = $request->get('pie');








        try {
            $persona = Persona::create($insert);
            $persona->jugador()->create($insert);

            $respuestaID='success';
            $respuestaMSJ='Registro creado satisfactoriamente';
        }catch(QueryException $ex){

            try {
                $persona = Persona::where('nombre','=',$insert['nombre'])->Where('apellido','=',$insert['apellido'])->Where('nacimiento','=',$insert['nacimiento'])->first();
                if (!empty($persona)){
                    $persona->update($insert);
                    $persona->jugador()->create($insert);
                    $respuestaID='success';
                    $respuestaMSJ='Registro creado satisfactoriamente';
                }
            }catch(QueryException $ex){

                $respuestaID='error';
                $errorCode = $ex->errorInfo[1];

                if ($errorCode == 1062) {
                    $respuestaMSJ='Jugador repetido';
                }
                //$respuestaMSJ=$ex->getMessage();

            }


        }

        if($request->get('plantilla_id')){
            $plantilla_id = $request->get('plantilla_id');
            $redirect = redirect()->route('plantillas.edit',[$plantilla_id])->with($respuestaID,$respuestaMSJ);

        }
        elseif($request->get('torneo_id')){
            $redirect = redirect()->route('plantillas.create', ['grupoId' => $request->get('grupo_id')])->with($respuestaID,$respuestaMSJ);
        }
        else{
            $redirect = redirect()->route('jugadores.index')->with($respuestaID,$respuestaMSJ);
        }

        return $redirect;
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
        $client = new Client();

        $response = $client->get(\Constants\Constants::API_URL.'/empleado/' . $id);

        $result = json_decode($response->getBody(), true);

        $response = $client->get(\Constants\Constants::API_URL.'/categorias/');

        $categorias = json_decode($response->getBody(), true);

        $response = $client->get(\Constants\Constants::API_URL.'/tipos-novedades/');

        $tiposNovedades = json_decode($response->getBody(), true);

        $exceptuadas['result']=array();
        if (!empty($result['result'])) {
            $firstResult = $result['result'][0];
            $empresa=$firstResult['IdEmpresa'];
            $response = $client->get(\Constants\Constants::API_URL . '/empresas-exceptuadas-validacion-minimo-traer-por-empresa/' .$empresa );

            $exceptuadas = json_decode($response->getBody(), true);
        }

        $response = $client->get(\Constants\Constants::API_URL.'/empresas-importe-minimo/');

        $importeMinimo = json_decode($response->getBody(), true);

        //dd($result['result']);


        return view('empleados.edit', ['empleado' => $result['result'],'categorias' => $categorias['result'],'tiposNovedades' => $tiposNovedades['result'],'exceptuadas' => $exceptuadas['result'],'importeMinimo' => $importeMinimo['result'],'empresa'=>$empresa]);
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
    public function update(Request $request, $id)
    {
        //dd($request);

        $idEmpleado=$id;
        // Ensure that empty values are set to null
        $cuil = empty(request('cuil')) ? null : request('cuil');
        $hCuil = empty(request('hCuil')) ? null : request('hCuil');
        $nombre = empty(request('nombre')) ? null : request('nombre');
        $idCategoria = empty(request('categoria')) ? null : request('categoria');
        $afiliado = empty(request('afiliado')) ? null : request('afiliado');
        $hAfiliado = empty(request('hAfiliado')) ? null : request('hAfiliado');
        $ingreso = empty(request('ingreso')) ? null : request('ingreso');
        $idNovedad = empty(request('novedades')) ? null : request('novedades');
        $egreso = empty(request('egreso')) ? null : request('egreso');
        $ia100 = empty(request('importeArt100')) ? null : request('importeArt100');
        $ica = empty(request('importeCuotaAfil')) ? null : request('importeCuotaAfil');
        $empresa = empty(request('empresa')) ? null : request('empresa');

        /*Log::debug('cuil: '.$cuil);
        Log::debug('nombre: '.$nombre);
        Log::debug('idCategoria: '.$idCategoria);
        Log::debug('afiliado: '.$afiliado);
        Log::debug('ingreso: '.$ingreso);
        Log::debug('idNovedad: '.$idNovedad);
        Log::debug('egreso: '.$egreso);
        Log::debug('ia100: '.$ia100);
        Log::debug('ica: '.$ica);*/




        $arrayValidation = [ 'cuil'=>'required','nombre'=>'required', 'ingreso'=>'required|date','afiliado'=>'required','importeArt100'=>'required','categoria'=>'required'];

        if ($idNovedad){
            $arrayValidation['egreso']='required|date';
        }
        else{
            $egreso=null;
        }
        if ($afiliado==1){
            $arrayValidation['importeCuotaAfil']='required';
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
        $url = \Constants\Constants::API_URL . '/empleados-actualizar/' . $idEmpleado . '/' . urlencode($cuil) . '/' . urlencode($nombre) . '/' . $idCategoria . '/' . ($afiliado ? urlencode($afiliado) : '0') . '/' . urlencode($ingreso) . '/' . ($idNovedad !== null ? urlencode($idNovedad) : 'null') . '/' . ($egreso !== null ? urlencode($egreso) : 'null') . '/' . urlencode($ia100) . '/' . urlencode($ica) . '/' . auth()->user()->IdUsuario;
        //Log::debug('url: '.$url);
// Realiza la solicitud PUT
        $response = $client->put($url);

        $result = json_decode($response->getBody(), true);



        if (isset($result['message'])){




            $respuestaID='success';
            $respuestaMSJ='Empleado actualizado satisfactoriamente';

            if($afiliado!=$hAfiliado){
                $respuestaMSJ .='<br>Al informar la baja del AFILIADO, debe acompañar la solicitud firmada por el mismo al email beneficios@seclaplata.org.ar  o al whatsapp 2216809844 / Art 11 del Estatuto Social. Art 4 Ley 23551.';
            }
        }
        if (isset($result['error'])){

            $respuestaID='error';
            $respuestaMSJ=$result['error'];
        }



        return redirect()->route('empleados.index',  array('empresa' => $empresa))->with($respuestaID,$respuestaMSJ);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jugador = Jugador::find($id);

        $jugador->delete();
        return redirect()->route('empleados.index')->with('success','Registro eliminado satisfactoriamente');
    }









    public function importar(Request $request)
    {


        //
        return view('jugadores.importar');
    }





}
