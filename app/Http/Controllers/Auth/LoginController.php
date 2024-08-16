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


    protected function attemptLogin(Request $request)
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



    protected function attemptLogin_old(Request $request)
    {
        $username = $request->input($this->username());
        $password = $request->input('password');

        // Llama a tu procedimiento almacenado para validar las credenciales
        //$result = DB::select('CALL sp_ValidateUser(?, ?)', [$username, $password]);

        /*$result=DB::select(DB::raw("exec ADM_EsUsuario :Param1, :Param2"),[
            ':Param1' => $username,
            ':Param2' => $password,
        ]);*/

        $client = new Client();



        $response = $client->get(\Constants\Constants::API_URL.'/verifica-usuario/' . $username . '/' . $password);

        $result = json_decode($response->getBody(), true);


        //dd($result);

        if (!empty($result['result']) && is_array($result['result']) && count($result['result']) > 0) {
            $firstResult = $result['result'][0];

            // Acceder a los datos
            $idUsuario = $firstResult['IdUsuario'];
            $usuarioNT = $firstResult['Nombre'];
            $user = new User([
                'IdUsuario' => $idUsuario,
                'Nombre' => $usuarioNT,
                // Añadir otros campos según tus necesidades
            ]);
            //dd($user);
            //dd($request->session()->all());
            // Autenticar el usuario manualmente
            auth()->login($user);
            return $this->sendLoginResponse($request);
        }

        // Usuario no válido, lanzar una excepción de validación
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();


        // Obtén el usuario autenticado
        $user = auth()->user();

        // Almacena el usuario en la sesión usando múltiples claves
        session([
            /*'user_id' => $user->IdUsuario,
            'user_name' => $user->Nombre,*/
            'user_' . $user->IdUsuario => $user,
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
