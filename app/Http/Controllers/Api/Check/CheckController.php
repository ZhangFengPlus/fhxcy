<?php
/**
 * Created by PhpStorm.
 * User: zhang_feng
 * Date: 2018/10/14
 * Time: 18:33
 */
namespace App\Http\Controllers\Api\Check;


use App\Models\Check\CheckpointUser;
use App\Models\Check\GiftBox;
use App\Models\Check\Postcard;
use App\Models\Check\QuestionBank;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Fengtest\Fengfengtest\Tools;


class CheckController extends Controller
{
    /**
     * @param Request $req
     * A20 关卡页
     */
    public function check(Request $req)
    {
        $check = CheckpointUser::where('user_id',$req->user->id)->get(['stars','checkponint_id']);
        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>$check]);
    }

    /**
     * @param Request $req
     * A21 答题
     */
    public function answer(Request $req)
    {
        $this->useValidator($req, [
            'checkponint_id'=>[0,1,102],  //区域id
        ]);
        //随机抽取 4道区域问题
       $region  =  QuestionBank::where([['checkponint_id',$req->checkponint_id],['level',0]])->get(['id'])->toarray();
       $textArray = array_column($region,'id');
        shuffle($textArray);
        // 1道公共问题
        $public = QuestionBank::where([['level',0],['checkponint_id',0]])->get(['id'])->toarray();
        $textArray1 = array_column($public,'id');
        shuffle($textArray1);
        $list = QuestionBank::whereIn('id',array_merge(array_slice($textArray,0,4),array_slice($textArray1,0,1)))
            ->select('id','checkponint_id','title')
            ->with(['questionbank'=>function($i){
                    $i->select('id','level','title','status');
            }])->get();
        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>$list]);
    }

    /**
     * @param Request $req
     * A22 答题结果
     */
    public function result(Request $req)
    {
        $this->useValidator($req, [
            'checkponint_id'=>[0,1,102],  //区域id
            'answer'=>[1,104],  //选择的答案id
            'postcard'=>[3,101],  //明信片
            'postcard_content'=>[3,101],  //明信片内容
        ]);
        $arr = 0;
        foreach ($req->answer as $v)
        {
            if(QuestionBank::where('id',$v)->value('status') == 1)
            {
                $arr+=1;
            }
        }
        //答题分数 自增到 用户表 分数字段上  每月清0 一次
        User::where('id',$req->user->id)->increment('fraction',$arr);

        $check_user_id = CheckpointUser::where([['checkponint_id',$req->checkponint_id],['user_id',$req->user->id]])
            ->select('id','stars')->first();


        if($arr >= 3)   //答题通关 可以加一颗星
        {
            //通关了 给他加一张明信片
            if(!$req->postcard)
            {
                return response()->json(['error_code'=>1548 ,'error_msg'=>'明信片错误', 'data'=>'']);
            }
            $oio['url'] = $req->postcard;
            $oio['user_id'] = $req->user->id;
            $oio['content'] = $req->postcard_content;
            $postcard = new Postcard();
            if(!$postcard->adds($oio))
            {
                return response()->json(['error_code'=>6589 ,'error_msg'=>'加入明信片错误,稍后再试', 'data'=>'']);
            }

            if($check_user_id)  //有这关的记录
            {
                if($check_user_id->stars < 3) //符合加星星的条件
                {
                    CheckpointUser::where('id',$check_user_id->id)->increment('stars');  //星星自增1
                }
                return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>'']);
            }else{   //没有这关的记录 新加一条记录  默认有一颗星星

                $ini['user_id'] = $req->user->id;
                $ini['checkponint_id'] = $req->checkponint_id;

                $CheckpointUser = new CheckpointUser();

                if($CheckpointUser->adds($ini) == false)
                {
                    return response()->json(['error_code'=>1048 ,'error_msg'=>'系统出错,稍后再试', 'data'=>'']);
                }
                return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>'']);
            }
        }
        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>'']);
    }
    /**
     * @param Request $req
     * A30 排行榜
     */
    public function ranking_list(Request $req)
    {
        $list = User::select('name','avatar','fraction')
            ->orderBy('fraction','desc')
            ->limit(20)
            ->get();
        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>$list]);
    }


    /**
     * @param Request $req
     * A40个人中心
     */
    public function personal(Request $req)
    {
        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>['name'=>$req->user->name,
            'avatar'=>$req->user->avatar,'fraction'=>$req->user->fraction]]);
    }

    /**
     * @param Request $req
     * A40 兑换
     */
    public function exchange(Request $req)
    {
        $this->useValidator($req, [
            'integral'=>[0,1,102],  //兑换积分
            'name'=>[1,101],  //兑换物品名称
            'picture'=>[1,101],  //兑换物品图片
        ]);
        if($req->user->mobile == '')
        {
            return response()->json(['error_code'=>1478 ,'error_msg'=>'请先绑定手机号', 'data'=>'']);
        }
        if($req->user->fraction < $req->integral)
        {
            return response()->json(['error_code'=>5454 ,'error_msg'=>'积分不足', 'data'=>'']);
        }
            try {
                DB::transaction(function () use($req) {
                    $gift = new GiftBox();
                    $ini['name'] = $req->name;
                    $ini['picture'] = $req->picture;
                    $ini['user_id'] = $req->user->id;
                    $ini['mobile'] = $req->user->mobile;  //兑换预留手机号
                    $ini['integral'] = $req->integral;  //兑换积分
                    if(!$gift->adds($ini))
                    {
                        throw new \Exception('失败', 5009);
                    }
                    $user = new User();
                    $iop['fraction'] = $req->user->fraction - $req->integral;
                    if(!$user->editor($iop,$req->user->id))
                    {
                        throw new \Exception('失败', 5110);
                    }
                });
            } catch (\Exception $e) {
                return $this->returnJson($e->getCode(),$e->getMessage());
            }
        return $this->returnJson(0,'兑换成功','');
    }

    /**
     * @param Request $req
     * A41 礼品箱
     */
    public function gift_box(Request $req)
    {
        $gift = GiftBox::where('user_id',$req->user->id)->get(['name','picture']);
        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>$gift]);
    }

    /**
     * @param Request $req
     * A42 明信片
     */
    public function postcard(Request $req)
    {
       $postcard = Postcard::where('user_id',$req->user->id)->get(['url','content']);
        return response()->json(['error_code'=>0 ,'error_msg'=>'成功', 'data'=>$postcard]);
    }





}