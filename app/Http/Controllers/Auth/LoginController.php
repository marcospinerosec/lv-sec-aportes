<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;



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
    protected $redirectTo = RouteServiceProvider::HOME;


    protected function redirectTo()
    {
        // Personaliza la redirección aquí
        return '/home';
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    protected function attemptLogin(Request $request)
    {
        $username = $request->input($this->username());
        $password = $request->input('password');

        // Llama a tu procedimiento almacenado para validar las credenciales
        //$result = DB::select('CALL sp_ValidateUser(?, ?)', [$username, $password]);

        $result=DB::select(DB::raw("exec ADM_EsUsuario :Param1, :Param2"),[
            ':Param1' => $username,
            ':Param2' => $password,
        ]);
        //dd($result);

        if (!empty($result)) {
            // Construir un objeto User manualmente
            $user = new User([
                'idUsuario' => $result[0]->idUsuario,
                'UsuarioNT' => $result[0]->UsuarioNT,
                // Añadir otros campos según tus necesidades
            ]);

            // Autenticar el usuario manualmente
            $request->session()->put('authenticated', time());
            $request->session()->put('user', $user);
            return $this->sendLoginResponse($request);
        }

        // Usuario no válido, lanzar una excepción de validación
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();


        dd($request->session()->all());
        //return redirect()->intended($this->redirectPath());
        return redirect()->intended('home');
    }

    protected function sendFailedLoginResponse(Request $request)
    {

        /*throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);*/
        dd($request->session()->all());
        return view('auth.login', [
            'message' => 'Provided PIN is invalid. ',
        ]);
    }




}
