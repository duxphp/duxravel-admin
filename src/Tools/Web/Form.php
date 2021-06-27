<?php

namespace Modules\Tools\Web;

use Modules\Article\Resource\ArticleCollection;
use Duxravel\Core\Web\Base;

class Form extends Base
{
    public function index($id)
    {
        $formInfo = \Duxravel\Core\Model\Form::find($id);
        if (!$formInfo || !$formInfo->tpl_list || $formInfo->manage) {
            app_error('表单不存在', 404);
        }
        $this->assign('formInfo', $formInfo);
        return $this->view($formInfo->tpl_list);
    }

    public function info($id)
    {
        $info = \Duxravel\Core\Model\FormData::find($id);
        if (!$info) {
            app_error('信息不存在', 404);
        }
        $formInfo = \Duxravel\Core\Model\Form::find($info->form_id);
        if (!$formInfo || !$formInfo->tpl_info || $formInfo->manage) {
            app_error('表单不存在', 404);
        }
        $tpl = $info->tpl ?: 'page';
        $this->assign('formInfo', $formInfo);
        $this->assign('info', $info);
        return $this->view($tpl);
    }

    public function push($id)
    {
        $formInfo = \Duxravel\Core\Model\Form::find($id);
        if (!$formInfo || $formInfo->manage || !$formInfo->submit) {
            app_error('表单不存在', 404);
        }
        $rules = ['captcha' => 'required|captcha'];
        $validator = validator()->make(request()->input(), $rules);
        if ($validator->fails()) {
            app_error('验证码输入有误');
        }

        $lastInfo = \Duxravel\Core\Model\FormData::latest()->first();

        if ($lastInfo->create_time->lt($formInfo['Interval'])) {
            app_error('提交太快了，请稍等');
        }

        \Duxravel\Core\Util\Form::saveForm($id, request()->input());
        return app_success('提交成功' . ($formInfo->audit ? '，请耐心等待审核' : ''));
    }
}
