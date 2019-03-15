<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //用户

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'mobile','name','email','openid','password','access_token','status','token','birthday','expired_time','prov','city','area','home_prov','home_city',
        'home_area','sex','last_login_ip','avatar','desc','amount','fraction'
    ];


    //添加
    public function adds(array $data)
    {
        return $this->create($data);
    }

    //修改
    public function editor(array $data, $id)
    {
        return $this->where('id', '=', intval($id))->update($data);
    }

    //删除
    public function deletes($id)
    {
        return $this->where('id', '=', intval($id))->delete();
    }

    // 后台 -----
    //删除
    public function boserver_deletes()
    {
        return $this->delete();
    }

    //修改
    public function boserver_editor(array $data)
    {
        return $this->update($data);
    }


}
