<?php

namespace Modules\Tools\Admin;

use Illuminate\Validation\Rule;

class FormData extends \Modules\System\Admin\Expend
{

    public string $model = \Duxravel\Core\Model\FormData::class;
    protected $formInfo;

    public function __construct()
    {
        $formId = request()->get('form');
        $this->formInfo = \Duxravel\Core\Model\Form::find($formId);
    }

    protected function table()
    {
        $table = new \Duxravel\Core\UI\Table(new $this->model());
        $table->model()->orderBy('data_id', 'desc');
        $table->model()->where('form_id', $this->formInfo->form_id);
        $table->title($this->formInfo->name);

        $table->action()->button('添加', 'admin.tools.formData.page', ['form' => $this->formInfo->form_id])->type('dialog');
        $table->filterParams('form', $this->formInfo->form_id);

        if ($this->formInfo->search) {
            $table->filter('搜索', $this->formInfo->search, function ($query, $value) use ($vo) {
                $query->where('data->' . $this->formInfo->search, $value);
            })->text('请输入关键词搜索')->quick();
        } else {
            foreach ($this->formInfo->data as $vo) {
                if ($vo['type'] == 'text') {
                    $table->filter($vo['name'], $vo['field'], function ($query, $value) use ($vo) {
                        $query->where('data->' . $vo['field'], $value);
                    })->text('请输入' . $vo['name'] . '搜索')->quick();
                    break;
                }
            }
        }

        $table->column('#', 'data_id')->width(130);
        foreach ($this->formInfo->data as $vo) {
            if ($vo['list']) {
                if ($vo['type'] == 'image') {
                    $table->column($vo['name'])->image('data->' . $vo['field'], function ($value) {
                        return $value ?: '无';
                    });
                } else {
                    $table->column($vo['name'], 'data->' . $vo['field']);
                }
            }
        }
        $table->column('状态', 'status')->toggle('status', 'admin.tools.formData.status', ['form' => $this->formInfo->form_id, 'id' => 'data_id'])->width(100);

        $column = $table->column('操作')->width(120);
        $column->link('编辑', 'admin.tools.formData.page', ['form' => $this->formInfo->form_id, 'id' => 'data_id'])->type('dialog');
        $column->link('删除', 'admin.tools.formData.del', ['form' => $this->formInfo->form_id, 'id' => 'data_id'])->type('ajax', ['method' => 'post']);

        return $table;
    }

    public function form(int $id = 0)
    {
        $form = new \Duxravel\Core\UI\Form();
        $form->dialog(true);
        $form->action(route('admin.tools.formData.save', ['id' => $id, 'form' => $this->formInfo->form_id]));
        app(\Duxravel\Core\Util\Form::class)->getFormUI($this->formInfo->form_id, $form, $id);
        return $form;
    }

    public function save($id = 0)
    {
        $data = $this->form($id)->save();
        app(\Duxravel\Core\Util\Form::class)->saveForm($this->formInfo->form_id, $data, $id);
        return app_success('更新' . $this->formInfo['menu'] . '成功', [], route('admin.tools.formData', ['form' => $this->formInfo->form_id]));
    }

}
