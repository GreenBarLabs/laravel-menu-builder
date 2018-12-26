<?php

namespace GreenBar\MenuBuilder;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use GreenBar\MenuBuilder\Nova\Http\Middleware\Authorize;

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
            sleep(1);
        }

        if (! class_exists('CreateMenuItemsTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../database/migrations/create_menu_items_table.stub.php' => database_path("migrations/{$timestamp}_create_menu_items_table.php"),
            ], 'migrations');
        }

        if (class_exists('\\Laravel\\Nova\\Nova')) {
            //$this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-log-viewer');

            \Laravel\Nova\Nova::serving(function (\Laravel\Nova\Events\ServingNova $event) {
                \Laravel\Nova\Nova::script('menu-builder-tool', __DIR__.'/../dist/js/tool.js');
                \Laravel\Nova\Nova::style('menu-builder-tool', __DIR__.'/../dist/css/tool.css');

                // \Laravel\Nova\Nova::provideToScript([
                //     'viewer' => config('log-viewer.colors.levels'),
                // ]);
            });

            $this->app->booted(function () {
                $this->novaRoutes();
            });
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

        $this->registerBladeExtensions();
    }

    /**
     * Register the nova routes
     */
    protected function novaRoutes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }
        Route::middleware(['nova', 'api', Authorize::class])
            ->prefix('nova-vendor/spatie/nova-tags-field')
            ->group(__DIR__.'/../routes/Nova/api.php');

    }

    /**
     * The package's blade extensions
     */
    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('render_menu', function ($arguments) {
                list($menu_position, $classes) = explode(',', $arguments.',');
                if (empty($classes)) {
                    $classes = 'null';
                }
                return '<?php echo \GreenBar\MenuBuilder\Services\MenuBuilder::render_menu(' . $menu_position . ',' . $classes . '); ?>';
            });
        });
    }
}
