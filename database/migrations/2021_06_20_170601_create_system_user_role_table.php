<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_user_role', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->index('user_id')->comment('用户id');
            $table->integer('role_id')->nullable()->index('role_id')->comment('角色id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_user_role');
    }
}
