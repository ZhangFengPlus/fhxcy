<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\Check\GiftBox;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
    * 会员列表
    * @param    string  token
    * @param    int page
    * @param    int pagesize
    * @return   array  data
    **/
    public function list(Request $req){
        $this->useValidator($req,[
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $list = User::offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->orderBy('created_at','desc')
            ->get(['id as user_id','created_at','name','mobile','prov','city','area','birthday','status']);
        if($list->isEmpty()){
            return $this->returnJson(3013, '无数据', '');
        }
        $count = User::count();
        return $this->returnJson(0, '成功', ['data'=>$list,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }


    /**
     * @param Request $req
     * 兑换记录
     */
    public function  exchange(Request $req)
    {
        $this->useValidator($req,[
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);

        $list  = GiftBox::select()->with(['user'=>function($i){
            $i->select('id','name');
        }])
        ->orderBy('created_at','desc')
         ->forPage($req->page, $req->pagesize)
        ->get();
        if($list->isEmpty()){
            return $this->returnJson(3013, '无数据', '');
        }
        $count = GiftBox::count();
        return $this->returnJson(0, '成功', ['data'=>$list,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * @param Request $req
     * 会员管理
     */
    public function user(Request $req)
    {
        $this->useValidator($req,[
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102],
            'status'=>[3,102],  //1id 2会员昵称 3绑定手机号
            'key_word'=>[3,101],
        ]);

        $list = User::select('id','created_at','name','avatar','fraction')
        ->where(function($query) use ($req){
        if($req->status == 1){
            $query->where('id','like',"%$req->keyword%");
        }elseif ($req->status == 2){
            $query->where('name','like',"%$req->keyword%");
        }elseif ($req->status == 3){
            $query->where('mobile','like',"%$req->keyword%");
        }
    });
        $count = $list->count();
        if(!$count){
            return $this->returnJson(5005,'无数据');
        }
        $list = $list
            ->forPage($req->page, $req->pagesize)
            ->orderBy('created_at','desc')
            ->get();
        return $this->returnJson(0,'成功',['data'=>$list,'page'=>$req->page,'count'=>$count]);
    }





    /**
    * 关键字搜索
    * @param    string  token
    * @param    string  keyword
    * @param    int type
    * @param    int page
    * @param    int pagesize
    * @return   array  data
    **/
    public function search(Request $req){
        $this->useValidator($req,[
            'keyword'=>[0,1,101,211],
            'type'=>[0,1,102,297],
            'page'=>[0,1,2,102],
            'pagesize'=>[0,1,102]
        ]);
        $user = User::where(function($query)use($req){
            if($req->type == 1){
                $query->where('name','like',"%$req->keyword%");
            }else{
                $query->where('mobile','like',"%$req->keyword%");
            }
        });
        $count = $user->count();
        $list = $user->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->orderBy('created_at','desc')
            ->get(['id as user_id','created_at','name','mobile','prov','city','area','birthday','status']);
        if($list->isEmpty()){
            return $this->returnJson(3013, '无数据', '');
        }
        return $this->returnJson(0, '成功', ['data'=>$list,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
    * 所在区域搜索
    * @param    string  token
    * @param    string  prov
    * @param    string  city
    * @param    string  area
    * @param    int page
    * @param    int pagesize
    * @return   array  data
    **/
    public function region(Request $req){
        $this->useValidator($req,[
            'prov'=>[0,1,101,210],
            'city'=>[0,3,101,210],
            'area'=>[0,3,101,210],
            'page'=>[0,1,2,102],
            'pagesize'=>[0,1,102]
        ]);
        $user = User::when($req->prov,function($query)use($req){
            $query->where('prov',$req->prov);
        })->when($req->city,function($query)use($req){
            $query->where('city',$req->city);
        })->when($req->area,function($query)use($req){
            $query->where('area',$req->area);
        });
        $count = $user->count();
        $list = $user->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->orderBy('created_at','desc')
            ->get(['id as user_id','created_at','name','mobile','prov','city','area','birthday','status']);
        if($list->isEmpty()){
            return $this->returnJson(3013, '无数据', '');
        }
        return $this->returnJson(0, '成功', ['data'=>$list,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
    * 会员详情
    * @param    string  token
    * @param    int user_id
    * @return   array  data
    **/
    public function detail(Request $req){
        $this->useValidator($req,[
            'user_id'=>[0,1,102,202]
        ]);
        $data = User::select(
            'id as user_id',
            'mobile',
            'name',
            'email',
            'openid',
            'status',
            'birthday',
            'prov',
            'city',
            'area',
            'sex',
            'last_login_ip',
            'avatar',
            'desc',
            'created_at',
            'updated_at',
            'home_prov',
            'home_city',
            'home_area'
        )->find($req->user_id);
        if(!$data){
            return $this->returnJson(3006, '无此用户', '');
        }
        return $this->returnJson(0,'成功',$data);
    }

    /**
    * 冻结
    * @param    string  token
    * @param    int user_id
    * @return   boolean
    **/
    public function freeze(Request $req){
        $this->useValidator($req,[
            'user_id'=>[0,1,102,202]
        ]);
        $data = User::select('id','status')->find($req->user_id);
        if(!$data){
            return $this->returnJson(3006, '无此用户', '');
        }
        if ($data->status == 0) {
            return $this->returnJson(3014, '帐号已被冻结', '');
        }
        $data->status = 0;
        return $data->save() ? $this->returnJson(0, '成功', ['user_id'=>$data->id,'status'=>$data->status]) : $this->returnJson(3011, '失败,冻结用户失败', '');
    }

    /**
    * 解冻
    * @param    string  token
    * @param    int user_id
    * @return   boolean
    **/
    public function unfreeze(Request $req){
        $this->useValidator($req,[
            'user_id'=>[0,1,102,202]
        ]);
        $data = User::select('id','status')->find($req->user_id);
        if(!$data){
            return $this->returnJson(3006, '无此用户', '');
        }
        if ($data->status == 1) {
            return $this->returnJson(3015, '帐号未被冻结', '');
        }
        $data->status = 1;
        return $data->save() ? $this->returnJson(0, '成功', ['user_id'=>$data->id,'status'=>$data->status]) : $this->returnJson(3011, '失败,解冻用户失败', '');
    }

    /**
    * 导出
    * @param    string  token
    * @param    array  user_id
    **/
    public function export(Request $req){
        User::whereIn('id',$req->user_id)->get();
    }
}
