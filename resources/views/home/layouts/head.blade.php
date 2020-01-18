<!DOCTYPE html>
<html >
<head>
    <title>听说</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="fly,layui,听说社区">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('Content/Layui-KnifeZ/css/layui.css') }}">
    <link rel="stylesheet" href="{{ url('/css/global.css') }}">
    <link rel="stylesheet" href="{{ url('/jqcloud/jqcloud.css') }}">
    <link rel="stylesheet" href="{{ url('/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    {{--<link rel="stylesheet" href="/css/bootstrap.min.css">--}}
    <style type="text/css">
        textarea img {
            width: 200px;
            height:300px;
        }
        .layui-util-face {
            margin-right: auto;
            margin-left: auto;
        }
        div.jqcloud span.vertical {
            -webkit-writing-mode: vertical-rl;
            writing-mode: tb-rl;
        }
        .icon_search {
            border-bottom-right-radius: 11px;
            border-top-right-radius: 11px;
            font-size: 26px;
            background-color: white;
            color: #3B3B3B;
            position: absolute;
            left: 238px;
            top:10px
        }
        .icon_search:hover {
            cursor:pointer
        }
        .search_input {
            margin-top:10px;
            text-indent: 5px;
            height:31px;
            width: 270px;
            /*border-top-left-radius: 12px;*/
            /*border-bottom-left-radius: 12px;*/
            border-radius: 4px;
            outline: none;
            border: 1px solid #DBDBDB
        }
        .focus_style {
            margin-top:10px;
            text-indent: 5px;
            height:31px;
            width: 270px;
            background-color: white;
            /*border-top-left-radius: 12px;*/
            /*border-bottom-left-radius: 12px;*/
            border-radius: 4px;
            outline: none;
            border: 1px solid #ABABAB;
        }


    </style>
