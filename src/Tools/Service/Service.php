<?php

namespace Modules\Tools\Service;

/**
 * 系统服务接口
 */
class Service
{

    /**
     * 获取安装数据
     */
    public function getInstallData()
    {
        return \Duxravel\System\Seeders\DatabaseSeeder::class;
    }
}

