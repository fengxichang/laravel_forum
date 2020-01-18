<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User as BaseUser;
class User extends BaseUser
{
    protected $table = "user";

    public function post()
    {
        return $this->hasMany('App\Model\Post','user_id');
    }

    public function comment()
    {
        return $this->hasMany('App\Model\Comment','user_id');
    }

    public function follow()
    {
        return $this->hasMany('App\Model\Follow', 'user_id');
    }

    public function message()
    {
        return $this->hasMany('App\Model\Message', 'user_id');
    }
}
