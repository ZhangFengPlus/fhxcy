<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    //验证码
    protected $table = 'codes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'mobile','code','status','overdued_at','type'
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
