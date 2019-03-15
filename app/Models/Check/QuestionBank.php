<?php
/**
 * Created by PhpStorm.
 * User: zhang_feng
 * Date: 2018/10/20
 * Time: 22:11
 */
namespace App\Models\Check;

use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    //用户

    protected $table = 'question_bank';

    protected $primaryKey = 'id';

    protected $fillable = [
        'level', 'title', 'checkponint_id', 'status'
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

    public function questionbank()
    {
        return $this->hasMany(QuestionBank::class,'level','id');
    }

}