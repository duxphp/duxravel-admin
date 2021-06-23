<?php

namespace Modules\System\Admin;

class Application extends \Modules\System\Admin\Common
{

    public function index()
    {

        $list = app_hook('Service', 'Menu', 'getAppMenu');
        $data = [];
        foreach ((array) $list as $value) {
            $data = array_merge((array) $data, (array) $value);
        }
        $typeArr = ['business', 'market', 'tools'];
        $typeData = [];
        foreach ($data as $vo) {
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
            'market' => [
                'name' => '营销应用',
                'desc' => '系统营销销售模块',
                'color' => 'red',
                'data' => $typeData['market'],
            ],
            'tools' => [
                'name' => '辅助工具',
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
        return $this->systemView();
    }

}
