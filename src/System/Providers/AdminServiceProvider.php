<?php

namespace Modules\System\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminServiceProvider extends ServiceProvider
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
        // 注册公共路由
        $router->group(['prefix' => 'admin', 'public' => true, 'auth_has' => 'admin', 'middleware' => ['web']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('Admin');
            foreach ($list as $file) {
                $this->loadRoutesFrom($file);
            }
        });
        $router->group(['prefix' => 'admin', 'auth_has' => 'admin', 'middleware' => ['auth.manage']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('AuthAdmin');
            foreach ($list as $file) {
                $this->loadRoutesFrom($file);
            }
        });

        // 注册数据库目录
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../../database/migrations'));
    }
}
