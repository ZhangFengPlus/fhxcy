<?php

namespace App\Http\Controllers\Admin\Log;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log as LogAlias;

class LogController extends Controller
{
    /**
    * 日志列表
    * @param    int page
    * @param    int pagesize
    **/
    public function list(Request $req){
        $this->useValidator($req,[
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $data = LogAlias::offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->orderBy('created_at','desc')
            ->get();
        if($data->isEmpty()){
            return $this->returnJson(3601,'无数据','');
        }
        $count = LogAlias::count();
        return $this->returnJson(0,'成功',['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }
}
