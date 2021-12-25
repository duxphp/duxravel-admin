<?php

use Illuminate\Support\Facades\Route;


Route::get('', ['uses' => 'Modules\System\Admin\Index@index', 'desc' => '系统首页'])->name('admin.index');

/**
 * 用户登录
 */
Route::post('login', 'Modules\System\Admin\Login@submit')->name('admin.login.submit');
Route::get('login/logout', 'Modules\System\Admin\Login@logout')->name('admin.login.logout');
Route::get('login/check', 'Modules\System\Admin\Login@check')->name('admin.login.check');

/**
 * 用户注册
 */
Route::post('register', 'Modules\System\Admin\Register@submit')->middleware('auth.manage.register')->name('admin.register.submit');

/**
 * 其他公用
 */
Route::get('map/weather', ['uses' => 'Modules\System\Admin\Map@weather', 'desc' => '天气服务'])->name('admin.map.weather');
