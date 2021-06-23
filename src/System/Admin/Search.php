<?php

namespace Modules\System\Admin;


class Search extends \Modules\System\Admin\Expend
{

    public function index()
    {
        $list = app_hook('Service', 'Type', 'getQuickSearch');
        $data = [];
        foreach ((array) $list as $value) {
            $data = array_merge_recursive((array) $data, (array) $value);
        }
        $this->assign('data', $data);
        return $this->systemView('vendor/duxphp/duxravel-admin/src/System/View/Admin/Search/index');
    }
}
