@extends("home.layouts.head")
@section("content")
    <style>
        .comment_span {
            /*overflow: hidden;*/
            /*text-overflow: ellipsis;*/
            /*-o-text-overflow: ellipsis;*/
            white-space:pre-wrap;
            width:100%;
            font-size: 14px;
            /*height:24px;*/
            display:block;
        }
    </style>

    <div class="layui-container fly-marginTop fly-user-main" style="margin-top: 55px;">
        {{--加载个人置侧边栏--}}
        @include("home.layouts.set_navbar")

        <div class="fly-panel fly-panel-user" pad20 style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);">

            <div class="layui-tab layui-tab-brief" lay-filter="user" >
                <ul class="layui-tab-title" id="LAY_mine">
                    <li data-type="mine-jie" lay-id="index" class="layui-this">
                        <a href="{{ url('/user/userMessage') }}">私信(<span>{{ $message_array[4] }}</span>)</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                        <a href="{{ url('/user/userMessage/postCommentMessage') }}">话题评论（<span>{{ $message_array[3] }}</span>）</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                        <a href="{{ url('/user/userMessage/commentMessage') }}"> 回复评论(<span>{{ $message_array[2] }}</span>)</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                        <a href="{{ url('/user/userMessage/systemMessage') }}">系统消息(<span>{{ $message_array[1] }}</span>)</a>
                    </li>
                </ul>
                <div class="layui-tab-content" style="padding: 20px 0;">
                    <div class="layui-tab-item layui-show">
                        <ul class="home-jieda">
                            @foreach($message_array[0] as $private_message)
                                <li data-id="111" class="jieda-daan" class="{{ $private_message->id }}" style="margin-bottom:5px; padding-top: 10px; border-bottom: 1px solid #DFDFDF;">
                                    <div class="detail-about detail-about-reply" style="padding-left:40px; margin-bottom: -10px">
                                        <div class="fly-detail-user" style="font-size: smaller">
                                            <img class="layui-circle fly-avatar" src="{{ $private_message->user->avatar }}" alt=" " style="width: 35px;height: 35px">
                                            <a href="" class="fly-link" style="font-size: small">
                                                <cite style="font-style: italic">{{ $private_message->user->username }}</cite>
                                            </a>
                                        </div>
                                        <span style="font-size: smaller">{{ date('Y-m-d',strtotime($private_message->created_at)) }}</span>
                                        <div class="jieda-admin">
                                            <a href="javascript:void(0)" style="color: black">
                                                <span class="" >
                                                    <em class="" onclick='privateMessageList("{{ $private_message->parent_id }}")' style="font-size: smaller">对话列表</em>
                                                </span>
                                            </a>
                                            &nbsp;&nbsp;
                                            <a href="javascript:void(0)" style="color: black">
                                                <span class="" >
                                                    <em class="" onclick='privateMessageReply("{{ $private_message->parent_id }}")' style="font-size: smaller">回复</em>
                                                </span>
                                            </a>
                                            &nbsp;&nbsp;
                                            @if($private_message->is_read == 0)
                                                <a href="javascript:void(0)" onclick="changeIsRead('{{$private_message->id}}', 1, this)"><span class="layui-badge-dot"></span></a>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="detail-body jieda-body photos" style="margin-bottom: 0px;margin-top: 10px;padding-bottom: 5px">
                                        <p style="margin-bottom: 0px" class="private_message_content">{{ $private_message->content }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div id="LAY_page6"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            layui.use('laypage', function(){
                var laypage = layui.laypage;
                currPage = GetQueryString('page') == null ? 1 : GetQueryString('page');
                //执行一个laypage实例
                laypage.render({
                    elem: 'LAY_page6' //注意，这里的 test1 是 ID，不用加 # 号
                    ,count: parseInt('{{ $message_array[4] }}'),
                    limit: 6,
                    curr: currPage,
                    jump: function(obj, first){
                        //首次不执行
                        if(!first){
                            //do something
                            window.location.href = '{{ url('/user/userMessage?page=') }}'+obj.curr;
                        }
                    }

                });
            });
        });

    </script>
@endsection

<script>

    function privateMessageList(parent_id)
    {
        var index = layer.open({
            type: 2,
            title: "对话列表",
            shade: ['0.1'],
            closeBtn: 1,
            shadeClose: true,
            area: ['360px', '482px'],
            skin: 'layui-layer-lan',
            content: ["{{ url('/user/showPrivateMessageList?parent_id=') }}"+parent_id]
        });
    }

    function privateMessageReply(parent_id)
    {
        var index = layer.open({
            type: 2,
            title: "对话列表",
            shade: ['0.1'],
            closeBtn: 1,
            shadeClose: true,
            area: ['360px', '392px'],
            skin: 'layui-layer-lan',
            content: ["{{ url('/user/privateMessageReply?parent_id=') }}"+parent_id]
        });
    }

    function changeIsRead(message_id, type, _this)
    {
        $.ajax({
            url: "{{ url('/user/changeIsRead') }}",
            dataType: 'json',
            type: 'GET',
            data: {
                'message_id': message_id,
                'type': type
            },
            success: function(e) {
                $(_this).hide();
            },
            error: function(e) {
                layer.msg('网络错误');
            }
        })
    }

</script>