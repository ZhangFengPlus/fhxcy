<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->index('category_id')->comment('分类id');
            $table->integer('admin_id')->unsigned()->index('admin_id')->comment('管理员id');
            $table->integer('business_id')->unsigned()->index('business_id')->comment('商家id')->default(0);
            $table->string('title', 50)->comment('资讯标题');
            $table->string('desc', 100)->default('')->comment('描述');
            $table->string('pic', 255)->default('')->comment('资讯推荐图');
            $table->string('thumb', 255)->default('')->comment('资讯缩略图');
            $table->tinyInteger('hot')->unsigned()->comment('推荐')->default(0);
            $table->integer('sort')->unsigned()->comment('排序')->default(0);
            $table->integer('fake_pv')->unsigned()->comment('附加点击量')->default(0);
            $table->integer('real_pv')->unsigned()->comment('真实点击量')->default(0);
            $table->text('content')->comment('详情');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('状态:1为正常,0为无效,其他状态自定义');
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
        Schema::dropIfExists('infos');
    }
}
