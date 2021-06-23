<?php

namespace Modules\Tools\Admin;

use Illuminate\Support\Facades\Log;

class MenuExpend extends \Modules\System\Admin\Expend
{
    public int $menuId;

    public function index($menuId)
    {
        $this->menuId = $menuId;
        return parent::index();
    }

    public function add($menuId)
    {
        $this->menuId = $menuId;
        return parent::add();
    }

    public function edit($menuId, $id)
    {
        $this->menuId = $menuId;
        return parent::edit($id);
    }

    public function page($menuId, $id = 0)
    {
        $this->menuId = $menuId;
        return parent::page($id);
    }

    public function save($menuId, $id = 0)
    {

        $this->menuId = $menuId;
        return parent::save($id);
    }

    public function del($menuId, $id = 0)
    {
        $this->menuId = $menuId;
        return parent::del($id);
    }

    public function data($menuId)
    {
        $this->menuId = $menuId;
        return parent::data();
    }
}
