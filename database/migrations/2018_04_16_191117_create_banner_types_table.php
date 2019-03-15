<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_types', function (Blueprint $table) {
            $table->integer('banner_id')->unsigned()->index('banner_id')->comment('banner_id')->default(0);
            $table->tinyInteger('show_type')->unsigned()->index('show_type')->comment('展示类型')->default(0);
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
        Schema::dropIfExists('banner_types');
    }
}
