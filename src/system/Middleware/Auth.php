<?php

namespace Modules\System\Middleware;

use Modules\System\Model\SystemUser;

class Auth
{
    public function handle($request, \Closure $next)
    {
        // 登录跳转
        if (!auth('admin')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                app_error('登录失效', 401, route('admin.login'));
            } else {
                $count = SystemUser::count();
                if ($count) {
                    return redirect()->intended(route('admin.login'));
                } else {
                    return redirect()->intended(route('admin.register'));
                }
            }
        }
        // 权限检测
        $public = request()->route()->getAction('public');
        if (!$public) {
            // 非公共方法进行验证
            $name = request()->route()->getName();
            $roleList = auth('admin')->user()->roles()->get();
            // 合并多角色权限
            $purview = [];
            $roleList->map(function ($item) use (&$purview) {
                $purview = array_merge($purview, (array) $item->purview);
            });
            $purview = array_filter($purview);
            // 权限存在判断
            if ($purview && !in_array($name, $purview)) {
                app_error('没有权限使用该功能', 403);
            }
        }
        return $next($request);
    }
}
