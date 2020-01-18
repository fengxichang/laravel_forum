@extends("home.layouts.head")
@section("content")
<div class="layui-container fly-marginTop fly-user-main" style="margin-top: 55px;">

  @include("home.layouts.set_navbar")

  <div class="fly-panel fly-panel-user" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);" pad20>

    <div class="layui-tab layui-tab-brief" lay-filter="user">
      <ul class="layui-tab-title" id="LAY_mine">
        <li data-type="mine-jie" lay-id="index" class="layui-this">
            <a href="{{ url('/user/index') }}">发表的帖(<span>{{$myPost_count}}</span>)</a>
        </li>
          <li data-type="collection"  data-url="/collection/find/" lay-id="collection">
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
        <div class="layui-tab-item layui-show index_item1">
          <ul class="mine-view jie-row">
              @foreach($myPosts as $myPost)
                <li>
                  <a class="jie-title" href="/postDetail?id={{$myPost->id}}" target="_blank">{{ $myPost->title }}</a>
                  <i>{{ date('Y-m-d', strtotime($myPost->created_at)) }}</i>
                    <span></span>
                    <i>{{ $myPost->visited }}阅/{{ $myPost->comments }}回</i>
                    <span></span>
                    <div style="float: right;display: inline-block">
                  <button style="float: right; margin-right: 8px" class="layui-btn layui-btn-xs" href="javascript:void(0)" onclick="deletePost({{ $myPost->id }})">删除</button>
                    <a style="float: right" href="/post/editPost?post_id={{$myPost->id}}"><button class="layui-btn layui-btn-xs" >编辑</button></a>
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

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            layui.use('laypage', function(){
                var laypage = layui.laypage;
                currPage = GetQueryString('page') == null ? 1 : GetQueryString('page');
                //执行一个laypage实例
                laypage.render({
                    elem: 'LAY_page' //注意，这里的 test1 是 ID，不用加 # 号
                    ,count: parseInt('{{$myPost_count}}'),
                    limit: 10,
                    curr: currPage,
                    jump: function(obj, first){
                        //首次不执行
                        if(!first){
                            window.location.href = '{{ url('/user/index?page=') }}'+obj.curr;
                        }
                    }

                });
            });
        });
    </script>
@endsection
<script type="text/javascript">
    function deletePost(post_id)
    {
        layer.confirm('确定要删除话题吗', {skin: 'layui-layer-lan',},function(){
            $.ajax({
                url: "{{ url('/post/deletePost') }}",
                dataType: "json",
                type: "get",
                data: {
                    'post_id': post_id
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