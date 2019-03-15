<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>用户->后台</title>
    </head>
    <style media="screen">
        label {
            margin-bottom: 20px;
            display: block;
        }
    </style>
    <body>
        <h2>列表</h2>
        <form class="" action="{{url('admin/user/list')}}" method="post">
            <label for="">token<input type="text" name="token" value="123456"> </label>
            <label for="">页数<input type="text" name="page" value=""> </label>
            <label for="">每页多少条<input type="text" name="pagesize" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>列表</h2>
        <form class="" action="{{url('admin/user/search')}}" method="post">
            <label for="">token<input type="text" name="token" value="123456"> </label>
            <label for="">关键字<input type="text" name="keyword" value=""> </label>
            <label for="">1为昵称,2为电话号<input type="text" name="type" value=""> </label>
            <label for="">页数<input type="text" name="page" value=""> </label>
            <label for="">每页多少条<input type="text" name="pagesize" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>列表</h2>
        <form class="" action="{{url('admin/user/region')}}" method="post">
            <label for="">token<input type="text" name="token" value="123456"> </label>
            <label for="">省份<input type="text" name="prov" value=""> </label>
            <label for="">城市<input type="text" name="city" value=""> </label>
            <label for="">区县<input type="text" name="area" value=""> </label>
            <label for="">页数<input type="text" name="page" value=""> </label>
            <label for="">每页多少条<input type="text" name="pagesize" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>会员详情</h2>
        <form class="" action="{{url('admin/user/detail')}}" method="post">
            <label for="">token<input type="text" name="token" value="123456"> </label>
            <label for="">用户id<input type="text" name="user_id" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>冻结用户</h2>
        <form class="" action="{{url('admin/user/freeze')}}" method="post">
            <label for="">token<input type="text" name="token" value="123456"> </label>
            <label for="">用户id<input type="text" name="user_id" value=""> </label>
            <input type="submit" value="提交">
        </form>
        <h2>解冻用户</h2>
        <form class="" action="{{url('admin/user/unfreeze')}}" method="post">
            <label for="">token<input type="text" name="token" value="123456"> </label>
            <label for="">用户id<input type="text" name="user_id" value=""> </label>
            <input type="submit" value="提交">
        </form>
    </body>
</html>
