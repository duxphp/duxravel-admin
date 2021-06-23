<?php

namespace Modules\System\Admin;

use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Table;

class Api extends \Modules\System\Admin\Expend
{

    public string $model = \Duxravel\Core\Model\Api::class;

    protected function table(): Table
    {
        $table = new Table(new $this->model());
        $table->title('接口授权');

        $table->action()->button('添加', 'admin.system.api.page')->type('dialog');

        $table->column('描述', 'name');
        $table->column('SECRET_ID', 'secret_id');
        $table->column('secret_key', 'secret_key')->hidden();

        $table->column('状态', 'status')->status([
            1 => '正常',
            0 => '禁用'
        ], [
            1 => 'green',
            0 => 'red'
        ]);

        $column = $table->column('操作')->width(180);
        $column->link('重置TOKEN', 'admin.system.api.token', ['id' => 'api_id'])->type('ajax')->data(['type' => 'post']);
        $column->link('编辑', 'admin.system.api.page', ['id' => 'api_id'])->type('dialog');
        $column->link('删除', 'admin.system.api.del', ['id' => 'api_id'])->type('ajax')->data(['type' => 'post']);

        $table->filter('描述搜索', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('请输入描述搜索')->quick();

        return $table;
    }

    public function form(int $id = 0): Form
    {
        $form = new Form(new $this->model());
        $form->dialog(true);

        $form->text('描述', 'name')->verify([
            'required',
        ], [
            'required' => '请输入接口描述',
        ]);

        $form->radio('状态', 'status', [
            1 => '启用',
            0 => '禁用',
        ]);

        $form->before(function ($data, $type, $model) {
            if ($type !== 'add') {
                return false;
            }
            $model->secret_id = random_int(10000000, 99999999);
            $model->secret_key = \Str::random(32);
        });

        return $form;
    }

    public function token($id = 0)
    {
        $info = $this->model::find($id);
        $info->secret_key = \Str::random(32);
        $info->save();
        return app_success('重置TOKEN成功');
    }
}
