@extends("home.layouts.head")
@section("content")

<div class="layui-container fly-marginTop" style="margin-top: 55px;">
  <div class="fly-panel" pad20 style="padding-top: 5px;">
    <!--<div class="fly-none">没有权限</div>-->
    <div class="layui-form layui-form-pane">
      <div class="layui-tab layui-tab-brief" lay-filter="user">
        <ul class="layui-tab-title">
          <li class="layui-this">发表新帖<!-- 编辑帖子 --></li>
        </ul>
        <div class="layui-form layui-tab-content" id="LAY_ucm" style="padding: 20px 0;">
          <div class="layui-tab-item layui-show">
            <form action="{{ url('/post/updatePost') }}" method="post" class="layui-form" id="postEditForm">
                {{ csrf_field() }}
              <div class="layui-row layui-col-space15 layui-form-item">
                <div class="layui-col-md3">
                  <label class="layui-form-label">栏目</label>
                  <div class="layui-input-block">
                    <select name="col_id" id="col_" lay-filter="col_">
                      <option></option>
                      @foreach($cols as $col)
                        <option @if($col->id == $post->col_id) selected="selected" @endif value={{ $col->id }}>{{ $col->column_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="layui-col-md3">
                  <label class="layui-form-label">标签</label>
                  <div class="layui-input-block">
                    <select name="lable_id" class="lable_" lay-filter="lable_">
                      <option value="{{ $post->lable_id }}">{{ $post->lable->lable_name }}</option>
                    </select>
                  </div>
                </div>
                <div class="layui-col-md9">
                  <label for="L_title" class="layui-form-label">标题</label>
                  <div class="layui-input-block">
                    <input type="text" id="L_title" name="title" autocomplete="off" value="{{ $post->title }}" class="layui-input">
                    <!-- <input type="hidden" name="id" value=""> -->
                  </div>
                </div>
              </div>
              <div class="layui-form-item layui-form-text">
                <div class="layui-input-block">
                  <textarea class="layui-textarea" name="content" id="LAY_demo1" style="display: none">{{ $post->content }}</textarea>
                </div>
              </div>
              <input type="text" name="post_id" style="display: none" value="{{ $post->id }}">
              <div class="layui-form-item">
                  <button type="submit" class="layui-btn layui-btn-radius layui-btn-green" data-type="content">提交修改</button>
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
  $(document).ready(function(){

  });
</script>