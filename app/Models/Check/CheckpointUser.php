<?php
/**
 * Created by PhpStorm.
 * User: zhang_feng
 * Date: 2018/10/14
 * Time: 18:30
 */
namespace App\Models\Check;

use Illuminate\Database\Eloquent\Model;

class CheckpointUser extends Model
{
    //用户

    protected $table = 'checkpoint_user';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id','stars','checkponint_id'
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