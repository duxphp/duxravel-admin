<?php

namespace Modules\Tools\Admin;

use Illuminate\Support\Facades\Log;
use Duxravel\Core\Util\Tree;
use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Table;
use Modules\Tools\Model\ToolsMenuItems;

class MenuItems extends MenuExpend
{
    use \Duxravel\Core\Traits\TableSortable;

    public string $model = ToolsMenuItems::class;

    /**
     * @return Table
     * @throws \Exception
     */
    protected function table(): Table
    {
        $table = new Table(new $this->model());
        $table->title('菜单管理');
        $table->model()->scoped(['menu_id' => $this->menuId])->defaultOrder();
        $table->action()->button('添加', 'admin.tools.menuItems.page', ['menu' => $this->menuId])->icon('plus')->type('dialog');
        $table->tree('parent_id', route('admin.tools.menuItems.sortable', ['menu' => $this->menuId]));
        // 设置筛选
        $table->filter('名称', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('请输入菜单名称')->quick();
        // 设置列表
        $table->column('菜单名', 'name')->drag();
        $column = $table->column('操作')->width('140');
        $column->link('编辑', 'admin.tools.menuItems.page', ['menu' => $this->menuId, 'id' => 'item_id'])->type('dialog');
        $column->link('删除', 'admin.tools.menuItems.del', ['menu' => $this->menuId, 'id' => 'item_id'])->type('ajax', ['method' => 'post']);
        return $table;
    }

    /**
     * @param null $id
     * @return Form
     */
    public function form($id = 0): \Duxravel\Core\UI\Form
    {
        $model = new $this->model();
        $form = new \Duxravel\Core\UI\Form($model);
        $form->dialog(true);
        $form->action(route('admin.tools.menuItems.save', ['menu' => $this->menuId, 'id' => $id]));

        $form->cascader('上级分类', 'parent_id', function ($value) {
            return $this->model::scoped(['menu_id' => $this->menuId])->get(['item_id as id', 'parent_id as pid', 'name']);
        });

        $form->text('菜单名称', 'name')->verify([
            'required',
        ], [
            'required' => '请填写菜单名称',
        ]);
        $url = route('admin.tools.url');
        $form->text('菜单链接', 'url')->verify([
            'required',
        ], [
            'required' => '请填写菜单链接',
        ])->afterText("<a class='block cursor-pointer' href='javascript:;' data-js='dialog-open' data-type='ajax' data-url='$url' data-layout='false'>选择</a>");

        $form->front(function ($data, $type, $model) {
            $model->menu_id = $this->menuId;
            return $model;
        });

        $form->script(static function () {
            return <<<JS
                window['selectUrl'] = function(url) {
                    $('input[name="url"]').val(url)
                }
            JS;
        });
        return $form;
    }


}
