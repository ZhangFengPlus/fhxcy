<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>用户->前台</title>
    </head>
    <style media="screen">
        label {
            margin-bottom: 20px;
            display: block;
        }
    </style>
    <body>
        <h2>注册</h2>
        <form class="" action="{{url('api/user/register')}}" method="post">
            <label for="">电话<input type="text" name="mobile" value=""> </label>
            <label for="">验证码<input type="text" name="code" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>获取验证码</h2>
        <form class="" action="{{url('getCode')}}" method="post">
            <label for="">电话<input type="text" name="mobile" value=""> </label>
            <label for="">1为注册 2为登录<input type="text" name="type" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>验证手机号是否存在</h2>
        <form class="" action="{{url('api/user/checkMobile')}}" method="post">
            <label for="">电话<input type="text" name="mobile" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>设置密码</h2>
        <form class="" action="{{url('api/user/setPassword')}}" method="post">
            <label for="">token<input type="text" name="token" value=""> </label>
            <label for="">密码<input type="text" name="password" value=""> </label>
            <label for="">再次输入密码<input type="text" name="password_confirmation" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>设置基本资料</h2>
        <form class="" action="{{url('api/user/setBasic')}}" method="post">
            <label for="">token<input type="text" name="token" value=""> </label>
            <label for="">头像<input type="text" name="avatar" value=""> </label>
            <label for="">昵称<input type="text" name="name" value=""> </label>
            <label for="">所属省份<input type="text" name="prov" value=""> </label>
            <label for="">所属城市<input type="text" name="city" value=""> </label>
            <label for="">所属区县<input type="text" name="area" value=""> </label>
            <label for="">性别<input type="text" name="sex" value=""> </label>
            <label for="">生日<input type="text" name="birthday" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>手机,密码登录</h2>
        <form class="" action="{{url('api/user/login')}}" method="post">
            <label for="">电话<input type="text" name="mobile" value=""> </label>
            <label for="">密码<input type="text" name="password" value=""> </label>
            <label for="">1<input type="text" name="type" value="1"> </label>
            <input type="submit" value="提交">
        </form>
        <h2>手机,验证码登录</h2>
        <form class="" action="{{url('api/user/login')}}" method="post">
            <label for="">电话<input type="text" name="mobile" value=""> </label>
            <label for="">验证码<input type="text" name="code" value=""> </label>
            <label for="">2<input type="text" name="type" value="2"> </label>
            <input type="submit" value="提交">
        </form>
        <h2>个人中心</h2>
        <form class="" action="{{url('api/user/detail')}}" method="post">
            <label for="">token<input type="text" name="token" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>更换头像</h2>
        <form class="" action="{{url('api/user/avatar')}}" method="post">
            <label for="">token<input type="text" name="token" value=""> </label>
            <label for="">头像<input type="text" name="avatar" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>更换所在地区</h2>
        <form class="" action="{{url('api/user/location')}}" method="post">
            <label for="">token<input type="text" name="token" value=""> </label>
            <label for="">所在省份<input type="text" name="prov" value=""> </label>
            <label for="">所在城市<input type="text" name="city" value=""> </label>
            <label for="">所在区县<input type="text" name="area" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>更换手机号</h2>
        <form class="" action="{{url('api/user/mobile')}}" method="post">
            <label for="">token<input type="text" name="token" value=""> </label>
            <label for="">电话<input type="text" name="mobile" value=""> </label>
            <label for="">验证码<input type="text" name="code" value=""> </label>
            <input type="submit" value="提交">
        </form>
    </body>
</html>
