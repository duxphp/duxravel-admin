<?php

namespace Modules\System\Providers;

use Duxravel\Core\Util\Hook;
use Duxravel\Core\Util\Menu;
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
            $this->loadRoutesFrom(realpath(__DIR__ . '/../Route/Admin.php'));
            foreach (glob(base_path('modules') . '/*/Route/Admin.php') as $file) {
                $this->loadRoutesFrom($file);
            }
        });
        $router->group(['prefix' => 'admin', 'auth_has' => 'admin', 'middleware' => ['auth.manage']], function () {
            $this->loadRoutesFrom(realpath(__DIR__ . '/../Route/AuthAdmin.php'));
            foreach (glob(base_path('modules') . '/*/Route/AuthAdmin.php') as $file) {
                $this->loadRoutesFrom($file);
            }
        });

        // 注册菜单
        if (\Request::is('admin/*')) {
            app(\Duxravel\Core\Util\Menu::class)->add('admin', function () {
                return app(\Modules\System\Service\Menu::class)->getAdminMenu();
            });
        }

        // 注册数据库目录
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../../database/migrations'));

        // 注册安装数据
        if (\Request::is('install/*')) {
            app(Hook::class)->add('service', 'type', 'getInstallData', function () {
                return \Duxravel\System\Seeders\DatabaseSeeder::class;
            });
        }

    }
}
