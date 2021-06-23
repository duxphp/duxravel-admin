<?php

namespace Modules\Tools\Service;

use Modules\Tools\Model\ToolsMenu;

/**
 * 系统菜单接口
 */
class Menu
{

    /**
     * 获取菜单结构
     */
    public function getAdminMenu()
    {
        $model = ToolsMenu::get();
        $menuList = $model->map(function ($item) {
            return [
                'name' => $item['name'],
                'url' => 'admin.tools.menuItems',
                'params' => ['menu' => $item['menu_id']]
            ];
        })->toArray();

        $curName = request()->route()->getName();
        $formInfo = [];
        if (strpos($curName, 'admin.tools.formData', 0) !== false) {
            $formInfo = \Duxravel\Core\Model\Form::find(request()->get('form'));
        }
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>';
        return [
            'tools' => [
                'name' => '工具',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
                'order' => 140,
                'menu' => [
                    [
                        'name' => '扩展工具',
                        'order' => 0,
                        'menu' => [
                            [
                                'name' => '菜单管理',
                                'url' => 'admin.tools.menu',
                            ],
                            [
                                'name' => '自定义页面',
                                'url' => 'admin.tools.page',
                            ],
                            [
                                'name' => '内容标签',
                                'url' => 'admin.tools.tags',
                            ],
                            [
                                'name' => '模板标记',
                                'url' => 'admin.tools.mark',
                            ],
                            // Generate Menu Make
                        ]
                    ],
                    [
                        'name' => '菜单管理',
                        'order' => 1,
                        'menu' => [
                            ...$menuList
                        ]
                    ],
                    [
                        'name' => '地区',
                        'order' => 200,
                        'menu' => [
                            [
                                'name' => '地区数据',
                                'url' => 'admin.tools.area',
                                'order' => 0
                            ],
                        ]
                    ],
                ],
            ],
            'form' => [
                'name' => '表单',
                'icon' => $icon,
                'hidden' => true,
                'order' => 1000,
                'url' => 'admin.tools.form'
            ],
            'form_data' => [
                'name' => $formInfo ? $formInfo->menu : '表单',
                'icon' => $icon,
                'hidden' => true,
                'order' => 1000,
                'url' => 'admin.tools.formData',
                'params' => $formInfo ? ['form' => request()->get('form')] : []
            ],
        ];
    }

    public function getAppMenu()
    {
        return [
            [
                'name' => '自定义表单',
                'desc' => '多功能自定义表单功能',
                'type' => 'tools',
                'url' => 'admin.tools.form',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>'
            ]
        ];
    }
}

