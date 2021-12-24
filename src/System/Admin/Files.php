<?php

namespace Modules\System\Admin;

use Duxravel\Core\UI\Node;
use Duxravel\Core\UI\Widget\Link;
use Duxravel\Core\UI\Widget\TreeList;
use Illuminate\Support\Facades\DB;
use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Widget;

class Files extends \Modules\System\Admin\Expend
{
    public string $model = \Duxravel\Core\Model\File::class;

    protected function table(): Table
    {
        $table = new Table(new $this->model());

        $table->title('文件管理');

        $table->filter('文件名', 'title', function ($query, $value) {
            $query->where('title', 'like', '%' . $value . '%');
        })->text('请输入文件名搜索')->quick();

        $table->column('文件', 'title')->image('url', function ($value, $items) {
            if (!in_array($items->ext, ['jpg', 'png', 'bmp', 'jpeg', 'gif'])) {
                return route('service.image.placeholder', ['w' => 128, 'h' => 128, 't' => '暂无预览']);
            }
            return $value;
        })->desc('size', fn($value)=> app_filesize($value));

        $table->column('关联类型 / 驱动', 'has_type')->desc('driver');
        $table->column('上传时间', 'created_at', fn($value) => $value->format('Y-m-d H:i:s'));


        $column = $table->column('操作')->width(100);
        $column->link('删除', 'admin.system.files.del', ['id' => 'file_id'])->type('ajax', ['method' => 'post']);


        $table->filter('分类', 'dir_id');
        $table->side(function () {
            return (new Node())->div(function ($node) {
                $node->div(
                    (new TreeList(request()->get('dir_id'), 'dir_id'))
                        ->search(true)
                        ->url(route('admin.system.filesDir.ajax'))
                        ->label('{{item.title}} ({{item.rawData.has_type}})')
                        ->render()
                )->class('p-2 h-10 flex-grow');
            })->class('h-screen flex flex-col')->render();
        }, 'left', false, '220px');

        return $table;
    }


}
