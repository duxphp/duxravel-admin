<?php

namespace Modules\Tools\Service;

use Modules\Tools\Model\ToolsMark;

/**
 * 标签扩展
 */
class Blade
{

    /**
     * 标记标签
     * @param array $args
     * @return mixed|string
     */
    public static function mark(array $args = [])
    {
        $params = [
            'id' => $args['id'] ?: null,
        ];
        if (!$params['id']) {
            return '';
        }
        return ToolsMark::where(['mark_id' => $params['id']])->value('content');
    }

    /**
     * 菜单标签
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|\Kalnoy\Nestedset\Collection|\Modules\Tools\Model\ToolsMenuItems[]
     */
    public static function menu(array $args = [])
    {
        $params = [
            'parent' => $args['parent'] ?: 0,
            'id' => $args['id'] ?: 1,
            'limit' => (int)$args['limit'] ?: 10,
        ];
        $data = new \Modules\Tools\Model\ToolsMenuItems();
        $data = $data->scoped(['menu_id' => $params['id']]);

        if ($params['parent']) {
            $data = $data->where('item_id', $params['parent'])->first()->descendants()->get()->toTree();
        } else {
            $data = $data->get()->toTree();
        }
        return $data;

    }
}

