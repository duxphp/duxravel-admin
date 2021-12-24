<?php

namespace Modules\System\Admin;

use Duxravel\Core\Model\FileDir;
use Duxravel\Core\UI\Node;
use Duxravel\Core\UI\Widget\Link;
use Duxravel\Core\UI\Widget\TreeList;
use Illuminate\Support\Facades\DB;
use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Widget;

class FilesDir extends \Modules\System\Admin\Expend
{
    public string $model = FileDir::class;

    protected function table(): Table
    {
        $table = new Table(new $this->model());
        $table->title('目录管理');
        $table->map([
            'key' => 'dir_id',
            'title' => 'name',
            'has_type',
        ]);
        // Generate Table Make
        return $table;
    }


}
