<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_school', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index('user_id')->comment('用户id');
            $table->string('name',30)->comment('学校名称')->default('');
            $table->string('major',15)->comment('专业名称')->default('');
            $table->string('desc',50)->comment('描述')->default('');
            $table->date('began_at')->comment('开始时间');
            $table->date('ended_at')->comment('结束时间')->nullable($value = true);
            $table->tinyInteger('type')->unsigned()->comment('在读中,1为在读,0为非在读')->default(0);
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
        Schema::dropIfExists('user_school');
    }
}
