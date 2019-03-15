<?php

namespace App\Http\Controllers\Api\User;

use EasyWeChat\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Code;
use App\Models\UserData;
use App\Events\UserLogin;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    /**
     * @param Request $req
     * 微信小程序授权登录
     */
    public function wechat(Request $request)
    {

        $this->useValidator($request,[
            'iv'=>[1,101],
            'code'=>[1,101],
            'encryptedData'=>[1,101],
        ]);

        $config = [
            'app_id' => '',
            'secret' => '',
            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' =>'',
            ],
        ];

        $app = Factory::miniProgram($config);

        $session =  $app->auth->session($request->code);


        $decryptedData = $app->encryptor->decryptData((string)$session['session_key'] , (string)$request->iv, (string)$request->encryptedData);

        $user = new User();

        $id = User::where('openid',$session['openid'])->value('id');

        if($id)   //如果此账号授权过
        {
            $ini['avatar'] = $decryptedData['avatarUrl'];
            $ini['name'] = $decryptedData['nickName'];
            $ini['token']  =  Crypt::encrypt(date('YmdHis').rand(1, 9999999));
            if( $user->editor($ini,$id) == false)
            {
                return response()->json(['error_code'=>1001 ,'error_msg'=>'老用户登录失败', 'data'=>'']);
            }
            return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>$ini['token'] ,'name'=> $decryptedData['nickName']]);
        }
        //没有授权过  新用户
        $ini['name'] = $decryptedData['nickName'];
        $ini['avatar'] = $decryptedData['avatarUrl'];
        $ini['openid'] = $session['openid'];
        $ini['token']  =  Crypt::encrypt(date('YmdHis').rand(1, 9999999));
        if($user->adds($ini) == false)
        {
            return response()->json(['error_code'=>1444 ,'error_msg'=>'创建用户失败', 'data'=>'']);
        }

        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>$ini['token'] , 'name'=>$decryptedData['nickName']]);
    }


    /**
     * @param Request $req
     * 查看是否绑定手机号
     */
    public function binding_phone(Request $req)
    {
        $mobile = User::where('id',$req->user->id)->value('mobile');
        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>$mobile]);

    }


    /**
     * @param Request $req
     *  绑定手机号
     */
    public function binding(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'code'=>[0,1,102]
        ]);

        if (User::where('mobile',$req->mobile)->value('id')){
            return $this->returnJson(3001, '手机号已经被注册');
        }

        $code = Code::where('mobile', $req->mobile)
            ->where('code', $req->code)
            ->where('type', 1)
            ->where('overdued_at', '>=', date('Y-m-d H:i:s', time()))
            ->where('status', 1)
            ->first(['id','status']);
        if (!$code) {
            return $this->returnJson(3002, '短信验证码不正确或已经过期');
        }

        $code->status = 0;
        $req->user->mobile = $req->mobile;

        return DB::transaction(function () use ($req,$code) {
            return $req->user->save() && $code->save() ? $this->returnJson(0,'成功') : $this->returnJson(3011,'失败,绑定手机号失败');
        });
    }


    /**
     * @param Request $req
     * 发送验证码
     */
    public function code(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'type'=>[0,1,102],  //1为绑定 2为修改
        ]);

        $rand = rand(1000,9999);

        $text="【凤凰乡村游】您的验证码是#$rand#。如非本人操作，请忽略本短信";

        $messige =  json_decode($this->send_sms('',$text,$req->mobile));

        if($messige->code != 0)
        {
            return $this->returnJson(1201, '短信发送失败');
        }

        $ini['mobile'] = $req->mobile;
        $ini['code'] = $rand;
        $ini['overdued_at'] = date('Y-m-d H:i:s',strtotime('+5 minute'));
        $ini['type'] = $req->type;

        $code = new Code();

        if($code->adds($ini) == false)
        {
            return $this->returnJson(1202, '失败');
        }
        return $this->returnJson(0, '成功');
    }

    /**
    * 用户根据手机号注册
    * @param    int mobile
    * @param    string password(密码)
    * @param    int code(验证码,4-6位)
    * @return   boolean
    **/
    public function register(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'code'=>[0,1,102]
        ]);
        if (User::where('mobile', $req->mobile)->value('id')) {
            return $this->returnJson(3001, '手机号已经被注册', '');
        }
        $code = Code::where('mobile', $req->mobile)
            ->where('code', $req->code)
            ->where('type', 1)
            ->where('overdued_at', '>=', date('Y-m-d H:i:s', time()))
            ->where('status', 1)
            ->first(['id','status']);
        if (!$code) {
            return $this->returnJson(3002, '短信验证码不正确或已经过期', '');
        }
        $user = new User;
        $user->mobile = $req->mobile;
        $code->status = 0;
        try {
			return DB::transaction(function () use($user,$code) {
				if(!$user->save() || !$code->save()){
					throw new \Exception ('创建用户失败');
				}
                $data = new UserData;
                $data->user_id = $user->id;
                if(!$data->save()){
                    throw new \Exception ('创建用户失败');
                }
                event(new UserLogin($user, $req->getClientIp()));
				return $this->returnJson(0, '成功', ['token'=>$user->token]);
			});
		} catch (\Exception $e) {
			return $this->returnJson(3003,$e->getMessage());
		}
    }

    /**
    * 检测手机是否被注册
    * @param    int mobile
    * @return   boolean
    **/
    public function checkMobile(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301]
        ]);
        return User::where('mobile', $req->mobile)->value('id') ? $this->returnJson(3001, '手机号已经被注册', '') : $this->returnJson(0, '手机号可用', '');
    }

    /**
    * 用户登录
    * @param    int mobile
    * @param    string password(密码)
    **/
    public function login(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'type'=>[0,1,102,297]
        ]);
        if($req->type == 1){
            $this->useValidator($req, [
                'password'=>[0,1,101,295,201],
            ]);
            $user = User::where('mobile', $req->mobile)->where('password', md5(md5($req->password).env('APP_ATTACH')))->first(['id','status','token','mobile']);
        }else{
            $this->useValidator($req, [
                'code'=>[0,1,102],
            ]);
            $code = Code::where('mobile', $req->mobile)
                ->where('code', $req->code)
                ->where('type', 2)
                ->where('overdued_at', '>=', date('Y-m-d H:i:s', time()))
                ->where('status', 1)
                ->first(['id','status']);
            if (!$code) {
                return $this->returnJson(3002, '短信验证码不正确或已经过期', '');
            }
            $code->status = 0;
            $user = User::where('mobile', $req->mobile)->first(['id','status','token','mobile']);
            if(!$code->save()){
                return $this->returnJson(3009, '验证码修改失败', '');
            }
        }
        if(!$user){
            return $this->returnJson(3006, '无此用户,请重新登录', '');
        }
        if ($user->status == 0) {
            return $this->returnJson(3004, '帐号被冻结', '');
        }
        event(new UserLogin($user, $req->getClientIp()));
        return $this->returnJson(0, '成功', ['token'=>$user->token]);
    }

    /**
    * 设置密码
    * @param    string  token
    * @param    string  password
    * @return   boolean
    **/
    public function setPassword(Request $req){
        $this->useValidator($req, [
            'password'=>[0,1,101,295,201]
        ]);
        $req->userpassword = md5(md5($req->password).env('APP_ATTACH'));
        return $req->user->save() ? $this->returnJson(0, '成功', '') : $this->returnJson(3010, '失败,设置密码保存失败', '');
    }

    /**
    * 设置基本资料
    * @param    string  token
    * @param    string  avatar
    * @param    string  name
    * @param    string  prov
    * @param    string  city
    * @param    string  area
    * @param    int sex
    * @param    date    birthday
    * @return   boolean
    **/
    public function setBasic(Request $req){
        $this->useValidator($req, [
            'avatar'=>[3,101,207],
            'name'=>[3,101,210],
            'prov'=>[3,101,210],
            'city'=>[3,101,210],
            'area'=>[3,101,210],
            'sex'=>[3,102,296],
            'birthday'=>[3,107],
        ]);
        $req->user->avatar = $req->avatar ?? '';
        $req->user->name = $req->name ?? '';
        $req->user->prov = $req->prov ?? '';
        $req->user->city = $req->city ?? '';
        $req->user->area = $req->area ?? '';
        $req->user->sex = $req->sex ?? '';
        $req->user->birthday = $req->birthday ?? '';
        return $req->user->save() ? $this->returnJson(0, '成功') : $this->returnJson(3011, '失败,设置基础信息保存失败');
    }

    /**
    * 个人中心
    * @param    string  token
    * @return   array  data
    **/
    public function detail(Request $req){
        return $this->returnJson(0, '成功', collect($req->user)->except('token','password','openid','access_token','last_login_ip'));
    }

    /**
    * 更换头像
    * @param    string  token
    * @param    string  avatar
    * @return   boolean
    **/
    public function avatar(Request $req){
        $this->useValidator($req, [
            'avatar'=>[0,1,101,207],
        ]);
        $req->user->avatar = $req->avatar;
        return $req->user->save() ? $this->returnJson(0, '成功', '') : $this->returnJson(3011, '失败,设置头像保存失败', '');
    }

    /**
    * 更换昵称
    * @param    string  token
    * @param    string  name
    * @return   boolean
    **/
    public function name(Request $req){
        $this->useValidator($req, [
            'name'=>[0,1,101,207],
        ]);
        if(User::where('name',$req->name)->value('id')){
            return $this->returnJson(3012, '昵称重复');
        }
        $req->user->name = $req->name;
        return $req->user->save() ? $this->returnJson(0, '成功') : $this->returnJson(3011, '失败,设置昵称保存失败');
    }

    /**
    * 更换所在地区
    * @param    string  token
    * @param    string  prov
    * @param    string  city
    * @param    string  area
    * @return   boolean
    **/
    public function location(Request $req){
        $this->useValidator($req, [
            'prov'=>[0,1,101,210],
            'city'=>[0,1,101,210],
            'area'=>[0,3,101,210]
        ]);
        $req->user->prov = $req->prov;
        $req->user->city = $req->city;
        $req->user->area = $req->area;
        return $req->user->save() ? $this->returnJson(0, '成功', '') : $this->returnJson(3011, '失败,设置所在地区保存失败', '');
    }

    /**
    * 更换故乡
    * @param    string  token
    * @param    string  home_prov
    * @param    string  home_city
    * @param    string  home_area
    * @return   boolean
    **/
    public function hometown(Request $req){
        $this->useValidator($req, [
            'home_prov'=>[0,1,101,210],
            'home_city'=>[0,1,101,210],
            'home_area'=>[0,3,101,210]
        ]);
        $req->user->home_prov = $req->home_prov;
        $req->user->home_city = $req->home_city;
        $req->user->home_area = $req->home_area;
        return $req->user->save() ? $this->returnJson(0, '成功', '') : $this->returnJson(3011, '失败,设置故乡保存失败', '');
    }

    /**
    * 更换性别
    * @param    string  token
    * @param    int sex
    * @return   boolean
    **/
    public function sex(Request $req){
        $this->useValidator($req, [
            'sex'=>[0,1,102,296]
        ]);
        $req->user->sex = $req->sex;
        return $req->user->save() ? $this->returnJson(0, '成功', '') : $this->returnJson(3011, '失败,设置性别保存失败', '');
    }

    /**
    * 更换生日
    * @param    string  token
    * @param    date    birthday
    * @return   boolean
    **/
    public function birthday(Request $req){
        $this->useValidator($req, [
            'birthday'=>[0,1,107]
        ]);
        $req->user->birthday = $req->birthday;
        return $req->user->save() ? $this->returnJson(0, '成功', '') : $this->returnJson(3011, '失败,设置生日保存失败', '');
    }

    /**
    * 更换个人简介
    * @param    string  token
    * @param    string  desc
    * @return   boolean
    **/
    public function desc(Request $req){
        $this->useValidator($req, [
            'desc'=>[0,1,101,203]
        ]);
        $req->user->desc = $req->desc;
        return $req->user->save() ? $this->returnJson(0, '成功', '') : $this->returnJson(3011, '失败,设置个人简介保存失败', '');
    }

    /**
    * 更换手机号
    * @param    int mobile
    * @param    int code
    * @return   boolean
    **/
    public function mobile(Request $req){
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'code'=>[0,1,102],
            'primary_mobile'=>[0,1,102],
        ]);
        if (User::where('mobile',$req->mobile)->value('id')){
            return $this->returnJson(3001, '手机号已经被注册');
        }
        if($req->primary_mobile != $req->user->mobile)
        {
            return $this->returnJson(2585, '原手机号不正确');
        }

        $code = Code::where('mobile', $req->primary_mobile)
            ->where('code', $req->code)
            ->where('type', 2)
            ->where('overdued_at', '>=', date('Y-m-d H:i:s', time()))
            ->where('status', 1)
            ->first(['id','status']);
        if (!$code) {
            return $this->returnJson(3002, '短信验证码不正确或已经过期');
        }
        $req->user->mobile = $req->mobile;
        $code->status = 0;
        return DB::transaction(function () use ($req,$code) {
            return $req->user->save() && $code->save() ? $this->returnJson(0,'成功') : $this->returnJson(3011,'失败,修改手机号失败');
        });
    }

    /**
     * 智能匹配模版接口发短信
     * apikey 为云片分配的apikey
     * text 为短信内容
     * mobile 为接受短信的手机号
     */
    private function send_sms($apikey, $text, $mobile){
        $url="http://yunpian.com/v1/sms/send.json";
        $encoded_text = urlencode("$text");
        $mobile = urlencode("$mobile");
        $post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
        return    $this->sock_post($url, $post_string);
    }

    /**
     * url 为服务的url地址
     * query 为请求串
     */
    private function sock_post($url,$query){
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;
    }

}
