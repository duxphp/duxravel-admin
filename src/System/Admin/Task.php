<?php

namespace Modules\System\Admin;

use Illuminate\Support\Facades\DB;
use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Widget;

class Task extends \Modules\System\Admin\Expend
{

    protected function table(): Table
    {
        $class = config('queue.default');
        if ($class <> 'redis' && $class <> 'database') {
            app_error('队列类型不支持');
        }
        $type = request()->get('type');
        $statsDown = 0;
        $statsUp = 0;
        $statsAll = 0;
        if ($type <> 3) {
            if ($class == 'database') {
                $data = new \Duxravel\Core\Model\Jobs();
                $table = new Table($data);
            }
            if ($class == 'redis') {
                $connection = config('queue.connections.redis.connection');
                $default = config('queue.connections.redis.queue');
                $data = collect(\Queue::getRedis()->connection($connection)->zrange('queues:' . $default . ':delayed', 0, -1))->map(function ($value) use (&$statsDown, &$statsUp) {
                    $payload = json_decode($value, true);
                    if ($payload['attempts']) {
                        $statsDown++;
                    } else {
                        $statsUp++;
                    }
                    return [
                        'payload' => $payload,
                        'attempts' => $payload['attempts']
                    ];
                });
                $statsAll = $data->count();

                if ($type == 1) {
                    $data = $data->filter(function ($value, $key) {
                        return $value['attempts'] > 0;
                    });
                }
                if ($type == 2) {
                    $data = $data->filter(function ($value, $key) {
                        return $value['attempts'] == 0;
                    });
                }
                $table = new Table($data);
            }

            $table->title('任务队列');
            $table->column('任务 | 参数', 'payload', function ($value) {
                $data = (array)unserialize($value['data']['command']);
                return $data["\x00*\x00class"] . '@' . $data["\x00*\x00method"];
            })->desc('payload', function ($value) {
                $data = (array)unserialize($value['data']['command']);
                $params = $data["\x00*\x00params"];
                return $params ? json_encode($params) : '-';
            });
            $table->column('执行次数', 'attempts');
            $table->column('状态', 'attempts', function ($value) {
                return $value ? 1 : 0;
            })->status([
                1 => '执行中',
                0 => '待执行'
            ], [
                1 => 'green',
                0 => 'red'
            ]);
            $table->header(Widget::StatsCard(function (Widget\StatsCard $card) use ($statsAll, $statsDown, $statsUp) {
                $card->item('全部队列', $statsAll, Widget::icon('cloud'), 'blue');
                $card->item('处理中', $statsDown, Widget::icon('cloud-download'), 'green');
                $card->item('待处理', $statsUp, Widget::icon('cloud-upload'), 'yellow');
                $statsFail = module('Common.Model.JobsFailed')->count();
                $card->item('失败队列', $statsFail, Widget::icon('ban'), 'red');
            }));

        } else {
            $data = new \Duxravel\Core\Model\JobsFailed();
            $table = new Table($data);

            $table->title('任务队列');
            $table->column('任务 | 参数', 'payload', function ($value) {
                $data = (array)unserialize($value['data']['command']);
                return $data["\x00*\x00class"] . '@' . $data["\x00*\x00method"];
            })->desc('payload', function ($value) {
                $data = (array)unserialize($value['data']['command']);
                $params = $data["\x00*\x00params"];
                return $params ? json_encode($params) : '-';
            });
            $table->column('链接 | 队列', 'connection')->desc('queue');
            $table->column('失败时间', 'failed_at');


            $table->title('失败任务');
        }


        $table->filterType('全部')->icon(Widget::icon('send', function ($icon) {
            $icon->class('align-middle');
        }));
        $table->filterType('处理中')->icon(Widget::icon('file-invoice', function ($icon) {
            $icon->class('align-middle');
        }));
        $table->filterType('待处理')->icon(Widget::icon('recycle', function ($icon) {
            $icon->class('align-middle');
        }));
        $table->filterType('失败')->icon(Widget::icon('recycle', function ($icon) {
            $icon->class('align-middle');
        }));

        return $table;
    }

}
