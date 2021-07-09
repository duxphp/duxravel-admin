<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'tools',
    'auth_app' => '扩展工具'
], function () {

    Route::group([
        'auth_group' => '地区数据'
    ], function () {
        Route::get('area', ['uses' => 'Modules\Tools\Admin\Area@index', 'desc' => '列表'])->name('admin.tools.area');
        Route::get('area/add', ['uses' => 'Modules\Tools\Admin\Area@import', 'desc' => '导入'])->name('admin.tools.area.import');
        Route::post('area/store', ['uses' => 'Modules\Tools\Admin\Area@importData', 'desc' => '导入数据'])->name('admin.tools.area.importData');
        Route::post('area/del/{id?}', ['uses' => 'Modules\Tools\Admin\Area@del', 'desc' => '删除'])->name('admin.tools.area.del');
    });

    Route::group([
        'auth_group' => '自定义表单'
    ], function () {
        Route::manage(\Modules\Tools\Admin\Form::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
        Route::get('form/setting/{id}', ['uses' => 'Modules\Tools\Admin\Form@setting', 'desc' => '设置'])->name('admin.tools.form.setting');
        Route::post('form/setting/{id}', ['uses' => 'Modules\Tools\Admin\Form@settingSave', 'desc' => '设置数据'])->name('admin.tools.form.setting.save');
    });

    Route::group([
        'auth_group' => '表单数据'
    ], function () {
        Route::manage(\Modules\Tools\Admin\FormData::class)->only(['index', 'data', 'page', 'save', 'status', 'del'])->make();
    });

    Route::group([
        'auth_group' => '菜单管理'
    ], function () {
        Route::manage(\Modules\Tools\Admin\Menu::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
    });
    Route::group([
        'auth_group' => '菜单内容管理'
    ], function () {
        Route::manage(\Modules\Tools\Admin\MenuItems::class)->only(['index', 'data', 'page', 'save', 'del', 'sort'])->prefix('menuItems-{menu}')->make();
    });

    Route::group([
        'auth_group' => '链接管理'
    ], function () {
        Route::manage(\Modules\Tools\Admin\Url::class)->only(['index', 'data'])->make();
    });

    Route::group([
        'auth_group' => '自定义页面'
    ], function () {
        Route::manage(\Modules\Tools\Admin\Page::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
    });

    Route::group([
        'auth_tags' => '内容标签'
    ], function () {
        Route::get('tags', ['uses' => 'Modules\Tools\Admin\Tags@index', 'desc' => '列表'])->name('admin.tools.tags');
        Route::post('tags/del/{id?}', ['uses' => 'Modules\Tools\Admin\Tags@del', 'desc' => '删除'])->name('admin.tools.tags.del');
        Route::get('tags/empty', ['uses' => 'Modules\Tools\Admin\Tags@empty', 'desc' => '清理'])->name('admin.tools.tags.empty');
    });
    Route::group([
        'auth_group' => '模板标记'
    ], function () {
        Route::manage(\Modules\Tools\Admin\Mark::class)->make();
    });
    // Generate Route Make
});

