<?php

namespace Modules\System\Admin;

class Upload extends Common
{
    /**
     * 强制文件驱动
     * @var string
     */
    protected string $driver = '';


    use \Duxravel\Core\Manage\Upload;
}
