<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户id')->default(0);
            $table->integer('level')->unsigned()->comment('等级')->default(0);
            $table->integer('experience')->unsigned()->comment('经验')->default(0);
            $table->integer('integral')->unsigned()->comment('积分')->default(0);
            $table->integer('post_num')->unsigned()->comment('发帖数量')->default(0);
            $table->integer('weibo_num')->unsigned()->comment('发博数量')->default(0);
            $table->integer('goods_num')->unsigned()->comment('发博数量')->default(0);
            $table->unsignedDecimal('balance', 8, 2)->comment('账户余额')->default(0);
            $table->unsignedDecimal('spend', 8, 2)->comment('消费金额')->default(0);
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
        Schema::dropIfExists('user_data');
    }
}
