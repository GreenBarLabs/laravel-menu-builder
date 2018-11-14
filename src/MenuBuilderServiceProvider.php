<?php

namespace GreenBar\MenuBuilder;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class MenuBuilderServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadViewsFrom(__DIR__.'/resources/views/partials', 'menu_builder');
        // $this->publishes([
        //     __DIR__.'/resources/views/partials' => resource_path('views/vendor/meubuilder'),
        // ]);

        $this->publishes([
            __DIR__.'/../config/menu_builder.php' => config_path('menu_builder.php'),
        ], 'config');

        if (! class_exists('CreateMenusTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../database/migrations/create_menus_table.stub.php' => database_path("migrations/{$timestamp}_create_menus_table.php"),
            ], 'migrations');
            sleep(1);
        }

        if (! class_exists('CreateMenuItemsTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../database/migrations/create_menu_items_table.stub.php' => database_path("migrations/{$timestamp}_create_menu_items_table.php"),
            ], 'migrations');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CreateMenuCommand::class,
                Commands\CreateMenuItemCommand::class,
                Commands\FixNestedSetTreeForMenu::class,
            ]);
        }
    }
    
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/menu_builder.php', 'menu_builder'
        );

        // $this->registerBladeExtensions();
    }

    /**
     * The package's blade extensions
     */
    protected function registerBladeExtensions()
    {
        // $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
        //     $bladeCompiler->directive('menu_bulder', function ($arguments) {
        //         $menu_class = config('menu_builder.models.menu');
        //         $menu_item_class = config('menu_builder.models.menu_item');

        //         list($menu_position, $classes) = explode(',', $arguments.',');
                
        //         $menu = $menu_class::where()

        //         $partial_template = view('menu_builder::main_navigation', $menu_position);
        //         return $partial_template->render();
        //     });
        // });
    }
}