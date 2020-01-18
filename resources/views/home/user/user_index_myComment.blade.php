@extends("home.layouts.head")
@section("content")

<div class="layui-container fly-marginTop fly-user-main" style="margin-top: 55px;">
    {{--加载个人置侧边栏--}}
  @include("home.layouts.set_navbar")

  <div class="fly-panel fly-panel-user" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);" pad20>

    <div class="layui-tab layui-tab-brief" lay-filter="user">
        <ul class="layui-tab-title" id="LAY_mine">
            <li data-type="mine-jie" lay-id="index" >
                <a href="{{ url('/user/index') }}">发表的帖(<span>{{$myPost_count}}</span>)</a>
            </li>
            <li data-type="collection" class="layui-this" data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myComment') }}">最近回答（<span>{{ $myComments_count }}</span>）</a>
            </li>
            <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myCollectPost') }}">收藏的帖(<span>{{ $collectPosts_count }}</span>)</a>
            </li>
            <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myCollectLabel') }}">关注的标签（<span>{{ $lables_count }}</span>）</a>
            </li>
        </ul>

      <div class="layui-tab-content" style="padding: 20px 0;">

        <div class="layui-tab-item index_item2 layui-show">
          <div class="fly-panel">

              @foreach($myComments as $myComment)
                  <ul class="home-jieda ">
                <li>
                  <p>
                    <span>{{ $myComment->created_at }}</span>
                    @if(!empty($myComment->height_id))
                          <a href="javascript:void(0);">{{ $myComment->comment_username }}</a>：
                    @else
                      在<a target="_blank" href="/postDetail?id={{$myComment->post_id}}">{{ $myComment->reply_for }}</a>中回复
                    @endif
                      &nbsp;&nbsp;
                      <button style="float: right" class="layui-btn layui-btn-xs" id="{{ $myComment->id }}" onclick="deleteComment(this)">删除</button>
                  </p>
                    @if(!empty($myComment->height_id))
                          <div class="home-dacontent">
                            {{ $myComment->parent_comment }}
                          </div>
                        <span>{{ $myComment->content }}</span>
                    @else
                        <div class="home-dacontent">
                            {{ $myComment->content }}
                        </div>
                    @endif
                    <hr>
                </li>
                  </ul>
            @endforeach
            <!-- <div class="fly-none" style="min-height: 50px; padding:30px 0; height:auto;"><span>没有回答任何问题</span></div> -->

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
                    ,count: parseInt('{{ $myComments_count }}'),
                    limit: 5,
                    curr: currPage,
                    jump: function(obj, first){
                        //首次不执行
                        if(!first){
                            window.location.href = '{{ url('/user/index/myComment?page=') }}'+obj.curr;
                            //do something
                        }
                    }

                });
            });
        });

    </script>
@endsection
<script>
    function deleteComment(_this)
    {
        layer.confirm('确定要删除评论吗', {skin: 'layui-layer-lan',},function(){
            $.ajax({
                url: "{{ url('/user/deleteComment') }}",
                dataType: "json",
                type: "get",
                data: {
                    'comment_id': _this.id
                },
                success: function(e) {
                    layer.msg(e);
                    location.reload();
                },
                error: function (e) {
                    layer.msg(e);
                }
            })
        });
    }
</script>