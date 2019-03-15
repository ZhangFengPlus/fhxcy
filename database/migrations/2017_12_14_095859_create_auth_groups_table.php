<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 20)->default('')->comment('组标题');
            $table->string('desc', 100)->default('')->comment('组描述');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('状态:1为正常,0为冻结,其他状态自定义');
            $table->string('rules')->comment('组所拥有权限,以,隔开');
            $table->tinyInteger('lower')->unsigned()->default(1)->comment('类型:0为管理员,1为添加员');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_groups');
    }
}
