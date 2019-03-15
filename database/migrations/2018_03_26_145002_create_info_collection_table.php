<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_collection', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('info_id')->unsigned()->index('info_id')->comment('资讯id');
            $table->integer('user_id')->unsigned()->index('user_id')->comment('用户id');
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
        Schema::dropIfExists('info_collection');
    }
}