</head>
<body bgcolor="#e2e2e2" style="margin: 0px; height: 100%; width: 100%">
@inject("oAuth",'Illuminate\Support\Facades\Auth')
<div class="fly-header" style="border:none; height: 49px; background-color: white; border-bottom: 1px solid #e2e2e2; box-shadow: 0 2px 3px rgba(0,0,0,.1);">
    <div class="layui-container " style=" height: 49px; background-color: white;">
        <a class="fly-logo" href="/" style="color:#434343; font-size:30px; font-weight: bolder; font-family: SimSun;">
            听说
        </a>

        <ul class="layui-nav fly-nav layui-hide-xs" style="margin-left: 150px">
            <li class="layui-hide-xs  layui-show-md-inline-block">
                <input class="search_input" onkeypress="indexSearch()" id="search_input_" type="text" name="search" />
            </li>
            <i class="fa fa-search icon_search" id="search_icon" onclick="indexSearch()" style="font-size: large;margin: 5px;"></i>
        </ul>

        <ul class="layui-nav fly-nav-user" style="margin-top: -3px">
            <!-- 未登入的状态 -->
            @if(Auth::guard("user")->check())
                <li class="layui-nav-item">
                    <a class="fly-nav-avatar" href="javascript:;" >
                        <cite class="" style="margin-bottom: 8px;color:#434343">{{ Auth::guard("user")->user()->username }}</cite>
                        <img src="{{ Auth::guard("user")->user()->avatar }}" id="touxiang" style="margin-bottom: 8px">
                    </a>
                    <dl class="layui-nav-child" style="margin-top: -15px">
                        <dd><a href="{{ url('/user/set_view') }}"><i class="fa fa-cog" style="font-size: 19px"></i>基本设置</a></dd>
                        <dd><a href="{{ url('/user/index') }}"><i class="fa fa-user" style="margin-left: 2px; font-size: 18px;"></i>个人中心</a></dd>
                        <dd><a href="{{ url('/user/userFriend') }}"><i class="fa fa-users" style="margin-left: 2px; font-size: 16px;"></i>我的好友</a></dd>
                        <dd><a href="{{ url('/user/userMessage') }}"><i class="fa fa-envelope" style="font-size: 15px"></i>我的消息</a></dd>

                        <hr style="margin: 5px 0;">
                        <dd class="layui-hide-md"><a href="{{ url('/post/addPostView') }}"><i class="fa fa-pencil-square-o" style="margin-left: 2px; font-size: 21px;"></i>发表话题</a></dd>
                        <dd><a href="/user/logout/" style="text-align: center;">退出</a></dd>
                    </dl>
                </li>
            @else
                <li class="layui-nav-item">
                    <a href="/app/qq/" onclick="layer.msg('正在通过Github登入', {icon:16, shade: 0.1, time:0})" title="Github登入" style="font-size:20px;color:#434343">
                        <i class="fa fa-github" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="layui-nav-item">
                    <a href="{{ url('/login_view') }}" style="color:#434343">登入</a>
                </li>
                <li class="layui-nav-item">
                    <a href="/reg_view" style="color:#434343">注册</a>
                </li>

                {{--<li class="layui-nav-item">--}}
                    {{--<a href="/app/qq/" onclick="layer.msg('正在通过QQ登入', {icon:16, shade: 0.1, time:0})" title="QQ登入" class="layui-icon layui-icon-login-qq" style="font-size:18px;color:#434343"></a>--}}
                {{--</li>--}}
                {{--<li class="layui-nav-item">--}}
                    {{--<a href="/app/weibo/" onclick="layer.msg('正在通过微信登入', {icon:16, shade: 0.1, time:0})" title="微信登入" class="layui-icon layui-icon-login-wechat" style="font-size:18px;color:#434343"></a>--}}
                {{--</li>--}}
                {{--<li class="layui-nav-item">--}}
                    {{--<a href="/app/weibo/" onclick="layer.msg('正在通过微博登入', {icon:16, shade: 0.1, time:0})" title="新浪微博登入" class="layui-icon layui-icon-login-weibo" style="font-size:18px;color:#434343"></a>--}}
                {{--</li>--}}
            @endif
            <!-- 登入后的状态 -->

        </ul>
    </div>
</div>

@yield("content")

@if(!strpos($_SERVER['REQUEST_URI'], 'login_view') && !strpos($_SERVER['REQUEST_URI'], 'reg_view'))
<div class="fly-footer" style="float: bottom;padding-top: 0px">
    <p><a href="http://fly.layui.com/" target="_blank">听说 社区</a> 2018 &copy; <a href="http://www.layui.com/" target="_blank">Neil.Feng</a></p>
</div>
@endif
<script src="{{ url('/js/jquery.min.js') }}"></script>
{{--<script src="/layedit.js"></script>--}}
<script src="{{ url('Content/Layui-KnifeZ/layui.js') }}"></script>
<script src="{{ url('Content/ace/ace.js') }}"></script>
<script src="{{ url('/js/bootstrap.min.js') }}"></script>
<script src="{{ url('/jqcloud/jqcloud-1.0.4.min.js') }}"></script>

@yield('javascript')

