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
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {


        $user=DB::select(DB::raw("exec ADM_UsuarioDatos :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);








        return view('users.edit', ['user' => $user]);
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
    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:200',
            'telefono' => 'required|string|max:200',
            'email' => 'required|email|max:200',
            'txtEmpresas' => 'nullable|string',
            'txtObservaciones' => 'nullable|string',
        ]);

        $contenido = "
        <p><b>Nombre:</b> {$request->nombre}</p>
        <p><b>T.E.:</b> {$request->telefono}</p>
        <p><b>E-Mail:</b> {$request->email}</p>
        <p><b>Empresas a asociar y/o desasociar:</b> {$request->txtEmpresas}</p>
        <p><b>Observaciones:</b> {$request->txtObservaciones}</p>
    ";

        $mensaje = "Sus datos serán modificados dentro de las 24 horas";

        // 📧 Cargar config personalizada
        $from = config('mail_custom.from_address');
        $fromName = config('mail_custom.from_name');
        $to = config('mail_custom.env') === 'prod'
            ? config('mail_custom.to_prod')
            : config('mail_custom.to_test'); // ✅ sin corchetes extra

// Enviar el mail
        Mail::send([], [], function ($message) use ($from, $fromName, $to, $contenido) {
            $message->from($from, $fromName)
                ->to($to)
                ->subject('Solicitud de modificación del usuario')
                ->setBody($contenido, 'text/html');
        });


        // Devolver respuesta sin cache
        return response()
            ->view('users.confirmacion', compact('mensaje'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('Content-Type', 'text/html; charset=ISO-8859-1');
    }



    public function cambiarClave(Request $request)
    {
        // Validación manual para devolver JSON
        $validator = \Validator::make($request->all(), [
            'clave' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $usuarioId = auth()->user()->IdUsuario;
        $nuevaClave = $request->clave;

        try {
            // Ejecuta el procedimiento almacenado
            DB::statement("exec ADM_UsuarioCambioClave ?, ?", [
                $usuarioId,
                $nuevaClave
            ]);

            // Obtiene el email del usuario
            $user = DB::select(DB::raw("exec ADM_UsuarioDatos :Param1"), [
                ':Param1' => $usuarioId
            ]);

            if (empty($user)) {
                return response()->json([
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $userEmail = trim($user[0]->EMail); // ❗ importante, elimina espacios

            // Validar email antes de enviar
            if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'message' => 'Email del usuario no válido'
                ], 500);
            }

            // Enviar mail
            $contenido = "
            <p style='font-family: Arial; font-size:13px; font-weight:bold; color:black;'>
            Cambio de clave realizado con éxito.<br><br>
            Nueva Clave: {$nuevaClave}
            </p>
        ";

            Mail::html($contenido, function ($message) use ($userEmail) {
                $message->to($userEmail)
                    ->subject('Cambio de clave de Usuario')
                    ->from(config('mail_custom.from_address'), config('mail_custom.from_name'));
            });


            return response()->json([
                'message' => 'Cambio de clave realizado, recibirá un mail en el correo registrado'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error cambiarClave: '.$e->getMessage());
            return response()->json([
                'message' => 'Ocurrió un error al cambiar la clave'
            ], 500);
        }
    }


}
