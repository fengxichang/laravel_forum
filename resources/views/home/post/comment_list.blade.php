<!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8">
    <title>DoIT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="fly,layui,前端社区">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Fly社区是模块化前端UI框架Layui的官网社区，致力于为web开发提供强劲动力">
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
        .icon_search {
            border-bottom-right-radius: 11px;
            border-top-right-radius: 11px;
            font-size: 26px;
            background-color: white;
            color: #3B3B3B;
            position: absolute;
            left: 238px;
            top:10px
        }
        .icon_search:hover {
            cursor:pointer
        }
        .search_input {
            margin-top:10px;
            text-indent: 5px;
            height:26px;
            width: 240px;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            outline: none;
            border: 1px solid #DBDBDB
        }

    </style>
</head>
<body bgcolor="white" style="margin: 0px; height: 100%">
@inject("oAuth",'Illuminate\Support\Facades\Auth')
    <ul class="jieda" id="comment" style="margin-bottom: 0px; padding: 15px">
    @foreach($comments as $comment)
        {{--<input type="hidden" value="{{ $comment->id }}" id="comment_id">--}}
        <li data-id="111" class="jieda-daan" class="{{ $comment->id }}" style="padding-top: 10px; border-bottom: 1px solid #DFDFDF;">
            <a name="item-1111111111"></a>
            <div class="detail-about detail-about-reply" style="padding-left:40px; margin-bottom: -10px">
                <div class="fly-detail-user" style="font-size: smaller">
                    <img class="layui-circle fly-avatar" src="{{ $comment->user->avatar }}" alt=" " style="width: 35px;height: 35px">
                    <a href="" class="fly-link" style="font-size: small">
                        <cite>{{ $comment->user->username }}</cite>
                    </a>
                    @if(!empty(Auth::guard("user")->user()->id))
                        @if(Auth::guard("user")->user()->id == $comment->user_id)
                            <span type="edit">编辑</span>
                            <span type="del">删除</span>
                            <!-- <span class="jieda-accept" type="accept">采纳</span> -->
                        @endif
                    @endif
                </div>
                <span style="font-size: smaller">{{ date('Y-m-d',strtotime($comment->created_at)) }}</span>
                <a href="" class="fly-link" style="font-size: small">
                    <cite>{{ $comment->comment_username }}</cite>
                </a>
                <div class="jieda-admin">
                    <a href="javascript:void(0)">
                                    <span class="" >
                                        <i class="fa fa-smile-o comment_praise" style="font-size:15px" onclick="commentPraise(this)" id="{{ $comment->id }}" ></i>
                                        <em class="{{ $comment->id }}">{{ $comment->praise }}</em>
                                    </span>
                    </a>
                    &nbsp;&nbsp;
                    <a href="#comment_input" onclick='showCommentInput("{{ $comment->id }}", "{{ $comment->user->username }}")'>
                                    <span type="reply">
                                        <i class="fa fa-commenting-o" style="font-size:15px"></i>
                                        <em class="">{{ $comment->comments }}</em>
                                    </span>
                    </a>
                </div>

            </div>
            <div class="detail-body jieda-body photos" style="margin-bottom: 0px;margin-top: 10px">
                <p style="margin-bottom: 0px";>{{ $comment->content }}</p>
            </div>
        </li>
        @if(Auth::guard('user')->check())
            <div class="layui-form layui-form-pane _{{ $comment->id }}" style="display: none" >
                <form action="{{ url('/saveReplyComment') }}" method="post" class="replyCommentForm">
                    {{--<input type="hidden" name="_token" value="{{csrf_token()}}"/>--}}
                    <input type="hidden" name="comment_username" value="{{ $comment->user->username }}">
                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->parent_id }}">
                    <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                    <input type="hidden" name="user_id" value="{{ Auth::guard("user")->user()->id }}">
                    <div class="layui-form-item layui-form-text">
                        <a name="comment"></a>
                        <div class="layui-input-block" id="comment_input">
                            <textarea name="_content" required lay-verify="required" placeholder="回复{{ $comment->user->username }}"  class="layui-textarea fly-editor" style="height: 80px;"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <button class="layui-btn layui-btn-sm" lay-filter="*" lay-submit id="reply_comment_sub">提交回复</button>
                        <button type="button" class="layui-btn layui-btn-sm" lay-filter="*" onclick='cancelComment("{{ $comment->id }}")'>取消</button>
                    </div>
                </form>
            </div>
        @else

        @endif
    @endforeach
</ul>
    <script src="/js/jquery.min.js"></script>
    <script src="/layui.js"></script>
    <script src="/layui.all.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/jqcloud/jqcloud-1.0.4.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".replyCommentForm").submit(function(event){
                event.preventDefault();
                var _form = $(this);
                $.ajax({
                    url: _form.attr('action'),
                    data: _form.serialize(),
                    dataType: 'json',
                    type: _form.attr('method'),
                    success: function(e){
                        $("#comment").prepend(
                            '<li data-id="111" class="jieda-daan" style="padding-top: 10px; border-bottom: 1px solid #DFDFDF;">'+
                            '<a name="item-1111111111"></a>'+
                            '<div class="detail-about detail-about-reply">'+
                            '<a class="fly-avatar" href="">'+
                            '<img style="border-radius: 16px;width: 35px;height: 35px" src="{{ !empty($oAuth::guard('user')->user()->avatar)?$oAuth::guard('user')->user()->avatar:null  }}"></a>'+
                            '<div class="fly-detail-user">'+
                            '<a href="" class="fly-link">'+
                            '<cite>{{ !empty($oAuth::guard('user')->user()->username)?$oAuth::guard('user')->user()->username:null }}</cite></a></div>'+
                            '<div class="detail-hits">'+
                            '<span>'+e.created_at+'</span><a href="" class="fly-link">'+ e.comment_username+'</a></div></div>'+
                            '<div class="detail-body jieda-body photos" style="margin-top:10px; margin-bottom:0px">'+
                            '<p >'+e.content+'</p></div>'+
                            ''
                        );
                    },
                    error: function(e){
                        alert('提交失败');
                    }
                })
            });
        });

        function showCommentInput(comment_id)
        {
            $("._"+comment_id).show();
        }

        function cancelComment(comment_id)
        {
            $("._"+comment_id).hide();
        }

        function commentPraise(e){
            var id = e.id;
            $.ajax({
                url: "{{ url('/commentPraise') }}",
                data: {id: id},
                dataType: "",
                type: "post",
                success: function(e){
                    $("."+id).html(e);
                },
                error: function(e){
                    var praise_num = $("."+id).text();
                    $("."+id).html(e.responseJSON);
                    setTimeout(function(){
                        $("."+id).html(praise_num);
                    },2000);
                }
            })
        }
    </script>
</body>
</html>