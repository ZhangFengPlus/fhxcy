<?php
/**
 * Created by PhpStorm.
 * User: zhang_feng
 * Date: 2018/10/21
 * Time: 0:02
 */

namespace App\Models\Check;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GiftBox extends Model
{
    //用户

    protected $table = 'gift_box';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id','name','picture','mobile','integral'
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


    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }


}