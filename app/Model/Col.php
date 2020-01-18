<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Col extends Model
{
    protected $table = "col";

    public $timestamps = false;

    public function lable()
    {
        return $this->hasMany('App\Model\Lable', 'col_id');
    }

    public function post()
    {
        return $this->hasMany('App\Model\Post','col_id');
    }
}
