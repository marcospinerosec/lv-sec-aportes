<?php

namespace App\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

use DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;
use App\Traits\SanitizesInput;

class FormularioController extends Controller
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





    public function importar()
    {
        $empresas=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);




        return view('formularios.importar', ['empresas' => $empresas]);
    }



    public function procesar(Request $request)
    {
        //dd($request->file('archivo')->getMimeType());
        set_time_limit(0);
        $mes = $this->sanitizeInput($request->input('mes'));
        $year = $this->sanitizeInput($request->input('year'));
        $empresa = empty(request('empresa')) ? null : $this->sanitizeInput(request('empresa'));
        $idUsuario = auth()->user()->IdUsuario; // Suponiendo que el usuario está autenticado y se obtiene el ID del usuario

        $empresas=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);

        $this->validate($request, [
            'archivo' => 'required|file|mimes:pdf|max:2048', // 2048KB = 2MB
            'empresa' => 'required',
            'mes' => 'required|numeric',
            'year' => 'required|numeric',
        ]);

        // Validar que el período no sea superior al actual
        $periodoActual = (int)(date('Y') * 100 + date('m'));
        $periodoCargado = (int)($year * 100 + $mes);

        //dd($periodoCargado, $periodoActual, $periodoCargado > $periodoActual);
        if ($periodoCargado > $periodoActual) {
            return redirect()
                ->route('formularios.importar')
                ->with('error', 'El mes y año no pueden ser superiores al actual.')
                ->withInput();
        }

        // Verificar si ya existe un formulario cargado para ese mes/año/empresa
        $existe = DB::connection('odbc-connection-name')->select(
            DB::raw("exec SAI.dbo.SW_FormulariosPorEmpresaValidaMes :empresa, :mes, :anio"),
            [
                ':empresa' => $empresa,
                ':mes'     => $mes,
                ':anio'    => $year,
            ]
        );


        if (count($existe) > 0) {
            return redirect()
                ->route('formularios.importar')
                ->with('error', 'Ya existe un formulario ingresado para la empresa, mes y año ingresados.')
                ->withInput();
        }


        try {


            //get the image from the form
            $DocumentoF=$request->file('archivo');
            //$fileNameWithTheExtension = $DocumentoF->getClientOriginalName();

            //get the name of the file
           // $fileName = pathinfo($fileNameWithTheExtension, PATHINFO_FILENAME);

            //get extension of the file
            $extension = $DocumentoF->getClientOriginalExtension();

            $results=DB::select(DB::raw("exec DDJJ_EmpresaPorId :Param1"),[
                ':Param1' => $empresa,
            ]);

            // Verificar si se obtuvieron resultados
            if (!empty($results)) {
                $firstResult = $results[0];

                $empresaCodigo = strval($firstResult->Codigo);

            }


            $numero = $empresaCodigo . '_F931';


// El nombre final es: numero-anio-mes.extension
            $newFileNameDocumento = "{$numero}-{$year}-{$mes}.{$extension}";



            //save the iamge onto a public directory into a separately folder
            //$path = $DocumentoF->storeAs('public/files', $newFileNameDocumento);




            $error='';
            try {
                $store  = Storage::disk('nas')->put($newFileNameDocumento, File::get($DocumentoF));
                // Call the stored procedure with the line data
                // Llamar SP
                $detalle = "Ingreso Web - Periodo: {$mes}-{$year}";
                $empresa = (int) $empresa;
                $tipo = 8;
                $nombre = str_replace("'", "''", $newFileNameDocumento); // Escapamos comillas simples
                $detalle = str_replace("'", "''", $detalle);
                $mes = (int) $mes;
                $anio = (int) $year;
                $usuario = (int) $idUsuario;

                $sql = "
    EXEC SAI.dbo.GEN_InsertarEmpresaDocumentoV2
        {$empresa},
        {$tipo},
        '{$nombre}',
        '{$detalle}',
        '{$mes}',
        '{$anio}',
        {$usuario}
";

                DB::connection('odbc-connection-name')->unprepared($sql);

                //return response()->json(['success' => true, 'message' => 'Archivo guardado y enviado para procesamiento.']);

            } catch (\Exception $e) {
                Log::error('Error al procesar el archivo: ' . $e->getMessage());
                //return response()->json(['error' => 'Error al procesar el archivo en el SP.'], 500);
                $error = 'Error al procesar el archivo en el SP.';
            }


            if (!$error) {

                return redirect()->route('formularios.listar', ['empresa' => $empresa])
                    ->with('success', 'Importación exitosa.');

            } else {
                $errorMessage = isset($error) ? $error : 'Error desconocido.';
                return redirect()->route('formularios.importar')->with(['error' => $errorMessage])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Error en la importación de F931: ' . $e->getMessage());
            return redirect()->route('formularios.importar')->with(['error' => 'Error en la subida.'])
                ->withInput();
        }
    }

    public function listar($empresa = null)
    {
        $empresa = $empresa ?? auth()->user()->IdEmpresa;

        // Traer los formularios desde el SP
        $formularioSP = DB::connection('odbc-connection-name')->select(
            DB::raw("exec SAI.dbo.SW_TraerFormulariosPorEmpresa :empresa"),
            [':empresa' => $empresa]
        );
        //dd($formularioSP); // O $results si los traés de la DB

        return view('formularios.listado', [
            'archivos' => $formularioSP
        ]);
    }

    public function verArchivo($nombre)
    {
        $filePath = Storage::disk('nas')->path($nombre);

        if (!file_exists($filePath)) {
            abort(404);
        }

        $mime = mime_content_type($filePath);
        return response()->file($filePath, ['Content-Type' => $mime]);
    }

}
