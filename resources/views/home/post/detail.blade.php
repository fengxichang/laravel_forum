@extends("home.layouts.head")
@section("content")
  @inject("oAuth",'Illuminate\Support\Facades\Auth')
  <style>
      a.tb:hover {
          background-color: rgba(255,255,255,.3);
          color: #000;
          text-decoration: none;
          border-radius: 15px;
      }
      .topic_buttons {
          padding: 5px;
          font-size: 12px;
          line-height: 120%;
          background: #eee;
          margin-bottom: 10px;
          background: -moz-linear-gradient(top,#eee 0,#ccc 100%);
          background: -webkit-gradient(linear,left top,left bottom,color-stop(0,#eee),color-stop(100%,#ccc));
          background: -webkit-linear-gradient(top,#eee 0,#ccc 100%);
          background: -o-linear-gradient(top,#eee 0,#ccc 100%);
          background: -ms-linear-gradient(top,#eee 0,#ccc 100%);
          background: linear-gradient(to bottom,#eee 0,#ccc 100%);
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc', GradientType=0 );
          border-radius: 0 0 3px 3px;
          text-align: left;
      }
      .topic_stats {
          text-shadow: 0 1px 0 #fff;
          font-size: 11px;
          color: #999;
          line-height: 100%;
      }
      .fr {
          float: right;
          text-align: right;
      }
      a.tb:active, a.tb:link, a.tb:visited {
          font-size: 12px;
          line-height: 12px;
          color: #333;
          text-decoration: none;
          display: inline-block;
          padding: 3px 10px 3px 10px;
          border-radius: 15px;
          text-shadow: 0 1px 0 #fff;
      }

  </style>
<div class="layui-container" style="margin-top: 55px">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-md9 content detail">
      <div class="fly-panel detail-box" style="margin-bottom: 0px; border-top-left-radius:6px;border-top-right-radius:6px;border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;height: auto">
          <div class="header" style="border-bottom: 1px solid #EEEEE0; padding-bottom: 10px">
              <div class="fr" style="display: block; float:right">
                  <a href="/member/chenchangjv">
                      <img src="{{ $post->user->avatar }}" style="margin-top: -3px; width: 72px;height: 72px; border-radius:4px;">
                  </a>
              </div>
              <a style="color:#8C8C8C; font-size: medium" href="/colIndex?column_id={{ $post->col->id }}">{{ $post->col->column_name }}</a> <span class="chevron">&nbsp;›&nbsp;</span>
              <a style="color:#8C8C8C; font-size: medium" href="/lableIndex?id={{ $post->lable->id }}">{{ $post->lable->lable_name }}</a>
              <div class="sep10"></div>
              <h1 style="margin-top: 10px; margin-bottom: 10px">{{ $post->title }}</h1>
              {{--<div id="topic_487299_votes" class="votes">--}}
                  {{--<a href="javascript:" onclick="upVoteTopic(487299);" class="vote"><li class="fa fa-chevron-up"></li></a> &nbsp;--}}
                  {{--<a href="javascript:" onclick="downVoteTopic(487299);" class="vote"><li class="fa fa-chevron-down"></li></a>--}}
              {{--</div> &nbsp;--}}
              <small class="gray" style="color:#8C8C8C">
                  <a style="color:#8C8C8C" href="{{ url('/authorInfo?id=').$post->user->id }}">{{ $post->user->username }}</a>
                  ·
                  @if(time()-strtotime($post->created_at) > 60*60*24*30)
                      <span style="padding: 0px;font-size: 12px">1个月之前</span>
                  @elseif(time()-strtotime($post->created_at) > 60*60*24*7)
                      <span style="padding: 0px;font-size: 12px">1周前</span>
                  @elseif(time()-strtotime($post->created_at) > 60*60*24)
                      <span style="padding: 0px;font-size: 12px">{{ floor((time()-strtotime($post->created_at))/60/60/24) }}天前</span>
                  @elseif(time()-strtotime($post->created_at) > 60*60)
                      <span style="padding: 0px;font-size: 12px">{{ floor((time()-strtotime($post->created_at))/60/60) }}小时前</span>
                  @elseif(time()-strtotime($post->created_at) > 60)
                      <span style="padding: 0px;font-size: 12px">{{ floor((time()-strtotime($post->created_at))/60) }}分钟前</span>
                  @else
                      <span style="padding: 0px">刚刚</span>
                  @endif
                   · {{ $post->visited }} 次点击
              </small>
          </div>

        <div id="hidePost" style="display: none; ">  {{ $post->content }} </div>
        <div class="detail-body photos" id="postBody" style="margin-top: 10px;margin-bottom: 10px; min-height: 30px"></div>
          <input type="hidden" value="{{ $post->id }}" id="post_id">
          {{--<fieldset class="layui-elem-field layui-field-title" style="text-align: center;margin-bottom: 0px">--}}
              {{--<legend></legend>--}}
          {{--</fieldset>--}}
    </div>
        <div class="topic_buttons">
            <div class="fr topic_stats" style="padding-top: 4px;float: right">{{ $post->praise }} 次点赞 &nbsp;∙&nbsp; {{ $post->collects }} 人收藏 &nbsp; </div>
            <a href="javascript:void(0);" onclick="collectPost({{ $post->id }})" class="tb collect_post">加入收藏</a>
            &nbsp;
            <a href="javascript:void(0)"  class="tb comment_btn" onclick="comment_btn()">发表评论</a>
            &nbsp;
            <a class="tb" lay-filter="*" lay-submit id="post_praise" href="javascript:void(0);">给他点赞</a>
        </div>
      <div class="fly-panel detail-box" id="flyReply" style="margin-bottom: 0; border-bottom: 1px solid #c2c2c2; border-radius:6px; height: auto">

          @if(!empty(Auth::guard("user")->user()->id))
              <div class="layui-form layui-form-pane post_comment" style="display: none" id="comment_form">
                  <form action="{{url('/saveComment')}}" method="post" id="commentForm">
                      {{--<input type="hidden" name="_token" value="{{csrf_token()}}"/>--}}
                      <input type="hidden" name="post_id" value="{{ $post->id }}">
                      <input type="hidden" name="user_id" value="{{ Auth::guard("user")->user()->id }}">
                      <div class="layui-form-item layui-form-text">
                          <a name="comment"></a>
                          <div class="layui-input-block" id="comment_input">
                              <textarea id="L_content" name="content" required lay-verify="required" placeholder="请输入内容"  class="layui-textarea fly-editor" style="border-radius:4px; height: 80px;"></textarea>
                          </div>
                      </div>
                      <div class="layui-form-item">
                          <button class="layui-btn layui-btn-sm" lay-filter="*" lay-submit id="sub">提交回复</button>
                          <button class="layui-btn layui-btn-sm" lay-filter="*" onclick="cancelComment_btn()">取消</button>
                      </div>
                  </form>
              </div>
          @else
              <div class="layui-form-item post_comment" style="display: none">
                  <a href="{{ url('/login_view') }}"><button class="layui-btn" lay-filter="*" lay-submit>登陆发表回复</button></a>
              </div>
          @endif

          <div>评论列表</div>
              <hr>
            <ul class="jieda" id="comment">
                @foreach($post->comment as $comment)
                    {{--<input type="hidden" value="{{ $comment->id }}" id="comment_id">--}}
                    <li data-id="111" class="jieda-daan" class="{{ $comment->id }}" style="padding-top: 10px; border-bottom: 1px solid #DFDFDF;">
                        <a name="item-1111111111"></a>
                        <div class="detail-about detail-about-reply" style="padding-left:40px; margin-bottom: -10px">
                            <div class="fly-detail-user" style="font-size: smaller">
                                <img class="layui-circle fly-avatar" src="{{ $comment->user->avatar }}" alt=" " style="width: 35px;height: 35px">
                                <a href="" class="fly-link" style="font-size: small">
                                    <cite>{{ $comment->user->username }}</cite>
                                </a>
                                @if($post->user_id == $comment->user_id)
                                    <span>(楼主)</span>
                                @endif
                                @if(!empty(Auth::guard("user")->user()->id))
                                    @if(Auth::guard("user")->user()->id == $comment->user_id)
                                        <span type="edit">编辑</span>
                                        <span type="del">删除</span>
                                        <!-- <span class="jieda-accept" type="accept">采纳</span> -->
                                    @endif
                                @endif
                            </div>
                            <span style="font-size: smaller">{{ date('Y-m-d',strtotime($comment->created_at)) }}</span>
                            <a href="" class="fly-link" style="font-size: small">
                                <cite>{{ $comment->comment_username }}</cite>
                            </a>
                            <div class="jieda-admin">
                                <a href="javascript:void(0)">
                                    <span class="" >
                                        <i class="fa fa-smile-o comment_praise" style="font-size:15px" onclick="commentPraise(this)" id="{{ $comment->id }}" ></i>
                                        <em class="{{ $comment->id }}">{{ $comment->praise }}</em>
                                    </span>
                                </a>
                                &nbsp;&nbsp;
                                <a href="#comment_input" onclick='showCommentInput("{{ $comment->id }}", "{{ $comment->user->username }}")'>
                                    <span type="reply">
                                        <i class="fa fa-commenting-o" style="font-size:15px"></i>
                                        <em class="">{{ $comment->comments }}</em>
                                    </span>
                                </a>
                            </div>

                        </div>
                        <div class="detail-body jieda-body photos" style="margin-bottom: 0px;margin-top: 10px">
                            <p style="margin-bottom: 0px";>{{ $comment->content }}</p>
                        </div>
                    </li>
                @if(Auth::guard('user')->check())
                    <div class="layui-form layui-form-pane _{{ $comment->id }}" style="display: none" >
                        <form action="{{ url('/saveReplyComment') }}" method="post" class="replyCommentForm">
                            {{--<input type="hidden" name="_token" value="{{csrf_token()}}"/>--}}
                            <input type="hidden" name="comment_username" value="{{ $comment->user->username }}">
                            <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                            <input type="hidden" name="parent_id" value="{{ $comment->parent_id }}">
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <input type="hidden" name="user_id" value="{{ Auth::guard("user")->user()->id }}">
                            <div class="layui-form-item layui-form-text">
                                <a name="comment"></a>
                                <div class="layui-input-block" id="comment_input">
                                    <textarea name="_content" required lay-verify="required" placeholder="回复{{ $comment->user->username }}"  class="layui-textarea fly-editor" style="border-radius:4px; height: 80px;"></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <button class="layui-btn layui-btn-sm" lay-filter="*" lay-submit id="reply_comment_sub">提交回复</button>
                                <button type="button" class="layui-btn layui-btn-sm" lay-filter="*" onclick='cancelComment("{{ $comment->id }}")'>取消</button>
                            </div>
                        </form>
                    </div>
                @else

                @endif
                @endforeach
            </ul>

        </div>
    </div>
      <div class="layui-col-md3">

          <div class="fly-panel fly-rank fly-rank-reply" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);">
              <div class="cell">
                  <table cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tbody>
                      <tr>
                          <td width="48" valign="top">
                              <a href="{{ url('/authorInfo?id=').$post->user->id }}">
                                  <img src="{{ $post->user->avatar }}" style="margin-top: 10px; margin-left: 10px; width: 49px;height: 49px; border-radius:4px;">
                              </a>
                          </td>
                          <td width="10" valign="top"></td>
                          <td width="auto" align="left">
                            <span class="bigger">
                                <a style="color: #999999" href="{{ url('/authorInfo?id=').$post->user->id }}">{{ $post->user->username }}</a>
                            </span>

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
                              <a href="{{ url('/authorInfo?id=').$post->user->id }}" class="dark" style="display: block;">
                                  <span class="bigger">1</span>
                                  <div class="sep3"></div>
                                  <span class="fade" style="color: #999999">帖子总数</span>
                              </a>
                          </td>
                          <td width="34%" style="border-left: 1px solid rgba(100, 100, 100, 0.4); border-right: 1px solid rgba(100, 100, 100, 0.4);" align="center">
                              <a href="{{ url('/authorInfo?id=').$post->user->id }}" class="dark" style="display: block;">
                                  <span class="bigger">0</span>
                                  <div class="sep3"></div>
                                  <span class="fade" style="color: #999999">被收藏数</span>
                              </a>
                          </td>
                          <td width="33%" align="center">
                              <a href="{{ url('/authorInfo?id=').$post->user->id }}" class="dark" style="display: block;">
                                  <span class="bigger">1</span>
                                  <div class="sep3"></div>
                                  <span class="fade" style="color: #999999">粉丝</span>
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
                          <td width="32">
                              <a  href="/new">
                                  <i style="font-size: 20px; color: #218868;" class="layui-icon layui-icon-friends"></i>
                              </a>
                          </td>
                          <td width="0"></td>
                          <td width="auto" valign="middle" align="left">
                              <a href="javascript:void(0);" onclick="addFriendApply({{ $post->user->id }})">加为好友</a>
                              <a href="javascript:void(0);" class="fade" style="float: right" onclick="sendPrivateMessage({{ $post->user->id }})">&nbsp;&nbsp;发送私信</a>
                              <a href=""><i style="float: right; color: #218868;" class="layui-icon layui-icon-reply-fill"></i></a>
                          </td>
                      </tr>
                      </tbody>
                  </table>
              </div>
          </div>

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


    function comment_btn()
    {
        @if(Auth::guard('user')->check())
            var verified = "{{ Auth::guard('user')->user()->verified }}";
            if(verified === "0") {
                layer.msg('请先激活帐户');
                return;
            } else {
                $(".post_comment").show();
            }
        @else
            window.location.href = "/login_view";
        @endif
    }

    function cancelComment_btn()
    {
        $(".post_comment").hide();
    }

    function showCommentInput(comment_id)
    {
       $("._"+comment_id).show();
    }

    function cancelComment(comment_id)
    {
        $("._"+comment_id).hide();
    }

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
                if(e.responseJSON.message === "Unauthenticated.") {
                    window.location.href = "/login_view";
                }
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
            content: ["{{ url('/showCommentList?comment_id=') }}"+comment_id]
        });
        // layer.full(index);
    }

    function collectPost(post_id)
    {
        $.ajax({
            url: "{{ url('/post/collectPost') }}",
            type: "GET",
            data: {
              'post_id': post_id
            },
            dtaType: "JSON",
            success: function(e) {
                $(".collect_post").html(e);
            },
            error: function(e) {
                window.location.href = "/login_view";
            }
        })
    }

    //发送私信
    function sendPrivateMessage(id)
    {
        @if(Auth::guard('user')->check())
        var verified = "{{ Auth::guard('user')->user()->verified }}";
        if(verified === "0") {
            layer.msg('请先激活帐户');
            return;
        }
        $.ajax({
            url: "{{ url('/post/isFriend') }}",
            dataType: 'json',
            data: {'author_id': id},
            type: 'get',
            success: function(e) {
                send_message = layer.open({
                    type: 2,
                    title: "发送消息",
                    shade: ['0.1'],
                    closeBtn: 1,
                    shadeClose: true,
                    area: ['360px', '465px'],
                    skin: 'layui-layer-lan',//加上边框
                    content: ["{{ url('/user/showPrivateMessageView?id=') }}"+id]
                });
            },
            error: function(e) {
                layer.msg(e.responseJSON);
            }
        });
        @else
            window.location.href = "/login_view";
        @endif
    }

    //发送添加好友申请
    function addFriendApply(id)
    {
        @if(Auth::guard('user')->check())
                var verified = "{{ Auth::guard('user')->user()->verified }}";
                if(verified === "0") {
                    layer.msg('请先激活帐户');
                    return;
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
                layer.msg("网络错误");
            }
        });
        @else
            window.location.href = "/login_view";
        @endif
    }

</script>