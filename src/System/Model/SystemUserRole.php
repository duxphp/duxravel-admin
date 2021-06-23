<?php

namespace Modules\System\Model;

/**
 * Class SystemUserRole
 * @package Modules\System\Model
 */
class SystemUserRole extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'system_user_role';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

}
