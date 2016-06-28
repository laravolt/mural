<?php

namespace Laravolt\Mural;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__.'/../resources/views/' . config('mural.skin')), 'mural');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mural');

        $this->setupRoutes($this->app->router);

        $this->publishes([
            __DIR__.'/../config/mural.php' => config_path('mural.php'),
            __DIR__.'/../database/migrations/2015_08_17_101000_create_comments_table.php' => database_path('migrations/2015_08_17_101000_create_comments_table.php'),
            __DIR__.'/../resources/lang/id' => base_path('resources/lang/id'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/mural.php', 'mural'
        );

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mural',function(){
            return new Mural();
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Laravolt\Mural\Http\Controllers', 'middleware' => config('mural.middleware')], function($router)
        {
            require __DIR__.'/Http/routes.php';
        });
    }

}
