<?php

namespace App\Http\Controllers\Admin\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth_group;
use App\Models\Auth_rule;

class GroupController extends Controller
{
    public function add(Request $req){
        $this->useValidator($req,[
            'title'=>[1,101,201],
            'desc'=>[0,1,2,203],
            'status'=>[0,1,100],
            'rules'=>[0,1,104],
            'lower'=>[0,1,100]
        ]);
        $oid = Auth_group::where('title',$req->title)->value('id');
        if($oid){
            return response()->json(['error_code' => 2004,'error_msg' => '此权限组已经存在' ,'data'=>'']);
        }
        $group = new Auth_group;
        $group->title = $req->title;
        $group->desc = $req->desc;
        $group->rules = implode(',',$req->rules);
        $group->status = $req->status;
        $group->lower = $req->lower;
        return $group->save() ? response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['auth_group_id'=>$group->id]]) : response()->json(['error_code' => 2005,'error_msg' => '管理组存储失败' ,'data'=>'']);
    }

    public function update(Request $req){
        $this->useValidator($req,[
            'auth_group_id'=>[1,102,202],
            'title'=>[1,101,201],
            'desc'=>[0,1,2,203],
            'status'=>[1,100],
            'rules'=>[1,104],
            'lower'=>[0,1,100]
        ]);
        $old_group = Auth_group::where(function($query) use ($req){
            $query->where('title',$req->title)
                ->where('id','!=',$req->auth_group_id);
        })->value('id');
        if($old_group){
            return response()->json(['error_code' => 2004,'error_msg' => '此权限组已经存在' ,'data'=>'']);
        }
        $group = (new Auth_group)->getFirst(['id'=>$req->auth_group_id]);
        if(!$group){
            return response()->json(['error_code' => 2007,'error_msg' => '无此权限组' ,'data'=>'']);
        }
        $group->title = $req->title;
        $group->desc = $req->desc;
        $group->status = $req->status;
        $group->lower = $req->lower;
        $group->rules = implode(',',$req->rules);
        return $group->save() ? response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['auth_group_id'=>$group->id]]) : response()->json(['error_code' => 2005,'error_msg' => '管理组存储失败' ,'data'=>'']);
    }

    public function del(Request $req){
        $this->useValidator($req,[
            'auth_group_id'=>[1,102,202]
        ]);
        $group = (new Auth_group)->getFirst(['id'=>$req->auth_group_id]);
        if(!$group){
            return response()->json(['error_code' => 2007,'error_msg' => '无此权限组' ,'data'=>'']);
        }
        if(!$group->delete(['id',$req->auth_group_id])){
            return response()->json(['error_code' => 2005,'error_msg' => '管理组删除失败' ,'data'=>'']);
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['auth_group_id'=>$req->auth_group_id]]);
    }

    public function list(Request $req){
        $this->useValidator($req,[
            'page'=>[0,1,102],
            'status'=>[0,1,102,298],
            'pagesize'=>[1,102]
        ]);
        $group = Auth_group::where(function($query)use ($req){
            if($req->status != 2){
                $query->where('status',$req->status);
            }
        })->orderBy('created_at','desc')->offset(($req->page-1)*$req->pagesize)->limit($req->pagesize)->get();
        $all = Auth_group::where(function($query)use ($req){
            if($req->status != 2){
                $query->where('status',$req->status);
            }
        })->count();
        if($group->isEmpty()){
            return response()->json(['error_code' => 2010,'error_msg' => '成功,无数据' ,'data'=>'']);
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>['data'=>$group,'current_page'=>(int)$req->page,'total_page'=>(int)ceil($all/$req->pagesize),'count'=>$all]]);
    }

    public function detail(Request $req){
        $this->useValidator($req,[
            'auth_group_id'=>[1,102,202]
        ]);
        $group = (new Auth_group)->getFirst(['id'=>$req->auth_group_id]);
        if(!$group){
            return response()->json(['error_code' => 2007,'error_msg' => '无此权限组' ,'data'=>'']);
        }
        return response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>$group]);
    }

    public function freeze(Request $req){
        $this->useValidator($req,[
            'auth_group_id'=>[1,102,202],
        ]);
        $group = (new Auth_group)->getFirst(['id'=>$req->auth_group_id]);
        if(!$group){
            return $this->returnJson(2007,'无此权限组');
        }
        if($group->status == 0){
            return $this->returnJson(2011,'失败,权限组已被冻结');
        }
        $group->status = 0;
        return $group->save() ? $this->returnJson(0,'成功',['auth_group_id'=>$req->auth_group_id,'status'=>0]): $this->returnJson(2005,'权限组存储失败');
    }

    public function unfreeze(Request $req){
        $this->useValidator($req,[
            'auth_group_id'=>[1,102,202],
        ]);
        $group = (new Auth_group)->getFirst(['id'=>$req->auth_group_id]);
        if(!$group){
            return $this->returnJson(2007,'无此权限组');
        }
        if($group->status == 1){
            return $this->returnJson(2012,'失败,权限组未被冻结');
        }
        $group->status = 1;
        return $group->save() ? $this->returnJson(0,'成功',['auth_group_id'=>$req->auth_group_id,'status'=>0]): $this->returnJson(2005,'权限组存储失败');
    }

    public function getAuthRules(){
        $list = Auth_rule::where('pid',0)
            ->where('status',1)
            ->with(['getChildRules'=>function($query){
                $query->where('status',1)->select('id','pid','title','type');
            }])
            ->get(['id','title','type']);
        return $list->isEmpty() ? response()->json(['error_code' => 2013,'error_msg' => '失败,无数据' ,'data'=>'']) : response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>$list]);
    }

    public function getAllGroup(){
        $group = Auth_group::all(['id as group_id','title']);
        return $group->isEmpty() ? response()->json(['error_code' => 2010,'error_msg' => '失败,暂无管理组' ,'data'=>'']) : response()->json(['error_code' => 0,'error_msg' => '成功' ,'data'=>$group]);
    }
}
