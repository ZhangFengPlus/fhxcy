<?php

namespace App\Listeners;

use App\Events\AdminLogin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Admin;
use App\Models\Log;
use Illuminate\Support\Facades\Storage;

class AdminloginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdminLogin  $event
     * @return void
     */
    public function handle(AdminLogin $event)
    {
        // $token = encrypt($event->id.' '.$event->ip.' '.time());
        $event->admin->token = encrypt($event->admin->id.' '.$event->ip.' '.time());
        $event->admin->last_login_ip = $event->ip;
        $event->admin->save();
        //登录日志
        $mag = '管理员   '.$event->admin->name.'   在时间为'.date('Y-m-d H:i:s',time()).'   ip为'.$event->ip.'登入';
        Storage::prepend('admin.log',$mag);
        $log = new Log;
        $log->name = $event->admin->name;
        $log->ip = $event->ip;
        $log->content = $mag;
        $log->save();
        return $event->admin;
    }

    /**
     * 处理任务失败
     *
     * @param  AdminLogin  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(AdminLogin $event, $exception)
    {
        //
    }
}
