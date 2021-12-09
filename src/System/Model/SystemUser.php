<?php

namespace Modules\System\Model;

use \Illuminate\Foundation\Auth\User;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Duxravel\Core\Traits\RoleHas;

/**
 * Class SystemUser
 * @package Modules\System\Model
 */
class SystemUser extends User implements JWTSubject
{
    use RoleHas;

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $dateFormat = 'U';

    protected $table = 'system_user';

    protected $primaryKey = 'user_id';

    protected $fillable = ['username', 'password'];


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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthIdentifierName()
    {
        return 'user_id';
    }


}
