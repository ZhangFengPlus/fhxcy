<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id')->comment('主键id');
            $table->string('name', 20)->index('name')->comment('用户名');
            $table->string('avatar',255)->comment('头像')->default('');
            $table->string('password', 32)->comment('登录密码');
            $table->string('email', 50)->comment('邮箱');
            $table->char('mobile', 11)->index('mobile')->comment('电话');
            $table->ipAddress('last_login_ip')->default('')->comment('上次登录ip');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('状态:1为正常,0为冻结,其他状态自定义');
            $table->integer('group_id')->unsigned()->comment('管理组id')->default(0);
            $table->integer('business_id')->comment('商家id')->default(0);
            $table->string('token', 255)->default('');
            $table->timestamps();//update为更新时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
