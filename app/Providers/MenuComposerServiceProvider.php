<?php
// app/Providers/MenuComposerServiceProvider.php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class MenuComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.menu', function ($view) {
            $client = new Client();

            $response = $client->get(\Constants\Constants::API_URL.'/es-administrador/' . auth()->user()->IdUsuario);

            $result = json_decode($response->getBody(), true);

           $esAdmin = (empty($result['result']))?0:1;

            $response = $client->get(\Constants\Constants::API_URL.'/imprime-boleta/' . auth()->user()->IdUsuario);

            $result = json_decode($response->getBody(), true);

            $imprimeBoleta = (empty($result['result']))?0:1;

            $response = $client->get(\Constants\Constants::API_URL.'/es-acta/' . auth()->user()->IdUsuario);

            $result = json_decode($response->getBody(), true);

            $esActa = (empty($result['result']))?0:1;




            //dd($result);
            $view->with('esAdmin', $esAdmin)->with('imprimeBoleta', $imprimeBoleta)->with('esActa', $esActa);
        });
    }

    public function register()
    {
        //
    }
}
