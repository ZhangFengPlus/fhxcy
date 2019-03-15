<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Code;
use Ender\YunPianSms\SMS\YunPianSms;

class CommonController extends Controller
{
    public function getCode(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[1,103,301],
            'type'=>[1,102,296]
        ]);
        $old = Code::where('mobile', $req->mobile)->where('type', $req->type)->where('status', 1)->where('overdued_at', '>=', date('Y-m-d H:i:s', time()))->value('code');
        if ($old) {
            $yunpianSms=new YunPianSms('bf1a017f5e23510088b60dafc633354d');
            $response=$yunpianSms->sendMsg($req->mobile, '【新墨科技】您的验证码是'.$old.'。如非本人操作，请忽略本短信');
            return $this->returnJson(0, '成功', ['code'=>$old]);
        }
        $rand = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_RIGHT);
        $code = new Code();
        $code->mobile = $req->mobile;
        $code->type = $req->type;
        $code->code = $rand;
        $code->overdued_at = date('Y-m-d H:i:s', time()+9000);
        if(!$code->save()){
            return $this->returnJson(3009, '验证码存储失败');
        }
        $yunpianSms=new YunPianSms('bf1a017f5e23510088b60dafc633354d');
        $response=$yunpianSms->sendMsg($req->mobile, '【新墨科技】您的验证码是'.$rand.'。如非本人操作，请忽略本短信');
        if ($response['status'] != 200 && $response['data']['code'] != 0) {
            return $this->returnJson(3008, '验证码发送失败');
        }
        return $this->returnJson(0, '成功', ['code'=>$rand]);
    }
}
