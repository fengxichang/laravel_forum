<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follow';
    public $timestamps = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo('App\Model\User','user_id');
    }
}
