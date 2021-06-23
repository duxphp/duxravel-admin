<?php

namespace Duxravel\System\Seeders;

use Illuminate\Database\Seeder;

class ToolsMarkTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tools_mark')->delete();
        
        \DB::table('tools_mark')->insert(array (
            0 => 
            array (
                'mark_id' => 1,
                'name' => '模板说明',
                'type' => 'text',
                'content' => '这是一个默认的新闻中心演示模板，包含了常用的标签示例，您可以重新创建主题，或者修改该主题进行使用。',
                'create_time' => 1624087193,
                'update_time' => 1624089421,
            ),
        ));
        
        
    }
}