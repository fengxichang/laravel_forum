@extends("home.layouts.head")
@section("content")

<div class="layui-container fly-marginTop fly-user-main" style="margin-top: 55px;">
    {{--加载个人置侧边栏--}}
  @include("home.layouts.set_navbar")

  <div class="fly-panel fly-panel-user" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);" pad20>

    <div class="layui-tab layui-tab-brief" lay-filter="user">
        <ul class="layui-tab-title" id="LAY_mine">
            <li data-type="mine-jie" lay-id="index">
                <a href="{{ url('/user/index') }}">发表的帖(<span>{{$myPost_count}}</span>)</a>
            </li>
            <li data-type="collection"  data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myComment') }}">最近回答（<span>{{ $myComments_count }}</span>）</a>
            </li>
            <li data-type="collection" data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myCollectPost') }}">收藏的帖(<span>{{ $collectPosts_count }}</span>)</a>
            </li>
            <li data-type="collection" class="layui-this" data-url="/collection/find/" lay-id="collection">
                <a href="{{ url('/user/index/myCollectLabel') }}">关注的标签（<span>{{ $lables_count }}</span>）</a>
            </li>
        </ul>

      <div class="layui-tab-content" style="padding: 20px 0;">

        <div class="layui-tab-item layui-show index_item4">
          @foreach($lables as $lable)
            <ul class="mine-view jie-row">
              <li>
                <a href="" class="jie-title">{{ $lable->lable_name }}</a>
                  <a style="float: right" onclick="cancelLable('{{ $lable->id }}')"><button class="layui-btn layui-btn-xs" >取消</button></a>
              </li>
              <!-- <div class="fly-none" style="min-height: 50px; padding:30px 0; height:auto;"><i style="font-size:14px;">没有发表任何求解</i></div> -->
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

                //执行一个laypage实例
                laypage.render({
                    elem: 'LAY_page' //注意，这里的 test1 是 ID，不用加 # 号
                    ,count: 20,
                    limit: 10,
                    jump: function(obj, first){
                        //obj包含了当前分页的所有参数，比如：
                        console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
                        console.log(obj.limit); //得到每页显示的条数

                        //首次不执行
                        if(!first){
                            //do something
                        }
                    }

                });
            });
        });

    </script>
@endsection
<script>
    function cancelLable(lable_id)
    {
        $.ajax({
            url: "{{ url('/user/cancleLabel?lable_id=') }}"+lable_id,
            dataType: 'json',
            type: 'GET',
            success: function(e) {
                layer.msg('取消关注成功');
                location.reload();
            },
            error: function(e) {
                layer.msg('网络错误');
            }
        })
    }

</script>