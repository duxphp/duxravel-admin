<?php

namespace Modules\Tools\Admin;


use Modules\System\Admin\Common;

class Url extends Common
{

    private function getMenuUrl(): array
    {
        $list = app_hook('type', 'getMenuUrl');
        $data = [];
        foreach ((array)$list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        return $data;
    }

    public function index()
    {

        $this->assign('data', $this->getMenuUrl());
        return $this->dialogView('vendor.duxphp.duxravel-admin.src.Tools.View.Admin.Url.index');
    }

    public function data()
    {
        $query = request()->get('query');
        $key = request()->get('key');

        $data = $this->getMenuUrl();
        $menuInfo = $data[$key ?: 0];
        $data = new $menuInfo['model']();

        $key = $data->getKeyName();

        if ($query) {
            $data = $data->where($menuInfo['maps']['name'], 'like', "%$query%");
        }
        $data = $data->paginate($menuInfo['limit'] ?: 10);

        $totalPage = $data->lastPage();
        $data = $data->toArray();

        foreach ($data['data'] as &$item) {
            $item['url'] = $menuInfo['maps']['webUrl']($item);
        }
        unset($item);
        if ($menuInfo['callback'] && $menuInfo['callback'] instanceof \Closure) {
            $data['data'] = $menuInfo['callback']($data['data']);
        }

        $dataArray = [];
        foreach ($data['data'] as $vo) {
            $dataArray[] = [
                'id' => $vo[$key],
                'title' => $vo[$menuInfo['maps']['name']],
                'url' => $vo['url']
            ];
        }

        return app_success('ok', [
            'data' => $dataArray,
            'total' =>$data['total'],
            'page' => $totalPage
        ]);

    }

}
