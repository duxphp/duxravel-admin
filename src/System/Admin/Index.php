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
        $list = app(\Duxravel\Core\Util\Menu::class)->getManage('admin');
        $static = app(\Duxravel\Core\Util\Menu::class)->getStatic('admin');
        $list = array_values($list);
        $apps = app(\Duxravel\Core\Util\Menu::class)->getApps();
        return app_success('ok', [
            'list' => $list,
            'apps' => $apps,
            'static' => $static
        ]);
    }
}
