@extends("home.layouts.head")
@section("content")
    <style>
        body {
            /*background: url("/images/lable/background/idea.jpg");*/
        }
        .post_title:hover {
            text-decoration: underline
        }

        .week_discuss_title:hover {
            text-decoration: underline
        }

        .lable:hover {
            text-decoration: underline
        }

        .dot {
            padding: 0px;
            width: 4px;
            height:4px;
            margin-left: 2px;
            margin-right: 2px;
            background-color: #A8A8A8;
        }
        .comment {
            border-radius:10px;
            font-size: small;
            font-weight:bold;
            text-align: center;
            color: white;
            width:28px;
            height: 18px;
            line-height: 18px;
            display: inline-block;
            background-color: #D1D1D1;
        }
        .label {
            padding-left: 5px;
            padding-right: 5px;
            border-radius:4px;
            font-size: small;
            text-align: center;
            color: #8F8F8F;
            width:auto;
            height: 20px;
            line-height: 20px;
            display: inline-block;
            background-color: #EEEEE0;
        }
        .comment:hover {
            background-color: #8F8F8F;
        }

        .col {
            border-radius:3px;
            text-align: center;
            color: black;
            width:46px;
            height: 24px;
            line-height: 24px;
            display: inline-block
        }
        .col:hover {
            background-color: #F5F5F5;
            color: black;
        }
        .col_selected {
            color: #F5F5F5;
            background-color: #122b40;
        }
    </style>
