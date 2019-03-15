<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->string('name',15)->default('')->comment('收件人');
            $table->string('mobile',11)->default('')->comment('联系方式');
            $table->string('prov',20)->default('')->comment('省份');
            $table->string('city',20)->default('')->comment('城市');
            $table->string('area',20)->default('')->comment('区县');
            $table->string('address',100)->default('')->comment('详细地址');
            $table->tinyInteger('status')->unsigned()->default(0);
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
        Schema::dropIfExists('addresses');
    }
}
