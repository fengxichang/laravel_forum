@extends("home.layouts.head")
@section("content")

<div class="layui-container fly-marginTop" style="margin-top: 55px;">
  <div class="fly-panel" pad20 style="padding-top: 5px; border-radius:6px;">
    <!--<div class="fly-none">没有权限</div>-->
    <div class="layui-form layui-form-pane">
      <div class="layui-tab layui-tab-brief" lay-filter="user">
        <ul class="layui-tab-title">
          <li class="layui-this">发表新帖<!-- 编辑帖子 --></li>
        </ul>
        <div class="layui-form layui-tab-content" id="LAY_ucm" style="padding: 20px 0;">
          <div class="layui-tab-item layui-show">
            <form action="{{ url('/post/createPost') }}" method="post" class="layui-form" id="textForm" onsubmit="return check()">
                {{ csrf_field() }}
              <div class="layui-row layui-col-space15 layui-form-item">
                <div class="layui-col-md3">
                  <label class="layui-form-label">栏目</label>
                  <div class="layui-input-block">
                    <select name="col_id" id="col_" lay-filter="col_">
                      <option></option>
                      @foreach($cols as $col)
                        <option value={{ $col->id }}>{{ $col->column_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="layui-col-md3">
                  <label class="layui-form-label">标签</label>
                  <div class="layui-input-block">
                    <select name="lable_id" class="lable_" lay-filter="lable_">
                      <option></option>
                    </select>
                  </div>
                </div>
                <div class="layui-col-md9">
                  <label for="L_title" class="layui-form-label">标题</label>
                  <div class="layui-input-block">
                    <input type="text" id="L_title" name="title" autocomplete="off" class="layui-input">
                    <!-- <input type="hidden" name="id" value=""> -->
                  </div>
                </div>
              </div>
              <div class="layui-form-item layui-form-text">
                <div class="layui-input-block">
                  <textarea class="layui-textarea" name="content" id="LAY_demo1" style="display: none"></textarea>
                </div>
              </div>
              <div class="layui-form-item">
                  <button class="layui-btn layui-btn-radius layui-btn-green" id="btn1" data-type="content">立即发布</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

<script type="text/javascript">

  function check()
  {
      if($("#col_ option:selected").val() === '') {
          layer.msg('栏目不能为空');
          return false;
      }
      if($("#L_title").val() === '') {
          layer.msg('标题不能为空');
          return false;
      }
       if($("#LAY_demo1").val() === '') {
           layer.msg('内容不能为空');
           return false;
       }
  }
</script>