<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * 后台管理员数据填充
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'id'=>1,
            'name'=>'admin',
            'password'=>md5(md5(123456).env('APP_ATTACH')),
            'email'=>'',
            'mobile'=>'admin',
            'token'=>123456
        ]);
    }
}
