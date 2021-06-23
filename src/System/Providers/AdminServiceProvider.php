<?php

namespace Modules\System\Providers;

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
        $router->group(['prefix' => 'admin', 'auth_has' => 'admin', 'middleware' => ['web', 'auth.manage']], function () {
            $this->loadRoutesFrom(realpath(__DIR__ . '/../Route/AuthAdmin.php'));

            foreach (glob(base_path('modules') . '/*/Route/AuthAdmin.php') as $file) {
                $this->loadRoutesFrom($file);
            }
        });

        // 注册菜单
        app(\Duxravel\Core\Util\Menu::class)->add('admin', function () {
            return [
                'index' => [
                    'name' => '首页',
                    'topic' => '控制台',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
</svg>',
                    'order' => 0,
                    'menu' => [
                        [
                            'name' => '控制台',
                            'order' => 100,
                            'menu' => [
                                [
                                    'name' => '运维概况',
                                    'url' => 'admin.development',
                                    'order' => 1,
                                ],
                            ]
                        ],

                    ],
                ],
                'system' => [
                    'name' => '设置',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>',
                    'order' => 150,
                    'menu' => [
                        [
                            'name' => '设置',
                            'order' => 100,
                            'menu' => [
                                [
                                    'name' => '系统设置',
                                    'url' => 'admin.system.setting',
                                    'order' => 0,
                                ],
                            ]
                        ],
                        [
                            'name' => '用户',
                            'order' => 200,
                            'menu' => [
                                [
                                    'name' => '用户管理',
                                    'url' => 'admin.system.user',
                                    'order' => 1
                                ],
                                [
                                    'name' => '角色管理',
                                    'url' => 'admin.system.role',
                                    'order' => 2
                                ],
                            ]
                        ],
                        [
                            'name' => '管理',
                            'order' => 201,
                            'menu' => [
                                [
                                    'name' => '接口授权',
                                    'url' => 'admin.system.api',
                                    'order' => 0
                                ],
                                [
                                    'name' => '任务队列',
                                    'url' => 'admin.system.task',
                                    'order' => 1
                                ],
                            ]
                        ],
                        [
                            'name' => '记录',
                            'order' => 202,
                            'menu' => [
                                [
                                    'name' => '接口统计',
                                    'url' => 'admin.system.visitorApi',
                                    'order' => 1
                                ],
                                [
                                    'name' => '操作日志',
                                    'url' => 'admin.system.operate',
                                    'order' => 2
                                ],
                            ]
                        ],

                    ],
                ],
                'app' => [
                    'name' => '应用',
                    'icon' => '<svg t="1610609240337" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="10024" width="16" height="16"><path d="M174.5 463.397h295.714V184.125c0-54.766-43.763-104.093-104.093-104.093H174.5c-60.206 0-104.093 49.327-104.093 104.093v180.742c0.123 54.643 43.887 98.53 104.093 98.53z" fill="#F36A5A" p-id="10025"></path><path d="M952.852 364.744V184.125c0-54.766-43.764-104.093-104.094-104.093h-191.62c-54.767 0-104.094 43.764-104.094 104.093v284.712h295.714c60.33-5.44 104.094-49.327 104.094-104.093z" fill="#F1C40F" p-id="10026"></path><path d="M656.52 934.29h197.183c54.767 0 98.53-43.763 98.53-104.093V649.579c0-54.767-43.763-104.094-104.093-104.094H552.426v284.712c0 54.89 43.764 104.093 104.093 104.093z" fill="#45BE89" p-id="10027"></path><path d="M174.5 934.29h191.62c54.767 0 104.094-43.763 104.094-104.093V550.925H174.5c-54.766 0-104.093 43.764-104.093 104.093V835.76c0.123 49.327 43.887 98.53 104.093 98.53z" fill="#5491DE" p-id="10028"></path></svg>',
                    'url' => 'admin.system.application',
                    'order' => 200,
                ],
            ];
        });

        // 注册数据库目录
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../../database/migrations'));

    }
}
