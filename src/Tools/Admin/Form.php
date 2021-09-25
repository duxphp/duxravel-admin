<?php

namespace Modules\Tools\Admin;

use Illuminate\Validation\Rule;

class Form extends \Modules\System\Admin\Expend
{

    public string $model = \Duxravel\Core\Model\Form::class;

    protected function table()
    {
        $table = new \Duxravel\Core\UI\Table(new $this->model());
        $table->title('自定义表单');
        $table->action()->button('添加', 'admin.tools.form.page')->type('dialog');

        $table->filter('名称', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('请输入表单名搜索')->quick();

        $table->column('#', 'form_id')->width(80);
        $table->column('名称', 'name');

        $column = $table->column('操作')->width(180);
        $column->link('设计器', 'admin.tools.form.setting', ['id' => 'form_id']);
        $column->link('编辑', 'admin.tools.form.page', ['id' => 'form_id'])->type('dialog');
        $column->link('删除', 'admin.tools.form.del', ['id' => 'form_id'])->type('ajax', ['method' => 'post']);

        return $table;
    }

    public function form(int $id = 0)
    {
        $form = new \Duxravel\Core\UI\Form(new $this->model());
        $form->dialog(true);


        $form->text('表单名称', 'name')->verify([
            'required',
        ], [
            'required' => '请填写表单名称',
        ]);

        $form->textarea('表单描述', 'description')->verify([
            'required',
        ], [
            'required' => '请填写表单功能描述',
        ]);

        $form->radio('表单类型', 'manage', [
            1 => '应用集成',
            0 => '独立管理',
        ])->switch('manage');

        $form->text('菜单名', 'menu')->group('manage', 0);
        $form->text('搜索字段', 'search')->group('manage', 0);
        $form->text('列表模板', 'tpl_list')->placeholder('留空关闭列表')->group('manage', 0);
        $form->text('详情模板', 'tpl_info')->placeholder('留空关闭详情')->group('manage', 0);
        $form->radio('前台提交', 'submit', [
            1 => '正常',
            0 => '禁用',
        ])->group('manage', 0);
        $form->number('提交间隔', 'interval')->group('manage', 0);
        $form->radio('内容审核', 'audit', [
            1 => '需要',
            0 => '无需',
        ])->group('manage', 0);
        return $form;
    }

    public function setting(int $id)
    {
        $model = new $this->model();
        $info = $model->find($id);
        $this->assign('id', $id);
        $this->assign('info', $info);
        return $this->systemView('vendor.duxphp.duxravel-admin.src.Tools.View.Admin.Form.setting');
    }

    public function settingSave(int $id)
    {
        $data = request()->input('data');
        $model = new \Duxravel\Core\Model\Form();
        $model->where('form_id', $id)->update(['data' => $data]);
        return app_success('保存表单数据成功');
    }

}
