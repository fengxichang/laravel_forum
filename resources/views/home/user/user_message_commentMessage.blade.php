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
                        <a href="{{ url('/user/userMessage') }}">私信(<span>{{ $message_array[1] }}</span>)</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                        <a href="{{ url('/user/userMessage/postCommentMessage') }}">话题评论（<span>{{ $message_array[3] }}</span>）</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" class="layui-this" lay-id="collection">
                        <a href="{{ url('/user/userMessage/commentMessage') }}"> 回复评论(<span>{{ $message_array[4] }}</span>)</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                        <a href="{{ url('/user/userMessage/systemMessage') }}">系统消息(<span>{{ $message_array[0] }}</span>)</a>
                    </li>
                </ul>
                <div class="layui-tab-content" style="padding: 20px 0;">

                    <div class="layui-tab-item layui-show">
                            <ul class="home-jieda">
                                @foreach($message_array[2] as $commentMessage)
                                    <li data-id="111" class="jieda-daan" class="{{ $commentMessage->id }}" style="margin-bottom:5px; padding-top: 10px; border-bottom: 1px solid #DFDFDF;">
                                        <div class="detail-about detail-about-reply" style="padding-left:40px; margin-bottom: -10px">
                                            <div class="fly-detail-user" style="font-size: smaller">
                                                <img class="layui-circle fly-avatar" src="{{ $commentMessage->user->avatar }}" alt=" " style="width: 35px;height: 35px">
                                                <a href="{{ url('authorInfo?id=').$commentMessage->user->id }}" class="fly-link" style="font-size: small">
                                                    <cite style="font-style: italic">{{ $commentMessage->user->username }}</cite>
                                                    {{--<span>&nbsp;回复话题：{{ $commentMessage->post->title }}</span>--}}
                                                </a>
                                            </div>
                                            <span style="font-size: smaller">{{ date('Y-m-d',strtotime($commentMessage->created_at)) }}</span>
                                            <a href="" class="fly-link" style="font-size: small">
                                                <cite>{{ $commentMessage->comment_username }}</cite>
                                            </a>
                                            <div style="border-radius:4px; width: 100%; background-color: #F2F2F5;padding: 8px"><span class="comment_span">{{ $commentMessage->parent_comment->content }}</span></div>
                                            <div class="jieda-admin">
                                                    {{--<a href="javascript:void(0)" style="color: black">--}}
                                                    {{--<span class="" >--}}
                                                        {{--<em class="" onclick='commentList("{{ $commentMessage->parent_id }}")' style="font-size: smaller">对话列表</em>--}}
                                                    {{--</span>--}}
                                                    {{--</a>--}}
                                                <a href="javascript:void(0);" style="color: black">
                                                    <span class="" >
                                                        <i class="fa fa-smile-o comment_praise" style="font-size:15px" onclick="commentPraise(this)" id="{{ $commentMessage->id }}" ></i>
                                                        <em class="{{ $commentMessage->id }}">{{ $commentMessage->praise }}</em>
                                                    </span>
                                                </a>
                                                </a>
                                                &nbsp;&nbsp;
                                                <a href="#comment_input" style="color: black" onclick='showCommentInput("{{ $commentMessage->id }}", "{{ $commentMessage->user->username }}")'>
                                                    <span type="reply">
                                                        <i class="fa fa-commenting-o" style="font-size:15px"></i>
                                                        <em class="">{{ $commentMessage->comments }}</em>
                                                    </span>
                                                </a>
                                                &nbsp;
                                                @if($commentMessage->is_read == 0)
                                                    <a href="javascript:void(0)" onclick="changeIsRead('{{$commentMessage->id}}', 2, this)"><span class="layui-badge-dot"></span></a>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="detail-body jieda-body photos" style="margin-bottom: 0px;margin-top: 10px;padding-bottom: 5px">
                                            <p style="margin-bottom: 0px";>{{ $commentMessage->content }}</p>
                                        </div>
                                    </li>
                                    @if(Auth::guard('user')->check())
                                        <div class="layui-form layui-form-pane _{{ $commentMessage->id }}" style="display: none" >
                                            <form action="{{ url('/saveReplyComment') }}" method="post" class="replyCommentForm">
                                                {{--<input type="hidden" name="_token" value="{{csrf_token()}}"/>--}}
                                                <input type="hidden" name="comment_username" value="{{ $commentMessage->user->username }}">
                                                <input type="hidden" name="comment_id" value="{{ $commentMessage->id }}">
                                                <input type="hidden" name="parent_id" value="{{ $commentMessage->parent_id }}">
                                                <input type="hidden" name="post_id" value="{{ $commentMessage->post->id }}">
                                                <input type="hidden" name="user_id" value="{{ Auth::guard("user")->user()->id }}">
                                                <div class="layui-form-item layui-form-text">
                                                    <a name="comment"></a>
                                                    <div class="layui-input-block" id="comment_input">
                                                        <textarea name="_content" required lay-verify="required" placeholder="回复{{ $commentMessage->user->username }}"  class="layui-textarea fly-editor" style="border-radius:4px; height: 80px;"></textarea>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <button class="layui-btn layui-btn-sm" lay-filter="*" lay-submit id="reply_comment_sub">提交回复</button>
                                                    <button type="button" class="layui-btn layui-btn-sm" lay-filter="*" onclick='cancelComment("{{ $commentMessage->id }}")'>取消</button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                    @endif
                                @endforeach
                            </ul>
                        <div id="LAY_page1"></div>
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
                    elem: 'LAY_page1' //注意，这里的 test1 是 ID，不用加 # 号
                    ,count: parseInt('{{ $message_array[4] }}'),
                    limit: 5,
                    curr: currPage,
                    jump: function(obj, first){
                        //首次不执行
                        if(!first){
                            window.location.href = '{{ url('/user/userMessage/commentMessage?page=') }}'+obj.curr;
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