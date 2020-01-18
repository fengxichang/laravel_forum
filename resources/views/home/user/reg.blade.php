
@extends("home.layouts.head")
@section("content")
  <style>
  </style>
<div class="layui-container fly-marginTop" style="margin-top: 55px;width:100%;">
  <div class="fly-panel fly-panel-user back_" style="height:100%;width:100%;background-image: url('/images/lable/100.jpg');">
    <div class="layui-tab layui-tab-brief" lay-filter="user" style="width:90%;max-width: 360px;margin-left:auto;margin-right:auto">
      <ul class="layui-tab-title">
        <li></li>
        <li><a href="{{ url('/login_view') }}">登入</a></li>
        <li class="layui-this">注册</li>
      </ul>
      <div class="layui-form layui-tab-content" id="LAY_ucm" style="padding: 20px 0;">
        <div class="layui-tab-item layui-show">
          <div class="layui-form layui-form-pane">
            <form method="post" action="{{ url('/reg') }}" id="reg_form">
              <input type="hidden" name="_token" value="{{csrf_token()}}"/>
              <div class="layui-form-item" >
                {{--<label for="L_email" class="layui-form-label">邮箱</label>--}}
                <div class="layui-input-inline" style="margin: 0px; width:100%;max-width: 360px">
                  {{--<i class="fa fa-search" style="position:absolute; left:10px; top:10px"></i>--}}
                  <input type="text" id="L_email" placeholder="邮箱" style="border-radius: 4px;width:100%;" name="email" required lay-verify="email" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux" id="email_fail_msg" style="display: none;padding:0px"></div>
              </div>
              <div class="layui-form-item" >
                {{--<label for="L_username" class="layui-form-label">昵称</label>--}}
                <div class="layui-input-inline" style="margin: 0px; width:100%;max-width: 360px">
                  <input type="text" id="L_username" placeholder="用户名" name="username" style="border-radius: 4px;width:100%;" required lay-verify="required" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux" id="username_fail_msg" style="display: none;padding:0px"></div>
              </div>
              <div class="layui-form-item" >
                {{--<label for="L_pass" class="layui-form-label">密码</label>--}}
                <div class="layui-input-inline" style="margin: 0px;width:100%;max-width: 360px">
                  <input type="password" id="L_pass" placeholder="密码" name="password" style="border-radius: 4px;width:100%;max-width: 360px" required lay-verify="required" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux" id="password_fail_msg" style="display: none;padding:0px"></div>
              </div>
              <div class="layui-form-item" >
                {{--<label for="L_repass" class="layui-form-label">确认密码</label>--}}
                <div class="layui-input-inline" style="margin: 0px;width:100%;max-width: 360px">
                  <input type="password" id="L_repass" placeholder="确认密码" name="repassword" style="border-radius: 4px;width:100%;max-width: 360px" required lay-verify="required" autocomplete="off" class="layui-input">
                </div>
              </div>
              <div class="alert alert-danger col-sm-3" id="fail_message" style="height: 45px;display: none">

              </div>
              {{--<div class="layui-form-item">--}}
                {{--<label for="L_vercode" class="layui-form-label">人类验证</label>--}}
                {{--<div class="layui-input-inline">--}}
                  {{--<input type="text" id="L_vercode" name="vercode" required lay-verify="required" placeholder="请回答后面的问题" autocomplete="off" class="layui-input">--}}
                {{--</div>--}}
                {{--<div class="layui-form-mid">--}}
                  {{--<span style="color: #c00;">####</span>--}}
                {{--</div>--}}
              {{--</div>--}}
              <div class="layui-form-item" style="width:100%;max-width: 360px">
                <button class="layui-btn" lay-filter="*" lay-submit style="border-radius: 4px;width:100%;max-width: 360px">立即注册</button>
              </div>
              <div class="layui-form-item fly-form-app" style="padding-left: 100px">
                <span>使用Github账号注册</span>
                &nbsp;
                <a href="" style="font-size: large" onclick="layer.msg('正在通过Github登入', {icon:16, shade: 0.1, time:0})" title="Github登入">
                  <i class="fa fa-github" aria-hidden="true"></i>
                </a>
                {{--<a href="" style="font-size: large" onclick="layer.msg('正在通过QQ登入', {icon:16, shade: 0.1, time:0})" title="QQ登入">--}}
                  {{--<i class="fa fa-qq" aria-hidden="true"></i>--}}
                {{--</a>--}}
                {{--&nbsp;--}}
                {{--<a href="" style="font-size: large" onclick="layer.msg('正在通过微信登入', {icon:16, shade: 0.1, time:0})" title="微博登入">--}}
                  {{--<i class="fa fa-weixin" aria-hidden="true"></i>--}}
                {{--</a>--}}
                {{--&nbsp;--}}
                {{--<a href="" style="font-size: large" onclick="layer.msg('正在通过微博登入', {icon:16, shade: 0.1, time:0})" title="微博登入">--}}
                  {{--<i class="fa fa-weibo" aria-hidden="true"></i>--}}
                {{--</a>--}}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
<script></script>