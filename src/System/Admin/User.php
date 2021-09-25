<?php

namespace Modules\System\Admin;

use Illuminate\Validation\Rule;
use Duxravel\Core\UI\Table;

class User extends \Modules\System\Admin\Expend
{
    public string $roleModel = \Modules\System\Model\SystemRole::class;
    public string $model = \Modules\System\Model\SystemUser::class;

    use \Duxravel\Core\Manage\User {
        \Duxravel\Core\Manage\User::table as traitTable;
        \Duxravel\Core\Manage\User::Form as traitForm;
    }


    protected function table(): Table
    {
        $table = $this->traitTable();
        $table->filter('角色', 'role_id', function ($query, $value) {
            $query->whereHas('roles', function ($query) use ($value) {
                $query->where((new $this->model)->roles()->getTable() . '.role_id', $value);
            });
        })->select(function () {
            return $this->roleModel::pluck('name', 'role_id')->toArray();
        })->quick();

        $table->column('角色', 'roles.name')->sort(0);
        return $table;
    }

    protected function form(int $id = 0): \Duxravel\Core\UI\Form
    {
        $form = $this->traitForm($id);

        $form->select('角色', 'role_ids', function () {
            return $this->roleModel::pluck('name', 'role_id');
        }, 'roles')->multi()->verify([
            'required',
        ], [
            'required' => '请选择角色',
        ])->sort(-1);

        return $form;
    }

}