<div class="layui-container" style="margin-top: 55px;">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-md9">
      <div class="fly-panel" style="margin-bottom: 0; border-bottom: 1px solid #c2c2c2; border-radius:6px;">
        <div class="fly-panel-title fly-filter" style="padding-left:2px; background-color: #3D7878; border-bottom: 1px solid #EBEBEB; height: 86px" id="col_div">
            <a href="/member/chenchangjv" style="float: left" >
                <img src="/images/lable/{{explode('=',parse_url($_SERVER['REQUEST_URI'])['query'])[1]}}.jpg" class="layui-circle" style="margin-top: 8px; width: 70px;height: 70px;">
            </a>
            <div style="float: left">
                <a href="/" style="color:#d0d0d0;">DoIT</a> <span class="chevron" style="color:white; font-size: medium">›</span>
                <a style="color:white;" >{{$lable_name}}</a>
                <p style="color:white; padding-left: 8px; font-size: small; line-height: 120%;"><span style="margin-top: -20px">说出你的奇思妙想</span></p>
            </div>
            <div style="float: right">
                <span style="color: whitesmoke; font-size: small">主题总数:111 &bull;</span>
                <a href="javascript:void(0);" onclick="lableCollect({{ $lable_id }})"><span style="color: #d0d0d0; font-size: small" class="lable_collect">加入收藏</span></a>
            </div>
        </div>

        <ul class="lr flow-default fly-list" id="#flow_list">

        </ul>

      </div>
    </div>
    <div class="layui-col-md3">

        @if(Auth::guard('user')->check())
            <div class="fly-panel fly-rank fly-rank-reply" style="border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);">
                <div class="cell">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody>
                        <tr>
                            <td width="48" valign="top">
                                <a href="{{ url('authorInfo?id=').Auth::guard('user')->user()->id }}">
                                    <img src="{{ Auth::guard("user")->user()->avatar }}" style="margin-top: 10px; margin-left: 10px; width: 49px;height: 49px; border-radius:4px;">
                                </a>
                            </td>
                            <td width="10" valign="top"></td>
                            <td width="auto" align="left">
                            <span class="bigger">
                                <a style="color: #999999" href="{{ url('authorInfo?id=').Auth::guard('user')->user()->id }}">{{ Auth::guard("user")->user()->username }}</a>
                            </span>
                            <br>
                            @if(Auth::guard("user")->user()->verified == 0)
                                <span style="font-size: small; color: #F08080">
                                    注册成功，请前往注册邮箱激活帐户
                                </span>
                            @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="sep10"></div>
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody>
                        <tr>
                            <td width="33%" align="center">
                                <a href="{{ url('/user/index/myCollectLabel') }}" class="dark" style="display: block;">
                                    <span class="bigger">{{ Auth::guard('user')->user()->lable_collect ? count(explode(',', Auth::guard('user')->user()->lable_collect))-1 : 0}}</span>
                                    <div class="sep3"></div>
                                    <span class="fade" style="color: #999999">标签收藏</span>
                                </a>
                            </td>
                            <td width="34%" style="border-left: 1px solid rgba(100, 100, 100, 0.4); border-right: 1px solid rgba(100, 100, 100, 0.4);" align="center">
                                <a href="{{ url('/user/index/myCollectPost') }}" class="dark" style="display: block;">
                                    <span class="bigger">{{ Auth::guard('user')->user()->collect ? count(explode(',', Auth::guard('user')->user()->collect))-1 : 0}}</span>
                                    <div class="sep3"></div>
                                    <span class="fade" style="color: #999999">话题收藏</span>
                                </a>
                            </td>
                            <td width="33%" align="center">
                                <a href="{{ url('/user/userFriend/follow') }}" class="dark" style="display: block;">
                                    <span class="bigger">{{ Auth::guard('user')->user()->follow->count() }}</span>
                                    <div class="sep3"></div>
                                    <span class="fade" style="color: #999999">特别关注</span>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                {{--<div class="cell">--}}
                {{--<div style="width: 250px; background-color: #f0f0f0; height: 3px; display: inline-block; vertical-align: middle;">--}}
                {{--<div style="width: 66px; background-color: #ccc; height: 3px; display: inline-block;"></div>--}}
                {{--</div>--}}
                {{--</div>--}}

                <div class="cell" style="padding: 5px;">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody>
                        <tr>
                            <td width="32"><a  href="/new"><i style="font-size: 20px; color: #218868;" class="layui-icon layui-icon-edit"></i> </a></td>
                            <td width="0"></td>
                            <td width="auto" valign="middle" align="left">
                                <a href="javascript:void(0);" onclick="publishPost({{ Auth::guard('user')->user()->verified }})">发表新话题</a>
                                <a href="{{ url('/user/userMessage') }}" class="fade" style="float: right">&nbsp;&nbsp;0未读消息</a>
                                <a href=""><i style="float: right; color: #218868;" class="layui-icon layui-icon-reply-fill"></i></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else

        @endif

      <dl class="fly-panel fly-list-one">
          <div class="box" id="TopicsHot" style="background-color: #fff;border-radius: 4px;border-bottom: 1px solid #e2e2e2;">
              <div class="cell" style="padding: 10px;font-size: 14px;line-height: 120%;text-align: left;border-bottom: 1px solid #e2e2e2;">
                  <span class="fade" style="color: #636363">今日热议主题</span>
              </div>
              @foreach($dayDiscussPost as $dayPost)
              <div class="cell from_180708 hot_t_487590" style="padding: 10px;font-size: 14px;line-height: 120%;text-align: left;border-bottom: 1px solid #e2e2e2;">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tbody>
                      <tr>
                          <td width="24" valign="middle" align="center">
                              <a href="/authorInfo?id={{$dayPost->user->id}}"><img src="{{ $dayPost->user->avatar }}" class="avatar" border="0" align="default" style="max-width: 24px; max-height: 24px;"></a>
                          </td>
                          <td width="10"></td>
                          <td width="auto" valign="middle">
                            <span class="item_hot_topic_title">
                            <a href="/postDetail?id={{$dayPost->id}}" class="week_discuss_title" style="color: #636363;font-size: 14px">{{ $dayPost->title }}</a>
                            </span>
                          </td>
                      </tr>
                      </tbody>
                  </table>
              </div>
              @endforeach
          </div>
      </dl>

        <div class="fly-panel" style="border-radius: 4px">
            <div id="cloud" style="width: 100%; height: 320px; border-radius: 4px">  </div>
        </div>

      {{--<div class="fly-panel fly-rank fly-rank-reply" id="LAY_replyRank">--}}
        {{--<h3 class="fly-panel-title">回贴周榜</h3>--}}
        {{--<dl>--}}
          {{--<!--<i class="layui-icon fly-loading">&#xe63d;</i>-->--}}
          {{--<dd>--}}
            {{--<a href="user/home.html">--}}
              {{--<img src="https://tva1.sinaimg.cn/crop.0.0.118.118.180/5db11ff4gw1e77d3nqrv8j203b03cweg.jpg"><cite>贤心</cite><i>106次回答</i>--}}
            {{--</a>--}}
          {{--</dd>--}}
        {{--</dl>--}}
      {{--</div>--}}


    </div>
  </div>
</div>
@endsection
<script type="text/javascript">
    
      function colSelected(e){
        $("#col_div").children(".col").removeClass("col_selected");
        e.addClass("col_selected");
      }

     function postOrder()
     {
        $(".order").removeClass("layui-this");
        $(this).addClass("layui-this");
     }

     function lableCollect(lable_id)
     {
        $.ajax({
            url: "{{ url('/user/collectLable') }}",
            data: {
                "lable_id": lable_id
            },
            dataType: "json",
            type: "GET",
            success: function(e) {
                $('.lable_collect').html(e);
            },
            error: function(e) {
                $('.lable_collect').html(e.responseJSON);
            }
        })
     }

      function publishPost(verified)
      {
          if(verified) {
              window.open("/post/addPostView");
          } else {
              layer.msg('请先激活帐户');
          }
      }

</script>