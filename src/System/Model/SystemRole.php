<?php

namespace Modules\System\Model;

/**
 * Class SystemRole
 * @package Modules\System\Model
 */
class SystemRole extends \Duxravel\Core\Model\Base
{

    protected $table = 'system_role';

    protected $primaryKey = 'role_id';

    public $timestamps = false;

    protected $casts = [
        'purview' => 'array',
    ];

}
