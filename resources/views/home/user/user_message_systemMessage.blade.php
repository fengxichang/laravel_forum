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
                    <li data-type="mine-jie" lay-id="index" >
                        <a href="{{ url('/user/userMessage') }}">私信(<span>{{ $message_array[4] }}</span>)</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                        <a href="{{ url('/user/userMessage/postCommentMessage') }}">话题评论（<span>{{ $message_array[3] }}</span>）</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                        <a href="{{ url('/user/userMessage/commentMessage') }}"> 回复评论(<span>{{ $message_array[2] }}</span>)</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection" class="layui-this">
                        <a href="{{ url('/user/userMessage/systemMessage') }}">系统消息(<span>{{ $message_array[0] }}</span>)</a>
                    </li>
                </ul>
                <div class="layui-tab-content" style="padding: 20px 0;">

                    <div class="layui-tab-item layui-show">
                        @foreach($message_array[1] as $systemMessage)
                            <ul class="mine-view jie-row">
                                    <li>
                                        @if($systemMessage->type == 2)
                                            <a href="{{ url('authorInfo?id=').$systemMessage->user_id }}" class="jie-title">{{ $systemMessage->content }}</a>
                                        @else
                                            {{ $systemMessage->content }}
                                        @endif

                                        <i>{{ date('Y-m-d', strtotime($systemMessage->created_at)) }}</i>
                                        <div style="float: right;display: inline-block">
                                            @if($systemMessage->is_read == 0)
                                                @if($systemMessage->type == 2)
                                                  <a class="mine-edit" href="javascript:void(0);" onclick="friendApplyHandle(1, '{{ $systemMessage->id }}')">接受</a>
                                                  <a class="mine-edit" href="javascript:void(0);" onclick="friendApplyHandle(0, '{{ $systemMessage->id }}')">拒绝</a>
                                               @endif
                                           @else
                                                <a class="mine-edit" href="javascript:void(0);">删除</a>
                                           @endif

                                        </div>
                                    </li>
                                <!-- <div class="fly-none" style="min-height: 50px; padding:30px 0; height:auto;"><i style="font-size:14px;">没有发表任何求解</i></div> -->
                            </ul>
                        @endforeach
                        <div id="LAY_page3"></div>
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
                    elem: 'LAY_page3' //注意，这里的 test1 是 ID，不用加 # 号
                    ,count: parseInt('{{ $message_array[0] }}'),
                    limit: 10,
                    curr: currPage,
                    jump: function(obj, first){
                        //首次不执行
                        if(!first){
                            window.location.href = '{{ url('/user/userMessage/systemMessage?page=') }}'+obj.curr;
                            //do something
                        }
                    }

                });
            });
        });

    </script>
@endsection

<script>


    //处理好友申请
    function friendApplyHandle(res, id)
    {
        var _res = res;
        $.ajax({
            url: "{{ url('/user/handleFriendApply') }}",
            type: 'get',
            dataType: 'json',
            data: {
                'res': _res,
                'id': id
            },
            success: function(e) {
                layer.msg(e)
            },
            error: function(e) {
                layer.msg('操作失败');
            }
        })
    }
</script>