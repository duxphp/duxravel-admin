<?php

namespace Modules\Tools\Admin;

use Illuminate\Support\Facades\DB;
use Duxravel\Core\Util\Excel;

class Area extends \Modules\System\Admin\Expend
{

    public string $model = \Duxravel\Core\Model\Area::class;

    protected function table()
    {
        $table = new \Duxravel\Core\UI\Table(new $this->model());
        $table->title('地区数据');

        $table->action()->button('导入', 'admin.tools.area.import')->type('dialog');

        $table->filter('名称', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('请输入地区名搜索')->quick();

        $table->column('区号', 'code');
        $table->column('名称', 'name');

        $column = $table->column('操作')->width(80);
        $column->link('删除', 'admin.tools.area.del', ['id' => 'area_id'])->type('ajax', ['method' => 'post']);

        return $table;
    }

    public function import()
    {
        $form = new \Duxravel\Core\UI\Form(collect());
        $form->action(route('admin.tools.area.importData'));
        $form->dialog(true);
        $form->file('导入数据', 'file')->verify([
            'required',
        ], [
            'required' => '请选择上传数据',
        ])->help([
            [
                'nodeName' => 'span',
                'child' => '数据来源'
            ],
            [
                'nodeName' => 'a',
                'class' => 'text-blue-600',
                'href' => 'http://lbsyun.baidu.com/index.php?title=open/dev-res',
                'target' => '_blank',
                'child' => '【百度地图行政区划adcode映射表】',
            ],
            [
                'nodeName' => 'span',
                'child' => '，上传后将覆盖现有数据'
            ]
        ], true);
        return $form->render();
    }

    public function importData()
    {
        $file = request()->input('file');
        $data = Excel::import($file);
        $data = array_slice($data, 1);
        $newData = [];
        foreach ($data as $key => $vo) {

            if (!$newData[$vo[1]]) {
                $newData[$vo[1]] = [
                    'parent_code' => 0,
                    'code' => $vo[1],
                    'name' => $vo[2],
                    'level' => 1,
                ];
            }

            if (!$newData[$vo[3]]) {
                $newData[$vo[3]] = [
                    'parent_code' => $vo[1],
                    'code' => $vo[3],
                    'name' => $vo[4],
                    'level' => 2,
                ];
            }

            if (!$newData[$vo[5]]) {
                $newData[$vo[5]] = [
                    'parent_code' => $vo[3],
                    'code' => $vo[5],
                    'name' => $vo[6],
                    'level' => 3,
                ];
            }

            if (!$newData[$vo[7]]) {
                $newData[$vo[7]] = [
                    'parent_code' => $vo[5],
                    'code' => $vo[7],
                    'name' => $vo[8],
                    'level' => 4,
                ];
            }
        }

        $list = array_chunk(collect($newData)->sortBy('code')->toArray(), 1000);

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('system_area')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');


        foreach ($list as $vo) {
            DB::table('system_area')->insert(array_values($vo));
        }

        return app_success('导入数据成功', [], route('admin.tools.area'));

    }


}
