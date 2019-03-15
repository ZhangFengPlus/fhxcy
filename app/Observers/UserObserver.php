<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\User;
use App\Observers\BaseObserver;

class UserObserver extends BaseObserver
{
    /**
     * 监听用户创建事件.
     *
     * @param  User $data
     * @return void
     */
    public function created(User $data){
        $data = $this->check($data);
        if(empty($data)){
            return;
        }
        if($handler = Admin::where('token',$_POST['token'])->first(['id','name','last_login_ip'])){
            $handler->id == 1
                ? $this->admin_log($handler,$data,'创建','超级管理员','用户',$_POST['rule'],'user.log')
                : $this->admin_log($handler,$data,'创建','管理员','用户',$_POST['rule'],'user.log');
        }elseif($handler = User::where('token',$_POST['token'])->first(['id','name','last_login_ip'])){
            $this->admin_log($handler,$data,'创建','用户','用户',$_POST['rule'],'user.log');
        }
        return;
    }

    /**
     * 监听用户更新事件.
     *
     * @param  User $data
     * @return void
     */
    public function updated(User $data){
        $data = $this->check($data);
        if(empty($data)){
            return;
        }
        if($handler = Admin::where('token',$_POST['token'])->first(['id','name','last_login_ip'])){
            $handler->id == 1
                ? $this->admin_log($handler,$data,'修改','超级管理员','用户',$_POST['rule'],'user.log')
                : $this->admin_log($handler,$data,'修改','管理员','用户',$_POST['rule'],'user.log');
        }elseif($handler = User::where('token',$_POST['token'])->first(['id','name','last_login_ip'])){
            $this->admin_log($handler,$data,'修改','用户','用户',$_POST['rule'],'user.log');
        }
        return;
    }

    /**
     * 监听用户删除事件.
     *
     * @param  User  $data
     * @return void
     */
    public function deleting(User $data){
        $data = $this->check($data);
        if(empty($data)){
            return;
        }
        if($handler = Admin::where('token',$_POST['token'])->first(['id','name','last_login_ip'])){
            $handler->id == 1
                ? $this->admin_log($handler,$data,'删除','超级管理员','用户',$_POST['rule'],'user.log')
                : $this->admin_log($handler,$data,'删除','管理员','用户',$_POST['rule'],'user.log');
        }elseif($handler = User::where('token',$_POST['token'])->first(['id','name','last_login_ip'])){
            $this->admin_log($handler,$data,'删除','用户','用户',$_POST['rule'],'user.log');
        }
        return;
    }
}
