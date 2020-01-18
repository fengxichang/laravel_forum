<!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8">
    <title>DoIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
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
        div.jqcloud span.vertical {
            -webkit-writing-mode: vertical-rl;
            writing-mode: tb-rl;
        }

        .private_message_list_author {
            padding-top: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #DFDFDF;
        }
        .private_message_avatar_author {
            padding-left:40px;
            margin-bottom: -10px;
            height: auto;
            width:10%
        }
        .private_message_content_author {
            margin-bottom: 0px;
            margin-top: 10px;
            margin-left: 14%;
            width:88%
        }

        .private_message_list_user {
            padding-top: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #DFDFDF;
        }
        .private_message_avatar_user {
            padding-left:40px;
            margin-bottom: -10px;
            height: auto;
            width:10%
        }
        .private_message_content_user {
            margin-bottom: 0px;
            margin-top: 10px;
            margin-left: 14%;
            width:88%
        }


    </style>
</head>
<body bgcolor="white" style="margin: 0px; height: 100%; background-color: white">
@inject("oAuth",'Illuminate\Support\Facades\Auth')
<ul class="jieda" id="comment" style="margin-bottom: 0px; padding: 5px 15px 15px 15px; height: 420px">
    @foreach($messages as $message)
        <li data-id="111" class="jieda-daan" class="{{ $message->id }}" style="padding-top: 5px;padding-bottom: 5px;border-bottom: 1px solid #DFDFDF;">
            <div>
            <div class="detail-about detail-about-reply pull-left private_message_avatar_author">
                <div class="fly-detail-user" style="font-size: smaller">
                    <img class="layui-circle fly-avatar" src="{{ $message->user->avatar }}" alt=" " style="width: 35px;height: 35px">
                </div>
            </div>
            <div class="detail-body jieda-body photos private_message_content_author">
                <p style="margin-bottom: 0px";>{{ $message->content }}</p>
                <span style="font-size: smaller">{{ date('Y-m-d',strtotime($message->created_at)) }}</span>
            </div>
            </div>
        </li>
    @endforeach
</ul>

    <script src="/js/jquery.min.js"></script>
    <script src="/layui.js"></script>
    <script src="/layui.all.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/jqcloud/jqcloud-1.0.4.min.js"></script>
    <script>
        layui.use('layedit', function(){
            var layedit = layui.layedit;
            layedit.build('demo', {
                height: 70,
                tool: []
            }); //建立编辑器
        });

    </script>
</body>
</html>