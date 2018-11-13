<?php

namespace Greenbar\MenuBuilder;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class MenuBuilderServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/menu_builder.php' => config_path('menu_builder.php'),
        ], 'config');

        if (! class_exists('CreateMenusTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../database/migrations/create_menus_table.stub.php' => database_path("migrations/{$timestamp}_create_menus_table.php"),
            ], 'migrations');
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
    }
}