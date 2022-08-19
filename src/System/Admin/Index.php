<?php

namespace Modules\System\Admin;

use Duxravel\Core\Util\View;

class Index extends Common
{

    use \Duxravel\Core\Manage\Notify;

    public function index()
    {
        return View::manage();
    }

    public function menu()
    {
        $menu = app(\Duxravel\Core\Util\Menu::class)->getManage('admin');
        $list = array_values($menu['list']);
        $apps = app(\Duxravel\Core\Util\Menu::class)->getApps();
        return app_success('ok', [
            'list' => $list,
            'apps' => $apps,
            'static' => $menu['static']
        ]);
    }
}