<script type="text/javascript">
    layui.use('element', function(){
        var element = layui.element;
    });

    var url = window.location.href;
    //个人中心侧边栏显示
    if(url.indexOf("http://www.laravel-forum-app.com/user/index") !== -1){
        $("#user_index").attr("class","layui-nav-item layui-this");
    }
    if(url=="http://www.laravel-forum-app.com/user/set_view"){
        $("#user_set").attr("class","layui-nav-item layui-this");
    }
    if(url.indexOf("http://www.laravel-forum-app.com/user/userMessage") !== -1){
        $("#user_msg").attr("class","layui-nav-item layui-this");
    }
    if(url.indexOf("http://www.laravel-forum-app.com/user/userFriend") !== -1){
        $("#user_friend").attr("class","layui-nav-item layui-this");
    }

    @if($_SERVER['REQUEST_URI'] == '/' ||
    strpos($_SERVER['REQUEST_URI'], 'colIndex') ||
    strpos($_SERVER['REQUEST_URI'], 'postDetail') ||
    strpos($_SERVER['REQUEST_URI'], 'order') ||
    strpos($_SERVER['REQUEST_URI'], 'indexSearch') ||
    strpos($_SERVER['REQUEST_URI'], 'lableIndex'))

    $(document).ready(function(){
        //鼠标聚焦时搜索框样式改变
        $("#search_input_").focus(function () {
            $("#search_input_").removeClass('search_input');
            $("#search_input_").addClass('focus_style');
        });
        $("#search_input_").blur(function () {
            $("#search_input_").removeClass('focus_style');
            $("#search_input_").addClass('search_input');
        });

        //词云
        var res = [
            @foreach($all_lables as $lable)
                {text: "{{ $lable->lable_name }}", weight: parseInt("{{ $lable->post_count }}")*10/parseInt("{{ $sum_post }}"), id: "{{ $lable->id }}"},
            @endforeach
        ];
        var array = new Array();
        lable = new Array();
        for (i = 0; i < res.length; i++) {
            obj = new Object();
            obj.text = res[i].text;
            obj.weight = res[i].weight;
            lable[res[i].text] = res[i].id;
            // obj.link = "/layerMsg";
            obj.html = {style: "cursor:pointer"};
            obj.link = "/lableIndex?id="+res[i].id;
            array.push(obj);
        }
        $("#cloud").jQCloud(array,{
            delayedMode: true,
            // shape: 'rectangular'
        });
    });
    @endif
    $(document).ready(function(){

        var fail_message = $("#fail_message");
        //提交登陆表单
        $("#login_form").submit(function(event){
            event.preventDefault();
            var form = $(this);
            $.ajax({
                url:form.attr("action"),
                type:form.attr("method"),
                data:form.serialize(),
                dataType:"",
                success:function(e){
                    self.location = document.referrer;
                },
                error:function(e){
                    fail_message.html(e.responseJSON);
                    fail_message.show();
                }
            });
        });

        //提交注册表单
        $("#reg_form").submit(function(event){
            event.preventDefault();
            var form = $(this);
            $.ajax({
                url:form.attr("action"),
                type:form.attr("method"),
                data:form.serialize(),
                dataType:"",
                success:function(e){
                    location.href = "{{ url('/') }}"
                },
                error:function(e){
                    $("#email_fail_msg").html(e.responseJSON["email"]);
                    $("#email_fail_msg").show();
                    $("#username_fail_msg").html(e.responseJSON["username"]);
                    $("#username_fail_msg").show();
                    $("#password_fail_msg").html(e.responseJSON["password"]);
                    $("#password_fail_msg").show();
                }
            });
        });
        //提交设置用户信息表单
        $("#user_set_form").submit(function(event){
            event.preventDefault();
            var form = $(this);
            $.ajax({
                url:form.attr("action"),
                type:form.attr("method"),
                data:form.serialize(),
                dataType:"",
                success:function(e){
                    layer.msg('修改成功',{
                        time: 1000,
                    });
                },
                error:function(e){
                    var msg = e.responseJSON;
                    layer.msg(msg,{
                        time: 1000,
                    });
                }
            });
        });
        layui.use('upload', function() {
            var $ = layui.jquery;
            var upload = layui.upload;
            var tag_token = $(".tag_token").val();
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#test1',
                accept:"images",
                field:"file",
                data:{
                    imageName:"touxiang",
                    '_token':tag_token
                },
                url: "{{ url('/user/upload_avatar') }}",
                before: function (obj) {
                    //预读本地文件示例
                    obj.preview(function (index, file, result) {
                        $('#demo1').attr('src', result);
                        $("#touxiang").attr("src",result);//图片链接（base64）
                    });
                },
                done: function (res) {
                    layer.msg(res.msg);

                },
                error: function () {
                    //演示失败状态，并实现重传
                    var demoText = $('#demoText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function () {
                        uploadInst.upload();
                    });
                }
            });
        });
    });
