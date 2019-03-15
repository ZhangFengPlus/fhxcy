<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id')->comment('主键id');
            $table->char('mobile', 11)->index('mobile')->comment('电话');
            $table->integer('code')->index('code')->comment('验证码');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('状态:1为未使用,0为已使用');
            $table->timestamp('overdued_at')->comment('失效时间');
            $table->tinyInteger('type')->unsigned()->default(1)->comment('类型:1为注册,2为登录,3为找回密码,其他状态自定义');
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
        Schema::dropIfExists('codes');
    }
}
