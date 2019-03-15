<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50)->index('title')->comment('内容标题');
            $table->string('pic', 255)->default('')->comment('图片');
            $table->integer('business_id')->unsigned()->comment('商家id')->default(0);
            $table->tinyInteger('hot')->unsigned()->comment('推荐')->default(0);
            $table->integer('sort')->unsigned()->comment('排序')->default(0);
            $table->tinyInteger('type')->unsigned()->index('type')->comment('类型:1为链接,2为分类,3为商品,4为资讯,5为帖子');
            $table->string('url', 255)->default('')->comment('图片');
            $table->integer('category_id')->unsigned()->comment('分类id')->default(0);
            $table->integer('goods_id')->unsigned()->comment('商品id')->default(0);
            $table->integer('info_id')->unsigned()->comment('资讯id')->default(0);
            $table->integer('note_id')->unsigned()->comment('帖子id')->default(0);
            $table->integer('admin_id')->unsigned()->index('admin_id')->comment('管理员id');
            $table->tinyInteger('status')->unsigned()->default(1)->index('status')->comment('状态:1为正常,0为无效,其他状态自定义');
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
        Schema::dropIfExists('banners');
    }
}
