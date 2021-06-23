<?php

namespace Modules\System\Admin;

use Illuminate\Support\Facades\DB;

class Index extends Common
{

    public function index()
    {
        return redirect(route(config('admin.home')));
    }
}
