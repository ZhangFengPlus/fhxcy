<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',10)->comment('姓名')->default('');
            $table->char('mobile', 11)->index('mobile')->comment('电话');
            $table->string('company',20)->comment('公司名称')->default('');
            $table->string('job',10)->comment('职位')->default('');
            $table->string('content')->comment('详细内容')->default('');
            $table->tinyInteger('status')->unsigned()->comment('回复,1为已回复,0为未回复')->default(0);
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
        Schema::dropIfExists('contacts');
    }
}
