<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 23:20
 */

namespace App\Constracts;

interface IPost
{
    public function insertPostData($request);

    public function getPostDetail($id);

    public function saveComment($data);

    public function saveReplyComment($data);

    public function commentPraise($id);

    public function postPraise($id);

    public function getAllPost();
}