<?php

namespace Modules\System\Providers;

use Duxravel\Core\Util\Permission;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // 注册配置文件
        $this->mergeConfigFrom(__DIR__ . '/../Config/Admin.php', 'admin');

        app('config')->set('auth.guards.admin', [
            'driver' => 'jwt',
            'provider' => 'admins',
        ]);

        app('config')->set('auth.providers.admins', [
            'driver' => 'eloquent',
            'model' => \Modules\System\Model\SystemUser::class,
        ]);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        // 注册公共路由
        $router->group(['prefix' => 'admin', 'public' => true, 'middleware' => ['web']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('Admin');
            foreach ($list as $file) {
                if (is_file($file)) {
                    $this->loadRoutesFrom($file);
                }
            }
        });
        $router->group(['prefix' => 'admin', 'auth_has' => 'admin', 'middleware' => ['web', 'auth.manage']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('AuthAdmin');
            foreach ($list as $file) {
                if (is_file($file)) {
                    $this->loadRoutesFrom($file);
                }
            }
        });

        Route::get('/', function () {
            return redirect(\route('admin.index'));
        })->middleware('web')->name('admin.web');

        // 注册数据库目录
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../../database/migrations'));

    }
}
