<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('nickname', 20)->nullable()->default('')->comment('昵称');
            $table->string('username', 20)->default('')->comment('用户名');
            $table->string('password', 250)->default('')->comment('密码');
            $table->rememberToken()->default('')->comment('随机码');
            $table->string('avatar', 250)->nullable()->default('')->comment('头像');
            $table->boolean('status')->default(1)->index('status')->comment('状态');
            $table->integer('create_time');
            $table->integer('update_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_user');
    }
}
