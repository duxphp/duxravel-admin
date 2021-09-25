<?php

namespace Modules\System\Admin;

use Illuminate\Support\Facades\DB;

class Index extends Common
{

    public function index()
    {
        return view('manage');
    }

    public function menu()
    {
        $list = app(\Duxravel\Core\Util\Menu::class)->getManage('admin');
        $list = array_values($list);
        $menuActive = 0;
        foreach ($list as $key => $app) {
            if ($app['cur']) {
                $menuActive = $key;
            }
        }
        return app_success('ok', [
            'list' => $list,
            'active' => $menuActive
        ]);
    }

    public function side($app)
    {
        $side = app(\Duxravel\Core\Util\Menu::class)->getSide('admin', $app);
        return app_success('ok', $side);
    }
}
