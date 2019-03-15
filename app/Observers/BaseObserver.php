<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Auth_rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class BaseObserver
{
    public function admin_log($handler,$data,$type,$admin_type,$database,$rule,$log){
        $msg = $admin_type.'('.$handler->name.')   id('.$handler->id.')  在'.$data->updated_at.'ip:  '.$handler->last_login_ip.   $type.' 对'.$database.'(id'.$data->id.')进行了*/'.$rule.'/*操作';
        $data = collect($data);
        $data->forget('id');
        $data->forget('created_at');
        $data->forget('updated_at');
        $msg .= '    操作数据:'.implode(',',$data->keys()->toArray()).'为'.implode(',',$data->values()->toArray());
        Storage::prepend($log,$msg);
        $log = new Log;
        $log->name = $handler->name;
        $log->ip = $handler->last_login_ip;
        $log->content = $msg;
        $log->save();
        return;
    }

    public function check($data){
        if(!isset($_POST['token']) || !isset($_POST['rule'])){
            return [];
        }
        if(empty($_POST['token']) || empty($_POST['rule'])){
            return [];
        }
        return json_decode($data);
    }
}
