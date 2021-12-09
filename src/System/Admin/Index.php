<?php

namespace Modules\System\Admin;

use Duxravel\Core\Util\View;
use Illuminate\Support\Facades\DB;

class Index extends Common
{

    public function index()
    {
        return View::manage();
    }

    public function menu()
    {
        $list = app(\Duxravel\Core\Util\Menu::class)->getManage('admin');
        $list = array_values($list);
        $apps = app(\Duxravel\Core\Util\Menu::class)->getApps();
        return app_success('ok', [
            'list' => $list,
            'apps' => $apps
        ]);
    }
}
