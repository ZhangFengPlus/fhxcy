<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//测试页面
Route::prefix('test')->group(function () {
    Route::get('user', function () {
        return view('user.api');
    });
    Route::prefix('admin')->group(function () {
        Route::get('user', function () {
            return view('user.admin');
        });
        Route::get('admin', function () {
            return view('admin.admin');
        });
        Route::get('group', function () {
            return view('group.admin');
        });
        Route::get('log', function () {
            return view('log.admin');
        });
    });
});
//获取验证码
Route::post('getCode', 'Common\CommonController@getCode');
//管理员操作
Route::prefix('admin')->group(function () {
    //日志
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    //登录
    Route::post('login', 'Admin\Admin\AdminController@login');
    //登出
    Route::post('logout', 'Admin\Admin\AdminController@logout');
    //获取权限列表
    Route::post('getAuthRules', 'Admin\Admin\GroupController@getAuthRules');
    //获取管理组
    Route::post('getAllGroup', 'Admin\Admin\GroupController@getAllGroup');
    //获取分类
    Route::post('getCate', 'Admin\Category\CategoryController@getCate');
    //获取所有商家
    Route::post('getAllBusiness', 'Admin\Business\BusinessController@getAllBusiness');

    //后台中间件
    Route::group(['middleware' => ['checkadmin']], function () {
        //注册
        Route::post('register', 'Admin\Admin\AdminController@register');
        //冻结
        Route::post('freeze', 'Admin\Admin\AdminController@freeze');
        //解冻
        Route::post('unfreeze', 'Admin\Admin\AdminController@unfreeze');
        //列表
        Route::post('list', 'Admin\Admin\AdminController@list');
        //修改
        Route::post('update', 'Admin\Admin\AdminController@update');
        //删除
        Route::post('delete', 'Admin\Admin\AdminController@del');
        //详情
        Route::post('detail', 'Admin\Admin\AdminController@detail');
        //管理组
        Route::prefix('group')->group(function () {
            //添加
            Route::post('add', 'Admin\Admin\GroupController@add');
            //修改
            Route::post('update', 'Admin\Admin\GroupController@update');
            //删除
            Route::post('delete', 'Admin\Admin\GroupController@del');
            //列表
            Route::post('list', 'Admin\Admin\GroupController@list');
            //详情
            Route::post('detail', 'Admin\Admin\GroupController@detail');
            //冻结
            Route::post('freeze', 'Admin\Admin\GroupController@freeze');
            //解冻
            Route::post('unfreeze', 'Admin\Admin\GroupController@unfreeze');
        });
        //用户
        Route::prefix('user')->group(function () {
            //列表
            Route::post('list', 'Admin\User\UserController@list');
            //冻结用户
            Route::post('freeze', 'Admin\User\UserController@freeze');
            //解冻用户
            Route::post('unfreeze', 'Admin\User\UserController@unfreeze');
            //根据昵称,电话检索
            Route::post('search', 'Admin\User\UserController@search');
            //根据省,城,区检索
            Route::post('region', 'Admin\User\UserController@region');
            //详情
            Route::post('detail', 'Admin\User\UserController@detail');
            //兑换记录
            Route::post('exchange', 'Admin\User\UserController@exchange');
            //会员管理
            Route::post('user', 'Admin\User\UserController@user');
        });
        //日志
        Route::prefix('log')->group(function () {
            //列表
            Route::post('list', 'Admin\Log\LogController@list');
        });
    });
});
