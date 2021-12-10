<?php

namespace Modules\Tools\Admin;


use Modules\System\Admin\Common;
use Modules\System\Events\MenuUrl;
use Modules\Tools\UI\UrlSelect;

class Url extends Common
{

    public function data()
    {
        $query = request()->get('query');
        $key = request()->get('type');

        $data = UrlSelect::getMenuUrl();

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
            'total' => $data['total'],
            'page' => $totalPage
        ]);

    }

}
