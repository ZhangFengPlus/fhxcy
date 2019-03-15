<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //管理员

    //用first查询一条
    public function getFirst(array $where,$select = '*'){
        return $this->select($select)->where($where)->first();
    }

    public function getGroup(){
        return $this->hasOne('App\Models\Auth_group','id','group_id');
    }
}
