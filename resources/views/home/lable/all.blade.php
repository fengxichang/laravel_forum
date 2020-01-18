<div class="fly-panel-title fly-filter" style="border-bottom: 1px solid #EBEBEB; height: 36px; line-height: 40px;">
    <a href="?orderBy=newest" @if (strpos($_SERVER['REQUEST_URI'], 'newest')) class="layui-this order" @endif>按最新</a>
    <span class="fly-mid"></span>
    <a href="?orderBy=hottest" @if (strpos($_SERVER['REQUEST_URI'], 'hottest')) class="layui-this order" @endif>按热议</a>
    @if(Auth::guard('user')->check())
        <span class="fly-mid"></span>
        <a href="?orderBy=lableCollect" @if (strpos($_SERVER['REQUEST_URI'], 'lableCollect')) class="layui-this order" @endif>关注</a>
    @endif
</div>