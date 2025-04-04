<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\ServiceProvider;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*Log::info('AppServiceProvider register() ejecutado');
        return new Client();*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);


        DB::listen(function ($query) {
            Log::debug("DB: " . $query->sql . "[".  implode(",",$query->bindings). "]");
        });

    }
}
