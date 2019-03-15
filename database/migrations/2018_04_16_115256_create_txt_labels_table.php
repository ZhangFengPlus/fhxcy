<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxtLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('txt_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',8)->comment('标题')->index('name');
            $table->tinyInteger('status')->unsigned()->default(1)->index('status')->comment('状态:1为正常,0为无效,其他状态自定义');
            $table->string('pic',8)->default('')->comment('色值');
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
        Schema::dropIfExists('txt_labels');
    }
}
