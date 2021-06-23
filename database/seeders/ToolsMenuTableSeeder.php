<?php

namespace Duxravel\System\Seeders;

use Illuminate\Database\Seeder;

class ToolsMenuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tools_menu')->delete();
        
        \DB::table('tools_menu')->insert(array (
            0 => 
            array (
                'menu_id' => 1,
                'name' => '默认菜单',
                'create_time' => 1623312566,
                'update_time' => 1623312566,
            ),
            1 => 
            array (
                'menu_id' => 2,
                'name' => '底部菜单',
                'create_time' => 1624089462,
                'update_time' => 1624089462,
            ),
            2 => 
            array (
                'menu_id' => 3,
                'name' => '友情链接',
                'create_time' => 1624089626,
                'update_time' => 1624089626,
            ),
        ));
        
        
    }
}