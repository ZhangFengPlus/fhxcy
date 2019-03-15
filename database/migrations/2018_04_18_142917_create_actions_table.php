<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedDecimal('single_experience', 8, 2)->comment('单次经验')->default(0);
            $table->unsignedDecimal('single_integral', 8, 2)->comment('单次积分')->default(0);
            $table->string('desc')->comment('描述')->default('');
            $table->string('action',50)->comment('行为')->default('');
            $table->unsignedDecimal('most_experience', 8, 2)->comment('上限经验')->default(0);
            $table->unsignedDecimal('most_integral', 8, 2)->comment('上限积分')->default(0);
            $table->tinyInteger('type')->unsigned()->default(1)->comment('状态:1为社区,2为消费,3为分销');
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
        Schema::dropIfExists('actions');
    }
}
