<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->unsigned()->comment('父级id')->default(0);
            $table->string('title',10)->comment('标题');
            $table->tinyInteger('type')->unsigned()->comment('类型 1为资讯,2为商品');
            $table->tinyInteger('level')->unsigned()->comment('层级')->default(1);
            $table->integer('sort')->unsigned()->comment('排序')->default(0);
            $table->tinyInteger('hot')->unsigned()->comment('推荐')->default(0);
            $table->string('pic')->default('')->comment('图标');
            $table->string('thumb')->default('')->comment('缩略图');
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
        Schema::dropIfExists('categories');
    }
}
