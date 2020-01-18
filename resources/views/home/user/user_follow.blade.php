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
                        <a href="{{ url('/user/userFriend') }}">我的好友(<span>{{ $friends_count }}</span>)</a>
                    </li>
                    <li data-type="collection" data-url="/collection/find/" lay-id="collection" class="layui-this">
                        <a href="{{ url('/user/userFriend/follow') }}">关注的人（<span>{{ $follows_count }}</span>）</a>
                    </li>
                </ul>
                <div class="layui-tab-content" style="padding: 20px 0;">

                    <div class="layui-tab-item layui-show">
                        <div class="fly-panel">
                            <ul class="home-jieda">
                                @foreach($follows as $follow)
                                    <li data-id="111" class="jieda-daan" style="margin-bottom:5px; padding-top: 10px; border-bottom: 1px solid #DFDFDF;">
                                        <div class="detail-about detail-about-reply" style="padding-left:40px; margin-bottom: -10px">
                                            <div class="fly-detail-user" style="font-size: smaller">
                                                <img class="layui-circle fly-avatar" src="{{ $follow->avatar }}" alt=" " style="width: 35px;height: 35px">
                                                <a href="{{ url('authorInfo?id=').$follow->id }}" class="fly-link" style="font-size: small">
                                                    <cite style="font-style: italic">{{ $follow->username }}</cite>
                                                </a>
                                            </div>
                                            <span style="font-size: smaller">recently</span>
                                            <div class="jieda-admin">
                                                <a href="javascript:void(0)" onclick="cancelFollow({{ $follow->id }})">
                                                <span class="" >
                                                    <em class="" style="font-size: smaller">取消关注</em>
                                                </span>
                                                </a>
                                            </div>

                                        </div>
                                        <div class="detail-body jieda-body photos" style="margin-bottom: 0px;margin-top: 10px;padding-bottom: 5px">
                                            <p style="margin-bottom: 0px";>{{ $follow->introduce }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div id="LAY_page"></div>
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
                    elem: 'LAY_page' //注意，这里的 test1 是 ID，不用加 # 号
                    ,count: parseInt('{{ $follows_count }}'),
                    limit: 8,
                    curr: currPage,
                    jump: function(obj, first){
                        //首次不执行
                        if(!first){
                            //do something
                            window.location.href = '{{ url('/user/userFriend/follow?page=') }}'+obj.curr;
                        }
                    }

                });
            });
        });

    </script>
@endsection

<script>
    //取消关注
    function cancelFollow(id)
    {
        layer.confirm('确定要取消关注吗', {skin: 'layui-layer-lan',},function(){
            var author_id = id;
            $.ajax({
                url: "{{ url('/user/cancelFollow') }}",
                data: {'id': author_id},
                dataType: 'json',
                type: 'GET',
                success: function(e) {
                    layer.msg(e);
                    location.reload();
                },
                error: function(e) {
                    layer.msg(e);
                }
            })
        });
    }

</script>