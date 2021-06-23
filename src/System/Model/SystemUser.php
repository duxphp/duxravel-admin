<?php

namespace Modules\System\Model;

use \Illuminate\Foundation\Auth\User;

/**
 * Class SystemUser
 * @package Modules\System\Model
 */
class SystemUser extends User
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $dateFormat = 'U';

    protected $table = 'system_user';

    protected $primaryKey = 'user_id';

    protected $fillable = ['username', 'password'];

    public function roles()
    {
        return $this->belongsToMany(\Modules\System\Model\SystemRole::class, 'system_user_role', 'user_id', 'role_id');
    }

    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            return;
        }
        $this->attributes['password'] = \Hash::make($value);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($data) {
            $data->roles()->detach();
        });
    }

}
