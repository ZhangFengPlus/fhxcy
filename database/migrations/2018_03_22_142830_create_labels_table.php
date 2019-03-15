<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',8)->comment('标题');
            $table->tinyInteger('type')->unsigned()->comment('类型:1为图片,2为文字');
            $table->tinyInteger('status')->unsigned()->default(1)->index('status')->comment('状态:1为正常,0为无效,其他状态自定义');
            $table->string('content')->default('')->comment('内容');
            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labels');
    }
}
