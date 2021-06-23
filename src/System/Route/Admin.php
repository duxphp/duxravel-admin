<?php

use Illuminate\Support\Facades\Route;

/**
 * 用户登录
 */
Route::get('login', 'Modules\System\Admin\Login@index')->name('admin.login');
Route::post('login', 'Modules\System\Admin\Login@submit')->name('admin.login.submit');
Route::get('login/logout', 'Modules\System\Admin\Login@logout')->name('admin.login.logout');

/**
 * 用户注册
 */
Route::get('register', 'Modules\System\Admin\Register@index')->middleware('auth.admin.register')->name('admin.register');
Route::post('register', 'Modules\System\Admin\Register@submit')->middleware('auth.admin.register')->name('admin.register.submit');
