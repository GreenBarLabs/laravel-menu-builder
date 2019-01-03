<?php

namespace GreenBar\MenuBuilder;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;

class MenuBuilderNovaServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (class_exists('\\Laravel\\Nova\\Nova')) {
            $this->loadViewsFrom(__DIR__.'/../resources/Nova/views', 'menu-builder');

            Nova::serving(function(ServingNova $event) {
                Nova::style('laravel-menu-builder-tool',  __DIR__.'/../dist/css/tool.css');
                Nova::script('laravel-menu-builder-tool', __DIR__.'/../dist/js/tool.js');

                // Nova::provideToScript([
                //     'viewer' => config('log-viewer.colors.levels'),
                // ]);
            });

            $this->app->booted(function() {
                $this->novaRoutes();
            });
        }
    }

    /**
     * Register the nova routes
     *
     * @return void
     */
    private function novaRoutes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware([
            'nova',
            'api',
            \GreenBar\MenuBuilder\Nova\Http\Middleware\Authorize::class,
        ])
        ->prefix('nova-vendor/greenbar/laravel-menu-builder')
        ->group(__DIR__.'/../routes/Nova/api.php');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
