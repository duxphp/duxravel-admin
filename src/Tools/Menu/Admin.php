<?php

use \Duxravel\Core\Facades\Menu;

$icon = '<svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>';

Menu::add('tools', [
    'name' => '工具',
    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
    'order' => 140,
], function () {

    Menu::group([
        'name' => '地区',
        'order' => 200,
    ], function () {
        Menu::link('地区数据', 'admin.tools.area');
    });

});

Menu::add('form', [
    'name' => '表单生成',
    'icon' => $icon,
    'hidden' => true,
    'order' => 1000,
    'route' => 'admin.tools.form'
]);

Menu::app([
    'name' => '自定义表单',
    'desc' => '多功能自定义表单功能',
    'type' => 'tools',
    'route' => 'admin.tools.form',
    'color' => '#ff5500',
    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>'
]);

$formList = \Duxravel\Core\Model\Form::where('manage', 0)->get();
foreach ($formList as $vo) {
    Menu::add('form_data', [
        'name' => $vo['name'],
        'icon' => $icon,
        'hidden' => true,
        'order' => 1000,
        'route' => 'admin.tools.formData',
        'params' => ['form' => $vo->form_id],
    ]);

    Menu::app([
        'name' => $vo['name'],
        'desc' => $vo['description'],
        'route' => 'admin.tools.formData',
        'color' => '#ff5500',
        'params' => ['form' => $vo->form_id],
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>'
    ]);
}
