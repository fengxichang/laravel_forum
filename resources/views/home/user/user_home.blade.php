@extends("home.layouts.head")
@section("content")



<div class="layui-container" style="margin-top: 55px;">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-md2 fly-home-jie">
      <div class="fly-home fly-panel" >
        <img src="{{ Auth::guard('user')->user()->avatar }}" alt="贤心">
        <h1>
          {{ Auth::guard("user")->user()->username }}
        </h1>

        <p style="padding: 10px 0; color: #5FB878;">认证信息：layui 作者</p>
        {{--<p class="fly-home-info">--}}
          {{--<i class="iconfont icon-kiss" title="飞吻"></i><span style="color: #FF7200;">66666 飞吻</span>--}}
        {{--</p>--}}
        <p>
          <i class="iconfont icon-shijian"></i><span>{{ Auth::guard("user")->user()->created_at }} 加入</span>
        </p>
        <p>
          <i class="iconfont icon-chengshi"></i><span>来自{{ Auth::guard("user")->user()->city }}</span>
        </p>

        <p class="fly-home-sign">{{ Auth::guard("user")->user()->introduce }}</p>

        <div class="fly-sns" data-user="">
          <a href="javascript:;" class="layui-btn layui-btn-radius layui-btn layui-btn-xs layui-btn-primary" data-type="addFriend">加为好友</a>
          <a href="javascript:;" class="layui-btn layui-btn-radius layui-btn layui-btn-xs layui-btn-primary" data-type="chat">发起会话</a>
        </div>

      </div>
    </div>
    <div class="layui-col-md5 fly-home-jie">
      <div class="fly-panel">
        <h3 class="fly-panel-title">贤心 最近的提问</h3>
        <ul class="jie-row">
          <li>
            <span class="fly-jing">精</span>
            <a href="" class="jie-title"> 基于 layui 的极简社区页面模版</a>
            <i>刚刚</i>
            <em class="layui-hide-xs">1136阅/27答</em>
          </li>
          <li>
            <a href="" class="jie-title"> 基于 layui 的极简社区页面模版</a>
            <i>1天前</i>
            <em class="layui-hide-xs">1136阅/27答</em>
          </li>
          <li>
            <a href="" class="jie-title"> 基于 layui 的极简社区页面模版</a>
            <i>2017-10-30</i>
            <em class="layui-hide-xs">1136阅/27答</em>
          </li>
          <li>
            <a href="" class="jie-title"> 基于 layui 的极简社区页面模版</a>
            <i>1天前</i>
            <em class="layui-hide-xs">1136阅/27答</em>
          </li>
          <li>
            <a href="" class="jie-title"> 基于 layui 的极简社区页面模版</a>
            <i>1天前</i>
            <em class="layui-hide-xs">1136阅/27答</em>
          </li>
          <li>
            <a href="" class="jie-title"> 基于 layui 的极简社区页面模版</a>
            <i>1天前</i>
            <em class="layui-hide-xs">1136阅/27答</em>
          </li>
          <li>
            <a href="" class="jie-title"> 基于 layui 的极简社区页面模版</a>
            <i>1天前</i>
            <em class="layui-hide-xs">1136阅/27答</em>
          </li>
          <!-- <div class="fly-none" style="min-height: 50px; padding:30px 0; height:auto;"><i style="font-size:14px;">没有发表任何求解</i></div> -->
        </ul>
      </div>
    </div>

    <div class="layui-col-md5 fly-home-da">
      <div class="fly-panel">
        <h3 class="fly-panel-title">贤心 最近的回答</h3>
        <ul class="home-jieda">
          <li>
          <p>
          <span>1分钟前</span>
          在<a href="" target="_blank">tips能同时渲染多个吗?</a>中回答：
          </p>
          <div class="home-dacontent">
            尝试给layer.photos加上这个属性试试：
<pre>
full: true         
</pre>
            文档没有提及
          </div>
        </li>
        <li>
          <p>
          <span>5分钟前</span>
          在<a href="" target="_blank">在Fly社区用的是什么系统啊?</a>中回答：
          </p>
          <div class="home-dacontent">
            Fly社区采用的是NodeJS。分享出来的只是前端模版
          </div>
        </li>
        
          <!-- <div class="fly-none" style="min-height: 50px; padding:30px 0; height:auto;"><span>没有回答任何问题</span></div> -->
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
