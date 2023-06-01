<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sigmie\Base\Http\ElasticsearchConnection;
use Sigmie\Http\JSONClient;
use Sigmie\Sigmie;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(Sigmie::class, function ($app) {
            $http = JSONClient::create(['localhost:9200']);

            $connection = new ElasticsearchConnection($http);

            return new Sigmie($connection);
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
