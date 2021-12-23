<?php

namespace Modules\System\Admin;

class Application extends \Modules\System\Admin\Common
{

    public function index()
    {
        app(\Duxravel\Core\Util\Menu::class)->getManage('admin');
        $data = app(\Duxravel\Core\Util\Menu::class)->getApps();


        $typeArr = ['business', 'market', 'tools'];
        $typeData = [];
        foreach ($data as $vo) {
            if ($vo['name'] == '应用中心') continue;
            if(in_array($vo['type'], $typeArr)) {
                $name = $vo['type'];
            }else {
                $name = 'other';
            }
            $typeData[$name][] = $vo;
        }


        $typeList = [
            'business' => [
                'name' => '业务应用',
                'desc' => '系统业务相关模块',
                'color' => 'blue',
                'data' => $typeData['business'],
            ],
            'tools' => [
                'name' => '工具应用',
                'desc' => '系统常用辅助工具',
                'color' => 'green',
                'data' => $typeData['tools'],
            ],
            'other' => [
                'name' => '其他应用',
                'desc' => '系统为定义分类应用',
                'color' => 'yellow',
                'data' => $typeData['other'],
            ],
        ];
        $this->assign('typeList', $typeList);


        $formList = \Duxravel\Core\Model\Form::where('manage' , 0)->get();
        $this->assign('formList', $formList);
        return $this->systemView('vendor/duxphp/duxravel-admin/src/System/View/Admin/Application/index');
    }

}
