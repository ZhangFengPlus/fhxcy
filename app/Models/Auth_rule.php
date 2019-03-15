<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth_rule extends Model
{
    //权限

    //获取子级权限
    public function getChildRules(){
        return $this->hasMany($this,'pid','id');
    }
}
