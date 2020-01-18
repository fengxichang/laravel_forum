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
            <li data-type="collection"  data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myComment') }}">最近回答（<span>{{ $myComments_count }}</span>）</a>
            </li>
            <li data-type="collection" class="layui-this" data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myCollectPost') }}">收藏的帖(<span>{{ $collectPosts_count }}</span>)</a>
            </li>
            <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myCollectLabel') }}">关注的标签（<span>{{ $lables_count }}</span>）</a>
            </li>
        </ul>

      <div class="layui-tab-content" style="padding: 20px 0;">

        <div class="layui-tab-item index_item3 layui-show">
          @foreach($collectPosts as $collectPost)
          <ul class="mine-view jie-row">
            <li>
              <a class="jie-title" href="/postDetail?id={{$collectPost->id}}" target="_blank">{{ $collectPost->title }}</a>
              <i>{{ $collectPost->user->username }}</i>
              <em>{{ $collectPost->visited }}阅/{{ $collectPost->comments }}答</em>
            </li>
          </ul>
          @endforeach
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
                    ,count: parseInt('{{ $collectPosts_count }}'),
                    limit: 10,
                    jump: function(obj, first){
                        //首次不执行
                        if(!first){
                            window.location.href = '{{ url('/user/index/myCollectPost?page=') }}'+obj.curr;
                            //do something
                        }
                    }

                });
            });
        });

    </script>
@endsection
<script>

</script>