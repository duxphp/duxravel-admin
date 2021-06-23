<?php

namespace Modules\Tools\Model;

/**
 * class ToolsMenuItems
 * @package Modules\Tools\Model
 */
class ToolsMenuItems extends \Duxravel\Core\Model\Base
{
    use \Kalnoy\Nestedset\NodeTrait;

    protected function getScopeAttributes(): array
    {
        return ['menu_id'];
    }

    protected $table = 'tools_menu_items';

    protected $primaryKey = 'item_id';

}
