<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    public $timestamps = false;
    protected $keyType = 'string';

    public function post()
    {
        return $this->belongsTo('App\Model\Post','post_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User','user_id');
    }

    public function parentComment()
    {
        return $this->belongsTo('App\Model\Comment', 'height_id');
    }
}
