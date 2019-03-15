<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>新墨官网主页-新墨致力于多领域产品策划、设计和开发。提供APP、微信、小程序、PC、物联网的产品全案解决方案。</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->

        <!-- icon -->
        <link href="favicon.ico" rel="shortcut icon">
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Sinmore
                </div>

                <div class="links">
                    <a href="http://www.sinmore.com.cn/">Index</a>
                    <a href="http://www.sinmore.com.cn/index.php?s=/Home/Service/index.html">Services</a>
                    <a href="http://www.sinmore.com.cn/index.php?s=/Home/Case/index.html">Cases</a>
                    <a href="http://www.sinmore.com.cn/index.php?s=/Home/About/index.html">About</a>
                    <a href="http://www.sinmore.com.cn/index.php?s=/Home/News/index/type_id/1.html">News</a>
                </div>
            </div>
        </div>
    </body>
</html>
