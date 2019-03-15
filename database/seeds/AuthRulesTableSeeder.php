<?php

use Illuminate\Database\Seeder;

class AuthRulesTableSeeder extends Seeder
{
    /**
     * 权限数据填充
     * @return void
     */
    public function run()
    {
        DB::table('auth_rules')->insert([
            ['id'=>8,   'pid'=>0,   'title'=>'权限管理',    'rule'=>'', 'desc'=>'权限管理', 'type'=>2,  'status'=>1],
            ['id'=>9,   'pid'=>8,   'title'=>'管理员列表',    'rule'=>'admin/list',   'desc'=>'管理员列表',    'type'=>3,  'status'=>1],
            ['id'=>10,  'pid'=>9,   'title'=>'添加管理员' ,   'rule'=>'admin/register',   'desc'=>'添加管理员',    'type'=>1,  'status'=>1],
            ['id'=>11,  'pid'=>9,   'title'=>'修改管理员' ,   'rule'=>'admin/update',   'desc'=>'修改管理员',    'type'=>1,  'status'=>1],
            ['id'=>12,  'pid'=>9,   'title'=>'管理员详情' ,   'rule'=>'admin/detail',   'desc'=>'管理员详情',    'type'=>1,  'status'=>1],
            ['id'=>13,  'pid'=>9,   'title'=>'删除管理员' ,   'rule'=>'admin/delete',   'desc'=>'删除管理员',    'type'=>1,  'status'=>1],
            ['id'=>14,  'pid'=>9,   'title'=>'冻结管理员' ,   'rule'=>'admin/freeze',   'desc'=>'冻结管理员',    'type'=>1,  'status'=>1],
            ['id'=>15,  'pid'=>9,   'title'=>'解冻管理员' ,   'rule'=>'admin/unfreeze',   'desc'=>'解冻管理员',    'type'=>1,  'status'=>1],
            ['id'=>16,  'pid'=>9,   'title'=>'解冻管理员' ,   'rule'=>'admin/unfreeze',   'desc'=>'解冻管理员',    'type'=>1,  'status'=>1],
            ['id'=>17,  'pid'=>8,   'title'=>'管理组列表'  ,  'rule'=>'admin/group/list',   'desc'=>'管理组列表',    'type'=>3,  'status'=>1],
            ['id'=>18,  'pid'=>17,   'title'=>'添加管理组' ,   'rule'=>'admin/group/add',   'desc'=>'添加管理组',    'type'=>1,  'status'=>1],
            ['id'=>19,  'pid'=>17,   'title'=>'修改管理组' ,   'rule'=>'admin/group/update',   'desc'=>'修改管理组',    'type'=>1,  'status'=>1],
            ['id'=>20,  'pid'=>17,   'title'=>'管理组详情'  ,  'rule'=>'admin/group/detail',   'desc'=>'管理组详情',    'type'=>1,  'status'=>1],
            ['id'=>21,  'pid'=>17,   'title'=>'删除管理组'  ,  'rule'=>'admin/group/delete',   'desc'=>'删除管理组',    'type'=>1,  'status'=>1],
            ['id'=>22,  'pid'=>17,   'title'=>'冻结管理组'  ,  'rule'=>'admin/group/freeze',   'desc'=>'冻结管理组',    'type'=>1,  'status'=>1],
            ['id'=>23,  'pid'=>17,   'title'=>'解冻管理组'  ,  'rule'=>'admin/group/unfreeze',   'desc'=>'解冻管理组',    'type'=>1,  'status'=>1],
            ['id'=>24,  'pid'=>8,   'title'=>'日志列表'  ,  'rule'=>'admin/log/list',   'desc'=>'日志列表',    'type'=>3,  'status'=>1],
        ]);
    }
}
