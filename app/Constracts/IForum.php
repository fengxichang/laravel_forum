<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 23:20
 */

namespace App\Constracts;

interface IForum
{
    public function getAllPostData();

    public function getDayDiscussPost();

    public function getWeekDiscussPost();

    public function getCols();

//    public function getPostBycolumnName($column_id);

    public function getLable($col_id);

    public function getAllLable();

    public function getPostPaginate($page, $orderBy, $col_id, $lable_id, $keyword);

    public function postService($posts);

    public function getPostDataById($id);

}