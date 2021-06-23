<?php

namespace Duxravel\System\Seeders;

use Illuminate\Database\Seeder;

class SystemRoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('system_role')->delete();
        
        \DB::table('system_role')->insert(array (
            0 => 
            array (
                'role_id' => 1,
                'name' => '管理员',
                'purview' => NULL,
            ),
        ));
        
        
    }
}