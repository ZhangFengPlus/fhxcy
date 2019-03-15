<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class CheckUser
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
        if(!$request->has('token')){
            return response()->json([
                'error_code' => 3005,
                'error_msg' =>'失败,缺少用户token',
                'data'=>''
            ]);
        }
        $user = User::where('token',$request->token)->first();
        //判断是否有此用户,没有则直接返回
        if(!$user){
            return response()->json([
                'error_code' => 3006,
                'error_msg' =>'无此用户,请重新登录',
                'data'=>''
            ]);
        }
        //判断此用户是否被冻结,冻结则直接返回
        if($user->status != 1){
            return response()->json([
                'error_code' => 3007,
                'error_msg' => '用户被冻结,请联系管理员' ,
                'data'=>''
            ]);
        }
        if(strtotime($user->expired_time) <= time()){
            return response()->json([
                'error_code' => 3016,
                'error_msg' => 'token已失效,请重新登录' ,
                'data'=>''
            ]);
        }
        $request->user = $user;
        return $next($request);
    }
}
