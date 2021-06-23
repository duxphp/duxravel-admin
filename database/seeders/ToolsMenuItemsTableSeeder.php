<?php

namespace Duxravel\System\Seeders;

use Illuminate\Database\Seeder;

class ToolsMenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tools_menu_items')->delete();
        
        \DB::table('tools_menu_items')->insert(array (
            0 => 
            array (
                'item_id' => 1,
                'parent_id' => NULL,
                'menu_id' => 2,
                'name' => '关于我们',
                'url' => 'www.baidu.com',
                '_lft' => 1,
                '_rgt' => 2,
                'create_time' => 1624089499,
                'update_time' => 1624089499,
            ),
        ));
        
        
    }
}