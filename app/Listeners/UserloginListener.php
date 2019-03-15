<?php

namespace App\Listeners;

use App\Events\UserLogin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Facades\Storage;

class UserloginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  UserLogin  $event
     * @return void
     */
    public function handle(UserLogin $event)
    {
        // $token = encrypt($event->id.' '.$event->ip.' '.time());
        if(strtotime($event->user->expired_time) <= time()){
            $event->user->token = encrypt($event->user->id.' '.$event->ip.' '.time());
            $event->user->expired_time = date('Y-m-d H:i:s',strtotime("+2 week"));
        }
        $event->user->last_login_ip = $event->ip;
        $event->user->save();
        $msg = '用户   '.$event->user->mobile.'   在时间为'.date('Y-m-d H:i:s',time()).'   ip为'.$event->user->last_login_ip.'登入';
        Storage::prepend('user.log',$msg);
        $log = new Log;
        $log->name = $event->user->mobile;
        $log->ip = $event->ip;
        $log->content = $msg;
        $log->save();
        return $event->user;
    }
}
