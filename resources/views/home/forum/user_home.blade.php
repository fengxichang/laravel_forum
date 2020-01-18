@extends("home.layouts.head")
@section("content")

<div class="layui-container" style="margin-top: 55px;">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-md3 fly-home-jie">
      <div class="fly-panel fly-rank fly-rank-reply" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);">
        <div class="cell">
          <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tbody>
            <tr>
              <td width="48" valign="top">
                <a href="javascript:void(0);">
                  <img src="{{ $author->avatar }}" style="margin-top: 10px; margin-left: 10px; width: 76px;height: 76px; border-radius:4px;">
                </a>
              </td>
              <td width="20" valign="top"></td>
              <td width="auto" align="left">
                <span class="bigger" style="font-size: x-large">
                   <a style="color: #999999" href="/member/neilfeng">{{ $author->username }}</a>
                  <i class="fa fa-mars" aria-hidden="true"></i>
                </span>
              </td>
            </tr>
            </tbody>
          </table>
          {{--<hr>--}}
          <br>
          <div class="sep10"></div>
          <table cellpadding="0" cellspacing="0" border="0" height="30px" width="100%">
            <tbody>
            <tr style="margin-top: 8px">
              <td width="10" valign="top">
              </td>
              <td width="auto" align="left">
                <span class="bigger" style="font-size: 15px">
                   注册于 {{ date('Y-m-d',strtotime($author->created_at)) }}
                </span>
                &nbsp;&nbsp;
                <span class="bigger" style="font-size: 15px;color: #999999">
                   第{{ $author->id }}位会员
                </span>
              </td>
              <td width="10" valign="top"></td>
            </tr>
            </tbody>
          </table>
          <hr>
          <div class="sep10"></div>
          <table cellpadding="0" cellspacing="0" border="0" height="80px" width="100%">
            <tbody>
            <tr>
              <td width="33%" align="center">
                <a href="javascript:void(0)" class="dark" style="display: block;">
                  <span class="bigger">{{$author->post->count()}}</span>
                  <div class="sep3"></div>
                  <span class="fade" style="color: #999999">话题</span>
                </a>
              </td>
              <td width="34%" align="center">
                <a href="javascript:void(0)" class="dark" style="display: block;">
                  <span class="bigger">{{ $follow_count }}</span>
                  <div class="sep3"></div>
                  <span class="fade" style="color: #999999">关注者</span>
                </a>
              </td>
              <td width="33%" align="center">
                <a href="javascript:void(0)" class="dark" style="display: block;">
                  <span class="bigger">{{ $author->collect? count(explode(',',$author->collect))-1 : 0 }}</span>
                  <div class="sep3"></div>
                  <span class="fade" style="color: #999999">收藏话题</span>
                </a>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
        <hr>

        <div class="cell" style="padding: 5px;">
          <table cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate; border-spacing:0px 6px;" height="40px" width="100%">
            <tbody>
            <tr>
              <td width="11"></td>
              <td width="78">
                <a class="layui-btn layui-btn-primary layui-btn-sm layui-btn-radius" onclick="showTips(this,'{{ $author->city }}')"  href="javascript:void(0)">城市</a>
                &nbsp;
                <a class="layui-btn layui-btn-primary layui-btn-sm layui-btn-radius" onclick="showTips(this,'{{ $author->profession }}')"  href="javascript:void(0)">职业</a>
                &nbsp;
                <a class="layui-btn layui-btn-primary layui-btn-sm layui-btn-radius" onclick="showTips(this,'{{ $author->university }}')" href="javascript:void(0)">学校</a>
              </td>
              <td width="11" valign="middle" align="left">

              </td>
            </tr>
            @if($author->id !== Auth::guard('user')->user()->id)
                <tr>
                  <td width="11"></td>
                  <td width="78">
                    <a  class="follow_author layui-btn layui-btn-primary layui-btn-sm layui-btn-radius" onclick="_follow({{ $author->id }})" href="javascript:void(0)">
                      @if(empty($_follows))
                        关注他
                      @else
                        取消关注
                      @endif
                    </a>
                    &nbsp;
                      @if($author->is_friend == 1)
                          <a class="layui-btn layui-btn-primary layui-btn-sm layui-btn-radius" onclick="sendPrivateMessage({{ $author->id }})"  href="javascript:void(0);">发送私信</a>
                      @else
                          <a class="layui-btn layui-btn-primary layui-btn-sm layui-btn-radius" onclick="addFriendApply({{ $author->id }})"  href="javascript:void(0);">加为好友</a>
                      @endif
                  </td>
                  <td width="11"></td>
                </tr>
            @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="layui-col-md9 fly-home-jie" >
      <div class="fly-panel fly-panel-user" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);" pad20>

        <div class="layui-tab layui-tab-brief" lay-filter="user">
          <ul class="layui-tab-title" id="LAY_mine">
            <li data-type="mine-jie" lay-id="index" class="layui-this">发表的帖(<span>{{$author->post->count()}}</span>)</li>
            <li data-type="collection" data-url="/collection/find/" lay-id="collection">最近回答（<span>{{ $author->comment->count() }}</span>）</li>
            <li data-type="collection" data-url="/collection/find/" lay-id="collection">收藏的帖(<span>{{ $author->collect? count(explode(',',$author->collect))-1 : 0 }}</span>)</li>
            <li data-type="collection" data-url="/collection/find/" lay-id="collection">关注的标签（<span>{{ $author->lable_collect? count(explode(',', $author->lable_collect))-1 : 0 }}</span>）</li>

          </ul>
          <div class="layui-tab-content" style="padding: 20px 0;min-height: 500px">
            <div class="layui-tab-item layui-show">
              <ul class="mine-view jie-row">
                @foreach($author->post as $myPost)
                  <li>
                    <a class="jie-title" href="/postDetail?id={{$myPost->id}}" target="_blank">{{ $myPost->title }}</a>
                    <i>{{ date('Y-m-d', strtotime($myPost->created_at)) }}</i>
                    <span></span>
                    <i>{{ $myPost->visited }}阅 / {{ $myPost->comments }}回</i>
                    <span></span>
                  </li>
                @endforeach
              </ul>
              <div id="LAY_page"></div>
            </div>

            <div class="layui-tab-item">
              <div class="fly-panel">
                <ul class="home-jieda ">
                  @foreach($author->comment as $myComment)
                    <li>
                      <p>
                        <span>{{ $myComment->created_at }}</span>
                        @if(!empty($myComment->height_id))
                          <a>{{ $myComment->comment_username }}</a>：
                        @else
                          在<a>{{ $myComment->reply_for }}</a>中回复
                        @endif
                        &nbsp;&nbsp;

                      </p>
                      <div class="home-dacontent">
                        {{ $myComment->content }}
                      </div>

                    </li>
                @endforeach
                <!-- <div class="fly-none" style="min-height: 50px; padding:30px 0; height:auto;"><span>没有回答任何问题</span></div> -->
                </ul>
              </div>
            </div>

            <div class="layui-tab-item">

                <ul class="mine-view jie-row" >
                    @foreach($collect_posts as $collectPost)
                      <li>
                        <a class="jie-title" href="/postDetail?id={{$collectPost->id}}" target="_blank">{{ $collectPost->title }}</a>
                        <i>{{ $collectPost->user->username }}</i>
                        <em>{{ $collectPost->visited }}阅/{{ $collectPost->comments }}答</em>
                      </li>
                    @endforeach
                </ul>

              <div id="LAY_page1"></div>
            </div>

              <div class="layui-tab-item">

                      <ul class="mine-view jie-row" >
                          @foreach($collect_lables as $lable)
                              <li>
                                  <a href="" class="jie-title">{{ $lable->lable_name }}</a>
                                  {{--<a class="mine-edit" href="/jie/edit/8116">取消</a>--}}
                              </li>
                          <!-- <div class="fly-none" style="min-height: 50px; padding:30px 0; height:auto;"><i style="font-size:14px;">没有发表任何求解</i></div> -->
                          @endforeach
                      </ul>

                  <div id="LAY_page1"></div>
              </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script>
  $(document).ready(function(){
  });

  function showTips(_this, _tips)
  {
      var tips = _tips;
      if(!tips) {
          tips = "资料没完善";
      }
      layer.tips(tips, _this, {
          tips: [1, '#3595CC'],
          time: 2000
      });
  }

  function _follow(id)
  {
      $.ajax({
          url: "{{ url('/user/followAuthor') }}",
          data: {'id': id},
          dataType: "JSON",
          type: "get",
          success: function(e) {
            if(e.status === 1) {
              $('.follow_author').html('取消关注');
              layer.msg('关注成功');
            } else if(e.status === 0) {
                $('.follow_author').html('关注他');
                layer.msg('取消关注成功');
            }
          },
          error: function(e) {
              if(e.statusText == "Unauthorized") {
                  window.location.href = "/login_view";
                  return false;
              }
              layer.msg('网络错误');
          }
      })
  }

  //发送添加好友申请
  function addFriendApply(id)
  {
      @if(Auth::guard('user')->check())
      var verified = "{{ Auth::guard('user')->user()->verified }}";
      if(verified === "0") {
          layer.msg('请先激活帐户');
          return false;
      }
      $.ajax({
          url: "{{ url('/user/addFriendApply') }}",
          dataType: "json",
          data: {"id": id},
          type: "get",
          success: function(e) {
            layer.msg(e.info);
          },
          error: function(e) {
              if(e.statusText == "Unauthorized") {
                  layer.msg('请先登陆');
                  return false;
              }
              layer.msg("网络错误");
          }
      })
      @else
          window.location.href = "/login_view";
      @endif
  }

  //发送私信
  function sendPrivateMessage(id)
  {
      send_message = layer.open({
          type: 2,
          title: "发送消息",
          shade: ['0.1'],
          closeBtn: 1,
          shadeClose: true,
          area: ['360px', '392px'],
          skin: 'layui-layer-lan',//加上边框
          content: ["{{ url('/user/showPrivateMessageView?id=') }}"+id]
      });
  }
</script>