</script>
<script type="text/javascript">

    $("document").ready(function(){



        //发表帖子
        layui.use(['layedit','layer','jquery'], function() {
            var layedit = layui.layedit,
                $ = layui.jquery;
            layedit.set({
                uploadImage: {
                    url: "{{ url('/post/avatarSave') }}"
                    ,type: 'post',
                },
                uploadVideo: {
                    url: '{{ url('/post/videoSave') }}',
                    accept: 'video',
                    acceptMime: 'video/*',
                    exts: 'mp4|flv|avi|rm|rmvb',
                    size: '20480'
                },
                height: '55%'
            });
            var index = layedit.build('LAY_demo1', {
                tool: [
                    'undo', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|', 'fontFomatt', 'fontBackColor', 'colorpicker', 'face'
                    , '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'images', 'image_alt', 'video','attachment'
                    , 'table'
                    // , 'fullScreen'
                ]
            });
        });



        $("#postBody").html($("#hidePost").text());
        //发表帖子时选择栏目和标签
        layui.use(['form'], function() {
            form=layui.form;
            form.on('select(col_)', function(data){
                var val=data.value;
                $.ajax({
                    url: "{{ url('/listLable') }}",
                    data: { "col_id" : val },
                    type: "GET",
                    dataType: "json",
                    success: function(e){
                        $(".lable_ option").remove();
                        var t = '';
                        for(var i=0; i<e.length; i++) {
                            t = '<option value="'+e[i].id+'"'+'>'+e[i].lable_name+'</option>' + t;
                        }
                        $(".lable_").append(t);
                        layui.use('form', function(){
                            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
                            form.render();
                            form.render('select', 'lable_');
                        });
                    },
                    error: function(e) {
                        alert('error');
                    }
                })
            });
        });

    });
</script>

