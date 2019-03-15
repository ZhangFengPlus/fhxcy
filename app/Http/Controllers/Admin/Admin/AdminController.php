<?php

namespace App\Http\Controllers\Admin\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Events\AdminLogin;
use Illuminate\Support\Facades\Storage;
use App\Models\Auth_group as Auth_groupAlias;
use App\Models\Auth_rule as Auth_ruleAlias;

class AdminController extends Controller
{
    //登录
    public function Login(Request $req){
        $this->useValidator($req,[
            'mobile'=>[1,101,201],
            'password'=>[1,101,/*300*/]//密码正则暂时去掉
        ]);
        $admin = Admin::where('mobile',$req->mobile)
            ->orWhere('name',$req->mobile)
            ->orWhere('email',$req->mobile)
            ->where('password',md5(md5($req->password).env('APP_ATTACH')))
            ->first();
        if(!$admin){
            return response()->json(['error_code' => 1007,'error_msg' => '无此管理员' ,'data'=>'']);
        }
        if($admin->status != 1){
            return response()->json(['error_code' => 1003,'error_msg' => '管理员被冻结,请联系超级管理员' ,'data'=>'']);
        }
        event(new AdminLogin($admin,$req->getClientIp()));
        $nav = '';
        if($admin->id != 1){
            //管理员权限级别表
            $nav = Auth_ruleAlias::whereIn('id',explode(",", Auth_groupAlias::where('id',$admin->group_id)->value('rules')))->where('pid',0)->where('type',2)
                ->with(['getChildRules'=>function($query){
                    $query->where('type',3)->select('id','pid','title','path');
                }])
                ->select('id','pid','title','path')->get();
        }else{
            $nav = Auth_ruleAlias::where('pid',0)->where('type',2)
                ->with(['getChildRules'=>function($query){
                    $query->where('type',3)->select('id','pid','title','path');
                }])
                ->select('id','pid','title','path')->get();
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['token'=>$admin->token,'auth_nav'=>$nav,'name'=>$admin->name,'business_id'=>$admin->business_id]]);
    }

    //注册
    public function register(Request $req){
        $this->useValidator($req,[
            'name'=>[1,101,201],
            'password'=>[1,101,/*300*/],//密码正则暂时去掉
            'email'=>[1,108],
            'mobile'=>[1,301],
            'group_id'=>[1,102,202],
            'business_id'=>[3,102,202],
            'status'=>[0,1,100]
        ]);
        $old_admin = Admin::where(function($query) use ($req){
            $query->where('name',$req->name)
                ->orWhere('email',$req->email)
                ->orWhere('mobile',$req->mobile);
        })->value('id');
        if($old_admin){
            return response()->json(['error_code' => 1004,'error_msg' => '管理员用户名或邮箱或电话已经存在' ,'data'=>'']);
        }
        $admin = new Admin;
        if($req->business_id){
            if($req->admin->business_id != 0 && $req->business_id != $admin->business_id ){
                return $this->returnJson(1018,'无法创建其他商家管理员','');
            }
            $admin->business_id = $req->business_id;
        }
        $admin->status = $req->status;
        $admin->name = $req->name;
        $admin->password = md5(md5($req->password).env('APP_ATTACH'));
        $admin->email = $req->email;
        $admin->mobile = $req->mobile;
        $admin->group_id = $req->group_id;
        if(!$admin->save()){
            return response()->json(['error_code' => 1005,'error_msg' => '管理员存储失败' ,'data'=>'']);
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['admin_id'=>$admin->id]]);
    }

    //冻结
    public function freeze(Request $req){
        $this->useValidator($req,[
            'admin_id'=>[1,102,202]
        ]);
        $admin = Admin::select('id','business_id','status')->find($req->admin_id);
        if(!$admin){
            return response()->json(['error_code' => 1007,'error_msg' => '无此管理员' ,'data'=>'']);
        }
        if($req->admin_id == 1){
            return response()->json(['error_code' => 1008,'error_msg' => '超级管理员无法冻结' ,'data'=>'']);
        }
        if($req->admin->business_id != 0 && $req->admin->business_id != $admin->business_id){
            return $this->returnJson(1017,'无法冻结其他商家的管理员','');
        }
        if($admin->status == 0){
            return response()->json(['error_code' => 1014,'error_msg' => '管理员已被冻结' ,'data'=>'']);
        }
        $admin->status = 0;
        if(!$admin->save()){
            return response()->json(['error_code' => 1005,'error_msg' => '管理员存储失败' ,'data'=>'']);
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['admin_id'=>$admin->id,'status'=>$admin->status]]);
    }

    //解冻
    public function unfreeze(Request $req){
        $this->useValidator($req,[
            'admin_id'=>[1,102,202]
        ]);
        $admin = Admin::select('id','business_id','status')->find($req->admin_id);
        if(!$admin){
            return response()->json(['error_code' => 1007,'error_msg' => '无此管理员' ,'data'=>'']);
        }
        if($req->admin_id == 1){
            return response()->json(['error_code' => 1013,'error_msg' => '超级管理员无需解冻' ,'data'=>'']);
        }
        if($req->admin->business_id != 0 && $req->admin->business_id != $admin->business_id){
            return $this->returnJson(1017,'无法解冻其他商家的管理员','');
        }
        if($admin->status == 1){
            return response()->json(['error_code' => 1015,'error_msg' => '管理员未被冻结' ,'data'=>'']);
        }
        $admin->status = 1;
        if(!$admin->save()){
            return response()->json(['error_code' => 1005,'error_msg' => '管理员存储失败' ,'data'=>'']);
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['admin_id'=>$admin->id,'status'=>$admin->status]]);
    }

    //更新操作(除超级管理员)
    public function update(Request $req){
        $this->useValidator($req,[
            'admin_id'=>[1,102,202],
            'name'=>[1,101,201],
            'password'=>[3,101,/*300*/],//密码正则暂时去掉
            'email'=>[1,108],
            'mobile'=>[1,301],
            'group_id'=>[1,102,202],
            'business_id'=>[3,102,202],
            'status'=>[0,1,100]
        ]);
        $old_admin = Admin::where(function($query) use ($req){
            $query->where('name',$req->name)
                ->orwhere('email',$req->email)
                ->orWhere('mobile',$req->mobile);
        })->where('id','!=',$req->admin_id)->value('id');
        if($old_admin){
            return response()->json(['error_code' => 1004,'error_msg' => '管理员用户名或邮箱或电话已经存在' ,'data'=>'']);
        }
        $admin = (new Admin)->getFirst(['id'=>$req->admin_id]);
        if(!$admin){
            return response()->json(['error_code' => 1007,'error_msg' => '无此管理员' ,'data'=>'']);
        }
        if($req->admin->business_id != 0 && $req->admin->business_id != $admin->business_id){
            return $this->returnJson(1017,'无法修改其他商家的管理员','');
        }
        if($req->business_id){
            if($req->admin->business_id != 0 && $req->business_id != $admin->business_id ){
                return $this->returnJson(1018,'无法修改管理员所属商家','');
            }
            $admin->business_id = $req->business_id;
        }
        $admin->name = $req->name;
        if($req->password){
            $admin->password = md5(md5($req->password).env('APP_ATTACH'));
        }
        $admin->status = $req->status;
        $admin->email = $req->email;
        $admin->mobile = $req->mobile;
        $admin->group_id = $req->group_id;
        if(!$admin->save()){
            return response()->json(['error_code' => 1005,'error_msg' => '管理员存储失败' ,'data'=>'']);
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['admin_id'=>$admin->id]]);
    }

    //管理员列表
    public function list(Request $req){
        $this->useValidator($req,[
            'page'=>[0,1,102],
            'status'=>[0,1,102,298],
            'business_id'=>[3,102,202],
            'pagesize'=>[0,1,102]
        ]);
        $admin = Admin::where(function($query)use ($req){
                if($req->status != 2){
                    $query->where('status',$req->status);
                }
                if($req->admin->business_id != 0){
                    $query->where('business_id',$req->admin->business_id);
                }
                if($req->admin->business_id == 0 && $req->business_id){
                    $query->where('business_id',$req->business_id);
                }
                $query->where('id','!=',1);
            })->with(['getGroup'=>function($query){
                $query->select('id','title');
            }])->orderBy('created_at','desc')->offset(($req->page-1)*$req->pagesize)->limit($req->pagesize)
            ->get(['id','name','email','mobile','last_login_ip','status','group_id','created_at','updated_at']);
        if($admin->isEmpty()){
            return response()->json(['error_code' => 1010,'error_msg' => '成功,无数据' ,'data'=>'']);
        }
        $all = Admin::where(function($query)use ($req){
            if($req->status != 2){
                $query->where('status',$req->status);
            }
            if($req->admin->business_id != 0){
                $query->where('business_id',$req->admin->business_id);
            }
            if($req->admin->business_id == 0 && $req->business_id){
                $query->where('business_id',$req->business_id);
            }
            $query->where('id','!=',1);
        })->count();
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['data'=>$admin,'current_page'=>(int)$req->page,'total_page'=>ceil($all/$req->pagesize),'count'=>$all]]);
    }

    //管理员详情
    public function detail(Request $req){
        $this->useValidator($req,[
            'admin_id'=>[1,102,202]
        ]);
        $admin = Admin::with(['getGroup'=>function($query){
            $query->select('id','title');
        }])->select('id','name','email','mobile','last_login_ip','status','group_id','created_at','updated_at','business_id')->find($req->admin_id);
        if(!$admin){
            return response()->json(['error_code' => 1007,'error_msg' => '无此管理员' ,'data'=>'']);
        }
        if($req->admin_id == 1){
            return response()->json(['error_code' => 1009,'error_msg' => '超级管理员无法修改' ,'data'=>'']);
        }
        if($req->admin->business_id != 0 && $req->admin->business_id != $admin->business_id){
            return $this->returnJson(1017,'无法查看其他商家的管理员','');
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>$admin]);
    }

    //删除管理员
    public function del(Request $req){
        $this->useValidator($req,[
            'admin_id'=>[1,102,202]
        ]);
        $admin = (new Admin)->getFirst(['id'=>$req->admin_id]);
        if(!$admin){
            return response()->json(['error_code' => 1007,'error_msg' => '无此管理员' ,'data'=>'']);
        }
        if($req->admin->business_id != 0 && $req->admin->business_id != $admin->business_id){
            return $this->returnJson(1017,'无法删除其他商家的管理员','');
        }
        if($req->admin_id == 1){
            return response()->json(['error_code' => 1009,'error_msg' => '超级管理员无法删除' ,'data'=>'']);
        }
        if(!$admin->delete(['id',$req->admin_id])){
            return response()->json(['error_code' => 1005,'error_msg' => '管理员删除失败' ,'data'=>'']);
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['admin_id'=>(int)$req->admin_id]]);
    }

    //登出方法及日志
    public function logout(Request $req){
        $admin = Admin::where('token',$req->token)->first();
        if(!$admin){
            return response()->json(['error_code' => 1007,'error_msg' => '无此管理员' ,'data'=>'']);
        }
        $admin->token = '';
        if($admin->save() == false){
            return response()->json(['error_code' => 1016,'error_msg' => '登出失败,请重试' ,'data'=>'']);
        }
        $msg = '管理员   '.$admin->name.'   在时间为'.date('Y-m-d H:i:s',time()).'   ip为'.$req->getClientIp().'登出';
        Storage::prepend('admin.log',$msg);
        return response()->json(['error_code' => 0,'error_msg' => '登出成功' ,'data'=>'']);
    }
}
