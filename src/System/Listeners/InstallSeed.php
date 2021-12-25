<?php

namespace Modules\System\Listeners;

/**
 * 数据安装接口
 */
class InstallSeed
{

    /**
     * @param $event
     *
     * @return string
     */
    public function handle($event)
    {
        return \Modules\System\Seeders\DatabaseSeeder::class;
    }
}
