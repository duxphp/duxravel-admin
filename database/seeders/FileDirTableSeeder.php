<?php

namespace Duxravel\System\Seeders;

use Illuminate\Database\Seeder;

class FileDirTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('file_dir')->delete();
        
        \DB::table('file_dir')->insert(array (
            0 => 
            array (
                'dir_id' => 23,
                'name' => '默认',
                'has_type' => 'admin',
            ),
            1 => 
            array (
                'dir_id' => 25,
                'name' => '图片',
                'has_type' => 'admin',
            ),
            2 => 
            array (
                'dir_id' => 26,
                'name' => '视频',
                'has_type' => 'admin',
            ),
            3 => 
            array (
                'dir_id' => 28,
                'name' => '其他',
                'has_type' => 'admin',
            ),
        ));
        
        
    }
}