<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth_group extends Model
{
    //管理组
    
    //用first查询一条
    public function getFirst(array $where,$select = '*'){
        return $this->select($select)->where($where)->first();
    }
}