<script type="text/javascript">
    //修改密码
    $("#changePasswordForm").submit(function(event){
        event.preventDefault();
        var _form = $(this);
        $.ajax({
            url:_form.attr("action"),
            data:_form.serialize(),
            dataType:"",
            type:_form.attr("method"),
            success:function(e){
                layer.msg("修改密码成功");
            },
            error:function(e){
                e.responseJSON.oldPwdWrong ? layer.msg(e.responseJSON.oldPwdWrong) : null;
                e.responseJSON.reNewWrong ? layer.msg(e.responseJSON.reNewWrong) : null;
                // $("#oldPwdWrong").html();
                // $("#reNewWrong").html(e.responseJSON.reNewWrong);
                // console.log(e);
            }
        })
    });

    //提交评论
    $("#commentForm").submit(function(event){
        event.preventDefault();
        var _form = $(this);
        $.ajax({
            url: _form.attr('action'),
            data: _form.serialize(),
            dataType: '',
            type: _form.attr('method'),
            success: function(e){
                $("#comment").prepend(
                    '<li data-id="111" class="jieda-daan" style="padding-top: 10px; border-bottom: 1px solid #DFDFDF;">'+
                    '<a name="item-1111111111"></a>'+
                    '<div class="detail-about detail-about-reply">'+
                    '<a class="fly-avatar" href="">'+
                    '<img style="border-radius: 16px;width: 35px;height: 35px" src="{{ !empty($oAuth::guard('user')->user()->avatar)?$oAuth::guard('user')->user()->avatar:null  }}"></a>'+
                    '<div class="fly-detail-user">'+
                    '<a href="" class="fly-link">'+
                    '<cite>{{ !empty($oAuth::guard('user')->user()->username)?$oAuth::guard('user')->user()->username:null }}</cite></a></div>'+
                    '<div class="detail-hits">'+
                    '<span>'+e.created_at+'</span></div></div>'+
                    '<div class="detail-body jieda-body photos" style="margin-top:10px; margin-bottom:0px">'+
                    '<p >'+e.content+'</p></div>'+
                    ''
                );
                $("#L_content").val("");
                $(".post_comment").hide();
            },
            error: function(e){
                alert('提交失败');
            }
        })
    });

    //回复评论
    $(".replyCommentForm").submit(function(event){
        event.preventDefault();
        var _form = $(this);
        $.ajax({
            url: _form.attr('action'),
            data: _form.serialize(),
            dataType: 'json',
            type: _form.attr('method'),
            success: function(e){
                $("#comment").prepend(
                    '<li data-id="111" class="jieda-daan" style="padding-top: 10px; border-bottom: 1px solid #DFDFDF;">'+
                    '<a name="item-1111111111"></a>'+
                    '<div class="detail-about detail-about-reply">'+
                    '<a class="fly-avatar" href="">'+
                    '<img style="border-radius: 24px;width: 35px;height: 35px" src="{{ !empty($oAuth::guard('user')->user()->avatar)?$oAuth::guard('user')->user()->avatar:null  }}"></a>'+
                    '<div class="fly-detail-user">'+
                    '<a href="" class="fly-link">'+
                    '<cite>{{ !empty($oAuth::guard('user')->user()->username)?$oAuth::guard('user')->user()->username:null }}</cite></a></div>'+
                    '<div class="detail-hits">'+
                    '<span>'+e.created_at+'</span><a href="" class="fly-link">'+ e.comment_username+'</a></div></div>'+
                    '<div class="detail-body jieda-body photos" style="margin-top:10px; margin-bottom:0px">'+
                    '<p >'+e.content+'</p></div>'+
                    ''
                );
                layer.msg("回复成功");
            },
            error: function(e){
                alert('提交失败');
            }
        })
    });

    //给文章点赞
    $("#post_praise").click(function(){
        var id = $("#post_id").val();
        $.ajax({
            url: "{{ url('/PostPraise') }}",
            data: {id: id},
            dataType: "",
            type: "post",
            success: function(e){
                $("#post_praise").html(e);
            },
            error: function(e){
                window.location.href = "/login_view";
            }
        })
    });

    $(".layedit-tool-face").on("click",function(){
        alert(23);
        $(".layui-util-face").css("margin-left","auto");
        $(".layui-util-face").css("margin-right","auto");
    })

</script>

