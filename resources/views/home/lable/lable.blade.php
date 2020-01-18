<div class="fly-panel-title fly-filter" style="border-bottom: 1px solid #EBEBEB; height: auto; line-height: 40px;">
    @foreach($lables as $lable)
        <a class="lable" style="font-style:italic;" href="/lableIndex?id={{$lable->id}}" @if (!strpos($_SERVER['REQUEST_URI'], 'hottest')) class=" order" @endif>{{ $lable->lable_name }}</a>
    <span class="fly-mid" style="margin:0px"></span>
    @endforeach
</div>