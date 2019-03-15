<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->char('mobile', 11)->index('mobile')->comment('电话')->default('');
            $table->string('name',20)->comment('用户昵称')->default('');
            $table->string('email',50)->comment('邮箱')->default('');
            $table->char('openid',28)->comment('微信openid')->default('');
            $table->string('password',32)->comment('密码')->default('');
            $table->string('access_token',255)->comment('微信token')->default('');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('冻结  1为正常,0为冻结');
            $table->string('token',255)->default('');
            $table->date('birthday')->comment('生日')->nullable($value = true);
            $table->timestamp('expired_time')->comment('token过期时间')->useCurrent();
            $table->string('prov',40)->comment('省份')->default('');
            $table->string('city',40)->comment('城市')->default('');
            $table->string('area',40)->comment('区县')->default('');
            $table->string('home_prov',40)->comment('故乡省份')->default('');
            $table->string('home_city',40)->comment('故乡城市')->default('');
            $table->string('home_area',40)->comment('故乡区县')->default('');
            $table->tinyInteger('sex')->unsigned()->comment('性别,1为男,2为女,3不男不女')->default(3);
            $table->ipAddress('last_login_ip')->comment('上次登录ip')->default('');
            $table->string('avatar',255)->comment('头像')->default('');
            $table->string('desc',100)->comment('个人简介')->default('');
            $table->unsignedDecimal('amount', 8, 2)->comment('消费总计')->default(0);
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
        Schema::dropIfExists('users');
    }
}
