<?php

namespace Modules\Tools\Web;

use Modules\Article\Resource\ArticleCollection;
use Duxravel\Core\Web\Base;

class Pages extends Base
{
    public function index($id)
    {
        $info = \Modules\Tools\Model\ToolsPage::find($id);
        if (!$info) {
            app_error('页面不存在', 404);
        }
        $tpl = $info->tpl ?: 'page';
        $this->meta($info->name, $info->keywords, $info->description);
        $this->assign('info', $info ?: collect());
        return $this->view($tpl);
    }
}
