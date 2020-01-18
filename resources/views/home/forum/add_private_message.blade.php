<!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8">
    <title>DoIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="fly,layui,前端社区">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/layui.css">
    <link rel="stylesheet" href="/css/global.css">
    <link rel="stylesheet" href="/jqcloud/jqcloud.css">
    <link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">
    {{--<link rel="stylesheet" href="/css/bootstrap.min.css">--}}
    <style type="text/css">
        textarea img {
            width: 200px;
            height:300px;
        }
        .layui-util-face {
            margin-right: auto;
            margin-left: auto;
        }

    </style>
</head>
<body bgcolor="white" style="margin: 0px; height: 100%">
@inject("oAuth",'Illuminate\Support\Facades\Auth')
<div class="layui-form layui-form-pane post_comment" id="comment_form">
    <form action="{{url('/user/sendPrivateMessage')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
        <input type="hidden" name="author_id" value="{{ $author_id }}">
        <div class="layui-form-item layui-form-text">
            <a name="comment"></a>
            <div class="layui-input-block" id="comment_input">
                <textarea id="demo" style="display: none;" name="message"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn layui-btn-normal layui-btn-sm" style="margin-left: 150px; margin-right: auto" lay-filter="*" lay-submit id="sub">提交回复</button>
        </div>
    </form>
</div>
    <script src="/js/jquery.min.js"></script>
    <script src="/layui.js"></script>
    <script src="/layui.all.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function(){

        });
        layui.use('layedit', function(){
            var layedit = layui.layedit;
            layedit.build('demo', {
                tool: []
            }); //建立编辑器
        });
    </script>

</body>
</html>