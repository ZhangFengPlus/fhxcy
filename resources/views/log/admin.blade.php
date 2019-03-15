<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>日志->后台</title>
    </head>
    <style media="screen">
        label {
            margin-bottom: 20px;
            display: block;
        }
    </style>
    <body>
        <h2>列表</h2>
        <form class="" action="{{url('admin/log/list')}}" method="post">
            <label for="">token<input type="text" name="token" value=""></label>
            <label for="">页数<input type="text" name="page" value=""></label>
            <label for="">每页展示条数<input type="text" name="pagesize" value=""></label>
            <input type="submit" value="提交">
        </form>
    </body>
</html>
