<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <script src="/js/jquery.min.js" charset="utf-8"></script>
    <style media="screen">
        label {
            margin-bottom: 20px;
            display: block;
        }
    </style>
    <body>
        <h2>注册</h2>
        <form class="" action="{{url('admin/register')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <label for="">用户名<input type="text" name="name" value=""></label>
            <label for="">密码<input type="text" name="password" value=""></label>
            <label for="">邮箱<input type="text" name="email" value=""></label>
            <label for="">手机<input type="text" name="mobile" value=""></label>
            <label for="">所属权限组<input type="text" name="group_id" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>登录</h2>
        <form class="" action="{{url('admin/login')}}" method="post">
            <label for="">用户名<input type="text" name="mobile" value=""></label>
            <label for="">密码<input type="text" name="password" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>冻结</h2>
        <form class="" action="{{url('admin/freeze')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <label for="">用户id<input type="text" name="admin_id" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>解冻</h2>
        <form class="" action="{{url('admin/unfreeze')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <label for="">用户id<input type="text" name="admin_id" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>列表</h2>
        <form class="" action="{{url('admin/list')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <label for="">页数<input type="text" name="page" value=""></label>
            <label for="">状态<input type="text" name="status" value=""></label>
            <label for="">每页展示条数<input type="text" name="pagesize" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>展示</h2>
        <form class="" action="{{url('admin/detail')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <label for="">用户id<input type="text" name="admin_id" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>获取所有管理组</h2>
        <form class="" action="{{url('admin/getAllGroup')}}" method="post">
            <input type="submit" value="提交">
        </form>
        <h2>修改</h2>
        <form class="" action="{{url('admin/update')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <label for="">用户id<input type="text" name="admin_id" value=""></label>
            <label for="">用户名<input type="text" name="name" value=""></label>
            <label for="">密码<input type="text" name="password" value=""></label>
            <label for="">邮箱<input type="text" name="email" value=""></label>
            <label for="">手机<input type="text" name="mobile" value=""></label>
            <label for="">所属权限组<input type="text" name="group_id" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>删除</h2>
        <form class="" action="{{url('admin/delete')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <label for="">用户id<input type="text" name="admin_id" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>登出</h2>
        <form class="" action="{{url('admin/logout')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <input type="submit" value="提交">
        </form>
        <h2>测试淘宝</h2>
        <input type="submit" value="提交" id="taobao">
        <script type="text/javascript">
            $('#taobao').click(function(){
                $.ajax({
                    url: "http://hws.m.taobao.com/cache/wdetail/5.0/?id=534085122030",
                    type: "GET",
                    dataType: "jsonp", //指定服务器返回的数据类型
                    success: function (data) {
                        var result = JSON.stringify(data); //json对象转成字符串
                        console.log(result);
                    }
                });
                // $.getJSON('http://hws.m.taobao.com/cache/wdetail/5.0/?id=534085122030','',function(res){
                //     console.log(res);
                // })
            })
        </script>
    </body>
</html>
