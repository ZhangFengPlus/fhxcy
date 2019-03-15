<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use App\Models\Auth_group;
use App\Models\Auth_rule;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //判断是否有TOKEN,没有则直接返回
        if (!$request->has('token')) {
            return response()->json(['error_code' => 1001,'error_msg' => '缺少管理员失败参数token' ,'data'=>'']);
        }
        $admin = (new Admin)->getFirst(['token'=>$request->token]);
        //判断是否有此用户,没有则直接返回
        if (!$admin) {
            return response()->json(['error_code' => 1002,'error_msg' => '帐号密码错误,请重新登录' ,'data'=>'']);
        }
        //判断此用户是否被冻结,冻结则直接返回
        if ($admin->status != 1) {
            return response()->json(['error_code' => 1003,'error_msg' => '管理员被冻结,请联系超级管理员' ,'data'=>'']);
        }
        $request->admin = $admin;
        //判断此用户是否是超级管理员
        $rule = Auth_rule::where('rule', $request->path())->where('status', 1)->first(['id','desc','title']);
        if ($admin->id != 1) {
            //判断此用户是否有权限操作此方法,没有则直接返回
            if (!$rule_id) {
                return response()->json(['error_code' => 1011,'error_msg' => '无此操作,请联系开发者' ,'data'=>'']);
            }
            $group = Auth_group::where('id', $admin->group_id)->first(['rules','lower']);
            if (!$group) {
                return response()->json(['error_code' => 2007,'error_msg' => '无此权限组' ,'data'=>'']);
            }
            if (!in_array($rule->id, explode(',', $group->rules))) {
                return response()->json(['error_code' => 1012,'error_msg' => '用户无此权限' ,'data'=>'']);
            }
            //添加员
            $request->admin->lower = $group->lower;
        } else {
            $request->admin->lower = 0;
        }
        if($rule){
            $_POST['rule'] = $rule->desc ?? $rule->title;
        }else{
            $_POST['rule'] = '暂无';
        }
        return $next($request);
    }
}
