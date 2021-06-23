<?php

namespace Modules\System\Middleware;

use Modules\System\Model\SystemUser;

class Register
{
    public function handle($request, \Closure $next)
    {
        $count = SystemUser::count();
        if ($count) {
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
