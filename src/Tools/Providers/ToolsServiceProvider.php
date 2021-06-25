<?php

namespace Modules\Tools\Providers;

use Duxravel\Core\Util\Menu;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Modules\Tools\Model\ToolsMenu;
use Illuminate\Support\Facades\Route;

class ToolsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        \Duxravel\Core\Util\Blade::make('marker', \Modules\Tools\Service\Blade::class, 'mark');
        \Duxravel\Core\Util\Blade::loopMake('menu', \Modules\Tools\Service\Blade::class, 'menu');
    }
}
