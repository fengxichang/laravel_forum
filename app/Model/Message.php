<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    public $timestamps = false;
    public $keyType = 'string';

    public function user()
    {
        return $this->belongsTo('App\Model\User','user_id');
    }

    public function parentMessage()
    {
        return $this->belongsTo('App\Model\Message', 'parent_id');
    }
}
