@extends("home.layouts.head")
@section("content")

<div class="layui-container fly-marginTop fly-user-main" style="margin-top: 55px;">
    {{--加载个人置侧边栏--}}
  @include("home.layouts.set_navbar")

  <div class="fly-panel fly-panel-user" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);" pad20>

    <div class="layui-tab layui-tab-brief" lay-filter="user">
      <ul class="layui-tab-title" id="LAY_mine">
        <li class="layui-this" lay-id="info">我的资料</li>
        <li lay-id="avatar">头像</li>
        <li lay-id="pass">密码</li>
        <li lay-id="bind">帐号绑定</li>
      </ul>
      <div class="layui-tab-content" style="padding: 20px 0;">
          {{--基本设置--}}
        <div class="layui-form layui-form-pane layui-tab-item layui-show">
          <form method="post" action="{{ url('/user/set') }}" id="user_set_form">
              <input type="hidden" name="_token" value="{{csrf_token()}}"/>
            <div class="layui-form-mid layui-word-aux">如果您在邮箱已激活的情况下变更了邮箱，需重新验证邮箱。</div>
            <div class="layui-form-item">
              <label for="L_email" class="layui-form-label">邮箱</label>
              <div class="layui-input-inline">
                <input type="text" id="L_email" name="email" required lay-verify="email" autocomplete="off" value="{{ $ouser->email }}" class="layui-input">
              </div>

            </div>
            <div class="layui-form-item">
              <label for="L_username" class="layui-form-label">昵称</label>
              <div class="layui-input-inline">
                <input type="text" id="L_username" name="username" required lay-verify="required" autocomplete="off" value="{{ $ouser->username }}" class="layui-input">
              </div>
                {{--<div class="layui-form-mid layui-word-aux" id="reset_username_fail_msg"></div>--}}
            </div>
              <div class="layui-form-item">
                <label class="layui-form-label">选择框</label>
                <div class="layui-input-inline">
                  <select lay-verify="required" name="gender">
                    <option value=""></option>
                    <option @if($ouser->gender=="0")selected="selected"@endif value="0">男</option>
                    <option @if($ouser->gender=="1")selected="selected"@endif value="1">女</option>

                  </select>
                </div>
              </div>
            <div class="layui-form-item">
              <label for="L_city" class="layui-form-label">城市</label>
              <div class="layui-input-inline">
                <input type="text" id="L_city" name="city" autocomplete="off" value="{{ $ouser->city }}" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label for="L_city" class="layui-form-label">职业</label>
              <div class="layui-input-inline">
                <input type="text" id="L_profession" name="profession" autocomplete="off" value="{{ $ouser->profession }}" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item">
              <label for="L_city" class="layui-form-label">学校</label>
              <div class="layui-input-inline">
                <input type="text" id="L_university" name="university" autocomplete="off" value="{{ $ouser->university }}" class="layui-input">
              </div>
            </div>
            <div class="layui-form-item layui-form-text">
              <label for="L_sign" class="layui-form-label">签名</label>
              <div class="layui-input-block">
                <textarea placeholder="随便写些什么刷下存在感" id="L_sign"  name="introduce" autocomplete="off" class="layui-textarea" style="height: 80px;" value="">{{ $ouser->introduce }}</textarea>
              </div>
            </div>
            <div class="layui-form-item">
              <button class="layui-btn" key="set-mine" lay-filter="*" lay-submit>确认修改</button>
                <div class="layui-form-mid layui-word-aux" id="reset_username_success_msg"></div>
            </div>
              </form>
          </div>
          {{--更换头像--}}
          <div class="layui-form layui-form-pane layui-tab-item">
              <div class="layui-form-item">
                  <input type="hidden" name="tag_token" class="tag_token" value="<?php echo csrf_token(); ?>">
                  <div class="avatar-add layui-upload-list" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);">
                      <p>建议尺寸168*168，支持jpg、png、gif，最大不能超过50KB</p>
                      <button type="button" class="layui-btn upload-img" id="test1">
                          <i class="layui-icon">&#xe67c;</i>上传头像
                      </button>
                      <img class="layui-upload-img" src="{{ Auth::guard('user')->user()->avatar }}" id="demo1">
                      <span class="loading"></span>
                  </div>
              </div>
          </div>
          {{--更改密码--}}
          <div class="layui-form layui-form-pane layui-tab-item">
            <form action="{{ url('/user/changePassword') }}" method="post" id="changePasswordForm">
                {!! csrf_field() !!}
              <div class="layui-form-item">
                <label for="L_nowpass" class="layui-form-label">当前密码</label>
                <div class="layui-input-inline">
                  <input type="password" id="L_nowpass" name="oldPassword" required lay-verify="required" autocomplete="off" class="layui-input">
                </div>
                  {{--<div class="layui-form-mid layui-word-aux" id="oldPwdWrong"></div>--}}
              </div>
              <div class="layui-form-item">
                <label for="L_pass" class="layui-form-label">新密码</label>
                <div class="layui-input-inline">
                  <input type="password" id="L_pass" name="newPassword" required lay-verify="required" autocomplete="off" class="layui-input">
                </div>
                {{--<div class="layui-form-mid layui-word-aux">6到16个字符</div>--}}
              </div>
              <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">确认密码</label>
                <div class="layui-input-inline">
                  <input type="password" id="L_repass" name="reNewPassword" required lay-verify="required" autocomplete="off" class="layui-input">
                </div>
                  <div class="layui-form-mid layui-word-aux" id="reNewWrong"></div>
              </div>
              <div class="layui-form-item">
                <button class="layui-btn" key="set-mine" lay-filter="*" lay-submit>确认修改</button>
              </div>
            </form>
          </div>
          {{--更改绑定--}}
          <div class="layui-form layui-form-pane layui-tab-item">
            <ul class="app-bind">
              <li class="fly-msg app-havebind">
                <i class="iconfont icon-qq"></i>
                <span>已成功绑定，您可以使用QQ帐号直接登录Fly社区，当然，您也可以</span>
                <a href="javascript:;" class="acc-unbind" type="qq_id">解除绑定</a>
                
                <!-- <a href="" onclick="layer.msg('正在绑定微博QQ', {icon:16, shade: 0.1, time:0})" class="acc-bind" type="qq_id">立即绑定</a>
                <span>，即可使用QQ帐号登录Fly社区</span> -->
              </li>
              <li class="fly-msg">
                <i class="iconfont icon-weibo"></i>
                <!-- <span>已成功绑定，您可以使用微博直接登录Fly社区，当然，您也可以</span>
                <a href="javascript:;" class="acc-unbind" type="weibo_id">解除绑定</a> -->
                
                <a href="" class="acc-weibo" type="weibo_id"  onclick="layer.msg('正在绑定微博', {icon:16, shade: 0.1, time:0})" >立即绑定</a>
                <span>，即可使用微博帐号登录Fly社区</span>
              </li>
            </ul>
          </div>
      </div>

    </div>
  </div>
</div>
@endsection
<script>

</script>