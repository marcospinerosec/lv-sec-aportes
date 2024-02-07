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

    public function showLoginForm()
    {
        // Mostrar el formulario de inicio de sesión dentro del dashboard
        return view('dashboard');
    }

    protected function attemptLogin(Request $request)
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


        //if (auth()->check()) {
            // El usuario está autenticado, realiza la redirección
            $request->session()->regenerate();

            // Personaliza la creación de la sesión aquí
            session([
                'user_id' => auth()->user()->IdUsuario,
                'user_name' => auth()->user()->Nombre,
                // Puedes agregar otros campos según tus necesidades
            ]);
       // dd($request->session()->all());
            return redirect()->intended($this->redirectTo);
        /*} else {
            // El usuario no está autenticado, maneja este caso según sea necesario
            // Puedes agregar un mensaje de error o realizar una redirección diferente
            return redirect()->back()->withInput($request->only($this->username(), 'remember'))->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
        }*/
    }


    protected function sendFailedLoginResponse(Request $request)
    {

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);

    }




}