<script>
    $(document).ready(function(){
        // var openid = "{$openid}";
        // var token = "{$token}";
        orderBy = 'newest';
        col_id = null;
        lable_id = null;
        keyword = null;
        @if (strpos($_SERVER['REQUEST_URI'], 'hottest'))
            orderBy = 'hottest';
        @endif
        @if (strpos($_SERVER['REQUEST_URI'], 'lableCollect'))
            orderBy = 'lableCollect';
        @endif
        @if (strpos($_SERVER['REQUEST_URI'], 'lableIndex'))
            lable_id = "{{explode('=',parse_url($_SERVER['REQUEST_URI'])['query'])[1]}}";
        @endif
        @if (strpos($_SERVER['REQUEST_URI'], 'colIndex'))
            col_id = "{{explode('=',parse_url($_SERVER['REQUEST_URI'])['query'])[1]}}";
        @endif
        @if (strpos($_SERVER['REQUEST_URI'], 'indexSearch'))
            keyword = "{{explode('=',parse_url($_SERVER['REQUEST_URI'])['query'])[1]}}";
        @endif
        page = 1;
        layui.use('flow', function () {
            var $ = layui.jquery; //不用额外加载jQuery，flow模块本身是有依赖jQuery的，直接用即可。
            var flow = layui.flow;
            flow.load({
                elem: '.lr' //指定列表容器
                , isAuto: true,
                scrollElem: '.lr', done: function (page, next) {
                    var url = "{{ url('/flowListPost') }}";
                    //传递的参数
                    var data = {
                        // openid: openid,
                        // token: token,
                        keyword: keyword,
                        lable_id: lable_id,
                        col_id: col_id,
                        orderBy: orderBy,
                        page: page
                    }
                    var lis = [];
                    $.get(url, data, function (res) {
                        console.log(res);
                        var list = res.data.list;
                        var sum = res.data.all_page;
                        var list1 = '';
                        var word = "要跳转的html";
                        for (var index = 0; index < list.length; index++) {
                            var l = list[index];
                            var content = "<li style=\"border-bottom: 1px solid #EBEBEB; padding-top: 12px; height: auto\">\n" +
                                "            <a href=\"/authorInfo?id=" + l.user.id + "\" class=\" layui-circle\" style=\"\">\n" +
                                "              <img class=\"fly-avatar \" src=\""+ l.user.avatar +"\" alt=\"贤心\" style=\"margin-top: -3px; width: 49px;height: 49px; border-radius:4px;\">\n" +
                                "            </a>\n" +
                                "            <h3 style=\"width: 92%; color: #8F8F8F\">\n" +
                                "              <a class=\"post_title\" style=\"color: #636363\" href=\"/postDetail?id="+ l.id +"\">"+ l.title +"</a>\n" +
                                "            </h3>\n" +
                                "            <div class=\"fly-list-info\">\n" +
                                "                <a href=\"/lableIndex?id="+ l.lable.id +"\" style=\"padding: 0px\">\n" +
                                "                    <div class=\"label\">"+ l.lable.lable_name +"</div>\n" +
                                "                </a>\n" +
                                "                <span class=\"layui-badge-dot dot\"></span>\n" +
                                "                <a href=\"/authorInfo?id="+ l.user.id +"\" style=\"padding: 0px; color: #696969;font-weight: 550; font: Georgia; font-size: 12px\">\n" +
                                "                <cite>"+ l.user.username +"</cite>\n" +
                                "               </a>\n" +
                                "                <span class=\"layui-badge-dot dot\"></span>\n" +
                                "                    <span style=\"padding: 0px\">"+l._created_at+"</span>\n" +
                                "                <span class=\"layui-badge-dot dot layui-hide-xs\"></span>\n" +
                                "                <span style=\"font-size: 12px\" class=\"layui-hide-xs\">\n" +
                                "                    最后回复&nbsp;<span style=\"padding: 0px; color: #696969;font-weight: 550; font: Georgia; font-size: 12px\">"+ l.last_comment_user +"</span>\n" +
                                "                </span>\n" +
                                "              <span></span>\n" +
                                "\n" +
                                "                <span class=\"fly-list-nums\">\n" +
                                "                  <a href=\"/postDetail?id="+ l.id +"\" style=\"padding: 0px\">\n" +
                                "                      <span title=\"评论数\" style=\"font-size: 17px; color: #F1ACA2\">"+ l.comment_count +"</span>"+"<span title=\"点击数\" style=\"font-size: 16px; color: #838E94\">"+" / "+ l.visited+ "</span>" +
                                "                  </a>\n" +
                                "                </span>\n" +
                                "            </div>\n" +
                                "            <div class=\"fly-list-badge\">\n" +
                                "              <!--<span class=\"layui-badge layui-bg-red\">精帖</span>-->\n" +
                                "            </div>\n" +
                                "          </li>";
                            lis.push(content);
                        }
                        //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                        //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                        next(lis.join(''), page <= sum);
                    });
                }
            });
        })
    });

    //获取url参数
    function GetQueryString(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);//search,查询？后面的参数，并匹配正则
        if(r!=null) {
            return  unescape(r[2]);
        } else {
            return null;
        }

    }

    function indexSearch()
    {
        var keyword = $("#search_input_").val();
        window.location.href = '{{ url('/indexSearch?keyword=') }}'+keyword;
    }
</script>

</body>
</html>