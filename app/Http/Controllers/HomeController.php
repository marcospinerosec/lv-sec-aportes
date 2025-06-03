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
        // Agrega esto para verificar si el constructor se está llamando

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // Depura para ver si el método se está llamando
        //Log::info('HomeController index method called');

        $empresas=DB::select(DB::raw("exec DDJJ_EmpresasPorUsuario :Param1"),[
            ':Param1' => auth()->user()->IdUsuario,
        ]);
        //dd($empresas);

        //dd($this->getMiddleware());

        /*$client = new Client();

        $response = $client->get(self::API_URL.'/empresa-usuario/' . auth()->user()->IdUsuario);

        $result = json_decode($response->getBody(), true);*/



        //return view('home',compact('empresas'));

        return view('home', ['empresas' => $empresas]);
    }





}
