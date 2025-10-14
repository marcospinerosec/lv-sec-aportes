<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';





    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('web'); // Asegúrate de que 'web' sea el guard correcto que deseas usar
    }

    public function showLoginForm()
    {
        // Mostrar el formulario de inicio de sesión dentro del dashboard
        return view('dashboard');
    }


    protected function attemptLogin_new(Request $request)
    {
        $credentials = [
            'username' => $request->input($this->username()),
            'password' => $request->input('password')
        ];

        $provider = $this->guard()->getProvider();
        $user = $provider->retrieveByCredentials($credentials);



        if ($user && $provider->validateCredentials($user, $credentials)) {


            $this->guard()->login($user, $request->filled('remember'));
            // Verificar la sesión después del login

            return $this->sendLoginResponse($request);
        }

        Log::info('User credentials not validated', ['credentials' => $credentials]);
        return $this->sendFailedLoginResponse($request);
    }



    protected function attemptLogin(Request $request)
    {
        $username = $request->input($this->username());
        $password = $request->input('password');

        // 1️⃣ Validar usuario principal
        $result = DB::select(DB::raw("exec ADM_EsUsuario :Param1, :Param2"), [
            ':Param1' => $username,
            ':Param2' => $password,
        ]);

        if (!empty($result) && is_array($result) && count($result) > 0) {
            $firstResult = $result[0];
            $idUsuario = $firstResult->IdUsuario;
            $nombre = $firstResult->Nombre;

            // 2️⃣ Buscar si es administrador
            $rsUsuariosAdm = DB::select(DB::raw("exec ADM_EsAdministrador :idUsuario"), [
                ':idUsuario' => $idUsuario
            ]);
            //log::info(print_r($rsUsuariosAdm, true));
            //dd($rsUsuariosAdm);
            $esAdministrador = !empty($rsUsuariosAdm) == 1;
            //log::info('Administrador : ' . $esAdministrador);
            // 3️⃣ Buscar si solo imprime boletas
            $rsUsuariosImprimeBoleta = DB::select(DB::raw("exec ADM_UsuarioImprimeSoloBoleta :idUsuario"), [
                ':idUsuario' => $idUsuario
            ]);
            $imprimeSoloBoleta = !empty($rsUsuariosImprimeBoleta) == 1;
            log::info('Boleta: ' . $imprimeSoloBoleta);
            // 4️⃣ Actas (si necesitás usarlo más adelante)
            $rsActa = DB::select(DB::raw("exec DDJJ_ActaBoletasTraerPorUsuario :idUsuario"), [
                ':idUsuario' => $idUsuario
            ]);
            log::info(print_r($rsActa, true));
            $imprimeSoloActa = !empty($rsActa) == 1;
            log::info('Acta: ' . $imprimeSoloActa);
            // 5️⃣ Empresas asociadas
            $rsEmpresas = DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :idUsuario"), [
                ':idUsuario' => $idUsuario
            ]);

            $empresa = 0;
            if (!empty($rsEmpresas)) {
                $empresa = $rsEmpresas[0]->IdEmpresa ?? 0;
            }

            // 6️⃣ Crear usuario Laravel con esos datos
            $user = new User([
                'IdUsuario' => $idUsuario,
                'Nombre' => $nombre,
                'EsAdministrador' => $esAdministrador,
                'ImprimeSoloBoleta' => $imprimeSoloBoleta,
                'ImprimeSoloActa' => $imprimeSoloActa,
                'Empresa' => $empresa,
            ]);

            // Guardar usuario en sesión
            auth()->login($user);

            // También guardar flags en session(), si querés usarlos directamente en Blade
            session([
                'filtro_empresa' => $empresa,
                'es_admin' => $esAdministrador,
                'solo_boleta' => $imprimeSoloBoleta,
            ]);

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }


    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();


        // Obtén el usuario autenticado
        $user = auth()->user();

        // Guardamos el usuario en sesión
        session([
            'user_' . $user->IdUsuario => $user,
            'filtro_empresa' => $user->Empresa,
            'es_admin' => $user->EsAdministrador,
            'solo_boleta' => $user->ImprimeSoloBoleta,
        ]);

        //Log::info('Session data set', ['session' => session()->all()]);

        return redirect()->intended($this->redirectTo);
    }


    protected function sendFailedLoginResponse(Request $request)
    {

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);

    }




}
