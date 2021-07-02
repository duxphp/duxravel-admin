<?php

namespace Modules\System\Admin;

use Illuminate\Support\Facades\DB;
use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Widget;

class VisitorApi extends \Modules\System\Admin\Expend
{
    public string $model = \Duxravel\Core\Model\VisitorApi::class;

    protected function table(): Table
    {
        $table = new Table(new $this->model());
        $table->title('接口统计');
        $table->model()->select(DB::raw('name, SUM(pv) as `pv`, SUM(uv) as `uv`, MAX(min_time) as `min`, MAX(max_time) as `max`,  `desc`, `method`'));
        $table->model()->groupBy('name', 'desc', 'method');
        if (!request()->get('order')) {
            $table->model()->orderBy('pv', 'desc');
        }

        $table->filter('接口名', 'desc', function ($query, $value) {
            $query->where('desc', $value);
        })->text('请输入接口名称')->quick();

        $table->filter('接口排序', 'order', function ($query, $value) {
            switch ($value) {
                case 1:
                    $query->orderBy('pv', 'asc');
                    break;
                case 2:
                    $query->orderBy('uv', 'desc');
                    break;
                case 3:
                    $query->orderBy('uv', 'asc');
                    break;
                case 4:
                    $query->orderBy('max', 'desc');
                    break;
                case 5:
                    $query->orderBy('min', 'asc');
                    break;
                case 0:
                default:
                    $query->orderBy('pv', 'desc');
                    break;
            }
        })->select([
            0 => '访问从高到低',
            1 => '访问从低到高',
            2 => '访客从高到低',
            3 => '访客从低到高',
            4 => '速度从慢到快',
            5 => '速度从快到慢',
        ])->quick();


        $table->filter('开始日期', 'start', function ($query, $value) {
            $query->where('create_time', '>=', strtotime($value));
        })->date();
        $table->filter('结束日期', 'stop', function ($query, $value) {
            $query->where('update_time', '<=', strtotime($value));
        })->date();

        $table->column('接口', 'desc', function ($value, $item) {
            return Widget::Badge($item['method'], function (Widget\Badge $badge) use ($item) {
                    $badge->size('small');
                    if ($item['method'] == 'GET') {
                        $badge->color('blue');
                    } else {
                        $badge->color('red');
                    }
                }) . ' <span class="ml-2">' . $item['desc'] . '</span>';
        })->desc('name');
        $table->column('访问量', 'pv');
        $table->column('访客', 'uv');
        $table->column('最大响应', 'max', function($value) {
            return $value . 's';
        });
        $table->column('最小响应', 'min', function($value) {
            return $value . 's';
        });

        return $table;
    }

    public function loadTotal()
    {
        $day = request()->get('type', 7);
        $startTime = strtotime("-{$day} day");
        $apiList = app(\Duxravel\Core\Model\VisitorApi::class)
            ->select(DB::raw('name, SUM(pv) as `value`, SUM(uv) as `uv`, `desc`'))
            ->where('date', '>=', date('Y-m-d', $startTime))
            ->groupBy('name', 'desc')
            ->orderBy('value', 'desc')
            ->limit(10)
            ->get();

        $apiListSum = $apiList->sum('value');
        $apiList->each(function ($item) use ($apiListSum) {
            $item['rate'] = $apiListSum ? round($item['value'] / $apiListSum * 100) : 0;
            return $item;
        });
        if (!$apiList->count()) {
            return app_error('暂未找到数据');
        }
        $this->assign('apiList', $apiList);
        return $this->dialogView('vendor/duxphp/duxravel-admin/src/System/View/Admin/VisitorApi/loadTotal');
    }

    public function loadDelay()
    {
        $day = request()->get('type', 7);
        $startTime = strtotime("-{$day} day");
        $apiList = app(\Duxravel\Core\Model\VisitorApi::class)
            ->select(DB::raw('name, MAX(max_time) as `value`, `desc`'))
            ->where('date', '>=', date('Y-m-d', $startTime))
            ->groupBy('name', 'desc')
            ->orderBy('value', 'desc')
            ->limit(10)
            ->get();

        $apiListSum = $apiList->sum('value');
        $apiList->each(function ($item) use ($apiListSum) {
            $item['rate'] = $apiListSum ? round($item['value'] / $apiListSum * 100) : 0;
            return $item;
        });
        if (!$apiList->count()) {
            return app_error('暂未找到数据');
        }
        $this->assign('apiList', $apiList);
        return $this->dialogView('vendor/duxphp/duxravel-admin/src/System/View/Admin/VisitorApi/loadDelay');
    }

}
