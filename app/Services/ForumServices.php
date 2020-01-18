<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/4
 * Time: 21:40
 */

namespace App\Services;

use App\Constracts\IForum;
use App\Model\Post;
use App\Model\Col;
use App\Model\Lable;
use Illuminate\Support\Facades\Auth;

class ForumServices implements IForum
{
    private $peoPost;
    private $peoCol;
    private $peoLable;

    public function __construct(
        Post $oPost,
        Col $oCol,
        Lable $oLable
    )
    {
        $this->peoPost = $oPost;
        $this->peoCol = $oCol;
        $this->peoLable = $oLable;
    }

    public function getAllPostData()
    {
        $allPostData = $this->peoPost->all();
        return $allPostData;
    }

    public function getPostDataById($id)
    {
        $posts = $this->peoPost->where('id', $id)->get();
        return $posts;
    }

    public function getDayDiscussPost()
    {
        $day = date('Y-m-d H:i:s',(time()-60*60*24));
        $post = $this->peoPost->with('user')
            ->withCount(['comment' => function($query) use($day){
                $query->whereBetween('created_at',[$day, date('Y-m-d H:i:s')]);
            }])
            ->orderBy('comment_count','desc')
            ->take(7)
            ->get();
        return $post;
    }

    public function getWeekDiscussPost()
    {
        $week = date('Y-m-d H:i:s',(time()-60*60*24*7));
        $post = $this->peoPost->with('user')
            ->withCount(['comment' => function($query) use($week){
                $query->whereBetween('created_at',[$week, date('Y-m-d H:i:s')]);
            }])
            ->orderBy('comment_count','desc')
            ->get();
        return $post;
    }

    public function getCols()
    {
        $cols = $this->peoCol->all();
        return $cols;
    }

//    public function getPostBycolumnName($column_id)
//    {
//        $posts = $this->peoPost
//            ->where('col_id', $column_id)
//            ->orderBy('created_at')
//            ->get();
//        foreach($posts as $PostData){
//            $PostData->lable_id = $this->peoPost->valueToText($PostData->lable_id,$this->peoPost->lableData());
//            $last_comment = $PostData->comment()->with("user")->orderBy("created_at", "desc")->first();
//            $PostData->last_comment_user = !empty($last_comment->user->username) ? $last_comment->user->username : null;
//        }
//        return $posts;
//    }

    public function getLable($col_id)
    {
        $lables = $this->peoLable->where('col_id', $col_id)->get();
        return $lables;
    }

    public function getAllLable()
    {
        $lables = $this->peoLable->withCount(['post'])->get();
        return $lables;
    }

    public function getPostPaginate($page, $orderBy, $col_id, $lable_id, $keyword)
    {
        $lable_collect = Auth::guard('user')->check() ? Auth::guard('user')->user()->lable_collect : null;
        if (!empty($col_id)) {
            $posts = $this->peoPost
                ->with('user')
                ->with('lable')
                ->with("comment")
                ->where('col_id', $col_id)
                ->orderBy('created_at')
                ->offset($page)
                ->limit(10)
                ->get();
            $posts_count = $this->peoPost
                ->where('col_id', $col_id)
                ->get()
                ->count();
            $posts = $this->postService($posts);
            return [$posts, $posts_count];
        }
        if (!empty($lable_id)) {
            $posts = $this->peoPost
                ->with('user')
                ->with('lable')
                ->with("comment")
                ->where('lable_id', $lable_id)
                ->orderBy('created_at')
                ->offset($page)
                ->limit(10)
                ->get();
            $posts_count = $this->peoPost
                ->where('lable_id', $lable_id)
                ->get()
                ->count();
            $posts = $this->postService($posts);
            return [$posts, $posts_count];
        }
        if(!empty($keyword)) {
            $posts = $this->peoPost
                ->with('user')
                ->with('lable')
                ->with("comment")
                ->where('title','like', '%'.$keyword.'%')
                ->orWhere('content', 'like', '%'.$keyword.'%')
                ->orderBy('created_at')
                ->offset($page)
                ->limit(10)
                ->get();
            $posts_count = $this->peoPost
                ->where('title','like', '%'.$keyword.'%')
                ->get()
                ->count();
            $posts = $this->postService($posts);
            return [$posts, $posts_count];
        }
        if ($orderBy == 'newest') {
            $posts = $this->peoPost
                ->with('user')
                ->with('lable')
                ->with("comment")
                ->orderBy("created_at","desc")
                ->offset($page)
                ->limit(10)
                ->get();
            $posts_count = $this->peoPost
                ->get()
                ->count();
            $posts = $this->postService($posts);
            return [$posts, $posts_count];
        } elseif ($orderBy == 'hottest') {
            $week = date('Y-m-d H:i:s',(time()-60*60*24*7));
            $posts = $this->peoPost->with('user')
                ->withCount(['comment' => function($query) use($week){
                    $query->whereBetween('created_at',[$week, date('Y-m-d H:i:s')]);
                }])
                ->with('lable')
                ->with("comment")
                ->orderBy('comment_count','desc')
                ->offset($page)
                ->limit(10)
                ->get();

            $posts_count = $this->peoPost->with('user')
                ->withCount(['comment' => function($query) use($week){
                    $query->whereBetween('created_at',[$week, date('Y-m-d H:i:s')]);
                }])
                ->get()
                ->count();
            $posts = $this->postService($posts);
            return [$posts, $posts_count];
        } elseif ($orderBy == 'lableCollect') {
            $posts = $this->peoPost->with('user')
                ->with('lable')
                ->with('comment')
                ->whereIn('lable_id', explode(',', $lable_collect))
                ->orderBy("created_at","desc")
                ->offset($page)
                ->limit(10)
                ->get();
            $posts_count = $this->peoPost->with('user')
                ->whereIn('lable_id', explode(',', $lable_collect))
                ->get()
                ->count();
            $posts = $this->postService($posts);
            return [$posts, $posts_count];
        }
    }

    public function postService($posts)
    {
        foreach($posts as $post) {
            if(time()-strtotime($post->created_at) > 60*60*24*30) {
                $post->_created_at = '1个月之前';
            } elseif (time()-strtotime($post->created_at) > 60*60*24*7) {
                $post->_created_at = '1周前';
            } elseif (time()-strtotime($post->created_at) > 60*60*24) {
                $post->_created_at = floor((time()-strtotime($post->created_at))/60/60/24).'天前';
            } elseif (time()-strtotime($post->created_at) > 60*60) {
                $post->_created_at = floor((time()-strtotime($post->created_at))/60/60).'小时前';
            } elseif (time()-strtotime($post->created_at) > 60) {
                $post->_created_at = floor((time()-strtotime($post->created_at))/60).'分钟前';
            } else {
                $post->_created_at = '刚刚';
            }
        }
        foreach($posts as $PostData){
            $last_comment = $PostData->comment()->with("user")->orderBy("created_at", "desc")->first();
            $PostData->last_comment_user = !empty($last_comment->user->username) ? $last_comment->user->username : '';
            $PostData->comment_count = $PostData->comment->count();
        }
        return $posts;
    }
}