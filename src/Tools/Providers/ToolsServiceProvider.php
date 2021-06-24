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
        // 注册基础路由
        Route::group(['prefix' => 'admin', 'auth_has' => 'admin', 'middleware' => ['auth.manage']], function () {
            $this->loadRoutesFrom(realpath(__DIR__ . '/../Route/AuthAdmin.php'));
        });

        // 注册菜单
        if ($router->is('admin/*')) {
            app(\Duxravel\Core\Util\Menu::class)->add('admin', function () {
                return app(\Modules\Tools\Service\Menu::class)->getAdminMenu();
            });

            app(\Duxravel\Core\Util\Menu::class)->add('app', function () {
                return app(\Modules\Tools\Service\Menu::class)->getAppMenu();
            });
        }

        \Duxravel\Core\Util\Blade::make('marker', \Modules\Tools\Service\Blade::class, 'mark');
        \Duxravel\Core\Util\Blade::loopMake('menu', \Modules\Tools\Service\Blade::class, 'menu');

    }
}
