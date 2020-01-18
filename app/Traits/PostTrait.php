<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/4
 * Time: 21:54
 */

namespace App\Traits;


trait PostTrait
{
    public function lableData()
    {
        return [
            ["value" => 1, "text" => "学习"],
            ["value" => 2, "text" => "影视"],
            ["value" => 3, "text" => "游戏"],
            ["value" => 4, "text" => "好玩"],
            ["value" => 5, "text" => "吃货"],
            ["value" => 6, "text" => "其它"],
        ];
    }

    public function colData()
    {
        return [
            ["value" => 1, "text" => "讨论"],
            ["value" => 2, "text" => "问答"],
            ["value" => 3, "text" => "分享"]
        ];
    }
    public function valueToText($val,$data)
    {
        foreach($data as $k =>$v){
            if($v['value']==$val){
                $val = $v['text'];
            }
        }
        return $val;
    }
}