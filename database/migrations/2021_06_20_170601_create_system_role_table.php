<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_role', function (Blueprint $table) {
            $table->increments('role_id');
            $table->string('name', 20)->nullable()->default('')->comment('名称');
            $table->text('purview')->nullable()->comment('权限信息');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_role');
    }
}
