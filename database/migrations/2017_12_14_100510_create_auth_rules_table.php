<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->unsigned()->default(0)->comment('所属父级id,当type为1且pid不为0的时,该url为后台子菜单');
            $table->string('title', 20)->default('')->comment('权限标题');
            $table->string('rule', 100)->default('')->index('rule')->comment('规则名称:url');
            $table->string('desc', 100)->default('')->comment('规则描述');
            $table->tinyInteger('type')->unsigned()->default(1)->comment('状态:1为url,2为主菜单,3为子菜单');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('状态:1为正常,0为无效,其他状态自定义');
            $table->string('path', 100)->default('')->comment('前台路由');
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
        Schema::dropIfExists('auth_rules');
    }
}
