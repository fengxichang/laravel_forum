<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Lable extends Model
{
    protected $table = "lable";
    public $timestamps = false;

    public function post()
    {
        return $this->hasMany('App\Model\Post','lable_id');
    }

    public function col()
    {
        return $this->belongsTo('App\Model\Col', 'col_id');
    }
}
