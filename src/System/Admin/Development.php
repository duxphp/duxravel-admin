<?php

namespace Modules\System\Admin;


use Illuminate\Support\Facades\DB;

class Development extends Common
{

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
        $apiNumChart = app(\Duxravel\Core\Util\ApexCharts::class)->area($apiNumData->toArray())->type('day', ['start' => date('Y-m-d', $startTime)])->render('api-num-chart', function ($config) {
            \Arr::set($config, 'chart.height', 200);
            return $config;
        });
        $this->assign('apiNumChart', $apiNumChart);

        // 访问延迟
        $apiTimeData = app(\Duxravel\Core\Model\VisitorApi::class)
            ->select(DB::raw('MAX(max_time) as max, MAX(min_time) as min, date as label'))
            ->where('date', '>=', date('Y-m-d', $startTime))
            ->groupBy('date')
            ->get();
        $apiTimeMax = $apiTimeData->map(function ($item) {
            $item['name'] = '最大延迟';
            $item['value'] = $item['max'];
            return $item;
        })->toArray();
        $apiTimeMin = $apiTimeData->map(function ($item) {
            $item['name'] = '最小延迟';
            $item['value'] = $item['min'];
            return $item;
        })->toArray();
        $this->data('apiTime', collect($apiTimeMax));
        $apiTimeChart = app(\Duxravel\Core\Util\ApexCharts::class)->line(array_merge($apiTimeMax, $apiTimeMin))->type('day', ['start' => date('Y-m-d', $startTime)])->render('api-time-chart', function ($config) {
            \Arr::set($config, 'chart.height', 200);
            return $config;
        });
        $this->assign('apiTimeChart', $apiTimeChart);

        // 文件上传
        $fileNumData = app(\Duxravel\Core\Model\File::class)
            ->select(DB::raw('COUNT(*) as value, FROM_UNIXTIME(create_time,"%Y-%m-%d")  as label'))
            ->where('create_time', '>=', strtotime(date('Y-m-d', $startTime)))
            //->where('has_type', 'admin')
            ->groupBy(DB::raw('FROM_UNIXTIME(create_time,"%Y-%m-%d")'))
            ->get();
        $fileNumData = $fileNumData->each(function ($item) {
            $item['name'] = '文件数量';
            return $item;
        });
        $this->data('fileNum', $fileNumData);
        $fileNumChart = app(\Duxravel\Core\Util\ApexCharts::class)->bar($fileNumData->toArray())->type('day', ['start' => date('Y-m-d', $startTime), 'stop' => date('Y-m-d')])->render('file-num-chart', function ($config) {
            \Arr::set($config, 'chart.height', 200);
            return $config;
        });
        $this->assign('fileNumChart', $fileNumChart);

        // 操作日志
        $operateData = app(\Duxravel\Core\Model\VisitorOperate::class)
            ->select(DB::raw('COUNT(*) as value, FROM_UNIXTIME(create_time,"%Y-%m-%d")  as label'))
            ->where('create_time', '>=', strtotime(date('Y-m-d', $startTime)))
            ->where('has_type', 'admin')
            ->groupBy(DB::raw('FROM_UNIXTIME(create_time,"%Y-%m-%d")'))
            ->get();
        $this->data('logNum', $operateData);
        $logNumChart = app(\Duxravel\Core\Util\ApexCharts::class)->bar($operateData->toArray())->type('day', ['start' => date('Y-m-d', $startTime), 'stop' => date('Y-m-d')])->render('log-num-chart', function ($config) {
            \Arr::set($config, 'chart.height', 200);
            return $config;
        });
        $this->assign('logNumChart', $logNumChart);

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
        $this->assign($label . 'Day', $dataTmpDay);
        $this->assign($label . 'Rate', $dataTmpRate);
        $this->assign($label . 'Trend', $dataTmpTrend);
    }
}
