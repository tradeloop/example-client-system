<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Obada\Api\HelperApi;
use Obada\Api\ObitApi;
use Obada\Configuration;

class ObadaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ObitApi::class, fn() => new ObitApi(
            new Client(),
            (new Configuration())->setHost(config('client-helper.host'))
        ));

        //App::bind('obada_client', fn() => new ObitApi(
        //    new Client(),
        //    (new Configuration())->setHost('client-helper')
        //));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
