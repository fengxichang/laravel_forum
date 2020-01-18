<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PostTrait;

class Post extends Model
{
    use PostTrait;
    protected $table = "post";

    protected $timestamp = false;

    public function user()
    {
        return $this->belongsTo('App\Model\User','user_id');
    }

    public function comment()
    {
        return $this->hasMany('App\Model\Comment','post_id');
    }

    public function lable()
    {
        return $this->belongsTo('App\Model\Lable', 'lable_id');
    }

    public function col()
    {
        return $this->belongsTo('App\Model\col', 'col_id');
    }
}
