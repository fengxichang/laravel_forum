<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta charset="UTF-8">
        <title>注册确认链接</title>
        </head>

    <body>
        <div style="width:100%;height:200px;padding:10px;background-image: url('http://www.laravel-forum-app.com/images/lable/100.jpg');border-radius: 4px;border: 1px solid #c2c2c2;">
            <h3>感谢您在 听说 进行注册！</h3>
            <p>
                请点击下面的链接完成注册：
                <br>
                <a href="{{ url('/user/verifiedEmail?verification_token=') . $verification_token }}">
                    "{{ url('/user/verifiedEmail?verification_token=') . $verification_token }}"
                </a>
            </p>
        </div>
    </body>

</html>