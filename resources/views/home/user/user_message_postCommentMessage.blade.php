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
                        <a href="{{ url('/user/userMessage') }}">私信(<span>{{ $message_array[0] }}</span>)</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection" class="layui-this">
                        <a href="{{ url('/user/userMessage/postCommentMessage') }}">话题评论（<span>{{ $message_array[4] }}</span>）</a>
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
                        <div class="fly-panel">
                            <ul class="home-jieda ">
                                @foreach($message_array[3] as $post_comment_message)
                                    {{--<input type="hidden" value="{{ $comment->id }}" id="comment_id">--}}
                                    <li data-id="111" class="jieda-daan" class="{{ $post_comment_message->id }}" style="margin-bottom:5px; padding-top: 10px; border-bottom: 1px solid #DFDFDF;">
                                        <div class="detail-about detail-about-reply" style="padding-left:40px; margin-bottom: -10px">
                                            <div class="fly-detail-user" style="font-size: smaller">
                                                <img class="layui-circle fly-avatar" src="{{ $post_comment_message->user->avatar }}" alt=" " style="width: 35px;height: 35px">
                                                <a href="{{ url('authorInfo?id=').$post_comment_message->user->id }}" class="fly-link" style="font-size: small">
                                                    <cite style="font-style: italic">{{ $post_comment_message->user->username }}</cite>
                                                </a>
                                            </div>
                                            <span style="font-size: smaller">{{ date('Y-m-d',strtotime($post_comment_message->created_at)) }}</span>
                                            <span>&nbsp;回复话题：<a target="_blank" href="/postDetail?id={{$post_comment_message->post->id}}">{{ $post_comment_message->post->title }}</a></span>
                                            <a href="" class="fly-link" style="font-size: small">
                                                <cite>{{ $post_comment_message->comment_username }}</cite>
                                            </a>
                                            <div class="jieda-admin">
                                                {{--@if($post_comment_message->chat_list == 1 )--}}
                                                    {{--<a href="javascript:void(0)" style="color: black">--}}
                                                    {{--<span class="" >--}}
                                                        {{--<em class="" onclick='commentList("{{ $post_comment_message->id }}")' style="font-size: smaller">对话列表</em>--}}
                                                    {{--</span>--}}
                                                    {{--</a>--}}
                                                {{--@endif--}}

                                                <a href="javascript:void(0);" style="color: black">
                                                    <span class="" >
                                                        <i class="fa fa-smile-o comment_praise" style="font-size:15px" onclick="commentPraise(this)" id="{{ $post_comment_message->id }}" ></i>
                                                        <em class="{{ $post_comment_message->id }}">{{ $post_comment_message->praise }}</em>
                                                    </span>
                                                </a>
                                                </a>
                                                &nbsp;&nbsp;
                                                <a  style="color: black" onclick='showCommentInput("{{ $post_comment_message->id }}", "{{ $post_comment_message->user->username }}")'>
                                                    <span type="reply">
                                                        <i class="fa fa-commenting-o" style="font-size:15px"></i>
                                                        <em class="">{{ $post_comment_message->comments }}</em>
                                                    </span>
                                                </a>
                                                    &nbsp;
                                                    @if($post_comment_message->is_read == 0)
                                                        <a href="javascript:void(0)" onclick="changeIsRead('{{$post_comment_message->id}}', 2, this)"><span class="layui-badge-dot"></span></a>
                                                    @endif
                                            </div>

                                        </div>
                                        <div class="detail-body jieda-body photos" style="margin-bottom: 0px;margin-top: 10px;padding-bottom: 5px">
                                            <p style="margin-bottom: 0px";>{{ $post_comment_message->content }}</p>
                                        </div>
                                    </li>
                                        <div class="layui-form layui-form-pane _{{ $post_comment_message->id }}" style="display: none" >
                                            <form action="{{ url('/saveReplyComment') }}" method="post" class="replyCommentForm">
                                                {{--<input type="hidden" name="_token" value="{{csrf_token()}}"/>--}}
                                                <input type="hidden" name="comment_username" value="{{ $post_comment_message->user->username }}">
                                                <input type="hidden" name="comment_id" value="{{ $post_comment_message->id }}">
                                                <input type="hidden" name="parent_id" value="{{ $post_comment_message->parent_id }}">
                                                <input type="hidden" name="post_id" value="{{ $post_comment_message->post->id }}">
                                                <input type="hidden" name="user_id" value="{{ Auth::guard("user")->user()->id }}">
                                                <div class="layui-form-item layui-form-text">
                                                    <a name="comment"></a>
                                                    <div class="layui-input-block" id="comment_input">
                                                        <textarea name="_content" required lay-verify="required" placeholder="回复{{ $post_comment_message->user->username }}"  class="layui-textarea fly-editor" style="border-radius:4px; height: 80px;"></textarea>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <button class="layui-btn layui-btn-sm" lay-filter="*" lay-submit id="reply_comment_sub">提交回复</button>
                                                    <button type="button" class="layui-btn layui-btn-sm" lay-filter="*" onclick='cancelComment("{{ $post_comment_message->id }}")'>取消</button>
                                                </div>
                                            </form>
                                        </div>
                                @endforeach
                            </ul>
                            <div id="LAY_page2"></div>
                        </div>
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
                    elem: 'LAY_page2' //注意，这里的 test1 是 ID，不用加 # 号
                    ,count: parseInt('{{ $message_array[4] }}'),
                    limit: 6,
                    curr: currPage,
                    jump: function(obj, first){
                        //obj包含了当前分页的所有参数，比如：
                        console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                        console.log(obj.limit); //得到每页显示的条数

                        //首次不执行
                        if(!first){
                            window.location.href = '{{ url('/user/userMessage/postCommentMessage?page=') }}'+obj.curr;
                            //do something
                        }
                    }

                });
            });
        });

    </script>
@endsection

<script>
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

    function commentList(comment_id)
    {
        var index = layer.open({
            type: 2,
            title: "评论列表",
            shade: ['0.1'],
            closeBtn: 1,
            shadeClose: true,
            area: ['360px', '580px'],
            skin: 'layui-layer-lan',
            content: ["{{ url('/user/showPostCommentList?comment_id=') }}"+comment_id]
        });
        // layer.full(index);
    }

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

    function privateMessageList(parent_id)
    {
        var index = layer.open({
            type: 2,
            title: "对话列表",
            shade: ['0.1'],
            closeBtn: 1,
            shadeClose: true,
            area: ['360px', '580px'],
            skin: 'layui-layer-lan',
            content: ["{{ url('/user/showPrivateMessageList?parent_id=') }}"+parent_id]
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

    function showCommentInput(comment_id)
    {
        $("._"+comment_id).show();
    }

    function cancelComment(comment_id)
    {
        $("._"+comment_id).hide();
    }
</script>