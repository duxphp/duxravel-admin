<?php

use Illuminate\Support\Facades\Route;

// 系统资源
Route::group(['public' => true], function () {
    /**
     * 公共资源
     */
    Route::get('', ['uses' => 'Modules\System\Admin\Index@index', 'desc' => '系统首页'])->name('admin.index');
    Route::get('menu', ['uses' => 'Modules\System\Admin\Index@menu', 'desc' => '系统菜单'])->name('admin.menu');
    Route::get('development', ['uses' => 'Modules\System\Admin\Development@index', 'desc' => '运维概况'])->name('admin.development');
    Route::get('fileManage', ['uses' => 'Modules\System\Admin\FileManage@handle', 'desc' => '文件管理器'])->name('admin.filemanage');
    Route::post('upload', ['uses' => 'Modules\System\Admin\Upload@ajax', 'desc' => '文件上传'])->name('admin.upload');
    Route::post('uploadRemote', ['uses' => 'Modules\System\Admin\Upload@remote', 'desc' => '远程保存'])->name('admin.uploadRemote');
    Route::get('search', ['uses' => 'Modules\System\Admin\Search@index', 'desc' => '全局搜索'])->name('admin.search');
    /**
     * 系统统计
     */
    Route::get('system/visitorApi/loadTotal', ['uses' => 'Modules\System\Admin\VisitorApi@loadTotal', 'desc' => '访问统计'])->name('admin.system.visitorApi.loadTotal');
    Route::get('system/visitorApi/loadDelay', ['uses' => 'Modules\System\Admin\VisitorApi@loadDelay', 'desc' => '延迟统计'])->name('admin.system.visitorApi.loadDelay');
    Route::get('system/visitorOperate/loadData', ['uses' => 'Modules\System\Admin\VisitorOperate@loadData', 'desc' => '操作日志'])->name('admin.system.visitorOperate.loadData');
    Route::get('system/visitorViews/info', ['uses' => 'Modules\System\Admin\VisitorViews@info', 'desc' => '访客详情'])->name('admin.system.visitorViews.info');

});

/**
 * 系统应用
 */
Route::group([
    'prefix' => 'system',
    'auth_app' => '系统应用'
], function () {
    /**
     * 系统设置
     */
    Route::group([
        'auth_group' => '系统设置'
    ], function () {
        Route::get('setting', ['uses' => 'Modules\System\Admin\Setting@handle', 'desc' => '系统设置'])->name('admin.system.setting');
        Route::post('setting/store', ['uses' => 'Modules\System\Admin\Setting@save', 'desc' => '保存设置'])->name('admin.system.setting.save');
    });
    /**
     * 系统用户
     */
    Route::group([
        'auth_group' => '系统用户'
    ], function () {
        Route::manage(\Modules\System\Admin\User::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
    });
    /**
     * 系统角色
     */
    Route::group([
        'auth_group' => '系统角色'
    ], function () {
        Route::manage(\Modules\System\Admin\Role::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
    });
    /**
     * 操作日志
     */
    Route::group([
        'auth_group' => '操作日志'
    ], function () {
        Route::get('operate', ['uses' => 'Modules\System\Admin\VisitorOperate@index', 'desc' => '列表'])->name('admin.system.operate');
        Route::get('operate/ajax', ['uses' => 'Modules\System\Admin\VisitorOperate@ajax', 'desc' => '列表'])->name('admin.system.operate.ajax');
        Route::get('operate/info/{id}', ['uses' => 'Modules\System\Admin\VisitorOperate@info', 'desc' => '详情'])->name('admin.system.operate.info');
    });

    Route::group([
        'auth_group' => '接口统计'
    ], function () {
        Route::get('system/visitorApi', ['uses' => 'Modules\System\Admin\VisitorApi@index', 'desc' => '列表'])->name('admin.system.visitorApi');
    });

    /**
     * 系统队列
     */
    Route::group([
        'auth_group' => '任务调度'
    ], function () {
        Route::get('task', ['uses' => 'Modules\System\Admin\Task@index', 'desc' => '列表'])->name('admin.system.task');
    });


    Route::group([
        'auth_group' => '应用中心'
    ], function () {
        Route::get('application', ['uses' => 'Modules\System\Admin\Application@index', 'desc' => '列表'])->name('admin.system.application');
    });

    /**
     * 接口授权
     */
    Route::group([
        'auth_group' => '接口授权'
    ], function () {
        Route::manage(\Modules\System\Admin\Api::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
        Route::post('api/token/{id?}', ['uses' => 'Modules\System\Admin\Api@token', 'desc' => '更换TOKEN'])->name('admin.system.api.token');
    });
});

