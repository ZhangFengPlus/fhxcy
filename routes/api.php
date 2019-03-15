<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//资源路由
Route::group(['prefix'=>'asset'], function () {
    Route::post('store', 'Asset\UploadController@store');
});

Route::prefix('user')->group(function () {
    //验证手机号是否可用
    Route::post('checkMobile', 'Api\User\UserController@checkMobile');
    //注册
    Route::post('register', 'Api\User\UserController@register');
    //登录
    Route::post('login', 'Api\User\UserController@login');
    //登录
    Route::post('wechat', 'Api\User\UserController@wechat');
});

Route::middleware('checkuser')->group(function () {
    Route::prefix('user')->group(function () {
        //设置密码
        Route::post('setPassword', 'Api\User\UserController@setPassword');
        //设置基本资料
        Route::post('setBasic', 'Api\User\UserController@setBasic');
        //个人中心
        Route::post('detail', 'Api\User\UserController@detail');
        //更换头像
        Route::post('avatar', 'Api\User\UserController@avatar');
        //更换昵称
        Route::post('name', 'Api\User\UserController@name');
        //更换所在区域
        Route::post('location', 'Api\User\UserController@location');
        //更换故乡
        Route::post('hometown', 'Api\User\UserController@hometown');
        //更换性别
        Route::post('sex', 'Api\User\UserController@sex');
        //更换生日
        Route::post('birthday', 'Api\User\UserController@birthday');
        //更换个人简介
        Route::post('desc', 'Api\User\UserController@desc');
        //更换手机
        Route::post('mobile', 'Api\User\UserController@mobile');
        //发送验证码
        Route::post('code', 'Api\User\UserController@code');
        //绑定手机号
        Route::post('binding', 'Api\User\UserController@binding');
        //查看是否绑定手机号
        Route::post('binding_phone', 'Api\User\UserController@binding_phone');
    });

    Route::prefix('check')->group(function () {
        Route::post('check', 'Api\Check\CheckController@check');
        Route::post('answer', 'Api\Check\CheckController@answer');
        Route::post('result', 'Api\Check\CheckController@result');
        Route::post('ranking_list', 'Api\Check\CheckController@ranking_list');
        Route::post('personal', 'Api\Check\CheckController@personal');
        Route::post('exchange', 'Api\Check\CheckController@exchange');
        Route::post('gift_box', 'Api\Check\CheckController@gift_box');
        Route::post('postcard', 'Api\Check\CheckController@postcard');
    });


});
