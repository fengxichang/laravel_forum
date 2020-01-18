<ul class="layui-nav layui-nav-tree layui-inline" lay-filter="user" style="border-radius: 4px; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);">
    {{--<li class="layui-nav-item">--}}
        {{--<a href="{{ url('/user/home') }}">--}}
            {{--<i class="layui-icon">&#xe609;</i>--}}
            {{--我的主页--}}
        {{--</a>--}}
    {{--</li>--}}
    <li class="layui-nav-item " id="user_set">
        <a href="{{ url('/user/set_view') }}">
            <i class="layui-icon">&#xe620;</i>
            基本设置
        </a>
    </li>

    <li class="layui-nav-item " id="user_index">
        <a href="/user/index">
            <i class="layui-icon">&#xe612;</i>
            个人中心
        </a>
    </li>

    <li class="layui-nav-item" id="user_friend">
        <a href="{{ url('/user/userFriend') }}">
            <i class="layui-icon">&#xe613;</i>
            我的好友
        </a>
    </li>

    <li class="layui-nav-item" id="user_msg">
        <a href="{{ url('/user/userMessage') }}">
            <i class="layui-icon">&#xe611;</i>
            我的消息
        </a>
    </li>
</ul>
