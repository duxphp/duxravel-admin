<?php

namespace Modules\System\Admin;


use Duxravel\Core\UI\Node;
use Illuminate\Support\Facades\DB;

class Development extends Common
{

    private $data = [];
    private $node = [];

    public function index()
    {
        $startTime = strtotime('-6 day');
        // 访问量
        $apiNumData = app(\Duxravel\Core\Model\VisitorApi::class)
            ->select(DB::raw('SUM(pv) as value, date as label'))
            ->where('date', '>=', date('Y-m-d', $startTime))
            ->groupBy('date')
            ->get();
        $apiNumData = $apiNumData->each(function ($item) {
            $item['name'] = '访问量';
            return $item;
        });
        $this->data('apiNum', $apiNumData);

        $apiNumChart = (new \Duxravel\Core\Util\Charts)
            ->area()
            ->date(date('Y-m-d', $startTime), date('Y-m-d'), '1 days', 'm-d')
            ->data('访问量', $apiNumData->toArray())
            ->height(200)
            ->render(true);
        $this->node['apiNumChart'] = $apiNumChart;

        // 访问延迟
        $apiTimeData = app(\Duxravel\Core\Model\VisitorApi::class)
            ->select(DB::raw('MAX(max_time) as max, MAX(min_time) as min, date as label'))
            ->where('date', '>=', date('Y-m-d', $startTime))
            ->groupBy('date')
            ->get();
        $apiTimeMax = $apiTimeData->map(function ($item) {
            $item['value'] = $item['max'];
            return $item;
        })->toArray();
        $apiTimeMin = $apiTimeData->map(function ($item) {
            $item['value'] = $item['min'];
            return $item;
        })->toArray();
        $this->data('apiTime', collect($apiTimeMax));

        $apiTimeChart = (new \Duxravel\Core\Util\Charts)
            ->line()
            ->date(date('Y-m-d', $startTime), date('Y-m-d'), '1 days', 'm-d')
            ->data('最大延迟', $apiTimeMax)
            ->data('最小延迟', $apiTimeMin)
            ->legend(true)
            ->height(200)
            ->render(true);

        $this->node['apiTimeChart'] = $apiTimeChart;

        // 文件上传
        $fileNumData = app(\Duxravel\Core\Model\File::class)
            ->select(DB::raw('COUNT(*) as value, DATE_FORMAT(created_at,"%Y-%m-%d")  as label'))
            ->where('created_at', '>=', date('Y-m-d', $startTime))
            //->where('has_type', 'admin')
            ->groupBy(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'))
            ->get();
        $this->data('fileNum', $fileNumData);

        $fileNumChart = (new \Duxravel\Core\Util\Charts)
            ->column()
            ->date(date('Y-m-d', $startTime), date('Y-m-d'), '1 days', 'm-d')
            ->data('文件数量', $fileNumData->toArray())
            ->height(200)
            ->render(true);

        $this->node['fileNumChart'] = $fileNumChart;

        // 操作日志
        $operateData = app(\Duxravel\Core\Model\VisitorOperate::class)
            ->select(DB::raw('COUNT(*) as value, DATE_FORMAT(created_at,"%Y-%m-%d")  as label'))
            ->where('created_at', '>=', date('Y-m-d', $startTime))
            ->where('has_type', 'admin')
            ->groupBy(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'))
            ->get();
        $this->data('logNum', $operateData);

        $logNumChart = (new \Duxravel\Core\Util\Charts)
            ->column()
            ->date(date('Y-m-d', $startTime), date('Y-m-d'), '1 days', 'm-d')
            ->data('操作记录', $operateData->toArray())
            ->render(true);

        $this->node['logNumChart'] = $logNumChart;

        foreach ($this->node as $key => $vo) {
            $this->assign($key, $vo);
        }
        foreach ($this->data as $key => $vo) {
            $this->assign($key, $vo);
        }
        return $this->systemView('vendor/duxphp/duxravel-admin/src/System/View/Admin/Development/index');
    }

    private function data($label, $data)
    {
        $dataTmpLast = $data->last();
        $dataTmpDay = $dataTmpLast['value'];
        $dataTmpSum = $data->sum('value');
        $dataTmpBefore = $data->last(function ($item) use ($dataTmpLast) {
            return $item['label'] < $dataTmpLast['label'];
        })['value'];
        $dataTmpRate = $dataTmpSum ? round($dataTmpDay / $dataTmpSum * 100) : 0;
        $dataTmpTrend = 1;
        if ($dataTmpBefore < $dataTmpDay) {
            $dataTmpTrend = 2;
        }
        if ($dataTmpBefore > $dataTmpDay) {
            $dataTmpTrend = 0;
        }

        $this->data[$label . 'Day'] = $dataTmpDay;
        $this->data[$label . 'Rate'] = $dataTmpRate;
        $this->data[$label . 'Trend'] = $dataTmpTrend;
    }


}
